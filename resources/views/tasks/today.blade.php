<!DOCTYPE html>
<x-app-layout>
    <x-slot name="header">
        {{ '当日To Doリスト' }}
    </x-slot>
    <body class="antialiased">
        <div class="container">
            <h1>当日のタスク</h1>
            @if($todayTasks->count() > 0)
                <ul>
                    @foreach($todayTasks as $task)
                        <li>{{ $task->name }} - 期日: {{ $task->deadline->format('Y-m-d H:i') }}</li>
                    @endforeach
                </ul>
            @else
                <p>当日のタスクはありません。</p>
            @endif
        </div>
    </body>
</x-app-layout>
</html>