// Channel is injected by the page via window.AGORA_CHANNEL = 'auction-{id}'
const channelName = window.AGORA_CHANNEL || null;

let agoraClient = AgoraRTC.createClient({ mode: 'live', codec: 'vp8' });
let localTracks  = {};
let hostUid      = null;
let isJoined     = false;

function addAdminParticipant(uid, label) {
    const container = document.getElementById('admin-participants');
    if (!container || document.getElementById('admin-participant-' + uid)) return;

    const div = document.createElement('div');
    div.id = 'admin-participant-' + uid;
    div.style.cssText = 'display:flex;align-items:center;padding:6px 10px;margin-bottom:6px;background:#f8f8f8;border:1px solid #ddd;border-radius:4px;font-size:13px;';
    div.innerHTML = '<span style="width:8px;height:8px;border-radius:50%;background:#28a745;display:inline-block;margin-right:8px;"></span>' + label + ' <span style="color:#999;margin-left:4px;">(uid: ' + uid + ')</span>';
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

        try {
            localTracks.audioTrack = await AgoraRTC.createMicrophoneAudioTrack();

            try {
                localTracks.videoTrack = await AgoraRTC.createCameraVideoTrack();
                localTracks.videoTrack.play('local-player');
                await agoraClient.publish([localTracks.audioTrack, localTracks.videoTrack]);
            } catch (videoErr) {
                console.warn('[ADMIN] Camera error, audio only:', videoErr);
                await agoraClient.publish([localTracks.audioTrack]);
                const player = document.getElementById('local-player');
                if (player) player.innerHTML = '<div style="color:#f0ad4e;padding:20px;text-align:center;"><i class="glyphicon glyphicon-warning-sign"></i> No camera — streaming audio only</div>';
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
    if (player) player.innerHTML = '';

    const container = document.getElementById('admin-participants');
    if (container) container.innerHTML = '';

    const joinBtn  = document.getElementById('btn-admin-join');
    const leaveBtn = document.getElementById('btn-admin-leave');
    if (joinBtn)  { joinBtn.disabled = false; joinBtn.style.display = ''; }
    if (leaveBtn) leaveBtn.style.display = 'none';

    setStreamStatus('Offline', '#999');
}
