<?php
include_once('Model.php');

class TanggunganModel  
{
    protected $db;
    protected $table = 'TB_Upload';

    public function __construct()
    {
        include('../lib/Connection.php');
        $this->db = $db;
    }

    // Mengambil daftar tanggungan berdasarkan NIM
    public function getTanggunganByNIM($nim)
    {
        $query = "SELECT u.IDUpload,
                         u.Nama_file,
                         u.TanggalDibuat,
                         m.NIM,
                         m.Nama,
                         m.ProgramStudi,
                         s.Jenis_Surat,
                         v.StatusVerifikasi,
                         v.Catatan,
                         v.TanggalVerifikasi
                  FROM TB_Upload u
                  JOIN TB_Mahasiswa m ON u.NIM = m.NIM
                  JOIN TB_Surat s ON u.IDSurat = s.IDSurat
                  LEFT JOIN TB_Verifikasi v ON u.IDUpload = v.IDUpload
                  WHERE m.NIM = ?
                  ORDER BY u.TanggalDibuat DESC";

        $params = array($nim);
        $stmt = sqlsrv_query($this->db, $query, $params);
        
        if ($stmt === false) {
            throw new Exception("Error getting tanggungan: " . print_r(sqlsrv_errors(), true));
        }

        $data = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            // Format tanggal
            if ($row['TanggalDibuat'] instanceof DateTime) {
                $row['TanggalDibuat'] = $row['TanggalDibuat']->format('Y-m-d');
            }
            if ($row['TanggalVerifikasi'] instanceof DateTime) {
                $row['TanggalVerifikasi'] = $row['TanggalVerifikasi']->format('Y-m-d');
            }
            
            // Format status
            $row['StatusVerifikasi'] = isset($row['StatusVerifikasi']) ? 
                ($row['StatusVerifikasi'] ? 'Diverifikasi' : 'Ditolak') : 
                'Menunggu';
            
            $data[] = $row;
        }
        return $data;
    }

    // Mengecek apakah mahasiswa sudah mengupload semua dokumen yang diperlukan
    public function checkAllDocumentsSubmitted($nim)
    {
        $query = "SELECT s.Jenis_Surat,
                         CASE WHEN u.IDUpload IS NULL THEN 0 ELSE 1 END as IsSubmitted
                  FROM TB_Surat s
                  LEFT JOIN (
                      SELECT IDSurat, IDUpload 
                      FROM TB_Upload 
                      WHERE NIM = ?
                  ) u ON s.IDSurat = u.IDSurat";

        $params = array($nim);
        $stmt = sqlsrv_query($this->db, $query, $params);
        
        if ($stmt === false) {
            throw new Exception("Error checking documents: " . print_r(sqlsrv_errors(), true));
        }

        $documents = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $documents[$row['Jenis_Surat']] = $row['IsSubmitted'];
        }
        return $documents;
    }
}
?>