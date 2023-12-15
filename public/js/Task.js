async function registerToTask(el) {
    var id_task = parseInt(el.parentElement.getAttribute("data-task-id"));
    $.ajax({
        url: "/tasks/register",
        data: {
            id_task,
            _token: $('meta[name="csrf-token"]').attr('content'),
        },
        method: "POST",
        dataType: "json",
        success: function (response) {
            $(el).parent().prev().prev().html(response["people_count"]);
            setButtonToUnregister(el);
            if (response["minimum_atteined"] == "Oui") {
                setTableRow(el);
            }
        },
        erro: function (xhr, status, error) {
            showNotificationAlert("Error Register", "There was an error during the register", "error");
            console.log("Erreur de requête : " + status + " - " + error);
        },
    });
}

async function unregisterToTask(el) {
    var id_task = parseInt(el.parentElement.getAttribute("data-task-id"));
    Swal.fire({
        title: "Unregister ?",
        text: "Are you sure you want to unregister from this task ?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Accept",
        cancelButtonText: "Cancel",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/tasks/unregister",
                data: {
                    id_task,
                    _token: $('meta[name="csrf-token"]').attr('content'),
                },
                method: "POST",
                dataType: "json",
                success: function (response) {
                    $(el).parent().prev().prev().html(response["people_count"]);
                    setButtonToRegister(el);
                    if (response["minimum_atteined"] == "Non") {
                        setTableRowDanger(el);
                    }
                },
                erro: function (xhr, status, error) {
                    showNotificationAlert("Error Unregister", "There was an error during the unregister", "error");
                    console.log("Erreur de requête : " + status + " - " + error);
                },
            });
        }
    });
}

function setTableRowDanger(el) {
    $(el).parent().prev().prev().addClass("text-danger");
    $(el).parent().parent().addClass("table-row-danger");
}
function setTableRow(el) {
    $(el).parent().prev().prev().removeClass("text-danger");
    $(el).parent().parent().removeClass("table-row-danger");
}

function setButtonToRegister(el) {
    el.setAttribute("class", "button-register");
    el.textContent = "Register";
    el.setAttribute("onclick", "registerToTask(this)");
}

function setButtonToUnregister(el) {
    el.setAttribute("class", "button-unregister");
    el.textContent = "Unregister";
    el.setAttribute("onclick", "unregisterToTask(this)");
}

function formatDate(datetime) {
    const date = new Date(datetime);
    const options = { day: 'numeric', month: 'numeric', year: 'numeric' };
    return date.toLocaleDateString('fr-FR', options);
}

const columnSortState = {
    task: 'default',
    description: 'default',
    participants: 'default',
    beginDate: 'default',
    endDate: 'default',
    address: 'default',
    inscription: 'default',
};

function resetColumnSortState(sortBy) {
    for (const column in columnSortState) {
        if (!column === sortBy) {
            columnSortState[column] = 'default';
        }
    }
}

function updateColumnSortState(column) {
    if (columnSortState[column] === 'default' || columnSortState[column] === 'desc') {
        columnSortState[column] = 'asc';
    } else {
        columnSortState[column] = 'desc';
    }
}

async function sortTasks(sortBy, isAdmin) {
    resetColumnSortState(sortBy);
    updateColumnSortState(sortBy);
    var data = {
        sortBy: sortBy
    };

    try {
        const response = await $.ajax({
            method: 'POST',
            url: '/tasks/sort',
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        localStorage.setItem('lastSortBy', sortBy);
        $('#tasks-body').empty();
        response.forEach((task, index) => {
            let newRow;
            if (task.MinimumAtteined == "Non") {
                newRow = $('<tr class="table-row-danger"></tr>');
            } else {
                newRow = $('<tr></tr>');
            }
            const formattedDate = formatDate(task.start_datetime);
            const formattedDateEnd = formatDate(task.end_datetime);
            newRow.append(`<td scope="row" class="text-center">${task.name}</td>`);
            newRow.append(`<td scope="row" class="text-center">${formattedDate}</td>`);
            newRow.append(`<td scope="row" class="text-center">${formattedDateEnd}</td>`);

            if (task.MinimumAtteined == "Non") {
                newRow.append(`<td scope="row" class="text-center text-danger">${task.people_count}</td>`);
            } else {
                newRow.append(`<td scope="row" class="text-center">${task.people_count}</td>`);
            }

            newRow.append(`<td scope="row" class="text-center">${task.people_min}-${task.people_max}</td>`);

            if (task.StatusInscription == "Inscrit") {
                newRow.append(`<td data-task-id="${task.id}" class="text-center"><button class="button-unregister" onclick='unregisterToTask(this)'>Unregister</button></td>`);
            } else if (task.people_count >= task.people_max) {
                newRow.append(`<td data-task-id="${task.id}" class="text-center"><button class="btn btn-warning text-white" disabled>Full</button></td>`);
            } else {
                newRow.append(`<td data-task-id="${task.id}" class="text-center"><button class="button-register" onclick='registerToTask(this)'>Register</button></td>`);
            }

            newRow.append(`<td scope="row" class="text-center">
                <button id="buttonTask${index}" type="button" class="btn border border-1" onclick="showTaskDetails('${task.name}', '${task.description}', '${task.people_count}', '${task.start_datetime}', '${task.end_datetime}', '${task.address}', '${task.people_min}', '${task.people_max}')
                ,listTaskDetail('${task.id}')">
                    Details
                </button>
            </td>`);


            if (isAdmin) {
                newRow.append(`<td>
                    <button id="button.{{$task->id}}" onclick="redirectToTask(${task.id})" class="btn btn-outline-danger">
                        Modify
                    </button>
                </td>`);
            }

            $('#tasks-body').append(newRow);
        });
    } catch (error) {
        console.log("Error with the ajax request");
    }
}

async function toggleSort(el) {
    $('.icon-sort').removeClass('fa-sort-up').addClass('fa-sort-down').css('color', '');
    $('.text-icon').css('color', '');

    var sortBy = el.getAttribute("data-sort-by");
    resetColumnSortState(sortBy);
    updateColumnSortState(sortBy);
    sortBy = sortBy + "_" + columnSortState[sortBy];

    var data = {
        sortBy: sortBy,
    };

    try {
        const response = await $.ajax({
            method: 'POST',
            url: '/tasks/sort',
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        localStorage.setItem('lastSortBy', sortBy);

        $('#tasks-body').empty();

        response.forEach((task, index) => {
            let newRow;
            if (task.MinimumAtteined == "Non") {
                newRow = $('<tr class="table-row-danger"></tr>');
            } else {
                newRow = $('<tr></tr>');
            }
            const formattedDate = formatDate(task.start_datetime);
            const formattedDateEnd = formatDate(task.end_datetime);
            newRow.append(`<td scope="row" class="text-center">${task.name}</td>`);
            newRow.append(`<td scope="row" class="text-center">${formattedDate}</td>`);
            newRow.append(`<td scope="row" class="text-center">${formattedDateEnd}</td>`);

            if (task.MinimumAtteined == "Non") {
                newRow.append(`<td scope="row" class="text-center text-danger">${task.people_count}</td>`);
            } else {
                newRow.append(`<td scope="row" class="text-center">${task.people_count}</td>`);
            }

            newRow.append(`<td scope="row" class="text-center">${task.people_min}-${task.people_max}</td>`);

            if (task.StatusInscription == "Inscrit") {
                newRow.append(`<td data-task-id="${task.id}" class="text-center"><button class="button-unregister" onclick='unregisterToTask(this)'>Unregister</button></td>`);
            } else if (task.people_count >= task.people_max) {
                newRow.append(`<td data-task-id="${task.id}" class="text-center"><button class="button-full" disabled>Full</button></td>`);
            } else {
                newRow.append(`<td data-task-id="${task.id}" class="text-center"><button class="button-register" onclick='registerToTask(this)'>Register</button></td>`);
            }

            newRow.append(`<td scope="row" class="text-center">
                <button id="buttonTask" type="button" class="btn border border-1" onclick="showTaskDetails('${task.name}', '${task.description}', '${task.people_count}', '${task.start_datetime}', '${task.end_datetime}', '${task.address}', '${task.people_min}', '${task.people_max}')">
                    Details
                </button>
            </td>`);

            newRow.append(`<td scope="row" class="text-center">
                <button onclick="redirectToTask(${task.id})">
                    Modify
                </button>
            </td>`);

            $('#tasks-body').append(newRow);
            const sortIcon = $(el).find('i');

            if (columnSortState[el.getAttribute("data-sort-by")] == 'asc') {
                sortIcon.removeClass('fa-sort-down').addClass('fa-sort-up').css('color', 'gray');
                $(el).parent().css('color', 'gray');
            } else {
                sortIcon.removeClass('fa-sort-up').addClass('fa-sort-down').css('color', 'gray');
                $(el).parent().css('color', 'gray');
            }
        });
    } catch (error) {
        console.error("Error with the ajax request", error);
    }
}
