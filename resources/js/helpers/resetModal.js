export function resetModal(modalId, options = {}) {
    const modalEl = document.getElementById(modalId);
    if (!modalEl) return;

    const form = modalEl.querySelector('form');
    if (form) form.reset();

    modalEl.querySelectorAll('span.text-danger').forEach(el => el.innerText = '');
    modalEl.querySelectorAll('.is-invalid').forEach(input => input.classList.remove('is-invalid'));
}
