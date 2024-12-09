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
                        <th>No</th>
                        <th>NIM</th>
                        <th>Nama Mahasiswa</th>
                        <th>Jenis Surat</th>
                        <th>Status Verifikasi</th>
                        <th>Tanggal Verifikasi</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data akan dimuat melalui AJAX -->
                </tbody>
            </table>
            <br><br>
            <!-- Status Validasi Section -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Status Validasi</h3>
                </div>
                <div class="card-body">
                    <h4>Status Validasi</h4>
                    <p>Status: <strong id="statusText">Menunggu Verifikasi</strong></p>

                    <!-- Tombol untuk mengubah status -->
                    <button class="btn btn-success" onclick="changeStatus('Terverifikasi')">Set Terverifikasi</button>
                    <button class="btn btn-warning" onclick="changeStatus('Menunggu Verifikasi')">Set Menunggu Verifikasi</button>
                    <button class="btn btn-danger" onclick="changeStatus('Ditolak')">Set Ditolak</button>
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
    // Function to load all verifikasi mahasiswa
    function loadVerifikasiData() {
        $.ajax({
            url: 'verifikasiMahasiswaAction.php?act=load', // Call the load action
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                let tbody = $('#verifikasiTable tbody');
                tbody.empty(); // Clear the current table content
                $.each(response.data, function(index, row) {
                    tbody.append(`
                        <tr>
                            <td>${index + 1}</td>
                            <td>${row.mahasiswa_nim}</td>
                            <td>${row.nama_mahasiswa}</td>
                            <td>${row.jenis_surat}</td>
                            <td>${row.status_validasi}</td>
                            <td>${row.tanggal_verifikasi}</td>
                            <td>
                                <button class="btn btn-sm btn-warning" onclick="editData(${row.id_verifikasi_admin})"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-sm btn-danger" onclick="deleteData(${row.id_verifikasi_admin})"><i class="fa fa-trash"></i></button>
                                <button class="btn btn-sm btn-success" onclick="approveVerification(${row.id_verifikasi_admin})"><i class="fa fa-check"></i> Approve</button>
                            </td>
                        </tr>
                    `);
                });
            }
        });
    }

    // Function to approve verification
    function approveVerification(id) {
        $.ajax({
            url: 'verifikasiMahasiswaAction.php?act=approve', // Assuming approve action is handled here
            method: 'POST',
            data: { id: id },
            success: function(response) {
                alert('Verifikasi berhasil disetujui!');
                loadVerifikasiData(); // Reload data after approval
            },
            error: function() {
                alert('Gagal menyetujui verifikasi!');
            }
        });
    }

    // Function to delete data
    function deleteData(id) {
        $.ajax({
            url: 'verifikasiMahasiswaAction.php?act=delete', // Assuming delete action is handled here
            method: 'POST',
            data: { id: id },
            success: function(response) {
                alert('Data berhasil dihapus!');
                loadVerifikasiData(); // Reload data after deletion
            }
        });
    }

    // Function to handle status changes
    function changeStatus(newStatus) {
        // Show modal for status change
        const statusModal = new bootstrap.Modal(document.getElementById('statusModal'));
        document.getElementById('statusInput').value = newStatus;
        statusModal.show();
    }

    // Function to update status
    function updateStatus() {
        const selectedStatus = document.getElementById('statusInput').value;

        // Update status on the page
        document.getElementById('statusText').textContent = selectedStatus;

        // Hide modal after update
        const statusModal = new bootstrap.Modal(document.getElementById('statusModal'));
        statusModal.hide();

        // Send update request to the server (using AJAX)
        alert('Status berhasil diubah menjadi: ' + selectedStatus);
    }

    // Initial load
    $(document).ready(function() {
        loadVerifikasiData(); // Load the verifikasi mahasiswa data when the page is ready
    });
</script>
