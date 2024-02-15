<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;

class Kernel extends ConsoleKernel
{
    protected function scheduleTimezone() 
    {
        return 'Asia/Tokyo';
    }

    protected function schedule(Schedule $schedule)    //schedule関数を定義している。引数にSchedule型の変数$scheduleを取る。
    {
        $schedule->call(function() {    //$scheduleでcallを呼び出す。callは関数内に好きなコードを記述できる。
            $tasks = Task::whereDate('deadline', Carbon::today())->get();   //「$tasksという変数を用意して、Taskモデルの中から'deadline'カラムの今日の日付であるものという条件でデータを絞り込んだものをget()メソッドで取得している。
            $users = collect(); //$usersという変数の空箱を用意する。以下foreach処理でuserを格納する役割を持つ。
            foreach($tasks as $task) {  //$tasksに格納されたタスクのリストをループしている。$taskには、各タスクが順番に代入される。
                $users->push($task->user);  //$task->userは各タスクのユーザーを表しており、それをpushメソッドで$usersコレクションに追加している。
                //このforeach文は、$users変数に、$tasks変数に格納されたタスクのユーザーをコレクションとして集めている。
            }
            $unique_users = $users->unique('id');   //変数usersにunique('id')でユーザーの重複を解消したものを$unique_usersに格納する。
            foreach($unique_users as $user) {   //$unique_usersに格納された各ユーザーのリストをループしている。$userには各ユーザーが代入される。
                $title = "今日締め切りのタスクがあります。";    //$titleにプッシュ通知のタイトルを設定。
                $task_num = $user->tasks()->whereDate('deadline', Carbon::today())->count();    //$user->tasks()は各ユーザーのタスクであり、それをwhereDateで'deadline'カラムの今日の日付であるものという条件でデータを絞り込んでいる。
                                                                                                //絞り込んだタスクの数を->count()で取得している。
                $body = "タスクが" . $task_num . "件あります。";    //$bodyにプッシュ通知の本文を設定。 .(ピリオド)で文字列同士を結合している。$task_numがあることでタスクの数に応じて可変の文章になる。
                $url = "/tasks/today";  //$urlにプッシュ通知のURLを設定。
                $user->notify(new \App\Notifications\EventAdded($title, $body, $url));  //$user->notify()で特定のユーザーにプッシュ通知を送信する。()内では、newで通知クラスの新しいEventAddedインスタンスを作成している。
                                                                                        //\App\Notifications\EventAddedが通知の内容や送信方法を定義している。EventAddedが通知に関する情報（$title, $body, $url）を受け取っている。
            }
        })->dailyAt('7:00');    //この処理を毎日7:00に実行する。
        
         $schedule->call(function() {
            $tasks = Task::whereDate('deadline', Carbon::tomorrow())->get();
            $users = collect();
            foreach($tasks as $task) {
                $users->push($task->user);
            }
            $unique_users = $users->unique('id');
            foreach($unique_users as $user) {
                $title = "明日締め切りのタスクがあります。";
                $task_num = $user->tasks()->whereDate('deadline', Carbon::tomorrow())->count();
                $body = "タスクが" . $task_num . "件あります。";
                $url = "/";
                $user->notify(new \App\Notifications\EventAdded($title, $body, $url));
            }
        })->dailyAt('15:00');
        
        $schedule->call(function() {
            $title = "今ある予定を確認しましょう！";
            $body =  "カレンダーに入れ忘れている予定はありませんか？";
            $url = "/calendar";
            $users = User::all();
            \Notification::send($users, new \App\Notifications\EventAdded($title, $body, $url));
        })->dailyAt('18:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
