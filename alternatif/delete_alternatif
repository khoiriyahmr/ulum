<?php
include '../config.php';

$id = $_GET['id'];

$sql = "DELETE FROM Siswa WHERE id_siswa=$id";
if (mysqli_query($conn, $sql)) {
    header("Location: ../index.php?page=alternatif");
    exit();
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}
