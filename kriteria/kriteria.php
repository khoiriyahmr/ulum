<?php
include '../config.php';

// Menambah Kriteria
if (isset($_POST['add_kriteria'])) {
    $nama_kriteria = $_POST['nama_kriteria'];
    $bobot = $_POST['bobot'];
    $sql = "INSERT INTO kriteria (nama_kriteria, bobot) VALUES ('$nama_kriteria', '$bobot')";
    $conn->query($sql);
}

// Menghapus Kriteria
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM kriteria WHERE id_kriteria = $id";
    $conn->query($sql);
}

// Mengedit Kriteria
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $sql = "SELECT * FROM kriteria WHERE id_kriteria = $id";
    $result = $conn->query($sql);
    $kriteria = $result->fetch_assoc();
}

// Memproses Update Kriteria
if (isset($_POST['edit_kriteria'])) {
    $id = $_POST['id_kriteria'];
    $nama_kriteria = $_POST['nama_kriteria'];
    $bobot = $_POST['bobot'];
    $sql = "UPDATE kriteria SET nama_kriteria='$nama_kriteria', bobot='$bobot' WHERE id_kriteria=$id";
    $conn->query($sql);
    header("Location: kriteria.php");
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Manajemen Kriteria</title>
</head>

<body>

    <h1>Manajemen Kriteria</h1>

    <!-- Formulir Tambah Kriteria -->
    <form action="" method="POST">
        <input type="text" name="nama_kriteria" placeholder="Nama Kriteria" required>
        <input type="number" step="0.01" name="bobot" placeholder="Bobot" required>
        <button type="submit" name="add_kriteria">Add</button>
    </form>

    <!-- Formulir Edit Kriteria -->
    <?php if (isset($kriteria)) : ?>
        <h2>Edit Kriteria</h2>
        <form action="" method="POST">
            <input type="hidden" name="id_kriteria" value="<?php echo $kriteria['id_kriteria']; ?>">
            <input type="text" name="nama_kriteria" value="<?php echo $kriteria['nama_kriteria']; ?>" required>
            <input type="number" step="0.01" name="bobot" value="<?php echo $kriteria['bobot']; ?>" required>
            <button type="submit" name="edit_kriteria">Update</button>
        </form>
    <?php endif; ?>

    <h2>Daftar Kriteria</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nama Kriteria</th>
            <th>Bobot</th>
            <th>Aksi</th>
        </tr>
        <?php
        $sql = "SELECT * FROM kriteria";
        $result = $conn->query($sql);

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['id_kriteria'] . "</td>";
            echo "<td>" . $row['nama_kriteria'] . "</td>";
            echo "<td>" . $row['bobot'] . "</td>";
            echo "<td>
                    <a href='kriteria.php?edit=" . $row['id_kriteria'] . "'>Edit</a>
                    <a href='kriteria.php?delete=" . $row['id_kriteria'] . "' onclick=\"return confirm('Anda yakin ingin menghapus kriteria ini?')\">Delete</a>
                  </td>";
            echo "</tr>";
        }
        ?>
    </table>
</body>

</html>