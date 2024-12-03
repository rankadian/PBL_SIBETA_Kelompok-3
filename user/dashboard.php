<?php
session_start();
if ($_SESSION['role'] !== 'user') {
    header('Location: ../login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - SIBETA</title>
</head>
<body>
    <h1>Welcome User, <?php echo $_SESSION['username']; ?></h1>
    <p>This is your User Dashboard.</p>
    <a href="../logout.php">Logout</a>
</body>
</html>
