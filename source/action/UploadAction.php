<?php
include('../lib/Session.php');
include_once('../model/UploadFileModel.php');
include_once('../lib/Secure.php');

$session = new Session();

if ($session->get('is_login') !== true) {
    header('Location: login.php');
}

$act = isset($_GET['act']) ? strtolower($_GET['act']) : '';

if ($act == 'load') {
    $upload = new UploadFileModel();
    $data = $upload->getData();
    $result = [];
    $i = 1;
    foreach ($data as $row) {
        $result['data'][] = [
            $i,
            $row['IDSurat'],
            $row['NamaSurat'],
            $row['JenisSurat'],
            $row['TanggalDibuat'],
            '<button class="btn btn-sm btn-warning" onclick="editData(' . $row['IDSurat'] . ')"><i class="fa fa-edit"></i></button> 
             <button class="btn btn-sm btn-danger" onclick="deleteData(' . $row['IDSurat'] . ')"><i class="fa fa-trash"></i></button>'
        ];
        $i++;
    }
    echo json_encode($result);
}

if ($act == 'get') {
    $id = (isset($_GET['id']) && ctype_digit($_GET['id'])) ? $_GET['id'] : 0;

    $upload = new UploadFileModel();
    $data = $upload->getDataById($id);
    echo json_encode($data);
}

if ($act == 'save') {
    // Check if the file and IDSurat are available
    if (isset($_FILES['laporan_tugas_akhir']) && isset($_POST['IDSurat'])) {
        $fileData = $_FILES['laporan_tugas_akhir'];
        $idSurat = $_POST['IDSurat'];

        // Instantiate the concrete class for handling file upload
        $uploadModel = new UploadFileModel();  
        $uploadResult = $uploadModel->uploadFile($fileData, $idSurat);

        if ($uploadResult === true) {
            // Prepare data to insert into the TB_Pengajuan table
            $data = [
                'IDSurat' => $idSurat,
                'TanggalPengajuan' => date('Y-m-d H:i:s'), // Current timestamp
                'StatusPengajuan' => 'Pending', // Or any default status you need
                'CatatanAdmin' => '', // You can populate this if necessary
            ];

            // Insert data into the database using the UploadFileModel's createPengajuan method
            $insertResult = $uploadModel->createPengajuan($data);

            if ($insertResult === true) {
                echo json_encode([
                    'status' => true,
                    'message' => 'Laporan berhasil diunggah dan data pengajuan disimpan.'
                ]);
            } else {
                echo json_encode([
                    'status' => false,
                    'message' => 'Laporan berhasil diunggah, tetapi gagal menyimpan pengajuan: ' . $insertResult
                ]);
            }
        } else {
            echo json_encode([
                'status' => false,
                'message' => $uploadResult // Error from file upload
            ]);
        }
    } else {
        echo json_encode([
            'status' => false,
            'message' => 'File or IDSurat is missing.'
        ]);
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check for file and IDSurat
    if (isset($_FILES['laporan_tugas_akhir']) && isset($_POST['IDSurat'])) {
        // Process the file upload
        // your upload logic here

        echo json_encode([
            'status' => true,
            'message' => 'File uploaded successfully.'
        ]);
    } else {
        echo json_encode([
            'status' => false,
            'message' => 'File or IDSurat is missing.'
        ]);
    }
} else {
    // Return an error if the method is not POST
    echo json_encode([
        'status' => false,
        'message' => 'Invalid request method.'
    ]);
}

if ($act == 'update') {
    $id = (isset($_GET['id']) && ctype_digit($_GET['id'])) ? $_GET['id'] : 0;
    $data = [
        'NamaSurat' => antiSqlInjection($_POST['NamaSurat']),
        'JenisSurat' => antiSqlInjection($_POST['JenisSurat']),
        'TanggalDibuat' => antiSqlInjection($_POST['TanggalDibuat'])
    ];

    $upload = new UploadFileModel();
    $upload->updateData($id, $data);

    echo json_encode([
        'status' => true,
        'message' => 'Data berhasil diupdate.'
    ]);
}

if ($act == 'delete') {
    $id = (isset($_GET['id']) && ctype_digit($_GET['id'])) ? $_GET['id'] : 0;

    $upload = new UploadFileModel();
    $upload->deleteData($id);

    echo json_encode([
        'status' => true,
        'message' => 'Data berhasil dihapus.'
    ]);
}
