<?php
include '../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_siswa = $_POST['nama_siswa'];
    $kelas = $_POST['kelas'];
    $nisn = $_POST['nisn'];


    $nama_siswa = mysqli_real_escape_string($conn, $nama_siswa);
    $kelas = mysqli_real_escape_string($conn, $kelas);
    $nisn = mysqli_real_escape_string($conn, $nisn);



    $sql = "INSERT INTO Siswa (nama_siswa, kelas, nisn) VALUES ('$nama_siswa', '$kelas', '$nisn')";
    if (mysqli_query($conn, $sql)) {

        header("Location: ../index.php?page=alternatif");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Siwa</title>
</head>

<body>
    <h1>Add Siswa</h1>
    <form method="post" action="">
        <label for="nama_siswa">Nama Siswa:</label>
        <input type="text" id="nama_siswa" name="nama_siswa" required>
        <br>
        <label for="kelas">Kelas:</label>
        <input type="number" id="kelas" name="kelas" step="0.01" required>
        <br>
        <label for="nisn">NISN:</label>
        <input type="number" id="nisn" name="nisn" step="0.01" required>
        <br>
        <input type="submit" value="Add alternatif">
    </form>
</body>

</html>