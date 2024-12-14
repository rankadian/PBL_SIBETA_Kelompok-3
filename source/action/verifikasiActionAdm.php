<?php
include('../lib/Session.php');
$session = new Session();

if ($session->get('is_login') !== true) {
    header('Location: login.php');
}

include_once('../model/verifikasiAdm.php');
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
            $row['StatusTanggungan'],
            '<button class="btn btn-sm btn-warning" onclick="changeStatusModal(' . $row['NIM'] . ')">Verifikasi</button>',
            '<button class="btn btn-sm btn-danger" onclick="rejectData(' . $row['NIM'] . ')">Tolak</button>'
        ];
        $i++;
    }
    echo json_encode($result);
}

if ($act == 'update') {
    $id = $_GET['id'];
    $data = [
        'StatusTanggungan' => $_POST['status'],
        'SuratBebasKompen' => $_POST['surat_bebas_kompen'],
        'SuratValidasiPKL' => $_POST['surat_validasi_pkl']
    ];

    $verifikasi = new verifikasiAdm();
    $verifikasi->updateData($id, $data);
    echo json_encode(['status' => true, 'message' => 'Status berhasil diperbarui']);
}

if ($act == 'reject') {
    $id = $_GET['id'];
    // Implement rejection logic here, such as marking the record as rejected
}
?>
