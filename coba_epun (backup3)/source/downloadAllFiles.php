<?php
// Debugging untuk memastikan skrip berjalan
echo "Skrip sedang berjalan...\n";

// Fungsi untuk mengompres folder menjadi file ZIP
function zipFolder($folderPath, $zipFilePath) {
    echo "Mencoba mengompres folder: $folderPath\n";
    $zip = new ZipArchive();

    // Pastikan zip archive dapat dibuka untuk menulis
    if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
        // Membaca isi folder
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($folderPath),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($folderPath) + 1); // Relative path untuk zip
                $zip->addFile($filePath, $relativePath); // Menambahkan file ke zip
            }
        }

        // Menutup file zip setelah selesai
        $zip->close();
        return true;
    } else {
        echo "Gagal membuka file ZIP untuk ditulis.\n";
    }
    return false;
}

// Tentukan folder yang akan dikompres
$folderToZip = 'assets/files/';
$zipFileName = 'downloaded_files.zip';
$zipFilePath = 'temp/' . $zipFileName;

// Pastikan folder temp ada dan dapat diakses
if (!file_exists('temp')) {
    mkdir('temp', 0777, true); // Membuat folder temp jika belum ada
}

if (!is_writable('temp')) {
    echo "Folder 'temp' tidak dapat diakses untuk menulis.\n";
    exit;
}

// Memulai proses pengompresan
if (zipFolder($folderToZip, $zipFilePath)) {
    echo "Folder berhasil dikompres.\n";
    
    // Mengatur header untuk mendownload file ZIP
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="' . $zipFileName . '"');
    header('Content-Length: ' . filesize($zipFilePath));

    // Output file ZIP ke browser
    readfile($zipFilePath);

    // Hapus file ZIP setelah selesai didownload
    unlink($zipFilePath);
} else {
    echo "Terjadi kesalahan saat membuat file ZIP.\n";
}
?>
