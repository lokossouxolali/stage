import './bootstrap';
import * as bootstrap from 'bootstrap';

window.bootstrap = bootstrap;

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.toast').forEach((toastEl) => {
        const toast = new window.bootstrap.Toast(toastEl);
        toast.show();
    });

    document.querySelectorAll('form[data-confirm]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            if (!window.confirm(form.dataset.confirm)) {
                event.preventDefault();
            }
        });
    });

    document.querySelectorAll('form').forEach((form) => {
        form.addEventListener('submit', () => {
            const submitButton = form.querySelector('button[type="submit"][data-loading-text]');
            if (!submitButton) {
                return;
            }

            submitButton.disabled = true;
            submitButton.dataset.originalText = submitButton.innerHTML;
            submitButton.innerHTML = `<i class="fas fa-spinner fa-spin me-2"></i>${submitButton.dataset.loadingText}`;
        });
    });
});
