@extends('layout/app')
@section('title', 'Détails')
@section('content')


    <div class="container" id="users_infos">
        <div class="row">
            <div class="col-md-auto">
                @if ($user->avatar_path != null)
                    <img src="{{ asset($user->avatar_path) }}" alt="Current Avatar"
                        class="img-fluid rounded-md object-cover">
                @endif
            </div>
            <div class="col-md-6 text-left">
                <h1 class="fw-bold fs-1">{{ $user->name }}</h1>
                <h2 class="my-1">{{ $user->email }}</h2>
            </div>
        </div>
    </div>

    <h2 class="fw-semibold fs-2 mb-4"> {{ $user->name }}'s tasks </h2>
    <ul class="list-group">
        @foreach ($user->tasks as $task)
            <li class="list-group-item">{{ $task->name }} : {{ $task->description }}</li>
        @endforeach
    </ul>

@endsection
