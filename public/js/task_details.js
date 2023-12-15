function showTaskDetails(name, description, peopleCount, Sdate, Edate, addr, min, max) {

    $('#taskDetails').empty();

    $('#taskDetails').append(`<strong>Name:</strong> ${name}<br>`);
    $('#taskDetails').append(`<strong>Description:</strong> ${description}<br>`);
    $('#taskDetails').append(`<strong>Number of participants:</strong> ${peopleCount}<br>`);
    $('#taskDetails').append(`<strong>Begin time:</strong> ${Sdate}<br>`);
    $('#taskDetails').append(`<strong>End time:</strong> ${Edate}<br>`);
    $('#taskDetails').append(`<strong>Adresse:</strong> ${addr}<br>`);
    $('#taskDetails').append(`<strong>minimum person:</strong> ${min}<br>`);
    $('#taskDetails').append(`<strong>maximum person:</strong> ${max}<br>`);

    $("#modalDetails").modal("show");
}

function listTaskDetail(idTask) {
    $.ajax({
        url: `api/tasks/personList/${idTask}`,
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            $('#pearson_list').empty();

            data.forEach(function (task) {
                console.log(task);
                const newRow = $('<tr></tr>');
                newRow.append(`<td scope="row" class="text-center">${task.name}</td>`);
                newRow.append(`<td scope="row" class="text-center">${task.email}</td>`);

                // if the current user is admin create button go to the page of information for the users
                if(currentUser.isAdmin){
                    const buttonCell = $('<td class="text-center"></td>');
                    const link = document.createElement('a');
                    link.textContent= "Detail";
                    link.className="btn btn-outline-info";
                    link.href="/user/"+task.id;
                    buttonCell.append(link);
                    newRow.append(buttonCell);
                }

                $('#pearson_list').append(newRow);
            });
        },
        error: function (xhr, status, error) {
            console.error('Error with the ajax request:', status, error);
        }
    });
}
