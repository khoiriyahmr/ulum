<?php
// Menghubungkan ke database
include '../../config.php';

// Menambah Perbandingan Alternatif Nilai Raport
if (isset($_POST['add_perbandingan'])) {
    $alternatif1_id = $_POST['alternatif1_id'];
    $alternatif2_id = $_POST['alternatif2_id'];

    $sql = "INSERT INTO perbandingan_alternatif_raport (alternatif1_id, alternatif2_id) VALUES ('$alternatif1_id', '$alternatif2_id')";
    if ($conn->query($sql) === TRUE) {
        echo "<p>Perbandingan alternatif nilai raport berhasil ditambahkan.</p>";
        calculate_ahp_raport(); // Otomatis menghitung AHP setelah menambah
    } else {
        echo "<p>Error: " . $conn->error . "</p>";
    }
}

// Menghapus Perbandingan Alternatif Nilai Raport
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM perbandingan_alternatif_raport WHERE id_perbandingan = $id";
    if ($conn->query($sql) === TRUE) {
        echo "<p>Perbandingan alternatif nilai raport berhasil dihapus.</p>";
        calculate_ahp_raport(); // Otomatis menghitung AHP setelah menghapus
    } else {
        echo "<p>Error: " . $conn->error . "</p>";
    }
}

// Mengedit Perbandingan Alternatif Nilai Raport
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $sql = "SELECT * FROM perbandingan_alternatif_raport WHERE id_perbandingan = $id";
    $result = $conn->query($sql);
    $perbandingan = $result->fetch_assoc();
}

// Memproses Update Perbandingan Alternatif Nilai Raport
if (isset($_POST['edit_perbandingan'])) {
    $id = $_POST['id_perbandingan'];
    $alternatif1_id = $_POST['alternatif1_id'];
    $alternatif2_id = $_POST['alternatif2_id'];

    $sql = "UPDATE perbandingan_alternatif_raport SET alternatif1_id='$alternatif1_id', alternatif2_id='$alternatif2_id' WHERE id_perbandingan=$id";
    if ($conn->query($sql) === TRUE) {
        echo "<p>Perbandingan alternatif nilai raport berhasil diperbarui.</p>";
        calculate_ahp_raport(); // Otomatis menghitung AHP setelah mengupdate
    } else {
        echo "<p>Error: " . $conn->error . "</p>";
    }
}

// Proses Perhitungan AHP untuk Nilai Raport
function calculate_ahp_raport()
{
    global $conn;

    // Ambil semua data perbandingan alternatif nilai raport
    $sql = "SELECT * FROM perbandingan_alternatif_raport";
    $result = $conn->query($sql);

    $matrix = [];
    $alternatif = [];
    $alternatif_map = [];

    // Membuat matrix perbandingan
    while ($row = $result->fetch_assoc()) {
        $alternatif1_id = $row['alternatif1_id'];
        $alternatif2_id = $row['alternatif2_id'];
        $alternatif[$alternatif1_id] = 1;
        $alternatif[$alternatif2_id] = 1;
        $matrix[$alternatif1_id][$alternatif2_id] = get_raport_value($alternatif1_id) / get_raport_value($alternatif2_id);
        $matrix[$alternatif2_id][$alternatif1_id] = 1 / $matrix[$alternatif1_id][$alternatif2_id];
    }

    // Normalisasi matrix
    $num_alternatif = count($matrix);
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
        $weights[$row_id] = array_sum($row) / $num_alternatif;
    }

    // Update bobot alternatif di database
    foreach ($weights as $alternatif_id => $weight) {
        $sql = "UPDATE alternatif SET bobot_raport = $weight WHERE id_alternatif = $alternatif_id";
        $conn->query($sql);
    }

    echo "<p>Perhitungan AHP untuk nilai raport berhasil dilakukan dan bobot alternatif diperbarui.</p>";
}

function get_raport_value($id_alternatif)
{
    global $conn;
    $sql = "SELECT nilai_raport FROM alternatif WHERE id_alternatif = $id_alternatif";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['nilai_raport'];
}
?>

<h2>Perbandingan Alternatif Nilai Raport</h2>
<form action="" method="POST">
    <select name="alternatif1_id" required>
        <option value="">Pilih Alternatif 1</option>
        <?php
        $sql = "SELECT * FROM alternatif";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . $row['id_alternatif'] . "'";
            if (isset($perbandingan) && $perbandingan['alternatif1_id'] == $row['id_alternatif']) {
                echo " selected";
            }
            echo ">" . $row['nama'] . "</option>";
        }
        ?>
    </select>
    <select name="alternatif2_id" required>
        <option value="">Pilih Alternatif 2</option>
        <?php
        $sql = "SELECT * FROM alternatif";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . $row['id_alternatif'] . "'";
            if (isset($perbandingan) && $perbandingan['alternatif2_id'] == $row['id_alternatif']) {
                echo " selected";
            }
            echo ">" . $row['nama'] . "</option>";
        }
        ?>
    </select>
    <?php if (isset($perbandingan)) : ?>
        <input type="hidden" name="id_perbandingan" value="<?php echo $perbandingan['id_perbandingan']; ?>">
        <button type="submit" name="edit_perbandingan">Update</button>
    <?php else : ?>
        <button type="submit" name="add_perbandingan">Add</button>
    <?php endif; ?>
</form>

<h3>Daftar Perbandingan Alternatif Nilai Raport</h3>

<?php
// Ambil alternatif untuk tabel
$sql = "SELECT * FROM alternatif";
$alternatif_result = $conn->query($sql);
$alternatif_list = [];
while ($row = $alternatif_result->fetch_assoc()) {
    $alternatif_list[$row['id_alternatif']] = $row['nama'];
}

// Ambil data perbandingan alternatif nilai raport
$sql = "SELECT pa.alternatif1_id, pa.alternatif2_id, pa.nilai, a1.nama AS alternatif1, a2.nama AS alternatif2
        FROM perbandingan_alternatif_raport pa
        JOIN alternatif a1 ON pa.alternatif1_id = a1.id_alternatif
        JOIN alternatif a2 ON pa.alternatif2_id = a2.id_alternatif";
$perbandingan_result = $conn->query($sql);
$perbandingan_data = [];
while ($row = $perbandingan_result->fetch_assoc()) {
    $perbandingan_data[$row['alternatif1_id']][$row['alternatif2_id']] = $row['nilai'];
}
?>

<table border="1">
    <thead>
        <tr>
            <th>Alternatif</th>
            <?php foreach ($alternatif_list as $id => $nama) : ?>
                <th><?php echo $nama; ?></th>
            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($alternatif_list as $id1 => $nama1) : ?>
            <tr>
                <td><?php echo $nama1; ?></td>
                <?php foreach ($alternatif_list as $id2 => $nama2) : ?>
                    <td>
                        <?php
                        if (isset($perbandingan_data[$id1][$id2])) {
                            echo $perbandingan_data[$id1][$id2];
                        } elseif ($id1 == $id2) {
                            echo "1";
                        } else {
                            echo "";
                        }
                        ?>
                    </td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>