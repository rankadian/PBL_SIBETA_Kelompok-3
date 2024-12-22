<?php
include_once('Model.php');

class TanggunganModel extends Model
{
    protected $db;
    protected $table = 'TB_PengajuanSurat';

    public function __construct()
    {
        include('../lib/Connection.php');
        $this->db = $db;
    }

    public function insertData($data)
    {
        // Optional, tergantung kebutuhan.
    }

    public function getData()
    {
        $sql = "
            SELECT 
                ps.PengajuanID, 
                ps.NIM, 
                m.Nama AS NamaMahasiswa, 
                s.NamaSurat, 
                ps.StatusPengajuan, 
                ps.TanggalPengajuan
            FROM 
                {$this->table} ps
            LEFT JOIN 
                TB_Mahasiswa m ON ps.NIM = m.NIM
            LEFT JOIN 
                TB_Surat s ON ps.SuratID = s.SuratID
        ";
        $query = sqlsrv_query($this->db, $sql);

        // Fetch data dalam bentuk array
        $data = [];
        while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
            $data[] = $row;
        }

        return $data;
    }

    /**
     * Ambil data pengajuan surat berdasarkan NIM mahasiswa.
     */
    public function getDataById($nim)
    {
        $sql = "
            SELECT 
                ps.PengajuanID, 
                ps.NIM, 
                m.Nama AS NamaMahasiswa, 
                s.NamaSurat, 
                ps.StatusPengajuan, 
                ps.TanggalPengajuan
            FROM 
                {$this->table} ps
            LEFT JOIN 
                TB_Mahasiswa m ON ps.NIM = m.NIM
            LEFT JOIN 
                TB_Surat s ON ps.SuratID = s.SuratID
            WHERE 
                ps.NIM = ?
        ";
        $params = [$nim];
        $stmt = sqlsrv_prepare($this->db, $sql, $params);

        if (sqlsrv_execute($stmt)) {
            $data = [];
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } else {
            return false; // Handle error jika eksekusi gagal
        }
    }

    public function updateData($id, $data)
    {
        $sql = "UPDATE {$this->table} SET StatusPengajuan = ? WHERE PengajuanID = ?";
        $params = [ $id];
        $stmt = sqlsrv_query($this->db, $sql, $params);

        if ($stmt === false) {
            return false; // Handle error
        }
        return true;
    }

    public function deleteData($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE PengajuanID = ?";
        $params = [$id];
        $stmt = sqlsrv_query($this->db, $sql, $params);

        if ($stmt === false) {
            return false; // Handle error
        }
        return true;
    }
}
