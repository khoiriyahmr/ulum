<?php
include '../config.php';
include '../navbar.php';

// Fetch classes
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

// Fetch alternatives based on selected class
if ($selected_class === 'All') {
    $sql_alternatif = "SELECT id_alternatif, nama, kelas, nilai_raport, extrakurikuler, prestasi, absensi FROM alternatif";
    $stmt = $conn->prepare($sql_alternatif);
} else {
    $sql_alternatif = "SELECT id_alternatif, nama, kelas, nilai_raport, extrakurikuler, prestasi, absensi FROM alternatif WHERE kelas = ?";
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
    $alternatifs[$row['id_alternatif']] = $row;
}

// Calculate and save rankings
foreach ($alternatifs as $id => $alt) {
    $nilai_akhir = ($alt['nilai_raport'] + $alt['extrakurikuler'] + $alt['prestasi'] + $alt['absensi']) / 4;

    // Insert or update results into the 'hasil' table
    $sql_insert = "INSERT INTO hasil (id_alternatif, nilai_akhir, ranking) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE nilai_akhir = VALUES(nilai_akhir)";
    $stmt = $conn->prepare($sql_insert);

    // Calculate ranking
    $ranking = 0;
    $sql_ranking = "SELECT COUNT(*) + 1 AS ranking FROM hasil WHERE nilai_akhir > ?";
    $stmt_ranking = $conn->prepare($sql_ranking);
    $stmt_ranking->bind_param('d', $nilai_akhir);
    $stmt_ranking->execute();
    $result_ranking = $stmt_ranking->get_result();
    $row_ranking = $result_ranking->fetch_assoc();
    $ranking = $row_ranking['ranking'];

    $stmt->bind_param('idi', $id, $nilai_akhir, $ranking);
    $stmt->execute();
}

// Retrieve and display rankings
$sql = "SELECT nama, kelas, nilai_akhir FROM hasil
        JOIN alternatif ON hasil.id_alternatif = alternatif.id_alternatif
        ORDER BY kelas, nilai_akhir DESC";
$result = $conn->query($sql);

if (!$result) {
    die("Error: " . $conn->error);
}

$rankings = [];
while ($row = $result->fetch_assoc()) {
    $kelas = $row['kelas'];
    if (!isset($rankings[$kelas])) {
        $rankings[$kelas] = $row;
    }
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