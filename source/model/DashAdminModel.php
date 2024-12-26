<?php
include_once('Model.php');

class DashAdminModel extends Model
{
    protected $db;
    protected $table1 = 'TB_Upload';
    protected $table2 = 'TB_Verifikasi';

    public function __construct()
    {
        include('../lib/Connection.php');
        $this->db = $db;
    }

    // Implementasi insertData - Menambahkan data verifikasi
    public function insertData($data)
    {
  
    }

    // Implementasi getData - Mengambil semua data verifikasi
    public function getData()
    {

    }


    // Implementasi getDataById - Mengambil data verifikasi berdasarkan ID
    public function getDataById($id)
    {

    }

    // Implementasi updateData - Mengupdate status verifikasi
    public function updateData($id, $data)
    {

    }

    // Implementasi deleteData - Menghapus data verifikasi
    public function deleteData($id)
    {

    }
}
?>
