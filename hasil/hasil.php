<?php
include 'config.php';

// Ambil data dari tabel alternatif
$sql = "SELECT id_alternatif, nama, kelas FROM alternatif";
$result = $conn->query($sql);

if (!$result) {
    die("Error: " . $conn->error);
}

$alternatifs = [];
while ($row = $result->fetch_assoc()) {
    $alternatifs[$row['id_alternatif']] = [
        'nama' => $row['nama'],
        'kelas' => $row['kelas']
    ];
}

// Ambil data hasil perhitungan AHP
$sql = "SELECT a.nama AS alternatif_nama, b.nama AS dibandingkan_nama, h.nilai_akhir
        FROM hasil h
        JOIN alternatif a ON h.id_alternatif = a.id_alternatif
        JOIN alternatif b ON h.id_alternatif = b.id_alternatif
        ORDER BY a.nama, b.nama";
$result = $conn->query($sql);

if (!$result) {
    die("Error: " . $conn->error);
}

// Matriks hasil perhitungan
$matrix = [];
while ($row = $result->fetch_assoc()) {
    $matrix[$row['alternatif_nama']][$row['dibandingkan_nama']] = $row['nilai_akhir'];
}

// Ambil data hasil perhitungan AHP dan ranking per kelas
$sql = "SELECT a.nama, a.kelas, h.nilai_akhir
        FROM hasil h
        JOIN alternatif a ON h.id_alternatif = a.id_alternatif
        ORDER BY a.kelas, h.nilai_akhir DESC";
$result = $conn->query($sql);

if (!$result) {
    die("Error: " . $conn->error);
}

// Mengambil satu perwakilan per kelas
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
</head>

<body>
    <h2>Matriks Hasil Perhitungan AHP</h2>
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
            <?php foreach ($alternatifs as $nama1 => $alt1) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($alt1['nama']); ?></td>
                    <?php foreach ($alternatifs as $nama2 => $alt2) : ?>
                        <td>
                            <?php echo isset($matrix[$nama1][$nama2]) ? number_format($matrix[$nama1][$nama2], 2) : '0'; ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Perwakilan Tiap Kelas Berdasarkan Ranking</h2>
    <table border="1">
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
</body>

</html>