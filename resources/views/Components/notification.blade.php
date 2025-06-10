<!-- Enhanced Notification Component -->
<div id="notification" class="fixed top-4 right-4 z-50 hidden">
    <div class="bg-white rounded-xl shadow-2xl border border-gray-200 p-4 max-w-sm transform transition-all duration-300 translate-x-full">
        <div class="flex items-center space-x-3">
            <div class="flex-shrink-0">
                <div id="notification-icon" class="w-10 h-10 rounded-full flex items-center justify-center">
                    <!-- Icon will be set by JavaScript -->
                </div>
            </div>
            <div class="flex-1">
                <p id="notification-title" class="font-semibold text-gray-900"></p>
                <p id="notification-message" class="text-sm text-gray-600"></p>
            </div>
            <button onclick="hideNotification()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <span class="text-lg">×</span>
            </button>
        </div>
    </div>
</div>

<script>
function showNotification(type, title, message, duration = 5000) {
    const notification = document.getElementById('notification');
    const icon = document.getElementById('notification-icon');
    const titleEl = document.getElementById('notification-title');
    const messageEl = document.getElementById('notification-message');
    const container = notification.querySelector('div');

    // Set content
    titleEl.textContent = title;
    messageEl.textContent = message;

    // Set icon and colors based on type
    switch(type) {
        case 'success':
            icon.innerHTML = '<span class="text-2xl">✅</span>';
            icon.className = 'w-10 h-10 rounded-full flex items-center justify-center bg-green-100';
            container.classList.add('border-green-200');
            break;
        case 'error':
            icon.innerHTML = '<span class="text-2xl">❌</span>';
            icon.className = 'w-10 h-10 rounded-full flex items-center justify-center bg-red-100';
            container.classList.add('border-red-200');
            break;
        case 'warning':
            icon.innerHTML = '<span class="text-2xl">⚠️</span>';
            icon.className = 'w-10 h-10 rounded-full flex items-center justify-center bg-yellow-100';
            container.classList.add('border-yellow-200');
            break;
        case 'info':
            icon.innerHTML = '<span class="text-2xl">ℹ️</span>';
            icon.className = 'w-10 h-10 rounded-full flex items-center justify-center bg-blue-100';
            container.classList.add('border-blue-200');
            break;
    }

    // Show notification
    notification.classList.remove('hidden');
    setTimeout(() => {
        container.classList.remove('translate-x-full');
        container.classList.add('translate-x-0');
    }, 100);

    // Auto hide
    if (duration > 0) {
        setTimeout(() => {
            hideNotification();
        }, duration);
    }
}

function hideNotification() {
    const notification = document.getElementById('notification');
    const container = notification.querySelector('div');
    
    container.classList.remove('translate-x-0');
    container.classList.add('translate-x-full');
    
    setTimeout(() => {
        notification.classList.add('hidden');
        // Reset classes
        container.classList.remove('border-green-200', 'border-red-200', 'border-yellow-200', 'border-blue-200');
    }, 300);
}

// Example usage:
// showNotification('success', 'Berhasil!', 'Video berhasil di-bookmark');
// showNotification('error', 'Error!', 'Gagal memuat data');
// showNotification('warning', 'Perhatian!', 'Koneksi tidak stabil');
// showNotification('info', 'Info', 'Data sedang dimuat...');
</script>
