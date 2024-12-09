<?php  
include('../lib/Session.php'); 

$session = new Session(); 

if ($session->get('is_login') !== true) { 
    header('Location: login.php'); 
}

include_once('../model/MahasiswaModel.php'); // Changed to MahasiswaModel
include_once('../lib/Secure.php'); 

$act = isset($_GET['act']) ? strtolower($_GET['act']) : ''; 

// Load Mahasiswa data
if ($act == 'load') { 
    $mahasiswa = new MahasiswaModel(); 
    $data = $mahasiswa->getData(); 
    $result = []; 
    $i = 1; 
    foreach ($data as $row) { 
        $result['data'][] = [ 
            $i, 
            $row['mahasiswa_nim'], // Display NIM
            $row['mahasiswa_nama'], // Display Nama
            $row['mahasiswa_angkatan'], // Display Angkatan
            '<button class="btn btn-sm btn-warning" onclick="editData(' . $row['mahasiswa_id'] . ')"><i class="fa fa-edit"></i></button>' .
            ' <button class="btn btn-sm btn-danger" onclick="deleteData(' . $row['mahasiswa_id'] . ')"><i class="fa fa-trash"></i></button>' 
        ]; 
        $i++; 
    } 
    echo json_encode($result); 
}

// Get Mahasiswa data by ID
if ($act == 'get') { 
    $id = (isset($_GET['id']) && ctype_digit($_GET['id'])) ? $_GET['id'] : 0; 

    $mahasiswa = new MahasiswaModel(); 
    $data = $mahasiswa->getDataById($id); 
    echo json_encode($data); 
}

// Save new Mahasiswa data
if ($act == 'save') { 
    $data = [ 
        'mahasiswa_nim' => antiSqlInjection($_POST['mahasiswa_nim']), 
        'mahasiswa_nama' => antiSqlInjection($_POST['mahasiswa_nama']),
        'mahasiswa_angkatan' => antiSqlInjection($_POST['mahasiswa_angkatan']) // Added angkatan
    ];
    
    $mahasiswa = new MahasiswaModel(); 
    $mahasiswa->insertData($data); 

    echo json_encode([ 
        'status' => true,  
        'message' => 'Data berhasil disimpan.' 
    ]); 
}

// Update Mahasiswa data
if ($act == 'update') { 
    $id = (isset($_GET['id']) && ctype_digit($_GET['id'])) ? $_GET['id'] : 0; 
    $data = [ 
        'mahasiswa_nim' => antiSqlInjection($_POST['mahasiswa_nim']),
        'mahasiswa_nama' => antiSqlInjection($_POST['mahasiswa_nama']),
        'mahasiswa_angkatan' => antiSqlInjection($_POST['mahasiswa_angkatan']) // Added angkatan
    ]; 

    $mahasiswa = new MahasiswaModel(); 
    $mahasiswa->updateData($id, $data); 

    echo json_encode([ 
        'status' => true,  
        'message' => 'Data berhasil diupdate.' 
    ]); 
}

// Delete Mahasiswa data
if ($act == 'delete') { 
    $id = (isset($_GET['id']) && ctype_digit($_GET['id'])) ? $_GET['id'] : 0; 

    $mahasiswa = new MahasiswaModel(); 
    $mahasiswa->deleteData($id); 

    echo json_encode([ 
        'status' => true,  
        'message' => 'Data berhasil dihapus.' 
    ]); 
} 
?>
