<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'bebas_tanggungan';

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
