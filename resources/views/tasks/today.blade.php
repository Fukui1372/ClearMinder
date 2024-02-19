<x-app-layout>
    <x-slot name="header">
        {{ '当日To Doリスト' }}
    </x-slot>

    <x-slot name="slot">
        <div class="space-y-6 m-4"="container">
            @if($todayTasks->count() > 0)
                <ul>
                    @foreach($todayTasks as $task)
                        <li>{{ $task->name }} - 期日: {{ $task->deadline->format('Y-m-d H:i') }}</li>
                    @endforeach
                </ul>
            @else
                <p>当日のタスクはありません。</p>
            @endif
        </div>
        <div class="space-y-4  m-4"="time">
            <h1>タスク消化タイマー(作業時間：休憩時間)<h1>
            <form name="e" action="" class="form">
                <div class="space-y-4  m-3"="setTimer">
                    <select id="study"></select>
                    <span class="koron">:</span>
                    <select id="rest"></select>
                </div>
        </select>
            </form>
            <form name="f" action="" class="space-y-3  m-3 w-20"="form2">
              <input type="text" name="days" size="25" class="timer py-1 px-2" />
              <input type="hidden" class="timeHour" />
            </form>
                          <button
                    type="button"
                    onclick="startTimer()"
                    class="bg-gray-900 hover:bg-gray-800 text-white rounded px-3 py-2 inline-block align-middle"="start"
                >
                    スタート
                </button>
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
                  
                  //休憩時間を通知
                  axios.post('/web_push/stress', {
                    restTime: rest.value,
                    url: window.location.pathname, 
                  })
                  .catch((error) => {
                    //バリデーションエラーなど
                    //alert("登録に失敗しました。");
                    console.log(error);
                  });
                  
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
              if (confirm("ポモドーロタイマーを開始しますか？\n（開始後はこのページを閉じたり再読み込みしないでください）")) {
                  display();
              }
            }
        </script>
    </x-slot>
</x-app-layout>
