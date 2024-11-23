<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}
?>
<h1>Selamat datang di Dashboard Admin, <?php echo $_SESSION['username']; ?>!</h1>
<a href="../logout.php">Logout</a>
