<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ApiTestController;
use App\Http\Controllers\WebPushController;
use App\Models\User;

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::controller(TaskController::class)->middleware(['auth'])->group(function(){
    Route::get ('/', 'index')->name('index');
    Route::get('/tasks/create', 'create')->name('task.create');
    Route::post('/tasks', 'store')->name('task.store');
    Route::get('/tasks/{task}/edit', 'edit')->name('task.edit');
    Route::put('/tasks/{task}', 'update')->name('task.update');
    Route::delete('/tasks/{task}', 'delete')->name('task.delete');
    Route::get('/tasks/weekly','weekly')->name('weekly');
    Route::get('/tasks/today', 'showTodayTasks')->name('showTodayTasks');
    Route::get('/tasks/{date}', 'showSelectedDay')->name('showSelectedDay');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/calendar',[EventController::class, 'calendar'])->name('calendar');
Route::post('/calendar/create', [EventController::class, 'create'])->name("event.create");
Route::post('/calendar/get', [EventController::class, 'get'])->name("get");// DBに登録した予定を取得
Route::put('/calendar/update', [EventController::class, 'update'])->name("event.update");//予定の更新
Route::delete('/calendar/delete', [EventController::class, 'delete'])->name("event.delete");//予定の削除

require __DIR__.'/auth.php';

Route::get('web_push/create', [WebPushController::class, 'create'])->name('web_push.create');
Route::post('web_push', [WebPushController::class, 'store'])->name('web_push.store');
// 全ユーザーにプッシュ通知を試みる
Route::get('web_push_test', function(){
    $users = \App\Models\User::all();
    \Notification::send($users, new \App\Notifications\EventAdded('タスク', '数学の課題', '/tasks/today'));
});
