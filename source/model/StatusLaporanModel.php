<?php
include_once('Model.php');

class StatusLaporanModel extends Model
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
        // Tidak dibutuhkan untuk melihat status laporan.
    }

    public function getData()
    {
        $sql = "SELECT * FROM {$this->table}";
        $query = sqlsrv_query($this->db, $sql);
        $data = [];
        while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }

    public function getDataById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE PengajuanID = ?";
        $params = [$id];
        $stmt = sqlsrv_prepare($this->db, $sql, $params);
        sqlsrv_execute($stmt);
        return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    }

    public function updateData($id, $data)
    {
        // Tidak relevan untuk fitur ini.
    }

    public function deleteData($id)
    {
        // Tidak relevan untuk fitur ini.
    }
}
