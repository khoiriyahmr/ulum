<?php
include '../config.php';

// Menambah Alternatif
if (isset($_POST['add_alternatif'])) {
    $nama = $_POST['nama'];
    $kelas = $_POST['kelas'];

    $nilai_raport = $_POST['nilai_raport'];
    $extrakurikuler = $_POST['extrakurikuler'];
    $prestasi = $_POST['prestasi'];
    $absensi = $_POST['absensi'];
    $sql = "INSERT INTO alternatif (nama, kelas,  nilai_raport, extrakurikuler, prestasi, absensi)
            VALUES ('$nama', '$kelas',  '$nilai_raport', '$extrakurikuler', '$prestasi', '$absensi')";
    $conn->query($sql);
}

// Menghapus Alternatif
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM alternatif WHERE id_alternatif = $id";
    $conn->query($sql);
}

// Mengedit Alternatif
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $sql = "SELECT * FROM alternatif WHERE id_alternatif = $id";
    $result = $conn->query($sql);
    $alternatif = $result->fetch_assoc();
}

// Memproses Update Alternatif
if (isset($_POST['edit_alternatif'])) {
    $id = $_POST['id_alternatif'];
    $nama = $_POST['nama'];
    $kelas = $_POST['kelas'];

    $nilai_raport = $_POST['nilai_raport'];
    $extrakurikuler = $_POST['extrakurikuler'];
    $prestasi = $_POST['prestasi'];
    $absensi = $_POST['absensi'];
    $sql = "UPDATE alternatif SET nama='$nama', kelas='$kelas',  nilai_raport='$nilai_raport', 
            extrakurikuler='$extrakurikuler', prestasi='$prestasi', absensi='$absensi' WHERE id_alternatif=$id";
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

        <input type="number" step="0.01" name="nilai_raport" placeholder="Nilai Raport" required>
        <input type="number" step="0.01" name="extrakurikuler" placeholder="Nilai Ekstrakurikuler" required>
        <input type="number" step="0.01" name="prestasi" placeholder="Nilai Prestasi" required>
        <input type="number" step="0.01" name="absensi" placeholder="Nilai Absensi" required>
        <button type="submit" name="add_alternatif">Add</button>
    </form>

    <!-- Formulir Edit Alternatif -->
    <?php if (isset($alternatif)) : ?>
        <h2>Edit Alternatif</h2>
        <form action="" method="POST">
            <input type="hidden" name="id_alternatif" value="<?php echo $alternatif['id_alternatif']; ?>">
            <input type="text" name="nama" value="<?php echo $alternatif['nama']; ?>" required>
            <input type="text" name="kelas" value="<?php echo $alternatif['kelas']; ?>" required>

            <input type="number" step="0.01" name="nilai_raport" value="<?php echo $alternatif['nilai_raport']; ?>" required>
            <input type="number" step="0.01" name="extrakurikuler" value="<?php echo $alternatif['extrakurikuler']; ?>" required>
            <input type="number" step="0.01" name="prestasi" value="<?php echo $alternatif['prestasi']; ?>" required>
            <input type="number" step="0.01" name="absensi" value="<?php echo $alternatif['absensi']; ?>" required>
            <button type="submit" name="edit_alternatif">Update</button>
        </form>
    <?php endif; ?>

    <h2>Daftar Alternatif</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Kelas</th>

            <th>Nilai Raport</th>
            <th>Nilai Ekstrakurikuler</th>
            <th>Nilai Prestasi</th>
            <th>Nilai Absensi</th>
            <th>Aksi</th>
        </tr>
        <?php
        $sql = "SELECT * FROM alternatif";
        $result = $conn->query($sql);

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['id_alternatif'] . "</td>";
            echo "<td>" . $row['nama'] . "</td>";
            echo "<td>" . $row['kelas'] . "</td>";

            echo "<td>" . $row['nilai_raport'] . "</td>";
            echo "<td>" . $row['extrakurikuler'] . "</td>";
            echo "<td>" . $row['prestasi'] . "</td>";
            echo "<td>" . $row['absensi'] . "</td>";
            echo "<td>
                    <a href='alternatif.php?edit=" . $row['id_alternatif'] . "'>Edit</a>
                    <a href='alternatif.php?delete=" . $row['id_alternatif'] . "' onclick=\"return confirm('Anda yakin ingin menghapus alternatif ini?')\">Delete</a>
                  </td>";
            echo "</tr>";
        }
        ?>
    </table>
</body>

</html>