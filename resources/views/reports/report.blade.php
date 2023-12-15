@extends('layout/app')
{{-- @section('title', 'Reports downloading') --}}
@section('content')

<div>
    <p>Download a CSV report containing users' credentials and the tasks to which they've registered : </p><br>
    <a href="/report/csv" class="btn btn-success btn-sm">Download CSV report</a>
</div>

@endsection