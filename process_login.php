<?php
session_start();
require './db/connection.php';

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
        echo "Username atau password salah!";
    }
}
?>
