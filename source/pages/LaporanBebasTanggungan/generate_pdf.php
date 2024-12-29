<?php

require 'vendor/autoload.php'; // Autoload Composer
include '../../lib/Connection.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Konfigurasi dompdf
$options = new Options();
$options->set('defaultFont', 'Arial'); // Atur font default
$options->set('isHtml5ParserEnabled', true); // Enable HTML5 parsing untuk mendukung tag HTML5
$options->set('isPhpEnabled', true); // Mengaktifkan eksekusi PHP dalam PDF (jika diperlukan)
$options->set('isRemoteEnabled', true); // Untuk menggunakan sumber daya eksternal (gambar, dll.)

$dompdf = new Dompdf($options);

// Ambil data mahasiswa dari session atau database
$mahasiswa = [
    'nama' => 'Azkiya Putri', // Nama mahasiswa
    'nim' => '2341760175', // NIM mahasiswa
    'program_studi' => 'Sistem Informasi Bisnis Politeknik Negeri Malang' // Program Studi
];

// Fungsi untuk mengonversi gambar ke Base64
function convertImageToBase64($imagePath) {
    if (!file_exists($imagePath)) {
        return ''; // Kembalikan string kosong jika file tidak ada
    }
    $imageData = file_get_contents($imagePath);
    return 'data:image/png;base64,' . base64_encode($imageData);
}

// Mengonversi gambar logo dan tanda tangan ke Base64
$logoBase64 = convertImageToBase64('polinema.png');
$cstmBase64 = convertImageToBase64('cstm.jpg');
$ttdBase64 = convertImageToBase64('ttd.png');

// Konten HTML yang ingin diubah menjadi PDF
$html = '
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Keterangan Bebas Tanggungan</title>
    <style>
        body {
            font-family: "Arial", sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            border: 1px solid #000;
            padding: 20px;
        }
        .header {
            display: flex; /* Menggunakan flexbox untuk penataan */
            align-items: center; /* Menyelaraskan item secara vertikal */
            margin-bottom: 20px;
        }
        .header img {
            width: 100px;
            height: auto;
            margin-right: 20px; /* Memberikan jarak antara logo dan teks */
        }
        .header h1 {
            font-size: 18px;
            margin: 5px 0;
        }
        .header h2 {
            font-size: 16px;
            margin: 5px 0;
        }
        .header p {
            margin: 5px 0;
            font-size: 14px;
        }
        .content {
            margin-top: 20px;
        }
        .content p {
            margin: 10px 0;
        }
        .content .detail {
            margin-left: 30px;
        }
        .signature {
            margin-top: 30px;
            text-align: right;
        }
        .signature p {
            margin: 5px 0;
        }
        .signature .name {
            margin-top: 50px;
        }
        .footer {
            text-align: left;
            font-size: 14px;
            margin-top: 10px;
        }
        .signature img {
            width: 180px; /* Ukuran baru untuk tanda tangan */
            height: auto;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="' . $logoBase64 . '" alt="Logo" align="left">
        <img src="' . $cstmBase64 . '" alt="Logo" align="right">
        <div>
            <h1 align="center">KEMENTERIAN PENDIDIKAN DAN KEBUDAYAAN</h1>
            <h2 align="center">POLITEKNIK NEGERI MALANG</h2>
            <p align="center">JURUSAN TEKNOLOGI INFORMASI<br>PROGRAM STUDI SISTEM INFORMASI BISNIS</p>
            <p align="center">Jl. Soekarno Hatta No.9 Malang 65141 Telp (0341) 404424 – 404425 Fax (0341) 404420</p>
        </div>
    </div>

    <h3 style="text-align: center; text-decoration: underline;">SURAT KETERANGAN BEBAS TANGGUNGAN</h3>

    <div class="content">
        <p>Yang bertanda tangan di bawah ini, Ketua Jurusan Teknologi Informasi Politeknik Negeri Malang, menerangkan bahwa mahasiswa yang tercantum di bawah ini :</p>
        <div class="detail">
            <p>Nama: <strong>' . htmlspecialchars($mahasiswa['nama']) . '</strong></p>
            <p>NIM: <strong>' . htmlspecialchars($mahasiswa['nim']) . '</strong></p>
            <p>Program Studi: <strong>' . htmlspecialchars($mahasiswa['program_studi']) . '</strong></p>
        </div>

        <p>Telah memenuhi persyaratan untuk mengikuti Yudisium Sarjana/Diploma Program Studi D-IV Sistem Informasi Bisnis Pada Gelombang: 002 TA. 2024/2025 dengan rincian sebagai berikut:</p>
        
        <p>Berkas Kehadiran Absensi <strong>Berkas Tervalidasi</strong></p>
        <p>Berkas Pelunasan Administrasi  <strong>Berkas Tervalidasi</strong></p>
        <p>Berkas Bukti Magang  <strong>Berkas Tervalidasi</strong></p>
        <p>Berkas Sertifikat TOEIC  <strong>Berkas Tervalidasi</strong></p>
        <p>Berkas Surat Keterangan Keaktifan Mahasiswa  <strong>Berkas Tervalidasi</strong></p>
        <p>Berkas Bukti Publikasi <strong>Berkas Tervalidasi</strong></p>
    </div>
    <div class="signature">
        <p>Malang, 10 Desember 2024</p>
        <p>Mengetahui,</p>
        <p>Ketua Jurusan Teknologi Informasi</p>
        <img src="' . $ttdBase64 . '" alt="Tanda Tangan Dr. Eng. Rosa Andrie Asmara">
        <p class="name"><strong>Dr. Eng. Rosa Andrie Asmara, S.T., M.T.</strong></p>
        <p>NIP: 198010102005011001</p>
    </div>
</body>
</html>';

// Load konten HTML ke dompdf
$dompdf->loadHtml($html);

// Atur ukuran dan orientasi kertas (opsional)
$dompdf->setPaper('A4', 'portrait'); // Ukuran A4, orientasi potrait

// Render PDF
$dompdf->render();

// Outputkan PDF ke browser (download otomatis)
$dompdf->stream("laporan_bebastanggungan.pdf", ["Attachment" => false]);

?>