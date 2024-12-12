<?php

class UploadModel extends Model
{

    private $db;

    private $table = 'TB_TANGGUNGAN';
    protected $driver;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Menyisipkan data (harus sesuai dengan metode Model::insertData($id))
    public function insertData($id, $fileData = null, $fileType = null, $table = null)
    {
        // Cek jika fileType ada
        if ($fileType && $table) {
            if (!in_array($fileType, ['absensi', 'ukt', 'skkm', 'pkl', 'toeic', 'publikasi'])) {
                throw new Exception("Tipe file tidak valid.");
            }

            // Logic untuk menyimpan file berdasarkan tipe dan tabel
            $column = '';
            $fileColumn = '';
            switch ($fileType) {
                case 'pkl':
                    $column = 'tempat_pkl';
                    $fileColumn = 'laporan_pkl';
                    break;
                case 'toeic':
                    $column = 'hasil_toeic';
                    $fileColumn = 'hasil_toeic';
                    break;
                case 'publikasi':
                    $column = 'judul_skripsi';
                    $fileColumn = 'file_publikasi';
                    break;
                case 'absensi':
                    $column = 'semester';
                    $fileColumn = 'status_validasi';  // Absensi hanya memiliki status validasi
                    break;
                case 'ukt':
                    $column = 'status_validasi';
                    $fileColumn = 'status_validasi';
                    break;
                case 'skkm':
                    $column = 'point_skkm';
                    $fileColumn = 'status_validasi';
                    break;
                default:
                    throw new Exception("Tipe file tidak valid.");
            }

            // Menyisipkan data file
            if ($fileType === 'absensi' || $fileType === 'ukt' || $fileType === 'skkm') {
                $stmt = $this->db->prepare("INSERT INTO dbo.$table (mahasiswa_nim, $column) VALUES (?, ?)");
                $stmt->bind_param("ss", $id, $fileData);
            } else {
                $stmt = $this->db->prepare("INSERT INTO dbo.$table (mahasiswa_nim, $column, $fileColumn) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $id, $fileData['filename'], $fileData['filedata']);
            }

            return $stmt->execute();
        }

        throw new Exception("Parameter tidak lengkap.");
    }

    // Mendapatkan data file upload berdasarkan ID mahasiswa
    public function getDataById($id, $fileType = null, $table = null)
    {
        // Penyesuaian untuk mendapatkan data berdasarkan ID
        $column = '';
        $fileColumn = '';
        if ($fileType && $table) {
            switch ($fileType) {
                case 'pkl':
                    $column = 'tempat_pkl';
                    $fileColumn = 'laporan_pkl';
                    break;
                case 'toeic':
                    $column = 'hasil_toeic';
                    $fileColumn = 'hasil_toeic';
                    break;
                case 'publikasi':
                    $column = 'judul_skripsi';
                    $fileColumn = 'file_publikasi';
                    break;
                case 'absensi':
                    $column = 'semester';
                    $fileColumn = 'status_validasi';
                    break;
                case 'ukt':
                    $column = 'status_validasi';
                    $fileColumn = 'status_validasi';
                    break;
                case 'skkm':
                    $column = 'point_skkm';
                    $fileColumn = 'status_validasi';
                    break;
                default:
                    throw new Exception("Tipe file tidak valid.");
            }

            $stmt = $this->db->prepare("SELECT $fileColumn FROM dbo.$table WHERE mahasiswa_nim = ?");
            $stmt->bind_param("s", $id);

            if ($stmt->execute()) {
                $result = $stmt->get_result();
                return $result->fetch_assoc();
            }
        }

        throw new Exception("Tipe file atau tabel tidak valid.");
    }

    // Mendapatkan semua data upload file dari suatu tabel
    public function getData($fileType = null, $table = null)
    {
        $column = '';
        $fileColumn = '';
        if ($fileType && $table) {
            switch ($fileType) {
                case 'pkl':
                    $column = 'tempat_pkl';
                    $fileColumn = 'laporan_pkl';
                    break;
                case 'toeic':
                    $column = 'hasil_toeic';
                    $fileColumn = 'hasil_toeic';
                    break;
                case 'publikasi':
                    $column = 'judul_skripsi';
                    $fileColumn = 'file_publikasi';
                    break;
                case 'absensi':
                    $column = 'semester';
                    $fileColumn = 'status_validasi';
                    break;
                case 'ukt':
                    $column = 'status_validasi';
                    $fileColumn = 'status_validasi';
                    break;
                case 'skkm':
                    $column = 'point_skkm';
                    $fileColumn = 'status_validasi';
                    break;
                default:
                    throw new Exception("Tipe file tidak valid.");
            }

            $stmt = $this->db->prepare("SELECT mahasiswa_nim, $fileColumn FROM dbo.$table");
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                $fileData = [];
                while ($row = $result->fetch_assoc()) {
                    $fileData[] = $row;
                }
                return $fileData;
            }
        }

        throw new Exception("Tipe file atau tabel tidak valid.");
    }

    // Mengupdate data file berdasarkan nim mahasiswa
    public function updateData($id, $fileData = null, $fileType = null, $table = null)
    {
        $column = '';
        $fileColumn = '';
        if ($fileType && $table) {
            switch ($fileType) {
                case 'pkl':
                    $column = 'tempat_pkl';
                    $fileColumn = 'laporan_pkl';
                    break;
                case 'toeic':
                    $column = 'hasil_toeic';
                    $fileColumn = 'hasil_toeic';
                    break;
                case 'publikasi':
                    $column = 'judul_skripsi';
                    $fileColumn = 'file_publikasi';
                    break;
                case 'absensi':
                    $column = 'semester';
                    $fileColumn = 'status_validasi';
                    break;
                case 'ukt':
                    $column = 'status_validasi';
                    $fileColumn = 'status_validasi';
                    break;
                case 'skkm':
                    $column = 'point_skkm';
                    $fileColumn = 'status_validasi';
                    break;
                default:
                    throw new Exception("Tipe file tidak valid.");
            }

            if ($fileType === 'absensi' || $fileType === 'ukt' || $fileType === 'skkm') {
                $stmt = $this->db->prepare("UPDATE dbo.$table SET $fileColumn = ? WHERE mahasiswa_nim = ?");
                $stmt->bind_param("ss", $fileData, $id);
            } else {
                $stmt = $this->db->prepare("UPDATE dbo.$table SET $column = ?, $fileColumn = ? WHERE mahasiswa_nim = ?");
                $stmt->bind_param("sss", $fileData['filename'], $fileData['filedata'], $id);
            }

            return $stmt->execute();
        }

        throw new Exception("Parameter tidak lengkap.");
    }

    // Menghapus data file berdasarkan ID mahasiswa
    public function deleteData($id, $fileType = null, $table = null)
    {
        $column = '';
        $fileColumn = '';
        if ($fileType && $table) {
            switch ($fileType) {
                case 'pkl':
                    $column = 'tempat_pkl';
                    $fileColumn = 'laporan_pkl';
                    break;
                case 'toeic':
                    $column = 'hasil_toeic';
                    $fileColumn = 'hasil_toeic';
                    break;
                case 'publikasi':
                    $column = 'judul_skripsi';
                    $fileColumn = 'file_publikasi';
                    break;
                case 'absensi':
                    $column = 'semester';
                    $fileColumn = 'status_validasi';
                    break;
                case 'ukt':
                    $column = 'status_validasi';
                    $fileColumn = 'status_validasi';
                    break;
                case 'skkm':
                    $column = 'point_skkm';
                    $fileColumn = 'status_validasi';
                    break;
                default:
                    throw new Exception("Tipe file tidak valid.");
            }

            $stmt = $this->db->prepare("DELETE FROM dbo.$table WHERE mahasiswa_nim = ?");
            $stmt->bind_param("s", $id);
            return $stmt->execute();
        }

        throw new Exception("Tipe file atau tabel tidak valid.");
    }
}
