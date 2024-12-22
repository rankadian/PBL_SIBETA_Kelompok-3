<?php
include_once('Model.php');

class UploadModel extends Model
{
    protected $db;
    protected $table = 'TB_PengajuanSurat';

    public function __construct()
    {
        include('../lib/Connection.php');
        $this->db = $db;
    }

    // Method untuk menambahkan data pengajuan surat
    public function insertData($data)
    {
        // Mulai transaksi
        sqlsrv_begin_transaction($this->db);

        try {
            // Query untuk memasukkan data ke TB_Surat
            $sqlSurat = "INSERT INTO TB_Surat (NamaSurat, FilePath, TanggalUpload) 
                        VALUES (?, ?, ?)";
            $paramsSurat = [
                $data['NamaSurat'],          // Nama surat
                $data['FilePath'],           // Lokasi file
                $data['TanggalUpload'],      // Tanggal upload
            ];

            $stmtSurat = sqlsrv_query($this->db, $sqlSurat, $paramsSurat);
            if ($stmtSurat === false) {
                throw new Exception('Error inserting into TB_Surat: ' . print_r(sqlsrv_errors(), true));
            }

            // Ambil SuratID yang baru saja di-insert
            $queryLastSuratID = "SELECT SCOPE_IDENTITY() AS SuratID";
            $stmtLastSuratID = sqlsrv_query($this->db, $queryLastSuratID);
            if ($stmtLastSuratID === false) {
                throw new Exception('Error retrieving SuratID: ' . print_r(sqlsrv_errors(), true));
            }

            $row = sqlsrv_fetch_array($stmtLastSuratID, SQLSRV_FETCH_ASSOC);
            $suratID = $row['SuratID'];

            // Query untuk memasukkan data ke TB_PengajuanSurat
            $sqlPengajuan = "INSERT INTO TB_PengajuanSurat 
                            (NIM, SuratID, StatusPengajuan, TanggalPengajuan, FilePath, CatatanVerifikasi) 
                            VALUES (?, ?, ?, ?, ?, ?)";
            $paramsPengajuan = [
                $data['NIM'],                // NIM mahasiswa
                $suratID,                    // SuratID dari langkah sebelumnya
                $data['StatusPengajuan'],    // Status pengajuan
                $data['TanggalPengajuan'],   // Tanggal pengajuan
                $data['FilePath'],           // Lokasi file
                $data['CatatanVerifikasi'],  // Catatan verifikasi
            ];

            $stmtPengajuan = sqlsrv_query($this->db, $sqlPengajuan, $paramsPengajuan);
            if ($stmtPengajuan === false) {
                throw new Exception('Error inserting into TB_PengajuanSurat: ' . print_r(sqlsrv_errors(), true));
            }

            // Commit transaksi jika semua berhasil
            sqlsrv_commit($this->db);
            return [
                'status' => true,
                'message' => 'Data berhasil disimpan.'
            ];
        } catch (Exception $e) {
            // Rollback transaksi jika ada error
            sqlsrv_rollback($this->db);
            return [
                'status' => false,
                'message' => 'Transaction failed: ' . $e->getMessage()
            ];
        }
    }


    // Method untuk mengambil semua data pengajuan surat
    public function getData()
    {
        $query = sqlsrv_query($this->db, "SELECT * FROM {$this->table}");
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
            "SELECT * FROM {$this->table} WHERE PengajuanID = ?",
            [$id]
        );
        return sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
    }

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
?>
