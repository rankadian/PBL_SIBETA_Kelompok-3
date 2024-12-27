<?php  
include_once('Model.php');  
include_once('Database.php');  

class GlobalModel extends Model  
{  
    protected $db;  
    protected $table = '';  
    protected $driver;  

    public function __construct()  
    {  
        // Mendapatkan instance database  
        $database = Database::getInstance();  
        $this->db = $database->getConnection(); // Mengatur sumber koneksi  
        $this->driver = $database->getDriver(); // Mengatur driver yang digunakan  

        // Memeriksa apakah koneksi valid  
        if ($this->db === false) {  
            throw new Exception("Koneksi database gagal: " . print_r(sqlsrv_errors(), true));  
        }  
    }  

    public function insertData($id) {}  
    public function getData() {}  
    public function getDataById($id) {}  
    public function updateData($id, $data) {}  
    public function deleteData($id) {}  

    public function getCountData($table)  
    {  
        $stmtTotal = sqlsrv_query($this->db, "SELECT count(*) as count from {$table}");  
        if ($stmtTotal === false) {  
            throw new Exception("Query gagal: " . print_r(sqlsrv_errors(), true));  
        }  

        $rowTotal = sqlsrv_fetch_array($stmtTotal, SQLSRV_FETCH_ASSOC);  
        return $rowTotal ? $rowTotal['count'] : 0;  
    }  

    public function getCountDynamicData($table, $conditions = [])  
    {  
        // Validasi nama tabel (hanya alfanumerik dan garis bawah yang diizinkan)  
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $table)) {  
            throw new InvalidArgumentException("Nama tabel tidak valid.");  
        }  

        // Mulai membangun query  
        $query = "SELECT COUNT(*) as count FROM {$table}";  

        // Tambahkan kondisi jika ada  
        $params = [];  
        if (!empty($conditions)) {  
            $query .= " WHERE ";  
            $clauses = [];  
            foreach ($conditions as $column => $value) {  
                $clauses[] = "{$column} = ?";  
                $params[] = $value; // Tambahkan nilai ke array parameter  
            }  
            $query .= implode(' AND ', $clauses); // Gabungkan semua kondisi dengan AND  
        }  

        // Eksekusi query  
        $stmtTotal = sqlsrv_query($this->db, $query, $params);  
        if ($stmtTotal === false) {  
            throw new Exception("Query gagal: " . print_r(sqlsrv_errors(), true));  
        }  

        // Ambil dan kembalikan jumlah  
        $rowTotal = sqlsrv_fetch_array($stmtTotal, SQLSRV_FETCH_ASSOC);  
        return $rowTotal ? $rowTotal['count'] : 0;  
    }  

    public function getSingleData($table, $conditions = [])  
    {  
        // Validasi nama tabel (hanya alfanumerik dan garis bawah yang diizinkan)  
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $table)) {  
            throw new InvalidArgumentException("Nama tabel tidak valid.");  
        }  

        // Mulai membangun query  
        $query = "SELECT * FROM {$table}";  

        // Tambahkan kondisi jika ada  
        $params = [];  
        if (!empty($conditions)) {  
            $query .= " WHERE ";  
            $clauses = [];  
            foreach ($conditions as $column => $value) {  
                $clauses[] = "{$column} = ?";  
                $params[] = $value; // Tambahkan nilai ke array parameter  
            }  
            $query .= implode(' AND ', $clauses); // Gabungkan semua kondisi dengan AND  
        }  

        // Eksekusi query  
        $stmt = sqlsrv_query($this->db, $query, $params);  
        if ($stmt === false) {  
            throw new Exception("Query gagal: " . print_r(sqlsrv_errors(), true));  
        }  

        // Ambil dan kembalikan satu record sebagai array asosiatif  
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);  
        return $row ?: []; // Kembalikan record atau array kosong jika tidak ada  
    }  

    public function getVerificationStatus($userId) {  
        $query = "SELECT TB_Verifikasi FROM TB_Mahasiswa WHERE IDVerifikasi = ?";  
        $params = [$userId];  
    
        $stmt = sqlsrv_query($this->db, $query, $params);  
        if ($stmt === false) {  
            // Catat kesalahan untuk debugging  
            error_log("Query SQL gagal: " . print_r(sqlsrv_errors(), true));  
            throw new Exception("Query gagal: " . print_r(sqlsrv_errors(), true));  
        }  
    
        if (sqlsrv_fetch($stmt)) {  
            return sqlsrv_get_field($stmt, 0);  
        }  
    
        return "Status tidak ditemukan";  
    }  
}