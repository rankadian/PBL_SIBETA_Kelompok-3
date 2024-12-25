<?php
include_once('Model.php');

class VerifAdmin extends Model
{
    protected $db;
    protected $table = 'TB_Verifikasi';

    public function __construct()
    {
        include('../lib/Connection.php');
        $this->db = $db;
    }

    // Implementasi insertData - Menambahkan data verifikasi
    public function insertData($data)
    {
        $query = "INSERT INTO {$this->table} (IDUpload, IDAdmin, TanggalVerifikasi, StatusVerifikasi, Catatan) 
                  VALUES (?, ?, ?, ?, ?)";
        
        $params = [
            $data['IDUpload'],
            $data['IDAdmin'],
            $data['TanggalVerifikasi'],
            $data['StatusVerifikasi'],
            $data['Catatan']
        ];

        $stmt = sqlsrv_query($this->db, $query, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        return sqlsrv_rows_affected($stmt) > 0; // Jika berhasil, return true
    }

    // Implementasi getData - Mengambil semua data verifikasi
    public function getData()
    {
        $query = "SELECT * FROM {$this->table}";
        $result = sqlsrv_query($this->db, $query);

        if ($result === false) {
            die(print_r(sqlsrv_errors(), true)); // Debugging error
        }

        $data = [];
        while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
            $data[] = $row;
        }

        return $data;
    }

    // Implementasi getDataById - Mengambil data verifikasi berdasarkan ID
    public function getDataById($id)
    {
        $query = "SELECT * FROM {$this->table} WHERE IDVerifikasi = ?";
        $params = [$id];

        $stmt = sqlsrv_query($this->db, $query, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true)); // Debugging error
        }

        return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    }

    // Implementasi updateData - Mengupdate status verifikasi
    public function updateData($id, $data)
    {
        $query = "UPDATE {$this->table} SET StatusVerifikasi = ?, Catatan = ? WHERE IDVerifikasi = ?";
        $params = [
            $data['StatusVerifikasi'],
            $data['Catatan'],
            $id
        ];

        $stmt = sqlsrv_query($this->db, $query, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true)); // Debugging error
        }

        return sqlsrv_rows_affected($stmt) > 0;
    }

    // Implementasi deleteData - Menghapus data verifikasi
    public function deleteData($id)
    {
        $query = "DELETE FROM {$this->table} WHERE IDVerifikasi = ?";
        $params = [$id];

        $stmt = sqlsrv_query($this->db, $query, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true)); // Debugging error
        }

        return sqlsrv_rows_affected($stmt) > 0;
    }
}
?>
