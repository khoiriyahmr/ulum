<?php
include '../../config.php';
include '../../navbar.php';


function classify_value($value)
{
    if ($value > 5) return 1;
    elseif ($value == 4) return 2;
    elseif ($value == 3) return 3;
    elseif ($value == 2) return 4;
    else return 5;
}


$sql = "SELECT id_alternatif, nama, absensi FROM alternatif";
$result = $conn->query($sql);

if (!$result) {
    die("Error: " . $conn->error);
}


$alternatifs = [];
while ($row = $result->fetch_assoc()) {
    $alternatifs[$row['id_alternatif']] = [
        'nama' => $row['nama'],
        'absensi' => classify_value($row['absensi'])
    ];
}


function calculate_ahp_absensi($alternatifs)
{
    global $conn;

    $matrix = [];
    $num_alternatif = count($alternatifs);


    foreach ($alternatifs as $id1 => $alt1) {
        foreach ($alternatifs as $id2 => $alt2) {
            if ($alt2['absensi'] != 0) {
                $matrix[$id1][$id2] = $alt1['absensi'] / $alt2['absensi'];
            } else {
                $matrix[$id1][$id2] = 0;
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

    $sql = "INSERT INTO perbandingan_alternatif_absensi (alternatif1_id, alternatif2_id, nilai)
            VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    foreach ($matrix as $id1 => $row) {
        foreach ($row as $id2 => $value) {
            if ($id1 != $id2) {
                $stmt->bind_param("iid", $id1, $id2, $value);
                $stmt->execute();
            }
        }
    }

    return $matrix;
}

// Hitung matriks perbandingan berdasarkan absensi
$matrix = calculate_ahp_absensi($alternatifs);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perbandingan Alternatif Nilai Absensi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4">Perbandingan Alternatif Nilai Absensi</h2>
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
                                <td><?php echo isset($matrix[$id1][$id2]) ? number_format($matrix[$id1][$id2], 2) : '0'; ?></td>
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