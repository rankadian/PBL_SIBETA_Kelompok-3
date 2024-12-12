<?php
include_once('Model.php');

abstract class UploadModel extends Model
{
    protected $db;
    protected $tableSurat = 'TB_Surat';
    protected $tablePengajuan = 'TB_Pengajuan';

    public function __construct()
    {
        include('../lib/Connection.php');
        $this->db = $db;
    }

    // Fungsi abstrak yang harus diimplementasikan di kelas turunan
    abstract public function getData();
    abstract public function getDataById($id);
    abstract public function updateData($id, $data);
    abstract public function deleteData($id);

    // Fungsi umum untuk mengupload file
    public function uploadFile($fileData, $idSurat)
    {
        // Validasi file
        if ($fileData['error'] !== UPLOAD_ERR_OK) {
            return "Terjadi kesalahan saat mengupload file.";
        }
    
        // Generate nama file unik
        $fileExtension = pathinfo($fileData['name'], PATHINFO_EXTENSION);
        $fileName = uniqid('surat_', true) . '.' . $fileExtension;
    
        // Lokasi penyimpanan file (relative path)
        $uploadDirectory = __DIR__ . '/../uploads/'; // Correct relative path
        $filePath = $uploadDirectory . $fileName;
    
        // Pastikan direktori uploads ada dan dapat ditulis
        if (!is_dir($uploadDirectory) && !mkdir($uploadDirectory, 0775, true)) {
            return "Gagal membuat direktori uploads.";
        }
    
        // Pindahkan file ke server
        if (!move_uploaded_file($fileData['tmp_name'], $filePath)) {
            return "Gagal menyimpan file ke server.";
        }
    
        // Update nama file ke database
        $query = sqlsrv_query(
            $this->db,
            "UPDATE {$this->tableSurat} SET NamaSurat = ? WHERE IDSurat = ?",
            [$fileName, $idSurat]
        );
    
        if (!$query) {
            return "Gagal menyimpan nama file ke database.";
        }
    
        return true;
    }
    


    // Fungsi umum untuk membuat pengajuan
    public function createPengajuan($data)
    {
        $query = sqlsrv_query(
            $this->db,
            "INSERT INTO {$this->tablePengajuan} (NIM, IDSurat, TanggalPengajuan, StatusPengajuan, CatatanAdmin) 
             VALUES (?, ?, GETDATE(), ?, ?)",
            [
                $data['NIM'],
                $data['IDSurat'],
                $data['StatusPengajuan'],
                $data['CatatanAdmin']
            ]
        );

        if (!$query) {
            return "Gagal menyimpan data pengajuan.";
        }

        return true;
    }
}
