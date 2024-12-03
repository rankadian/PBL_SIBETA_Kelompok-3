<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - SIBETA</title>
</head>
<body>
    <h1>Welcome Admin, <?php echo $_SESSION['username']; ?></h1>
    <p>This is the Admin Dashboard.</p>
    <a href="../logout.php">Logout</a>
</body>
</html>
