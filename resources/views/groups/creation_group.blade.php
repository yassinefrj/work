@extends('layout/app')
@section('title', 'Group creation')
@section('content')
    <h1>Group creation</h1>
    <form action="{{Route('groups.insert')}}" method="POST">
        @csrf

        <div class="form-group">
            <label for="nom">Name of the group</label>
            <input name="name" type="text" placeholder="name" class="form-control" required>
            @error('name')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="description">Description of the group</label>
            <textarea name="description" placeholder="description" class="form-control" required></textarea>
        </div>
        <br>
        <button style="background-color: #007bff;" type="submit" class="btn btn-primary">submit</button>
    </form>

    

@endsection
