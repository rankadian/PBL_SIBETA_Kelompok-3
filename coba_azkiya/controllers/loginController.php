<?php
session_start();
include '../config/connection.php';

// Ambil data dari form login
$inputUsername = $_POST['username'];
$inputPassword = $_POST['password'];

// cari user berdasarkan username
$query = "SELECT * FROM TB_USER WHERE username = ?";
$params = [$inputUsername];
$stmt = sqlsrv_query($conn, $query, $params);

if ($stmt === false) {
    die("Query gagal: " . print_r(sqlsrv_errors(), true));
}

$user = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

// Validasi user
if ($user) {
    // Verif pass
    if (password_verify($inputPassword, $user['password'])) {
        // session berdasarkan level
        $_SESSION['username'] = $user['username'];
        $_SESSION['level'] = $user['level'];
        $_SESSION['id'] = $user['id'];

        // Sesuai level
        if ($user['level'] === 'admin') {
            $_SESSION['level'] = 'admin';
            header("Location: ../views/pages/admin/dashboard.php");
        } elseif ($user['level'] === 'mahasiswa') {
            $_SESSION['level'] = 'mahasiswa';
            header("Location: ../views/pages/mahasiswa/dashboard.php");
        }
    } else echo "Password salah!";
} else {
    echo "Username tidak ditemukan!";
}

// Tutup koneksi
sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>