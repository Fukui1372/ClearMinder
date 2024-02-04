<!DOCTYPE html>
<x-app-layout>
    <x-slot name="header">
        {{ '週間To Doリスト' }}
    </x-slot>
    <body class="antialiased">
        <div class="container">
            <h1>週間タスク</h1>
            <p>週の期間: {{ $from->format('Y-m-d') }} から {{ $to->format('Y-m-d') }}</p>
            @if($tasks->count() > 0)
                <ul>
                    @foreach($tasks as $task)
                        <li>{{ $task->name }} - 期日: {{ $task->deadline->format('Y-m-d H:i') }}</li>
                    @endforeach
                </ul>
            @else
                <p>週間に対応するタスクはありません。</p>
            @endif
        </div>
    </body>
</x-app-layout>
</html>