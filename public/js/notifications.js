/**
 * Sistema de Biblioteca - Sistema de Notificações
 * Sistema avançado de notificações para feedback do usuário
 */

class NotificationSystem {
    constructor() {
        this.container = null;
        this.init();
    }

    /**
     * Initialize the notification system
     */
    init() {
        this.createContainer();
        this.enhanceExistingAlerts();
        this.setupAutoHide();
    }

    /**
     * Create notification container
     */
    createContainer() {
        if (!document.getElementById('notification-container')) {
            const container = document.createElement('div');
            container.id = 'notification-container';
            container.className = 'notification-container position-fixed top-0 end-0 p-3';
            container.style.zIndex = '9999';
            document.body.appendChild(container);
            this.container = container;
        } else {
            this.container = document.getElementById('notification-container');
        }
    }

    /**
     * Show notification
     */
    show(message, type = 'info', options = {}) {
        const defaultOptions = {
            duration: 5000,
            closable: true,
            icon: true,
            position: 'top-right',
            animate: true
        };

        const config = { ...defaultOptions, ...options };
        const notification = this.createNotification(message, type, config);
        
        this.container.appendChild(notification);
        
        if (config.animate) {
            this.animateIn(notification);
        }

        if (config.duration > 0) {
            setTimeout(() => {
                this.hide(notification);
            }, config.duration);
        }

        return notification;
    }

    /**
     * Create notification element
     */
    createNotification(message, type, config) {
        const notification = document.createElement('div');
        notification.className = `notification alert alert-${this.getBootstrapType(type)} alert-dismissible fade show mb-2`;
        notification.setAttribute('role', 'alert');

        const icon = config.icon ? this.getIcon(type) : '';
        const closeButton = config.closable ? this.getCloseButton() : '';

        notification.innerHTML = `
            ${icon}
            <span class="notification-message">${message}</span>
            ${closeButton}
        `;

        if (config.closable) {
            const closeBtn = notification.querySelector('.btn-close');
            closeBtn.addEventListener('click', () => {
                this.hide(notification);
            });
        }

        return notification;
    }

    /**
     * Hide notification
     */
    hide(notification) {
        if (notification && notification.parentNode) {
            this.animateOut(notification, () => {
                notification.remove();
            });
        }
    }

    /**
     * Show success notification
     */
    success(message, options = {}) {
        return this.show(message, 'success', options);
    }

    /**
     * Show error notification
     */
    error(message, options = {}) {
        return this.show(message, 'error', { ...options, duration: 7000 });
    }

    /**
     * Show warning notification
     */
    warning(message, options = {}) {
        return this.show(message, 'warning', options);
    }

    /**
     * Show info notification
     */
    info(message, options = {}) {
        return this.show(message, 'info', options);
    }

    /**
     * Show loading notification
     */
    loading(message, options = {}) {
        const loadingOptions = {
            ...options,
            duration: 0,
            closable: false,
            icon: '<i class="bi bi-hourglass-split me-2 notification-spinner"></i>'
        };
        return this.show(message, 'info', loadingOptions);
    }

    /**
     * Show validation error notification
     */
    validationError(errors, options = {}) {
        let message = '<strong>Erro de validação:</strong><ul class="mb-0 mt-2">';
        
        if (Array.isArray(errors)) {
            errors.forEach(error => {
                message += `<li>${error}</li>`;
            });
        } else if (typeof errors === 'object') {
            Object.values(errors).forEach(errorArray => {
                if (Array.isArray(errorArray)) {
                    errorArray.forEach(error => {
                        message += `<li>${error}</li>`;
                    });
                } else {
                    message += `<li>${errorArray}</li>`;
                }
            });
        } else {
            message += `<li>${errors}</li>`;
        }
        
        message += '</ul>';
        
        return this.show(message, 'error', { ...options, duration: 8000 });
    }

    /**
     * Clear all notifications
     */
    clearAll() {
        const notifications = this.container.querySelectorAll('.notification');
        notifications.forEach(notification => {
            this.hide(notification);
        });
    }

    /**
     * Get Bootstrap alert type
     */
    getBootstrapType(type) {
        const typeMap = {
            'success': 'success',
            'error': 'danger',
            'warning': 'warning',
            'info': 'info'
        };
        return typeMap[type] || 'info';
    }

    /**
     * Get icon for notification type
     */
    getIcon(type) {
        const iconMap = {
            'success': '<i class="bi bi-check-circle me-2"></i>',
            'error': '<i class="bi bi-exclamation-circle me-2"></i>',
            'warning': '<i class="bi bi-exclamation-triangle me-2"></i>',
            'info': '<i class="bi bi-info-circle me-2"></i>'
        };
        return iconMap[type] || iconMap['info'];
    }

    /**
     * Get close button HTML
     */
    getCloseButton() {
        return '<button type="button" class="btn-close" aria-label="Close"></button>';
    }

    /**
     * Animate notification in
     */
    animateIn(notification) {
        notification.style.transform = 'translateX(100%)';
        notification.style.opacity = '0';
        
        setTimeout(() => {
            notification.style.transition = 'all 0.3s ease';
            notification.style.transform = 'translateX(0)';
            notification.style.opacity = '1';
        }, 10);
    }

    /**
     * Animate notification out
     */
    animateOut(notification, callback) {
        notification.style.transition = 'all 0.3s ease';
        notification.style.transform = 'translateX(100%)';
        notification.style.opacity = '0';
        
        setTimeout(() => {
            if (callback) callback();
        }, 300);
    }

    /**
     * Enhance existing Bootstrap alerts
     */
    enhanceExistingAlerts() {
        const alerts = document.querySelectorAll('.alert:not(.notification)');
        alerts.forEach(alert => {
            // Add animation class
            alert.classList.add('enhanced-alert');
            
            // Auto-hide after delay
            if (!alert.querySelector('.btn-close')) {
                setTimeout(() => {
                    if (alert.parentNode) {
                        this.animateOut(alert, () => {
                            alert.remove();
                        });
                    }
                }, 5000);
            }
        });
    }

    /**
     * Setup auto-hide for alerts with close buttons
     */
    setupAutoHide() {
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('btn-close')) {
                const alert = e.target.closest('.alert');
                if (alert) {
                    this.animateOut(alert, () => {
                        alert.remove();
                    });
                }
            }
        });
    }
}

/**
 * Form Validation Notifications
 */
class FormNotifications {
    constructor(notificationSystem) {
        this.notifications = notificationSystem;
        this.init();
    }

    init() {
        this.setupFormValidation();
        this.setupAjaxErrorHandling();
    }

    /**
     * Setup form validation notifications
     */
    setupFormValidation() {
        document.addEventListener('submit', (e) => {
            const form = e.target;
            if (form.tagName === 'FORM') {
                this.handleFormSubmit(form);
            }
        });
    }

    /**
     * Handle form submit
     */
    handleFormSubmit(form) {
        // Clear previous notifications
        this.notifications.clearAll();

        // Show loading notification for forms that might take time
        if (this.isSlowForm(form)) {
            this.showLoadingNotification(form);
        }
    }

    /**
     * Check if form might be slow
     */
    isSlowForm(form) {
        const slowActions = ['store', 'update', 'destroy'];
        const action = form.action;
        return slowActions.some(slowAction => action.includes(slowAction));
    }

    /**
     * Show loading notification
     */
    showLoadingNotification(form) {
        let message = 'Processando...';
        
        if (form.action.includes('store')) {
            message = 'Salvando dados...';
        } else if (form.action.includes('update')) {
            message = 'Atualizando dados...';
        } else if (form.action.includes('destroy')) {
            message = 'Excluindo registro...';
        }

        return this.notifications.loading(message);
    }

    /**
     * Setup AJAX error handling
     */
    setupAjaxErrorHandling() {
        // Handle fetch errors
        window.addEventListener('unhandledrejection', (e) => {
            if (e.reason && e.reason.message) {
                this.notifications.error('Erro de conexão: ' + e.reason.message);
            }
        });
    }

    /**
     * Show field validation error
     */
    showFieldError(field, message) {
        // Remove existing field notifications
        const existingNotification = field.parentNode.querySelector('.field-notification');
        if (existingNotification) {
            existingNotification.remove();
        }

        // Create field notification
        const notification = document.createElement('div');
        notification.className = 'field-notification alert alert-danger alert-sm mt-1 p-2';
        notification.innerHTML = `<small><i class="bi bi-exclamation-circle me-1"></i>${message}</small>`;
        
        field.parentNode.appendChild(notification);

        // Auto-remove after delay
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    }

    /**
     * Clear field error
     */
    clearFieldError(field) {
        const notification = field.parentNode.querySelector('.field-notification');
        if (notification) {
            notification.remove();
        }
    }
}

/**
 * Progress Notifications
 */
class ProgressNotifications {
    constructor(notificationSystem) {
        this.notifications = notificationSystem;
    }

    /**
     * Show progress notification
     */
    showProgress(message, progress = 0) {
        const notification = this.notifications.show(
            `${message} <div class="progress mt-2" style="height: 4px;">
                <div class="progress-bar" role="progressbar" style="width: ${progress}%"></div>
            </div>`,
            'info',
            { duration: 0, closable: false }
        );

        return {
            update: (newProgress, newMessage = null) => {
                const progressBar = notification.querySelector('.progress-bar');
                if (progressBar) {
                    progressBar.style.width = `${newProgress}%`;
                }
                if (newMessage) {
                    const messageSpan = notification.querySelector('.notification-message');
                    if (messageSpan) {
                        messageSpan.innerHTML = `${newMessage} <div class="progress mt-2" style="height: 4px;">
                            <div class="progress-bar" role="progressbar" style="width: ${newProgress}%"></div>
                        </div>`;
                    }
                }
            },
            complete: (message = 'Concluído!') => {
                this.notifications.hide(notification);
                this.notifications.success(message);
            },
            error: (message = 'Erro durante o processo') => {
                this.notifications.hide(notification);
                this.notifications.error(message);
            }
        };
    }
}

// Initialize notification system when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Create global notification system
    window.notifications = new NotificationSystem();
    window.formNotifications = new FormNotifications(window.notifications);
    window.progressNotifications = new ProgressNotifications(window.notifications);

    // Add convenience methods to window
    window.showNotification = (message, type, options) => window.notifications.show(message, type, options);
    window.showSuccess = (message, options) => window.notifications.success(message, options);
    window.showError = (message, options) => window.notifications.error(message, options);
    window.showWarning = (message, options) => window.notifications.warning(message, options);
    window.showInfo = (message, options) => window.notifications.info(message, options);

    // Handle Laravel validation errors from session
    const laravelErrors = document.querySelector('.alert-danger');
    if (laravelErrors && laravelErrors.querySelector('ul')) {
        const errorList = laravelErrors.querySelectorAll('li');
        const errors = Array.from(errorList).map(li => li.textContent);
        window.notifications.validationError(errors);
        laravelErrors.style.display = 'none';
    }
});