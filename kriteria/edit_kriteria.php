<?php
include '../config.php'; // Pastikan path ini benar

$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_kriteria = $_POST['nama_kriteria'];
    $bobot_kriteria = $_POST['bobot_kriteria'];

    $sql = "UPDATE Kriteria SET nama_kriteria='$nama_kriteria', bobot_kriteria='$bobot_kriteria' WHERE id_kriteria=$id";
    if (mysqli_query($conn, $sql)) {
        header("Location: ../index.php?page=kriteria");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
} else {
    $sql = "SELECT * FROM Kriteria WHERE id_kriteria=$id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
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
        <label for="nama_kriteria">Nama Kriteria:</label>
        <input type="text" id="nama_kriteria" name="nama_kriteria" value="<?php echo $row['nama_kriteria']; ?>" required>
        <br>
        <label for="bobot_kriteria">Bobot Kriteria:</label>
        <input type="number" id="bobot_kriteria" name="bobot_kriteria" value="<?php echo $row['bobot_kriteria']; ?>" step="0.01" required>
        <br>
        <input type="submit" value="Update Kriteria">
    </form>
</body>

</html>