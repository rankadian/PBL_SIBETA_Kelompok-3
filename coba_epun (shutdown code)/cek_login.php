<?php
session_start();

// Debug: Cek apakah data POST diterima dengan benar
echo '<pre>';
print_r($_POST);
echo '</pre>';

include "lib/Connection.php";
include "fungsi/pesan_kilat.php";
include "fungsi/anti_injection.php";

// Mendapatkan input username dan password dari form login
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = antiinjection($db, $_POST['username']);
    $password = antiinjection($db, $_POST['password']);
} else {
    echo "Data POST tidak diterima dengan benar.";
    exit();
}

// Query untuk mencari username
$query = "SELECT username, level, password as hashed_password FROM [user] WHERE username = ?";
$params = array($username);

// Menjalankan query menggunakan SQL Server
$stmt = sqlsrv_query($db, $query, $params);
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));  // Jika query gagal
}

// Mengambil hasil query
$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

// Menutup koneksi SQL Server setelah selesai
sqlsrv_free_stmt($stmt);

// Jika username ditemukan
if ($row) {
    $hashed_password = $row['hashed_password'];

    // Verifikasi password langsung terhadap kolom password (tanpa salt)
    if (password_verify($password, $hashed_password)) {
        $_SESSION['username'] = $row['username'];
        $_SESSION['level'] = $row['level'];

        // Menyimpan pesan sukses sebelum redirect
        set_flashdata('success', "Login berhasil! Selamat datang, {$row['username']}.");
        if ($row['level'] === 'admin') {
            header("Location: admin/dashboard.php");
        } else {
            header("Location: user/dashboard.php");
        }
        exit();
    } else {
        // Jika password salah
        pesan('error', "Login gagal. Password Anda salah.");
        header("Location: login.php");
        exit();
    }
} else {
    // Jika username tidak ditemukan
    pesan('warning', "Username tidak ditemukan.");
    header("Location: login.php");
    exit();
}
