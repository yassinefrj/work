@extends('layout/app')

@section('title', 'Creating a new task')

@section('content')

    <form id="form" method="POST" action="/tasks/store">
        @csrf
        <label>Task's name</label>
        <input name="name" type="text" class="form-control" id="name" required><br>

        <label>Description</label>
        <textarea name="description" class="form-control" id="description" cols="30" rows="1"></textarea><br>

        <label>Number of participants</label>
        <input name="people_count" class="form-control" id="people_count" type="number" required><br>

        <label>min of participants</label>
        <input name="people_min" class="form-control" id="people_min" type="number" required><br>

        <label>max of participants</label>
        <input name="people_max" class="form-control" id="people_max" type="number" required><br>

        <label>Begin time</label>
        <input name="start_datetime" class="form-control" id="start_datetime" type="datetime-local" required><br>

        <label>End time</label>
        <input name="end_datetime" class="form-control" id="end_datetime" type="datetime-local" required><br>

        <div class="autocomplete-container" id="autocomplete-container">
            <label>Address</label>
            <div class="input-container" id="input-container">
                <input name="address" class="form-control" id="address" type="text" required><br>
            </div>
        </div>

        <input name="id" type="hidden" id="id">
        <button class="btn btn-primary">Submit</button>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('create-validation')
        @yield('modify-validation')
        
    </form>
    <script>
        verifyModify();

        function verifyModify() {
            var old = {!! json_encode($old) !!};
            if (old.id != 0) {
                document.getElementById('name').value = old.name;
                document.getElementById('description').value = old.description;
                document.getElementById('people_count').value = old.people_count;
                document.getElementById('start_datetime').value = old.start_datetime;
                document.getElementById('end_datetime').value = old.end_datetime;
                document.getElementById('address').value = old.address;
                document.getElementById('id').value = old.id;
                document.getElementById('people_min').value = old.people_min;
                document.getElementById('people_max').value = old.people_max;

                var xx = document.getElementById('form');
                xx.action = '/tasks/modify';
            }
        }
    </script>
    <script src="{{ asset('js/autocomplete.js') }}"></script>

@endsection
