<?php
// Include file koneksi database


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_siswa = $_POST['nama_siswa'];
    $kelas = $_POST['kelas'];
    $nisn = $_POST['nisn'];

    $insertQuery = "INSERT INTO Siswa (nama_siswa, kelas, nisn) VALUES ('$nama_siswa', '$kelas', '$nisn')";
    mysqli_query($conn, $insertQuery);

    header("Location: alternatif.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Siswa</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- Ganti dengan link ke file CSS Anda -->
</head>

<body>
    <h1>Tambah Siswa</h1>

    <form action="add_alternatif.php" method="post">
        <label for="nama_siswa">Nama Siswa:</label>
        <input type="text" id="nama_siswa" name="nama_siswa" required>
        <label for="kelas">Kelas:</label>
        <input type="text" id="kelas" name="kelas" required>
        <label for="nisn">NISN:</label>
        <input type="text" id="nisn" name="nisn" required>
        <button type="submit">Tambah</button>
    </form>

    <a href="alternatif.php" class="btn">Kembali</a>
</body>

</html>