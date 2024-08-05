<?php
// Include file koneksi database
include('config.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch data untuk kriteria yang dipilih
    $query = "SELECT * FROM Kriteria WHERE id_kriteria = $id";
    $result = mysqli_query($conn, $query);
    $kriteria = mysqli_fetch_assoc($result);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nama_kriteria = $_POST['nama_kriteria'];
        $bobot_kriteria = $_POST['bobot_kriteria'];

        $updateQuery = "UPDATE Kriteria SET nama_kriteria = '$nama_kriteria', bobot_kriteria = $bobot_kriteria WHERE id_kriteria = $id";
        mysqli_query($conn, $updateQuery);

        header("Location: kriteria.php");
    }
} else {
    echo "ID Kriteria tidak ditemukan!";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kriteria</title>
    <link rel="stylesheet" href="kriteria/styles.css"> <!-- Ganti dengan link ke file CSS Anda -->
</head>

<body>
    <h1>Edit Kriteria</h1>

    <form action="edit_kriteria.php?id=<?php echo $id; ?>" method="post">
        <label for="nama_kriteria">Nama Kriteria:</label>
        <input type="text" id="nama_kriteria" name="nama_kriteria" value="<?php echo $kriteria['nama_kriteria']; ?>" required>
        <label for="bobot_kriteria">Bobot Kriteria:</label>
        <input type="number" id="bobot_kriteria" name="bobot_kriteria" step="0.01" value="<?php echo $kriteria['bobot_kriteria']; ?>" required>
        <button type="submit">Update</button>
    </form>

    <a href="kriteria.php" class="btn">Kembali</a>
</body>

</html>