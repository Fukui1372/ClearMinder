

function startPomodoroTimer() {
    // ポモドーロタイマーを開始する処理を実装する
    // 25分経過後にプッシュ通知を送信する処理もここで実装する

    // 25分後にプッシュ通知を送信する
    setTimeout(function() {
        sendPushNotification();
    }, 25 * 60 * 1000); // 25分後
}

function sendPushNotification() {
    // プッシュ通知を送信する処理を実装する
    alert('25分経ちました。5分間休憩してください。');
}