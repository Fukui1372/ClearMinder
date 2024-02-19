<!DOCTYPE html>
<x-app-layout>
    <x-slot name="header">
        {{ 'タスク一覧' }}
    </head>
    </x-slot>
    <span class="bg-gray-900 hover:bg-gray-800 text-white rounded px-4 py-2 inline-block align-middle">
        <a href = "/tasks/create">タスク作成</a>
    </span>
    <div class="space-y-4  m-3" = 'tasks'>
        @foreach ($tasks as $task)
            <div class = 'task'>
                <h2 class = 'name' >{{ $task->name }}</h2>
                    <h3>期日</h3>
                        <p class = 'deadline' >{{$task->deadline}}</p>
    <div class="flex space-x-4">
        <a href="/tasks/{{ $task->id }}/edit" class="border px-3 py-1 m-1">編集</a>
        <form action="/tasks/{{ $task->id }}" id="form_{{ $task->id }}" method="POST">
            @csrf
            @method('DELETE')
        <button type="button" onclick="deleteTask({{ $task->id }})" class="border px-3 py-1 m-1">削除</button> 
        </form>
    </div>
        @endforeach
    </div>
    <div class="flex justify-center mt-4" = 'paginate'>{{ $tasks->links() }}</div>
         <ul class="flex"></ul>
    <script>
        function deleteTask(id) {
            'use strict'
                if(confirm('削除すると復元できません。 \n本当に削除しますか？')){
                    document.getElementById(`form_${id}`).submit();
            }
        }
    </script>
</x-app-layout>
