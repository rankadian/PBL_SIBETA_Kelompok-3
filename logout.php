<?php
session_start(); // Mulai sesi
session_unset(); // Hapus semua variabel sesi
session_destroy(); // Hancurkan sesi

// Redirect ke halaman index.php
header('Location: index.php');
exit;
?>
