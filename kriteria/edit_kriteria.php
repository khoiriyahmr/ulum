<?php
include '../config.php';

if (isset($_GET['id'])) {
    $id_kriteria = $_GET['id'];

    // Mendapatkan data kriteria berdasarkan ID
    $sql = "SELECT * FROM Kriteria WHERE id_kriteria = $id_kriteria";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_kriteria = $_POST['id_kriteria'];
    $nama_kriteria = $_POST['nama_kriteria'];

    // Menyaring input untuk keamanan
    $nama_kriteria = mysqli_real_escape_string($conn, $nama_kriteria);

    $sql = "UPDATE Kriteria SET nama_kriteria = '$nama_kriteria' WHERE id_kriteria = $id_kriteria";
    if (mysqli_query($conn, $sql)) {
        // Redirect ke halaman kriteria.php setelah sukses mengupdate data
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
    <title>Edit Kriteria</title>
</head>
<body>
    <h1>Edit Kriteria</h1>
    <form method="post" action="">
        <input type="hidden" name="id_kriteria" value="<?php echo $row['id_kriteria']; ?>">
        <label for="nama_kriteria">Nama Kriteria:</label>
        <input type="text" id="nama_kriteria" name="nama_kriteria" value="<?php echo $row['nama_kriteria']; ?>" required>
        <br>
        <input type="submit" value="Update Kriteria">
    </form>
</body>
</html>
