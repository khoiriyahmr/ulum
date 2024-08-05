<?php
include '../config.php'; // Pastikan path ini benar

$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_siswa = $_POST['nama_siswa'];
    $kelas = $_POST['kelas'];
    $nisn = $_POST['nisn'];

    $sql = "UPDATE Siswa SET nama_siswa='$nama_siswa', kelas='$kelas', nisn='$nisn' WHERE id_siswa=$id";
    if (mysqli_query($conn, $sql)) {
        header("Location: ../index.php?page=alternatif");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
} else {
    $sql = "SELECT * FROM Siswa WHERE id_siswa=$id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit siswa</title>
</head>

<body>
    <h1>Edit siswa</h1>
    <form method="post" action="">
        <label for="nama_siswa">Nama siswa:</label>
        <input type="text" id="nama_siswa" name="nama_siswa" value="<?php echo $row['nama_siswa']; ?>" required>
        <br>
        <label for="kelas">Kelas:</label>
        <input type="number" id="kelas" name="kelas" value="<?php echo $row['kelas']; ?>" step="0.01" required>
        <br>
        <label for="nisn">NISN:</label>
        <input type="number" id="nisn" name="nisn" value="<?php echo $row['nisn']; ?>" step="0.01" required>
        <br>
        <input type="submit" value="Update siswa">
    </form>
</body>

</html>