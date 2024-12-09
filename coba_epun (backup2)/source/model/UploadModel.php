<?
// Di dalam file Model.php (sebagai contoh)
include_once('../lib/Connection.php');

class UploadModel
{
    protected $db;
    protected $table = 'TB_TANGGUNGAN';  // Ganti dengan nama tabel yang sesuai

    public function __construct()
    {
        // Gunakan koneksi yang sudah terdefinisi di Connection.php
        global $db;  // Mengakses koneksi dari Connection.php
        $this->db = $db;  // Menggunakan koneksi untuk query
    }

    public function insertData($data)
    {
        $query = "INSERT INTO {$this->table} (mahasiswa_nim, jenis_tanggungan, absensi_id, ukt_id, pkl_id, toeic_id, skkm_id, publikasi_id, status_validasi, tanggal_ajukan) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $params = [
            $data['mahasiswa_nim'],
            $data['jenis_tanggungan'],
            $data['absensi_id'],
            $data['ukt_id'],
            $data['pkl_id'],
            $data['toeic_id'],
            $data['skkm_id'],
            $data['publikasi_id'],
            $data['status_validasi'],
            $data['tanggal_ajukan']
        ];

        $stmt = sqlsrv_query($this->db, $query, $params);

        if (!$stmt) {
            die(print_r(sqlsrv_errors(), true));
        }
        return true;
    }
}
