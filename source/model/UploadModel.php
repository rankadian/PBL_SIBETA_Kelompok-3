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

    public function insertData($data)
    {
        // Start transaction
        if (sqlsrv_begin_transaction($this->db) === false) {
            error_log("Failed to begin transaction");
            return false;
        }

        try {
            // Verify that NIM exists in TB_Mahasiswa
            $queryCheckNIM = "SELECT NIM FROM TB_Mahasiswa WHERE NIM = ?";
            $stmtCheckNIM = sqlsrv_query($this->db, $queryCheckNIM, array($data['NIM']));
            
            if ($stmtCheckNIM === false) {
                throw new Exception("Error checking NIM: " . print_r(sqlsrv_errors(), true));
            }
            
            if (!sqlsrv_fetch($stmtCheckNIM)) {
                throw new Exception("Invalid NIM: " . $data['NIM']);
            }

            // Insert into TB_Upload
            $queryUpload = "INSERT INTO TB_Upload (Nama_file, Jenis_Surat, TanggalDibuat, NIM) 
                           VALUES (?, ?, ?, ?);
                           SELECT CAST(SCOPE_IDENTITY() AS INT) AS IDUpload;";

            $stmtUpload = sqlsrv_query($this->db, $queryUpload, array(
                $data['Nama_file'],
                $data['Jenis_Surat'],
                $data['TanggalDibuat'],
                $data['NIM']
            ));

            if ($stmtUpload === false) {
                throw new Exception("Error inserting into TB_Upload: " . print_r(sqlsrv_errors(), true));
            }

            // Get the inserted ID
            if (!sqlsrv_next_result($stmtUpload)) {
                throw new Exception("Error getting to IDUpload result: " . print_r(sqlsrv_errors(), true));
            }

            if (!sqlsrv_fetch($stmtUpload)) {
                throw new Exception("Error fetching IDUpload row: " . print_r(sqlsrv_errors(), true));
            }

            $idUpload = sqlsrv_get_field($stmtUpload, 0);
            if ($idUpload === false) {
                throw new Exception("Error getting IDUpload value: " . print_r(sqlsrv_errors(), true));
            }

            error_log("Got IDUpload: " . $idUpload);

            // Verify that IDAdmin 1 exists
            $queryCheckAdmin = "SELECT IDAdmin FROM TB_Admin WHERE IDAdmin = 1";
            $stmtCheckAdmin = sqlsrv_query($this->db, $queryCheckAdmin);
            
            if ($stmtCheckAdmin === false) {
                throw new Exception("Error checking Admin: " . print_r(sqlsrv_errors(), true));
            }
            
            if (!sqlsrv_fetch($stmtCheckAdmin)) {
                throw new Exception("Admin with ID 1 does not exist");
            }

            // Insert into TB_Verifikasi
            $queryVerif = "INSERT INTO TB_Verifikasi (IDUpload, IDAdmin, TanggalVerifikasi, StatusVerifikasi, Catatan) 
                          VALUES (?, 1, GETDATE(), 0, NULL)";

            $stmtVerif = sqlsrv_query($this->db, $queryVerif, array($idUpload));
            
            if ($stmtVerif === false) {
                throw new Exception("Error inserting into TB_Verifikasi: " . print_r(sqlsrv_errors(), true));
            }

            // Commit transaction
            if (!sqlsrv_commit($this->db)) {
                throw new Exception("Error committing transaction: " . print_r(sqlsrv_errors(), true));
            }

            return true;

        } catch (Exception $e) {
            sqlsrv_rollback($this->db);
            error_log("Upload Error: " . $e->getMessage());
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Method untuk menambahkan data pengajuan surat
    public function insertData1($data)
    {
        // Query SQL untuk menyisipkan data
        $query = "INSERT INTO TB_Upload (Nama_file, Jenis_Surat, TanggalDibuat, NIM) VALUES (?, ?, ?, ?)";
    
        // Menyiapkan query dengan parameter
        $stmt = sqlsrv_prepare($this->db, $query, array(
            $data['Nama_file'], 
            $data['Jenis_Surat'], 
            $data['TanggalDibuat'], 
            $data['NIM']
        ));
    
        // Mengeksekusi query yang sudah disiapkan
        if (sqlsrv_execute($stmt)) {
            return true; // Berhasil
        } else {
            echo "Error executing query: ";
            print_r(sqlsrv_errors()); // Tampilkan error jika eksekusi gagal
            return false; // Gagal
        }
    }

    // Method untuk mengambil semua data pengajuan surat
    public function getData()
    {
        // Execute the query
        $query = sqlsrv_query($this->db, "SELECT * FROM {$this->table}");

        // Check if the query failed
        if ($query === false) {
            die("SQL query failed: " . print_r(sqlsrv_errors(), true));
        }

        // Fetch the data
        $data = [];
        while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
            $data[] = $row;
        }

        return $data;
    }

    // Method untuk mengambil data pengajuan berdasarkan ID
    public function getDataById($id)
    {
        $query = sqlsrv_query(
            $this->db,
            "SELECT NamaSurat, TanggalUpload FROM {$this->table} WHERE PengajuanID = ?",
            [$id]
        );
        return sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
    }

    public function getDataForSurat() {}

    // Method untuk memperbarui data pengajuan surat
    public function updateData($id, $data)
    {
        $sql = "UPDATE {$this->table} 
                SET NIM = ?, SuratID = ?, StatusPengajuan = ?, 
                    TanggalPengajuan = ?, FilePath = ?, CatatanVerifikasi = ? 
                WHERE PengajuanID = ?";

        $params = [
            $data['NIM'],
            $data['SuratID'],
            $data['StatusPengajuan'],
            $data['TanggalPengajuan'],
            $data['FilePath'],
            $data['CatatanVerifikasi'],
            $id
        ];

        $stmt = sqlsrv_query($this->db, $sql, $params);
        if (!$stmt) {
            die(print_r(sqlsrv_errors(), true)); // Debug jika eksekusi gagal
        }
    }

    // Method untuk menghapus data pengajuan surat
    public function deleteData($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE PengajuanID = ?";
        $params = [$id];

        $stmt = sqlsrv_query($this->db, $sql, $params);
        if (!$stmt) {
            die(print_r(sqlsrv_errors(), true)); // Debug jika eksekusi gagal
        }
    }
}
