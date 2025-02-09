<?php
include 'config.php';


if (isset($_POST['add_kriteria'])) {
    $nama_kriteria = $_POST['nama_kriteria'];

    $sql = "INSERT INTO kriteria (nama_kriteria) VALUES ('$nama_kriteria')";
    $conn->query($sql);
}


if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM kriteria WHERE id_kriteria = $id";
    $conn->query($sql);
}


if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $sql = "SELECT * FROM kriteria WHERE id_kriteria = $id";
    $result = $conn->query($sql);
    $kriteria = $result->fetch_assoc();
}

if (isset($_POST['edit_kriteria'])) {
    $id = $_POST['id_kriteria'];
    $nama_kriteria = $_POST['nama_kriteria'];

    $sql = "UPDATE kriteria SET nama_kriteria='$nama_kriteria' WHERE id_kriteria=$id";
    $conn->query($sql);
    header("Location: kriteria.php");
}
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
                            <li class="active">
                                <a href="kriteria.php"><i class="ti-pencil-alt"></i><span>Kriteria</span></a>
                            </li>
                            <li>
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
                                <a href="hasil.php"><i class="ti-check-box"></i><span>Rangking</span></a>
                            </li>
                            <li>
                                <a href="periode.php"><i class="ti-calendar"></i><span>Periode</span></a>
                            </li>
                            <li>
                                <a href="profile.php"><i class="ti-user"></i><span>Profile</span></a>
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

                <h1>Manajemen Kriteria</h1>


                <form action="" method="POST" class="mb-3">
                    <div class="mb-3">
                        <label for="nama_kriteria" class="form-label">Nama Kriteria</label>
                        <input type="text" class="form-control" id="nama_kriteria" name="nama_kriteria" placeholder="Nama Kriteria" required>
                    </div>

                    <button type="submit" name="add_kriteria" class="btn btn-primary">Add</button>
                </form>

                <?php if (isset($kriteria)) : ?>
                    <h2>Edit Kriteria</h2>
                    <form action="" method="POST" class="mb-3">
                        <input type="hidden" name="id_kriteria" value="<?php echo $kriteria['id_kriteria']; ?>">
                        <div class="mb-3">
                            <label for="nama_kriteria_edit" class="form-label">Nama Kriteria</label>
                            <input type="text" class="form-control" id="nama_kriteria_edit" name="nama_kriteria" value="<?php echo $kriteria['nama_kriteria']; ?>" required>
                        </div>

                        <button type="submit" name="edit_kriteria" class="btn btn-primary">Update</button>
                    </form>
                <?php endif; ?>

                <h2>Daftar Kriteria</h2>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nomor</th>
                            <th>Nama Kriteria</th>

                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM kriteria";
                        $result = $conn->query($sql);
                        $no = 1; // Inisialisasi nomor urut

                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $no++ . "</td>"; // Menampilkan nomor urut
                            echo "<td>" . $row['nama_kriteria'] . "</td>";
                            echo "<td>
<a href='kriteria.php?edit=" . $row['id_kriteria'] . "' class='btn btn-warning btn-sm'>Edit</a>
<a href='kriteria.php?delete=" . $row['id_kriteria'] . "' class='btn btn-danger btn-sm' onclick=\"return confirm('Anda yakin ingin menghapus kriteria ini?')\">Delete</a>
</td>";
                            echo "</tr>";
                        }
                        ?>
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