<?php
include_once('Model.php');

class VerifAdminModel extends Model
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
        try {
            // Begin transaction
            if (sqlsrv_begin_transaction($this->db) === false) {
                throw new Exception("Tidak dapat memulai transaksi");
            }

            // Check if data exists first
            $checkQuery = "SELECT COUNT(*) as count FROM {$this->table} WHERE IDVerifikasi = ?";
            $checkParams = [$id];
            $checkStmt = sqlsrv_query($this->db, $checkQuery, $checkParams);
            
            if ($checkStmt === false) {
                sqlsrv_rollback($this->db);
                throw new Exception(print_r(sqlsrv_errors(), true));
            }
            
            $row = sqlsrv_fetch_array($checkStmt, SQLSRV_FETCH_ASSOC);
            if ($row['count'] == 0) {
                sqlsrv_rollback($this->db);
                return false; // Data not found
            }

            // Delete query
            $query = "DELETE FROM {$this->table} WHERE IDVerifikasi = ?";
            $params = [$id];
            
            // Execute delete
            $stmt = sqlsrv_query($this->db, $query, $params);
            
            if ($stmt === false) {
                sqlsrv_rollback($this->db);
                throw new Exception(print_r(sqlsrv_errors(), true));
            }

            // Check affected rows
            $affected = sqlsrv_rows_affected($stmt);
            if ($affected === false || $affected === 0) {
                sqlsrv_rollback($this->db);
                return false;
            }

            // Commit transaction
            if (sqlsrv_commit($this->db) === false) {
                throw new Exception("Gagal melakukan commit transaksi");
            }

            return true;
        } catch (Exception $e) {
            // Ensure rollback on any error
            if (sqlsrv_rollback($this->db) === false) {
                throw new Exception("Gagal melakukan rollback: " . $e->getMessage());
            }
            throw $e;
        }
    }

    // Mengambil data verifikasi dengan detail mahasiswa dan surat
    public function getDetailVerifikasi()
    {
        $query = "SELECT v.IDVerifikasi,
                         v.TanggalVerifikasi,
                         v.StatusVerifikasi,
                         v.Catatan,
                         u.IDUpload,
                         u.Nama_file,
                         u.Jenis_Surat,
                         u.TanggalDibuat,
                         m.NIM,
                         m.Nama,
                         a.NamaAdmin
                  FROM TB_Verifikasi v
                  JOIN TB_Upload u ON v.IDUpload = u.IDUpload
                  JOIN TB_Mahasiswa m ON u.NIM = m.NIM
                  JOIN TB_Admin a ON v.IDAdmin = a.IDAdmin
                  ORDER BY v.TanggalVerifikasi DESC";
        
        $result = sqlsrv_query($this->db, $query);
        if ($result === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        $data = [];
        while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
            // Konversi status dari BIT ke boolean
            $row['StatusVerifikasi'] = $row['StatusVerifikasi'] ? true : false;
            
            // Format tanggal menggunakan CONVERT di SQL Server
            $row['TanggalVerifikasi'] = date_format($row['TanggalVerifikasi'], 'Y-m-d');
            $row['TanggalDibuat'] = date_format($row['TanggalDibuat'], 'Y-m-d');
            
            $data[] = $row;
        }
        return $data;
    }

    // Mengambil surat yang belum diverifikasi berdasarkan jenis surat
    public function getUnverifiedDocuments($jenisSurat = null)
    {
        $query = "SELECT 
                    u.IDUpload,
                    u.Nama_file,
                    u.Jenis_Surat,
                    u.TanggalDibuat,
                    m.NIM,
                    m.Nama,
                    m.ProgramStudi
                  FROM TB_Upload u
                  JOIN TB_Mahasiswa m ON u.NIM = m.NIM
                  WHERE u.IDUpload NOT IN (SELECT IDUpload FROM {$this->table})";
        
        if ($jenisSurat) {
            $query .= " AND u.Jenis_Surat = ?";
            $params = [$jenisSurat];
        } else {
            $params = [];
        }
        
        $result = sqlsrv_query($this->db, $query, $params);
        if ($result === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        $data = [];
        while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
            $row['TanggalDibuat'] = $row['TanggalDibuat']->format('Y-m-d');
            $data[] = $row;
        }
        return $data;
    }

    // Update status verifikasi
    public function updateVerifikasi($idVerifikasi, $status, $catatan)
    {
        $query = "UPDATE {$this->table} 
                  SET StatusVerifikasi = ?, 
                      Catatan = ?,
                      TanggalVerifikasi = GETDATE()
                  WHERE IDVerifikasi = ?";
        
        // Konversi status ke BIT
        $statusBit = $status ? 1 : 0;
        $params = [$statusBit, $catatan, $idVerifikasi];
        
        $stmt = sqlsrv_query($this->db, $query, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        
        return sqlsrv_rows_affected($stmt) > 0;
    }
}
