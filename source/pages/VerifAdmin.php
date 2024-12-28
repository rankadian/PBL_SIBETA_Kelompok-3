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
                    <li class="breadcrumb-item"><a href="DashAdmin.php">Home</a></li>
                    <li class="breadcrumb-item active">Verifikasi Mahasiswa</li>
                </ol>
            </div>
        </div>

        <!-- Layanan Verifikasi Mahasiswa -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-check-circle mr-2"></i>
                    Layanan Verifikasi Mahasiswa
                </h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="verifikasiTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>NIM</th>
                                <th>Nama</th>
                                <th>Program Studi</th>
                                <th>Jenis Surat</th>
                                <th>Nama File</th>
                                <th>Tanggal Upload</th>
                                <th>Status</th>
                                <th>Catatan</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data akan dimuat melalui AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Penolakan -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title text-white">
                    <i class="fas fa-times-circle mr-2"></i>
                    Penolakan Dokumen
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formReject">
                <div class="modal-body">
                    <input type="hidden" name="IDVerifikasi" id="rejectIDVerifikasi">
                    <input type="hidden" name="StatusVerifikasi" value="0">
                    
                    <div class="form-group">
                        <label for="rejectCatatan">Catatan Penolakan <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="Catatan" id="rejectCatatan" rows="4" 
                            placeholder="Mohon berikan alasan penolakan dokumen" required></textarea>
                        <small class="text-muted">
                            Catatan ini akan ditampilkan kepada mahasiswa sebagai alasan penolakan dokumen.
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-2"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-check mr-2"></i>Konfirmasi Penolakan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Template untuk Preview File -->
<div class="modal fade" id="previewModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Preview Dokumen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <iframe id="filePreview" style="width: 100%; height: 500px;" frameborder="0"></iframe>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Inisialisasi DataTable dengan konfigurasi
    var table = $('#verifikasiTable').DataTable({
        "processing": true,
        "serverSide": false,
        "ajax": {
            "url": "action/VerifAdminAction.php?act=load",
            "type": "GET"
        },
        "columns": [
            { "data": 0 },  // No
            { "data": 9 },  // NIM
            { "data": 10 }, // Nama
            { "data": 11 }, // Program Studi
            { "data": 7 },  // Jenis Surat
            { 
                "data": 6,  // Nama File
                "render": function(data, type, row) {
                    return `<a href="javascript:void(0)" onclick="previewFile('${data}')">${data}</a>`;
                }
            },
            { "data": 8 },  // Tanggal Upload
            { "data": 3 },  // Status
            { "data": 4 },  // Catatan
            { "data": 13 }  // Aksi
        ],
        "order": [[0, 'asc']],
        "responsive": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        },
        "columnDefs": [
            {
                "targets": -1,
                "className": "text-center"
            }
        ]
    });

    // Handle form rejection submission
    $('#formReject').on('submit', function(e) {
        e.preventDefault();
        
        if (!$('#rejectCatatan').val().trim()) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Catatan penolakan harus diisi'
            });
            return;
        }

        $.ajax({
            url: 'action/VerifAdminAction.php?act=update',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                try {
                    var result = JSON.parse(response);
                    if (result.status) {
                        $('#rejectModal').modal('hide');
                        table.ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Dokumen berhasil ditolak'
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
                    text: 'Gagal menghubungi server'
                });
            }
        });
    });
});

// Function to approve document
function approveDocument(id) {
    Swal.fire({
        title: 'Konfirmasi Persetujuan',
        text: "Apakah Anda yakin ingin menyetujui dokumen ini?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Setuju',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'action/VerifAdminAction.php?act=update',
                type: 'POST',
                data: {
                    IDVerifikasi: id,
                    StatusVerifikasi: 1,
                    Catatan: 'Dokumen disetujui'
                },
                success: function(response) {
                    try {
                        var result = JSON.parse(response);
                        if (result.status) {
                            $('#verifikasiTable').DataTable().ajax.reload();
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: 'Dokumen berhasil disetujui'
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
                        text: 'Gagal menghubungi server'
                    });
                }
            });
        }
    });
}

// Function to show reject modal
function showRejectModal(id) {
    $('#rejectIDVerifikasi').val(id);
    $('#rejectCatatan').val('');
    $('#rejectModal').modal('show');
}

// Function to preview file
function previewFile(filename) {
    // Assuming files are stored in an 'uploads' directory
    var fileUrl = '../uploads/' + filename;
    $('#filePreview').attr('src', fileUrl);
    $('#previewModal').modal('show');
}
</script>