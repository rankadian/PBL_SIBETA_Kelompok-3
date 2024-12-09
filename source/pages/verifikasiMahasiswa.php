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
                        <th>Status</th>
                        <th>Actions</th>
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
    // Function to load all verifikasi mahasiswa
    function loadVerifikasiData() {
        $.ajax({
            url: 'action/VerifikasiMahasiswaAction.php?act=load', // Pastikan URL ini sesuai
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                console.log(response); // Menampilkan data untuk debug
                let tbody = $('#verifikasiTable tbody');
                tbody.empty(); // Clear the current table content
                
                // Iterasi data
                $.each(response.data, function(index, row) {
                    tbody.append(`
                        <tr>
                            <td>${index + 1}</td>
                            <td>${row[2]}</td> <!-- mahasiswa_nim -->
                            <td>${row[1]}</td> <!-- admin_email -->
                            <td>${row[3]}</td> <!-- id_tanggungan -->
                            <td>${row[4]}</td> <!-- status_validasi -->
                            <td>
                                <button class="btn btn-sm btn-warning" onclick="changeStatusModal(${row[0]}, '${row[4]}')"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-sm btn-danger" onclick="deleteData(${row[0]})"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                    `);
                });
            },
            error: function(xhr, status, error) {
                console.error('Error loading data:', error);
                alert('Gagal memuat data.');
            }
        });
    }

    // Function to show the modal for status change
    function changeStatusModal(id, currentStatus) {
        const statusModal = new bootstrap.Modal(document.getElementById('statusModal'));
        document.getElementById('statusInput').value = currentStatus;
        statusModal.show();
        window.currentVerificationId = id; // Store the ID globally
    }

    // Function to update status
    function updateStatus() {
        const selectedStatus = document.getElementById('statusInput').value;

        $.ajax({
            url: 'action/VerifikasiMahasiswaAction.php?act=update', // Assuming update action is handled here
            method: 'POST',
            data: { id: window.currentVerificationId, status_validasi: selectedStatus },
            success: function(response) {
                alert('Status berhasil diubah!');
                loadVerifikasiData(); 
                const statusModal = new bootstrap.Modal(document.getElementById('statusModal'));
                statusModal.hide();
            },
            error: function() {
                alert('Gagal mengubah status!');
            }
        });
    }

    // Function to delete data
    function deleteData(id) {
        $.ajax({
            url: 'action/VerifikasiMahasiswaAction.php?act=delete', 
            method: 'POST',
            data: { id: id },
            success: function(response) {
                alert('Data berhasil dihapus!');
                loadVerifikasiData();
            },
            error: function() {
                alert('Gagal menghapus data!');
            }
        });
    }

    // Initial load
    $(document).ready(function() {
        loadVerifikasiData(); 
    });
</script>

