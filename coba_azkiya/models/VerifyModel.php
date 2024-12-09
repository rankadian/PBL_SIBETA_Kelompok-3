<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include('../config/connection.php');

    $id_tanggungan = $_POST['id_tanggungan'];
    $action = $_POST['action'];

    if ($action === 'approve') {
        $newStatus = 'terpenuhi';
    } elseif ($action === 'reject') {
        $newStatus = 'belum terpenuhi';
    } else {
        die("Aksi tidak valid.");
    }

    // Update status di database
    $sql = "UPDATE tanggungan SET status = ? WHERE id_tanggungan = ?";
    $stmt = sqlsrv_prepare($conn, $sql, array(&$newStatus, &$id_tanggungan));

    if (sqlsrv_execute($stmt)) {
        echo "Status telah diperbarui menjadi " . $newStatus;
    } else {
        echo "Error: " . print_r(sqlsrv_errors(), true);
    }

    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);

    // Redirect kembali ke halaman admin
    header("Location: adminPage.php");
    exit;
}
?>
