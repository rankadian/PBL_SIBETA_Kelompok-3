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
            htmlspecialchars($row['PengajuanID'] ?? ''),
            htmlspecialchars($row['NIM'] ?? ''),
            htmlspecialchars($row['SuratID'] ?? ''),
            htmlspecialchars($row['StatusPengajuan'] ?? ''),
            htmlspecialchars($row['TanggalPengajuan'] ? $row['TanggalPengajuan']->format('Y-m-d') : ''),
            htmlspecialchars($row['FilePath'] ?? ''),
            htmlspecialchars($row['CatatanVerifikasi'] ?? '')
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
    $FilePath = '';
    if (isset($_FILES['FilePath']) && $_FILES['FilePath']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = "../upload";
        $allowedTypes = ['pdf', 'doc', 'docx'];

        // Validasi jenis file
        $fileType = pathinfo($_FILES['FilePath']['name'], PATHINFO_EXTENSION);
        if (!in_array($fileType, $allowedTypes)) {
            $response = ['status' => false, 'message' => 'Invalid file type. Allowed types are pdf, doc, and docx.'];
            echo json_encode($response);
            exit;
        }

        // Generate nama file unik
        $fileName = time() . "_" . basename($_FILES['FilePath']['name']);
        $uploadFile = $uploadDir . '/' . $fileName;

        // Pindahkan file ke direktori upload
        if (!move_uploaded_file($_FILES['FilePath']['tmp_name'], $uploadFile)) {
            $response = ['status' => false, 'message' => 'Failed to upload the file.'];
            echo json_encode($response);
            exit;
        }
        $FilePath = $fileName;
    }

    $data = [
        'NIM' => isset($_POST['NIM']) ? antiSqlInjection($_POST['NIM']) : null,
        'SuratID' => isset($_POST['SuratID']) ? antiSqlInjection($_POST['SuratID']) : null,
        'StatusPengajuan' => isset($_POST['StatusPengajuan']) ? antiSqlInjection($_POST['StatusPengajuan']) : null,
        'TanggalPengajuan' => isset($_POST['TanggalPengajuan']) ? antiSqlInjection($_POST['TanggalPengajuan']) : null,
        'FilePath' => $FilePath,
        'CatatanVerifikasi' => isset($_POST['CatatanVerifikasi']) ? antiSqlInjection($_POST['CatatanVerifikasi']) : null
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
        'NIM' => isset($_POST['NIM']) ? antiSqlInjection($_POST['NIM']) : null,
        'SuratID' => isset($_POST['SuratID']) ? antiSqlInjection($_POST['SuratID']) : null,
        'StatusPengajuan' => isset($_POST['StatusPengajuan']) ? antiSqlInjection($_POST['StatusPengajuan']) : null,
        'TanggalPengajuan' => isset($_POST['TanggalPengajuan']) ? antiSqlInjection($_POST['TanggalPengajuan']) : null,
        'FilePath' => isset($_POST['FilePath']) ? antiSqlInjection($_POST['FilePath']) : null,
        'CatatanVerifikasi' => isset($_POST['CatatanVerifikasi']) ? antiSqlInjection($_POST['CatatanVerifikasi']) : null
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
