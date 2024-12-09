<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Dashboard Header -->
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Beranda</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Beranda</li>
                </ol>
            </div>
        </div>
    </div>

    <!-- Layanan Bebas Tanggungan Section -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Layanan Bebas Tanggungan</h3>
        </div>
        <div class="card-body">
            <h4>Request Verifikasi</h4>
            <ul>
                <li>REQ Surat Publikasi *admin prodi 
                    <!-- Button to Download All Files -->
                    <button class="btn btn-sm btn-info" onclick="downloadAllFiles()">Download Semua File</button>
                </li>
                <li>REQ Surat Bebas Kompen *admin prodi</li>
                <li>REQ Surat Validasi PKL *admin ruang BACA</li>
                <li>REQ Surat SKLA *admin bu merry</li>
            </ul>

            <!-- Status Validasi -->
            <h4>Status Validasi</h4>
            <p>Status: <strong>Sudah Terverifikasi oleh Admin</strong></p>
            <a href="#" class="btn btn-info">Download Surat Bebas Tanggungan</a>
        </div>
    </div>

    <!-- File Upload Section -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Form Upload Bukti Validasi</h3>
        </div>
        <div class="card-body">
            <h4>Upload Bukti Laporan</h4>
            <form id="uploadForm" action="uploadAction.php" method="post" enctype="multipart/form-data">
                <!-- Upload Laporan Tugas Akhir -->
                <div class="form-group">
                    <label>Upload Laporan Tugas Akhir</label>
                    <input type="file" class="form-control" name="laporan_tugas_akhir" id="laporan_tugas_akhir">
                    <button type="button" class="btn btn-sm btn-secondary" onclick="saveFile('laporan_tugas_akhir')">Save</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                
                <!-- Upload Program/Aplikasi (ZIP/RAR) -->
                <div class="form-group">
                    <label>Upload Program/Aplikasi (ZIP/RAR)</label>
                    <input type="file" class="form-control" name="program_aplikasi" id="program_aplikasi">
                    <button type="button" class="btn btn-sm btn-secondary" onclick="saveFile('program_aplikasi')">Save</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal for File Upload -->
    <div class="modal fade" id="form-upload" style="display: none;" aria-hidden="true">
        <form action="uploadAction.php?act=save" method="post" id="form-upload-data">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Upload Form</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>File</label>
                            <input type="file" class="form-control" name="upload_file" id="upload_file">
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<script>
    // Function to handle saving files
    function saveFile(fileId) {
        const file = document.getElementById(fileId).files[0];
        if (file) {
            alert('File saved: ' + file.name);
        } else {
            alert('No file selected!');
        }
    }

    // Function for Downloading All Files (ZIP)
    function downloadAllFiles() {
        window.location.href = 'downloadAllFiles.php'; // Redirect to PHP script to download ZIP
    }

    // Function to Download Single File (for example, 'Surat Bebas Tanggungan')
    function downloadSingleFile(fileUrl) {
        window.location.href = fileUrl; // Redirect to the file download URL
    }
</script>
