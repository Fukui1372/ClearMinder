<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    public function index(Task $task)
    {
        return view('tasks.index')->with(['tasks' => $task->getPaginateByLimit()]);
        //getPaginateByLimit()はTask.phpで定義したメソッドです。
    }
}