<?php
include 'config.php';

// Hapus Kriteria jika ada permintaan delete
if (isset($_GET['delete'])) {
    $id_kriteria = $_GET['delete'];

    // Cek apakah kriteria ini digunakan di tabel lain
    $check_sql = "SELECT COUNT(*) AS count FROM Penilaian WHERE id_kriteria = $id_kriteria";
    $check_result = mysqli_query($conn, $check_sql);
    $check_row = mysqli_fetch_assoc($check_result);

    if ($check_row['count'] > 0) {
        echo "Kriteria ini tidak bisa dihapus karena sedang digunakan dalam tabel lain.";
    } else {
        $sql = "DELETE FROM Kriteria WHERE id_kriteria = $id_kriteria";
        if (mysqli_query($conn, $sql)) {
            // Penghapusan berhasil
            header("Location: ../index.php?page=kriteria");
            exit();
        } else {
            echo "Error deleting record: " . mysqli_error($conn);
        }
    }
}

// Ambil data kriteria
$sql = "SELECT * FROM Kriteria";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kriteria</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h1>Data Kriteria</h1>
    <a href="kriteria/add_kriteria.php">Add Kriteria</a>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Kriteria</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['id_kriteria']; ?></td>
                    <td><?php echo $row['nama_kriteria']; ?></td>
                    <td>
                        <a href="kriteria/edit_kriteria.php?id=<?php echo $row['id_kriteria']; ?>">Edit</a>
                        <a href="kriteria/delete_kriteria.php?id=<?php echo $row['id_kriteria']; ?>" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>
