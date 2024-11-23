<?php
// Include file koneksi database
include '../lib/connectionDB.php';

// Cek apakah form telah dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $subject = $conn->real_escape_string($_POST['subject']);
    $message = $conn->real_escape_string($_POST['message']);

    // Simpan ke database
    $sql = "INSERT INTO contact_messages (name, email, subject, message)
            VALUES ('$name', '$email', '$subject', '$message')";

    if ($conn->query($sql) === TRUE) {
        echo "Pesan Anda telah berhasil dikirim!";
    } else {
        echo "Terjadi kesalahan: " . $conn->error;
    }

    // Tutup koneksi
    $conn->close();
} else {
    echo "Akses tidak diizinkan!";
}
?>
