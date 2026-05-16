// Channel is set by the page before this script runs via window.AGORA_CHANNEL
const channelName = window.AGORA_CHANNEL || 'default-room';
let agoraClient = AgoraRTC.createClient({ mode: 'live', codec: 'vp8' });

function addUserParticipant(uid, label) {
    const container = document.getElementById('user-participants');
    if (!container || document.getElementById('user-participant-' + uid)) return;

    const div = document.createElement('div');
    div.id = 'user-participant-' + uid;
    div.style.cssText = 'display:flex;align-items:center;padding:6px 10px;margin-bottom:6px;background:#eef;border:1px solid #ccd;border-radius:4px;font-size:13px;';
    div.innerHTML = '<i class="bi bi-person-fill me-2"></i>' + label + ' <span class="text-muted ms-1">(#' + uid + ')</span>';
    container.appendChild(div);
}

function removeUserParticipant(uid) {
    const el = document.getElementById('user-participant-' + uid);
    if (el) el.remove();
}

async function joinVideo() {
    const joinBtn = document.getElementById('btn-join-stream');
    const leaveBtn = document.getElementById('btn-leave-stream');
    const statusEl = document.getElementById('stream-status');

    if (joinBtn) joinBtn.disabled = true;
    if (statusEl) statusEl.textContent = 'Connecting...';

    try {
        const res = await fetch('/agora/get-token?channel=' + channelName);
        const data = await res.json();

        await agoraClient.setClientRole('audience');
        await agoraClient.join(data.appid, data.channel, data.token, data.uid);

        addUserParticipant(data.uid, 'You');

        if (statusEl) statusEl.textContent = 'Live';
        if (joinBtn) joinBtn.style.display = 'none';
        if (leaveBtn) leaveBtn.style.display = '';

        agoraClient.on('user-joined', (user) => {
            addUserParticipant(user.uid, 'Viewer');
        });

        agoraClient.on('user-published', async (user, mediaType) => {
            await agoraClient.subscribe(user, mediaType);
            if (mediaType === 'video') {
                playMainVideo(user);
                createThumbnail(user);
            }
            if (mediaType === 'audio') {
                user.audioTrack.play();
            }
            addUserParticipant(user.uid, 'Host');
        });

        agoraClient.on('user-left', (user) => {
            removeUserParticipant(user.uid);
            const thumb = document.getElementById('thumb-' + user.uid);
            if (thumb) thumb.remove();
        });

        agoraClient.on('user-unpublished', (user, mediaType) => {
            if (mediaType === 'video') {
                const container = document.getElementById('remote-player');
                if (container) container.innerHTML = '<p class="text-muted text-center py-5">Host paused the stream.</p>';
            }
        });

    } catch (err) {
        console.error('[AGORA] join error:', err);
        if (statusEl) statusEl.textContent = 'Connection failed';
        if (joinBtn) joinBtn.disabled = false;
    }
}

async function leaveVideo() {
    await agoraClient.leave();

    const container = document.getElementById('remote-player');
    if (container) container.innerHTML = '<p class="text-muted text-center py-5">You have left the stream.</p>';

    const participants = document.getElementById('user-participants');
    if (participants) participants.innerHTML = '';

    const joinBtn = document.getElementById('btn-join-stream');
    const leaveBtn = document.getElementById('btn-leave-stream');
    const statusEl = document.getElementById('stream-status');

    if (joinBtn) { joinBtn.disabled = false; joinBtn.style.display = ''; }
    if (leaveBtn) leaveBtn.style.display = 'none';
    if (statusEl) statusEl.textContent = 'Offline';
}

function createThumbnail(user) {
    const list = document.getElementById('user-participants');
    if (!list) return;

    const existing = document.getElementById('thumb-' + user.uid);
    if (existing) existing.remove();

    const thumbContainer = document.createElement('div');
    thumbContainer.id = 'thumb-' + user.uid;
    thumbContainer.style.cssText = 'width:120px;height:80px;border:1px solid #ccc;margin-bottom:8px;overflow:hidden;background:#000;border-radius:4px;';

    const videoEl = document.createElement('video');
    videoEl.autoplay = true;
    videoEl.playsInline = true;
    videoEl.muted = true;
    videoEl.style.cssText = 'width:100%;height:100%;object-fit:cover;';

    thumbContainer.appendChild(videoEl);
    list.appendChild(thumbContainer);

    user.videoTrack.play(videoEl);
}

function playMainVideo(user) {
    const container = document.getElementById('remote-player');
    if (!container) return;

    container.innerHTML = '';

    const video = document.createElement('video');
    video.autoplay = true;
    video.playsInline = true;
    video.muted = false;
    video.style.cssText = 'width:100%;height:100%;object-fit:cover;';

    container.appendChild(video);
    user.videoTrack.play(video);
}
