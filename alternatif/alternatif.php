<?php
include '../config.php';

function updateComparison($conn, $table_name, $alternatif_id, $column_name)
{
    // Hapus perbandingan yang ada untuk alternatif ini
    $sql_delete = "DELETE FROM $table_name WHERE alternatif1_id = $alternatif_id OR alternatif2_id = $alternatif_id";
    $conn->query($sql_delete);

    // Ambil semua alternatif
    $sql = "SELECT id_alternatif, $column_name FROM alternatif";
    $result = $conn->query($sql);
    $alternatifs = $result->fetch_all(MYSQLI_ASSOC);

    foreach ($alternatifs as $i => $alternatif1) {
        foreach ($alternatifs as $j => $alternatif2) {
            if ($i < $j) {
                $nilai1 = $alternatif1[$column_name];
                $nilai2 = $alternatif2[$column_name];

                // Hitung nilai perbandingan
                $nilai = $nilai1 / $nilai2;

                // Simpan ke tabel perbandingan
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

    // Update tabel perbandingan
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

    // Update tabel perbandingan
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

    // Update tabel perbandingan
    updateComparison($conn, 'perbandingan_alternatif_raport', $id, 'nilai_raport');
    updateComparison($conn, 'perbandingan_alternatif_ekstrakurikuler', $id, 'extrakurikuler');
    updateComparison($conn, 'perbandingan_alternatif_prestasi', $id, 'prestasi');
    updateComparison($conn, 'perbandingan_alternatif_absensi', $id, 'absensi');

    header("Location: alternatif.php");
}

// Menampilkan data alternatif
$sql = "SELECT * FROM alternatif";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Alternatif</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-4">

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
                <th>ID</th>
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
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id_alternatif'] ?></td>
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

</body>

</html>