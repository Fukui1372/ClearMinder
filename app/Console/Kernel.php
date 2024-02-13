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
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function() {
            $tasks = Task::whereDate('deadline', Carbon::today())->get();
            $users = collect();
            foreach($tasks as $task) {
                $users->push($task->user);
            }
            $unique_users = $users->unique('id');
            foreach($unique_users as $user) {
                $title = "今日締め切りのタスクがあります。";
                $task_num = $user->tasks()->whereDate('deadline', Carbon::today())->count();
                $body = "タスクが" . $task_num . "件あります。";
                $url = "/tasks/today";
                $user->notify(new \App\Notifications\EventAdded($title, $body, $url));
            }
        })->dailyAt('7:00');
        
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
