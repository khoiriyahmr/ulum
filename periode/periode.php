<?php
include '../config.php';
include '../navbar.php'; 

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
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Periode</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</head>

<body>
    <div class="container mt-5">
        <h1 class="mb-4">Daftar Periode</h1>

        <!-- Dropdown Filter Kelas -->
        <form method="GET" action="" class="mb-4">
            <div class="mb-3">
                <label for="kelas" class="form-label">Filter Kelas:</label>
                <select name="kelas" id="kelas" class="form-select" onchange="this.form.submit()">
                    <option value="all" <?php echo $kelas_filter == 'all' ? 'selected' : ''; ?>>All</option>
                    <?php while ($kelas_row = $kelas_result->fetch_assoc()) : ?>
                        <option value="<?php echo htmlspecialchars($kelas_row['kelas']); ?>" <?php echo htmlspecialchars($kelas_row['kelas']) == $kelas_filter ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($kelas_row['kelas']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
        </form>

        <!-- Tabel Daftar Periode -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID Periode</th>
                    <th>Tahun</th>
                    <th>Nama Alternatif</th>
                    <th>Kelas</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id_periode']); ?></td>
                        <td><?php echo htmlspecialchars($row['tahun']); ?></td>
                        <td><?php echo htmlspecialchars($row['nama']); ?></td>
                        <td><?php echo htmlspecialchars($row['kelas']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>



</body>

</html>
