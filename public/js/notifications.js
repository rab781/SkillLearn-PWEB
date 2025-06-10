// Enhanced Notification System for Skillearn
class NotificationSystem {
    constructor() {
        this.container = null;
        this.init();
    }

    init() {
        // Create notification container
        this.container = document.createElement('div');
        this.container.id = 'notification-container';
        this.container.className = 'fixed top-4 right-4 z-50 space-y-2';
        document.body.appendChild(this.container);
    }

    show(type, title, message, duration = 3000) {
        const notification = document.createElement('div');
        notification.className = `notification-item transform transition-all duration-300 translate-x-full opacity-0`;
        
        const icons = {
            success: '✅',
            error: '❌',
            warning: '⚠️',
            info: 'ℹ️'
        };

        const colors = {
            success: 'bg-green-500',
            error: 'bg-red-500',
            warning: 'bg-yellow-500',
            info: 'bg-blue-500'
        };

        notification.innerHTML = `
            <div class="max-w-sm w-full ${colors[type]} shadow-lg rounded-lg pointer-events-auto flex ring-1 ring-black ring-opacity-5">
                <div class="flex-1 w-0 p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <span class="text-white text-xl">${icons[type]}</span>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-medium text-white">
                                ${title}
                            </p>
                            <p class="mt-1 text-sm text-white opacity-90">
                                ${message}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="flex border-l border-white border-opacity-20">
                    <button onclick="this.parentElement.parentElement.remove()" 
                            class="w-full border border-transparent rounded-none rounded-r-lg p-4 flex items-center justify-center text-sm font-medium text-white hover:bg-black hover:bg-opacity-10 focus:outline-none">
                        ×
                    </button>
                </div>
            </div>
        `;

        this.container.appendChild(notification);

        // Animate in
        setTimeout(() => {
            notification.classList.remove('translate-x-full', 'opacity-0');
        }, 100);

        // Auto remove
        if (duration > 0) {
            setTimeout(() => {
                this.remove(notification);
            }, duration);
        }

        return notification;
    }

    remove(notification) {
        notification.classList.add('translate-x-full', 'opacity-0');
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 300);
    }

    success(title, message) {
        return this.show('success', title, message);
    }

    error(title, message) {
        return this.show('error', title, message);
    }

    warning(title, message) {
        return this.show('warning', title, message);
    }

    info(title, message) {
        return this.show('info', title, message);
    }

    // Specialized notifications
    bookmark(isAdded) {
        if (isAdded) {
            this.success('📖 Bookmark Added', 'Video berhasil ditambahkan ke bookmark');
        } else {
            this.info('📖 Bookmark Removed', 'Video dihapus dari bookmark');
        }
    }

    feedbackSent() {
        this.success('💬 Feedback Sent', 'Feedback berhasil dikirim');
    }

    networkError() {
        this.error('🌐 Network Error', 'Terjadi kesalahan koneksi. Silakan coba lagi.');
    }

    loginRequired() {
        this.warning('🔒 Login Required', 'Silakan login terlebih dahulu');
    }

    confirmDelete(itemName) {
        return new Promise((resolve) => {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: `Apakah Anda yakin ingin menghapus ${itemName}?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal',
                    background: '#fff',
                    customClass: {
                        popup: 'rounded-xl shadow-2xl',
                        confirmButton: 'rounded-lg px-6 py-2',
                        cancelButton: 'rounded-lg px-6 py-2'
                    }
                }).then(resolve);
            } else {
                resolve({ isConfirmed: confirm(`Apakah Anda yakin ingin menghapus ${itemName}?`) });
            }
        });
    }
}

// Initialize notification system
window.notify = new NotificationSystem();

// Legacy support for existing code
window.showNotification = (type, title, message) => {
    window.notify.show(type, title, message);
};
