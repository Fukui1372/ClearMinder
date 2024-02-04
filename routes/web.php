<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ApiTestController;

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::controller(TaskController::class)->middleware(['auth'])->group(function(){
    Route::get ('/', 'index')->name('index');
    Route::get('/tasks/create', 'create')->name('create');
    Route::post('/tasks', 'store')->name('store');
    Route::get('/tasks/{task}/edit', 'edit')->name('edit');
    Route::put('/tasks/{task}', 'update')->name('update');
    Route::delete('/tasks/{task}', 'delete')->name('delete');
    Route::get('/tasks/weekly','weekly')->name('weekly');
    Route::get('/tasks/today', 'showTodayTasks')->name('showTodayTasks');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/calendar',[EventController::class, 'calendar'])->name('calendar');
Route::post('/calendar/create', [EventController::class, 'create'])->name("create");
Route::post('/calendar/get', [EventController::class, 'get'])->name("get");// DBに登録した予定を取得
Route::put('/calendar/update', [EventController::class, 'update'])->name("update");//予定の更新
Route::delete('/calendar/delete', [EventController::class, 'delete'])->name("delete");//予定の削除

require __DIR__.'/auth.php';


