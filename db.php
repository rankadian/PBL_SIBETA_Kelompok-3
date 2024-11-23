<?php
$host = "localhost"; // Server host
$user = "root";      // Username MySQL Anda
$password = "";      // Password MySQL Anda
$database = "sibeta_web"; // Nama database

// Buat koneksi
$conn = new mysqli($host, $user, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
