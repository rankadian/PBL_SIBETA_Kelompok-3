<?php
include('../lib/Session.php'); 
include_once('../model/VerifikasiMahasiswaModel.php'); 
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
    // Load semua verifikasi mahasiswa
    $verifikasiMahasiswa = new VerifikasiMahasiswaModel(); 
    $data = $verifikasiMahasiswa->getData(); 
    $result = []; 
    $i = 1; 
    foreach ($data as $row) { 
        $result['data'][] = [ 
            $i, 
            $row['mahasiswa_nim'], 
            $row['id_tanggungan'], 
            $row['status_validasi'], 
            $row['tanggal_verifikasi'], 
            '<button class="btn btn-sm btn-warning" onclick="editData(' . $row['id_verifikasi_admin'] . ')"><i class="fa fa-edit"></i></button> 
             <button class="btn btn-sm btn-danger" onclick="deleteData(' . $row['id_verifikasi_admin'] . ')"><i class="fa fa-trash"></i></button>'
        ]; 
        $i++; 
    } 
    echo json_encode($result); 
}

if ($act == 'get') { 
    // Ambil data verifikasi berdasarkan ID
    $id = (isset($_GET['id']) && ctype_digit($_GET['id'])) ? $_GET['id'] : 0; 
    $verifikasiMahasiswa = new VerifikasiMahasiswaModel(); 
    $data = $verifikasiMahasiswa->getDataById($id); 
    echo json_encode($data); 
}

if ($act == 'save') { 
    // Simpan verifikasi mahasiswa baru
    $data = [ 
        'admin_email' => antiSqlInjection($_POST['admin_email']), 
        'mahasiswa_nim' => antiSqlInjection($_POST['mahasiswa_nim']), 
        'id_tanggungan' => antiSqlInjection($_POST['id_tanggungan']),
        'status_validasi' => 'Pending',  // Set status to Pending by default
        'tanggal_verifikasi' => date('Y-m-d') 
    ]; 

    $verifikasiMahasiswa = new VerifikasiMahasiswaModel(); 
    $verifikasiMahasiswa->insertData($data); 

    echo json_encode([ 
        'status' => true, 
        'message' => 'Verifikasi berhasil disimpan.' 
    ]); 
}

if ($act == 'update') { 
    // Update verifikasi mahasiswa
    $id = (isset($_GET['id']) && ctype_digit($_GET['id'])) ? $_GET['id'] : 0; 
    $data = [ 
        'admin_email' => antiSqlInjection($_POST['admin_email']), 
        'mahasiswa_nim' => antiSqlInjection($_POST['mahasiswa_nim']), 
        'id_tanggungan' => antiSqlInjection($_POST['id_tanggungan']),
        'status_validasi' => antiSqlInjection($_POST['status_validasi']),  // Use status from the form
        'tanggal_verifikasi' => date('Y-m-d') 
    ]; 

    $verifikasiMahasiswa = new VerifikasiMahasiswaModel(); 
    $verifikasiMahasiswa->updateData($id, $data); 

    echo json_encode([ 
        'status' => true, 
        'message' => 'Verifikasi berhasil diperbarui.' 
    ]); 
}

if ($act == 'delete') { 
    // Hapus verifikasi mahasiswa berdasarkan ID
    $id = (isset($_GET['id']) && ctype_digit($_GET['id'])) ? $_GET['id'] : 0; 

    $verifikasiMahasiswa = new VerifikasiMahasiswaModel(); 
    $verifikasiMahasiswa->deleteData($id); 

    echo json_encode([ 
        'status' => true, 
        'message' => 'Verifikasi berhasil dihapus.' 
    ]); 
}
?>
