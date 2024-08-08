<!-- navbar.php -->
<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a id="home-link" class="nav-link active" aria-current="page" href="/ulum/home.php">Home</a>
        </li>
        <li class="nav-item">
          <a id="kriteria-link" class="nav-link" href="/ulum/kriteria/kriteria.php">Kriteria</a>
        </li>
        <li class="nav-item">
          <a id="alternatif-link" class="nav-link" href="/ulum/alternatif/alternatif.php">Alternatif</a>
        </li>
        <li class="nav-item">
          <a id="perbandingan-kriteria-link" class="nav-link" href="/ulum/perbandingan/kriteria/perbandingan_kriteria.php">Perbandingan Kriteria</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Perbandingan Alternatif
          </a>
          <ul class="dropdown-menu">
            <li><a id="nilai-raport-link" class="dropdown-item" href="/ulum/perbandingan/alternatif/nilai_raport.php">Nilai Raport</a></li>
            <li><a id="ekstrakurikuler-link" class="dropdown-item" href="/ulum/perbandingan/alternatif/ekstrakurikuler.php">Ekstrakurikuler</a></li>
            <li><a id="prestasi-link" class="dropdown-item" href="/ulum/perbandingan/alternatif/prestasi.php">Prestasi</a></li>
            <li><a id="absensi-link" class="dropdown-item" href="/ulum/perbandingan/alternatif/absensi.php">Absensi</a></li>
          </ul>
        </li>
        <li class="nav-item">
          <a id="hasil-link" class="nav-link" href="/ulum/hasil/hasil.php">Hasil</a>
        </li>
        <li class="nav-item">
          <a id="periode-link" class="nav-link" href="/ulum/periode/periode.php">Periode</a>
        </li>
        <li class="nav-item">
          <a id="profile-link" class="nav-link" href="/ulum/profile/profile.php">Profile</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const currentURL = window.location.href;

    document.querySelectorAll('.nav-link').forEach(function(navLink) {
      if (navLink.href === currentURL) {
        navLink.addEventListener('click', function(event) {
          event.preventDefault();
        });
      }
    });

    document.querySelectorAll('.dropdown-item').forEach(function(dropdownItem) {
      if (dropdownItem.href === currentURL) {
        dropdownItem.addEventListener('click', function(event) {
          event.preventDefault();
        });
      }
    });
  });
</script>
