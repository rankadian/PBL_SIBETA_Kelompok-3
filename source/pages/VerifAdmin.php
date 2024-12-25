<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Dashboard Header -->
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Verifikasi Mahasiswa</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Verifikasi Mahasiswa</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- Layanan Verifikasi Mahasiswa -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Layanan Verifikasi Mahasiswa</h3>
        </div>
        <div class="card-body">
            <h4>Status Verifikasi Mahasiswa</h4>
            <table id="verifikasiTable" class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID Upload</th>
                        <th>Nama Mahasiswa</th>
                        <th>Nama File</th>
                        <th>Jenis Surat</th>
                        <th>Tanggal Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data akan dimuat melalui AJAX -->
                    <!-- Data akan dimasukkan ke dalam tbody ini secara dinamis melalui JavaScript -->
                </tbody>
            </table>
            <br><br>
            <!-- Modal for Status Update -->
            <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="statusModalLabel">Perubahan Status Validasi</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="statusInput">Pilih Status</label>
                                <select class="form-control" id="statusInput" name="status">
                                    <option value="Terverifikasi">Terverifikasi</option>
                                    <option value="Menunggu Verifikasi">Menunggu Verifikasi</option>
                                    <option value="Ditolak">Ditolak</option>
                                </select>
                            </div>
                            <button type="button" class="btn btn-primary mt-3" onclick="updateStatus()">Update Status</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
$('#verifikasiTable').DataTable({
    ajax: {
        url: 'UploadAction.php?act=load', // Pastikan URL path benar
        type: 'GET' // Pastikan metode HTTP sesuai
    },
    columns: [
        { data: 0 }, // ID Upload
        { data: 1 }, // Nama Mahasiswa
        { data: 2 }, // Nama File
        { data: 3 }, // Jenis Surat
        { data: 4 }, // Tanggal Dibuat
        { data: 5 }  // Aksi
    ]
});


</script>