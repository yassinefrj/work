@extends('layout/app')
@section('title', 'Group list')
@section('content')
    <h1>Group list</h1>
    <table class="table">
        <thead>
            <tr>
                <th scope="col" class="text-center">name</th>
                <th scope="col" class="text-center">Description</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($groups as $group)
                <tr>
                    <td scope="row" class="text-center">{{ $group->name }}</td>
                    <td scope="row" class="text-center">{{ $group->description }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

@endsection
