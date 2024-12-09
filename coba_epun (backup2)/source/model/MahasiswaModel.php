<?php
include('Model.php');

class MahasiswaModel extends Model
{
    protected $db;
    protected $table_tanggungjawab = 'TB_MAHASISWA';
    protected $table_uploads = 'uploads_bukti';
    protected $driver;

    public function __construct()
    {
        include_once('../lib/Connection.php');
        $this->db = $db;
        $this->driver = $use_driver;
    }

    // Insert new data into a table (general method)
    public function insertData($data)
    {
        if ($this->driver == 'mysql') {
            $query = $this->db->prepare("INSERT INTO {$this->table_uploads} (mahasiswa_id, file_name, file_path, status) VALUES (?, ?, ?, ?)");
            $query->bind_param('isss', $data['mahasiswa_id'], $data['file_name'], $data['file_path'], $data['status']);
            $query->execute();
        } else {
            sqlsrv_query($this->db, "INSERT INTO {$this->table_uploads} (mahasiswa_id, file_name, file_path, status) VALUES (?, ?, ?, ?)", [
                $data['mahasiswa_id'],
                $data['file_name'],
                $data['file_path'],
                $data['status']
            ]);
        }
    }

    // Retrieve all data from a table (general method)
    public function getData()
    {
        if ($this->driver == 'mysql') {
            return $this->db->query("SELECT * FROM {$this->table_tanggungjawab}")->fetch_all(MYSQLI_ASSOC);
        } else {
            $query = sqlsrv_query($this->db, "SELECT * FROM {$this->table_tanggungjawab}");
            $data = [];
            while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        }
    }

    // Retrieve data by ID (general method)
    public function getDataById($id)
    {
        if ($this->driver == 'mysql') {
            $query = $this->db->prepare("SELECT * FROM {$this->table_tanggungjawab} WHERE id = ?");
            $query->bind_param('i', $id);
            $query->execute();
            return $query->get_result()->fetch_assoc();
        } else {
            $query = sqlsrv_query($this->db, "SELECT * FROM {$this->table_tanggungjawab} WHERE id = ?", [$id]);
            return sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
        }
    }

    // Update data by ID (general method)
    public function updateData($id, $data)
    {
        if ($this->driver == 'mysql') {
            $query = $this->db->prepare("UPDATE {$this->table_uploads} SET file_name = ?, file_path = ?, status = ? WHERE id = ?");
            $query->bind_param('sssi', $data['file_name'], $data['file_path'], $data['status'], $id);
            $query->execute();
        } else {
            sqlsrv_query($this->db, "UPDATE {$this->table_uploads} SET file_name = ?, file_path = ?, status = ? WHERE id = ?", [
                $data['file_name'],
                $data['file_path'],
                $data['status'],
                $id
            ]);
        }
    }

    // Delete data by ID (general method)
    public function deleteData($id)
    {
        if ($this->driver == 'mysql') {
            $query = $this->db->prepare("DELETE FROM {$this->table_uploads} WHERE id = ?");
            $query->bind_param('i', $id);
            $query->execute();
        } else {
            sqlsrv_query($this->db, "DELETE FROM {$this->table_uploads} WHERE id = ?", [$id]);
        }
    }

    // Fetch student responsibility data by semester
    public function getTanggungJawab($mahasiswa_id)
    {
        if ($this->driver == 'mysql') {
            return $this->db->query("SELECT * FROM {$this->table_tanggungjawab} WHERE mahasiswa_id = $mahasiswa_id")->fetch_all(MYSQLI_ASSOC);
        } else {
            $query = sqlsrv_query($this->db, "SELECT * FROM {$this->table_tanggungjawab} WHERE mahasiswa_id = ?", [$mahasiswa_id]);
            $data = [];
            while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        }
    }

    // Save file upload
    public function saveUpload($data)
    {
        $this->insertData($data);  // Delegate to insertData method
    }
}
