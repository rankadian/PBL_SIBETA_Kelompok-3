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
    <form method="post" id="form-tambah" enctype="multipart/form-data">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Unggah Berkas PDF</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="Jenis_Surat">Nama Surat</label>
                        <select class="form-control" name="Jenis_Surat" id="Jenis_Surat" required>
                            <option value="" disabled selected>Pilih Surat Yang akan di Upload</option>
                            <option value="ukt">UKT</option>
                            <option value="skkm">SKKM</option>
                            <option value="Toeic">TOEIC</option>
                            <option value="Publikasi">Publikasi</option>
                            <option value="Skla">SKLA</option>
                            <option value="kompensasi">Kompensasi</option>
                        </select>

                    </div>
                    <div class="form-group">
                        <label>Tanggal Laporan</label>
                        <input type="date" class="form-control" name="TanggalDibuat" id="TanggalDibuat" required>
                    </div>
                    <div class="form-group">
                        <label for="Nama_file">Unggah File</label>
                        <input type="file" class="form-control" name="FilePath" id="Nama_file" accept=".pdf, .doc, .docx" required>
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
                $('#Jenis_Surat').val(data.Jenis_Surat);
                $('#TanggalDibuat').val(data.TanggalDibuat);
            },
            error: function() {
                alert('Gagal mengambil data.');
            }
        });
    }

    // Handle form submission
    $('#form-tambah').on('submit', function(e) {
        e.preventDefault();
        $(this).find('button[type="submit"]').prop('disabled', true);
        
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: new FormData(this),
            processData: false,
            contentType: false,
            success: function(response) {
                try {
                    const result = JSON.parse(response);
                    if (result.status) {
                        $('#form-data').modal('hide');
                        location.reload();
                    } else {
                        alert(result.message);
                    }
                } catch (e) {
                    console.error(e, response);
                    alert('Terjadi kesalahan saat memproses data');
                }
            },
            error: function() {
                alert('Gagal menyimpan data');
            },
            complete: function() {
                $('#form-tambah').find('button[type="submit"]').prop('disabled', false);
            }
        });
    });

    // Initialize DataTable
    let table;
    $(document).ready(function() {
        table = $('#table-data').DataTable({
            processing: true,
            serverSide: true,
            ajax: 'action/UploadAction.php?act=load'
        });
    });
</script>