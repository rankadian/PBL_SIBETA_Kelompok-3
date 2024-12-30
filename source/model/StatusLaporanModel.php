<?php
include_once('Model.php');

class StatusLaporanModel 
{
    protected $db;
    protected $table = 'TB_Verifikasi';

    public function __construct()
    {
        include('../lib/Connection.php');
        $this->db = $db;
    }

    // Mengambil status laporan berdasarkan NIM
    // public function getStatusByNIM($nim)
    // {
    //     $query = "SELECT v.IDVerifikasi,
    //                      v.StatusVerifikasi,
    //                      v.Catatan,
    //                      v.TanggalVerifikasi,
    //                      u.IDUpload,
    //                      u.Nama_file,
    //                      u.TanggalDibuat,
    //                      s.Jenis_Surat,
    //                      m.NIM,
    //                      m.Nama,
    //                      m.ProgramStudi,
    //                      a.NamaAdmin
    //               FROM TB_Verifikasi v
    //               JOIN TB_Upload u ON v.IDUpload = u.IDUpload
    //               JOIN TB_Mahasiswa m ON u.NIM = m.NIM
    //               JOIN TB_Surat s ON u.IDSurat = s.IDSurat
    //               JOIN TB_Admin a ON v.IDAdmin = a.IDAdmin
    //               WHERE m.NIM = ?
    //               ORDER BY v.TanggalVerifikasi DESC";

    //     $params = array($nim);
    //     $stmt = sqlsrv_query($this->db, $query, $params);
        
    //     if ($stmt === false) {
    //         throw new Exception("Error getting status: " . print_r(sqlsrv_errors(), true));
    //     }

    //     $data = [];
    //     while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    //         // Format tanggal
    //         if ($row['TanggalVerifikasi'] instanceof DateTime) {
    //             $row['TanggalVerifikasi'] = $row['TanggalVerifikasi']->format('Y-m-d');
    //         }
    //         if ($row['TanggalDibuat'] instanceof DateTime) {
    //             $row['TanggalDibuat'] = $row['TanggalDibuat']->format('Y-m-d');
    //         }
            
    //         // Format status
    //         $row['StatusVerifikasi'] = $row['StatusVerifikasi'] ? 'Disetujui' : 'Ditolak';
            
    //         $data[] = $row;
    //     }
    //     return $data;
    // }

    // Mengambil status laporan berdasarkan NIM menggunakan view
    public function getStatusByNIM($nim)
    {
        // Query Pemanggilan VIEW V_StatusLaporan
        $query = "SELECT IDVerifikasi,
                        StatusVerifikasi,
                        Catatan,
                        TanggalVerifikasi,
                        IDUpload,
                        Nama_file,
                        TanggalDibuat,
                        Jenis_Surat,
                        NIM,
                        Nama,
                        ProgramStudi,
                        NamaAdmin
                FROM V_StatusLaporan
                WHERE NIM = ?
                ORDER BY TanggalVerifikasi DESC";

        $params = array($nim);
        $stmt = sqlsrv_query($this->db, $query, $params);

        if ($stmt === false) {
            throw new Exception("Error getting status: " . print_r(sqlsrv_errors(), true));
        }

        $data = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            // Format tanggal
            if ($row['TanggalVerifikasi'] instanceof DateTime) {
                $row['TanggalVerifikasi'] = $row['TanggalVerifikasi']->format('Y-m-d');
            }
            if ($row['TanggalDibuat'] instanceof DateTime) {
                $row['TanggalDibuat'] = $row['TanggalDibuat']->format('Y-m-d');
            }

            // Format status
            $row['StatusVerifikasi'] = $row['StatusVerifikasi'] ? 'Disetujui' : 'Ditolak';

            $data[] = $row;
        }
        return $data;
    }

    // Mengecek apakah semua dokumen sudah diverifikasi dan disetujui
    public function checkAllDocumentsVerified($nim)
    {
        $query = "SELECT s.Jenis_Surat,
                         CASE 
                             WHEN v.StatusVerifikasi IS NULL THEN 'Belum Upload'
                             WHEN v.StatusVerifikasi = 1 THEN 'Disetujui'
                             ELSE 'Ditolak'
                         END as Status
                  FROM TB_Surat s
                  LEFT JOIN (
                      SELECT u.IDSurat, v.StatusVerifikasi
                      FROM TB_Upload u
                      LEFT JOIN TB_Verifikasi v ON u.IDUpload = v.IDUpload
                      WHERE u.NIM = ?
                  ) v ON s.IDSurat = v.IDSurat";

        $params = array($nim);
        $stmt = sqlsrv_query($this->db, $query, $params);
        
        if ($stmt === false) {
            throw new Exception("Error checking verification: " . print_r(sqlsrv_errors(), true));
        }

        $status = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $status[$row['Jenis_Surat']] = $row['Status'];
        }
        return $status;
    }

    // Mengecek apakah mahasiswa bisa download surat bebas tanggungan
    public function canDownloadBebasTanggungan($nim)
    {
        $status = $this->checkAllDocumentsVerified($nim);
        return !in_array('Belum Upload', $status) && !in_array('Ditolak', $status);
    }
}
?>