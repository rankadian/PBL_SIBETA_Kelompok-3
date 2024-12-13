<?php

// Include file database.php yang berisi koneksi database
include('Model.php');

class addMahasiswaModel  {
    protected $db;
    protected $table = 'TB_USER'; // Replace with the correct table name
    protected $driver;

    public function __construct() {
        // Initialize database connection
        include_once('../lib/Connection.php');
        $this->db = $db;
        $this->driver = $use_driver;
    }

    // Function to add mahasiswa data to the database
    public function insertData($data) {
        // Prepare the SQL query to insert data into the table
        $sql = "INSERT INTO {$this->table} (username, password, level) 
                VALUES (?, ?, ?)";
    
        // Prepare the statement
        $stmt = sqlsrv_prepare($this->db, $sql, array(
            $data['username'],
            $data['password'],
            $data['level']
        ));
    
        // Execute the query
        if (sqlsrv_execute($stmt)) {
            return true; // Data successfully inserted
        } else {
            return false; // Error inserting data
        }
    }
}
?>
