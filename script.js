document.addEventListener('DOMContentLoaded', function() {
    var submenuToggles = document.querySelectorAll('.submenu-toggle');

    submenuToggles.forEach(function(toggle) {
        toggle.addEventListener('click', function(e) {
            e.preventDefault(); // Mencegah aksi default dari link
            var submenuList = this.nextElementSibling;
            var isVisible = submenuList.style.display === 'block';

            // Sembunyikan semua submenu lainnya
            document.querySelectorAll('.submenu-list').forEach(function(list) {
                list.style.display = 'none';
            });

            // Tampilkan atau sembunyikan submenu yang dipilih
            submenuList.style.display = isVisible ? 'none' : 'block';
        });
    });
});
