<?php
$host = 'localhost';
$dbname = 'sibeta_web_3';
$username = 'root'; // Sesuaikan dengan username MySQL Anda
$password = ''; // Sesuaikan dengan password MySQL Anda

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}
?>

<!-- erorr
