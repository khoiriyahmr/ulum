<?php
include '../config.php';
include '../navbar.php';

// Mengambil data alternatif dari database
$sql = "SELECT id_alternatif, nama, nilai_raport, extrakurikuler, prestasi, absensi FROM alternatif";
$result = $conn->query($sql);

if (!$result) {
    die("Error: " . $conn->error);
}

$alternatifs = [];
while ($row = $result->fetch_assoc()) {
    $alternatifs[$row['id_alternatif']] = [
        'nama' => $row['nama'],
        'nilai_raport' => $row['nilai_raport'],
        'extrakurikuler' => $row['extrakurikuler'],
        'prestasi' => $row['prestasi'],
        'absensi' => $row['absensi']
    ];
}

// Bobot untuk setiap kriteria
$weights = [
    'nilai_raport' => 0.25,
    'extrakurikuler' => 0.25,
    'prestasi' => 0.25,
    'absensi' => 0.25
];

// Fungsi untuk menghitung nilai akhir
function calculate_final_score($alternatif, $weights)
{
    return ($alternatif['nilai_raport'] * $weights['nilai_raport']) +
        ($alternatif['extrakurikuler'] * $weights['extrakurikuler']) +
        ($alternatif['prestasi'] * $weights['prestasi']) +
        ($alternatif['absensi'] * $weights['absensi']);
}

// Hitung nilai akhir untuk setiap alternatif
$final_scores = [];
foreach ($alternatifs as $id => $alt) {
    $final_scores[$id] = calculate_final_score($alt, $weights);
}

// Hapus hasil yang ada sebelumnya
$conn->query("DELETE FROM hasil");

// Simpan hasil perhitungan ke dalam tabel hasil
arsort($final_scores); // Urutkan nilai akhir secara menurun
$ranking = 1;
foreach ($final_scores as $id => $score) {
    $stmt = $conn->prepare("INSERT INTO hasil (id_alternatif, nilai_akhir, ranking) VALUES (?, ?, ?)");
    $stmt->bind_param("idi", $id, $score, $ranking);
    $stmt->execute();
    $ranking++;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Perbandingan Alternatif</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4">Hasil Perbandingan Alternatif</h2>

        <?php
        // Ambil hasil dari tabel hasil
        $sql = "SELECT a.nama, h.nilai_akhir, h.ranking 
                FROM hasil h 
                JOIN alternatif a ON h.id_alternatif = a.id_alternatif 
                ORDER BY h.ranking";
        $result = $conn->query($sql);

        if (!$result) {
            die("Error: " . $conn->error);
        }
        ?>

        <table class="table table-bordered">
            <thead class="thead-light">
                <tr>
                    <th>Ranking</th>
                    <th>Alternatif</th>
                    <th>Nilai Akhir</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['ranking']); ?></td>
                        <td><?php echo htmlspecialchars($row['nama']); ?></td>
                        <td><?php echo number_format($row['nilai_akhir'], 2); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>