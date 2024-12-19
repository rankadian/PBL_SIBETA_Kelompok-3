<?php
include('../lib/Session.php');
include_once('../model/UploadModel.php');
include_once('../lib/Secure.php');

$session = new Session();

// Cek apakah user sudah login
if ($session->get('is_login') !== true) {
    header('Location: login.php');
    exit;
}

$act = isset($_GET['act']) ? strtolower($_GET['act']) : '';

// Proses untuk load data
if ($act == 'load') {
    $upload = new UploadModel();
    $data = $upload->getData();
    $result = ['data' => []];
    $i = 1;
    foreach ($data as $row) {
        $result['data'][] = [
            $i,
            htmlspecialchars($row['NamaSurat'] ?? ''),
            htmlspecialchars($row['TanggalDibuat'] ? $row['TanggalDibuat']->format('Y-m-d') : ''),
            htmlspecialchars($row['BuktiSurat'] ?? '')
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

// Proses untuk menyimpan data (termasuk upload file)
if ($act == 'save') {
    $BuktiSurat = '';
    if (isset($_FILES['BuktiSurat']) && $_FILES['BuktiSurat']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = "../upload";
        $allowedTypes = ['pdf', 'doc', 'docx'];

        // Validasi jenis file
        $fileType = pathinfo($_FILES['BuktiSurat']['name'], PATHINFO_EXTENSION);
        if (!in_array($fileType, $allowedTypes)) {
            $response = ['status' => false, 'message' => 'Invalid file type. Allowed types are pdf, doc, and docx.'];
            echo json_encode($response);
            exit;
        }

        // Generate nama file unik
        $fileName = time() . "_" . basename($_FILES['BuktiSurat']['name']);
        $uploadFile = $uploadDir . '/' . $fileName;

        // Pindahkan file ke direktori upload
        if (!move_uploaded_file($_FILES['BuktiSurat']['tmp_name'], $uploadFile)) {
            $response = ['status' => false, 'message' => 'Failed to upload the file.'];
            echo json_encode($response);
            exit;
        }
        $BuktiSurat = $fileName;
    }
    $data = [
        'NamaSurat' => isset($_POST['NamaSurat']) ? antiSqlInjection($_POST['NamaSurat']) : null,
        'TanggalDibuat' => isset($_POST['TanggalDibuat']) ? antiSqlInjection($_POST['TanggalDibuat']) : null,
        'BuktiSurat' => $BuktiSurat
    ];

    $upload = new UploadModel();
    $result = $upload->insertData($data);

    if ($result) {
        echo json_encode(['status' => true, 'message' => 'Data berhasil disimpan.']);
    } else {
        echo json_encode(['status' => false, 'message' => 'Gagal menyimpan data.']);
    }
    exit;
}

// Proses untuk mengupdate data
if ($act == 'update') {
    $id = (isset($_GET['id']) && ctype_digit($_GET['id'])) ? $_GET['id'] : 0;

    $data = [
        'NamaSurat' => htmlspecialchars($_POST['NamaSurat']),
        'TanggalDibuat' => htmlspecialchars($_POST['TanggalDibuat'])
    ];

    $upload = new UploadModel();
    if ($upload->updateData($id, $data)) {
        echo json_encode(['status' => true, 'message' => 'Data berhasil diupdate.']);
    } else {
        echo json_encode(['status' => false, 'message' => 'Gagal mengupdate data.']);
    }
    exit;
}

// Proses untuk menghapus data
if ($act == 'delete') {
    $id = (isset($_GET['id']) && ctype_digit($_GET['id'])) ? $_GET['id'] : 0;

    $upload = new UploadModel();
    if ($upload->deleteData($id)) {
        echo json_encode(['status' => true, 'message' => 'Data berhasil dihapus.']);
    } else {
        echo json_encode(['status' => false, 'message' => 'Gagal menghapus data.']);
    }
    exit;
}
