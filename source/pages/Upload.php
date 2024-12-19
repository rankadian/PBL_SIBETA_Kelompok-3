<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Upload Berkas</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Upload</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<!-- Main content -->
<section class="content">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Upload Mahasiswa</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-md btn-primary" onclick="tambahData()">
                    Tambah
                </button>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-sm table-bordered table-striped" id="table-data">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Surat</th>
                        <th>Tanggal Dibuat</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</section>

<div class="modal fade" id="form-data" style="display: none;" aria-hidden="true">
    <form action="../action/UploadAction.php?act=save" method="post" id="form-tambah">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Unggah Berkas PDF</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="NamaSurat">Nama Surat</label>
                        <select class="form-control" name="NamaSurat" id="NamaSurat">
                            <option value="" disabled selected>Pilih Surat Yang akan di Upload</option>
                            <option value="SKKM">SKKM</option>
                            <option value="TOEIC">Toeic</option>
                            <option value="PKL">PKL</option>
                            <option value="SKLA">SKLA</option>
                            <option value="KOMPEN">Kompen</option>
                            <option value="PUBLIKASI">Publikasi</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Laporan</label>
                        <input type="date" class="form-control" name="TanggalDibuat" id="TanggalDibuat" required>
                    </div>
                    <div class="form-group">
                        <label for="BuktiSurat">Unggah File</label>
                        <input type="file" class="form-control" name="BuktiSurat" id="BuktiSurat" accept=".pdf, .doc, .docx" required>
                        <small class="form-text text-muted">Hanya file PDF, DOC, dan DOCX yang diperbolehkan.</small>
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

<script>
    function tambahData() {
        $('#form-data').modal('show');
        $('#form-tambah').trigger('reset');
        $('#form-tambah').attr('action', 'action/UploadAction.php?act=save');
    }

    function editData(id) {
        $.ajax({
            url: 'action/UploadAction.php?act=get&id=' + id,
            method: 'GET',
            success: function(response) {
                const data = JSON.parse(response);
                $('#form-data').modal('show');
                $('#form-tambah').attr('action', 'action/UploadAction.php?act=update&id=' + id);
                $('#NamaSurat').val(data.NamaSurat);
                $('#TanggalDibuat').val(data.TanggalDibuat);
            },
            error: function() {
                alert('Gagal mengambil data.');
            }
        });
    }

    function deleteData(id) {
        if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
            $.ajax({
                url: 'action/UploadAction.php?act=delete&id=' + id,
                method: 'POST',
                success: function(response) {
                    const result = JSON.parse(response);
                    if (result.status) {
                        alert('Data berhasil dihapus.');
                        tabelData.ajax.reload();
                    } else {
                        alert('Gagal menghapus data: ' + result.message);
                    }
                },
                error: function() {
                    alert('Terjadi kesalahan saat menghapus data.');
                }
            });
        }
    }

    $(document).ready(function() {
        table = $('#table-data').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "action/UploadAction.php?act=load",
                "type": "GET"
            }
        });
    });

    $('#form-tambah').on('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                const result = JSON.parse(response);
                if (result.status) {
                    $('#form-data').modal('hide');
                    tabelData.ajax.reload();
                } else {
                    alert('Gagal menyimpan data: ' + result.message);
                }
            },
            error: function() {
                alert('Terjadi kesalahan saat menyimpan data.');
            }
        });
    });
</script>