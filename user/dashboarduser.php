<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header('Location: ../login.php');
    exit;
}
?>
<h1>Selamat datang di Dashboard User, <?php echo $_SESSION['username']; ?>!</h1>
<a href="../logout.php">Logout</a>
