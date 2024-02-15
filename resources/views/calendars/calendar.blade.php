<!-- calendar.blade.php -->
<x-app-layout>
    <x-slot name="header">
        　{{ 'カレンダー' }}
        <style scoped>
/* モーダルのオーバーレイ */
.modal{
    display: none; /* モーダル開くとflexに変更（ここの切り替えでモーダルの表示非表示をコントロール） */
    justify-content: center;
    align-items: center;
    position: absolute;
    z-index: 10; /* カレンダーの曜日表示がz-index=2のため、それ以上にする必要あり */
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    height: 100%;
    width: 100%;
    background-color: rgba(0,0,0,0.5);
}
/* モーダル */
.modal-contents{
    background-color: white;
    height: 400px;
    width: 600px;
    padding: 20px;
}

/* 以下モーダル内要素のデザイン調整 */
input{
    padding: 2px;
    border: 1px solid black;
    border-radius: 5px;
}
.input-title{
    display: block;
    width: 80%;
    margin: 0 0 20px;
}
.input-date{
    width: 27%;
    margin: 0 5px 20px 0;
}
textarea{
    display: block;
    width: 80%;
    margin: 0 0 20px;
    padding: 2px;
    border: 1px solid black;
    border-radius: 5px;
    resize: none;
}
select{
    display: block;
    width: 20%;
    margin: 0 0 20px;
    padding: 2px;
    border: 1px solid black;
    border-radius: 5px;
}
/* 予定の上ではカーソルがポインターになる */
.fc-event-title-container{
    cursor: pointer;
}
</style>
    </x-slot>
        <div id="app">
            <div v-if="processing">処理中...</div>
            <div v-else>
                <button type="button" @click="subscribe" v-if="!isSubscribed" class="border p-3 m-3">イベントのプッシュ通知を登録する</button>
                <button type="button" @click="unsubscribe" v-else class="border p-3 m-3">イベントのプッシュ通知を解除する</button>
            </div>
        </div>
        <!-- 以下のdivタグ内にカレンダーを表示 -->
        <div id='calendar'></div>
        <!-- カレンダー新規追加モーダル -->
            <div id="modal-add" class="modal">
                <div class="modal-contents">
                    <form method="POST" action="{{ route('event.create') }}">
                        @csrf
                        <input id="new-id" type="hidden" name="id" value=""/>
                        <label for="event_name">イベント名</label>
                        <input id="new-event_name" class="input-name" type="text" name="event_name" value=""/>
                        <label for="start_date">開始日時</label>
                        <input id="new-start_date" class="input-date" type="date" name="start_date" value=""/>
                        <label for="end_date">終了日時</label>
                        <input id="new-end_date" class="input-date" type="date" name="end_date" value=""/>
                        <label for="event_description" style="display: block">メモ</label>
                        <textarea id="new-event_description" name="event_description" rows="3" value=""></textarea>
                        <label for="event_color">背景色</label>
                        <select id="new-event_color" name="event_color">
                            <option value="blue" selected>青</option>
                            <option value="green">緑</option>
                        </select>
                        <button type="button" onclick ="closeAddModal()">キャンセル</button>
                        <button type="submit">決定</button>
                    </form>
                </div>
            </div>
        <!-- カレンダー編集モーダル -->
        <div id = "modal-update" class="modal">
            <div class ="modal-contents">
                <form method="POST" action="{{ route('event.update') }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="id" name="id" value="" />
                    <label for="event_name">イベント名</label>
                    <input class="input-name" type="text" id="event_name" name="event_name" value="" />
                    <label for="start_date">開始日時</label>
                    <input class="input-date" type="date" id="start_date" name="start_date" value="" />
                    <label for="end_date">終了日時</label>
                    <input class="input-date" type="date" id="end_date" name="end_date" value="" />
                    <label for="event_description" style="display: block">メモ</label>
                    <textarea id="event_description" name="event_description" rows="3" value=""></textarea>
                    <label for="event_color">背景色</label>
                    <select id="event_color" name="event_color">
                        <option value="blue">青</option>
                        <option value="green">緑</option>
                    </select>
                    <button type="button" onclick="closeUpdateModal()">キャンセル</button>
                    <button type="submit">決定</button>
                </form>
                <!-- カレンダー削除　-->
                <form id="delete-form" method="POST" action="{{ route('event.delete') }}">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" id="delete-id" name="id" value="" />
                    <button class="delete" type="button" onclick="deleteEvent()">削除</button>
                </form>
            </div>
        </div>
            <script src="https://cdn.jsdelivr.net/npm/vue@2.6.11"></script>
    <script>

        new Vue({
            el: '#app',
            data: {
                vapidPublicKey: '{{ config('webpush.vapid.public_key') }}',
                registration: null,
                isSubscribed: false,
                processing: false,
                csrfToken: '{{ csrf_token() }}'
            },
            methods: {
                subscribe() {   // プッシュ通知を許可する

                    this.processing = true;
                    const applicationServerKey = this.base64toUint8(this.vapidPublicKey);
                    const options = {
                        userVisibleOnly: true,
                        applicationServerKey: applicationServerKey
                    };
                    this.registration.pushManager.subscribe(options)
                        .then(subscription => {

                            // Laravel側へデータを送信
                            fetch('/web_push', {
                                method: 'POST',
                                body: JSON.stringify(subscription),
                                headers: {
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json',
                                    'X-CSRF-Token': this.csrfToken
                                }
                            })
                            .then(response => {

                                this.isSubscribed = true;
                                alert('プッシュ通知が登録されました');

                            })
                            .catch(error => {

                                console.log(error);

                            });

                        })
                        .finally(() => {

                            this.processing = false;

                        });

                },
                unsubscribe() { // プッシュ通知を解除する

                    this.processing = true;
                    this.registration.pushManager.getSubscription()
                        .then(subscription => {
                            subscription.unsubscribe()
                                .then(result => {

                                    if(result) {

                                        this.isSubscribed = false;
                                        alert('プッシュ通知が解除されました');

                                    }

                                });
                        })
                        .finally(() => {

                            this.processing = false;

                        });

                },
                base64toUint8(str) {

                    str += '='.repeat((4 - str.length % 4) % 4);
                    const base64 = str
                        .replace(new RegExp('\-', 'g'), '+')
                        .replace(new RegExp('_', 'g'), '/');

                    const binary = window.atob(base64);
                    const binaryLength = binary.length;
                    let uint8Array = new Uint8Array(binaryLength);

                    for(let i = 0; i < binaryLength; i++) {

                        uint8Array[i] = binary.charCodeAt(i);

                    }

                    return uint8Array.buffer;
                }
            },
            mounted() {

                if('serviceWorker' in navigator && 'PushManager' in window) {

                    // Service Workerをブラウザにインストールする
                    navigator.serviceWorker.register('/sw.js')
                        .then(registration => {

                            console.log('Service Worker が登録されました。');
                            this.registration = registration;
                            this.registration.pushManager.getSubscription()
                                .then(subscription => {

                                    this.isSubscribed = !(subscription === null);

                                });

                        });

                } else {

                    console.log('このブラウザは、プッシュ通知をサポートしていません。');

                }

            }
        });

        </script>
</x-app-layout>