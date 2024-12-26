<?php
include('../lib/Session.php');
include_once('../model/VerifAdminModel.php');
include_once('../model/UploadModel.php');
include_once('../model/AdminModel.php');
include_once('../lib/Secure.php');

$session = new Session();

// Cek login
if ($session->get('is_login') !== true) {
    header('Location: login.php');
    exit();
}

// Mendapatkan action dari parameter
$act = isset($_GET['act']) ? strtolower($_GET['act']) : '';

if ($act == 'load') {
    // Mengambil data upload yang belum diverifikasi
    $uploadModel = new UploadModel();
    $data = $uploadModel->getData();
    
    echo json_encode([
        'status' => true,
        'data' => $data
    ]);
    exit;
}

if ($act == 'create') {
    // Mendapatkan ID Admin yang sedang login dari session
    $adminID = $session->get('AdminID');
    
    if (!$adminID) {
        echo json_encode([
            'status' => false,
            'message' => 'Admin ID tidak ditemukan dalam session'
        ]);
        exit;
    }

    // Mendapatkan data dari POST
    $idUpload = isset($_POST['IDUpload']) ? $_POST['IDUpload'] : null;

    if (!$idUpload) {
        echo json_encode([
            'status' => false,
            'message' => 'ID Upload tidak ditemukan'
        ]);
        exit;
    }

    // Menyiapkan data awal (hanya IDAdmin dan status pending)
    $data = [
        'IDUpload' => $idUpload,
        'IDAdmin' => $adminID,
        'TanggalVerifikasi' => null,
        'StatusVerifikasi' => 'Pending',
        'Catatan' => null
    ];

    // Menyimpan data verifikasi awal
    $verifModel = new VerifAdminModel();
    $result = $verifModel->insertData($data);

    echo json_encode([
        'status' => $result,
        'message' => $result ? 'Berhasil menambahkan data ke verifikasi' : 'Gagal menambahkan data ke verifikasi'
    ]);
    exit;
}