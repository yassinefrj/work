@extends('layout/app')
@section('title', 'Group list')
@section('content')
<h1>Group list</h1>
<div id="table-group">
    <table class="table">
        <thead>
            <tr>
                <th scope="col" class="text-center">Name</th>
                <th scope="col" class="text-center">Description</th>
                <th scope="col" class="text-center">Registration</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<script>
    var user_id = {{Auth::id() }};
    getAllGroups(user_id);
</script>
@endsection
