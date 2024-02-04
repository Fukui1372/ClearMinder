<!DOCTYPE html>
<x-app-layout>
    <x-slot name="header">
        {{ 'タスク一覧' }}
    </head>
    </x-slot>
<body class="antialiased">
    <a href = "/tasks/create">タスク作成</a>
        <div class = 'tasks'>
            @foreach ($tasks as $task)
                <div class = 'task'>
                    <h2 class = 'name' >{{ $task->name }}</h2>
                        <h3>期日</h3>
                            <p class = 'deadline' >{{$task->deadline}}</p>
                                 <div class="edit">
                                     <a href="/tasks/{{ $task->id }}/edit">編集</a>
                                </div>
                            <form action="/tasks/{{ $task->id }}" id="form_{{ $task->id }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="deleteTask({{ $task->id }})">削除</button> 
                               </form>
                            </div>
                        @endforeach
                    </div>
                <div class = 'paginate'>{{ $tasks->links() }}</div>
            <script>
                function deleteTask(id) {
                    'use strict'
                        
                    if(confirm('削除すると復元できません。 \n本当に削除しますか？')){
                        document.getElementById(`form_${id}`).submit();
                    }
                }
            </script>
        </body>
        </x-app-layout>
    </html>
