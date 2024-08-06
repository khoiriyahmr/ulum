<?php
include 'config.php';

$page = isset($_GET['page']) ? $_GET['page'] : 'home';

include 'header.php';

switch ($page) {
    case 'home':
        include 'home.php';
        break;
    case 'kriteria':
        include 'kriteria/kriteria.php';
        break;
    case 'alternatif':
        include 'alternatif/alternatif.php';
        break;
    case 'perbandingan_kriteria':
        include 'perbandingan/kriteria/perbandingan_kriteria.php';
        break;
    case 'nilai_raport':
        include 'perbandingan/alternatif/nilai_raport.php';
        break;
    case 'ekstrakurikuler':
        include 'perbandingan/alternatif/ekstrakurikuler.php';
        break;
    case 'prestasi':
        include 'perbandingan/alternatif/prestasi.php';
        break;
    case 'absensi':
        include 'perbandingan/alternatif/absensi.php';
        break;
    case 'hasil':
        include 'hasil/hasil.php';
        break;
    case 'periode':
        include 'periode/periode.php';
        break;
    default:
        include 'home.php';
        break;
}

include 'footer.php';
