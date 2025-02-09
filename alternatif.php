<?php

include 'config.php';

function updateComparison($conn, $table_name, $alternatif_id, $column_name)
{

    $sql_delete = "DELETE FROM $table_name WHERE alternatif1_id = $alternatif_id OR alternatif2_id = $alternatif_id";
    $conn->query($sql_delete);


    $sql = "SELECT id_alternatif, $column_name FROM alternatif";
    $result = $conn->query($sql);
    $alternatifs = $result->fetch_all(MYSQLI_ASSOC);

    foreach ($alternatifs as $i => $alternatif1) {
        foreach ($alternatifs as $j => $alternatif2) {
            if ($i < $j) {
                $nilai1 = $alternatif1[$column_name];
                $nilai2 = $alternatif2[$column_name];


                $nilai = $nilai1 / $nilai2;


                $sql_insert = "INSERT INTO $table_name (alternatif1_id, alternatif2_id, nilai)
                               VALUES ({$alternatif1['id_alternatif']}, {$alternatif2['id_alternatif']}, $nilai)";
                $conn->query($sql_insert);
            }
        }
    }
}

if (isset($_POST['add_alternatif'])) {
    $nama = $_POST['nama'];
    $kelas = $_POST['kelas'];
    $nilai_raport = (int)$_POST['nilai_raport'];
    $extrakurikuler = (int)$_POST['extrakurikuler'];
    $prestasi = (int)$_POST['prestasi'];
    $absensi = (int)$_POST['absensi'];

    $sql = "INSERT INTO alternatif (nama, kelas, nilai_raport, extrakurikuler, prestasi, absensi)
            VALUES ('$nama', '$kelas', '$nilai_raport', '$extrakurikuler', '$prestasi', '$absensi')";
    $conn->query($sql);

    $id_alternatif = $conn->insert_id;


    updateComparison($conn, 'perbandingan_alternatif_raport', $id_alternatif, 'nilai_raport');
    updateComparison($conn, 'perbandingan_alternatif_ekstrakurikuler', $id_alternatif, 'extrakurikuler');
    updateComparison($conn, 'perbandingan_alternatif_prestasi', $id_alternatif, 'prestasi');
    updateComparison($conn, 'perbandingan_alternatif_absensi', $id_alternatif, 'absensi');

    header("Location: alternatif.php");
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM alternatif WHERE id_alternatif = $id";
    $conn->query($sql);

    updateComparison($conn, 'perbandingan_alternatif_raport', $id, 'nilai_raport');
    updateComparison($conn, 'perbandingan_alternatif_ekstrakurikuler', $id, 'extrakurikuler');
    updateComparison($conn, 'perbandingan_alternatif_prestasi', $id, 'prestasi');
    updateComparison($conn, 'perbandingan_alternatif_absensi', $id, 'absensi');

    header("Location: alternatif.php");
}

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $sql = "SELECT * FROM alternatif WHERE id_alternatif = $id";
    $result = $conn->query($sql);
    $alternatif = $result->fetch_assoc();
}

if (isset($_POST['edit_alternatif'])) {
    $id = $_POST['id_alternatif'];
    $nama = $_POST['nama'];
    $kelas = $_POST['kelas'];
    $nilai_raport = (int)$_POST['nilai_raport'];
    $extrakurikuler = (int)$_POST['extrakurikuler'];
    $prestasi = (int)$_POST['prestasi'];
    $absensi = (int)$_POST['absensi'];
    $sql = "UPDATE alternatif SET nama='$nama', kelas='$kelas', nilai_raport='$nilai_raport', 
            extrakurikuler='$extrakurikuler', prestasi='$prestasi', absensi='$absensi' WHERE id_alternatif=$id";
    $conn->query($sql);


    updateComparison($conn, 'perbandingan_alternatif_raport', $id, 'nilai_raport');
    updateComparison($conn, 'perbandingan_alternatif_ekstrakurikuler', $id, 'extrakurikuler');
    updateComparison($conn, 'perbandingan_alternatif_prestasi', $id, 'prestasi');
    updateComparison($conn, 'perbandingan_alternatif_absensi', $id, 'absensi');

    header("Location: alternatif.php");
}


$sql = "SELECT * FROM alternatif";
$result = $conn->query($sql);
?>



<!doctype html>
<html class="no-js" lang="en">



<head>
    <meta charset="utf-8">
    <link rel="icon" type="image/png" href="favicon.png">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Bustanul Ulum</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/png" href="assets/images/icon/favicon.ico">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/themify-icons.css">
    <link rel="stylesheet" href="assets/css/metisMenu.css">
    <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="assets/css/slicknav.min.css">
    <!-- amchart css -->
    <link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
    <!-- Start datatable css -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.jqueryui.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css">
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-144808195-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-144808195-1');
    </script>


    <link rel="stylesheet" href="assets/css/typography.css">
    <link rel="stylesheet" href="assets/css/default-css.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/responsive.css">

    <script src="assets/js/vendor/modernizr-2.8.3.min.js"></script>
</head>

<body>
    <div id="preloader">
        <div class="loader"></div>
    </div>
    <div class="page-container">
        <div class="sidebar-menu">
            <div class="sidebar-header">
                <div style="color:white">
                    <h3>Bustanul Ulum</3>
                </div>
            </div>
            <div class="main-menu">
                <div class="menu-inner">
                    <nav>
                        <ul class="metismenu" id="menu">
                            <li>
                                <a href="home.php"><i class="ti-dashboard"></i><span>Dashboard</span></a>
                            </li>
                            <li>
                                <a href="kriteria.php"><i class="ti-pencil-alt"></i><span>Kriteria</span></a>
                            </li>
                            <li class="active">
                                <a href="alternatif.php"><i class="ti-view-list"></i><span>Alternatif</span></a>
                            </li>
                            <li>
                                <a href="perbandingan_kriteria.php"><i class="ti-exchange-vertical"></i><span>Perbandingan Kriteria</span></a>
                            </li>
                            <li>
                                <a href="#" aria-expanded="false">
                                    <i class="ti-bar-chart"></i><span>Perbandingan Alternatif</span><span class="caret"></span>
                                </a>
                                <ul aria-expanded="false" class="collapse">
                                    <li><a href="raport.php"><i class="ti-file"></i><span style="margin-left: 5px;">Nilai Raport</span></a></li>
                                    <li><a href="ekstra.php"><i class="ti-plus"></i><span style="margin-left: 5px;">Ekstrakurikuler</span></a></li>
                                    <li><a href="prestasi.php"><i class="ti-pencil"></i><span style="margin-left: 5px;">Prestasi</span></a></li>
                                    <li><a href="absensi.php"><i class="ti-close"></i><span style="margin-left: 5px;">Absensi</span></a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="pendaftaran.php"><i class="ti-check-box"></i><span>Hasil</span></a>
                            </li>
                            <li>
                                <a href="pendaftaran.php"><i class="ti-calendar"></i><span>Periode</span></a>
                            </li>
                            <li>
                                <a href="pendaftaran.php"><i class="ti-user"></i><span>Profile</span></a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <div class="main-content">
            <div class="header-area py-3 bg-primary text-white">
                <div class="row align-items-center">
                    <div class="col-md-6 col-sm-8 clearfix">
                        <div class="nav-btn pull-left">
                            <span class="bg-white d-block mb-1" style="height: 3px; width: 25px;"></span>
                            <span class="bg-white d-block mb-1" style="height: 3px; width: 25px;"></span>
                            <span class="bg-white d-block" style="height: 3px; width: 25px;"></span>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-4 clearfix">
                        <ul class="notification-area pull-right">
                            <li>
                                <h3 class="fs-5">
                                    <div class="date text-end">
                                        <i class="ti-calendar"></i>
                                        <script type='text/javascript'>
                                            var months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                                            var myDays = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                                            var date = new Date();
                                            var day = date.getDate();
                                            var month = date.getMonth();
                                            var thisDay = date.getDay(),
                                                thisDay = myDays[thisDay];
                                            var yy = date.getYear();
                                            var year = (yy < 1000) ? yy + 1900 : yy;
                                            document.write(thisDay + ', ' + day + ' ' + months[month] + ' ' + year);
                                        </script>
                                    </div>
                                </h3>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="main-content-inner my-4">

                <h1>Manajemen Alternatif</h1>

                <form action="alternatif.php" method="post">
                    <input type="hidden" name="id_alternatif" value="<?= isset($alternatif) ? $alternatif['id_alternatif'] : '' ?>">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" name="nama" id="nama" class="form-control" value="<?= isset($alternatif) ? $alternatif['nama'] : '' ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="kelas" class="form-label">Kelas</label>
                        <input type="text" name="kelas" id="kelas" class="form-control" value="<?= isset($alternatif) ? $alternatif['kelas'] : '' ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="nilai_raport" class="form-label">Nilai Raport</label>
                        <input type="number" name="nilai_raport" id="nilai_raport" class="form-control" value="<?= isset($alternatif) ? $alternatif['nilai_raport'] : '' ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="extrakurikuler" class="form-label">Ekstrakurikuler</label>
                        <input type="number" name="extrakurikuler" id="extrakurikuler" class="form-control" value="<?= isset($alternatif) ? $alternatif['extrakurikuler'] : '' ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="prestasi" class="form-label">Prestasi</label>
                        <input type="number" name="prestasi" id="prestasi" class="form-control" value="<?= isset($alternatif) ? $alternatif['prestasi'] : '' ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="absensi" class="form-label">Absensi</label>
                        <input type="number" name="absensi" id="absensi" class="form-control" value="<?= isset($alternatif) ? $alternatif['absensi'] : '' ?>" required>
                    </div>

                    <?php if (isset($alternatif)): ?>
                        <button type="submit" name="edit_alternatif" class="btn btn-primary">Update</button>
                        <a href="alternatif.php" class="btn btn-secondary">Cancel</a>
                    <?php else: ?>
                        <button type="submit" name="add_alternatif" class="btn btn-success">Add</button>
                    <?php endif; ?>
                </form>

                <h2 class="mt-4">Daftar Alternatif</h2>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>Nilai Raport</th>
                            <th>Ekstrakurikuler</th>
                            <th>Prestasi</th>
                            <th>Absensi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $row['nama'] ?></td>
                                <td><?= $row['kelas'] ?></td>
                                <td><?= $row['nilai_raport'] ?></td>
                                <td><?= $row['extrakurikuler'] ?></td>
                                <td><?= $row['prestasi'] ?></td>
                                <td><?= $row['absensi'] ?></td>
                                <td>
                                    <a href="alternatif.php?edit=<?= $row['id_alternatif'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="alternatif.php?delete=<?= $row['id_alternatif'] ?>" class="btn btn-danger btn-sm">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

            </div>
        </div>

        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    </div>
    <?php include('footer.html'); ?>
    </div>
    <script src="assets/js/vendor/jquery-2.2.4.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/owl.carousel.min.js"></script>
    <script src="assets/js/metisMenu.min.js"></script>
    <script src="assets/js/jquery.slimscroll.min.js"></script>
    <script src="assets/js/jquery.slicknav.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
    <script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://cdn.zingchart.com/zingchart.min.js"></script>
    <script>
        zingchart.MODULESDIR = "https://cdn.zingchart.com/modules/";
        ZC.LICENSE = ["569d52cefae586f634c54f86dc99e6a9", "ee6b7db5b51705a13dc2339db3edaf6d"];
    </script>
    <script src="assets/js/line-chart.js"></script>
    <script src="assets/js/pie-chart.js"></script>
    <script src="assets/js/plugins.js"></script>
    <script src="assets/js/scripts.js"></script>
</body>

</html>