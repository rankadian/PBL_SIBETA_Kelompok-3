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

// Fungsi Edit Password
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_password'])) {
    $id = $_POST['id'];
    $new_password = $_POST['new_password'];
    $hashedPassword = hash('sha256', $new_password);

    $stmt = $conn->prepare("UPDATE users SET password = :password WHERE id = :id AND role = 'mahasiswa'");
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->bindParam(':id', $id);

    try {
        $stmt->execute();
        $message = "Password berhasil diperbarui!";
    } catch (PDOException $e) {
        $error = "Gagal memperbarui password: " . $e->getMessage();
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
                        <th>NIM</th>
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
                                <!-- Tombol Hapus -->
                                <a href="?page=manage_accounts&delete=<?= $student['id'] ?>" class="btn btn-danger btn-sm"
                                   onclick="return confirm('Yakin ingin menghapus akun ini?')">Hapus</a>

                                <!-- Form Edit Password -->
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editPasswordModal<?= $student['id'] ?>">Edit Password</button>

                                <!-- Modal Edit Password -->
                                <div class="modal fade" id="editPasswordModal<?= $student['id'] ?>" tabindex="-1" aria-labelledby="editPasswordModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editPasswordModalLabel">Edit Password - <?= htmlspecialchars($student['username']) ?></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="" method="POST">
                                                <div class="modal-body">
                                                    <input type="hidden" name="edit_password" value="1">
                                                    <input type="hidden" name="id" value="<?= $student['id'] ?>">
                                                    <div class="mb-3">
                                                        <label for="new_password" class="form-label">Password Baru</label>
                                                        <input type="password" name="new_password" class="form-control" placeholder="Masukkan password baru" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
