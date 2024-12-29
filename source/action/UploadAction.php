<?php
include('../lib/Session.php');
include_once('../model/UploadModel.php');
include_once('../model/MahasiswaModel.php');
include_once('../lib/Secure.php');

$session = new Session();

// Cek apakah user sudah login
if ($session->get('is_login') !== true) {
    header('Location: ../login.php');
    exit;
}

$act = isset($_GET['act']) ? strtolower($_GET['act']) : '';

// Proses untuk load data
if ($act == 'load') {
    $upload = new UploadModel();
    $data = $upload->getData(); // Fungsi untuk mengambil NamaSurat dan TanggalUpload
    $result = ['data' => []];
    $i = 1;

    foreach ($data as $row) {
        $result['data'][] = [
            $i,
            htmlspecialchars($row['Jenis_Surat'] ?? ''),  // Nama Surat
            htmlspecialchars($row['TanggalDibuat'] ? $row['TanggalDibuat']->format('Y-m-d') : '') // Tanggal Upload
        ];
        $i++;
    }

    echo json_encode($result);
    exit;
}

// Proses untuk mendapatkan data berdasarkan ID
if ($act == 'get') {
    $id = (isset($_GET['id']) && ctype_digit($_GET['id'])) ? $_GET['id'] : 0;

    $upload = new UploadModel();
    $data = $upload->getDataById($id);
    echo json_encode($data);
    exit;
}

if ($act == 'save') {
    // Get NIM from session
    if (!isset($_SESSION['NIM'])) {
        echo json_encode(['status' => false, 'message' => 'NIM tidak ditemukan dalam session.']);
        exit;
    }
    $NIM = $_SESSION['NIM'];

    $uploadDir = "../uploads/documents";
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $allowedTypes = ['pdf', 'doc', 'docx'];
    $maxFileSize = 10 * 1024 * 1024; // 10MB
    $fileTypes = [
        'file_skla' => 1,      // ID 1 untuk SKLA
        'file_kompensasi' => 2, // ID 2 untuk Kompensasi
        'file_ukt' => 3,       // ID 3 untuk UKT
        'file_skkm' => 4,      // ID 4 untuk SKKM
        'file_toeic' => 5,     // ID 5 untuk TOEIC
        'file_publikasi' => 6  // ID 6 untuk Publikasi
    ];

    $uploadModel = new UploadModel();

    // Mulai transaksi database
    if (!$uploadModel->beginTransaction()) {
        echo json_encode([
            'status' => false,
            'message' => "Gagal memulai transaksi database."
        ]);
        exit;
    }

    $uploadedFiles = [];
    $errors = [];
    $successCount = 0;

    try {
        foreach ($fileTypes as $fileKey => $IDSurat) {
            // Cek apakah file ada dan tidak ada error
            if (!isset($_FILES[$fileKey]) || $_FILES[$fileKey]['error'] !== UPLOAD_ERR_OK) {
                throw new Exception("File {$fileKey} belum dipilih atau terjadi error.");
            }

            $file = $_FILES[$fileKey];
            if (empty($file['name'])) {
                throw new Exception("File {$fileKey} tidak memiliki nama yang valid.");
            }

            $fileType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            // Validasi tipe file
            if (!in_array($fileType, $allowedTypes)) {
                throw new Exception("File {$fileKey}: Tipe file tidak diizinkan (hanya " . implode(', ', $allowedTypes) . ")");
            }

            // Validasi ukuran file
            if ($file['size'] > $maxFileSize) {
                throw new Exception("File {$fileKey}: Ukuran file terlalu besar (max 10MB).");
            }

            // Generate nama file yang unik
            $fileName = time() . "_" . $NIM . "_" . $IDSurat . "." . $fileType;
            $uploadFile = $uploadDir . '/' . $fileName;

            // Upload file
            if (!move_uploaded_file($file['tmp_name'], $uploadFile)) {
                throw new Exception("File {$fileKey}: Gagal mengupload file.");
            }

            $uploadedFiles[] = $uploadFile;
            
            // Simpan ke database
            $uploadModel->save([
                'Nama_file' => $fileName,
                'TanggalDibuat' => date('Y-m-d'),
                'NIM' => $NIM,
                'IDSurat' => $IDSurat
            ]);

            $successCount++;
        }

        // Jika semua file berhasil diupload, commit transaksi
        if ($successCount === count($fileTypes)) {
            $uploadModel->commit();
            echo json_encode([
                'status' => true,
                'message' => "Berhasil mengupload semua file."
            ]);
        } else {
            throw new Exception("Tidak semua file berhasil diupload.");
        }

    } catch (Exception $e) {
        // Rollback transaksi
        $uploadModel->rollback();
        
        // Hapus file yang sudah terupload
        foreach ($uploadedFiles as $file) {
            if (file_exists($file)) {
                unlink($file);
            }
        }

        echo json_encode([
            'status' => false,
            'message' => $e->getMessage()
        ]);
    }
    exit;
}