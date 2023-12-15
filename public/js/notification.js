/**
 * Update the notification for Group navigation
 * @param {number} count - The number of notifications.
 */
function updateGroupNotificationBadge(count) {
    if (count > 0) {
        $('#groupNotificationBadge').text(count).show();
    } else {
        $('#groupNotificationBadge').hide();
    }
}

/**
 * Update the notification for Group Participation navigation
 * @param {number} count - The number of notifications.
 */
function updateGroupListNotificationBadge(count) {
    if (count > 0) {
        $('#groupParticipationNotificationBadge').text(count).show();
    } else {
        $('#groupParticipationNotificationBadge').hide();
    }
}

/**
 * Fetch the count of waiting notifications from the API.
 * @returns {Promise<number>} - A promise that resolves with the count of waiting notifications.
 */
async function getNotificationCount() {
    try {
        const response = await fetch('/api/waiting-count');
        const data = await response.json();
        return data.waitingCount;
    } catch (error) {
        console.error('Error fetching group participants', error);
        throw error;
    }
}

/**
 * Update both Group and Group Participation navigation badges.
 */
async function updateNotificationBadges() {
    try {
        const groupNotificationCount = await getNotificationCount();
        updateGroupNotificationBadge(groupNotificationCount);
        updateGroupListNotificationBadge(groupNotificationCount);
    } catch (error) {
        console.error('Error fetching group participants', error);
    }
}

/**
 * Shows a notification alert using the Swal library.
 * @param {string} titleMessage - The title of the notification.
 * @param {string} bodyMessage - The body text of the notification.
 * @param {string} icon - The icon to display in the notification.
 */
function showNotificationAlert(titleMessage, bodyMessage, icon) {
    Swal.fire({
        title: titleMessage,
        text: bodyMessage,
        icon: icon,
        showConfirmButton: false,
        timer: 1500,
    });
}



