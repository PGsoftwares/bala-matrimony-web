document.addEventListener('DOMContentLoaded', function () {
    fetchNotifications();
    setInterval(fetchNotifications, 10000);
});

function fetchNotifications() {
    fetch(window.routes.fetchNotifications)
        .then(res => {
            if (!res.ok) throw new Error(`HTTP error! Status: ${res.status}`);
            return res.json();
        })
        .then(data => updateNotificationUI(data))
        .catch(err => console.error("Fetch error:", err));
}

function updateNotificationUI(data) {
    const badge = document.getElementById('notification-badge');
    const dropdown = document.getElementById('notification-dropdown');

    if (!badge || !dropdown) {
        // console.warn("Notification elements not found. Skipping UI update.");
        return;
    }

    if (data.count > 0) {
        badge.classList.remove('d-none');
        badge.textContent = data.count;
    } else {
        badge.classList.add('d-none');
    }

    let html = `
        <li class="dropdown-header fw-bold px-3 py-2 bg-light sticky-top">Notifications</li>
        <div style="max-height: 300px; overflow-y: auto;" class="p-0 m-0">
    `;

    if (data.notifications.length > 0) {
        data.notifications.forEach(note => {
            html += `
                <li id="notification-${note.id}" class="dropdown-item border-bottom d-flex align-items-start gap-2">
                    <div class="flex-grow-1" style="white-space: normal;">
                        <strong class="d-block text-truncate" style="max-width: 300px;">${note.title}</strong>
                        <small class="text-muted d-block text-wrap">${note.message}</small>
                    </div>
                    <!--<div class="d-flex flex-column align-items-end gap-1">
                        <a href="javascript:void(0);" onclick="readNotification(${note.id})" class="text-success" title="Mark as read">
                            <i class="bi bi-check-square-fill"></i>
                        </a>
                        <a href="javascript:void(0);" onclick="deleteNotification(${note.id})" class="text-danger" title="Delete">
                            <i class="bi bi-trash-fill"></i>
                        </a>
                    </div> -->
                </li>`;
        });
    } else {
        html += `<li><a class="dropdown-item text-muted">No notifications</a></li>`;
    }

    html += `</div>`;
    dropdown.innerHTML = html;
}

function readNotification(id) {
    fetch(window.routes.readNotification, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": window.csrfToken
        },
        body: JSON.stringify({ id: id })
    }).then(res => res.json())
        .then(() => {
            document.getElementById(`notification-${id}`)?.remove();
            fetchNotifications();
        }).catch(err => console.error(err));
}

function deleteNotification(id) {
    fetch(window.routes.deleteNotification, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": window.csrfToken
        },
        body: JSON.stringify({ id: id, _method: "DELETE" })
    }).then(res => res.json())
        .then(() => {
            document.getElementById(`notification-${id}`)?.remove();
            fetchNotifications();
        }).catch(err => console.error(err));
}

