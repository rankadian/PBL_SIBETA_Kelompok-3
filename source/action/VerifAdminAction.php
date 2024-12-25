<?php
include('../lib/Session.php');
include_once('../model/VerifAdminModel.php');
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
    $VerifAdminModel = new VerifAdminModel();
    $data = $VerifAdminModel->getJoinedData(); // Pastikan query-nya sesuai
    $result = [];
    $i = 1;

    foreach ($data as $row) {
        $result['data'][] = [
            $row['IDUpload'],
            $row['NIMMahasiswa'],
            $row['NamaMahasiswa'],
            $row['Nama_file'],
            $row['Jenis_Surat'],
            $row['TanggalDibuat'] = $row['TanggalDibuat']->format('Y-m-d'),
            '<button class="btn btn-sm btn-warning" onclick="editData(' . $row['IDUpload'] . ')"><i class="fa fa-edit"></i></button> 
            <button class="btn btn-sm btn-danger" onclick="deleteData(' . $row['IDUpload'] . ')"><i class="fa fa-trash"></i></button>'
        ];
        $i++;
    }

    echo json_encode($result);
    exit();
}

if ($act == 'get') {
    // Ambil data verifikasi berdasarkan ID
    $id = (isset($_GET['id']) && ctype_digit($_GET['id'])) ? $_GET['id'] : 0;
    $VerifAdminModel = new VerifAdminModel();
    $data = $VerifAdminModel->getDataById($id);

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($data);
    exit();
}

if ($act == 'save') {
    // Simpan verifikasi mahasiswa baru
    $data = [
        'admin_email' => antiSqlInjection($_POST['admin_email']),
        'mahasiswa_nim' => antiSqlInjection($_POST['mahasiswa_nim']),
        'id_tanggungan' => antiSqlInjection($_POST['id_tanggungan']),
        'status_validasi' => 'Pending',  // Status default
        'tanggal_verifikasi' => date('Y-m-d')
    ];

    $VerifAdminModel = new VerifAdminModel();
    $VerifAdminModel->insertData($data);

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode([
        'status' => true,
        'message' => 'Data berhasil disimpan.'
    ]);
    exit();
}

if ($act == 'update') {
    // Update data verifikasi mahasiswa
    $id = (isset($_GET['id']) && ctype_digit($_GET['id'])) ? $_GET['id'] : 0;
    $data = [
        'admin_email' => antiSqlInjection($_POST['admin_email']),
        'mahasiswa_nim' => antiSqlInjection($_POST['mahasiswa_nim']),
        'id_tanggungan' => antiSqlInjection($_POST['id_tanggungan']),
        'status_validasi' => antiSqlInjection($_POST['status_validasi']),
        'tanggal_verifikasi' => date('Y-m-d')
    ];

    $VerifAdminModel = new VerifAdminModel();
    $VerifAdminModel->updateData($id, $data);

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode([
        'status' => true,
        'message' => 'Data berhasil diperbarui.'
    ]);
    exit();
}

if ($act == 'delete') {
    // Hapus data verifikasi mahasiswa berdasarkan ID
    $id = (isset($_GET['id']) && ctype_digit($_GET['id'])) ? $_GET['id'] : 0;

    $VerifAdminModel = new VerifAdminModel();
    $VerifAdminModel->deleteData($id);

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode([
        'status' => true,
        'message' => 'Data berhasil dihapus.'
    ]);
    exit();
}
