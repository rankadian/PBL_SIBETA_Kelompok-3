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


    public function getDataById($id)
    {
        try {
            // Menggunakan view untuk mengambil data berdasarkan IDVerifikasi
            $query = "SELECT * 
                    FROM VW_VerifikasiData
                    WHERE IDVerifikasi = ?";

            $params = [$id];
            $stmt = sqlsrv_query($this->db, $query, $params);

            if ($stmt === false) {
                throw new Exception("Error getting data: " . print_r(sqlsrv_errors(), true));
            }

            $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
            
            // Format tanggal jika ada
            if ($row && isset($row['TanggalVerifikasi']) && $row['TanggalVerifikasi'] instanceof DateTime) {
                $row['TanggalVerifikasi'] = $row['TanggalVerifikasi']->format('Y-m-d H:i:s');
            }

            return $row;
        } catch (Exception $e) {
            error_log("Error in getDataById: " . $e->getMessage());
            return false;
        }
    }

    public function updateData($id, $data)
    {
        try {
            // Query untuk memanggil stored procedure
            $query = "EXEC SP_UpdateVerifikasiData ?, ?, ?, ?, ?";

            // Menyiapkan parameter yang akan diteruskan ke stored procedure
            $params = [
                $id,                       // IDVerifikasi
                $data['StatusVerifikasi'],  // StatusVerifikasi
                $data['Catatan'],           // Catatan
                $data['TanggalVerifikasi'], // TanggalVerifikasi
                $data['IDAdmin']            // IDAdmin
            ];

            // Menyiapkan dan mengeksekusi query stored procedure
            $stmt = sqlsrv_prepare($this->db, $query, $params);

            // Mengeksekusi stored procedure
            if (sqlsrv_execute($stmt) === false) {
                throw new Exception("Error executing stored procedure: " . print_r(sqlsrv_errors(), true));
            }

            return true;
        } catch (Exception $e) {
            // Menangani error dan menulis log jika terjadi kegagalan
            error_log("Error in updateData: " . $e->getMessage());
            return false;
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