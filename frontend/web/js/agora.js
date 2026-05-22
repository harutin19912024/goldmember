// Channel is set by the page before this script runs via window.AGORA_CHANNEL
const channelName = window.AGORA_CHANNEL || 'default-room';
let agoraClient = AgoraRTC.createClient({ mode: 'live', codec: 'vp8' });
let hostInfo = { uid: null, name: 'Host' };
let hostIsPublishing = false;

function setRemoteMessage(html) {
    const container = document.getElementById('remote-player');
    if (container) container.innerHTML = html;
}

function setStreamStatus(text) {
    const el = document.getElementById('stream-status');
    if (el) el.textContent = text;
}

function addUserParticipant(uid, label, isHost) {
    const container = document.getElementById('user-participants');
    if (!container) return;
    const id = 'user-participant-' + uid;
    let div = document.getElementById(id);
    if (!div) {
        div = document.createElement('div');
        div.id = id;
        container.appendChild(div);
    }
    div.style.cssText = 'display:flex;align-items:center;padding:6px 10px;margin-bottom:6px;'
        + 'background:' + (isHost ? '#fff5e6' : '#eef')
        + ';border:1px solid ' + (isHost ? '#f0ad4e' : '#ccd')
        + ';border-radius:4px;font-size:13px;';
    const icon = isHost ? '<i class="bi bi-mic-fill me-2 text-warning"></i>' : '<i class="bi bi-person-fill me-2"></i>';
    div.innerHTML = icon + label + ' <span class="text-muted ms-1">(#' + uid + ')</span>';
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

        await agoraClient.setClientRole('audience');
        await agoraClient.join(data.appid, data.channel, data.token, data.uid);

        addUserParticipant(data.uid, 'You', false);
        setRemoteMessage('<p class="text-muted text-center py-5"><i class="bi bi-hourglass-split"></i> Waiting for <strong>' + hostInfo.name + '</strong> to start the stream…</p>');
        setStreamStatus('Connected — waiting for host');
        if (joinBtn)  joinBtn.style.display  = 'none';
        if (leaveBtn) leaveBtn.style.display = '';

        agoraClient.on('user-joined', (user) => {
            const lbl = labelFor(user.uid);
            addUserParticipant(user.uid, lbl.text, lbl.isHost);
        });

        agoraClient.on('user-published', async (user, mediaType) => {
            await agoraClient.subscribe(user, mediaType);
            if (mediaType === 'video') {
                playMainVideo(user);
                createThumbnail(user);
                hostIsPublishing = true;
                setStreamStatus('Live');
            }
            if (mediaType === 'audio') {
                user.audioTrack.play();
            }
            // Re-render label now that we know this user is publishing — almost certainly the host.
            const lbl = labelFor(user.uid);
            addUserParticipant(user.uid, lbl.text, lbl.isHost || mediaType === 'video');
        });

        agoraClient.on('user-left', (user) => {
            removeUserParticipant(user.uid);
            const thumb = document.getElementById('thumb-' + user.uid);
            if (thumb) thumb.remove();
            if (user.uid === hostInfo.uid) {
                hostIsPublishing = false;
                setRemoteMessage('<p class="text-muted text-center py-5"><i class="bi bi-slash-circle"></i> ' + hostInfo.name + ' has left. The stream has ended.</p>');
                setStreamStatus('Host left');
            }
        });

        agoraClient.on('user-unpublished', (user, mediaType) => {
            if (mediaType === 'video' && user.uid === hostInfo.uid) {
                hostIsPublishing = false;
                setRemoteMessage('<p class="text-muted text-center py-5"><i class="bi bi-pause-circle"></i> ' + hostInfo.name + ' paused the stream.</p>');
                setStreamStatus('Paused by host');
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
    await agoraClient.leave();

    setRemoteMessage('<p class="text-muted text-center py-5">You have left the stream.</p>');
    const participants = document.getElementById('user-participants');
    if (participants) participants.innerHTML = '';

    const joinBtn  = document.getElementById('btn-join-stream');
    const leaveBtn = document.getElementById('btn-leave-stream');
    if (joinBtn)  { joinBtn.disabled = false; joinBtn.style.display = ''; }
    if (leaveBtn) leaveBtn.style.display = 'none';
    setStreamStatus('Offline');
    hostIsPublishing = false;
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
