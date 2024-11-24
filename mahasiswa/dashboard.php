<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'mahasiswa') {
    header('Location: ../login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Mahasiswa</title>
    <!-- fav icons-->
     <link href="../assets/img/sibetaV3.png" rel="icon">
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
                case 'layanan':
                    include 'includes/layanan.php';
                    break;
                case 'status':
                    include 'includes/status.php';
                    break;
                case 'wisuda':
                    include 'includes/wisuda.php';
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
