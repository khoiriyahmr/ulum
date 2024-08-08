<?php
include '../config.php';
include '../navbar.php'; 

// Ambil data kelas dari tabel alternatif
$sql_kelas = "SELECT DISTINCT kelas FROM alternatif ORDER BY kelas";
$result_kelas = $conn->query($sql_kelas);

if (!$result_kelas) {
    die("Error: " . $conn->error);
}

$kelas_options = ['All'];
while ($row = $result_kelas->fetch_assoc()) {
    $kelas_options[] = $row['kelas'];
}

// Ambil data dari tabel alternatif berdasarkan kelas yang dipilih
$selected_class = isset($_POST['kelas']) ? $_POST['kelas'] : 'All'; // Default ke 'All'

if ($selected_class === 'All') {
    $sql_alternatif = "SELECT id_alternatif, nama, kelas FROM alternatif";
    $stmt = $conn->prepare($sql_alternatif);
} else {
    $sql_alternatif = "SELECT id_alternatif, nama, kelas FROM alternatif WHERE kelas = ?";
    $stmt = $conn->prepare($sql_alternatif);
    $stmt->bind_param('s', $selected_class);
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
        'kelas' => $row['kelas']
    ];
}

// Proses perhitungan AHP
// Inisialisasi matriks perbandingan
$matrix = [];
foreach ($alternatifs as $id1 => $alt1) {
    foreach ($alternatifs as $id2 => $alt2) {
        if ($id1 != $id2) {
            // Contoh perhitungan, misalnya hasil perbandingan
            $nilai = rand(1, 9); // Gantilah dengan logika perhitungan AHP yang sebenarnya
            $matrix[$id1][$id2] = $nilai;
        } else {
            $matrix[$id1][$id2] = 1; // Diagonal matriks selalu 1
        }
    }
}

// Debugging: Tampilkan matriks perbandingan
echo "<!DOCTYPE html>";
echo "<html lang='en'>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
echo "<title>Matriks Perbandingan</title>";
echo "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css' rel='stylesheet' integrity='sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH' crossorigin='anonymous'>";
echo "</head>";
echo "<body>";
echo "<div class='container mt-5'>";
echo "<h2>Matriks Perbandingan</h2>";
echo "<form method='post' class='mb-3'>";
echo "<div class='mb-3'>";
echo "<label for='kelas' class='form-label'>Pilih Kelas:</label>";
echo "<select name='kelas' id='kelas' class='form-select' onchange='this.form.submit()'>";
foreach ($kelas_options as $kelas) {
    $selected = ($kelas == $selected_class) ? 'selected' : '';
    echo "<option value='" . htmlspecialchars($kelas) . "' $selected>" . htmlspecialchars($kelas) . "</option>";
}
echo "</select>";
echo "</div>";
echo "</form>";

echo "<table class='table table-bordered'>";
echo "<thead><tr><th>Alternatif</th>";
foreach ($alternatifs as $alt) {
    echo "<th>" . htmlspecialchars($alt['nama']) . "</th>";
}
echo "</tr></thead><tbody>";
foreach ($alternatifs as $id1 => $alt1) {
    echo "<tr><td>" . htmlspecialchars($alt1['nama']) . "</td>";
    foreach ($alternatifs as $id2 => $alt2) {
        echo "<td>" . (isset($matrix[$id1][$id2]) ? number_format($matrix[$id1][$id2], 2) : '0') . "</td>";
    }
    echo "</tr>";
}
echo "</tbody></table>";

// Simulasikan proses perhitungan AHP
// Misalnya hasil akhir dihitung dari matriks perbandingan
$nilai_akhir = [];
foreach ($alternatifs as $id => $alt) {
    // Contoh perhitungan nilai akhir, ini harus diganti dengan logika AHP yang sebenarnya
    $nilai_akhir[$id] = array_sum($matrix[$id]) / count($matrix[$id]);
}

// Masukkan hasil ke tabel 'hasil'
foreach ($nilai_akhir as $id_alternatif => $nilai) {
    $ranking = 0; // Inisialisasi ranking

    // Tentukan ranking (ini contoh sederhana, sesuaikan dengan kebutuhan)
    $sql_ranking = "SELECT COUNT(*) + 1 AS ranking FROM hasil WHERE nilai_akhir > ?";
    $stmt = $conn->prepare($sql_ranking);
    $stmt->bind_param('d', $nilai);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $ranking = $row['ranking'];

    // Masukkan data ke tabel hasil
    $sql_insert = "INSERT INTO hasil (id_alternatif, nilai_akhir, ranking) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql_insert);
    $stmt->bind_param('idi', $id_alternatif, $nilai, $ranking);
    $stmt->execute();
}

// Ambil data hasil perhitungan AHP untuk ditampilkan
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
