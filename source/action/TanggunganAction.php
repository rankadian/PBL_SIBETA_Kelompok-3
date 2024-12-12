<?php
include('../lib/Session.php');
include_once('../model/TanggunganModel.php');
include_once('../lib/Secure.php');

$session = new Session();

if ($session->get('is_login') !== true) {
    header('Location: login.php');
}

$act = isset($_GET['act']) ? strtolower($_GET['act']) : '';

if ($act == 'load') {
    $tanggungan = new TanggunganModel();
    $data = $tanggungan->getData();
    $result = [];
    $i = 1;
    foreach ($data as $row) {
        $result['data'][] = [
            $i,
            $row['id_tanggungan'],
            $row['mahasiswa_nim'],
            $row['id_jenis'],
            $row['status_validasi'],
            $row['berkas'],
            $row['tanggal_ajukan'],
            '<button class="btn btn-sm btn-warning" onclick="editData(' . $row['id_tanggungan'] . ')"><i class="fa fa-edit"></i></button> 
             <button class="btn btn-sm btn-danger" onclick="deleteData(' . $row['id_tanggungan'] . ')"><i class="fa fa-trash"></i></button>'
        ];
        $i++;
    }
    echo json_encode($result);
}

if ($act == 'get') {
    $id = (isset($_GET['id']) && ctype_digit($_GET['id'])) ? $_GET['id'] : 0;

    $tanggungan = new TanggunganModel();
    $data = $tanggungan->getDataById($id);
    echo json_encode($data);
}

if ($act == 'save') {
    $data = [
        'id_tanggungan' => antiSqlInjection($_POST['id_tanggungan']),
        'mahasiswa_nim' => antiSqlInjection($_POST['mahasiswa_nim']),
        'id_jenis' => antiSqlInjection($_POST['id_jenis']),
        'status_validasi' => antiSqlInjection($_POST['status_validasi']),
        'berkas' => antiSqlInjection($_POST['berkas']),
        'tanggal_ajukan' => antiSqlInjection($_POST['tanggal_ajukan'])
    ];

    $tanggungan = new TanggunganModel();
    $tanggungan->insertData($data);

    echo json_encode([
        'status' => true,
        'message' => 'Data berhasil disimpan.'
    ]);
}

if ($act == 'update') {
    $id = (isset($_GET['id']) && ctype_digit($_GET['id'])) ? $_GET['id'] : 0;
    $data = [
        'mahasiswa_nim' => antiSqlInjection($_POST['mahasiswa_nim']),
        'id_jenis' => antiSqlInjection($_POST['id_jenis']),
        'status_validasi' => antiSqlInjection($_POST['status_validasi']),
        'berkas' => antiSqlInjection($_POST['berkas']),
        'tanggal_ajukan' => antiSqlInjection($_POST['tanggal_ajukan'])
    ];

    $tanggungan = new TanggunganModel();
    $tanggungan->updateData($id, $data);

    echo json_encode([
        'status' => true,
        'message' => 'Data berhasil diupdate.'
    ]);
}

if ($act == 'delete') {
    $id = (isset($_GET['id']) && ctype_digit($_GET['id'])) ? $_GET['id'] : 0;

    $tanggungan = new TanggunganModel();
    $tanggungan->deleteData($id);

    echo json_encode([
        'status' => true,
        'message' => 'Data berhasil dihapus.'
    ]);
}
