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
        <div class = 'tasks'>
            @foreach ($tasks as $task)
                <div class = 'task'>
                    <h2 class = 'title' >{{ $task->title }}</h2>
                        <p class = 'body' >{{$task->body}}</p>
                    </div>
                @endforeach
            </div>
            <div class = 'paginate'>
                {{ $tasks->links() }}
            </div>
        </body>
    </html>
