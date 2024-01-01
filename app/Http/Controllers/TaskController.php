<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Http\Requests\TaskRequest;

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
        return view('tasks/edit')->with(['task' =>$task]);    
    }
}