<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES["file"]) && $_FILES["file"]["error"] == 0) {
        // Nama file dan lokasi penyimpanan
        $fileName = basename($_FILES["file"]["name"]);
        $fileTmp = $_FILES["file"]["tmp_name"];
        $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Tipe file yang diperbolehkan
        $allowed_types = array("jpg", "jpeg", "pdf");
        if (!in_array($fileType, $allowed_types)) {
            echo "Sorry, only JPG, JPEG, and PDF files are allowed.";
        } else {
            // Direktori penyimpanan
            $uploadDir = "../upload/";
            $filePath = $uploadDir . $fileName;

            // Ambil ID tanggungan dari form
            $id_tanggungan = $_POST['id_tanggungan'];

            // Pindahkan file ke folder upload
            if (move_uploaded_file($fileTmp, $filePath)) {
                // Koneksi ke database
                include('../config/connection.php');

                // Query untuk menyimpan nama file dan memperbarui status
                $sql = "UPDATE tanggungan 
                        SET berkas = ?, status = 'pending' 
                        WHERE id_tanggungan = ?";
                $stmt = sqlsrv_prepare($conn, $sql, array(&$fileName, &$id_tanggungan));

                // Eksekusi query
                if (sqlsrv_execute($stmt)) {
                    echo "The file has been uploaded, and the status has been updated to 'pending'.";
                } else {
                    echo "Error: " . print_r(sqlsrv_errors(), true);
                }

                // Bebaskan statement dan tutup koneksi
                if ($stmt !== false) {
                    sqlsrv_free_stmt($stmt);
                }
                sqlsrv_close($conn);
            } else {
                echo "Failed to move the uploaded file.";
            }
        }
    } else {
        echo "No file was uploaded.";
    }
}
?>
