<?php
// Password yang ingin di-hash
$password = "password_anda";

// Hash password menggunakan bcrypt
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

// Menampilkan hasil hash
echo "Hashed Password: " . $hashed_password;
