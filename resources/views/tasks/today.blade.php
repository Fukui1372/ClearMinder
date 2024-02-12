<x-app-layout>
    <x-slot name="header">
        {{ '当日To Doリスト' }}
    </x-slot>

    <body class="antialiased">
        <div class="container">
            <h1>当日のタスク</h1>
            @if($todayTasks->count() > 0)
                <ul>
                    @foreach($todayTasks as $task)
                        <li>{{ $task->name }} - 期日: {{ $task->deadline->format('Y-m-d H:i') }}</li>
                    @endforeach
                </ul>

                <!-- ポモドーロタイマーボタン -->
                <button id="startPomodoro">ポモドーロタイマーを開始する</button>
            @else
                <p>当日のタスクはありません。</p>
            @endif
        </div>
    </body>

    @section('scripts')
        @parent

        <script>
            document.getElementById('startPomodoro').addEventListener('click', function() {
                if (confirm('ポモドーロタイマーを開始しますか？（開始後はページを閉じないで下さい）')) {
                    startPomodoroTimer();
                }
            });

            function startPomodoroTimer() {
                setTimeout(function() {
                    sendPushNotification();
                }, 25 * 60 * 1000); // 25分後
            }

            function sendPushNotification() {
                alert('25分経ちました。5分間休憩してください。');
            }
        </script>
    @endsection
</x-app-layout>