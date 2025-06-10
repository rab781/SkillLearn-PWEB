// Enhanced Notification System for Skillearn Platform
// Provides consistent notification experience across all pages

class SkillLearnNotifications {
    constructor() {
        this.defaultConfig = {
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: false,
            toast: true,
            position: 'top-end',
            customClass: {
                popup: 'skill-notification'
            }
        };
    }

    // Success notification
    success(title, text = '', options = {}) {
        return Swal.fire({
            ...this.defaultConfig,
            icon: 'success',
            title: title,
            text: text,
            iconColor: '#10b981',
            ...options
        });
    }

    // Error notification
    error(title, text = '', options = {}) {
        return Swal.fire({
            ...this.defaultConfig,
            icon: 'error',
            title: title,
            text: text,
            iconColor: '#ef4444',
            timer: 4000,
            ...options
        });
    }

    // Info notification
    info(title, text = '', options = {}) {
        return Swal.fire({
            ...this.defaultConfig,
            icon: 'info',
            title: title,
            text: text,
            iconColor: '#3b82f6',
            ...options
        });
    }

    // Warning notification
    warning(title, text = '', options = {}) {
        return Swal.fire({
            ...this.defaultConfig,
            icon: 'warning',
            title: title,
            text: text,
            iconColor: '#f59e0b',
            timer: 4000,
            ...options
        });
    }

    // Loading notification
    loading(title = 'Memproses...', text = 'Mohon tunggu sebentar') {
        return Swal.fire({
            title: title,
            text: text,
            icon: 'info',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    }

    // Confirmation dialog
    confirm(title, text, options = {}) {
        return Swal.fire({
            title: title,
            text: text,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3b82f6',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Lanjutkan',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                popup: 'skill-confirm-dialog'
            },
            ...options
        });
    }

    // Delete confirmation
    confirmDelete(itemName = 'item ini', options = {}) {
        return this.confirm(
            'Hapus Data?',
            `Apakah Anda yakin ingin menghapus ${itemName}? Tindakan ini tidak dapat dibatalkan.`,
            {
                icon: 'warning',
                confirmButtonColor: '#ef4444',
                confirmButtonText: '🗑️ Ya, Hapus',
                cancelButtonText: '❌ Batal',
                ...options
            }
        );
    }

    // Custom notification with emojis for better UX
    bookmark(isAdded) {
        if (isAdded) {
            return this.success('🔖 Berhasil!', 'Video telah ditambahkan ke bookmark');
        } else {
            return this.info('📝 Info', 'Video sudah ada di bookmark Anda');
        }
    }

    // Video feedback notification
    feedbackSent() {
        return this.success('💬 Feedback Terkirim!', 'Terima kasih atas feedback Anda');
    }

    // Login success
    loginSuccess(userName) {
        return this.success(`👋 Selamat Datang!`, `Hi ${userName}, selamat datang kembali!`);
    }

    // Registration success
    registrationSuccess() {
        return this.success('🎉 Registrasi Berhasil!', 'Akun Anda telah berhasil dibuat');
    }

    // Network error
    networkError() {
        return this.error('🌐 Koneksi Bermasalah', 'Periksa koneksi internet Anda dan coba lagi');
    }

    // Close any open notification
    close() {
        Swal.close();
    }

    // Show progress notification
    progress(title, text, currentStep, totalSteps) {
        const percentage = Math.round((currentStep / totalSteps) * 100);
        return Swal.fire({
            title: title,
            text: text,
            icon: 'info',
            html: `
                <div class="progress-container mt-4">
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: ${percentage}%"></div>
                    </div>
                    <p class="text-sm text-gray-600 mt-2">${currentStep} dari ${totalSteps} langkah</p>
                </div>
            `,
            showConfirmButton: false,
            allowOutsideClick: false
        });
    }
}

// Create global instance
window.notify = new SkillLearnNotifications();

// Also provide backward compatibility
window.showNotification = (type, title, text = '') => {
    switch(type) {
        case 'success':
            return window.notify.success(title, text);
        case 'error':
            return window.notify.error(title, text);
        case 'info':
            return window.notify.info(title, text);
        case 'warning':
            return window.notify.warning(title, text);
        default:
            return window.notify.info(title, text);
    }
};

// Enhanced CSS for notifications
const style = document.createElement('style');
style.textContent = `
    .skill-notification {
        border-radius: 12px !important;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1) !important;
    }
    
    .skill-confirm-dialog {
        border-radius: 16px !important;
        box-shadow: 0 20px 60px rgba(0,0,0,0.15) !important;
    }
    
    .swal2-popup.skill-notification .swal2-title {
        font-size: 1rem !important;
        font-weight: 600 !important;
    }
    
    .swal2-popup.skill-notification .swal2-content {
        font-size: 0.875rem !important;
    }
    
    .progress-container {
        text-align: center;
    }
`;
document.head.appendChild(style);

export default SkillLearnNotifications;
