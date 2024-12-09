<?php
class AdminModel {
    private $db;

    public function __construct($connection) {
        $this->db = $connection;
    }

    public function countPendingTanggungan() {
        $sql = "SELECT COUNT(*) AS pending_count FROM Tanggungan WHERE status = 'pending'";
        $stmt = sqlsrv_query($this->db, $sql);
    
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
    
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        sqlsrv_free_stmt($stmt);
    
        return $row['pending_count'];
    }
}

$model = new AdminModel($conn);
$pendingCount = $model->countPendingTanggungan();