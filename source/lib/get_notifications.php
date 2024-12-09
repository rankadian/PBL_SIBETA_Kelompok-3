<?php
include('lib/Session.php');
include('lib/Connection.php'); // Ganti dari Database.php ke Connection.php

// Pastikan user sudah login
$session = new Session();
if ($session->get('is_login') !== true) {
    header('Location: login.php');
    exit;
}

// Ambil koneksi database
$conn = (new Connection())->getConnection();  // Membuat objek koneksi dari kelas Connection

// Ambil notifikasi dari database
$query = "
    SELECT n.id_notifikasi, n.pesan, n.tanggal_notifikasi
    FROM dbo.TB_NOTIFIKASI n
    JOIN dbo.TB_USER u ON n.user_id = u.id  -- Menggunakan user_id yang ada di TB_USER
    WHERE u.id = :user_id  -- Filter berdasarkan user_id yang ada di session
    ORDER BY n.tanggal_notifikasi DESC
";

$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $session->get('user_id'), PDO::PARAM_INT); // Mengambil user_id dari session
$stmt->execute();

$notifications = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $notifications[] = [
        'id' => $row['id_notifikasi'],
        'message' => $row['pesan'],
        'timestamp' => $row['tanggal_notifikasi']
    ];
}

// Kembalikan notifikasi dalam format JSON
header('Content-Type: application/json');
echo json_encode($notifications);
