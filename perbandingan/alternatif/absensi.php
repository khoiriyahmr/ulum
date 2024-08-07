<?php
include 'config.php';

// Ambil data alternatif dari tabel alternatif
$sql = "SELECT id_alternatif, nama, absensi FROM alternatif";
$result = $conn->query($sql);

if (!$result) {
    die("Error: " . $conn->error);
}

$alternatifs = [];
while ($row = $result->fetch_assoc()) {
    $alternatifs[$row['id_alternatif']] = $row;
}

// Fungsi untuk menghitung AHP
function calculate_ahp_absensi($alternatifs)
{
    global $conn;

    $matrix = [];
    $num_alternatif = count($alternatifs);

    // Membuat matriks perbandingan berdasarkan nilai absensi
    foreach ($alternatifs as $id1 => $alt1) {
        foreach ($alternatifs as $id2 => $alt2) {
            if ($alt2['absensi'] != 0) {
                $matrix[$id1][$id2] = $alt1['absensi'] / $alt2['absensi'];
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
    $sql = "INSERT INTO perbandingan_alternatif_absensi (alternatif1_id, alternatif2_id, nilai)
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

// Memanggil fungsi perhitungan AHP untuk nilai absensi
$matrix = calculate_ahp_absensi($alternatifs);

?>

<h2>Perbandingan Alternatif Absensi</h2>

<table border="1">
    <thead>
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