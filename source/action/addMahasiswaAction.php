<?php
include('../lib/Session.php'); 

$session = new Session(); 

if($session->get('is_login') !== true){ 
    header('Location: login.php'); 
} 

include_once('../model/addMahasiswaModel.php'); 
include_once('../lib/Secure.php'); 
// Include model MahasiswaModel
require_once '../model/addMahasiswaModel.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['level'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $level = $_POST['level'];

        // Prepare the data as an associative array
        $data = [
            'username' => $username,
            'password' => $password,
            'level' => $level
        ];

        // Inisialisasi objek model Mahasiswa
        $mahasiswaModel = new addMahasiswaModel();

        // Panggil fungsi untuk menyimpan data mahasiswa
        if ($mahasiswaModel->insertData($data)) {
            echo "Mahasiswa berhasil ditambahkan!";
        } else {
            echo "Terjadi kesalahan saat menambahkan mahasiswa.";
        }
    } else {
        echo "Form data tidak lengkap.";
    }
}
?>
