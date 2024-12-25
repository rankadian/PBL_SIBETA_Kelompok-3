<?php
include('../lib/Session.php');
include('../lib/Connection.php');

$session = new Session();
$act = isset($_GET['act']) ? strtolower($_GET['act']) : '';

if ($act === 'login') {
    // Validasi input
    if (empty($_POST['username']) || empty($_POST['password'])) {
        $session->setFlash('status', false);
        $session->setFlash('message', 'Username atau password tidak boleh kosong.');
        $session->commit();
        header('Location: ../login.php', false);
        exit;
    }

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Load UserModel untuk memeriksa pengguna
    include('../model/UserModel.php');
    $user = new UserModel();
    $data = $user->getSingleDataByKeyword('username', $username);

    if ($data && password_verify($password, $data['password'])) {
        // Set session untuk data pengguna
        $session->set('is_login', true);
        $session->set('username', $data['username']);
        $session->set('name', $data['nama']);
        $session->set('level', $data['level']);

        // Load MahasiswaModel untuk mengambil NIM berdasarkan username
        include('../model/MahasiswaModel.php');
        $mahasiswa = new MahasiswaModel();
        $mahasiswaData = $mahasiswa->getDataByUsername($data['username']); // Sesuaikan metode ini

        // Set NIM ke session jika ditemukan
        if ($mahasiswaData && isset($mahasiswaData['NIM'])) {
            $session->set('NIM', $mahasiswaData['NIM']);
        } else {
            $session->setFlash('status', false);
            $session->setFlash('message', 'Data mahasiswa tidak ditemukan.');
            $session->commit();
            header('Location: ../login.php', false);
            exit;
        }

        $session->commit();

        // Redirect ke halaman utama setelah login berhasil
        header('Location: ../index.php', false);
        exit;
    } else {
        // Jika login gagal
        $session->setFlash('status', false);
        $session->setFlash('message', 'Username atau password salah.');
        $session->commit();
        header('Location: ../login.php', false);
        exit;
    }
} elseif ($act === 'logout') {
    // Hapus semua data sesi saat logout
    $session->deleteAll();
    header('Location: ../login.php', false);
    exit;
}
