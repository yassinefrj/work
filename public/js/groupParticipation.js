/**
 * Asynchronously fetches and displays group participants.
 * Uses jQuery AJAX to retrieve groups from the API and displays them on the page.
 */
async function showGroupParticipants() {
    $("#groupsContainer").empty();
    $.ajax({
        url: "/api/groups",
        method: "GET",
        success: function (groups) {
            displayGroups(groups);
        },
        error: function (error) {
            console.error("Error fetching groups", error);
        },
    });
}

/**
 * Displays groups on the page.
 * Creates HTML elements for each group and appends them to the groups container.
 * Also fetches and displays group participants for each group.
 * @param {Array} groups - The array of groups to display.
 */
function displayGroups(groups) {
    var groupsContainer = $("#groupsContainer");
    groups.forEach(function (group) {
        var groupCard =
            '<div class="mb-4 col-md-6">' +
            '<div class="card">' +
            '<div class="card-header">' +
            '<h5 class="mb-0">' +
            group.name +
            "</h5>" +
            "</div>" +
            '<div class="card-body">' +
            '<h6 class="card-subtitle mb-2 text-muted">Participants:</h6>' +
            '<table id="tableGroup' +
            group.id +
            '" class="table">' +
            "<thead>" +
            "<tr>" +
            "<th>User Name</th>" +
            "<th>Status</th>" +
            "<th>Actions</th>" +
            "</tr>" +
            "</thead>" +
            '<tbody id="participantsTable' +
            group.id +
            '">' +
            "</tbody>" +
            "</table>" +
            "</div>" +
            "</div>" +
            "</div>";
        groupsContainer.append(groupCard);
        getGroupParticipants(group.id);
    });
}

/**
 * Asynchronously fetches group participants from the API.
 * Uses jQuery AJAX to retrieve group participants and calls the displayParticipants function.
 * @param {number} groupId - The ID of the group for which to fetch participants.
 */
async function getGroupParticipants(groupId) {
    $.ajax({
        url: "/api/groupParticipation",
        method: "GET",
        success: function (participants) {
            displayParticipants(groupId, participants);
        },
        error: function (error) {
            console.error("Error fetching group participants", error);
        },
    });
}

/**
 * Displays group participants in a table on the page.
 * Creates HTML elements for each participant and appends them to the table.
 * Also handles cases where there are no participants in the group.
 * @param {number} groupId - The ID of the group for which to display participants.
 * @param {Array} participants - The array of group participants.
 */
function displayParticipants(groupId, participants) {
    var tableBody = $("#participantsTable" + groupId);

    var groupParticipants = participants.filter(function (participant) {
        return participant.group.id === groupId;
    });

    groupParticipants.forEach(function (group) {
        if (group.waitingCount === 0) {
            var tableContainer = $("#tableGroup" + groupId);
            tableContainer.find(".table").remove();
            tableContainer.html(
                "<p> No users waiting to be validated in this group</p>"
            );
            return;
        }

        group.participants.forEach(function (partUser) {
            var row =
                "<tr>" +
                "<td>" +
                partUser.user.name +
                "</td>" +
                "<td>" +
                partUser.status +
                "</td>" +
                "<td>";

            if (partUser.status == "waiting") {
                row +=
                    '<div class="btn-group">' +
                    '<button class="btn btn-success" data-group-id="' +
                    groupId +
                    '" data-user-id="' +
                    partUser.user.id +
                    '" onclick="acceptParticipant(this)">Accept</button>' +
                    '<button class="btn btn-danger" data-group-id="' +
                    groupId +
                    '" data-user-id="' +
                    partUser.user.id +
                    '" onclick="rejectParticipant(this)">Reject</button>' +
                    "</div>";
            }
            row += "</td></tr>";
            tableBody.append(row);
        });
    });
}

/**
* Handles the acceptance of a participant.
 * Displays an alert indicating the acceptance of the user in the group.
 * @param {number} userId - The ID of the user being accepted.
 * @param {number} groupId - The ID of the group in which the user is accepted.
 */
function acceptParticipant(el) {
    Swal.fire({
        title: "Accept ?",
        text: "Are you sure you want to accept this person in this group ?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Accept",
        cancelButtonText: "Cancel",
    }).then((result) => {
        if (result.isConfirmed) {
            var groupId = parseInt(el.getAttribute("data-group-id"));
            var userId = parseInt(el.getAttribute("data-user-id"));
            $.ajax({
                url: "/api/updateUserParticipation",
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                data: {
                    user_id: userId,
                    group_id: groupId,
                },
                success: function (data) {
                    showNotificationAndUpdateGroup("Successfull", data.message, "success");
                },
                error: function (error) {
                    showNotificationAndUpdateGroup("Error", error, "error");
                },
            });
        }
    });
}

/**
 * Handles the rejection of a participant.
 * Displays an alert indicating the rejection of the user from the group.
 * @param {number} userId - The ID of the user being rejected.
 * @param {number} groupId - The ID of the group from which the user is rejected.
 */
function rejectParticipant(el) {
    Swal.fire({
        title: "Accept ?",
        text: "Are you sure you want to reject this person in this group ?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Accept",
        cancelButtonText: "Cancel",
    }).then((result) => {
        if (result.isConfirmed) {
            var groupId = parseInt(el.getAttribute("data-group-id"));
            var userId = parseInt(el.getAttribute("data-user-id"));
            $.ajax({
                url: "/api/removeUserParticipation",
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                data: {
                    user_id: userId,
                    group_id: groupId,
                },
                success: function (data) {
                    showNotificationAndUpdateGroup("Successfull", data.message, "success");
                },
                error: function (error) {
                    showNotificationAndUpdateGroup("Error", error, "error");
                },
            });
        }
    });
}



/**
 * Shows a notification alert, updates the group participants, and updates notification badges.
 * @param {string} titleMessage - The title of the notification.
 * @param {string} bodyMessage - The body text of the notification.
 * @param {string} icon - The icon to display in the notification.
 */
function showNotificationAndUpdateGroup(titleMessage, bodyMessage, icon) {
    showNotificationAlert(titleMessage, bodyMessage, icon)
    showGroupParticipants();
    updateNotificationBadges();
}
