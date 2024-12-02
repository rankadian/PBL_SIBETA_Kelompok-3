<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['file'])) {
        $uploadDir = 'uploads/'; // Folder penyimpanan file upload
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true); // Buat folder jika belum ada
        }
        $fileName = basename($_FILES['file']['name']);
        $filePath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['file']['tmp_name'], $filePath)) {
            echo '<div class="alert alert-success">File berhasil diunggah: ' . $fileName . '</div>';
        } else {
            echo '<div class="alert alert-danger">Gagal mengunggah file.</div>';
        }
    }
}
?>

<div class="mb-5">
    <h2>Layanan Bebas Tanggungan</h2>
    <p>DOWNLOAD SURAT:</p>
    <ul>
        <li><a href="../documents/surat_publikasi.docx" download>Request Surat Publikasi</a></li>
        <li><a href="../documents/surat_bebas_kompen.docx" download>Request Surat Bebas Kompen</a></li>
        <li><a href="../documents/surat_validasi_pkl.docx" download>Request Surat Validasi PKL</a></li>
        <li><a href="../documents/surat_skla.docx" download>Request Surat SKLA</a></li>
    </ul>
    <h3>Form Upload Bukti Validasi</h3>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="file" class="form-label">Upload File</label>
            <input type="file" name="file" id="file" class="form-control" required>
        </div>
        <button type="submit" name="save" class="btn btn-secondary">Save</button>
        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
