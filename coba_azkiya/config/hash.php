<?php
// Contoh untuk menghasilkan hash password
$password = 'kiya'; // Ganti dengan password yang ingin di-hash
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
echo $hashedPassword; // Salin hasil hash ini untuk digunakan di SQL
?>