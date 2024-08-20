<?php
include '../../config.php';
include '../../navbar.php';


function classify_value($value)
{
    if ($value > 5) return 5;
    elseif ($value == 4) return 4;
    elseif ($value == 3) return 3;
    elseif ($value == 2) return 2;
    else return 1;
}


$sql = "SELECT id_alternatif, nama, extrakurikuler FROM alternatif";
$result = $conn->query($sql);

if (!$result) {
    die("Error: " . $conn->error);
}

$alternatifs = [];
while ($row = $result->fetch_assoc()) {
    $alternatifs[$row['id_alternatif']] = [
        'nama' => $row['nama'],
        'extrakurikuler' => classify_value($row['extrakurikuler'])
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


function calculate_normalized_matrix_and_weights($matrix)
{
    $num_alternatif = count($matrix);
    $normalized_matrix = [];
    $weights = [];


    for ($j = 0; $j < $num_alternatif; $j++) {
        $column_sum = 0;
        for ($i = 0; $i < $num_alternatif; $i++) {
            $column_sum += $matrix[$i][$j] ?? 0;
        }
        for ($i = 0; $i < $num_alternatif; $i++) {
            $normalized_matrix[$i][$j] = $column_sum != 0 ? ($matrix[$i][$j] ?? 0) / $column_sum : 0;
        }
    }


    for ($i = 0; $i < $num_alternatif; $i++) {
        $row_sum = array_sum($normalized_matrix[$i] ?? []);
        $weights[$i] = $row_sum / $num_alternatif;
    }

    return [$normalized_matrix, $weights];
}


$criteria = 'extrakurikuler';


$matrix = calculate_comparison_matrix($alternatifs, $criteria);

list($normalized_matrix, $weights) = calculate_normalized_matrix_and_weights($matrix);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perbandingan Alternatif Ekstrakurikuler</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4">Perbandingan Alternatif Ekstrakurikuler</h2>

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