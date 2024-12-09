<?php
session_start();
if (!isset($_SESSION['level'])) {
    header("Location: ../index.php");
    exit();
}

// Redirect admin if accessing student page
if ($_SESSION['level'] === 'mahasiswa') {
    header("Location: ../mahasiswa/dashboard.php");
    exit();
}

// Query to get student data based on id_user
$user_id = $_SESSION['user_id']; //id tb_users
$query = "SELECT * FROM TB_ADMIN WHERE user_id = '$user_id'"; //id_user foreign admin
$result = sqlsrv_query($conn, $query);

if (!$result) {
    die("Query failed: " . print_r(sqlsrv_errors(), true));
}

$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

// Store student data into variablesa
$username = $row['username'];
?>