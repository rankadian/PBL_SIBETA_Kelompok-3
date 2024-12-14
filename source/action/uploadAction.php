<?php
include('../lib/Session.php');
$session = new Session();

if ($session->get('is_login') !== true) {
    header('Location: login.php');
}

include_once('../model/verifikasiAdm.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle file uploads
    $nim = $_POST['nim'];
    $nama = $_POST['nama'];
    $statusTanggungan = 'Menunggu Verifikasi';  // Default status

    // Simpan file yang diupload
    $suratBebasKompen = uploadFile('surat_bebas_kompen');
    $suratValidasiPKL = uploadFile('surat_validasi_pkl');

    // Simpan data ke database
    $verifikasi = new verifikasiAdm();
    $data = [
        'NIM' => $nim,
        'Nama' => $nama,
        'StatusTanggungan' => $statusTanggungan,
        'SuratBebasKompen' => $suratBebasKompen,
        'SuratValidasiPKL' => $suratValidasiPKL
    ];
    $verifikasi->insertData($data);

    // Menambahkan fitur verifikasi otomatis setelah data di-upload
    // Status ini bisa diubah di kemudian waktu jika perlu
    echo json_encode(['status' => true, 'message' => 'Berkas berhasil diupload, menunggu verifikasi']);
}

function uploadFile($fileKey)
{
    if ($_FILES[$fileKey]['error'] == UPLOAD_ERR_OK) {
        $tmpName = $_FILES[$fileKey]['tmp_name'];
        $fileName = $_FILES[$fileKey]['name'];
        $destination = '../uploads/' . $fileName;
        move_uploaded_file($tmpName, $destination);
        return $destination;
    }
    return null;
}
?>
