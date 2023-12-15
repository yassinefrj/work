@extends('layout.app')
@section('title', 'Group Participants')
@section('content')
    <div class="container">
        <h1 class="mb-4">All Participants in Groups</h1>
        <div class="row" id="groupsContainer">
        </div>
    </div>

    <script>
        $(document).ready(function() {
            showGroupParticipants();
        });
    </script>

@endsection
