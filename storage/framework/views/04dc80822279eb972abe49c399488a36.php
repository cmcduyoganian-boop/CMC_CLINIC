<div id="toastContainer" class="toast-container"></div>

<style>
    .toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        gap: 10px;
        pointer-events: none;
    }

    .toast {
        background: white;
        border-radius: 8px;
        padding: 16px 20px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 300px;
        max-width: 400px;
        animation: slideIn 0.3s ease-out;
        pointer-events: auto;
    }

    @keyframes slideIn {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOut {
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }

    .toast.removing {
        animation: slideOut 0.3s ease-out forwards;
    }

    .toast-icon {
        font-size: 20px;
        flex-shrink: 0;
        width: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .toast-content {
        flex: 1;
    }

    .toast-title {
        font-weight: 600;
        font-size: 14px;
        margin: 0;
    }

    .toast-message {
        font-size: 13px;
        margin: 4px 0 0 0;
        opacity: 0.8;
    }

    .toast-close {
        background: none;
        border: none;
        cursor: pointer;
        font-size: 18px;
        opacity: 0.5;
        transition: opacity 0.2s;
        flex-shrink: 0;
        padding: 0;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .toast-close:hover {
        opacity: 1;
    }

    .toast.success {
        background: #d5f4e6;
        border-left: 4px solid #27ae60;
    }

    .toast.success .toast-icon {
        color: #27ae60;
    }

    .toast.success .toast-title {
        color: #27ae60;
    }

    .toast.success .toast-message {
        color: #229954;
    }

    .toast.error {
        background: #fadbd8;
        border-left: 4px solid #e74c3c;
    }

    .toast.error .toast-icon {
        color: #e74c3c;
    }

    .toast.error .toast-title {
        color: #e74c3c;
    }

    .toast.error .toast-message {
        color: #c0392b;
    }

    .toast.warning {
        background: #ffeaa7;
        border-left: 4px solid #f39c12;
    }

    .toast.warning .toast-icon {
        color: #f39c12;
    }

    .toast.warning .toast-title {
        color: #d68910;
    }

    .toast.warning .toast-message {
        color: #b8860b;
    }

    .toast.info {
        background: #dbeafe;
        border-left: 4px solid #3498db;
    }

    .toast.info .toast-icon {
        color: #3498db;
    }

    .toast.info .toast-title {
        color: #2980b9;
    }

    .toast.info .toast-message {
        color: #1d4ed8;
    }

    @media (max-width: 480px) {
        .toast-container {
            left: 10px;
            right: 10px;
            top: 10px;
        }

        .toast {
            min-width: unset;
            max-width: unset;
        }
    }
</style>

<script>
    function showToast(message, type = 'info', title = null) {
        const container = document.getElementById('toastContainer');
        
        const iconMap = {
            success: 'fas fa-check-circle',
            error: 'fas fa-exclamation-circle',
            warning: 'fas fa-exclamation-triangle',
            info: 'fas fa-info-circle'
        };

        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.innerHTML = `
            <div class="toast-icon">
                <i class="${iconMap[type]}"></i>
            </div>
            <div class="toast-content">
                ${title ? `<p class="toast-title">${title}</p>` : ''}
                <p class="toast-message">${message}</p>
            </div>
            <button class="toast-close" onclick="this.closest('.toast').remove()">
                ×
            </button>
        `;

        container.appendChild(toast);

        // Auto remove after 4 seconds
        setTimeout(() => {
            if (toast.parentNode) {
                toast.classList.add('removing');
                setTimeout(() => toast.remove(), 300);
            }
        }, 4000);
    }

    // Show session flash messages
    document.addEventListener('DOMContentLoaded', function() {
        const successMsg = document.querySelector('[data-toast-success]');
        if (successMsg) {
            showToast(successMsg.dataset.toastSuccess, 'success', 'Success!');
        }

        const errorMsg = document.querySelector('[data-toast-error]');
        if (errorMsg) {
            showToast(errorMsg.dataset.toastError, 'error', 'Error!');
        }
    });
</script><?php /**PATH C:\laragon\www\cmc_clinic\resources\views\components\toast-notification.blade.php ENDPATH**/ ?>