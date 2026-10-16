// Channel is injected by the page via window.AGORA_CHANNEL = 'auction-{id}'
const channelName = window.AGORA_CHANNEL || null;
const ADMIN_URLS = window.AGORA_ADMIN_URLS || {
    token: '/auction/get-token',
    userInfo: '/auction/user-info',
    bids: '/auction/bids',
};

let agoraClient = AgoraRTC.createClient({ mode: 'live', codec: 'vp8' });
let localTracks  = {};
let hostUid      = null;
let isJoined     = false;

const adminProfiles = {};
const adminPendingLookups = new Set();

function escapeHtml(s) {
    return String(s).replace(/[&<>"']/g, function(c) {
        return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'})[c];
    });
}

function adminProfileOf(uid) {
    return adminProfiles[uid] || {
        name: 'Loading…',
        initial: '?',
        color: 'hsl(' + ((uid * 47) % 360) + ', 35%, 55%)',
    };
}

async function fetchAdminProfiles(uids) {
    const need = uids.filter(function(u) { return !adminProfiles[u] && !adminPendingLookups.has(u); });
    if (!need.length) return;
    need.forEach(function(u) { adminPendingLookups.add(u); });
    try {
        const res = await fetch(ADMIN_URLS.userInfo + '?uids=' + need.join(','));
        const data = await res.json();
        Object.keys(data || {}).forEach(function(k) {
            adminProfiles[k] = data[k];
            renderAdminCardMeta(parseInt(k, 10));
        });
    } catch (err) {
        console.warn('[ADMIN] user-info failed:', err);
    } finally {
        need.forEach(function(u) { adminPendingLookups.delete(u); });
    }
}

function ensureAdminCard(uid, opts) {
    const container = document.getElementById('admin-participants');
    if (!container) return null;
    opts = opts || {};
    const id = 'admin-participant-' + uid;
    let card = document.getElementById(id);
    if (!card) {
        const prof = adminProfileOf(uid);
        card = document.createElement('div');
        card.id = id;
        card.dataset.uid = String(uid);
        card.style.cssText = 'padding:8px;margin-bottom:8px;background:#fff;border:1px solid #e1e1e1;border-radius:4px;font-size:13px;';

        const header = document.createElement('div');
        header.style.cssText = 'display:flex;align-items:center;gap:10px;';

        const slot = document.createElement('div');
        slot.id = 'admin-slot-' + uid;
        slot.style.cssText = 'width:40px;height:40px;border-radius:50%;overflow:hidden;flex-shrink:0;background:' + prof.color + ';color:#fff;display:flex;align-items:center;justify-content:center;font-weight:600;font-size:16px;';
        slot.textContent = prof.initial;
        header.appendChild(slot);

        const meta = document.createElement('div');
        meta.id = 'admin-meta-' + uid;
        meta.style.cssText = 'flex:1;min-width:0;';
        header.appendChild(meta);

        const videoBox = document.createElement('div');
        videoBox.id = 'admin-participant-video-' + uid;
        videoBox.style.cssText = 'width:100%;height:120px;background:#000;border-radius:3px;margin-top:8px;overflow:hidden;display:none;';
        card.appendChild(header);
        card.appendChild(videoBox);

        container.appendChild(card);
        if (!adminProfiles[uid]) fetchAdminProfiles([uid]);
    }
    if (opts.isHost) {
        card.style.background = '#fff5e6';
        card.style.borderColor = '#f0ad4e';
    }
    renderAdminCardMeta(uid, opts);
    return card;
}

function renderAdminCardMeta(uid, opts) {
    opts = opts || {};
    const meta = document.getElementById('admin-meta-' + uid);
    const slot = document.getElementById('admin-slot-' + uid);
    if (!meta) return;
    const prof = adminProfileOf(uid);
    const card = document.getElementById('admin-participant-' + uid);
    const hasVideo = card && card.dataset.hasVideo === '1';
    const isHost = opts.isHost || (card && card.dataset.isHost === '1');
    if (isHost && card) card.dataset.isHost = '1';
    if (slot && !hasVideo) {
        slot.textContent = prof.initial;
        slot.style.background = prof.color;
    }
    const suffix = isHost ? ' (Host • You)' : '';
    meta.innerHTML = '<div style="font-weight:600;color:#222;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">'
        + escapeHtml(prof.name) + suffix
        + '</div>'
        + '<div style="color:#999;font-size:11px;">uid ' + uid + ' • '
        + (hasVideo ? '<span style="color:#28a745;">camera on</span>' : 'no camera')
        + '</div>';
}

// Backwards-compatible shim so existing call sites still work.
function addAdminParticipant(uid, label, opts) {
    opts = opts || {};
    ensureAdminCard(uid, { isHost: /Host/.test(label || '') });
    if (opts.withVideo) {
        // user-published handler will mount the actual video — flip the flag.
        const card = document.getElementById('admin-participant-' + uid);
        if (card) card.dataset.hasVideo = '1';
    }
}

function removeAdminParticipant(uid) {
    const el = document.getElementById('admin-participant-' + uid);
    if (el) el.remove();
}

function mountAdminVideo(user) {
    const card = ensureAdminCard(user.uid, {});
    if (!card) return;
    card.dataset.hasVideo = '1';
    const box = document.getElementById('admin-participant-video-' + user.uid);
    if (box) {
        box.style.display = 'block';
        box.innerHTML = '';
        user.videoTrack.play(box, { fit: 'cover' });
    }
    renderAdminCardMeta(user.uid);
}

function unmountAdminVideo(uid) {
    const card = document.getElementById('admin-participant-' + uid);
    if (!card) return;
    card.dataset.hasVideo = '';
    const box = document.getElementById('admin-participant-video-' + uid);
    if (box) { box.innerHTML = ''; box.style.display = 'none'; }
    renderAdminCardMeta(uid);
}

function setStreamStatus(text, color) {
    const el = document.getElementById('admin-stream-status');
    if (el) { el.textContent = text; el.style.color = color || ''; }
}

async function joinVideo() {
    if (isJoined) return;
    if (!channelName) {
        alert('No auction channel configured. Save the auction first.');
        return;
    }

    const joinBtn  = document.getElementById('btn-admin-join');
    const leaveBtn = document.getElementById('btn-admin-leave');
    if (joinBtn)  joinBtn.disabled = true;
    setStreamStatus('Connecting…', '#f0ad4e');

    // Attach listeners BEFORE join() so we never miss an event from a viewer
    // who is already publishing when we join, or who publishes while we're
    // still setting up our local tracks.
    agoraClient.on('user-joined', (user) => {
        console.log('[ADMIN] user-joined:', user.uid);
        ensureAdminCard(user.uid, {});
    });
    agoraClient.on('user-left', (user) => {
        removeAdminParticipant(user.uid);
    });
    agoraClient.on('user-published', async (user, mediaType) => {
        console.log('[ADMIN] user-published:', user.uid, mediaType);
        try {
            await agoraClient.subscribe(user, mediaType);
        } catch (err) {
            console.warn('[ADMIN] subscribe failed:', err);
            return;
        }
        if (mediaType === 'video') {
            mountAdminVideo(user);
        }
        if (mediaType === 'audio') {
            user.audioTrack.play();
        }
    });
    agoraClient.on('user-unpublished', (user, mediaType) => {
        if (mediaType === 'video') {
            unmountAdminVideo(user.uid);
        }
    });

    // Listen for bid broadcasts from viewers in the same channel.
    agoraClient.on('stream-message', function(uid, payload) {
        let msg;
        try { msg = JSON.parse(new TextDecoder().decode(payload)); } catch (e) { return; }
        if (!msg || msg.type !== 'bid' || !msg.bid) return;
        applyAdminBid(msg.bid, msg.snapshot);
    });

    try {
        const res  = await fetch(ADMIN_URLS.token + '?channel=' + encodeURIComponent(channelName));
        const data = await res.json();

        hostUid = data.uid;
        await agoraClient.setClientRole('host');
        await agoraClient.join(data.appid, data.channel, data.token, data.uid);

        isJoined = true;
        fetchAdminProfiles([data.uid]);
        ensureAdminCard(data.uid, { isHost: true });
        setStreamStatus('LIVE', '#d9534f');
        if (joinBtn)  joinBtn.style.display  = 'none';
        if (leaveBtn) leaveBtn.style.display = '';

        // Backfill cards for users already in the channel (presence + any
        // already-publishing media — 'user-joined'/'user-published' won't replay).
        if (agoraClient.remoteUsers.length) {
            const uids = agoraClient.remoteUsers.map(u => u.uid);
            fetchAdminProfiles(uids);
            for (const remote of agoraClient.remoteUsers) {
                ensureAdminCard(remote.uid, {});
                if (remote.hasVideo) {
                    try {
                        await agoraClient.subscribe(remote, 'video');
                        mountAdminVideo(remote);
                    } catch (e) { console.warn('[ADMIN] backfill video subscribe failed:', e); }
                }
                if (remote.hasAudio) {
                    try {
                        await agoraClient.subscribe(remote, 'audio');
                        remote.audioTrack.play();
                    } catch (e) { console.warn('[ADMIN] backfill audio subscribe failed:', e); }
                }
            }
        }

        const localPlayer = document.getElementById('local-player');
        if (localPlayer) {
            localPlayer.innerHTML = '';
            localPlayer.style.display = 'block';
            localPlayer.style.background = '#000';
        }

        try {
            localTracks.audioTrack = await AgoraRTC.createMicrophoneAudioTrack();

            try {
                localTracks.videoTrack = await AgoraRTC.createCameraVideoTrack();
                localTracks.videoTrack.play(localPlayer || 'local-player', { fit: 'cover' });
                await agoraClient.publish([localTracks.audioTrack, localTracks.videoTrack]);
            } catch (videoErr) {
                console.warn('[ADMIN] Camera error, audio only:', videoErr);
                await agoraClient.publish([localTracks.audioTrack]);
                if (localPlayer) localPlayer.innerHTML = '<div style="color:#f0ad4e;padding:20px;text-align:center;"><i class="glyphicon glyphicon-warning-sign"></i> No camera — streaming audio only</div>';
            }
            showAdminMicControl();
        } catch (e) {
            console.error('[ADMIN] Track error:', e);
        }

    } catch (err) {
        console.error('[ADMIN] Join error:', err);
        setStreamStatus('Connection failed', '#d9534f');
        if (joinBtn) joinBtn.disabled = false;
        isJoined = false;
    }
}

function showAdminMicControl() {
    const btn = document.getElementById('btn-admin-mic');
    if (btn) {
        btn.style.display = '';
        btn.disabled = !localTracks.audioTrack;
    }
    const muteAll = document.getElementById('btn-admin-mute-all');
    if (muteAll) muteAll.style.display = '';
}

function hideAdminMicControl() {
    const btn = document.getElementById('btn-admin-mic');
    if (btn) btn.style.display = 'none';
    const muteAll = document.getElementById('btn-admin-mute-all');
    if (muteAll) {
        muteAll.style.display = 'none';
        muteAll.innerHTML = '<span class="glyphicon glyphicon-volume-off"></span> Mute All Viewers';
        muteAll.classList.remove('btn-warning');
        muteAll.classList.add('btn-default');
    }
    allViewersMuted = false;
}

async function toggleAdminMic() {
    if (!localTracks.audioTrack) return;
    const btn = document.getElementById('btn-admin-mic');
    const muted = !localTracks.audioTrack.muted;
    await localTracks.audioTrack.setMuted(muted);
    if (btn) {
        btn.innerHTML = muted
            ? '<span class="glyphicon glyphicon-volume-off"></span> Unmute'
            : '<span class="glyphicon glyphicon-volume-up"></span> Mute';
        btn.classList.toggle('btn-warning', muted);
        btn.classList.toggle('btn-default', !muted);
    }
}

let allViewersMuted = false;

function encodePayload(obj) {
    return new TextEncoder().encode(JSON.stringify(obj));
}

async function muteAllViewers() {
    if (!isJoined) return;
    const btn = document.getElementById('btn-admin-mute-all');
    if (btn) btn.disabled = true;

    allViewersMuted = !allViewersMuted;
    const action = allViewersMuted ? 'mute-all' : 'unmute-all';

    try {
        await agoraClient.sendStreamMessage(encodePayload({ type: action, from: hostUid }));
    } catch (err) {
        console.warn('[ADMIN] sendStreamMessage failed:', err);
        alert('Failed to broadcast: ' + (err && err.message ? err.message : err));
        allViewersMuted = !allViewersMuted; // revert
        if (btn) btn.disabled = false;
        return;
    }

    if (btn) {
        btn.innerHTML = allViewersMuted
            ? '<span class="glyphicon glyphicon-volume-up"></span> Unmute All Viewers'
            : '<span class="glyphicon glyphicon-volume-off"></span> Mute All Viewers';
        btn.classList.toggle('btn-warning', allViewersMuted);
        btn.classList.toggle('btn-default', !allViewersMuted);
        btn.disabled = false;
    }
}

async function leaveVideo() {
    if (!isJoined) return;

    await agoraClient.leave();
    isJoined = false;

    if (localTracks.videoTrack) { localTracks.videoTrack.stop(); localTracks.videoTrack.close(); }
    if (localTracks.audioTrack) { localTracks.audioTrack.stop(); localTracks.audioTrack.close(); }
    localTracks = {};
    hideAdminMicControl();

    const player = document.getElementById('local-player');
    if (player) {
        player.innerHTML = '<span style="color:#666;">Click <strong>Join as Host</strong> to start streaming</span>';
        player.style.display = 'flex';
        player.style.alignItems = 'center';
        player.style.justifyContent = 'center';
        player.style.background = '#1a1a1a';
    }

    const container = document.getElementById('admin-participants');
    if (container) container.innerHTML = '';

    const joinBtn  = document.getElementById('btn-admin-join');
    const leaveBtn = document.getElementById('btn-admin-leave');
    if (joinBtn)  { joinBtn.disabled = false; joinBtn.style.display = ''; }
    if (leaveBtn) leaveBtn.style.display = 'none';

    setStreamStatus('Offline', '#999');
}

// ----- Live bid feed (admin) -----

const adminSeenBidIds = new Set();

function formatAmdAdmin(n) {
    return '֏ ' + Number(n || 0).toLocaleString('en-US').replace(/,/g, '.');
}

function buildAdminBidLi(b, fresh) {
    const li = document.createElement('li');
    li.dataset.bidId = String(b.id);
    li.style.cssText = 'display:flex;align-items:center;justify-content:space-between;padding:8px 14px;border-bottom:1px solid #f0f0f0;font-size:13px;'
        + (fresh ? 'background:#fff3a8;transition:background 1.5s ease-out;' : '');
    const when = b.created_at ? new Date(b.created_at.replace(' ', 'T')).toLocaleTimeString([], { hour:'2-digit', minute:'2-digit' }) : '';
    li.innerHTML = '<span><strong>' + escapeHtml(b.name || 'Bidder') + '</strong>'
        + ' <span style="color:#999;font-size:11px;">#' + b.user_id + (when ? ' · ' + when : '') + '</span>'
        + '</span>'
        + '<span style="display:flex;align-items:center;gap:10px;">'
        +   '<span style="font-weight:700;color:#13B2AD;">' + formatAmdAdmin(b.amount) + '</span>'
        +   '<button type="button" class="btn btn-xs btn-success bid-sell-btn" onclick="sellToBid(' + b.id + ')" title="Sell to this bidder">✓ Sell</button>'
        + '</span>';
    if (fresh) {
        setTimeout(function() { li.style.background = '#fff'; }, 50);
    }
    return li;
}

function applyAdminSnapshot(snap) {
    if (!snap) return;
    const cur = document.getElementById('admin-bid-current');
    const nxt = document.getElementById('admin-bid-next');
    const count = document.getElementById('admin-bid-count');
    const feed = document.getElementById('admin-bid-feed');
    const empty = document.getElementById('admin-bid-empty');
    if (cur) cur.textContent = formatAmdAdmin(snap.current_price);
    if (nxt) nxt.textContent = formatAmdAdmin(snap.next_minimum);
    if (count) count.textContent = (snap.bids || []).length;
    if (!feed) return;
    feed.innerHTML = '';
    if (!snap.bids || !snap.bids.length) {
        if (empty) feed.appendChild(empty);
        else {
            const li = document.createElement('li');
            li.style.cssText = 'padding:14px;color:#999;font-size:13px;text-align:center;';
            li.textContent = 'Waiting for the first bid…';
            feed.appendChild(li);
        }
        return;
    }
    snap.bids.forEach(function(b) {
        adminSeenBidIds.add(b.id);
        feed.appendChild(buildAdminBidLi(b, false));
    });
}

function applyAdminBid(bid, snapshot) {
    if (!bid || adminSeenBidIds.has(bid.id)) return;
    adminSeenBidIds.add(bid.id);

    const feed = document.getElementById('admin-bid-feed');
    const empty = document.getElementById('admin-bid-empty');
    if (feed) {
        if (empty && empty.parentNode === feed) feed.removeChild(empty);
        feed.insertBefore(buildAdminBidLi(bid, true), feed.firstChild);
        const count = document.getElementById('admin-bid-count');
        if (count) count.textContent = feed.children.length;
    }
    if (snapshot) {
        // applyAdminSnapshot would re-render the whole list; just update the
        // header numbers and trust the in-place insert above.
        const cur = document.getElementById('admin-bid-current');
        const nxt = document.getElementById('admin-bid-next');
        if (cur) cur.textContent = formatAmdAdmin(snapshot.current_price);
        if (nxt) nxt.textContent = formatAmdAdmin(snapshot.next_minimum);
    } else {
        const cur = document.getElementById('admin-bid-current');
        if (cur) cur.textContent = formatAmdAdmin(bid.amount);
    }
}

async function refreshAdminBids() {
    const panel = document.querySelector('.admin-bid-panel');
    if (!panel) return;
    const auctionId = panel.dataset.auctionId;
    try {
        const res = await fetch(ADMIN_URLS.bids + '?auction_id=' + encodeURIComponent(auctionId));
        const data = await res.json();
        if (data && !data.error) {
            applyAdminSnapshot(data);
            if (data.closed && data.winner) {
                renderAdminClosed(data.winner);
            }
        }
    } catch (err) { console.warn('[ADMIN] bid snapshot failed:', err); }
}

function renderAdminClosed(winner) {
    const banner = document.getElementById('admin-winner-banner');
    const text   = document.getElementById('admin-winner-text');
    const actions = document.getElementById('admin-close-actions');
    if (banner && text) {
        text.textContent = winner
            ? ('Sold to ' + winner.name + ' for ' + formatAmdAdmin(winner.amount))
            : 'Auction closed (no winner)';
        banner.style.display = 'block';
    }
    if (actions) actions.style.display = 'none';
    document.querySelectorAll('.bid-sell-btn').forEach(function(b) { b.disabled = true; });
}

function getCsrfTokenAdmin() {
    const m = document.querySelector('meta[name="csrf-token"]');
    return m ? m.content : '';
}
function getCsrfParamAdmin() {
    const m = document.querySelector('meta[name="csrf-param"]');
    return m ? m.content : '_csrf-backend';
}

async function postCloseAuction(bidId) {
    const panel = document.querySelector('.admin-bid-panel');
    if (!panel) return;
    const auctionId = panel.dataset.auctionId;
    const btn = document.getElementById('btn-close-auction');
    if (btn) btn.disabled = true;
    document.querySelectorAll('.bid-sell-btn').forEach(function(b) { b.disabled = true; });

    try {
        const body = new URLSearchParams();
        body.set('auction_id', auctionId);
        if (bidId) body.set('bid_id', String(bidId));
        body.set(getCsrfParamAdmin(), getCsrfTokenAdmin());

        const res = await fetch(ADMIN_URLS.close, {
            method: 'POST',
            headers: {
                'X-CSRF-Token': getCsrfTokenAdmin(),
                'Content-Type': 'application/x-www-form-urlencoded',
                'Accept': 'application/json',
            },
            body: body.toString(),
        });
        const data = await res.json();
        if (!res.ok || data.error) {
            alert(data.error || ('Close failed (' + res.status + ')'));
            if (btn) btn.disabled = false;
            document.querySelectorAll('.bid-sell-btn').forEach(function(b) { b.disabled = false; });
            return;
        }
        // Render closed state locally.
        if (data.snapshot && data.snapshot.winner) {
            renderAdminClosed(data.snapshot.winner);
        }
        // Broadcast to all clients in the channel so viewers see the hammer.
        try {
            if (isJoined) {
                const payload = new TextEncoder().encode(JSON.stringify({
                    type:    'auction-ended',
                    snapshot: data.snapshot,
                }));
                await agoraClient.sendStreamMessage(payload);
            }
        } catch (e) {
            console.warn('[ADMIN] auction-ended broadcast failed (viewers will catch up on poll):', e);
        }
    } catch (err) {
        console.error('[ADMIN] close failed:', err);
        alert('Network error — please try again.');
        if (btn) btn.disabled = false;
        document.querySelectorAll('.bid-sell-btn').forEach(function(b) { b.disabled = false; });
    }
}

function closeAuctionToHighest() {
    if (!confirm('Close auction and sell to the highest bidder?')) return;
    postCloseAuction(null);
}

function sellToBid(bidId) {
    if (!confirm('Sell to this bidder?')) return;
    postCloseAuction(bidId);
}

document.addEventListener('DOMContentLoaded', refreshAdminBids);
setInterval(function() { if (document.querySelector('.admin-bid-panel')) refreshAdminBids(); }, 15000);
