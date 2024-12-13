<?php
include_once('Model.php');

class TanggunganModel extends Model
{
    protected $db;
    protected $table = 'TB_TANGGUNGAN';

    public function __construct()
    {
        include('../lib/Connection.php');
        $this->db = $db;
    }

    public function insertData($data)
    {
        sqlsrv_query(
            $this->db,
            "INSERT INTO {$this->table} (id_tanggungan, mahasiswa_nim, id_jenis, status_validasi, berkas, tanggal_ajukan) VALUES (?, ?, ?, ?, ?, ?)",
            [
                $data['id_tanggungan'],
                $data['mahasiswa_nim'],
                $data['id_jenis'],
                $data['status_validasi'],
                $data['berkas'],
                $data['tanggal_ajukan']
            ]
        );
    }

    public function getData()
    {
        $query = sqlsrv_query($this->db, "SELECT * FROM {$this->table}");
        $data = [];
        while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }

    public function getDataById($id)
    {
        $query = sqlsrv_query(
            $this->db,
            "SELECT * FROM {$this->table} WHERE id_tanggungan = ?",
            [$id]
        );
        return sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
    }

    public function updateData($id, $data)
    {
        sqlsrv_query(
            $this->db,
            "UPDATE {$this->table} SET mahasiswa_nim = ?, id_jenis = ?, status_validasi = ?, berkas = ?, tanggal_ajukan = ? WHERE id_tanggungan = ?",
            [
                $data['mahasiswa_nim'],
                $data['id_jenis'],
                $data['status_validasi'],
                $data['berkas'],
                $data['tanggal_ajukan'],
                $id
            ]
        );
    }

    public function deleteData($id)
    {
        sqlsrv_query(
            $this->db,
            "DELETE FROM {$this->table} WHERE id_tanggungan = ?",
            [$id]
        );
    }

    public function getSingleDataByKeyword($column, $keyword)
    {
        $query = sqlsrv_query(
            $this->db,
            "SELECT * FROM {$this->table} WHERE {$column} = ?",
            [$keyword]
        );
        return sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
    }
}
