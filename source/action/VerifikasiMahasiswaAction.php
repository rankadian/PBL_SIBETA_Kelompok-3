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
    $verifikasiMahasiswa = new VerifikasiMahasiswaModel(); 
    $data = $verifikasiMahasiswa->getData(); 
    $result = []; 
    $i = 1; 
    foreach ($data as $row) { 
        $result['data'][] = [ 
            $i, 
            $row['admin_email'], 
            $row['mahasiswa_nim'], 
            $row['id_tanggungan'], 
            $row['status_validasi'], 
            '<button class="btn btn-sm btn-warning" onclick="editData(' . $row['id_verifikasi_admin'] . ')"><i class="fa fa-edit"></i></button> 
             <button class="btn btn-sm btn-danger" onclick="deleteData(' . $row['id_verifikasi_admin'] . ')"><i class="fa fa-trash"></i></button>'
        ]; 
        $i++; 
    }
    // Pastikan hanya JSON yang dikembalikan
    echo json_encode($result); 
    exit();
}

if ($act == 'get') { 
    // Ambil data verifikasi berdasarkan ID
    $id = (isset($_GET['id']) && ctype_digit($_GET['id'])) ? $_GET['id'] : 0; 
    $verifikasiMahasiswa = new VerifikasiMahasiswaModel(); 
    $data = $verifikasiMahasiswa->getDataById($id); 
    
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

    $verifikasiMahasiswa = new VerifikasiMahasiswaModel(); 
    $verifikasiMahasiswa->insertData($data); 

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

    $verifikasiMahasiswa = new VerifikasiMahasiswaModel(); 
    $verifikasiMahasiswa->updateData($id, $data); 

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

    $verifikasiMahasiswa = new VerifikasiMahasiswaModel(); 
    $verifikasiMahasiswa->deleteData($id); 

    // Return JSON response
    header('Content-Type: application/json'); 
    echo json_encode([ 
        'status' => true, 
        'message' => 'Data berhasil dihapus.' 
    ]); 
    exit();
}
?>
    