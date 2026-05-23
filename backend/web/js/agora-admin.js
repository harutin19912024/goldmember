// Channel is injected by the page via window.AGORA_CHANNEL = 'auction-{id}'
const channelName = window.AGORA_CHANNEL || null;

let agoraClient = AgoraRTC.createClient({ mode: 'live', codec: 'vp8' });
let localTracks  = {};
let hostUid      = null;
let isJoined     = false;

function addAdminParticipant(uid, label, opts) {
    const container = document.getElementById('admin-participants');
    if (!container) return;
    const id = 'admin-participant-' + uid;
    if (document.getElementById(id)) return;

    opts = opts || {};
    const div = document.createElement('div');
    div.id = id;
    div.style.cssText = 'padding:6px 10px;margin-bottom:6px;background:#f8f8f8;border:1px solid #ddd;border-radius:4px;font-size:13px;';

    const header = document.createElement('div');
    header.style.cssText = 'display:flex;align-items:center;';
    header.innerHTML = '<span style="width:8px;height:8px;border-radius:50%;background:#28a745;display:inline-block;margin-right:8px;"></span>' + label + ' <span style="color:#999;margin-left:4px;">(uid: ' + uid + ')</span>';
    div.appendChild(header);

    if (opts.withVideo) {
        const videoBox = document.createElement('div');
        videoBox.id = 'admin-participant-video-' + uid;
        videoBox.style.cssText = 'width:100%;height:120px;background:#000;border-radius:3px;margin-top:6px;overflow:hidden;';
        div.appendChild(videoBox);
    }
    container.appendChild(div);
}

function removeAdminParticipant(uid) {
    const el = document.getElementById('admin-participant-' + uid);
    if (el) el.remove();
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

    try {
        const res  = await fetch('/auction/get-token?channel=' + encodeURIComponent(channelName));
        const data = await res.json();

        hostUid = data.uid;
        await agoraClient.setClientRole('host');
        await agoraClient.join(data.appid, data.channel, data.token, data.uid);

        isJoined = true;
        addAdminParticipant(data.uid, 'Host (You)');
        setStreamStatus('LIVE', '#d9534f');
        if (joinBtn)  joinBtn.style.display  = 'none';
        if (leaveBtn) leaveBtn.style.display = '';

        // Prep the local-player container: drop the placeholder text and the
        // flex centering so the SDK's injected <video> can fill the box.
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
                // Agora v4: pass the container element (or its id) — the SDK injects its own <video>.
                localTracks.videoTrack.play(localPlayer || 'local-player', { fit: 'cover' });
                await agoraClient.publish([localTracks.audioTrack, localTracks.videoTrack]);
            } catch (videoErr) {
                console.warn('[ADMIN] Camera error, audio only:', videoErr);
                await agoraClient.publish([localTracks.audioTrack]);
                if (localPlayer) localPlayer.innerHTML = '<div style="color:#f0ad4e;padding:20px;text-align:center;"><i class="glyphicon glyphicon-warning-sign"></i> No camera — streaming audio only</div>';
            }
        } catch (e) {
            console.error('[ADMIN] Track error:', e);
        }

        agoraClient.on('user-joined', (user) => {
            addAdminParticipant(user.uid, 'Viewer');
        });
        agoraClient.on('user-left', (user) => {
            removeAdminParticipant(user.uid);
        });

        // If a viewer raises their hand and publishes their camera, subscribe
        // and show them inside the participant card so the host sees them.
        agoraClient.on('user-published', async (user, mediaType) => {
            try {
                await agoraClient.subscribe(user, mediaType);
            } catch (err) {
                console.warn('[ADMIN] subscribe failed:', err);
                return;
            }
            if (mediaType === 'video') {
                // Make sure the participant card exists with a video slot.
                removeAdminParticipant(user.uid);
                addAdminParticipant(user.uid, 'Viewer', { withVideo: true });
                const box = document.getElementById('admin-participant-video-' + user.uid);
                if (box) user.videoTrack.play(box, { fit: 'cover' });
            }
            if (mediaType === 'audio') {
                user.audioTrack.play();
            }
        });

        agoraClient.on('user-unpublished', (user, mediaType) => {
            if (mediaType === 'video') {
                const box = document.getElementById('admin-participant-video-' + user.uid);
                if (box) box.remove();
            }
        });

    } catch (err) {
        console.error('[ADMIN] Join error:', err);
        setStreamStatus('Connection failed', '#d9534f');
        if (joinBtn) joinBtn.disabled = false;
        isJoined = false;
    }
}

async function leaveVideo() {
    if (!isJoined) return;

    await agoraClient.leave();
    isJoined = false;

    if (localTracks.videoTrack) { localTracks.videoTrack.stop(); localTracks.videoTrack.close(); }
    if (localTracks.audioTrack) { localTracks.audioTrack.stop(); localTracks.audioTrack.close(); }
    localTracks = {};

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
