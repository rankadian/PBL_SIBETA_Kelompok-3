<head>
<link rel="stylesheet" href="../source/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="../source/adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
</head>
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
                <button type="button" class="btn btn-md btn-primary" >
                  <a href="../index.php?page=upload">UPLOAD</a>
                </button>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-sm table-bordered table-striped" id="table-data">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Pengajuan ID</th>
                        <th>NIM Mahasiswa</th>
                        <th>Nama Mahasiswa</th>
                        <th>Nama Surat</th>
                        <th>Status Pengajuan</th>
                        <th>Tanggal Pengajuan</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</section>


<script>
    
    function tambahData() {
        $('#form-data').modal('show');
        $('#form-tambah').attr('action', 'pages/Upload.php');
        $('#pengajuan_id').val('');
        $('#nim').val('');
        $('#nama_surat').val('');
        $('#status_pengajuan').val('');
        $('#tanggal_pengajuan').val('');
    }

    function editData(id) {
        $.ajax({
            url: 'action/tanggunganAction.php?act=get&id=' + id,
            method: 'get',
            success: function(response) {
                var data = JSON.parse(response);
                $('#form-data').modal('show');
                $('#form-tambah').attr('action', 'action/tanggunganAction.php?act=update&id=' + id);
                $('#pengajuan_id').val(data.PengajuanID);  // Assuming 'PengajuanID' is the correct field
                $('#nim').val(data.NIM); 
                $('#nama_surat').val(data.NamaSurat);
                $('#status_pengajuan').val(data.StatusPengajuan);
                $('#tanggal_pengajuan').val(data.TanggalPengajuan);
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
                nim: {
                    required: true,
                },
                nama_surat: {
                    required: true,
                },
                status_pengajuan: {
                    required: true,
                },
                tanggal_pengajuan: {
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
