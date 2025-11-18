// Real-time Notifications System
class NotificationManager {
    constructor() {
        this.notifications = [];
        this.checkInterval = 30000; // Check every 30 seconds
        this.init();
    }

    init() {
        this.loadNotifications();
        this.startAutoCheck();
        this.setupEventListeners();
    }

    loadNotifications() {
        // Load from localStorage or API
        const stored = localStorage.getItem('admin_notifications');
        if (stored) {
            this.notifications = JSON.parse(stored);
            this.updateUI();
        }
    }

    addNotification(notification) {
        notification.id = Date.now();
        notification.read = false;
        notification.timestamp = new Date().toISOString();

        this.notifications.unshift(notification);
        this.saveNotifications();
        this.updateUI();
        this.showToast(notification);
    }

    markAsRead(id) {
        const notification = this.notifications.find(n => n.id === id);
        if (notification) {
            notification.read = true;
            this.saveNotifications();
            this.updateUI();
        }
    }

    markAllAsRead() {
        this.notifications.forEach(n => n.read = true);
        this.saveNotifications();
        this.updateUI();
    }

    deleteNotification(id) {
        this.notifications = this.notifications.filter(n => n.id !== id);
        this.saveNotifications();
        this.updateUI();
    }

    clearAll() {
        if (confirm('Clear all notifications?')) {
            this.notifications = [];
            this.saveNotifications();
            this.updateUI();
        }
    }

    saveNotifications() {
        localStorage.setItem('admin_notifications', JSON.stringify(this.notifications));
    }

    updateUI() {
        const unreadCount = this.notifications.filter(n => !n.read).length;
        const badge = document.querySelector('.notification-badge');
        const dropdown = document.querySelector('.notification-dropdown-list');

        if (badge) {
            badge.textContent = unreadCount;
            badge.style.display = unreadCount > 0 ? 'inline-block' : 'none';
        }

        if (dropdown) {
            if (this.notifications.length === 0) {
                dropdown.innerHTML = '<li class="dropdown-item text-muted">No notifications</li>';
            } else {
                dropdown.innerHTML = this.notifications.slice(0, 5).map(n => `
                    <li class="dropdown-item ${n.read ? 'read' : 'unread'}" data-id="${n.id}">
                        <div class="notification-item">
                            <i class="fas fa-${n.icon || 'bell'} me-2 text-${n.type || 'primary'}"></i>
                            <div class="notification-content">
                                <strong>${n.title}</strong>
                                <p class="mb-0 small">${n.message}</p>
                                <small class="text-muted">${this.formatTime(n.timestamp)}</small>
                            </div>
                        </div>
                    </li>
                `).join('');
            }
        }
    }

    showToast(notification) {
        // Create toast notification
        const toast = document.createElement('div');
        toast.className = 'toast-notification';
        toast.innerHTML = `
            <div class="toast-icon">
                <i class="fas fa-${notification.icon || 'bell'}"></i>
            </div>
            <div class="toast-content">
                <strong>${notification.title}</strong>
                <p>${notification.message}</p>
            </div>
            <button class="toast-close" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        `;

        document.body.appendChild(toast);

        setTimeout(() => {
            toast.classList.add('show');
        }, 100);

        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 5000);
    }

    formatTime(timestamp) {
        const date = new Date(timestamp);
        const now = new Date();
        const diff = now - date;

        const minutes = Math.floor(diff / 60000);
        const hours = Math.floor(diff / 3600000);
        const days = Math.floor(diff / 86400000);

        if (minutes < 1) return 'Just now';
        if (minutes < 60) return `${minutes}m ago`;
        if (hours < 24) return `${hours}h ago`;
        if (days < 7) return `${days}d ago`;
        return date.toLocaleDateString();
    }

    startAutoCheck() {
        setInterval(() => {
            this.checkForNewNotifications();
        }, this.checkInterval);
    }

    checkForNewNotifications() {
        // Simulate checking for new notifications
        // In production, this would be an API call
        const random = Math.random();
        if (random > 0.8) {
            const types = [
                { title: 'New Contact', message: 'You have a new contact message', icon: 'envelope', type: 'primary' },
                { title: 'New Booking', message: 'New booking request received', icon: 'calendar', type: 'success' },
                { title: 'New Application', message: 'Job application submitted', icon: 'file-alt', type: 'warning' }
            ];
            const notification = types[Math.floor(Math.random() * types.length)];
            this.addNotification(notification);
        }
    }

    setupEventListeners() {
        document.addEventListener('click', (e) => {
            if (e.target.closest('.notification-item')) {
                const id = parseInt(e.target.closest('[data-id]').dataset.id);
                this.markAsRead(id);
            }
        });
    }
}

// Initialize notification manager
const notificationManager = new NotificationManager();

// Export for use in other scripts
window.notificationManager = notificationManager;
