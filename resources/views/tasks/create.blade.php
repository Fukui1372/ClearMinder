<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>タスク作成</title>
    </head>
<body>
    <h1>タスク作成</h1>
        <form action = "/tasks" method = "POST">
            @csrf
        <div class = "name">
            <h2>タスク名</h2>
            <input type = "text" name = "task[name]" placeholder = "タスク名" value="{{ old('task.name') }}"/>
            <p class="name__error" style="color:red">{{ $errors->first('task.name') }}</p>
        </div>
         <div class = "deadline">
                <h2>期日</h2>
                <input type ="datetime-local" name="task[deadline]" placeholder = "2000-11-11 11:00:00" value="{{ old('task.deadline') }}"/>
                <p class="deadline__error" style="color:red">{{ $errors->first('task.deadline') }}</p>
            </div>
            <input type ="submit" value ="保存"/>
        </form>
            <div class = "footer">
                <a href = "/">戻る</a>
            </div>
        </body>
    </html>
