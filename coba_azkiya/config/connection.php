<?php
$servername = "AZKIYAIHTIARILH"; //AZKIYAIHTIARILH
$database = "SIBETA_NEW_3";
$username = "";
$password = "";

// Koneksi ke database
$conn = sqlsrv_connect($servername, [
    "Database" => $database,
    "Uid" => $username,
    "PWD" => $password
]);

if (!$conn) {
    die("Koneksi gagal: " . print_r(sqlsrv_errors(), true));
}
?>