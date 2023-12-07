async function registerToTask(el){
    var id_task = parseInt(el.parentElement.getAttribute("data-task-id"));
    //erreur car data-task-id est null , id retourner pour  chaque elelment ==Null
    console.log(id_task);
    $.ajax({
        url : "/tasks/register",
        data : {
            id_task,
            _token : $('meta[name="csrf-token"]').attr('content'),
        },
        method: "POST",
        dataType: "json",
        success: function (response) {
            console.log(response["message"]);
            $("#people_count_"+id_task).text(response["people_count"]);
            setButtonToUnregister(el);

            var participantsElement = document.getElementById(id_task);
            
            if (participantsElement) {
                console.log(participantsElement)
                participantsElement.textContent = response.people_count;
            }
            if(response.people_count < response.people_min){
                participantsElement.setAttribute("class", "text-center text-danger");
            }else{
                participantsElement.setAttribute("class", "text-center");
            }
        },
        erro : function(xhr, status, error){
            console.log("Erreur de requête : " + status + " - " + error);
        },
    });
}

async function unregisterToTask(el){
    var id_task = parseInt(el.parentElement.getAttribute("data-task-id"));
    $.ajax({
        url : "/tasks/unregister",
        data : {
            id_task,
            _token : $('meta[name="csrf-token"]').attr('content'),
        },
        method : "POST",
        dataType : "json",
        success : function (response) {
            console.log(response["message"]);
            $("#people_count_"+id_task).text(response["people_count"]);
            setButtonToRegister(el);
        },
        erro : function(xhr, status, error){
            console.log("Erreur de requête : " + status + " - " + error);
        },
    });
}


function setButtonToRegister(el){
    el.setAttribute("class","button-register");
    el.textContent = "Register";
    el.setAttribute("onclick", "registerToTask(this)");
}

function setButtonToUnregister(el){
    el.setAttribute("class","button-unregister");
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

async function sortTasks(sortBy) {
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
        console.log(response);
        localStorage.setItem('lastSortBy', sortBy);
        $('#tasks-body').empty();
        response.forEach((task, index) => {
            const newRow = $('<tr></tr>');
            const formattedDate = formatDate(task.start_datetime);
            const formattedDateEnd = formatDate(task.end_datetime);
            newRow.append(`<td scope="row" class="text-center">${task.name}</td>`);
            newRow.append(`<td scope="row" class="text-center">${formattedDate}</td>`);
            newRow.append(`<td scope="row" class="text-center">${formattedDateEnd}</td>`);
            if(task.people_count<task.people_min){
                newRow.append(`<td scope="row" class="text-center text-danger" id="${task.people_count}">${task.people_count}</td>`);    
            }else{
                newRow.append(`<td scope="row" class="text-center" id="people_count_${task.id}" id="${task.people_count}">${task.people_count}</td>`);
            }
            newRow.append(`<td scope="row" class="text-center">${task.people_min}-${task.people_max}</td>`);

            if (task.StatusInscription == "Inscrit") {
                newRow.append(`<td data-task-id="${task.id}"><button class="button-unregister" onclick='unregisterToTask(this)'>Unregister</button></td>`);
            } else if (task.people_count >= task.people_max) {
                newRow.append(`<td data-task-id="${task.id}"><button disabled>Maximum Reached</button></td>`);
            } else {
                newRow.append(`<td data-task-id="${task.id}"><button class="button-register" onclick='registerToTask(this)'>Register</button></td>`);
            }

            newRow.append(`<td scope="row" class="text-center">
                <button id="buttonTask" type="button" class="btn border border-1" onclick="showTaskDetails('${task.name}', '${task.description}', '${task.people_count}', '${task.start_datetime}', '${task.end_datetime}', '${task.address}', '${task.people_min}', '${task.people_max}'),listTaskDetail('${task.id}')">
                    Details
                </button>
            </td>`);

            // FIXME: shows Modify to everyone, not just admins !

            newRow.append(`<td>
                <button id="button.{{$task->id}}" onclick="redirectToTask(${task.id})">
                    Modify
                </button>
            </td>`);

            $('#tasks-body').append(newRow);
        });
    } catch (error) {
        console.log("Error with the ajax request CONNARD");
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
        console.log(response);
        localStorage.setItem('lastSortBy', sortBy);

        $('#tasks-body').empty();

        response.forEach((task, index) => {
            const newRow = $('<tr></tr>');
            const formattedDate = formatDate(task.start_datetime);
            const formattedDateEnd = formatDate(task.end_datetime);
            newRow.append(`<td scope="row" class="text-center">${task.name}</td>`);
            newRow.append(`<td scope="row" class="text-center">${formattedDate}</td>`);
            newRow.append(`<td scope="row" class="text-center">${formattedDateEnd}</td>`);
            newRow.append(`<td scope="row" class="text-center" id="people_count_${task.id}">${task.people_count}</td>`);
            newRow.append(`<td scope="row" class="text-center">${task.people_min}-${task.people_max}</td>`);

            if (task.StatusInscription == "Inscrit") {
                newRow.append(`<td data-task-id="${task.id}"><button class="button-unregister" onclick='unregisterToTask(this)'>Unregister</button></td>`);
            } else if (task.people_count >= task.people_max) {
                newRow.append(`<td data-task-id="${task.id}"><button disabled>Maximum Reached</button></td>`);
            } else {
                newRow.append(`<td data-task-id="${task.id}"><button class="button-register" onclick='registerToTask(this)'>Register</button></td>`);
            }

            newRow.append(`<td scope="row" class="text-center">
                <button id="buttonTask" type="button" class="btn border border-1" onclick="showTaskDetails('${task.name}', '${task.description}', '${task.people_count}', '${task.start_datetime}', '${task.end_datetime}', '${task.address}', '${task.people_min}', '${task.people_max}')">
                    Details
                </button>
            </td>`);

            newRow.append(`<td>
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