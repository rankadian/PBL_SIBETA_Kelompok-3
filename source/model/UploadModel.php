<?php
include_once('Model.php');

class UploadModel extends Model
{
    protected $db;
    protected $table = 'TB_Upload';

    public function __construct()
    {
        include('../lib/Connection.php');
        $this->db = $db;
    }

    public function beginTransaction()
    {
        return sqlsrv_begin_transaction($this->db);
    }

    public function commit()
    {
        return sqlsrv_commit($this->db);
    }

    public function rollback()
    {
        return sqlsrv_rollback($this->db);
    }

    // Implementasi method abstract
    public function insertData($data)
    {
        try {
            // Query SQL untuk menyisipkan data
            $query = "INSERT INTO TB_Upload (Nama_file, TanggalDibuat, NIM, IDSurat) VALUES (?, ?, ?, ?)";
        
            // Menyiapkan query dengan parameter
            $stmt = sqlsrv_prepare($this->db, $query, array(
                $data['Nama_file'],
                $data['TanggalDibuat'],
                $data['NIM'],
                $data['IDSurat']
            ));
        
            // Mengeksekusi query yang sudah disiapkan
            if (sqlsrv_execute($stmt)) {
                return true;
            }
            return false;
        } catch (Exception $e) {
            error_log("Error in insertData: " . $e->getMessage());
            return false;
        }
    }

    public function getData()
    {
        try {
            $query = "SELECT 
                        u.IDUpload,
                        u.Nama_file,
                        u.TanggalDibuat,
                        s.Jenis_Surat,
                        m.NIM,
                        m.Nama as NamaMahasiswa,
                        v.StatusVerifikasi,
                        v.TanggalVerifikasi,
                        v.Catatan
                    FROM TB_Upload u
                    INNER JOIN TB_Surat s ON u.IDSurat = s.IDSurat
                    INNER JOIN TB_Mahasiswa m ON u.NIM = m.NIM
                    LEFT JOIN TB_Verifikasi v ON u.IDUpload = v.IDUpload
                    ORDER BY u.TanggalDibuat DESC";

            $stmt = sqlsrv_query($this->db, $query);
            
            if ($stmt === false) {
                throw new Exception("Error getting data: " . print_r(sqlsrv_errors(), true));
            }

            $result = array();
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                if (isset($row['TanggalDibuat']) && $row['TanggalDibuat'] instanceof DateTime) {
                    $row['TanggalDibuat'] = $row['TanggalDibuat']->format('Y-m-d');
                }
                if (isset($row['TanggalVerifikasi']) && $row['TanggalVerifikasi'] instanceof DateTime) {
                    $row['TanggalVerifikasi'] = $row['TanggalVerifikasi']->format('Y-m-d');
                }
                $result[] = $row;
            }

            return $result;
        } catch (Exception $e) {
            error_log("Error in getData: " . $e->getMessage());
            return false;
        }
    }

    public function getDataById($id)
    {
        try {
            $query = "SELECT 
                        u.IDUpload,
                        u.Nama_file,
                        u.TanggalDibuat,
                        s.Jenis_Surat,
                        v.StatusVerifikasi,
                        v.TanggalVerifikasi,
                        v.Catatan
                    FROM TB_Upload u
                    INNER JOIN TB_Surat s ON u.IDSurat = s.IDSurat
                    LEFT JOIN TB_Verifikasi v ON u.IDUpload = v.IDUpload
                    WHERE u.IDUpload = ?";

            $stmt = sqlsrv_query($this->db, $query, array($id));
            
            if ($stmt === false) {
                throw new Exception("Error getting data by ID: " . print_r(sqlsrv_errors(), true));
            }

            if ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                if (isset($row['TanggalDibuat']) && $row['TanggalDibuat'] instanceof DateTime) {
                    $row['TanggalDibuat'] = $row['TanggalDibuat']->format('Y-m-d');
                }
                if (isset($row['TanggalVerifikasi']) && $row['TanggalVerifikasi'] instanceof DateTime) {
                    $row['TanggalVerifikasi'] = $row['TanggalVerifikasi']->format('Y-m-d');
                }
                return $row;
            }
            return false;
        } catch (Exception $e) {
            error_log("Error in getDataById: " . $e->getMessage());
            return false;
        }
    }

    public function updateData($id, $data)
    {
        try {
            $query = "UPDATE TB_Upload 
                     SET Nama_file = ?, 
                         TanggalDibuat = ?, 
                         NIM = ?, 
                         IDSurat = ?
                     WHERE IDUpload = ?";

            $stmt = sqlsrv_prepare($this->db, $query, array(
                $data['Nama_file'],
                $data['TanggalDibuat'],
                $data['NIM'],
                $data['IDSurat'],
                $id
            ));

            return sqlsrv_execute($stmt);
        } catch (Exception $e) {
            error_log("Error in updateData: " . $e->getMessage());
            return false;
        }
    }

    public function deleteData($id)
    {
        try {
            // Delete verifikasi first due to foreign key constraint
            $queryVerif = "DELETE FROM TB_Verifikasi WHERE IDUpload = ?";
            $stmtVerif = sqlsrv_query($this->db, $queryVerif, array($id));
            
            if ($stmtVerif === false) {
                throw new Exception("Error deleting verification data");
            }

            // Then delete upload
            $queryUpload = "DELETE FROM TB_Upload WHERE IDUpload = ?";
            $stmtUpload = sqlsrv_query($this->db, $queryUpload, array($id));
            
            if ($stmtUpload === false) {
                throw new Exception("Error deleting upload data");
            }

            return true;
        } catch (Exception $e) {
            error_log("Error in deleteData: " . $e->getMessage());
            return false;
        }
    }

    // Method tambahan untuk upload file
    public function save($data)
    {
        try {
            // Verify that NIM exists in TB_Mahasiswa
            $queryCheckNIM = "SELECT NIM FROM TB_Mahasiswa WHERE NIM = ?";
            $stmtCheckNIM = sqlsrv_query($this->db, $queryCheckNIM, array($data['NIM']));
            
            if ($stmtCheckNIM === false) {
                throw new Exception("Error checking NIM: " . print_r(sqlsrv_errors(), true));
            }
            
            if (!sqlsrv_fetch($stmtCheckNIM)) {
                throw new Exception("NIM tidak ditemukan: " . $data['NIM']);
            }

            // Verify that IDSurat exists
            $queryCheckSurat = "SELECT IDSurat FROM TB_Surat WHERE IDSurat = ?";
            $stmtCheckSurat = sqlsrv_query($this->db, $queryCheckSurat, array($data['IDSurat']));
            
            if ($stmtCheckSurat === false) {
                throw new Exception("Error checking IDSurat: " . print_r(sqlsrv_errors(), true));
            }
            
            if (!sqlsrv_fetch($stmtCheckSurat)) {
                throw new Exception("IDSurat tidak valid: " . $data['IDSurat']);
            }

            // Insert into TB_Upload
            $queryUpload = "INSERT INTO TB_Upload (Nama_file, TanggalDibuat, NIM, IDSurat) 
                           VALUES (?, ?, ?, ?);
                           SELECT SCOPE_IDENTITY() AS IDUpload;";

            $params = array(
                $data['Nama_file'],
                $data['TanggalDibuat'],
                $data['NIM'],
                $data['IDSurat']
            );

            $stmtUpload = sqlsrv_query($this->db, $queryUpload, $params);

            if ($stmtUpload === false) {
                throw new Exception("Error inserting into TB_Upload: " . print_r(sqlsrv_errors(), true));
            }

            // Get the inserted ID
            if (!sqlsrv_next_result($stmtUpload)) {
                throw new Exception("Error getting IDUpload result: " . print_r(sqlsrv_errors(), true));
            }

            if (!sqlsrv_fetch($stmtUpload)) {
                throw new Exception("Error fetching IDUpload row: " . print_r(sqlsrv_errors(), true));
            }

            $idUpload = sqlsrv_get_field($stmtUpload, 0);
            if ($idUpload === false) {
                throw new Exception("Error getting IDUpload value: " . print_r(sqlsrv_errors(), true));
            }

            // Verify that Admin exists
            $queryCheckAdmin = "SELECT IDAdmin FROM TB_Admin WHERE IDAdmin = 110";
            $stmtCheckAdmin = sqlsrv_query($this->db, $queryCheckAdmin);
            
            if ($stmtCheckAdmin === false) {
                throw new Exception("Error checking Admin: " . print_r(sqlsrv_errors(), true));
            }
            


            // Insert into TB_Verifikasi with initial status
            $queryVerif = "INSERT INTO TB_Verifikasi (IDUpload, IDAdmin, TanggalVerifikasi, StatusVerifikasi, Catatan) 
                          VALUES (?, 110, GETDATE(), 0, NULL)";

            $stmtVerif = sqlsrv_query($this->db, $queryVerif, array($idUpload));
            
            if ($stmtVerif === false) {
                throw new Exception("Error inserting into TB_Verifikasi: " . print_r(sqlsrv_errors(), true));
            }

            return true;

        } catch (Exception $e) {
            error_log("Upload Error: " . $e->getMessage());
            throw $e; // Re-throw exception so transaction can be rolled back in UploadAction
        }
    }

    public function getDataByNIM($nim)
    {
        try {
            $query = "SELECT 
                        u.IDUpload,
                        u.Nama_file,
                        u.TanggalDibuat,
                        s.Jenis_Surat,
                        v.StatusVerifikasi,
                        v.TanggalVerifikasi,
                        v.Catatan
                    FROM TB_Upload u
                    INNER JOIN TB_Surat s ON u.IDSurat = s.IDSurat
                    LEFT JOIN TB_Verifikasi v ON u.IDUpload = v.IDUpload
                    WHERE u.NIM = ?
                    ORDER BY u.TanggalDibuat DESC";

            $stmt = sqlsrv_query($this->db, $query, array($nim));
            
            if ($stmt === false) {
                throw new Exception("Error getting data by NIM: " . print_r(sqlsrv_errors(), true));
            }

            $result = array();
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                if (isset($row['TanggalDibuat']) && $row['TanggalDibuat'] instanceof DateTime) {
                    $row['TanggalDibuat'] = $row['TanggalDibuat']->format('Y-m-d');
                }
                if (isset($row['TanggalVerifikasi']) && $row['TanggalVerifikasi'] instanceof DateTime) {
                    $row['TanggalVerifikasi'] = $row['TanggalVerifikasi']->format('Y-m-d');
                }
                $result[] = $row;
            }

            return $result;
        } catch (Exception $e) {
            error_log("Error in getDataByNIM: " . $e->getMessage());
            return false;
        }
    }
}