document.addEventListener('DOMContentLoaded', function () {
    ['nombre', 'descripcion', 'precio', 'category_id', 'user_id'].forEach(function (id) {
        const el = document.getElementById(id);
        if (el) el.addEventListener('change', function () { limpiarError(id); });
    });
});
