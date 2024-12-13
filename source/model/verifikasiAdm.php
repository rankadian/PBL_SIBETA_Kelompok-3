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
public function insertData($data)
    {
      // eksekusi query untuk menyimpan ke database
    sqlsrv_query($this->db, "insert into {$this->table} (NIM,IDUser,Nama,StatusTanggungan) values(?,?,?,?)", 
array($data['NIM'], $data['IDUser'],$data['Nama'],$data['StatusTanggungan']));
        
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
        if ($this->driver == 'mysql') {
            // query untuk mengambil data berdasarkan id
            $query = $this->db->prepare("select * from {$this->table} where NIM =
?");
            // binding parameter ke query "i" berarti integer. Biar tidak kena SQL Injection
            $query->bind_param('i', $id);
            // eksekusi query
            $query->execute();
            // ambil hasil query
            return $query->get_result()->fetch_assoc();
        } else {
            // query untuk mengambil data berdasarkan id
            $query = sqlsrv_query($this->db, "select * from {$this->table} where NIM= ?", [$id]);
            // ambil hasil query
            return sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
        }
    }
    public function updateData($id, $data)
    {
        if ($this->driver == 'mysql') {
            // query untuk update data
            $query = $this->db->prepare("update {$this->table} set kategori_kode = ?,kategori_nama = ? where kategori_id = ?");
            // binding parameter ke query
            $query->bind_param('ssi', $data['kategori_kode'], $data['kategori_nama'], $id);
            // eksekusi query
            $query->execute();
        } else {
            // query untuk update data
            sqlsrv_query($this->db, "update {$this->table} set NIM = ?, Nama = ?, IDUser = ?,StatusTanggungan = ? where NIM = ?", [
                $data['NIM'],
                $data['Nama'],
                $data['IDUser'],
                $data ['StatusTanggungan'],
                $id
            ]);
        }
    }
    public function deleteData($id)
    {
        if ($this->driver == 'mysql') {
            // query untuk delete data
            $query = $this->db->prepare("delete from {$this->table} where kategori_id = ?");
            // binding parameter ke query
            $query->bind_param('i', $id);
            // eksekusi query
            $query->execute();
        } else {
            // query untuk delete data
            sqlsrv_query(
                $this->db,
                "delete from {$this->table} where NIM = ?",
                [$id]
            );
        }
    }
}

?>