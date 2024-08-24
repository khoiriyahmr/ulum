<?php
include '../../config.php';
include '../../navbar.php';

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

function calculate_comparison_matrix($alternatifs, $criteria)
{
    $matrix = [];
    $keys = array_keys($alternatifs);
    foreach ($keys as $id1) {
        foreach ($keys as $id2) {
            if ($id1 != $id2) {
                $value1 = $alternatifs[$id1][$criteria] ?? 1;
                $value2 = $alternatifs[$id2][$criteria] ?? 1;
                $matrix[$id1][$id2] = $value2 != 0 ? $value1 / $value2 : 1;
            } else {
                $matrix[$id1][$id2] = 1;
            }
        }
    }
    return $matrix;
}

$criteria = 'prestasi';

$matrix = calculate_comparison_matrix($alternatifs, $criteria);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perbandingan Alternatif Prestasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4">Perbandingan Alternatif Prestasi</h2>

        <table class="table table-bordered">
            <thead class="thead-light">
                <tr>
                    <th>Alternatif</th>
                    <?php foreach ($alternatifs as $id => $alt) : ?>
                        <th><?php echo htmlspecialchars($alt['nama']); ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($alternatifs as $id1 => $alt1) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($alt1['nama']); ?></td>
                        <?php foreach ($alternatifs as $id2 => $alt2) : ?>
                            <td><?php echo number_format($matrix[$id1][$id2] ?? 0, 4); ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>