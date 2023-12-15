/**
 * Sets the button to a waiting state.
 * @param {HTMLElement} el - The HTML element of the button.
 */
function setButtonToWaiting(el) {
    el.setAttribute("class", "btn btn-warning");
    el.disabled = true;
    el.textContent = "Waiting";
}

/**
 * Registers the user for the group.
 * @param {HTMLElement} el - The HTML element of the registration button.
 */
function registerToGroup(el) {
    var groupId = parseInt(el.parentElement.getAttribute("data-group-id"));
    var userId = parseInt(el.parentElement.getAttribute("data-user-id"));

    $.ajax({
        url: `/api/groups/${groupId}`,
        type: "GET",
        contentType: "application/json",
        data: { userId: userId, groupId: groupId },
        success: function (data) {
            showNotificationAlert(
                "Successfull",
                "Successfully registered !",
                "success"
            );
            updateNotificationBadges();
            setButtonToWaiting(el);
        },
        error: function (xhr, status, error) {
            console.error("An error occurred:", error);
        },
    });
}

/**
 * Sets the button to Waiting.
 * @param {HTMLElement} el - The HTML element of the button.
 */
function setButtonToWaiting(el) {
    el.setAttribute("class", "btn btn-warning");
    el.textContent = "Waiting";
    el.disabled = true;
}

/**
 * Fetches and displays information about all groups and the user's group memberships.
 * @param {number} userId - The ID of the user.
 */
async function getAllGroups(userId) {
    try {
        // Fetch all groups
        const responseAllGroup = await fetch(`/api/groups`, {
            method: "GET",
            headers: {
                "Content-Type": "application/json",
            },
        });
        // Fetch groups for the user
        const responseGroup = await fetch(`/api/groups/user/${userId}`, {
            method: "GET",
            headers: {
                "Content-Type": "application/json",
            },
        });
        const groups = await responseAllGroup.json();
        const groupsUser = await responseGroup.json();

        groups.forEach((group, index) => {
            const newRow = $("<tr></tr>");
            newRow.append(
                $(`<td scope="row" class="text-center">${group.name}</td>`)
            );
            newRow.append(
                $(
                    `<td scope="row" class="text-center">${group.description}</td>`
                )
            );

            let waitingButton = true;

            groupsUser.forEach((groupUser) => {
                if (
                    groupUser.group.id == group.id &&
                    groupUser.status == "waiting"
                ) {
                    waitingButton = false;
                    newRow.append(
                        $(
                            `<td scope="row" class="text-center" data-group-id="${group.id}" data-user-id="${userId}"><button disabled class="btn btn-warning">Waiting</button></td>`
                        )
                    );
                } else if (
                    groupUser.group.id == group.id &&
                    groupUser.status == "register"
                ) {
                    waitingButton = false;
                    newRow.append(
                        $(
                            `<td scope="row" class="text-center" data-group-id="${group.id}" data-user-id="${userId}"><button class="btn btn-danger" onclick="unregisterToGroup(this)">Unregister</button></td>`
                        )
                    );
                }
            });
            if (waitingButton) {
                newRow.append(
                    $(
                        `<td scope="row" class="text-center" data-group-id="${group.id}" data-user-id="${userId}"><button class="btn btn-success" onclick="registerToGroup(this)">Register</button></td>`
                    )
                );
            }

            $("#table-group table tbody").append(newRow);
        });
    } catch (error) {
        console.log("error : " + error);
    }
}

/**
 * Unregister the user for the group.
 * @param {HTMLElement} el - The HTML element of the registration button.
 */
function unregisterToGroup(el) {
    Swal.fire({
        title: "Unregister ?",
        text: "Are you sure you want to unregister from this group ?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Ok",
        cancelButtonText: "Cancel",
    }).then((result) => {
        if (result.isConfirmed) {
            var groupId = parseInt(
                el.parentElement.getAttribute("data-group-id")
            );
            var userId = parseInt(
                el.parentElement.getAttribute("data-user-id")
            );
            console.log(groupId, userId);

            $.ajax({
                url: `/api/groups/unregister`,
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                data: { user_id: userId, group_id: groupId },
                success: function (data) {
                    console.log(data);
                    showNotificationAlert(
                        "Success",
                        "Unregistered successfull !",
                        "success"
                    );
                    updateNotificationBadges();
                    setButtonGroupToRegister(el);
                },
                error: function (xhr, status, error) {
                    console.log(error);
                    showNotificationAlert(
                        "Error",
                        "An error occurred:', " + error,
                        "error"
                    );
                    console.error("An error occurred:", error);
                },
            });
        }
    });
}

/**
 * Sets the button to a register state.
 * @param {HTMLElement} el - The HTML element of the button.
 */
function setButtonGroupToRegister(el) {
    el.setAttribute("class", "btn btn-success");
    el.textContent = "Register";
    el.setAttribute("onclick", "registerToGroup(this)");
}
