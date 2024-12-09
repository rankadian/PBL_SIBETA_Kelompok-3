<?php
// get_notifications.php
// Simulasi pengambilan notifikasi dari database atau sumber lainnya
$notifications = [
    ['message' => 'Pesan 1: Notifikasi baru!', 'timestamp' => '2024-12-07 10:00:00'],
    ['message' => 'Pesan 2: Ada update terbaru.', 'timestamp' => '2024-12-07 08:30:00'],
    // Tambahkan notifikasi lainnya jika perlu
];

// Kembalikan data dalam format JSON
echo json_encode($notifications);
