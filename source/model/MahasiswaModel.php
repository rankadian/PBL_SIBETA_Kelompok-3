<?php

class MahasiswaModel
{
    protected $db;
    protected $table = 'TB_Mahasiswa';

    public function __construct()
    {
        include('../lib/Connection.php');
        $this->db = $db;
    }

    // Ambil data mahasiswa berdasarkan username
    public function getDataByUsername($username)
    {
    // Persiapkan query dengan parameter
    $sql = "SELECT m.NIM 
            FROM {$this->table} m 
            JOIN TB_USER u ON m.ID = u.ID 
            WHERE u.username = ?";

    // Persiapkan statement
    $stmt = sqlsrv_prepare($this->db, $sql, [$username]);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true)); // Debug jika terjadi kesalahan saat persiapan
    }

    // Eksekusi query
    $result = sqlsrv_execute($stmt);

    if ($result === false) {
        die(print_r(sqlsrv_errors(), true)); // Debug jika terjadi kesalahan saat eksekusi
    }

    // Ambil hasilnya
    return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    }

    
}
