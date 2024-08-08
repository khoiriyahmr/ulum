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
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manajemen Kriteria</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>

<?php include '../navbar.php'; ?>

<div class="container mt-4">
    <h1>Manajemen Kriteria</h1>

    <!-- Formulir Tambah Kriteria -->
    <form action="" method="POST" class="mb-3">
        <div class="mb-3">
            <label for="nama_kriteria" class="form-label">Nama Kriteria</label>
            <input type="text" class="form-control" id="nama_kriteria" name="nama_kriteria" placeholder="Nama Kriteria" required>
        </div>
        <div class="mb-3">
            <label for="bobot" class="form-label">Bobot</label>
            <input type="number" step="0.01" class="form-control" id="bobot" name="bobot" placeholder="Bobot" required>
        </div>
        <button type="submit" name="add_kriteria" class="btn btn-primary">Add</button>
    </form>

    <!-- Formulir Edit Kriteria -->
    <?php if (isset($kriteria)) : ?>
        <h2>Edit Kriteria</h2>
        <form action="" method="POST" class="mb-3">
            <input type="hidden" name="id_kriteria" value="<?php echo $kriteria['id_kriteria']; ?>">
            <div class="mb-3">
                <label for="nama_kriteria_edit" class="form-label">Nama Kriteria</label>
                <input type="text" class="form-control" id="nama_kriteria_edit" name="nama_kriteria" value="<?php echo $kriteria['nama_kriteria']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="bobot_edit" class="form-label">Bobot</label>
                <input type="number" step="0.01" class="form-control" id="bobot_edit" name="bobot" value="<?php echo $kriteria['bobot']; ?>" required>
            </div>
            <button type="submit" name="edit_kriteria" class="btn btn-primary">Update</button>
        </form>
    <?php endif; ?>

    <h2>Daftar Kriteria</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Kriteria</th>
                <th>Bobot</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $sql = "SELECT * FROM kriteria";
        $result = $conn->query($sql);

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['id_kriteria'] . "</td>";
            echo "<td>" . $row['nama_kriteria'] . "</td>";
            echo "<td>" . $row['bobot'] . "</td>";
            echo "<td>
                    <a href='kriteria.php?edit=" . $row['id_kriteria'] . "' class='btn btn-warning btn-sm'>Edit</a>
                    <a href='kriteria.php?delete=" . $row['id_kriteria'] . "' class='btn btn-danger btn-sm' onclick=\"return confirm('Anda yakin ingin menghapus kriteria ini?')\">Delete</a>
                  </td>";
            echo "</tr>";
        }
        ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
