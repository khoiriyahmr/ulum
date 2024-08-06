<?php
include '../config.php'; 

$id = $_GET['id'];

$sql = "DELETE FROM Kriteria WHERE id_kriteria=$id";
if (mysqli_query($conn, $sql)) {
    header("Location: ../index.php?page=kriteria");
 
    exit();
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}
