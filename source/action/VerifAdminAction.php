<?php
include('../lib/Session.php');
include_once('../model/VerifAdminModel.php');
include_once('../model/AdminModel.php');
include_once('../lib/Secure.php');

$session = new Session();

// Cek login
if ($session->get('is_login') !== true) {
    header('Location: login.php');
    exit();
}

// Mendapatkan action dari parameter
$act = isset($_GET['act']) ? strtolower($_GET['act']) : '';
$VerifAdminModel = new VerifAdminModel();

if ($act == 'load') {
    $data = $VerifAdminModel->getDetailVerifikasi();
    $result = [];
    $i = 1;

    foreach ($data as $row) {
        // Format tanggal
        $tanggalVerifikasi = $row['TanggalVerifikasi'] ? date('d/m/Y', strtotime($row['TanggalVerifikasi'])) : '-';
        $tanggalDibuat = date('d/m/Y', strtotime($row['TanggalDibuat']));

        // Format status
        $statusText = '';
        $statusClass = '';

        if ($row['StatusVerifikasi'] === null) {
            $statusText = 'Menunggu Verifikasi';
            $statusClass = 'badge badge-warning';
        } else if ($row['StatusVerifikasi'] == 1) {
            $statusText = 'Disetujui';
            $statusClass = 'badge badge-success';
        } else {
            $statusText = 'Ditolak';
            $statusClass = 'badge badge-danger';
        }

        $status = '<span class="' . $statusClass . '">' . $statusText . '</span>';

        // Tentukan tombol aksi berdasarkan status
        $actionButtons = '';
        $actionButtons = '<div class="btn-group">
    <button type="button" class="btn btn-sm btn-success" onclick="approveDocument(' . $row['IDVerifikasi'] . ')">
        <i class="fas fa-check"></i> Setuju
    </button>
    <button type="button" class="btn btn-sm btn-danger" onclick="showRejectModal(' . $row['IDVerifikasi'] . ')">
        <i class="fas fa-times"></i> Tolak
    </button>
    <button class="btn btn-sm btn-info" onclick="previewFile(\'' . $row['IDVerifikasi'] . '\', \'' . $row['Nama_file'] . '\')">
        <i class="fas fa-eye"></i> Cek data
    </button>
</div>';
        $result['data'][] = [
            $i,
            $row['IDVerifikasi'],
            $tanggalVerifikasi,
            $status,
            $row['Catatan'] ?: '-',
            $row['IDUpload'],
            $row['Nama_file'],
            $row['Jenis_Surat'],
            $tanggalDibuat,
            $row['NIM'],
            $row['Nama'],
            $row['ProgramStudi'],
            $row['NamaAdmin'],
            $actionButtons
        ];
        $i++;
    }

    echo json_encode($result);
    exit();
}

if ($act == 'update') {
    // Get ID ADMIN from session
    if (!isset($_SESSION['IDAdmin'])) {
        echo json_encode(['status' => false, 'message' => 'IDAdmin tidak ditemukan dalam session.']);
        exit;
    }
    $IDAmin = $_SESSION['IDAdmin'];
    try {
        // Validasi input
        $idVerifikasi = isset($_POST['IDVerifikasi']) ? antiSqlInjection($_POST['IDVerifikasi']) : '';
        $statusVerifikasi = isset($_POST['StatusVerifikasi']) ? antiSqlInjection($_POST['StatusVerifikasi']) : '';
        $catatan = isset($_POST['Catatan']) ? antiSqlInjection($_POST['Catatan']) : '';

        // Validasi data
        if (empty($idVerifikasi) || $statusVerifikasi === '') {
            throw new Exception('Data tidak lengkap');
        }

        // Jika status ditolak (0), catatan harus diisi
        if ($statusVerifikasi == '0' && empty($catatan)) {
            throw new Exception('Catatan penolakan harus diisi');
        }

        // Jika status disetujui (1), berikan catatan default
        if ($statusVerifikasi == '1' && empty($catatan)) {
            $catatan = 'Dokumen disetujui';
        }

        $data = [
            'IDVerifikasi' => $idVerifikasi,
            'StatusVerifikasi' => $statusVerifikasi,
            'Catatan' => $catatan,
            'TanggalVerifikasi' => date('Y-m-d'),
            'IDAdmin' => $IDAmin
        ];

        // Update data
        $result = $VerifAdminModel->updateData($idVerifikasi, $data);

        if ($result) {
            echo json_encode([
                'status' => true,
                'message' => $statusVerifikasi == '1' ? 'Dokumen berhasil disetujui' : 'Dokumen berhasil ditolak'
            ]);
        } else {
            throw new Exception('Gagal memperbarui data');
        }
    } catch (Exception $e) {
        echo json_encode([
            'status' => false,
            'message' => $e->getMessage()
        ]);
    }
    exit();
}
if ($act == 'getDocument') {
    $id = isset($_GET['id']) ? antiSqlInjection($_GET['id']) : '';

    if (empty($id)) {
        echo json_encode(['status' => false, 'message' => 'ID dokumen tidak valid']);
        exit;
    }

    try {
        $document = $VerifAdminModel->getDocumentById($id);
        if ($document) {
            $fileUrl = '../uploads/documents/' . $document['Nama_file'];
            if (file_exists($fileUrl)) {
                echo json_encode(['status' => true, 'fileUrl' => $fileUrl]);
            } else {
                echo json_encode(['status' => false, 'message' => 'File tidak ditemukan di server']);
            }
        } else {
            echo json_encode(['status' => false, 'message' => 'Dokumen tidak ditemukan']);
        }
    } catch (Exception $e) {
        echo json_encode(['status' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
    }
    exit;
}
