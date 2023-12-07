@extends('layout/app')
@section('title', 'Calendar')
@section('content')
<div>
    <table class="table">
        <thead>
            <th>Name</th>
            <th>Email</th>
            <th>Inscription date</th>
            <th>Actions</th>
        </thead>
        <tbody>
            @foreach ($unverified as $veri)
            <tr>
                <td>{{ $veri->name }}</td>
                <td>{{ $veri->email }}</td>
                <td>{{ $veri->created_at }}</td>
                <td class="flex flex-row">
                    <form action="{{ route('verify.add') }}" method="post">
                        @csrf
                        @method('patch')
                        <input type="hidden" name="id_user_unverified" value="{{ $veri->id }}">
                        <button type="submit" class="mx-1 btn btn-success bg-green-500 hover:bg-green-800"> Accept </button>
                    </form>
                    <form action="{{ route('verify.delete') }}" method="post">
                        @csrf
                        @method('delete')
                        <input type="hidden" name="id_user_unverified" value="{{ $veri->id }}">
                        <button type="submit" class="mx-1 btn btn-danger bg-red-500 hover:bg-red-800"> Refuse </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection