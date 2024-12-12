<?php
include_once('Model.php');
class UserModel extends Model
{
    protected $db;
    protected $table = 'TB_USER';
    protected $driver;
    public function __construct()
    {
        include('../lib/Connection.php');
        $this->db = $db;
        $this->driver = $use_driver;
    }
    public function insertData($data)
    {
       
            // eksekusi query untuk menyimpan ke database
            sqlsrv_query($this->db, "insert into {$this->table} (username, nama, level,password) values(?,?,?,?)", array(
                $data['username'],
                $data['nama'],
                $data['level'],
                password_hash($data['password'], PASSWORD_DEFAULT)
            ));
        
    }
    public function getData()
    {
        
            // query untuk mengambil data dari tabel
            $query = sqlsrv_query($this->db, "select * from {$this->table}");
            $data = [];
            while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        
    }
    public function getDataById($id)
    {

            // query untuk mengambil data berdasarkan id
            $query = sqlsrv_query($this->db, "select * from {$this->table} where user_id =?", [$id]);
            // ambil hasil query
            return sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
        
    }
    public function updateData($id, $data)
    {
            // query untuk update data
            sqlsrv_query($this->db, "update {$this->table} set username = ?, nama = ?, level
            = ?, password = ? where user_id = ?", [
                $data['username'],
                $data['nama'],
                $data['level'],
                password_hash($data['password'], PASSWORD_DEFAULT),
                $id
            ]);
    }
    public function deleteData($id)
    {
            // query untuk delete data
            sqlsrv_query($this->db, "delete from {$this->table} where user_id = ?", [$id]);
    }
    public function getSingleDataByKeyword($column, $keyword)
    {
            // query untuk mengambil data berdasarkan id
            $query = sqlsrv_query($this->db, "select * from {$this->table} where {$column} =
            ?", [$keyword]);
            // ambil hasil query
            return sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
    }
}