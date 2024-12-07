<?php  
    $use_driver = 'sqlsrv'; // mysql atau sqlsrv 
 
    $host     = 'LAPTOP-Q3PDL8SK'; 
    $username = ''; //'sa'; 
    $password = ''; 
    $database = 'SIBETA_NEW_3'; 
    $db; 
 
    
        $credential = [ 
            'Database' => $database,  
            'UID' => $username,  
            'PWD' => $password 
        ]; 
         
        try{ 
            $db = sqlsrv_connect($host, $credential); 
 
            if (!$db){ 
                $msg = sqlsrv_errors(); 
                die($msg[0]['message']); 
            } 
        }catch(Exception $e){ 
            die($e->getMessage()); 
        } 
   