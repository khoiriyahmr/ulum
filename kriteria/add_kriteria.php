<?php
// Include file koneksi database
include('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_kriteria = $_POST['nama_kriteria'];
    $bobot_kriteria = $_POST['bobot_kriteria'];

    $insertQuery = "INSERT INTO Kriteria (nama_kriteria, bobot_kriteria) VALUES ('$nama_kriteria', $bobot_kriteria)";
    mysqli_query($conn, $insertQuery);

    header("Location: kriteria.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kriteria</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- Ganti dengan link ke file CSS Anda -->
</head>

<body>
    <h1>Tambah Kriteria</h1>

    <form action="add_kriteria.php" method="post">
        <label for="nama_kriteria">Nama Kriteria:</label>
        <input type="text" id="nama_kriteria" name="nama_kriteria" required>
        <label for="bobot_kriteria">Bobot Kriteria:</label>
        <input type="number" id="bobot_kriteria" name="bobot_kriteria" step="0.01" required>
        <button type="submit">Tambah</button>
    </form>

    <a href="kriteria.php" class="btn">Kembali</a>
</body>

</html>