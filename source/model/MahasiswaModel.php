<?php
include('Model.php');

class MahasiswaModel extends Model
{
    protected $db;
    protected $table = 'TB_MAHASISWA'; // Changed table name to m_mahasiswa
    protected $driver;

    public function __construct()
    {
        include_once('../lib/Connection.php');
        $this->db = $db;
        $this->driver = $use_driver;
    }

    // Insert new Mahasiswa data
    public function insertData($data)
    {
        if ($this->driver == 'mysql') {
            $query = $this->db->prepare("INSERT INTO {$this->table} (mahasiswa_nim, mahasiswa_nama, mahasiswa_angkatan) VALUES (?, ?, ?)");
            $query->bind_param('sss', $data['mahasiswa_nim'], $data['mahasiswa_nama'], $data['mahasiswa_angkatan']);
            $query->execute();
        } else {
            sqlsrv_query($this->db, "INSERT INTO {$this->table} (mahasiswa_nim, mahasiswa_nama, mahasiswa_angkatan) VALUES (?, ?, ?)", [
                $data['mahasiswa_nim'], 
                $data['mahasiswa_nama'], 
                $data['mahasiswa_angkatan']
            ]);
        }
    }

    // Get all Mahasiswa data
    public function getData()
    {
        if ($this->driver == 'mysql') {
            return $this->db->query("SELECT * FROM {$this->table}")->fetch_all(MYSQLI_ASSOC);
        } else {
            $query = sqlsrv_query($this->db, "SELECT * FROM {$this->table}");
            $data = [];
            while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        }
    }

    // Get Mahasiswa data by ID
    public function getDataById($id)
    {
        if ($this->driver == 'mysql') {
            $query = $this->db->prepare("SELECT * FROM {$this->table} WHERE mahasiswa_id = ?");
            $query->bind_param('i', $id);
            $query->execute();
            return $query->get_result()->fetch_assoc();
        } else {
            $query = sqlsrv_query($this->db, "SELECT * FROM {$this->table} WHERE mahasiswa_id = ?", [$id]);
            return sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
        }
    }

    // Update Mahasiswa data
    public function updateData($id, $data)
    {
        if ($this->driver == 'mysql') {
            $query = $this->db->prepare("UPDATE {$this->table} SET mahasiswa_nim = ?, mahasiswa_nama = ?, mahasiswa_angkatan = ? WHERE mahasiswa_id = ?");
            $query->bind_param('sssi', $data['mahasiswa_nim'], $data['mahasiswa_nama'], $data['mahasiswa_angkatan'], $id);
            $query->execute();
        } else {
            sqlsrv_query($this->db, "UPDATE {$this->table} SET mahasiswa_nim = ?, mahasiswa_nama = ?, mahasiswa_angkatan = ? WHERE mahasiswa_id = ?", [
                $data['mahasiswa_nim'], 
                $data['mahasiswa_nama'], 
                $data['mahasiswa_angkatan'], 
                $id
            ]);
        }
    }

    // Delete Mahasiswa data by ID
    public function deleteData($id)
    {
        if ($this->driver == 'mysql') {
            $query = $this->db->prepare("DELETE FROM {$this->table} WHERE mahasiswa_id = ?");
            $query->bind_param('i', $id);
            $query->execute();
        } else {
            sqlsrv_query($this->db, "DELETE FROM {$this->table} WHERE mahasiswa_id = ?", [$id]);
        }
    }
}
