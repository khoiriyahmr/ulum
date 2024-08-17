<?php
include '../config.php';


if (isset($_POST['add_alternatif'])) {
    $nama = $_POST['nama'];
    $kelas = $_POST['kelas'];
    $nilai_raport = $_POST['nilai_raport'];
    $extrakurikuler = $_POST['extrakurikuler'];
    $prestasi = $_POST['prestasi'];
    $absensi = $_POST['absensi'];


    $sql = "INSERT INTO alternatif (nama, kelas, nilai_raport, extrakurikuler, prestasi, absensi)
            VALUES ('$nama', '$kelas', '$nilai_raport', '$extrakurikuler', '$prestasi', '$absensi')";
    $conn->query($sql);


    $id_alternatif = $conn->insert_id;


    $tahun_sekarang = date('Y');
    $sql_periode = "INSERT INTO periode (tahun, id_alternatif) VALUES ('$tahun_sekarang', $id_alternatif)";
    $conn->query($sql_periode);
}


if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM alternatif WHERE id_alternatif = $id";
    $conn->query($sql);
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
    $nilai_raport = $_POST['nilai_raport'];
    $extrakurikuler = $_POST['extrakurikuler'];
    $prestasi = $_POST['prestasi'];
    $absensi = $_POST['absensi'];
    $sql = "UPDATE alternatif SET nama='$nama', kelas='$kelas', nilai_raport='$nilai_raport', 
            extrakurikuler='$extrakurikuler', prestasi='$prestasi', absensi='$absensi' WHERE id_alternatif=$id";
    $conn->query($sql);
    header("Location: alternatif.php");
}


$kelas_filter = isset($_GET['kelas']) ? $_GET['kelas'] : 'all';
$filter_query = $kelas_filter == 'all' ? "" : "WHERE kelas = '$kelas_filter'";


$kelas_query = "SELECT DISTINCT kelas FROM alternatif";
$kelas_result = $conn->query($kelas_query);


$sql = "SELECT * FROM alternatif $filter_query";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Manajemen Alternatif</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">


</head>

<body>
    <?php include '../navbar.php'; ?>

    <div class="container mt-4">
        <h1 class="mb-4">Manajemen Alternatif</h1>
        <div class="mb-4">
            <h2>Tambah Alternatif</h2>
            <form action="" method="POST">
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama</label>
                    <input type="text" id="nama" name="nama" class="form-control" placeholder="Nama" required>
                </div>
                <div class="mb-3">
                    <label for="kelas" class="form-label">Kelas</label>
                    <input type="text" id="kelas" name="kelas" class="form-control" placeholder="Kelas" required>
                </div>
                <div class="mb-3">
                    <label for="nilai_raport" class="form-label">Nilai Raport</label>
                    <input type="number" id="nilai_raport" name="nilai_raport" class="form-control" step="0.01" placeholder="Nilai Raport" required>
                </div>
                <div class="mb-3">
                    <label for="extrakurikuler" class="form-label">Nilai Ekstrakurikuler</label>
                    <input type="number" id="extrakurikuler" name="extrakurikuler" class="form-control" step="0.01" placeholder="Nilai Ekstrakurikuler" required>
                </div>
                <div class="mb-3">
                    <label for="prestasi" class="form-label">Nilai Prestasi</label>
                    <input type="number" id="prestasi" name="prestasi" class="form-control" step="0.01" placeholder="Nilai Prestasi" required>
                </div>
                <div class="mb-3">
                    <label for="absensi" class="form-label">Nilai Absensi</label>
                    <input type="number" id="absensi" name="absensi" class="form-control" step="0.01" placeholder="Nilai Absensi" required>
                </div>
                <button type="submit" name="add_alternatif" class="btn btn-primary">Add</button>
            </form>
        </div>
        <?php if (isset($alternatif)) : ?>
            <div class="mb-4">
                <h2>Edit Alternatif</h2>
                <form action="" method="POST">
                    <input type="hidden" name="id_alternatif" value="<?php echo $alternatif['id_alternatif']; ?>">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" id="nama" name="nama" class="form-control" value="<?php echo $alternatif['nama']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="kelas" class="form-label">Kelas</label>
                        <input type="text" id="kelas" name="kelas" class="form-control" value="<?php echo $alternatif['kelas']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="nilai_raport" class="form-label">Nilai Raport</label>
                        <input type="number" id="nilai_raport" name="nilai_raport" class="form-control" value="<?php echo $alternatif['nilai_raport']; ?>" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label for="extrakurikuler" class="form-label">Nilai Ekstrakurikuler</label>
                        <input type="number" id="extrakurikuler" name="extrakurikuler" class="form-control" value="<?php echo $alternatif['extrakurikuler']; ?>" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label for="prestasi" class="form-label">Nilai Prestasi</label>
                        <input type="number" id="prestasi" name="prestasi" class="form-control" value="<?php echo $alternatif['prestasi']; ?>" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label for="absensi" class="form-label">Nilai Absensi</label>
                        <input type="number" id="absensi" name="absensi" class="form-control" value="<?php echo $alternatif['absensi']; ?>" step="0.01" required>
                    </div>
                    <button type="submit" name="edit_alternatif" class="btn btn-warning">Update</button>
                </form>
            </div>
        <?php endif; ?>
        <form method="GET" class="mb-4">
            <div class="form-group">
                <label for="kelas">Filter Kelas:</label>
                <select name="kelas" id="kelas" class="form-select" onchange="this.form.submit()">
                    <option value="all" <?php echo $kelas_filter == 'all' ? 'selected' : ''; ?>>All</option>
                    <?php while ($kelas_row = $kelas_result->fetch_assoc()) : ?>
                        <option value="<?php echo $kelas_row['kelas']; ?>" <?php echo $kelas_row['kelas'] == $kelas_filter ? 'selected' : ''; ?>>
                            <?php echo $kelas_row['kelas']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
        </form>

        <h2>Daftar Alternatif</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nomor</th>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th>Nilai Raport</th>
                    <th>Nilai Ekstrakurikuler</th>
                    <th>Nilai Prestasi</th>
                    <th>Nilai Absensi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $no++ . "</td>";
                    echo "<td>" . $row['nama'] . "</td>";
                    echo "<td>" . $row['kelas'] . "</td>";
                    echo "<td>" . $row['nilai_raport'] . "</td>";
                    echo "<td>" . $row['extrakurikuler'] . "</td>";
                    echo "<td>" . $row['prestasi'] . "</td>";
                    echo "<td>" . $row['absensi'] . "</td>";
                    echo "<td>
                            <a href='alternatif.php?edit=" . $row['id_alternatif'] . "' class='btn btn-warning btn-sm'>Edit</a>
                            <a href='alternatif.php?delete=" . $row['id_alternatif'] . "' class='btn btn-danger btn-sm' onclick=\"return confirm('Anda yakin ingin menghapus alternatif ini?')\">Delete</a>
                          </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>