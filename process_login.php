<?php
session_start();
require './db/connection.php';

$error = ''; // Variabel untuk menyimpan pesan error

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && hash('sha256', $password) === $user['password']) {
        $_SESSION['role'] = $user['role'];
        $_SESSION['username'] = $user['username'];

        // Redirect berdasarkan role
        if ($user['role'] === 'admin') {
            header('Location: admin/dashboard.php');
        } elseif ($user['role'] === 'prodi') {
            header('Location: prodi/dashboard.php');
        } elseif ($user['role'] === 'mahasiswa') {
            header('Location: mahasiswa/dashboard.php');
        } elseif ($user['role'] === 'kps') {
            header('Location: kps/dashboard.php');
        }
        exit;
    } else {
        // Simpan pesan error jika username atau password salah
        $error = "Username atau password salah!";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIBETA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="d-flex justify-content-center align-items-center vh-100 bg-light">
        <div class="card shadow p-4" style="width: 100%; max-width: 400px;">
            <div style="text-align: center; margin-bottom: 2px;">
                <img src="./assets/img/sibetaV3.png" alt="icon" style="max-width: 150px; width: 100%; height: auto;">
            </div>
            <h2 class="text-center mb-4">Login - <strong>SIBETA</strong></h2>
            <!-- Form Login -->
            <form action="" method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" id="username" class="form-control" placeholder="Enter your username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
                </div>
                <?php if ($error): ?>
                    <!-- Pesan Error -->
                    <div class="alert alert-danger text-center" role="alert">
                        <?= $error ?>
                    </div>
                <?php endif; ?>
                <button type="submit" class="btn btn-primary w-100" style="background-color:#1b156a; border-color:#1b156a; color:white;">Login</button>
            </form>
            <p class="text-center mt-3">Belum punya akun? <a href="register.php" class="text-decoration-none">Daftar</a></p>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
