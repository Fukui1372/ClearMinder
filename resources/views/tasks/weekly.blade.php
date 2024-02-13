<!DOCTYPE html>
<x-app-layout>
    <x-slot name="header">
        {{ '週間To Doリスト' }}
    </x-slot>
        <div class="container">
            <h1>週間タスク</h1>
            <p>週の期間: {{ $from->format('Y-m-d') }} から {{ $to->format('Y-m-d') }}</p>
            @if($tasks->count() > 0)
                <ul>
                    @foreach($tasks as $task)
                        <li>{{ $task->name }} - 期日: {{ $task->deadline->format('Y-m-d H:i') }}</li>
                    @endforeach
                </ul>
            @else
                <p>週間に対応するタスクはありません。</p>
            @endif
            </div>
          <div class="time">
            <h1>ポモドーロタイマー<h1>
            <form name="e" action="" class="form">
                <div class="setTimer">
                    <select id="study"></select>
                    <span class="koron">:</span>
                    <select id="rest"></select>
                </div>
            </select>
                <button
                    type="button"
                    onclick="startTimer()"
                    class="start"
                >
                    スタート
                </button>
            </form>
            <form name="f" action="" class="form2">
              <input type="text" name="days" size="25" class="timer" />
              <input type="hidden" class="timeHour" />
            </form>
        </div>
        <script>
            let millenium;
            let count = 0;
            let study = document.getElementById("study");
            let rest = document.getElementById("rest");
            
            //タイマーのデフォルト値設定
            function time(value) {
              for (let i = 0; i < 60; i++) {
                let option = `<option value="${i}">${i}</option>`;
                if (i == 25) {
                  if (value == "study") {
                    option = `<option value="${i}" selected>${i}</option>`;
                  }
                } else if (i == 5) {
                  if (value == "rest") {
                    option = `<option value="${i}" selected>${i}</option>`;
                  }
                }
                document.getElementById(`${value}`).insertAdjacentHTML("beforeend", option);
              }
            }
            time("study");
            time("rest");
            
            //カウントダウン先の日時の取得
            function setLastMinutes(max) {
              millenium = new Date();
              millenium.setMinutes(millenium.getMinutes() + max);
            }
            
            //カウントダウンの表示
            function display() {
              let today = new Date();
              let hour;
            
              if (!millenium) {
                //勉強
                setLastMinutes(Number(study.value));
              }
              if (millenium < today) {
                count++;
                if (count % 2 !== 0) {
            　　　//休憩
                  setLastMinutes(Number(rest.value));
                } else {
                 //勉強
                  setLastMinutes(Number(study.value));
                }
              }
            
              let milliSec = millenium - today;
            　//分
              time2 = Math.floor(milliSec / (60 * 1000));
            　//秒
              time3 = Math.floor(milliSec / 1000) % 60;
            
              times2 = ("00" + time2).slice(-2);
              times3 = ("00" + time3).slice(-2);
            
              document.f.days.value = times2 + ":" + times3;
            
              document.f.days.style.display = "block";
          
            　
            　//1秒ごとに処理を実行
              tid = setTimeout("display()", 1000);
            }
            function startTimer() {
              if (confirm("ポモドーロタイマーを開始しますか？\n（タイマーを開始後はこのページを閉じたり再読み込みしないでください）")) {
                  display();
              }
            }
        </script>
    </x-app-layout>
</html>