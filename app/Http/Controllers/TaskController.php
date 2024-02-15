<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Http\Requests\TaskRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Task $task)
    {
        return view('tasks.index')->with(['tasks' => $task->getPaginateByLimit()]);
        //getPaginateByLimit()はTask.phpで定義したメソッドです。
    }
    
    public function create()
    {
        return view('tasks.create');
    }
    
    public function store(TaskRequest $request, Task $task)
    {
        $input = $request['task'];
        $input += ['user_id' => $request->user()->id];
        $task->fill($input)->save();
        return redirect('/');
    }
    
    public function edit(Task $task)
    {
         return view('tasks.edit')->with(['task' => $task]);
    }
    
    public function update(Taskrequest $request, Task $task)
    {
        $input_task = $request['task'];
        $task->fill($input_task)->save();
        
        return redirect('/');
    }
    
    public function delete(Task $task)
    {
        $task->delete();
        return redirect('/');
    }
    
    public function weekly(Task $task){
        $from=Carbon::today()->subDay(date('w'))->timezone('Asia/Tokyo');
        $to=Carbon::today()->addDay(6-date('w'))->timezone('Asia/Tokyo');
        $tasks = $task->whereDate('deadline', '>=', $from)->whereDate('deadline', '<=', $to)->orderby('deadline', 'asc' )->where('user_id', Auth::id())->get();
        
        $tasks->transform(function ($task) {
            $task->deadline =Carbon::parse($task->deadline);
            return $task;
        });
        
        return view('tasks.weekly')->with(['tasks' => $tasks, 'from' => $from, 'to' => $to]);
    }
    
    public function showTodayTasks(Task $task){
        $todayTasks = $task->whereDate('deadline', today())->orderby('deadline', 'asc' )->where('user_id', Auth::id())->get();
        $todayTasks->transform(function ($task) {
            $task->deadline =Carbon::parse($task->deadline)->timezone('Asia/Tokyo');
            return $task;
        });
        
        return view('tasks.today')->with(['todayTasks' => $todayTasks]);
    }
    
    public function showSelectedDay($date, Task $task) {
        $selectedDay = new Carbon($date);
        $tasks = $task->whereDate('deadline', $selectedDay)->where('user_id', Auth::id())->get();
        return view('tasks.selected')->with(['date' => $date, 'tasks' =>$tasks]);
    }
}