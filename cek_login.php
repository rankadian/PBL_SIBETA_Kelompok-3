<?php
session_start();

// Memasukkan koneksi SQL Server dan fungsi lainnya
include "lib/Connection.php";  // Memasukkan koneksi SQL Server
include "fungsi/pesan_kilat.php";  // Memasukkan fungsi pesan
include "fungsi/anti_injection.php";  // Memasukkan fungsi anti injection

// Mendapatkan input username dan password dari form login
$username = antiinjection($db, $_POST['username']);  // Gunakan koneksi $db untuk anti injection
$password = antiinjection($db, $_POST['password']);  // Gunakan koneksi $db untuk anti injection

// Query untuk mencari username
$query = "SELECT username, level, salt, password as hashed_password FROM user WHERE username = ?";
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
    $salt = $row['salt'];
    $hashed_password = $row['hashed_password'];

    // Menggabungkan password dengan salt
    $combined_password = $salt . $password;

    // Verifikasi password
    if (password_verify($combined_password, $hashed_password)) {
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
