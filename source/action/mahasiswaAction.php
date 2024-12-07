// action/mahasiswaAction.php
<?php
include('../lib/Session.php');
$session = new Session();

if ($session->get('is_login') !== true) {
    header('Location: login.php');
}

include_once('../model/MahasiswaModel.php');
include_once('../lib/Secure.php');

$act = isset($_GET['act']) ? strtolower($_GET['act']) : '';

if ($_GET['act'] == 'loadTanggungJawab') {
    $mahasiswa_id = $_SESSION['mahasiswa_id']; // Assuming this is available in the session
    $data = $mahasiswaModel->getTanggungJawab($mahasiswa_id);
    echo json_encode($data);
}


if ($act == 'upload') {
    // Handle file upload
    $mahasiswa_id = $session->get('mahasiswa_id');
    $file = $_FILES['upload_file'];
    $file_name = basename($file['name']);
    $file_path = 'uploads/' . $file_name;

    // Save file to server
    move_uploaded_file($file['tmp_name'], $file_path);

    // Save file data in the database
    $status = $_POST['status']; // save or submit
    $mahasiswa = new MahasiswaModel();
    $data = [
        'mahasiswa_id' => $mahasiswa_id,
        'file_name' => $file_name,
        'file_path' => $file_path,
        'status' => $status
    ];
    $mahasiswa->saveUpload($data);

    echo json_encode([
        'status' => true,
        'message' => 'File berhasil diupload.'
    ]);
}
?>
