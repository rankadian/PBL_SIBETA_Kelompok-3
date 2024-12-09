<?php
class Connection
{
    private $db;
    private $host = 'LAPTOP-9E916KGJ';
    private $username = '';
    private $password = '';
    private $database = 'SIBETA_NEW_3';

    // Konstruktor untuk membuat koneksi database
    public function __construct()
    {
        // Set kredensial koneksi
        $credential = [
            'Database' => $this->database,
            'UID' => $this->username,
            'PWD' => $this->password
        ];

        // Coba untuk melakukan koneksi
        try {
            $this->db = sqlsrv_connect($this->host, $credential);

            if (!$this->db) {
                $msg = sqlsrv_errors();
                die($msg[0]['message']); // Menampilkan pesan kesalahan jika koneksi gagal
            }
        } catch (Exception $e) {
            die($e->getMessage()); // Menangani kesalahan dengan exception
        }
    }

    // Fungsi untuk mendapatkan koneksi database
    public function getConnection()
    {
        return $this->db;
    }

    // Fungsi untuk menutup koneksi database
    public function closeConnection()
    {
        if ($this->db) {
            sqlsrv_close($this->db);
        }
    }
}
