<?php
session_start();
if (!isset($_SESSION['level'])) {
    header("Location: ../index.php");
    exit();
}
?>