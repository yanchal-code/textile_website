// Create a container for notifications
const notificationContainer = document.createElement('div');
notificationContainer.id = 'notification-container';
notificationContainer.style.position = 'fixed';
notificationContainer.style.top = '20px';
notificationContainer.style.right = '20px';
notificationContainer.style.zIndex = '9999';
notificationContainer.style.display = 'flex';
notificationContainer.style.flexDirection = 'column';
notificationContainer.style.alignItems = 'flex-end';
document.body.appendChild(notificationContainer);

function showNotification(message, type = 'info', messageType = 'text') {
    const notificationId = `notification-${new Date().getTime()}`;
    const notification = document.createElement('div');
    notification.id = notificationId;
    notification.className = `notification ${type}`;
    notification.innerHTML = `
        <div class="notification-content">${messageType === 'html' ? message : escapeHTML(message)}</div>
        <div class="progress-bar"></div>
        <button class="notificationCloseBtn" onclick="dismissNotification('${notificationId}')">&times;</button>
    `;

    notificationContainer.appendChild(notification);

    let remainingTime = 4000;
    const progressBar = notification.querySelector('.progress-bar');
    let timeout, start;

    function startTimeout() {
        start = Date.now();
        timeout = setTimeout(() => {
            dismissNotification(notificationId);
        }, remainingTime);

        progressBar.style.animation = `progress ${remainingTime / 1000}s linear forwards`;
    }

    function pauseTimeout() {
        clearTimeout(timeout);
        remainingTime -= Date.now() - start;
        const elapsed = Date.now() - start;
        const remaining = remainingTime;
        const percentageElapsed = (elapsed / (elapsed + remaining)) * 100;
        progressBar.style.width = `${percentageElapsed}%`;
        progressBar.style.animationPlayState = 'paused';
    }

    function resumeTimeout() {
        start = Date.now();
        timeout = setTimeout(() => {
            dismissNotification(notificationId);
        }, remainingTime);
        progressBar.style.animation = `progress ${remainingTime / 1000}s linear forwards`;
    }

    notification.addEventListener('mouseenter', pauseTimeout);
    notification.addEventListener('mouseleave', resumeTimeout);

    startTimeout();
}

// Function to dismiss a notification
function dismissNotification(notificationId) {
    const notification = document.getElementById(notificationId);
    if (notification) {
        notification.style.animation = 'slide-out 0.5s forwards';
        setTimeout(() => {
            notification.remove();
        }, 500);
    }
}

// Function to escape HTML characters in text messages
function escapeHTML(html) {
    const div = document.createElement('div');
    div.innerText = html;
    return div.innerHTML;
}

// Export the showNotification function
window.showNotification = showNotification;
