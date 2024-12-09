<?php
include('../lib/Session.php');
include_once('../model/UploadModel.php');
include_once('../lib/Secure.php');

// Inisialisasi session
$session = new Session();

// Cek apakah user sudah login
if ($session->get('is_login') !== true) {
    header('Location: login.php');
    exit;
}

// Tentukan variabel untuk menyimpan file upload
$uploaded_files = [];
$file_fields = ['file_absensi', 'file_ukt', 'file_pkl', 'file_toeic', 'file_skkm', 'file_publikasi'];

// Proses upload file jika ada yang dipilih
foreach ($file_fields as $i => $file_field) {
    if (isset($_FILES[$file_field]) && $_FILES[$file_field]['error'] == 0) {
        $file_name = $_FILES[$file_field]['name'];
        $file_tmp = $_FILES[$file_field]['tmp_name'];
        $file_path = 'uploads/' . $file_name;

        // Pindahkan file ke folder uploads
        if (move_uploaded_file($file_tmp, $file_path)) {
            $uploaded_files[$file_field] = $file_path; // Menyimpan path file
        } else {
            // Jika gagal upload, berikan gambar default atau pesan error
            $uploaded_files[$file_field] = 'uploads/no-image.png'; // Gambar default
        }
    } else {
        // Jika tidak ada file yang diupload, set gambar default
        $uploaded_files[$file_field] = 'uploads/no-image.png'; // Gambar default
    }
}

// Menyiapkan data untuk disimpan ke database
$data = [
    'mahasiswa_nim' => antiSqlInjection($_POST['nim']),
    'jenis_tanggungan' => antiSqlInjection($_POST['jenis_tanggungan']),
    'status_validasi' => 'belum mengajukan',
];

// Menambahkan file yang diupload ke dalam data
foreach ($uploaded_files as $key => $file) {
    $data[$key] = $file;
}

// Insert data ke database menggunakan model
$upload = new UploadModel();
$upload->insertData($data);

// Menampilkan hasil atau respon
echo json_encode([
    'status' => true,
    'message' => 'Data berhasil disimpan.'
]);

?>

<!-- HTML Form untuk Upload -->
<form method="post" enctype="multipart/form-data">
    <label for="nim">NIM Mahasiswa:</label>
    <input type="text" id="nim" name="nim" required><br><br>

    <label for="jenis_tanggungan">Jenis Tanggungan:</label>
    <select id="jenis_tanggungan" name="jenis_tanggungan">
        <option value="absensi">Absensi</option>
        <option value="ukt">UKT</option>
        <option value="pkl">PKL</option>
        <option value="toeic">TOEIC</option>
        <option value="skkm">SKKM</option>
        <option value="publikasi">Publikasi</option>
    </select><br><br>

    <label for="file_absensi">File Absensi:</label>
    <input type="file" id="file_absensi" name="file_absensi"><br><br>

    <label for="file_ukt">File UKT:</label>
    <input type="file" id="file_ukt" name="file_ukt"><br><br>

    <label for="file_pkl">File PKL:</label>
    <input type="file" id="file_pkl" name="file_pkl"><br><br>

    <label for="file_toeic">File TOEIC:</label>
    <input type="file" id="file_toeic" name="file_toeic"><br><br>

    <label for="file_skkm">File SKKM:</label>
    <input type="file" id="file_skkm" name="file_skkm"><br><br>

    <label for="file_publikasi">File Publikasi:</label>
    <input type="file" id="file_publikasi" name="file_publikasi"><br><br>

    <button type="submit">Save</button>
</form>