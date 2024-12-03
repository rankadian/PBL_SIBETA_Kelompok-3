<?php
session_start();
include 'db.php';  // Termasuk file koneksi ke database

// Cek apakah form login sudah disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $username = $_POST['username'];
    $password = $_POST['password'];  // Password yang dimasukkan oleh user

    // Query untuk mencari pengguna berdasarkan username
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Cek apakah user ada dan passwordnya cocok
    if ($user && password_verify($password, $user['password'])) {
        // Set session jika login berhasil
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Arahkan ke halaman dashboard berdasarkan peran pengguna
        if ($user['role'] === 'admin') {
            header('Location: admin/dashboard.php');
        } elseif ($user['role'] === 'user') {
            header('Location: user/dashboard.php');
        }
    } else {
        // Jika login gagal, set session error dan redirect kembali ke login
        $_SESSION['error'] = 'Invalid username or password';
        header('Location: login.php');
        exit;
    }
}
?>
