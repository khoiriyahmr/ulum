<?php
include '../config.php';
include '../navbar.php';


$sql_kelas = "SELECT DISTINCT kelas FROM alternatif ORDER BY kelas";
$result_kelas = $conn->query($sql_kelas);

if (!$result_kelas) {
    die("Error: " . $conn->error);
}

$kelas_options = ['All'];
while ($row = $result_kelas->fetch_assoc()) {
    $kelas_options[] = $row['kelas'];
}

$selected_class = isset($_POST['kelas']) ? $_POST['kelas'] : 'All';


if ($selected_class === 'All') {
    $sql_alternatif = "SELECT id_alternatif, nama, kelas, absensi FROM alternatif";
    $stmt = $conn->prepare($sql_alternatif);
} else {
    $sql_alternatif = "SELECT id_alternatif, nama, kelas, absensi FROM alternatif WHERE kelas = ?";
    $stmt = $conn->prepare($sql_alternatif);
    $stmt->bind_param('i', $selected_class);
}

$stmt->execute();
$result_alternatif = $stmt->get_result();

if (!$result_alternatif) {
    die("Error: " . $conn->error);
}

$alternatifs = [];
while ($row = $result_alternatif->fetch_assoc()) {
    $alternatifs[$row['id_alternatif']] = [
        'nama' => $row['nama'],
        'kelas' => $row['kelas'],
        'absensi' => $row['absensi']
    ];
}


$matrix = [];
foreach ($alternatifs as $id1 => $alt1) {
    foreach ($alternatifs as $id2 => $alt2) {
        if ($id1 != $id2) {
            $matrix[$id1][$id2] = $alt1['absensi'] / $alt2['absensi'];
        } else {
            $matrix[$id1][$id2] = 1;
        }
    }
}


$normalized_matrix = [];
foreach ($matrix as $row_id => $row) {
    $sum = array_sum($row);
    foreach ($row as $col_id => $value) {
        $normalized_matrix[$row_id][$col_id] = $sum != 0 ? $value / $sum : 0;
    }
}


$nilai_akhir = [];
foreach ($alternatifs as $id => $alt) {
    $nilai_akhir[$id] = array_sum($normalized_matrix[$id]) / count($normalized_matrix[$id]);
}


foreach ($nilai_akhir as $id_alternatif => $nilai) {
    $ranking = 0;

    $sql_ranking = "SELECT COUNT(*) + 1 AS ranking FROM hasil WHERE nilai_akhir > ?";
    $stmt = $conn->prepare($sql_ranking);
    $stmt->bind_param('d', $nilai);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $ranking = $row['ranking'];

    $sql_insert = "INSERT INTO hasil (id_alternatif, nilai_akhir, ranking) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE nilai_akhir = VALUES(nilai_akhir), ranking = VALUES(ranking)";
    $stmt = $conn->prepare($sql_insert);
    $stmt->bind_param('idi', $id_alternatif, $nilai, $ranking);
    $stmt->execute();
}


$sql = "SELECT nama, kelas, nilai_akhir
        FROM alternatif
        JOIN hasil ON alternatif.id_alternatif = hasil.id_alternatif
        WHERE (kelas, nilai_akhir) IN (
            SELECT kelas, MAX(nilai_akhir) AS nilai_akhir
            FROM alternatif
            JOIN hasil ON alternatif.id_alternatif = hasil.id_alternatif
            GROUP BY kelas
        )
        ORDER BY kelas, nilai_akhir DESC";

$result = $conn->query($sql);

if (!$result) {
    die("Error: " . $conn->error);
}

$rankings = [];
while ($row = $result->fetch_assoc()) {
    $kelas = $row['kelas'];
    $rankings[$kelas] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Perhitungan AHP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-5">
        <h2>Perwakilan Tiap Kelas Berdasarkan Ranking</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th>Nilai Akhir</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rankings as $ranking) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($ranking['nama']); ?></td>
                        <td><?php echo htmlspecialchars($ranking['kelas']); ?></td>
                        <td><?php echo number_format($ranking['nilai_akhir'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>