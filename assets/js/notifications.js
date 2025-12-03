// Sistema de Notificaciones Toast

class NotificationSystem {
    constructor() {
        this.container = null;
        this.init();
    }

    init() {
        // Crear contenedor si no existe
        if (!document.querySelector('.notification-container')) {
            this.container = document.createElement('div');
            this.container.className = 'notification-container';
            document.body.appendChild(this.container);
        } else {
            this.container = document.querySelector('.notification-container');
        }
    }

    show(message, type = 'info', duration = 3000) {
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;

        // Iconos según el tipo
        const icons = {
            success: 'check_circle',
            error: 'error',
            warning: 'warning',
            info: 'info'
        };

        // Títulos según el tipo
        const titles = {
            success: '¡Éxito!',
            error: 'Error',
            warning: 'Advertencia',
            info: 'Información'
        };

        notification.innerHTML = `
            <div class="notification-icon">
                <i class="material-icons">${icons[type] || 'info'}</i>
            </div>
            <div class="notification-content">
                <div class="notification-title">${titles[type]}</div>
                <div class="notification-message">${message}</div>
            </div>
            <button class="notification-close" onclick="this.closest('.notification').remove()">
                <i class="material-icons">close</i>
            </button>
        `;

        this.container.appendChild(notification);

        // Auto-ocultar después del tiempo especificado
        if (duration > 0) {
            setTimeout(() => {
                this.hide(notification);
            }, duration);
        }

        return notification;
    }

    hide(notification) {
        if (notification && notification.parentNode) {
            notification.classList.add('hiding');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 300);
        }
    }

    success(message, duration = 3000) {
        return this.show(message, 'success', duration);
    }

    error(message, duration = 4000) {
        return this.show(message, 'error', duration);
    }

    warning(message, duration = 3500) {
        return this.show(message, 'warning', duration);
    }

    info(message, duration = 3000) {
        return this.show(message, 'info', duration);
    }
}

// Sistema de Confirmación Modal
class ModalSystem {
    constructor() {
        this.overlay = null;
    }

    confirm(message, title = 'Confirmar', type = 'warning') {
        return new Promise((resolve) => {
            // Crear overlay
            const overlay = document.createElement('div');
            overlay.className = 'modal-overlay';

            // Iconos según el tipo
            const icons = {
                warning: 'warning',
                error: 'error',
                info: 'info'
            };

            overlay.innerHTML = `
                <div class="modal-container">
                    <div class="modal-header ${type}">
                        <div class="modal-icon">
                            <i class="material-icons">${icons[type] || 'help'}</i>
                        </div>
                        <h3 class="modal-title">${title}</h3>
                    </div>
                    <div class="modal-body">
                        ${message}
                    </div>
                    <div class="modal-footer">
                        <button class="modal-btn modal-btn-secondary" data-action="cancel">
                            <i class="material-icons">close</i> Cancelar
                        </button>
                        <button class="modal-btn modal-btn-${type === 'error' ? 'danger' : 'primary'}" data-action="confirm">
                            <i class="material-icons">check</i> Confirmar
                        </button>
                    </div>
                </div>
            `;

            document.body.appendChild(overlay);
            this.overlay = overlay;

            // Botones
            const cancelBtn = overlay.querySelector('[data-action="cancel"]');
            const confirmBtn = overlay.querySelector('[data-action="confirm"]');

            const close = (result) => {
                overlay.classList.add('hiding');
                const container = overlay.querySelector('.modal-container');
                if (container) {
                    container.classList.add('hiding');
                }
                setTimeout(() => {
                    overlay.remove();
                    resolve(result);
                }, 200);
            };

            cancelBtn.addEventListener('click', () => close(false));
            confirmBtn.addEventListener('click', () => close(true));

            // Cerrar con ESC
            const handleEsc = (e) => {
                if (e.key === 'Escape') {
                    close(false);
                    document.removeEventListener('keydown', handleEsc);
                }
            };
            document.addEventListener('keydown', handleEsc);

            // Cerrar al hacer click fuera
            overlay.addEventListener('click', (e) => {
                if (e.target === overlay) {
                    close(false);
                }
            });
        });
    }

    alert(message, title = 'Aviso', type = 'info') {
        return new Promise((resolve) => {
            const overlay = document.createElement('div');
            overlay.className = 'modal-overlay';

            const icons = {
                warning: 'warning',
                error: 'error',
                info: 'info',
                success: 'check_circle'
            };

            overlay.innerHTML = `
                <div class="modal-container">
                    <div class="modal-header ${type}">
                        <div class="modal-icon">
                            <i class="material-icons">${icons[type] || 'info'}</i>
                        </div>
                        <h3 class="modal-title">${title}</h3>
                    </div>
                    <div class="modal-body">
                        ${message}
                    </div>
                    <div class="modal-footer">
                        <button class="modal-btn modal-btn-primary" data-action="ok">
                            <i class="material-icons">check</i> Aceptar
                        </button>
                    </div>
                </div>
            `;

            document.body.appendChild(overlay);
            this.overlay = overlay;

            const okBtn = overlay.querySelector('[data-action="ok"]');

            const close = () => {
                overlay.classList.add('hiding');
                const container = overlay.querySelector('.modal-container');
                if (container) {
                    container.classList.add('hiding');
                }
                setTimeout(() => {
                    overlay.remove();
                    resolve();
                }, 200);
            };

            okBtn.addEventListener('click', close);

            // Cerrar con ESC
            const handleEsc = (e) => {
                if (e.key === 'Escape') {
                    close();
                    document.removeEventListener('keydown', handleEsc);
                }
            };
            document.addEventListener('keydown', handleEsc);

            // Cerrar al hacer click fuera
            overlay.addEventListener('click', (e) => {
                if (e.target === overlay) {
                    close();
                }
            });
        });
    }
}

// Instancias globales
const notifications = new NotificationSystem();
const modals = new ModalSystem();

// Funciones de conveniencia globales
function showNotification(message, type = 'info', duration = 3000) {
    return notifications.show(message, type, duration);
}

function showSuccess(message, duration = 3000) {
    return notifications.success(message, duration);
}

function showError(message, duration = 4000) {
    return notifications.error(message, duration);
}

function showWarning(message, duration = 3500) {
    return notifications.warning(message, duration);
}

function showInfo(message, duration = 3000) {
    return notifications.info(message, duration);
}

function showConfirm(message, title = 'Confirmar', type = 'warning') {
    return modals.confirm(message, title, type);
}

function showAlert(message, title = 'Aviso', type = 'info') {
    return modals.alert(message, title, type);
}

