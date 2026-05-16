<?php
$this->title = 'Group Shopping Room';
$channel = Yii::$app->request->get('channel', 'shopping-room');
?>

<script src="https://download.agora.io/sdk/release/AgoraRTC_N.js"></script>

<div>
    <h2>Group Shopping Video Room</h2>
    <div id="local-player" style="width: 200px; height: 150px; border: 1px solid #ccc;"></div>
    <div id="remote-playerlist"></div>
    <button onclick="joinCall()">Join</button>
    <button onclick="leaveCall()">Leave</button>
</div>

<script>
    const APP_ID = '<?= Yii::$app->params['agoraAppId'] ?>';
    const CHANNEL = '<?= $channel ?>';
    let TOKEN = '';
    let UID = null;

    let client = AgoraRTC.createClient({ mode: "rtc", codec: "vp8" });
    let localTracks;

    async function getToken() {
        const response = await fetch(`/agora/get-token?channel=${CHANNEL}`);
        const data = await response.json();
        TOKEN = data.token;
        UID = data.uid;
    }

    async function joinCall() {
        await getToken();

        await client.join(APP_ID, CHANNEL, TOKEN, UID);
        localTracks = await AgoraRTC.createMicrophoneAndCameraTracks();

        localTracks[1].play("local-player");
        await client.publish(localTracks);

        client.on("user-published", async (user, mediaType) => {
            await client.subscribe(user, mediaType);
            if (mediaType === "video") {
                const remoteDiv = document.createElement("div");
                remoteDiv.id = user.uid;
                remoteDiv.style = "width: 200px; height: 150px; border: 1px solid #ccc;";
                document.getElementById("remote-playerlist").appendChild(remoteDiv);
                user.videoTrack.play(remoteDiv.id);
            }
        });

        client.on("user-unpublished", user => {
            document.getElementById(user.uid)?.remove();
        });
    }

    async function leaveCall() {
        if (localTracks) {
            localTracks[0].stop();
            localTracks[1].stop();
            localTracks[0].close();
            localTracks[1].close();
        }
        await client.leave();
        document.getElementById("remote-playerlist").innerHTML = '';
    }
</script>
