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

    // Method untuk menambahkan data pengajuan surat
    public function insertData($data)
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
