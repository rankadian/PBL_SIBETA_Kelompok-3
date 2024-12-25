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

// Proses untuk menyimpan data (termasuk upload file)
if ($act == 'save') {
    // Check if a file was uploaded
    $FilePath = '';
    if (isset($_FILES['FilePath']) && $_FILES['FilePath']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = "../upload";
        $allowedTypes = ['pdf', 'doc', 'docx'];
        $fileType = pathinfo($_FILES['FilePath']['name'], PATHINFO_EXTENSION);

        if (!in_array(strtolower($fileType), $allowedTypes)) {
            echo json_encode(['status' => false, 'message' => 'Invalid file type.']);
            exit;
        }
        if ($_FILES['FilePath']['size'] > 10000000) {
            echo json_encode(['status' => false, 'message' => 'File is too large.']);
            exit;
        }

        $fileName = time() . "_" . basename($_FILES['FilePath']['name']);
        $uploadFile = $uploadDir . '/' . $fileName;
        if (move_uploaded_file($_FILES['FilePath']['tmp_name'], $uploadFile)) {
            $FilePath = $fileName;
        } else {
            echo json_encode(['status' => false, 'message' => 'Failed to move uploaded file.']);
            exit;
        }
    }

    // Get NIM from session
    if (!isset($_SESSION['NIM'])) {
        echo json_encode(['status' => false, 'message' => 'NIM is not set in session.']);
        exit;
    }
    $NIM = $_SESSION['NIM'];

    // Validate Jenis_Surat
    $Jenis_Surat = isset($_POST['Jenis_Surat']) && !empty(trim($_POST['Jenis_Surat']))
        ? trim($_POST['Jenis_Surat'])
        : null;

    if (is_null($Jenis_Surat)) {
        echo json_encode(['status' => false, 'message' => 'Please select a valid Jenis_Surat.']);
        exit;
    }


    // Validate or set TanggalDibuat
    $TanggalDibuat = isset($_POST['TanggalDibuat']) && !empty($_POST['TanggalDibuat'])
        ? antiSqlInjection($_POST['TanggalDibuat'])
        : date('Y-m-d');

    // Prepare data
    $data = [
        'Nama_file' => $FilePath,
        'Jenis_Surat' => $Jenis_Surat,
        'TanggalDibuat' => $TanggalDibuat,
        'NIM' => $NIM,
    ];

    // Save to database
    $upload = new UploadModel();
    $result = $upload->insertData($data);

    echo json_encode([
        'status' => $result,
        'message' => $result ? 'Data successfully saved.' : 'Failed to save data.',
    ]);
    exit;
}
