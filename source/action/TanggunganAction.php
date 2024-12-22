<?php
include('../lib/Session.php');
include_once('../model/TanggunganModel.php');
include_once('../lib/Secure.php');

$session = new Session();

if ($session->get('is_login') !== true) {
    header('Location: login.php');
    exit;
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
            $row['PengajuanID'],
            $row['NIM'],
            $row['NamaMahasiswa'],
            $row['NamaSurat'],
            $row['StatusPengajuan'],
            $row['TanggalPengajuan'],
        ];
        $i++;
    }
    echo json_encode($result);
}

if ($act == 'get') {
    $nim = isset($_GET['nim']) ? $_GET['nim'] : '';
    if (empty($nim)) {
        echo json_encode(['status' => false, 'message' => 'NIM tidak valid.']);
        exit;
    }

    $tanggungan = new TanggunganModel();
    $data = $tanggungan->getDataById($nim);
    echo json_encode($data);
}

if ($act == 'save') {
    $data = [
        'NIM' => antiSqlInjection($_POST['nim']),
        'StatusPengajuan' => antiSqlInjection($_POST['status_pengajuan']),
        'TanggalPengajuan' => antiSqlInjection($_POST['tanggal_pengajuan'])
    ];

    $tanggungan = new TanggunganModel();
    $success = $tanggungan->insertData($data);

    echo json_encode([
        'status' => $success,
        'message' => $success ? 'Data berhasil disimpan.' : 'Gagal menyimpan data.'
    ]);
}

if ($act == 'update') {
    $id = isset($_GET['id']) ? $_GET['id'] : 0;
    if (empty($id)) {
        echo json_encode(['status' => false, 'message' => 'ID tidak valid.']);
        exit;
    }

    $data = [
        'StatusPengajuan' => antiSqlInjection($_POST['status_pengajuan'])
    ];

    $tanggungan = new TanggunganModel();
    $success = $tanggungan->updateData($id, $data);

    echo json_encode([
        'status' => $success,
        'message' => $success ? 'Data berhasil diupdate.' : 'Gagal mengupdate data.'
    ]);
}

if ($act == 'delete') {
    $id = isset($_GET['id']) ? $_GET['id'] : 0;
    if (empty($id)) {
        echo json_encode(['status' => false, 'message' => 'ID tidak valid.']);
        exit;
    }

    $tanggungan = new TanggunganModel();
    $success = $tanggungan->deleteData($id);

    echo json_encode([
        'status' => $success,
        'message' => $success ? 'Data berhasil dihapus.' : 'Gagal menghapus data.'
    ]);
}
?>
