<?php
include_once('Model.php');

class VerifAdminModel 
{
    protected $db;
    protected $table = 'TB_Verifikasi';

    public function __construct()
    {
        include('../lib/Connection.php');
        $this->db = $db;
    }

    // Get detailed verification data with joins
    public function getDetailVerifikasi()
    {
        $query = "SELECT v.IDVerifikasi,
                         v.StatusVerifikasi,
                         v.Catatan,
                         v.TanggalVerifikasi,
                         u.IDUpload,
                         u.Nama_file,
                         u.TanggalDibuat,
                         s.Jenis_Surat,
                         m.NIM,
                         m.Nama,
                         m.ProgramStudi,
                         a.NamaAdmin
                  FROM TB_Verifikasi v
                  JOIN TB_Upload u ON v.IDUpload = u.IDUpload
                  JOIN TB_Mahasiswa m ON u.NIM = m.NIM
                  JOIN TB_Surat s ON u.IDSurat = s.IDSurat
                  LEFT JOIN TB_Admin a ON v.IDAdmin = a.IDAdmin
                  ORDER BY v.TanggalVerifikasi DESC";

        $result = sqlsrv_query($this->db, $query);

        if ($result === false) {
            throw new Exception("Error executing query: " . print_r(sqlsrv_errors(), true));
        }

        $data = [];
        while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
            // Format dates if they are DateTime objects
            if ($row['TanggalVerifikasi'] instanceof DateTime) {
                $row['TanggalVerifikasi'] = $row['TanggalVerifikasi']->format('Y-m-d H:i:s');
            }
            if ($row['TanggalDibuat'] instanceof DateTime) {
                $row['TanggalDibuat'] = $row['TanggalDibuat']->format('Y-m-d H:i:s');
            }
            $data[] = $row;
        }

        return $data;
    }

    // Get verification data by ID
    public function getDataById($id)
    {
        $query = "SELECT v.*, u.Nama_file, s.Jenis_Surat, m.NIM, m.Nama
                  FROM {$this->table} v
                  JOIN TB_Upload u ON v.IDUpload = u.IDUpload
                  JOIN TB_Mahasiswa m ON u.NIM = m.NIM
                  JOIN TB_Surat s ON u.IDSurat = s.IDSurat
                  WHERE v.IDVerifikasi = ?";
        
        $params = [$id];
        $stmt = sqlsrv_query($this->db, $query, $params);
        
        if ($stmt === false) {
            throw new Exception("Error getting data: " . print_r(sqlsrv_errors(), true));
        }

        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        
        // Format dates if they exist
        if ($row && isset($row['TanggalVerifikasi']) && $row['TanggalVerifikasi'] instanceof DateTime) {
            $row['TanggalVerifikasi'] = $row['TanggalVerifikasi']->format('Y-m-d H:i:s');
        }

        return $row;
    }

    // Update verification status
    public function updateData($id, $data)
    {
        try {
            if (sqlsrv_begin_transaction($this->db) === false) {
                throw new Exception("Could not begin transaction");
            }

            $query = "UPDATE {$this->table} 
                     SET StatusVerifikasi = ?,
                         Catatan = ?,
                         TanggalVerifikasi = ?,
                         IDAdmin = ?
                     WHERE IDVerifikasi = ?";

            $params = [
                $data['StatusVerifikasi'],
                $data['Catatan'],
                $data['TanggalVerifikasi'],
                $data['IDAdmin'],
                $id
            ];

            $stmt = sqlsrv_query($this->db, $query, $params);
            
            if ($stmt === false) {
                throw new Exception("Error updating data: " . print_r(sqlsrv_errors(), true));
            }

            if (sqlsrv_commit($this->db) === false) {
                throw new Exception("Could not commit transaction");
            }

            return true;

        } catch (Exception $e) {
            if (sqlsrv_rollback($this->db) === false) {
                throw new Exception("Could not rollback transaction");
            }
            throw $e;
        }
    }

    // Check if all documents are verified for a student
    public function checkAllDocumentsVerified($nim)
    {
        $query = "SELECT COUNT(*) as total,
                         SUM(CASE WHEN v.StatusVerifikasi = 1 THEN 1 ELSE 0 END) as verified
                  FROM TB_Upload u
                  JOIN {$this->table} v ON u.IDUpload = v.IDUpload
                  WHERE u.NIM = ?";

        $params = [$nim];
        $stmt = sqlsrv_query($this->db, $query, $params);

        if ($stmt === false) {
            throw new Exception("Error checking verification: " . print_r(sqlsrv_errors(), true));
        }

        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        return $row['total'] > 0 && $row['total'] == $row['verified'];
    }
}
?>