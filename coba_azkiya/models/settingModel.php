<?php
include '../config/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $nim = $_POST['nim'] ?? '';
    $jurusan = $_POST['jurusan'] ?? '';
    $prodi = $_POST['prodi'] ?? '';
    $angkatan = $_POST['angkatan'] ?? '';
    $id_mhs = $_POST['id_mhs'] ?? '';

    // Gabungkan nama
    $nama_lengkap = trim($first_name . ' ' . $last_name);

    // Query SQL
    $sqlUpdate = "UPDATE Mahasiswa SET 
                    nama = ?, 
                    nim = ?, 
                    jurusan = ?, 
                    prodi = ?, 
                    angkatan = ? 
                  WHERE id_mhs = ?";
    $params = [$nama_lengkap, $nim, $jurusan, $prodi, $angkatan, $id_mhs];

    // Eksekusi query
    $stmtUpdate = sqlsrv_query($conn, $sqlUpdate, $params);

    if ($stmtUpdate) {
        // Redirect ke halaman yang sama setelah berhasil
        echo "<script>
                alert('Biodata berhasil diperbarui!');
                window.location.href = '{$_SERVER['HTTP_REFERER']}';
            </script>";
        exit();
    } else {
        die(print_r(sqlsrv_errors(), true));
    }
}
