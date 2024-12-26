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

            <!-- Modal Verifikasi -->
            <div class="modal fade" id="verifikasiModal" tabindex="-1" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Verifikasi Dokumen</h5>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <form id="formVerifikasi">
                                <input type="hidden" id="idVerifikasi">
                                <div class="form-group">
                                    <label>Status Verifikasi</label>
                                    <select class="form-control" id="statusVerifikasi">
                                        <option value="1">Terima</option>
                                        <option value="0">Tolak</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Catatan</label>
                                    <textarea class="form-control" id="catatan" rows="3" required></textarea>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
$(document).ready(function() {
    var table = $('#verifikasiTable').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "action/VerifAdminAction.php?act=load",
            "type": "POST"
        },
        "columns": [
            {"data": 0}, // ID Verifikasi
            {"data": 1}, // Tanggal Verifikasi
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
            {"data": 3}, // Catatan
            {"data": 4}, // Nama File
            {"data": 5}, // Jenis Surat
            {"data": 6}, // Tanggal Upload
            {"data": 7}, // NIM
            {"data": 8}, // Nama Mahasiswa
            {"data": 9}, // Admin
            {
                "data": 10,
                "orderable": false,
                "searchable": false,
                "className": "text-center"
            }
        ],
        "order": [[1, 'desc']], // Sort by tanggal verifikasi
        "responsive": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        }
    });

});

// Fungsi untuk edit/verifikasi data
function editData(id) {
    $.ajax({
        url: 'action/VerifAdminAction.php?act=get&id= ' +id,
        type: 'POST',
        data: {id: id},
        success: function(response) {
            var data = JSON.parse(response);
            $('#verifikasiModal').modal('show');
            $('#idVerifikasi').val(data.IDVerifikasi);
            $('#statusVerifikasi').val(data.StatusVerifikasi);
            $('#catatan').val(data.Catatan);
        },
        error: function() {
            toastr.error('Gagal mengambil data verifikasi');
        }
    });
}

// Fungsi untuk melihat detail data
function viewData(id) {
    $.ajax({
        url: 'action/VerifAdminAction.php?act=view',
        type: 'POST',
        data: {id: id},
        success: function(response) {
            var data = JSON.parse(response);
            // Tampilkan detail data dalam modal atau halaman baru
            window.open('view_document.php?id=' + id, '_blank');
        },
        error: function() {
            toastr.error('Gagal mengambil detail data');
        }
    });
}

// Fungsi untuk menghapus data
function deleteData(id) {
    if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
        $.ajax({
            url: 'action/VerifAdminAction.php?act=delete&id=' + id,
            type: 'POST',
            data: {id: id},
            success: function(response) {
                $('#verifikasiTable').DataTable().ajax.reload();
                toastr.success('Data berhasil dihapus');
            },
            error: function() {
                toastr.error('Gagal menghapus data');
            }
        });
    }
}

function submitVerifikasi() {
    var id = $('#idVerifikasi').val();
    var status = $('#statusVerifikasi').val();
    var catatan = $('#catatan').val();

    if (!catatan) {
        toastr.warning('Catatan harus diisi');
        return;
    }

    $.ajax({
        url: 'action/VerifAdminAction.php?act=update',
        type: 'POST',
        data: {
            id: id,
            status: status,
            catatan: catatan
        },
        success: function(response) {
            $('#verifikasiModal').modal('hide');
            $('#verifikasiTable').DataTable().ajax.reload();
            toastr.success('Status verifikasi berhasil diperbarui');
        },
        error: function() {
            toastr.error('Terjadi kesalahan saat memperbarui status');
        }
    });
}
</script>