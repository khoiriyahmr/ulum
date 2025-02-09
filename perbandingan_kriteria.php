<?php
include 'config.php';


function calculate_ahp()
{
    global $conn;

    $sql = "SELECT * FROM perbandingan_kriteria";
    $result = $conn->query($sql);

    $matrix = [];
    $kriteria = [];

    while ($row = $result->fetch_assoc()) {
        $kriteria1_id = $row['kriteria1_id'];
        $kriteria2_id = $row['kriteria2_id'];
        $nilai = $row['nilai'];

        if (!isset($matrix[$kriteria1_id])) {
            $matrix[$kriteria1_id] = [];
        }
        if (!isset($matrix[$kriteria2_id])) {
            $matrix[$kriteria2_id] = [];
        }

        $matrix[$kriteria1_id][$kriteria2_id] = $nilai;
        $matrix[$kriteria2_id][$kriteria1_id] = 1 / $nilai;

        $kriteria[$kriteria1_id] = 1;
        $kriteria[$kriteria2_id] = 1;
    }

    $num_kriteria = count($matrix);
    $normalized_matrix = [];

    foreach ($matrix as $row_id => $row) {
        $sum = array_sum($row);
        foreach ($row as $col_id => $value) {
            $normalized_matrix[$row_id][$col_id] = $value / $sum;
        }
    }

    $weights = [];
    foreach ($normalized_matrix as $row_id => $row) {
        $weights[$row_id] = array_sum($row) / $num_kriteria;
    }

    echo "";
}


if (isset($_POST['add_perbandingan'])) {
    $kriteria1_id = $_POST['kriteria1_id'];
    $kriteria2_id = $_POST['kriteria2_id'];
    $nilai = str_replace(',', '.', $_POST['nilai']);

    if (!filter_var($nilai, FILTER_VALIDATE_FLOAT)) {
        echo "<p>Error: Nilai harus berupa angka desimal yang valid.</p>";
        exit;
    }

    $sql = "INSERT INTO perbandingan_kriteria (kriteria1_id, kriteria2_id, nilai) VALUES ('$kriteria1_id', '$kriteria2_id', '$nilai')";
    if ($conn->query($sql) === TRUE) {
        echo "<p>Perbandingan kriteria berhasil ditambahkan.</p>";
        calculate_ahp();
    } else {
        echo "<p>Error: " . $conn->error . "</p>";
    }
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM perbandingan_kriteria WHERE id_perbandingan = $id";
    if ($conn->query($sql) === TRUE) {
        echo "<p>Perbandingan kriteria berhasil dihapus.</p>";
        calculate_ahp();
    } else {
        echo "<p>Error: " . $conn->error . "</p>";
    }
}

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $sql = "SELECT * FROM perbandingan_kriteria WHERE id_perbandingan = $id";
    $result = $conn->query($sql);
    $perbandingan = $result->fetch_assoc();
}

if (isset($_POST['edit_perbandingan'])) {
    $id = $_POST['id_perbandingan'];
    $kriteria1_id = $_POST['kriteria1_id'];
    $kriteria2_id = $_POST['kriteria2_id'];
    $nilai = str_replace(',', '.', $_POST['nilai']);

    if (!filter_var($nilai, FILTER_VALIDATE_FLOAT)) {
        echo "<p>Error: Nilai harus berupa angka desimal yang valid.</p>";
        exit;
    }

    $sql = "UPDATE perbandingan_kriteria SET kriteria1_id='$kriteria1_id', kriteria2_id='$kriteria2_id', nilai='$nilai' WHERE id_perbandingan=$id";
    if ($conn->query($sql) === TRUE) {
        echo "<p>Perbandingan kriteria berhasil diperbarui.</p>";
        calculate_ahp();
    } else {
        echo "<p>Error: " . $conn->error . "</p>";
    }
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
                            <li>
                                <a href="kriteria.php"><i class="ti-pencil-alt"></i><span>Kriteria</span></a>
                            </li>
                            <li>
                                <a href="alternatif.php"><i class="ti-view-list"></i><span>Alternatif</span></a>
                            </li>
                            <li class="active">
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

                <h2>Perbandingan Kriteria</h2>
                <div class="row">
                    <div class="col-md-6">
                        <form action="" method="POST">
                            <div class="mb-3">
                                <label for="kriteria1_id" class="form-label">Kriteria 1</label>
                                <select name="kriteria1_id" id="kriteria1_id" class="form-select" required>
                                    <option value="">Pilih Kriteria 1</option>
                                    <?php
                                    $sql = "SELECT * FROM kriteria";
                                    $result = $conn->query($sql);
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<option value='" . $row['id_kriteria'] . "'";
                                        if (isset($perbandingan) && $perbandingan['kriteria1_id'] == $row['id_kriteria']) {
                                            echo " selected";
                                        }
                                        echo ">" . $row['nama_kriteria'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="kriteria2_id" class="form-label">Kriteria 2</label>
                                <select name="kriteria2_id" id="kriteria2_id" class="form-select" required>
                                    <option value="">Pilih Kriteria 2</option>
                                    <?php
                                    $sql = "SELECT * FROM kriteria";
                                    $result = $conn->query($sql);
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<option value='" . $row['id_kriteria'] . "'";
                                        if (isset($perbandingan) && $perbandingan['kriteria2_id'] == $row['id_kriteria']) {
                                            echo " selected";
                                        }
                                        echo ">" . $row['nama_kriteria'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="nilai" class="form-label">Nilai</label>
                                <input type="text" name="nilai" id="nilai" class="form-control" placeholder="Nilai (gunakan titik sebagai pemisah desimal)" value="<?php echo isset($perbandingan) ? $perbandingan['nilai'] : ''; ?>" required>
                            </div>
                            <?php if (isset($perbandingan)) : ?>
                                <input type="hidden" name="id_perbandingan" value="<?php echo $perbandingan['id_perbandingan']; ?>">
                                <button type="submit" name="edit_perbandingan" class="btn btn-primary">Update</button>
                            <?php else : ?>
                                <button type="submit" name="add_perbandingan" class="btn btn-primary">Add</button>
                            <?php endif; ?>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <h5 class="mt-4">Tabel Intensitas Kepentingan Metode AHP</h5>
                        <table class="table table-bordered mt-3">
                            <thead>
                                <tr>
                                    <th>Intensitas Kepentingan</th>
                                    <th>Definisi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Sama pentingnya dibanding dengan yang lain</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Sedikit lebih penting dibanding yang lain</td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>Cukup penting dibanding dengan yang lain</td>
                                </tr>
                                <tr>
                                    <td>7</td>
                                    <td>Sangat penting dibanding dengan yang lain</td>
                                </tr>
                                <tr>
                                    <td>9</td>
                                    <td>Ekstrim pentingnya dibanding yang lain</td>
                                </tr>
                                <tr>
                                    <td>2,4,6,8</td>
                                    <td>Nilai diantara dua penilaian yang berdekatan</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <h3 class="mt-4">Daftar Perbandingan Kriteria</h3>

                <?php
                $sql = "SELECT pk.kriteria1_id, pk.kriteria2_id, pk.nilai, k1.nama_kriteria AS kriteria1, k2.nama_kriteria AS kriteria2
            FROM perbandingan_kriteria pk
            JOIN kriteria k1 ON pk.kriteria1_id = k1.id_kriteria
            JOIN kriteria k2 ON pk.kriteria2_id = k2.id_kriteria";
                $result = $conn->query($sql);
                ?>

                <table class="table table-bordered mt-3">
                    <thead>
                        <tr>
                            <th>Kriteria 1</th>
                            <?php
                            $sql = "SELECT * FROM kriteria";
                            $kriteria_result = $conn->query($sql);
                            $kriteria_list = [];
                            while ($row = $kriteria_result->fetch_assoc()) {
                                $kriteria_list[$row['id_kriteria']] = $row['nama_kriteria'];
                            }

                            foreach ($kriteria_list as $kriteria_id => $kriteria_name) : ?>
                                <th><?php echo $kriteria_name; ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($kriteria_list as $row_id => $row_name) : ?>
                            <tr>
                                <td><?php echo $row_name; ?></td>
                                <?php foreach ($kriteria_list as $col_id => $col_name) : ?>
                                    <td>
                                        <?php
                                        $value = 0;
                                        foreach ($result as $row) {
                                            if ($row['kriteria1_id'] == $row_id && $row['kriteria2_id'] == $col_id) {
                                                $value = $row['nilai'];
                                            } elseif ($row['kriteria1_id'] == $col_id && $row['kriteria2_id'] == $row_id) {
                                                $value = 1 / $row['nilai'];
                                            }
                                        }

                                        if ($value) {
                                            echo rtrim(rtrim(number_format($value, 8, '.', ''), '0'), '.');
                                        } else {
                                            echo $row_id == $col_id ? '1' : '0';
                                        }
                                        ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
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