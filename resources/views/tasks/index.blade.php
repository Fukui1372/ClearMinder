<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>タスク一覧</title>
        <!-- Fonts -->
        <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    </head>
<body class="antialiased">
    <h1>タスク一覧</h1>
    <a href = "/tasks/create">create</a>
        <div class = 'tasks'>
            @foreach ($tasks as $task)
                <div class = 'task'>
                    <h2 class = 'name' >{{ $task->name }}</h2>
                        <h3>期日</h3>
                            <p class = 'deadline' >{{$task->deadline}}</p>
                    </div>
                @endforeach
            </div>
            <div class = 'paginate'>
                {{ $tasks->links() }}
            </div>
            <div class ='edit'>
                <a href = "/tasks/{{ $tasks->id }}/edit">edit</a>
            </div>
        </body>
    </html>
