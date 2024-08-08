<?php
// Menghubungkan ke database
include '../../config.php';
include '../../navbar.php'; 

// Menambah Perbandingan Kriteria
if (isset($_POST['add_perbandingan'])) {
    $kriteria1_id = $_POST['kriteria1_id'];
    $kriteria2_id = $_POST['kriteria2_id'];
    $nilai = $_POST['nilai'];

    $sql = "INSERT INTO perbandingan_kriteria (kriteria1_id, kriteria2_id, nilai) VALUES ('$kriteria1_id', '$kriteria2_id', '$nilai')";
    if ($conn->query($sql) === TRUE) {
        echo "<p>Perbandingan kriteria berhasil ditambahkan.</p>";
        calculate_ahp(); // Otomatis menghitung AHP setelah menambah
    } else {
        echo "<p>Error: " . $conn->error . "</p>";
    }
}

// Menghapus Perbandingan Kriteria
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM perbandingan_kriteria WHERE id_perbandingan = $id";
    if ($conn->query($sql) === TRUE) {
        echo "<p>Perbandingan kriteria berhasil dihapus.</p>";
        calculate_ahp(); // Otomatis menghitung AHP setelah menghapus
    } else {
        echo "<p>Error: " . $conn->error . "</p>";
    }
}

// Mengedit Perbandingan Kriteria
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $sql = "SELECT * FROM perbandingan_kriteria WHERE id_perbandingan = $id";
    $result = $conn->query($sql);
    $perbandingan = $result->fetch_assoc();
}

// Memproses Update Perbandingan Kriteria
if (isset($_POST['edit_perbandingan'])) {
    $id = $_POST['id_perbandingan'];
    $kriteria1_id = $_POST['kriteria1_id'];
    $kriteria2_id = $_POST['kriteria2_id'];
    $nilai = $_POST['nilai'];

    $sql = "UPDATE perbandingan_kriteria SET kriteria1_id='$kriteria1_id', kriteria2_id='$kriteria2_id', nilai='$nilai' WHERE id_perbandingan=$id";
    if ($conn->query($sql) === TRUE) {
        echo "<p>Perbandingan kriteria berhasil diperbarui.</p>";
        calculate_ahp(); // Otomatis menghitung AHP setelah mengupdate
    } else {
        echo "<p>Error: " . $conn->error . "</p>";
    }
}

// Proses Perhitungan AHP
function calculate_ahp()
{
    global $conn;

    // Ambil semua data perbandingan kriteria
    $sql = "SELECT * FROM perbandingan_kriteria";
    $result = $conn->query($sql);

    $matrix = [];
    $kriteria = [];
    $kriteria_map = [];

    // Membuat matrix perbandingan
    while ($row = $result->fetch_assoc()) {
        $kriteria1_id = $row['kriteria1_id'];
        $kriteria2_id = $row['kriteria2_id'];
        $nilai = $row['nilai'];
        $kriteria[$kriteria1_id] = 1;
        $kriteria[$kriteria2_id] = 1;
        $matrix[$kriteria1_id][$kriteria2_id] = $nilai;
        $matrix[$kriteria2_id][$kriteria1_id] = 1 / $nilai;
    }

    // Normalisasi matrix
    $num_kriteria = count($matrix);
    $normalized_matrix = [];
    foreach ($matrix as $row_id => $row) {
        $sum = array_sum($row);
        foreach ($row as $col_id => $value) {
            $normalized_matrix[$row_id][$col_id] = $value / $sum;
        }
    }

    // Menghitung bobot
    $weights = [];
    foreach ($normalized_matrix as $row_id => $row) {
        $weights[$row_id] = array_sum($row) / $num_kriteria;
    }

    // Update bobot kriteria di database
    foreach ($weights as $kriteria_id => $weight) {
        $sql = "UPDATE kriteria SET bobot = $weight WHERE id_kriteria = $kriteria_id";
        $conn->query($sql);
    }

    echo "<p>Perhitungan AHP berhasil dilakukan dan bobot kriteria diperbarui.</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perbandingan Kriteria</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>

<div class="container mt-4">
    <h2>Perbandingan Kriteria</h2>
    <form action="" method="POST">
        <div class="mb-3">
            <label for="kriteria1_id" class="form-label">Kriteria 1</label>
            <select name="kriteria1_id" id="kriteria1_id" class="form-select" required>
                <option value="">Pilih Kriteria 1</option>
                <?php
                $sql = "SELECT * FROM kriteria";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['id_kriteria'] . "'";
                    if (isset($perbandingan) && $perbandingan['kriteria1_id'] == $row['id_kriteria']) {
                        echo " selected";
                    }
                    echo ">" . $row['nama_kriteria'] . "</option>";
                }
                ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="kriteria2_id" class="form-label">Kriteria 2</label>
            <select name="kriteria2_id" id="kriteria2_id" class="form-select" required>
                <option value="">Pilih Kriteria 2</option>
                <?php
                $sql = "SELECT * FROM kriteria";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['id_kriteria'] . "'";
                    if (isset($perbandingan) && $perbandingan['kriteria2_id'] == $row['id_kriteria']) {
                        echo " selected";
                    }
                    echo ">" . $row['nama_kriteria'] . "</option>";
                }
                ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="nilai" class="form-label">Nilai</label>
            <input type="number" step="0.1" name="nilai" id="nilai" class="form-control" placeholder="Nilai" value="<?php echo isset($perbandingan) ? $perbandingan['nilai'] : ''; ?>" required>
        </div>
        <?php if (isset($perbandingan)) : ?>
            <input type="hidden" name="id_perbandingan" value="<?php echo $perbandingan['id_perbandingan']; ?>">
            <button type="submit" name="edit_perbandingan" class="btn btn-primary">Update</button>
        <?php else : ?>
            <button type="submit" name="add_perbandingan" class="btn btn-primary">Add</button>
        <?php endif; ?>
    </form>

    <h3 class="mt-4">Daftar Perbandingan Kriteria</h3>

    <?php
    // Ambil kriteria untuk tabel
    $sql = "SELECT * FROM kriteria";
    $kriteria_result = $conn->query($sql);
    $kriteria_list = [];
    while ($row = $kriteria_result->fetch_assoc()) {
        $kriteria_list[$row['id_kriteria']] = $row['nama_kriteria'];
    }

    // Ambil data perbandingan kriteria
    $sql = "SELECT pk.kriteria1_id, pk.kriteria2_id, pk.nilai, k1.nama_kriteria AS kriteria1, k2.nama_kriteria AS kriteria2
            FROM perbandingan_kriteria pk
            JOIN kriteria k1 ON pk.kriteria1_id = k1.id_kriteria
            JOIN kriteria k2 ON pk.kriteria2_id = k2.id_kriteria";
    $perbandingan_result = $conn->query($sql);
    $perbandingan_data = [];
    while ($row = $perbandingan_result->fetch_assoc()) {
        $perbandingan_data[$row['kriteria1_id']][$row['kriteria2_id']] = $row['nilai'];
    }
    ?>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th></th>
                <?php foreach ($kriteria_list as $id => $name) : ?>
                    <th><?php echo $name; ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($kriteria_list as $id1 => $name1) : ?>
                <tr>
                    <th><?php echo $name1; ?></th>
                    <?php foreach ($kriteria_list as $id2 => $name2) : ?>
                        <td>
                            <?php
                            if ($id1 == $id2) {
                                echo '1'; // Nilai diagonal adalah 1
                            } elseif (isset($perbandingan_data[$id1][$id2])) {
                                echo $perbandingan_data[$id1][$id2];
                            } else {
                                echo '-';
                            }
                            ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
