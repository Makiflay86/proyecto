document.addEventListener('DOMContentLoaded', function () {
    ['name', 'email', 'password', 'password_confirmation'].forEach(function (id) {
        const el = document.getElementById(id);
        if (el) el.addEventListener('change', function () { limpiarError(id); });
    });
});
