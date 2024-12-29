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
        // Query  Pemanggilan VIEW
        $query = "SELECT * FROM V_Tanggungan_Mahasiswa WHERE NIM = ? ORDER BY TanggalDibuat DESC;";

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

    //Mengecek apakah mahasiswa sudah mengupload semua dokumen yang diperlukan
    public function checkAllDocumentsSubmitted($nim)
    {
        // Query Pemanggilan VIEW
        $query = "SELECT Jenis_Surat, IsSubmitted 
                  FROM V_CheckAllDocumentsSubmitted 
                  WHERE NIM = ?";
    
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