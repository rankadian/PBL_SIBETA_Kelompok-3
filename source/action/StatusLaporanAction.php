<?php
include('../lib/Session.php');
include_once('../model/StatusLaporanModel.php');
include_once('../lib/Secure.php');

$session = new Session();

// Cek login
if ($session->get('is_login') !== true) {
    header('Location: login.php');
    exit();
}

$act = isset($_GET['act']) ? $_GET['act'] : '';
$StatusModel = new StatusLaporanModel();

if ($act == 'load') {
    try {
        $nim = $session->get('NIM');
        if (!$nim) {
            throw new Exception('NIM tidak ditemukan');
        }

        // Get document status
        $status = $StatusModel->getStatusByNIM($nim);
        
        // Get verification progress
        $verificationStatus = $StatusModel->checkAllDocumentsVerified($nim);
        
        // Calculate current step
        $currentStep = 1; // Default: Pengajuan
        $isComplete = true;
        
        foreach ($verificationStatus as $docStatus) {
            if ($docStatus == 'Belum Upload') {
                $currentStep = 1;
                $isComplete = false;
                break;
            } elseif ($docStatus == 'Ditolak') {
                $currentStep = 2;
                $isComplete = false;
                break;
            } 
        }
        
        if ($isComplete) {
            $currentStep = 3; // Semua dokumen disetujui
        }

        // Check if can download surat bebas tanggungan
        $canDownload = $StatusModel->canDownloadBebasTanggungan($nim);

        $response = array(
            'status' => 'success',
            'data' => array(
                'documents' => $status,
                'verification_status' => $verificationStatus,
                'current_step' => $currentStep,
                'can_download' => $canDownload
            )
        );
    } catch (Exception $e) {
        $response = array(
            'status' => 'error',
            'message' => $e->getMessage()
        );
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}


