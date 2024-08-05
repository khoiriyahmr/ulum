<?php
// Include file koneksi database
include('koneksi.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch data untuk siswa yang dipilih
    $query = "SELECT * FROM Siswa WHERE id_siswa = $id";
    $result = mysqli_query($conn, $query);
    $siswa = mysqli_fetch_assoc($result);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nama_siswa = $_POST['nama_siswa'];
        $kelas = $_POST['kelas'];
        $nisn = $_POST['nisn'];

        $updateQuery = "UPDATE Siswa SET nama_siswa = '$nama_siswa', kelas = '$kelas', nisn = '$nisn' WHERE id_siswa = $id";
        mysqli_query($conn, $updateQuery);

        header("Location: alternatif.php");
    }
} else {
    echo "ID Siswa tidak ditemukan!";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Siswa</title>
    <link rel="stylesheet" href="styles.css"> <!-- Ganti dengan link ke file CSS Anda -->
</head>

<body>
    <h1>Edit Siswa</h1>

    <form action="edit_alternatif.php?id=<?php echo $id; ?>" method="post">
        <label for="nama_siswa">Nama Siswa:</label>
        <input type="text" id="nama_siswa" name="nama_siswa" value="<?php echo $siswa['nama_siswa']; ?>" required>
        <label for="kelas">Kelas:</label>
        <input type="text" id="kelas" name="kelas" value="<?php echo $siswa['kelas']; ?>" required>
        <label for="nisn">NISN:</label>
        <input type="text" id="nisn" name="nisn" value="<?php echo $siswa['nisn']; ?>" required>
        <button type="submit">Update</button>
    </form>

    <a href="alternatif.php" class="btn">Kembali</a>
</body>

</html>