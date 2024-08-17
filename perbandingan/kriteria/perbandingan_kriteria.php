<?php
include '../../config.php';
include '../../navbar.php';

function calculate_ahp()
{
    global $conn;

    $sql = "SELECT * FROM perbandingan_kriteria";
    $result = $conn->query($sql);

    $matrix = [];
    $kriteria = [];

    while ($row = $result->fetch_assoc()) {
        $kriteria1_id = $row['kriteria1_id'];
        $kriteria2_id = $row['kriteria2_id'];
        $nilai = $row['nilai'];

        if (!isset($matrix[$kriteria1_id])) {
            $matrix[$kriteria1_id] = [];
        }
        if (!isset($matrix[$kriteria2_id])) {
            $matrix[$kriteria2_id] = [];
        }

        $matrix[$kriteria1_id][$kriteria2_id] = $nilai;
        $matrix[$kriteria2_id][$kriteria1_id] = 1 / $nilai;

        $kriteria[$kriteria1_id] = 1;
        $kriteria[$kriteria2_id] = 1;
    }

    $num_kriteria = count($matrix);
    $normalized_matrix = [];

    foreach ($matrix as $row_id => $row) {
        $sum = array_sum($row);
        foreach ($row as $col_id => $value) {
            $normalized_matrix[$row_id][$col_id] = $value / $sum;
        }
    }

    $weights = [];
    foreach ($normalized_matrix as $row_id => $row) {
        $weights[$row_id] = array_sum($row) / $num_kriteria;
    }

    echo "";
}


if (isset($_POST['add_perbandingan'])) {
    $kriteria1_id = $_POST['kriteria1_id'];
    $kriteria2_id = $_POST['kriteria2_id'];
    $nilai = str_replace(',', '.', $_POST['nilai']);

    if (!filter_var($nilai, FILTER_VALIDATE_FLOAT)) {
        echo "<p>Error: Nilai harus berupa angka desimal yang valid.</p>";
        exit;
    }

    $sql = "INSERT INTO perbandingan_kriteria (kriteria1_id, kriteria2_id, nilai) VALUES ('$kriteria1_id', '$kriteria2_id', '$nilai')";
    if ($conn->query($sql) === TRUE) {
        echo "<p>Perbandingan kriteria berhasil ditambahkan.</p>";
        calculate_ahp();
    } else {
        echo "<p>Error: " . $conn->error . "</p>";
    }
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM perbandingan_kriteria WHERE id_perbandingan = $id";
    if ($conn->query($sql) === TRUE) {
        echo "<p>Perbandingan kriteria berhasil dihapus.</p>";
        calculate_ahp();
    } else {
        echo "<p>Error: " . $conn->error . "</p>";
    }
}

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $sql = "SELECT * FROM perbandingan_kriteria WHERE id_perbandingan = $id";
    $result = $conn->query($sql);
    $perbandingan = $result->fetch_assoc();
}

if (isset($_POST['edit_perbandingan'])) {
    $id = $_POST['id_perbandingan'];
    $kriteria1_id = $_POST['kriteria1_id'];
    $kriteria2_id = $_POST['kriteria2_id'];
    $nilai = str_replace(',', '.', $_POST['nilai']);

    if (!filter_var($nilai, FILTER_VALIDATE_FLOAT)) {
        echo "<p>Error: Nilai harus berupa angka desimal yang valid.</p>";
        exit;
    }

    $sql = "UPDATE perbandingan_kriteria SET kriteria1_id='$kriteria1_id', kriteria2_id='$kriteria2_id', nilai='$nilai' WHERE id_perbandingan=$id";
    if ($conn->query($sql) === TRUE) {
        echo "<p>Perbandingan kriteria berhasil diperbarui.</p>";
        calculate_ahp();
    } else {
        echo "<p>Error: " . $conn->error . "</p>";
    }
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
        <div class="row">
            <div class="col-md-6">
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
                        <input type="text" name="nilai" id="nilai" class="form-control" placeholder="Nilai (gunakan titik sebagai pemisah desimal)" value="<?php echo isset($perbandingan) ? $perbandingan['nilai'] : ''; ?>" required>
                    </div>
                    <?php if (isset($perbandingan)) : ?>
                        <input type="hidden" name="id_perbandingan" value="<?php echo $perbandingan['id_perbandingan']; ?>">
                        <button type="submit" name="edit_perbandingan" class="btn btn-primary">Update</button>
                    <?php else : ?>
                        <button type="submit" name="add_perbandingan" class="btn btn-primary">Add</button>
                    <?php endif; ?>
                </form>
            </div>
            <div class="col-md-6">
                <h5 class="mt-4">Tabel Intensitas Kepentingan Metode AHP</h5>
                <table class="table table-bordered mt-3">
                    <thead>
                        <tr>
                            <th>Intensitas Kepentingan</th>
                            <th>Definisi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Sama pentingnya dibanding dengan yang lain</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Sedikit lebih penting dibanding yang lain</td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>Cukup penting dibanding dengan yang lain</td>
                        </tr>
                        <tr>
                            <td>7</td>
                            <td>Sangat penting dibanding dengan yang lain</td>
                        </tr>
                        <tr>
                            <td>9</td>
                            <td>Ekstrim pentingnya dibanding yang lain</td>
                        </tr>
                        <tr>
                            <td>2,4,6,8</td>
                            <td>Nilai diantara dua penilaian yang berdekatan</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <h3 class="mt-4">Daftar Perbandingan Kriteria</h3>

        <?php
        $sql = "SELECT pk.kriteria1_id, pk.kriteria2_id, pk.nilai, k1.nama_kriteria AS kriteria1, k2.nama_kriteria AS kriteria2
            FROM perbandingan_kriteria pk
            JOIN kriteria k1 ON pk.kriteria1_id = k1.id_kriteria
            JOIN kriteria k2 ON pk.kriteria2_id = k2.id_kriteria";
        $result = $conn->query($sql);
        ?>

        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Kriteria 1</th>
                    <?php
                    $sql = "SELECT * FROM kriteria";
                    $kriteria_result = $conn->query($sql);
                    $kriteria_list = [];
                    while ($row = $kriteria_result->fetch_assoc()) {
                        $kriteria_list[$row['id_kriteria']] = $row['nama_kriteria'];
                    }

                    foreach ($kriteria_list as $kriteria_id => $kriteria_name) : ?>
                        <th><?php echo $kriteria_name; ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($kriteria_list as $row_id => $row_name) : ?>
                    <tr>
                        <td><?php echo $row_name; ?></td>
                        <?php foreach ($kriteria_list as $col_id => $col_name) : ?>
                            <td>
                                <?php
                                $value = 0;
                                foreach ($result as $row) {
                                    if ($row['kriteria1_id'] == $row_id && $row['kriteria2_id'] == $col_id) {
                                        $value = $row['nilai'];
                                    } elseif ($row['kriteria1_id'] == $col_id && $row['kriteria2_id'] == $row_id) {
                                        $value = 1 / $row['nilai'];
                                    }
                                }

                                if ($value) {
                                    echo rtrim(rtrim(number_format($value, 8, '.', ''), '0'), '.');
                                } else {
                                    echo $row_id == $col_id ? '1' : '0';
                                }
                                ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-IYSGg4AIh1h5s8FZSE3ok1riLQ/OHPH3z7f5YZ4p+nW+9BeG3ZovX3kqg7nOIVx7" crossorigin="anonymous"></script>
</body>

</html>