<?php
include('Model.php');

class VerifikasiMahasiswaModel extends Model
{
    protected $db;
    protected $table = 'TB_VERIFIKASI_ADMIN';

    public function __construct()
    {
        include_once('../lib/Connection.php');
        $this->db = $db; // pastikan $db adalah koneksi ke SQL Server
    }

    // Insert new data for verification
    public function insertData($data)
    {
        $query = "INSERT INTO {$this->table} 
                  (admin_email, mahasiswa_nim, id_tanggungan, status_validasi, tanggal_verifikasi) 
                  VALUES (?, ?, ?, ?, ?)";

        $params = [
            $data['admin_email'],
            $data['mahasiswa_nim'],
            $data['id_tanggungan'],
            $data['status_validasi'],
            $data['tanggal_verifikasi']
        ];

        // Execute query with parameters
        $stmt = sqlsrv_query($this->db, $query, $params);
        if (!$stmt) {
            die(print_r(sqlsrv_errors(), true));
        }
    }

    // Get all data for verification
    public function getData()
    {
        $query = "SELECT * FROM {$this->table}";
        $stmt = sqlsrv_query($this->db, $query);

        $data = [];
        if ($stmt) {
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $data[] = $row;
            }
        }
        return $data;
    }

    // Get data by ID for a specific verification entry
    public function getDataById($id)
    {
        $query = "SELECT * FROM {$this->table} WHERE id_verifikasi_admin = ?";
        $params = [$id];
        $stmt = sqlsrv_query($this->db, $query, $params);

        return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    }

    // Update data for verification
    public function updateData($id, $data)
    {
        $query = "UPDATE {$this->table} SET 
                  admin_email = ?, mahasiswa_nim = ?, id_tanggungan = ?, status_validasi = ?, tanggal_verifikasi = ? 
                  WHERE id_verifikasi_admin = ?";

        $params = [
            $data['admin_email'],
            $data['mahasiswa_nim'],
            $data['id_tanggungan'],
            $data['status_validasi'],
            $data['tanggal_verifikasi'],
            $id
        ];

        // Execute query with parameters
        $stmt = sqlsrv_query($this->db, $query, $params);
        if (!$stmt) {
            die(print_r(sqlsrv_errors(), true));
        }
    }

    // Delete data for a specific verification entry
    public function deleteData($id)
    {
        $query = "DELETE FROM {$this->table} WHERE id_verifikasi_admin = ?";
        $params = [$id];

        // Execute query with parameters
        $stmt = sqlsrv_query($this->db, $query, $params);
        if (!$stmt) {
            die(print_r(sqlsrv_errors(), true));
        }
    }

    // Update the verification status (for approve, pending, or rejected)
    public function updateStatus($id, $status)
    {
        $query = "UPDATE {$this->table} SET status_validasi = ? WHERE id_verifikasi_admin = ?";
        $params = [$status, $id];

        // Execute query with parameters
        $stmt = sqlsrv_query($this->db, $query, $params);
        if (!$stmt) {
            die(print_r(sqlsrv_errors(), true));
        }
    }
}
?>
