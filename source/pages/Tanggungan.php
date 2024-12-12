<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Daftar Tanggungan</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Tanggungan</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Tanggungan Mahasiswa</h3>
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
                        <th>ID Tanggungan</th>
                        <th>Mahasiswa NIM</th>
                        <th>ID Jenis</th>
                        <th>Status Validasi</th>
                        <th>Berkas</th>
                        <th>Tanggal Ajukan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</section>

<div class="modal fade" id="form-data" style="display: none;" aria-hidden="true">
    <form action="action/tanggunganAction.php?act=save" method="post" id="form-tambah">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Tanggungan</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>ID Tanggungan</label>
                        <input type="text" class="form-control" name="id_tanggungan" id="id_tanggungan">
                    </div>
                    <div class="form-group">
                        <label>Mahasiswa NIM</label>
                        <input type="text" class="form-control" name="mahasiswa_nim" id="mahasiswa_nim">
                    </div>
                    <div class="form-group">
                        <label>ID Jenis</label>
                        <input type="text" class="form-control" name="id_jenis" id="id_jenis">
                    </div>
                    <div class="form-group">
                        <label>Status Validasi</label>
                        <input type="text" class="form-control" name="status_validasi" id="status_validasi">
                    </div>
                    <div class="form-group">
                        <label>Berkas</label>
                        <input type="text" class="form-control" name="berkas" id="berkas">
                    </div>
                    <div class="form-group">
                        <label>Tanggal Ajukan</label>
                        <input type="date" class="form-control" name="tanggal_ajukan" id="tanggal_ajukan">
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
        $('#form-tambah').attr('action', 'action/tanggunganAction.php?act=save');
        $('#id_tanggungan').val('');
        $('#mahasiswa_nim').val('');
        $('#id_jenis').val('');
        $('#status_validasi').val('');
        $('#berkas').val('');
        $('#tanggal_ajukan').val('');
    }

    function editData(id) {
        $.ajax({
            url: 'action/tanggunganAction.php?act=get&id=' + id,
            method: 'post',
            success: function(response) {
                var data = JSON.parse(response);
                $('#form-data').modal('show');
                $('#form-tambah').attr('action', 'action/tanggunganAction.php?act=update&id=' + id);
                $('#id_tanggungan').val(data.id_tanggungan);
                $('#mahasiswa_nim').val(data.mahasiswa_nim);
                $('#id_jenis').val(data.id_jenis);
                $('#status_validasi').val(data.status_validasi);
                $('#berkas').val(data.berkas);
                $('#tanggal_ajukan').val(data.tanggal_ajukan);
            }
        });
    }

    function deleteData(id) {
        if (confirm('Apakah anda yakin?')) {
            $.ajax({
                url: 'action/tanggunganAction.php?act=delete&id=' + id,
                method: 'post',
                success: function(response) {
                    var result = JSON.parse(response);
                    if (result.status) {
                        tabelData.ajax.reload();
                    } else {
                        alert(result.message);
                    }
                }
            });
        }
    }

    var tabelData;
    $(document).ready(function() {
        tabelData = $('#table-data').DataTable({
            ajax: 'action/tanggunganAction.php?act=load',
        });

        $('#form-tambah').validate({
            rules: {
                id_tanggungan: {
                    required: true,
                },
                mahasiswa_nim: {
                    required: true,
                },
                id_jenis: {
                    required: true,
                },
                status_validasi: {
                    required: true,
                },
                berkas: {
                    required: true,
                },
                tanggal_ajukan: {
                    required: true,
                }
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            },
            submitHandler: function(form) {
                $.ajax({
                    url: $(form).attr('action'),
                    method: 'post',
                    data: $(form).serialize(),
                    success: function(response) {
                        var result = JSON.parse(response);
                        if (result.status) {
                            $('#form-data').modal('hide');
                            tabelData.ajax.reload();
                        } else {
                            alert(result.message);
                        }
                    }
                });
            }
        });
    });
</script>