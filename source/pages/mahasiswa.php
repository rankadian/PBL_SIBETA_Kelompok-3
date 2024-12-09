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
    <!-- Info table per semester -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Informasi Per Semester dan Tanggungannya</h3>
        </div>
        <div class="card-body">
            <table class="table table-sm table-bordered table-striped" id="semester-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Semester</th>
                        <th>Tanggungan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Dynamic table content will be injected here -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Layanan Bebas Tanggungan -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Layanan Bebas Tanggungan</h3>
        </div>
        <div class="card-body">
            <h4>Request Verifikasi</h4>
            <ul>
                <li>REQ Surat Publikasi *admin prodi <button class="btn btn-sm btn-info" onclick="downloadCart()">Download Cart</button></li>
                <li>REQ Surat Bebas Kompen *admin prodi</li>
                <li>REQ Surat Validasi PKL *admin ruang BACA</li>
                <li>REQ Surat SKLA *admin bu merry</li>
            </ul>

            <!-- Form Upload Bukti Validasi -->
            <h4>Form Upload Bukti Validasi</h4>
            <form id="uploadForm" action="uploadAction.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Upload Laporan Tugas Akhir</label>
                    <input type="file" class="form-control" name="laporan_tugas_akhir" id="laporan_tugas_akhir">
                    <button type="button" class="btn btn-sm btn-secondary" onclick="saveFile('laporan_tugas_akhir')">Save</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                <div class="form-group">
                    <label>Upload Program/Aplikasi (ZIP/RAR)</label>
                    <input type="file" class="form-control" name="program_aplikasi" id="program_aplikasi">
                    <button type="button" class="btn btn-sm btn-secondary" onclick="saveFile('program_aplikasi')">Save</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                <!-- Additional Upload Fields Here -->
            </form>

            <h4>Status Validasi</h4>
            <p>Status: <strong>Sudah Terverifikasi oleh Admin</strong></p>
            <a href="#" class="btn btn-info">Download Surat Bebas Tanggungan</a>
        </div>
    </div>

    <!-- Modal for Upload Form -->
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

    // Function for Download Cart (can be customized for actual cart download functionality)
    function downloadCart() {
        alert('Cart downloaded!');
    }

    // Initialize DataTable for semester info
    $(document).ready(function() {
        $('#semester-table').DataTable({
            ajax: 'MahasiswaAction.php?act=load', // Data loading from server
        });
    });
</script>
