<?php
require '../db/connection.php';

// Inisialisasi pesan
$message = '';
$error = '';

// Fungsi Tambah Akun
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_account'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $hashedPassword = hash('sha256', $password);

    // Validasi username unik
    $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    if ($stmt->fetchColumn() > 0) {
        $error = "Username sudah digunakan. Pilih username lain.";
    } else {
        // Tambah data ke database
        $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, 'mahasiswa')");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashedPassword);

        try {
            $stmt->execute();
            $message = "Akun mahasiswa berhasil ditambahkan!";
        } catch (PDOException $e) {
            $error = "Gagal menambah akun: " . $e->getMessage();
        }
    }
}

// Fungsi Hapus Akun
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $stmt = $conn->prepare("DELETE FROM users WHERE id = :id AND role = 'mahasiswa'");
    $stmt->bindParam(':id', $id);

    try {
        $stmt->execute();
        $message = "Akun berhasil dihapus.";
    } catch (PDOException $e) {
        $error = "Gagal menghapus akun: " . $e->getMessage();
    }
}

// Ambil Data Mahasiswa
$stmt = $conn->prepare("SELECT id, username, created_at FROM users WHERE role = 'mahasiswa'");
$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container my-4">
    <h2 class="mb-4">Manajemen Akun Mahasiswa</h2>

    <!-- Pesan -->
    <?php if ($message): ?>
        <div class="alert alert-success"><?= $message ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <!-- Form Tambah Akun -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            Tambah Akun Mahasiswa
        </div>
        <div class="card-body">
            <form action="" method="POST">
                <input type="hidden" name="add_account" value="1">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" id="username" class="form-control" placeholder="Masukkan username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password" required>
                </div>
                <button type="submit" class="btn btn-primary">Tambah Akun</button>
            </form>
        </div>
    </div>

    <!-- Tabel Akun Mahasiswa -->
    <div class="card">
        <div class="card-header bg-success text-white">
            Daftar Akun Mahasiswa
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Username</th>
                        <th>Tanggal Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $index => $student): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($student['username']) ?></td>
                            <td><?= $student['created_at'] ?></td>
                            <td>
                                <a href="?page=manage_accounts&delete=<?= $student['id'] ?>" class="btn btn-danger btn-sm"
                                   onclick="return confirm('Yakin ingin menghapus akun ini?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
