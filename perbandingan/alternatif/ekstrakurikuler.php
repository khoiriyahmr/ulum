<?php
include '../../config.php';
include '../../navbar.php'; 

// Ambil data alternatif dari tabel alternatif
$sql = "SELECT id_alternatif, nama, extrakurikuler FROM alternatif";
$result = $conn->query($sql);

if (!$result) {
    die("Error: " . $conn->error);
}

$alternatifs = [];
while ($row = $result->fetch_assoc()) {
    $alternatifs[$row['id_alternatif']] = $row;
}

// Fungsi untuk menghitung AHP
function calculate_ahp_ekstrakurikuler($alternatifs)
{
    global $conn;

    $matrix = [];
    $num_alternatif = count($alternatifs);

    // Membuat matriks perbandingan berdasarkan nilai ekstrakurikuler
    foreach ($alternatifs as $id1 => $alt1) {
        foreach ($alternatifs as $id2 => $alt2) {
            if ($alt2['extrakurikuler'] != 0) {
                $matrix[$id1][$id2] = $alt1['extrakurikuler'] / $alt2['extrakurikuler'];
            } else {
                $matrix[$id1][$id2] = 0; // Menghindari pembagian dengan nol
            }
        }
    }

    // Normalisasi matriks
    $normalized_matrix = [];
    foreach ($matrix as $row_id => $row) {
        $sum = array_sum($row);
        foreach ($row as $col_id => $value) {
            $normalized_matrix[$row_id][$col_id] = $sum != 0 ? $value / $sum : 0;
        }
    }

    // Simpan data perbandingan ke tabel
    $sql = "INSERT INTO perbandingan_alternatif_ekstrakurikuler (alternatif1_id, alternatif2_id, nilai)
            VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    foreach ($matrix as $id1 => $row) {
        foreach ($row as $id2 => $value) {
            if ($id1 != $id2) { // Menghindari perbandingan diri sendiri
                $stmt->bind_param("iid", $id1, $id2, $value);
                $stmt->execute();
            }
        }
    }

    return $matrix;
}

// Memanggil fungsi perhitungan AHP untuk nilai ekstrakurikuler
$matrix = calculate_ahp_ekstrakurikuler($alternatifs);

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perbandingan Alternatif Ekstrakurikuler</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</head>
<body>

<div class="container mt-5">
<h2 class="mb-4">Perbandingan Alternatif Nilai Ekstrakurikuler</h2>
<table class="table table-bordered">
            <thead class="thead-light">
        <tr>
            <th>Alternatif</th>
            <?php foreach ($alternatifs as $alt) : ?>
                <th><?php echo htmlspecialchars($alt['nama']); ?></th>
            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($matrix)) : ?>
            <?php foreach ($alternatifs as $id1 => $alt1) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($alt1['nama']); ?></td>
                    <?php foreach ($alternatifs as $id2 => $alt2) : ?>
                        <td>
                            <?php echo isset($matrix[$id1][$id2]) ? number_format($matrix[$id1][$id2], 2) : '0'; ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr>
                <td colspan="<?php echo count($alternatifs) + 1; ?>">Tidak ada data untuk ditampilkan.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
</div>
    

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>




