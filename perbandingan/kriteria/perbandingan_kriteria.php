<?php
// Menghubungkan ke database
include '../../config.php';

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

<h2>Perbandingan Kriteria</h2>
<form action="" method="POST">
    <select name="kriteria1_id" required>
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
    <select name="kriteria2_id" required>
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
    <input type="number" step="0.1" name="nilai" placeholder="Nilai" value="<?php echo isset($perbandingan) ? $perbandingan['nilai'] : ''; ?>" required>
    <?php if (isset($perbandingan)) : ?>
        <input type="hidden" name="id_perbandingan" value="<?php echo $perbandingan['id_perbandingan']; ?>">
        <button type="submit" name="edit_perbandingan">Update</button>
    <?php else : ?>
        <button type="submit" name="add_perbandingan">Add</button>
    <?php endif; ?>
</form>

<h3>Daftar Perbandingan Kriteria</h3>

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

<table border="1">
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