<?php
$use_driver = 'sqlsrv'; // mysql atau sqlsrv 

$host     = 'LAPTOP-9E916KGJ';
$username = ''; //'sa'; 
$password = '';
$database = 'dasar_web';
$db;


$credential = [
    'Database' => $database,
    'UID' => $username,
    'PWD' => $password
];

try {
    $db = sqlsrv_connect($host, $credential);

    if (!$db) {
        $msg = sqlsrv_errors();
        die($msg[0]['message']);
    }
} catch (Exception $e) {
    die($e->getMessage());
}
