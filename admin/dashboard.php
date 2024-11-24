<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- fav icons-->
    <link href="../assets/img/sibetaV3.png" rel="icon">
    <!--link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon"-->
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <div class="container my-5">
        <?php
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
            switch ($page) {
                case 'beranda':
                    include 'includes/beranda.php';
                    break;
                case 'requests':
                    include 'includes/requests.php';
                    break;
                case 'validation':
                    include 'includes/validation.php';
                    break;
                case 'graduation':
                    include 'includes/graduation.php';
                    break;
                default:
                    echo "<h3>Halaman tidak ditemukan!</h3>";
            }
        } else {
            include 'includes/beranda.php';
        }
        ?>
    </div>
    <?php include 'includes/footer.php'; ?>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
