<?php
include '../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_kriteria = $_POST['nama_kriteria'];

    // Menyaring input untuk keamanan
    $nama_kriteria = mysqli_real_escape_string($conn, $nama_kriteria);

    $sql = "INSERT INTO Kriteria (nama_kriteria) VALUES ('$nama_kriteria')";
    if (mysqli_query($conn, $sql)) {
        // Redirect ke halaman kriteria.php setelah sukses menambah data
        header("Location: ../index.php?page=kriteria");
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
    <title>Add Kriteria</title>
</head>
<body>
    <h1>Add Kriteria</h1>
    <form method="post" action="">
        <label for="nama_kriteria">Nama Kriteria:</label>
        <input type="text" id="nama_kriteria" name="nama_kriteria" required>
        <br>
        <input type="submit" value="Add Kriteria">
    </form>
</body>
</html>
