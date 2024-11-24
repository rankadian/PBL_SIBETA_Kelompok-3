<?php
require '../db/connection.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $hashedPassword = hash('sha256', $password);

    // Cek apakah username sudah ada
    $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    if ($stmt->fetchColumn() > 0) {
        $error = "Username sudah digunakan. Silakan pilih username lain.";
    } else {
        // Tambahkan akun mahasiswa
        $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, 'mahasiswa')");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashedPassword);

        try {
            $stmt->execute();
            $success = "Akun mahasiswa berhasil dibuat!";
        } catch (PDOException $e) {
            $error = "Gagal membuat akun: " . $e->getMessage();
        }
    }
}
?>

<div class="card">
    <div class="card-header bg-primary text-white">
        Tambah Akun Mahasiswa
    </div>
    <div class="card-body">
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>
        <form action="" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">NIM</label>
                <input type="text" name="username" id="username" class="form-control" placeholder="Masukkan NIM" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password" required>
            </div>
            <button type="submit" class="btn btn-primary">Tambah Akun</button>
        </form>
    </div>
</div>
