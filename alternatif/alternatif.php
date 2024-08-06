<?php
include '../config.php';

// Menambah Alternatif
if (isset($_POST['add_alternatif'])) {
    $nama = $_POST['nama'];
    $kelas = $_POST['kelas'];
    $nisn = $_POST['nisn'];
    $nilai_raport = $_POST['nilai_raport'];
    $nilai_ekstrakurikuler = $_POST['nilai_ekstrakurikuler'];
    $nilai_prestasi = $_POST['nilai_prestasi'];
    $nilai_absensi = $_POST['nilai_absensi'];
    $sql = "INSERT INTO siswa (nama, kelas, nisn, nilai_raport, nilai_ekstrakurikuler, nilai_prestasi, nilai_absensi)
            VALUES ('$nama', '$kelas', '$nisn', '$nilai_raport', '$nilai_ekstrakurikuler', '$nilai_prestasi', '$nilai_absensi')";
    $conn->query($sql);
}

// Menghapus Alternatif
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM siswa WHERE id_siswa = $id";
    $conn->query($sql);
}

// Mengedit Alternatif
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $sql = "SELECT * FROM siswa WHERE id_siswa = $id";
    $result = $conn->query($sql);
    $alternatif = $result->fetch_assoc();
}

// Memproses Update Alternatif
if (isset($_POST['edit_alternatif'])) {
    $id = $_POST['id_siswa'];
    $nama = $_POST['nama'];
    $kelas = $_POST['kelas'];
    $nisn = $_POST['nisn'];
    $nilai_raport = $_POST['nilai_raport'];
    $nilai_ekstrakurikuler = $_POST['nilai_ekstrakurikuler'];
    $nilai_prestasi = $_POST['nilai_prestasi'];
    $nilai_absensi = $_POST['nilai_absensi'];
    $sql = "UPDATE siswa SET nama='$nama', kelas='$kelas', nisn='$nisn', nilai_raport='$nilai_raport', 
            nilai_ekstrakurikuler='$nilai_ekstrakurikuler', nilai_prestasi='$nilai_prestasi', nilai_absensi='$nilai_absensi' WHERE id_siswa=$id";
    $conn->query($sql);
    header("Location: alternatif.php");
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Manajemen Alternatif</title>
</head>

<body>
    <h1>Manajemen Alternatif</h1>

    <!-- Formulir Tambah Alternatif -->
    <form action="" method="POST">
        <input type="text" name="nama" placeholder="Nama" required>
        <input type="text" name="kelas" placeholder="Kelas" required>
        <input type="text" name="nisn" placeholder="NISN" required>
        <input type="number" step="0.01" name="nilai_raport" placeholder="Nilai Raport" required>
        <input type="number" step="0.01" name="nilai_ekstrakurikuler" placeholder="Nilai Ekstrakurikuler" required>
        <input type="number" step="0.01" name="nilai_prestasi" placeholder="Nilai Prestasi" required>
        <input type="number" step="0.01" name="nilai_absensi" placeholder="Nilai Absensi" required>
        <button type="submit" name="add_alternatif">Add</button>
    </form>

    <!-- Formulir Edit Alternatif -->
    <?php if (isset($alternatif)) : ?>
        <h2>Edit Alternatif</h2>
        <form action="" method="POST">
            <input type="hidden" name="id_siswa" value="<?php echo $alternatif['id_siswa']; ?>">
            <input type="text" name="nama" value="<?php echo $alternatif['nama']; ?>" required>
            <input type="text" name="kelas" value="<?php echo $alternatif['kelas']; ?>" required>
            <input type="text" name="nisn" value="<?php echo $alternatif['nisn']; ?>" required>
            <input type="number" step="0.01" name="nilai_raport" value="<?php echo $alternatif['nilai_raport']; ?>" required>
            <input type="number" step="0.01" name="nilai_ekstrakurikuler" value="<?php echo $alternatif['nilai_ekstrakurikuler']; ?>" required>
            <input type="number" step="0.01" name="nilai_prestasi" value="<?php echo $alternatif['nilai_prestasi']; ?>" required>
            <input type="number" step="0.01" name="nilai_absensi" value="<?php echo $alternatif['nilai_absensi']; ?>" required>
            <button type="submit" name="edit_alternatif">Update</button>
        </form>
    <?php endif; ?>

    <h2>Daftar Alternatif</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Kelas</th>
            <th>NISN</th>
            <th>Nilai Raport</th>
            <th>Nilai Ekstrakurikuler</th>
            <th>Nilai Prestasi</th>
            <th>Nilai Absensi</th>
            <th>Aksi</th>
        </tr>
        <?php
        $sql = "SELECT * FROM siswa";
        $result = $conn->query($sql);

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['id_siswa'] . "</td>";
            echo "<td>" . $row['nama'] . "</td>";
            echo "<td>" . $row['kelas'] . "</td>";
            echo "<td>" . $row['nisn'] . "</td>";
            echo "<td>" . $row['nilai_raport'] . "</td>";
            echo "<td>" . $row['nilai_ekstrakurikuler'] . "</td>";
            echo "<td>" . $row['nilai_prestasi'] . "</td>";
            echo "<td>" . $row['nilai_absensi'] . "</td>";
            echo "<td>
                    <a href='alternatif.php?edit=" . $row['id_siswa'] . "'>Edit</a>
                    <a href='alternatif.php?delete=" . $row['id_siswa'] . "' onclick=\"return confirm('Anda yakin ingin menghapus alternatif ini?')\">Delete</a>
                  </td>";
            echo "</tr>";
        }
        ?>
    </table>
</body>

</html>