<?php
include('../lib/Session.php');
include_once('../model/TanggunganModel.php');
include_once('../lib/Secure.php');

$session = new Session();

// Cek login
if ($session->get('is_login') !== true) {
    header('Location: login.php');
    exit();
}

$act = isset($_GET['act']) ? $_GET['act'] : '';
$TanggunganModel = new TanggunganModel();

if ($act == 'load') {
    // Ambil NIM dari session
    $nim = $session->get('NIM');
    
    try {
        $data = $TanggunganModel->getTanggunganByNIM($nim);
        $result = [];
        $i = 1;

        foreach ($data as $row) {
            $result['data'][] = [
                $i,
                $row['IDUpload'],
                $row['Nama_file'],
                $row['Jenis_Surat'],
                $row['TanggalDibuat'],
                $row['StatusVerifikasi'],
                $row['Catatan'] ?: '-',
                '<button class="btn btn-sm btn-info" onclick="viewDocument(\''.$row['Nama_file'].'\')">
                    <i class="fas fa-eye"></i> Cek data
                </button>'
            ];
            $i++;
        }

        echo json_encode($result);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit();
}

if ($act == 'check_status') {
    $nim = $session->get('nim');
    try {
        $status = $TanggunganModel->checkAllDocumentsSubmitted($nim);
        echo json_encode(['status' => true, 'data' => $status]);
    } catch (Exception $e) {
        echo json_encode(['status' => false, 'message' => $e->getMessage()]);
    }
    exit();
}
?>