@extends('layout/app')
@section('title', 'Liste des tâches')
@section('content')

    <h1>Tasks list</h1>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="mb-3 d-flex justify-content-end">
        <div class="mr-2 form-group">
            <label for="sort" class="mr-2">Sort by <i class="fas fa-sort"></i></label>
            <select id="sort" class="form-control form-control-sm" onchange="sortTasks(this.value)">
                <option value="default">Default</option>
                <option value="participants_asc">Ascending participants</option>
                <option value="participants_desc">Descending participants</option>
                <option value="date_asc">Date ascending</option>
                <option value="date_desc">Date descending</option>
                <option value="confirmed">Confirmed</option>
                <option value="unconfirmed">Unconfirmed</option>
            </select>
        </div>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th scope="col" class="text-center text-icon">Task <span data-sort-by="task"
                        onclick="toggleSort(this)"><i class=" icon-sort fas fa-sort-down"></i></span></th>
                <th scope="col" class="text-center text-icon">Begin date <span data-sort-by="beginDate"
                        onclick="toggleSort(this)"><i class="icon-sort fas fa-sort-down"></i></span></th>
                <th scope="col" class="text-center text-icon">End date <span data-sort-by="endDate"
                        onclick="toggleSort(this)"><i class="icon-sort fas fa-sort-down"></i></span></th>
                <th scope="col" class="text-center text-icon">Number of participants <span data-sort-by="participants"
                        onclick="toggleSort(this)"><i class="icon-sort fas fa-sort-down"></i></span></th>
                <th scope="col" class="text-center text-icon">Max participants</th>
                <th scope="col" class="text-center text-icon">Registration <span data-sort-by="inscription"
                        onclick="toggleSort(this)"><i class="icon-sort fas fa-sort-down"></i></span></th>
                <th scope="col" class="text-center text-icon">Details</th>
                @if(auth()->user()->isAdmin())
                <th scope="col" class="text-center text-icon">Modifcation</th>
                @endif
            </tr>
        </thead>
        <tbody id="tasks-body">
            @foreach ($tasks as $task)
                <tr>
                    <th scope="row" class="text-center">{{ $task->id }}</th>
                    <td scope="row" class="text-center">{{ $task->name }}</td>
                    <td scope="row" class="text-center">{{ $task->start_datetime }}</td>
                    @if ($task->people_count < $task->people_min)
                        <td scope="row" data-task-id="{{ $task->id }}" class="text-center text-danger"
                            id="{{ $task->id }}">{{ $task->people_count }}</td>
                    @else
                        <td scope="row" data-task-id="{{ $task->id }}" class="text-center" id="{{ $task->id }}">
                            {{ $task->people_count }}</td>
                    @endif
                    <td scope="row" class="text-center">{{ $task->people_min }}-{{ $task->people_max }}</td>

                    @if ($task->people_count >= $task->people_max)
                        <td data-task-id="{{ $task->id }}">
                            <button class="button-registered" disabled>maximum reached</button>
                        </td>
                    @elseif ($task->id_task != null)
                        <td data-task-id="{{ $task->id }}">
                            <button onclick='singInTask(this)' class="button-registered" disabled>Inscrit</button>
                        </td>
                    @else
                        <td data-task-id="{{ $task->id }}">
                            <button onclick='singInTask(this)' class="button-signUp">S'inscrire</button>
                        </td>
                    @endif

                    <td scope="row" class="text-center">
                        <button id="buttonTask" type="button" class="btn border border-1"
                            onclick="showTaskDetails('{{ $task->name }}', '{{ $task->description }}', '{{ $task->people_count }}','{{ $task->start_datetime }}','{{ $task->end_datetime }}','{{ $task->address }}')">
                            Details
                        </button>
                    </td>

                    @if (auth()->user()->isAdmin())
                        <td>
                            <button id="button.{{ $task->id }}" onclick="redirectToTask({{ $task->id }})">
                                modify
                            </button>
                        </td>
                    @endif
                </tr>
                <script>
                    function redirectToTask(taskId) {
                        window.location.href = '/tasks/' + taskId;
                    }
                </script>
            @endforeach
        </tbody>
    </table>
    <!-- Modal -->
    <div class="modal fade" id="modalDetails" tabindex="-1" role="dialog" aria-labelledby="modalDetailsLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetailsLabel">Task Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Details will be displayed here -->
                    <p id="taskDetails"></p>
                    <br>
                    <br>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col" class="text-icon text-center">Name</th>
                                <th scope="col" class="text-icon text-center">Mail</th>
                                @if (auth()->user()->isAdmin())
                                    <th scope="col" class="text-icon text-center">View details</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody id="pearson_list">

                        </tbody>

                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="border btn border-1" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            const lastSortBy = localStorage.getItem('lastSortBy') || 'default';
            const isAdmin = {!! (auth()->user()->isAdmin()) !!};
            sortTasks(lastSortBy,isAdmin);

        });
        function redirectToTask(taskId)
        {
            window.location.href = '/tasks/' + taskId;
        }
    </script>
@endsection
