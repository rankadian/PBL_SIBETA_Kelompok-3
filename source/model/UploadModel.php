<?php
include_once('Model.php');

class UploadModel extends Model
{
    protected $db;
    protected $table = 'TB_Surat';

    public function __construct()
    {
        include('../lib/Connection.php');
        $this->db = $db;
    }
    public function insertData($data)
    {
        $sql = "INSERT INTO {$this->table} 
        ( NamaSurat, TanggalDibuat, BuktiSurat) 
        VALUES ( ?, ?, ?)";

        $params = [
            $data['NamaSurat'],
            $data['TanggalDibuat'],
            $data['BuktiSurat']
        ];


        // Persiapkan query
        $stmt = sqlsrv_prepare($this->db, $sql, $params);

        if (!$stmt) {
            die(print_r(sqlsrv_errors(), true)); // Debug jika terjadi error
        }

        // Eksekusi query
        if (sqlsrv_execute($stmt)) {
            echo "Data berhasil ditambahkan.";
        } else {
            die(print_r(sqlsrv_errors(), true)); // Debug jika eksekusi gagal
        }
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
            "SELECT * FROM {$this->table} WHERE IDSurat = ?",
            [$id]
        );
        return sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
    }
    public function updateData($id, $data)
    {
        sqlsrv_query(
            $this->db,
            "UPDATE {$this->table} SET IDSurat = ?, NamaSurat = ?, TanggalDibuat = ? WHERE IDSurat = ?",
            [
                $data['IDSurat'],
                $data['NamaSurat'],
                $data['TanggalDibuat'],
                $id
            ]
        );
    }
    public function deleteData($id)
    {
        sqlsrv_query(
            $this->db,
            "DELETE FROM {$this->table} WHERE IDSurat = ?",
            [$id]
        );
    }

    public function insertFileName($data)
    {
        $query = "INSERT INTO TB_Surat (NamaFile) VALUES (:NamaFile)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':NamaFile', $data['NamaFile']);
        return $stmt->execute();
    }
}
