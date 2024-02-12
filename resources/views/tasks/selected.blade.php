<x-app-layout>
    <x-slot name="header">
        {{ $date.'To Doリスト' }}
    </x-slot>

    <body class="antialiased">
        <div class="container">
            <h1>{{$date}}のタスク</h1>
            @if($tasks->count() > 0)
                <ul>
                    @foreach($tasks as $task)
                        <li>{{ $task->name }} - 期日: {{ $task->deadline }}</li>
                    @endforeach
                </ul>
            @else  
                <p>{{$date}}のタスクはありません。</p>
            @endif
        </div>
    </body>
</x-app-layout>