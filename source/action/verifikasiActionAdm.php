<?php
include('../lib/Session.php');

$session = new Session();

if ($session->get('is_login') !== true) {
    header('Location: login.php');
}

include_once('../model/verifikasiAdm.php');
include_once('../lib/Secure.php');

$act = isset($_GET['act']) ? strtolower($_GET['act']) : '';

if ($act == 'load') {
    $verifikasi = new verifikasiAdm();
    $data = $verifikasi->getData();
    $result = [];
    $i = 1;
    foreach ($data as $row) {
        $result['data'][] = [
            $i,
            $row['NIM'],
            $row['Nama'],
            '<button class="btn btn-sm btn-warning" onclick="editData(' . $row['NIM'] . ')"><i class="fa fa-edit"></i></button>  
             <button class="btn btn-sm btn-danger" onclick="deleteData(' . $row['Nama'] . ')"><i class="fa fa-trash"></i></button>'
        ];
        $i++;
    }
    echo json_encode($result);
}

if ($act == 'get') {
    $id = (isset($_GET['id']) && ctype_digit($_GET['id'])) ? $_GET['id'] : 0;

    $verifikasi = new verifikasiAdm();
    $data = $verifikasi->getDataById($id);
    echo json_encode($data);
}

if ($act == 'save') {
    $data = [
        'NIM' => antiSqlInjection($_POST['NIM']),
        'Nama' => antiSqlInjection($_POST['Nama'])
    ];
    $verifikasi = new verifikasiAdm();
    $verifikasi->insertData($data);

    echo json_encode([
        'status' => true,
        'message' => 'Data berhasil disimpan.'
    ]);
}

if ($act == 'update') {
    $id = (isset($_GET['id']) && ctype_digit($_GET['id'])) ? $_GET['id'] : 0;
    $data = [
        'NIM' => antiSqlInjection($_POST['NIM']),
        'Nama' => antiSqlInjection($_POST['Nama'])
    ];

    $verifikasi = new verifikasiAdm();
    $verifikasi->updateData($id, $data);

    echo json_encode([
        'status' => true,
        'message' => 'Data berhasil diupdate.'
    ]);
}

if ($act == 'delete') {
    $id = (isset($_GET['id']) && ctype_digit($_GET['id'])) ? $_GET['id'] : 0;

    $verifikasi = new verifikasiAdm();
    $verifikasi->deleteData($id);

    echo json_encode([
        'status' => true,
        'message' => 'Data berhasil dihapus.'
    ]);
}

if ($act == 'reject') {
    $id = (isset($_GET['nim']) && ctype_digit($_GET['nim'])) ? $_GET['nim'] : 0;

    $verifikasi = new verifikasiAdm();
    $verifikasi->deleteData($id);

    echo json_encode([
        'status' => true,
        'message' => 'Data berhasil dihapus.'
    ]);
}
