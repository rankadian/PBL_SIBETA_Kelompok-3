<?php
include('Model.php');

class verifikasiAdm extends Model
{
    protected $db;
    protected $table = 'TB_Mahasiswa';
    protected $driver;

    public function __construct()
    {
        include_once('../lib/Connection.php');
        $this->db = $db;
        $this->driver = $use_driver;
    }

    // Method to insert data into the table
    public function insertData($data)
    {
        $query = "INSERT INTO {$this->table} (NIM, Nama, StatusTanggungan, SuratBebasKompen, SuratValidasiPKL) 
                  VALUES (?, ?, ?, ?, ?)";
        sqlsrv_query($this->db, $query, [
            $data['NIM'], $data['Nama'], $data['StatusTanggungan'], $data['SuratBebasKompen'], $data['SuratValidasiPKL']
        ]);
    }

    // Method to get all data
    public function getData()
    {
        $query = sqlsrv_query($this->db, "SELECT * FROM {$this->table}");
        $data = [];
        while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }

    // Method to update data based on NIM
    public function updateData($id, $data)
    {
        $query = "UPDATE {$this->table} 
                  SET StatusTanggungan = ?, SuratBebasKompen = ?, SuratValidasiPKL = ? 
                  WHERE NIM = ?";
        sqlsrv_query($this->db, $query, [
            $data['StatusTanggungan'], $data['SuratBebasKompen'], $data['SuratValidasiPKL'], $id
        ]);
    }

    // Method to get data by NIM (Get a specific record by its NIM)
    public function getDataById($id)
    {
        $query = sqlsrv_query($this->db, "SELECT * FROM {$this->table} WHERE NIM = ?", [$id]);
        if ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
            return $row;
        } else {
            return null; // Return null if no record is found
        }
    }

    // Method to delete data based on NIM
    public function deleteData($id)
    {
        $query = "DELETE FROM {$this->table} WHERE NIM = ?";
        $result = sqlsrv_query($this->db, $query, [$id]);
        
        // Check if the query was successful
        if ($result === false) {
            return false; // Return false if delete fails
        }
        return true; // Return true if delete is successful
    }
}
?>
