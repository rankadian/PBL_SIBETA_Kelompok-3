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
            <table id="verifikasiTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Catatan</th>
                        <th>File</th>
                        <th>Jenis Surat</th>
                        <th>Tanggal Upload</th>
                        <th>NIM</th>
                        <th>Nama Mahasiswa</th>
                        <th>Admin</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data akan dimuat melalui AJAX -->
                </tbody>
            </table>
        </div>
    </div>
</section>
<!-- Modal Verifikasi -->
<div class="modal fade" id="verifikasiModal" tabindex="-1" role="dialog">
    <form action="action/VerifAdminAction.php?act=update" method="post" id="formVerifikasi">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Verifikasi Dokumen</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="IDVerifikasi" id="IDVerifikasi">
                    <div class="form-group">
                        <label>Status Verifikasi</label>
                        <select class="form-control" name="StatusVerifikasi" id="StatusVerifikasi">
                            <option value="1">Terima</option>
                            <option value="0">Tolak</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Catatan</label>
                        <textarea class="form-control" name="Catatan" id="Catatan" rows="3" required></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Make sure SweetAlert2 is included -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        var table = $('#verifikasiTable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "action/VerifAdminAction.php?act=load",
                "type": "POST"
            },
            "columns": [{
                    "data": 0
                }, // ID Verifikasi
                {
                    "data": 1
                }, // Tanggal Verifikasi
                {
                    "data": 2,
                    "render": function(data) {
                        if (data === null || data === '') {
                            return '<span class="badge badge-warning">Menunggu</span>';
                        }
                        return data == 1 ?
                            '<span class="badge badge-success">Diterima</span>' :
                            '<span class="badge badge-danger">Ditolak</span>';
                    }
                },
                {
                    "data": 3
                }, // Catatan
                {
                    "data": 4
                }, // Nama File
                {
                    "data": 5
                }, // Jenis Surat
                {
                    "data": 6
                }, // Tanggal Upload
                {
                    "data": 7
                }, // NIM
                {
                    "data": 8
                }, // Nama Mahasiswa
                {
                    "data": 9
                }, // Admin
                {
                    "data": 10,
                    "orderable": false,
                    "searchable": false,
                    "className": "text-center"
                }
            ],
            "order": [
                [1, 'desc']
            ],
            "responsive": true,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
            }
        });

        // Handle form submission
        $('#formVerifikasi').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    try {
                        var result = JSON.parse(response);
                        if (result.status) {
                            $('#verifikasiModal').modal('hide');
                            table.ajax.reload();
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: result.message
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: result.message || 'Terjadi kesalahan'
                            });
                        }
                    } catch (e) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan saat memproses response'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Gagal memperbarui data'
                    });
                }
            });
        });
    });

    // Fungsi untuk edit/verifikasi data
    function editData(id) {
        $.ajax({
            url: 'action/VerifAdminAction.php?act=get&id=' + id,
            type: 'GET',
            success: function(response) {
                try {
                    var data = JSON.parse(response);
                    $('#verifikasiModal').modal('show');
                    $('#formVerifikasi').attr('action', 'action/VerifAdminAction.php?act=update&id=' + id);
                    $('#IDVerifikasi').val(data.IDVerifikasi);
                    $('#StatusVerifikasi').val(data.StatusVerifikasi);
                    $('#Catatan').val(data.Catatan);
                } catch (e) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Gagal memproses data'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Gagal mengambil data verifikasi'
                });
            }
        });
    }

    // Fungsi untuk menghapus data
    function deleteData(id) {
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: "Apakah Anda yakin ingin menghapus data ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading state
                Swal.fire({
                    title: 'Memproses...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowEnterKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Send delete request
                $.ajax({
                    url: 'action/VerifAdminAction.php?act=delete&id=' + id,
                    type: 'POST',
                    dataType: 'json',
                    success: function(response) {
                        if (response.status) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
                            $('#verifikasiTable').DataTable().ajax.reload(null, false);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        let errorMessage = 'Terjadi kesalahan saat menghapus data';
                        try {
                            const response = JSON.parse(xhr.responseText);
                            if (response.message) {
                                errorMessage = response.message;
                            }
                        } catch (e) {
                            console.error('Error parsing response:', e);
                        }
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorMessage
                        });
                    }
                });
            }
        });
    }
</script>