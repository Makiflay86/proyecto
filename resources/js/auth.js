window.limpiarError = function limpiarError(id) {
    const input = document.getElementById(id);
    if (!input) return;
    const group = input.closest('.field-group');
    input.classList.remove('border-red-500');
    input.classList.add('border-gray-300');
    const asterisk = group.querySelector('.asterisk');
    if (asterisk) { asterisk.classList.remove('text-red-500'); asterisk.classList.add('text-gray-400'); }
    const error = group.querySelector('.field-error');
    if (error) error.remove();
}

document.addEventListener('DOMContentLoaded', function () {
    ['email', 'password', 'name', 'password_confirmation'].forEach(function (id) {
        const el = document.getElementById(id);
        if (el) el.addEventListener('change', function () { limpiarError(id); });
    });
});

window.togglePassword = function togglePassword(id) {
    const input = document.getElementById(id);
    const eyeOpen = document.getElementById(id + '-eye-open');
    const eyeClosed = document.getElementById(id + '-eye-closed');
    if (input.type === 'password') {
        input.type = 'text';
        eyeOpen.classList.add('hidden');
        eyeClosed.classList.remove('hidden');
    } else {
        input.type = 'password';
        eyeOpen.classList.remove('hidden');
        eyeClosed.classList.add('hidden');
    }
}
