// Channel is set by the page before this script runs via window.AGORA_CHANNEL
const channelName = window.AGORA_CHANNEL || 'default-room';
let agoraClient = AgoraRTC.createClient({ mode: 'live', codec: 'vp8' });
let hostInfo = { uid: null, name: 'Host' };
let hostIsPublishing = false;
let myLocalTracks = { video: null, audio: null };
let isPublishingSelf = false;

function setRemoteMessage(html) {
    const container = document.getElementById('remote-player');
    if (container) container.innerHTML = html;
}

function setStreamStatus(text) {
    const el = document.getElementById('stream-status');
    if (el) el.textContent = text;
}

const userProfiles = {};    // uid -> { name, initial, color }
const pendingLookups = new Set();

async function fetchUserProfiles(uids) {
    const need = uids.filter(u => !userProfiles[u] && !pendingLookups.has(u));
    if (!need.length) return;
    need.forEach(u => pendingLookups.add(u));
    try {
        const res = await fetch('/agora/user-info?uids=' + need.join(','));
        const data = await res.json();
        Object.keys(data || {}).forEach(k => {
            userProfiles[k] = data[k];
            renderParticipantBody(parseInt(k, 10));
        });
    } catch (err) {
        console.warn('[AGORA] user-info fetch failed:', err);
    } finally {
        need.forEach(u => pendingLookups.delete(u));
    }
}

function profileOf(uid) {
    return userProfiles[uid] || {
        name: 'Loading…',
        initial: '?',
        color: 'hsl(' + ((uid * 47) % 360) + ', 35%, 55%)',
    };
}

function ensureParticipantCard(uid, opts) {
    const container = document.getElementById('user-participants');
    if (!container) return null;
    opts = opts || {};
    const id = 'user-participant-' + uid;
    let card = document.getElementById(id);
    if (!card) {
        const prof = profileOf(uid);
        card = document.createElement('div');
        card.id = id;
        card.dataset.uid = String(uid);
        card.style.cssText = 'display:flex;align-items:center;gap:10px;padding:8px 10px;margin-bottom:6px;background:#fff;border:1px solid #e1e1e1;border-radius:6px;font-size:13px;';

        const slot = document.createElement('div');
        slot.id = 'slot-' + uid;
        slot.style.cssText = 'width:48px;height:48px;border-radius:50%;overflow:hidden;flex-shrink:0;position:relative;background:' + prof.color + ';color:#fff;display:flex;align-items:center;justify-content:center;font-weight:600;font-size:18px;';
        slot.textContent = prof.initial;
        card.appendChild(slot);

        const meta = document.createElement('div');
        meta.id = 'meta-' + uid;
        meta.style.cssText = 'flex:1;min-width:0;';
        card.appendChild(meta);

        container.appendChild(card);
        if (!userProfiles[uid]) fetchUserProfiles([uid]);
    }
    if (opts.isHost) {
        card.style.background = '#fff5e6';
        card.style.borderColor = '#f0ad4e';
    } else if (uid === window.AGORA_OWN_UID) {
        card.style.background = '#e6f7f5';
        card.style.borderColor = '#13B2AD';
    }
    renderParticipantBody(uid);
    return card;
}

function renderParticipantBody(uid) {
    const card = document.getElementById('user-participant-' + uid);
    if (!card) return;
    const slot = document.getElementById('slot-' + uid);
    const meta = document.getElementById('meta-' + uid);
    const prof = profileOf(uid);
    const isHost = uid === hostInfo.uid;
    const isSelf = uid === window.AGORA_OWN_UID;
    const hasVideo = card.dataset.hasVideo === '1';
    const suffix = isHost ? ' (Host)' : (isSelf ? ' (You)' : '');

    // Update slot only if not currently hosting a video.
    if (slot && !hasVideo) {
        slot.textContent = prof.initial;
        slot.style.background = prof.color;
    }
    if (meta) {
        meta.innerHTML = '<div style="font-weight:600;color:#222;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">'
            + escapeHtml(prof.name) + suffix
            + '</div>'
            + '<div class="small text-muted">'
            + (hasVideo ? '<i class="bi bi-camera-video-fill text-success"></i> Sharing camera' : '<i class="bi bi-person"></i> Watching')
            + '</div>';
    }
}

function escapeHtml(s) {
    return String(s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
}

function addUserParticipant(uid, _label, isHost) {
    ensureParticipantCard(uid, { isHost });
}

function removeUserParticipant(uid) {
    const el = document.getElementById('user-participant-' + uid);
    if (el) el.remove();
}

function labelFor(uid) {
    if (uid === hostInfo.uid) return { text: hostInfo.name + ' (Host)', isHost: true };
    if (uid === window.AGORA_OWN_UID) return { text: 'You', isHost: false };
    return { text: 'Viewer', isHost: false };
}

async function joinVideo() {
    const joinBtn  = document.getElementById('btn-join-stream');
    const leaveBtn = document.getElementById('btn-leave-stream');

    if (joinBtn) joinBtn.disabled = true;
    setStreamStatus('Connecting...');

    try {
        const res  = await fetch('/agora/get-token?channel=' + encodeURIComponent(channelName));
        const data = await res.json();
        if (data && data.host) hostInfo = data.host;
        window.AGORA_OWN_UID = data.uid;

        // Join as host (with publisher token) but don't publish anything yet.
        // In Agora's `live` mode, audience members are invisible to other
        // clients — promoting to host is the canonical way to be listed in
        // the participants pane while still being a silent watcher.
        await agoraClient.setClientRole('host');
        await agoraClient.join(data.appid, data.channel, data.token, data.uid);

        ensureParticipantCard(data.uid, { isHost: false });
        // Backfill any users already in the channel when we joined.
        const existingUids = agoraClient.remoteUsers.map(u => u.uid);
        if (existingUids.length) {
            existingUids.forEach(uid => ensureParticipantCard(uid, { isHost: uid === hostInfo.uid }));
            fetchUserProfiles(existingUids);
        }
        setRemoteMessage('<p class="text-muted text-center py-5"><i class="bi bi-hourglass-split"></i> Waiting for <strong>' + hostInfo.name + '</strong> to start the stream…</p>');
        setStreamStatus('Connected — waiting for host');
        if (joinBtn)  joinBtn.style.display  = 'none';
        if (leaveBtn) leaveBtn.style.display = '';
        const shareBtn = document.getElementById('btn-share-camera');
        if (shareBtn) shareBtn.style.display = '';

        agoraClient.on('user-joined', (user) => {
            ensureParticipantCard(user.uid, { isHost: user.uid === hostInfo.uid });
        });

        agoraClient.on('user-published', async (user, mediaType) => {
            await agoraClient.subscribe(user, mediaType);
            if (mediaType === 'video') {
                const isHost = (user.uid === hostInfo.uid);
                ensureParticipantCard(user.uid, { isHost });
                if (isHost) {
                    // Host's stream belongs ONLY in the main player.
                    // Agora's track.play() can render to a single container at a time —
                    // mounting it in the card slot as well would steal the video away
                    // from the main player and leave it black.
                    playMainVideo(user);
                    hostIsPublishing = true;
                    setStreamStatus('Live');
                    markHostCardLive(user.uid);
                } else {
                    mountVideoInCard(user);
                }
            }
            if (mediaType === 'audio') {
                user.audioTrack.play();
            }
        });

        agoraClient.on('user-left', (user) => {
            removeUserParticipant(user.uid);
            if (user.uid === hostInfo.uid) {
                hostIsPublishing = false;
                setRemoteMessage('<p class="text-muted text-center py-5"><i class="bi bi-slash-circle"></i> ' + hostInfo.name + ' has left. The stream has ended.</p>');
                setStreamStatus('Host left');
            }
        });

        agoraClient.on('user-unpublished', (user, mediaType) => {
            if (mediaType === 'video') {
                if (user.uid === hostInfo.uid) {
                    hostIsPublishing = false;
                    markHostCardOffline(user.uid);
                    setRemoteMessage('<p class="text-muted text-center py-5"><i class="bi bi-pause-circle"></i> ' + hostInfo.name + ' paused the stream.</p>');
                    setStreamStatus('Paused by host');
                } else {
                    unmountVideoInCard(user.uid);
                }
            }
        });

        // Host can broadcast control messages (mute-all, etc) via stream messages.
        agoraClient.on('stream-message', async (uid, payload) => {
            // Only honor messages from the auction host.
            if (uid !== hostInfo.uid) return;
            let msg;
            try {
                msg = JSON.parse(new TextDecoder().decode(payload));
            } catch (e) { return; }
            if (!msg || !msg.type) return;

            if (msg.type === 'mute-all' && isPublishingSelf && myLocalTracks.audio) {
                try {
                    await myLocalTracks.audio.setMuted(true);
                    updateMicButton(true);
                    showHostNotice('The host muted all viewers.');
                } catch (err) { console.warn('[AGORA] mute-all failed:', err); }
            } else if (msg.type === 'unmute-all' && isPublishingSelf && myLocalTracks.audio) {
                try {
                    await myLocalTracks.audio.setMuted(false);
                    updateMicButton(false);
                    showHostNotice('The host re-enabled microphones.');
                } catch (err) { console.warn('[AGORA] unmute-all failed:', err); }
            }
        });

    } catch (err) {
        console.error('[AGORA] join error:', err);
        setStreamStatus('Connection failed');
        setRemoteMessage('<p class="text-danger text-center py-5">Unable to connect to the live stream.</p>');
        if (joinBtn) joinBtn.disabled = false;
    }
}

async function leaveVideo() {
    await stopMyCamera(true);
    await agoraClient.leave();

    setRemoteMessage('<p class="text-muted text-center py-5">You have left the stream.</p>');
    const participants = document.getElementById('user-participants');
    if (participants) participants.innerHTML = '';

    const joinBtn  = document.getElementById('btn-join-stream');
    const leaveBtn = document.getElementById('btn-leave-stream');
    const shareBtn = document.getElementById('btn-share-camera');
    const stopBtn  = document.getElementById('btn-stop-camera');
    if (joinBtn)  { joinBtn.disabled = false; joinBtn.style.display = ''; }
    if (leaveBtn) leaveBtn.style.display = 'none';
    if (shareBtn) shareBtn.style.display = 'none';
    if (stopBtn)  stopBtn.style.display  = 'none';
    setStreamStatus('Offline');
    hostIsPublishing = false;
}

async function shareMyCamera() {
    if (isPublishingSelf) return;
    const shareBtn = document.getElementById('btn-share-camera');
    const stopBtn  = document.getElementById('btn-stop-camera');
    if (shareBtn) shareBtn.disabled = true;

    try {
        // Audience role can't publish. Switch this client to host before publishing.
        await agoraClient.setClientRole('host');

        myLocalTracks.audio = await AgoraRTC.createMicrophoneAudioTrack();
        myLocalTracks.video = await AgoraRTC.createCameraVideoTrack();
        await agoraClient.publish([myLocalTracks.audio, myLocalTracks.video]);

        // Local self-preview thumbnail.
        const list = document.getElementById('user-participants');
        if (list) {
            const existing = document.getElementById('thumb-self');
            if (existing) existing.remove();
            const box = document.createElement('div');
            box.id = 'thumb-self';
            box.style.cssText = 'width:120px;height:80px;border:2px solid #13B2AD;margin:6px 0;overflow:hidden;background:#000;border-radius:4px;';
            list.appendChild(box);
            myLocalTracks.video.play(box, { fit: 'cover', mirror: true });
        }

        isPublishingSelf = true;
        if (shareBtn) shareBtn.style.display = 'none';
        if (stopBtn)  stopBtn.style.display  = '';
        const micBtn = document.getElementById('btn-toggle-mic');
        if (micBtn) micBtn.style.display = '';
        updateMicButton(false);
    } catch (err) {
        console.error('[AGORA] shareMyCamera error:', err);
        alert('Could not start your camera: ' + (err && err.message ? err.message : err));
        try { await agoraClient.setClientRole('audience'); } catch (e) {}
        if (shareBtn) { shareBtn.disabled = false; shareBtn.style.display = ''; }
        if (stopBtn)  stopBtn.style.display = 'none';
    }
}

async function stopMyCamera(silent) {
    if (!isPublishingSelf) return;
    try {
        if (myLocalTracks.video) {
            try { await agoraClient.unpublish([myLocalTracks.video]); } catch (e) {}
            myLocalTracks.video.stop(); myLocalTracks.video.close();
        }
        if (myLocalTracks.audio) {
            try { await agoraClient.unpublish([myLocalTracks.audio]); } catch (e) {}
            myLocalTracks.audio.stop(); myLocalTracks.audio.close();
        }
    } catch (err) {
        console.warn('[AGORA] stopMyCamera cleanup:', err);
    }
    myLocalTracks = { video: null, audio: null };
    isPublishingSelf = false;

    const selfThumb = document.getElementById('thumb-self');
    if (selfThumb) selfThumb.remove();

    if (!silent) {
        try { await agoraClient.setClientRole('audience'); } catch (e) {}
        const shareBtn = document.getElementById('btn-share-camera');
        const stopBtn  = document.getElementById('btn-stop-camera');
        const micBtn   = document.getElementById('btn-toggle-mic');
        if (shareBtn) { shareBtn.disabled = false; shareBtn.style.display = ''; }
        if (stopBtn)  stopBtn.style.display  = 'none';
        if (micBtn)   micBtn.style.display   = 'none';
    }
}

function showHostNotice(text) {
    const el = document.getElementById('stream-status');
    if (el) el.textContent = text;
}

function updateMicButton(muted) {
    const btn = document.getElementById('btn-toggle-mic');
    if (!btn) return;
    const icon = btn.querySelector('i');
    const label = btn.querySelector('[data-mic-label]');
    if (icon)  icon.className  = muted ? 'bi bi-mic-mute me-1' : 'bi bi-mic me-1';
    if (label) label.textContent = muted ? 'Unmute' : 'Mute';
    btn.classList.toggle('muted', muted);
}

async function toggleMyMic() {
    if (!isPublishingSelf || !myLocalTracks.audio) return;
    const muted = !myLocalTracks.audio.muted;
    try {
        await myLocalTracks.audio.setMuted(muted);
        updateMicButton(muted);
    } catch (err) {
        console.warn('[AGORA] toggle mic failed:', err);
    }
}

function mountVideoInCard(user) {
    const card = ensureParticipantCard(user.uid, { isHost: user.uid === hostInfo.uid });
    if (!card) return;
    card.dataset.hasVideo = '1';
    const slot = document.getElementById('slot-' + user.uid);
    if (!slot) return;
    slot.textContent = '';
    slot.style.background = '#000';
    user.videoTrack.play(slot, { fit: 'cover' });
    // Update only the meta text — slot already holds the Agora-injected video.
    const meta = document.getElementById('meta-' + user.uid);
    if (meta) {
        const prof = profileOf(user.uid);
        const isHost = user.uid === hostInfo.uid;
        const isSelf = user.uid === window.AGORA_OWN_UID;
        const suffix = isHost ? ' (Host)' : (isSelf ? ' (You)' : '');
        meta.innerHTML = '<div style="font-weight:600;color:#222;">' + escapeHtml(prof.name) + suffix + '</div>'
            + '<div class="small text-muted"><i class="bi bi-camera-video-fill text-success"></i> Sharing camera</div>';
    }
}

function unmountVideoInCard(uid) {
    const card = document.getElementById('user-participant-' + uid);
    if (!card) return;
    card.dataset.hasVideo = '';
    const slot = document.getElementById('slot-' + uid);
    if (slot) slot.innerHTML = '';
    renderParticipantBody(uid);
}

// The host's video goes ONLY into the main player. Their participant card
// keeps the initials avatar and shows a "live" indicator instead — calling
// track.play() twice would steal the video from the main player.
function markHostCardLive(uid) {
    const card = document.getElementById('user-participant-' + uid);
    if (!card) return;
    // Make sure no leftover video tile is mounted in the slot.
    card.dataset.hasVideo = '';
    const slot = document.getElementById('slot-' + uid);
    if (slot) {
        slot.innerHTML = '';
        const prof = profileOf(uid);
        slot.textContent = prof.initial;
        slot.style.background = prof.color;
    }
    card.dataset.live = '1';
    const meta = document.getElementById('meta-' + uid);
    if (meta) {
        const prof = profileOf(uid);
        meta.innerHTML = '<div style="font-weight:600;color:#222;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">'
            + escapeHtml(prof.name) + ' (Host)'
            + '</div>'
            + '<div class="small" style="color:#d9534f;">'
            + '<span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:#d9534f;margin-right:4px;vertical-align:middle;animation:blink 1s step-start infinite;"></span>'
            + 'Broadcasting'
            + '</div>';
    }
}

function markHostCardOffline(uid) {
    const card = document.getElementById('user-participant-' + uid);
    if (!card) return;
    card.dataset.live = '';
    card.dataset.hasVideo = '';
    const slot = document.getElementById('slot-' + uid);
    if (slot) slot.innerHTML = '';
    renderParticipantBody(uid);
}

function playMainVideo(user) {
    const container = document.getElementById('remote-player');
    if (!container) return;
    container.innerHTML = '';
    container.style.background = '#000';
    container.style.display = 'block';
    container.style.minHeight = '380px';

    // Agora v4: pass the container div directly. The SDK will create its own
    // <div><video></video></div> inside; passing a manually-created <video> element
    // leaves the player blank.
    user.videoTrack.play(container, { fit: 'contain' });
}
