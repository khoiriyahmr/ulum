<?php
include 'config.php';


$sql = "SELECT * FROM Siswa";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alternatif</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <h1>Data Alternatif</h1>

    <!-- Tombol Add -->
    <a href="alternatif/add_alternatif.php" class="btn btn-primary">Add Alternatif</a>

    <table border="1">
        <thead>
            <tr>
                <th>ID Siswa</th>
                <th>Nama </th>
                <th>Kelas</th>
                <th>NISN</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>{$row['id_siswa']}</td>";
                    echo "<td>{$row['nama_siswa']}</td>";
                    echo "<td>{$row['kelas']}</td>";
                    echo "<td>{$row['nisn']}</td>";
                    echo "<td>
                        <a href='alternatif/edit_alternatif.php?id={$row['id_siswa']}'>Edit</a> | 
                        <a href='alternatif/delete_alternatif.php?id={$row['id_siswa']}' onclick='return confirm(\"Are you sure?\")'>Delete</a>
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