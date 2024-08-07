<?php
include '../config.php';

// Ambil filter kelas dari parameter GET
$kelas_filter = isset($_GET['kelas']) ? $_GET['kelas'] : 'all';

// Query untuk mendapatkan kelas yang unik dari tabel alternatif
$kelas_query = "SELECT DISTINCT kelas FROM alternatif";
$kelas_result = $conn->query($kelas_query);

// Query untuk mengambil data periode berdasarkan filter kelas
$filter_query = $kelas_filter == 'all' ? "" : "AND a.kelas = '$kelas_filter'";
$sql = "SELECT p.id_periode, p.tahun, a.nama, a.kelas 
        FROM periode p
        JOIN alternatif a ON p.id_alternatif = a.id_alternatif
        WHERE 1=1 $filter_query
        ORDER BY p.tahun DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Daftar Periode</title>
</head>

<body>
    <h1>Daftar Periode</h1>

    <!-- Dropdown Filter Kelas -->
    <form method="GET" action="">
        <label for="kelas">Filter Kelas:</label>
        <select name="kelas" id="kelas" onchange="this.form.submit()">
            <option value="all" <?php echo $kelas_filter == 'all' ? 'selected' : ''; ?>>All</option>
            <?php while ($kelas_row = $kelas_result->fetch_assoc()) : ?>
                <option value="<?php echo htmlspecialchars($kelas_row['kelas']); ?>" <?php echo htmlspecialchars($kelas_row['kelas']) == $kelas_filter ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($kelas_row['kelas']); ?>
                </option>
            <?php endwhile; ?>
        </select>
    </form>

    <!-- Tabel Daftar Periode -->
    <table border="1">
        <tr>
            <th>ID Periode</th>
            <th>Tahun</th>
            <th>Nama Alternatif</th>
            <th>Kelas</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) : ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id_periode']); ?></td>
                <td><?php echo htmlspecialchars($row['tahun']); ?></td>
                <td><?php echo htmlspecialchars($row['nama']); ?></td>
                <td><?php echo htmlspecialchars($row['kelas']); ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>

</html>