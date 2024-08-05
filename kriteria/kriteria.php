<?php
include 'config.php';


$sql = "SELECT * FROM Kriteria";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kriteria</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <h1>Data Kriteria</h1>

    <!-- Tombol Add -->
    <a href="kriteria/add_kriteria.php" class="btn btn-primary">Add Kriteria</a>

    <table border="1">
        <thead>
            <tr>
                <th>ID Kriteria</th>
                <th>Nama Kriteria</th>
                <th>Bobot Kriteria</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>{$row['id_kriteria']}</td>";
                    echo "<td>{$row['nama_kriteria']}</td>";
                    echo "<td>{$row['bobot_kriteria']}</td>";
                    echo "<td>
                        <a href='kriteria/edit_kriteria.php?id={$row['id_kriteria']}'>Edit</a> | 
                        <a href='kriteria/delete_kriteria.php?id={$row['id_kriteria']}' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                    </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No data found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>

</html>