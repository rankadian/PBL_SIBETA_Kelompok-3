<?php
session_start();
if (!isset($_SESSION['level'])) {
    header("Location: ../index.php");
    exit();
}

// Redirect kps if accessing student page
if ($_SESSION['level'] === 'mahasiswa') {
    header("Location: ../mahasiswa/dashboard.php");
    exit();
}

// Redirect admin if accessing admin page
if ($_SESSION['level'] === 'admin') {
    header("Location: ../admin/dashboard.php");
    exit();
}

// Query to get student data based on id_user
$id_user = $_SESSION['id_user'];
$query = "SELECT * FROM admin WHERE id_user = '$id_user'";
$result = sqlsrv_query($conn, $query);

if (!$result) {
    die("Query failed: " . print_r(sqlsrv_errors(), true));
}

$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

// Store student data into variablesa
$nip = $row['nip'];
$nama = $row['nama'];
?>