@extends('layout/app')
@section('title', 'Liste des tâches')
@section('content')

    <h1>Tasks list</h1>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="d-flex justify-content-end mb-3">
        <div class="form-group mr-2">
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
                <th scope="col" class="text-icon text-center">Task <span data-sort-by="task"
                        onclick="toggleSort(this)"><i class=" icon-sort fas fa-sort-down"></i></span></th>
                <th scope="col" class="text-icon text-center">Begin date <span data-sort-by="beginDate"
                        onclick="toggleSort(this)"><i class="icon-sort fas fa-sort-down"></i></span></th>
                <th scope="col" class="text-icon text-center">End date <span data-sort-by="endDate"
                        onclick="toggleSort(this)"><i class="icon-sort fas fa-sort-down"></i></span></th>
                <th scope="col" class="text-icon text-center">Number of participants <span data-sort-by="participants"
                        onclick="toggleSort(this)"><i class="icon-sort fas fa-sort-down"></i></span></th>
                <th scope="col" class="text-icon text-center">Max participants</th>
                <th scope="col" class="text-icon text-center">Registration <span data-sort-by="inscription"
                        onclick="toggleSort(this)"><i class="icon-sort fas fa-sort-down"></i></span></th>
                <th scope="col" class="text-icon text-center">Details</th>
                @if(auth()->user()->isAdmin())
                <th scope="col" class="text-icon text-center">Modifcation</th>
                @endif
            </tr>
        </thead>
        <tbody id="tasks-body">
            
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
                            </tr>
                        </thead>
                        <tbody id="pearson_list">

                        </tbody>

                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn border border-1" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            const lastSortBy = localStorage.getItem('lastSortBy') || 'default';
            sortTasks(lastSortBy);
        });
        function redirectToTask(taskId) 
        {
            window.location.href = '/tasks/' + taskId;
        }
    </script>
@endsection
