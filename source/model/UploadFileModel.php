<?php
include_once('UploadModel.php');

class UploadFileModel extends UploadModel
{
    // Mengambil semua data dari tabel surat
    public function getData()
    {
        $query = sqlsrv_query($this->db, "SELECT * FROM {$this->tableSurat}");
        $data = [];
        while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }

    // Mengambil data berdasarkan ID
    public function getDataById($id)
    {
        $query = sqlsrv_query(
            $this->db,
            "SELECT * FROM {$this->tableSurat} WHERE IDSurat = ?",
            [$id]
        );
        return sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
    }

    // Memperbarui data berdasarkan ID
    public function updateData($id, $data)
    {
        sqlsrv_query(
            $this->db,
            "UPDATE {$this->tableSurat} SET NamaSurat = ?, JenisSurat = ?, TanggalDibuat = ? WHERE IDSurat = ?",
            [
                $data['NamaSurat'],
                $data['JenisSurat'],
                $data['TanggalDibuat'],
                $id
            ]
        );
    }

    // Menghapus data berdasarkan ID
    public function deleteData($id)
    {
        sqlsrv_query(
            $this->db,
            "DELETE FROM {$this->tableSurat} WHERE IDSurat = ?",
            [$id]
        );
    }

    // Menambahkan data baru ke tabel surat
    public function insertData($data)
    {
        sqlsrv_query(
            $this->db,
            "INSERT INTO {$this->tableSurat} (NamaSurat, JenisSurat, TanggalDibuat) VALUES (?, ?, ?)",
            [
                $data['NamaSurat'],
                $data['JenisSurat'],
                $data['TanggalDibuat']
            ]
        );
    }
}
