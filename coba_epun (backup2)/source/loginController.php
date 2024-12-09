<?php
include('lib/Session.php');
include('lib/Connection.php');

// Buat objek koneksi
$connection = new Connection();
$conn = $connection->getConnection(); // Mendapatkan koneksi dari objek Connection

// Pastikan koneksi berhasil
if (!$conn) {
    die("Koneksi database gagal.");
}

// Periksa apakah form telah dikirimkan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form login
    $inputUsername = isset($_POST['username']) ? $_POST['username'] : null;
    $inputPassword = isset($_POST['password']) ? $_POST['password'] : null;

    if ($inputUsername && $inputPassword) {
        // cari user berdasarkan username
        $query = "SELECT * FROM TB_USER WHERE username = ?";
        $params = [$inputUsername];

        $stmt = sqlsrv_query($conn, $query, $params);

        if ($stmt === false) {
            die("Query gagal: " . print_r(sqlsrv_errors(), true));
        }

        $user = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

        // Validasi user
        if ($user) {
            // Verifikasi password
            if (password_verify($inputPassword, $user['password'])) {
                // Set session berdasarkan level
                session_start();
                $_SESSION['username'] = $user['username'];
                $_SESSION['level'] = $user['level'];
                $_SESSION['id'] = $user['id'];

                // Redirect sesuai role
                if ($user['level'] === 'admin') {
                    header("Location: admin_dashboard.php");
                } elseif ($user['level'] === 'mahasiswa') {
                    header("Location: ../source/pages/mahasiswa.php");
                } elseif ($user['level'] === 'kps') {
                    header("Location: kps_dashboard.php");
                }
                exit;
            } else {
                echo "Password salah!";
            }
        } else {
            echo "Username tidak ditemukan!";
        }

        // Tutup koneksi
        sqlsrv_free_stmt($stmt);
    } else {
        echo "Silakan isi username dan password.";
    }
} else {
    echo "Akses langsung tidak diperbolehkan.";
}

// Tutup koneksi
$connection->closeConnection();
