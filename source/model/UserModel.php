<?php
include('Model.php');

class UserModel extends Model
{
    protected $db;
    protected $table = 'TB_USER';

    public function __construct()
    {
        include('../lib/Connection.php');
        $this->db = $db;
    }

    public function insertData($data)
    {
        $query = "INSERT INTO {$this->table} (username, nama, level, password) VALUES (?, ?, ?, ?)";
        $params = [
            $data['username'],
            $data['nama'],
            $data['level'],
            password_hash($data['password'], PASSWORD_DEFAULT)
        ];

        $stmt = sqlsrv_prepare($this->db, $query, $params);
        if (!$stmt) {
            throw new Exception("Error preparing statement: " . print_r(sqlsrv_errors(), true));
        }

        sqlsrv_execute($stmt);
    }

    public function getData()
    {
        $query = "SELECT * FROM {$this->table}";
        $stmt = sqlsrv_query($this->db, $query);
        if (!$stmt) {
            throw new Exception("Query failed: " . print_r(sqlsrv_errors(), true));
        }

        $data = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $data[] = $row;
        }

        return $data;
    }

    public function getDataById($id)
    {
        $query = "SELECT * FROM {$this->table} WHERE id = ?";
        $params = [$id];
        $stmt = sqlsrv_query($this->db, $query, $params);

        if (!$stmt) {
            throw new Exception("Query failed: " . print_r(sqlsrv_errors(), true));
        }

        return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    }

    public function updateData($id, $data)
    {
        $query = "UPDATE {$this->table} SET username = ?, nama = ?, level = ?, password = ? WHERE user_id = ?";
        $params = [
            $data['username'],
            $data['nama'],
            $data['level'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $id
        ];

        $stmt = sqlsrv_query($this->db, $query, $params);
        if (!$stmt) {
            throw new Exception("Query failed: " . print_r(sqlsrv_errors(), true));
        }
    }

    public function deleteData($id)
    {
        $query = "DELETE FROM {$this->table} WHERE id = ?";
        $params = [$id];
        $stmt = sqlsrv_query($this->db, $query, $params);

        if (!$stmt) {
            throw new Exception("Query failed: " . print_r(sqlsrv_errors(), true));
        }
    }

    public function getSingleDataByKeyword($column, $keyword)
    {
        $validColumns = ['username', 'email', 'user_id'];  // Ensure valid column names
        if (!in_array($column, $validColumns)) {
            throw new Exception("Invalid column name.");
        }

        $query = "SELECT * FROM {$this->table} WHERE {$column} = ?";
        $params = [$keyword];
        $stmt = sqlsrv_query($this->db, $query, $params);

        if (!$stmt) {
            throw new Exception("Query failed: " . print_r(sqlsrv_errors(), true));
        }

        return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    }
}
