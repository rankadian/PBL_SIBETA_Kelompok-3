<?php
session_start();

// Jika user sudah login, arahkan ke halaman dashboard sesuai dengan perannya
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'admin') {
        header('Location: /admin/dashboard.php');
        exit;
    } elseif ($_SESSION['role'] === 'user') {
        header('Location: /user/dashboard.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIBETA</title>

    <!-- Favicon -->
    <link href="assets/img/sibetaV3.png" rel="icon">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom Styles (optional) -->
    <link rel="stylesheet" href="assets/css/main.css">
</head>
<body>
    <div class="d-flex justify-content-center align-items-center vh-100 bg-light">
        <div class="card shadow p-4" style="width: 100%; max-width: 400px;">
            <div style="text-align: center; margin-bottom: 2px;">
                <img src="./assets/img/sibetaV3.png" alt="icon" style="max-width: 150px; width: 100%; height: auto;">
            </div>
            <h2 class="text-center mb-4">Login - <strong>SIBETA</strong></h2>
            
            <!-- Form Login -->
            <form action="process_login.php" method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" id="username" class="form-control" placeholder="Enter your username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
                </div>
                
                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary w-100" style="background-color:#1b156a; border-color:#1b156a; color:white;">Login</button>
            </form>

            <!-- Error Message -->
            <?php
            if (isset($_SESSION['error'])) {
                echo '<div class="alert alert-danger mt-3">' . $_SESSION['error'] . '</div>';
                unset($_SESSION['error']);  // Hapus error setelah ditampilkan
            }
            ?>
        </div>
    </div>

    <!-- Bootstrap JS (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
