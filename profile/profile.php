<?php
session_start();
include '../navbar.php';
include '../config.php'; // Sertakan koneksi ke database

// Cek apakah pengguna sudah login
if (!isset($_SESSION['id_admin'])) {
    // Redirect ke halaman login jika belum login
    header("Location: login.php");
    exit;
}

// Ambil ID admin dari sesi
$id_admin = $_SESSION['id_admin'];

// Ambil data admin dari database berdasarkan ID
$sql = "SELECT * FROM admin WHERE id_admin = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_admin);
$stmt->execute();
$result = $stmt->get_result();

// Periksa apakah data admin ditemukan
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $nama = $row['nama'];
    $email = $row['email'];
} else {
    // Jika data tidak ditemukan, set default value atau redirect
    $nama = "Nama tidak ditemukan";
    $email = "Email tidak ditemukan";
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" value="<?php echo $nama; ?>" readonly>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" value="<?php echo $email; ?>" readonly>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" value="********" readonly>
                </div>

                <div class="d-grid gap-2 mb-3">
                    <a href="edit_profile.php" class="btn btn-primary">Edit Profile</a>
                    <a href="logout.php" class="btn btn-danger">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>

</html>