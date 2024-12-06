<?php
function antiInjection($db, $data)
{
    // Check for null or empty data
    if (is_null($data) || $data === '') {
        return '';
    }

    $cleanedData = strip_tags($data);
    $cleanedData = htmlspecialchars($cleanedData, ENT_QUOTES, 'UTF-8');

    return $cleanedData;
}

function insertData($db, $data)
{
    $cleanedData = antiInjection($db, $data);

    $sql = "INSERT INTO your_table (your_column) VALUES (?)";
    $stmt = sqlsrv_prepare($db, $sql, array(&$cleanedData));

    if (sqlsrv_execute($stmt)) {
        echo "Data inserted successfully.";
    } else {
        echo "Error inserting data.";
    }
}
