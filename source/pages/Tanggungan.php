<?php

?>

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
                <a href="../index.php?page=upload" class="btn btn-primary">
                    <i class="fas fa-upload"></i> Upload Dokumen
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="tanggunganTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID Upload</th>
                            <th>Nama File</th>
                            <th>Jenis Surat</th>
                            <th>Tanggal Upload</th>
                            <th>Status</th>
                            <th>Catatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</section>

<script>

$(document).ready(function() {
    var table = $('#tanggunganTable').DataTable({
        "processing": true,
        "ajax": {
            "url": "action/TanggunganAction.php?act=load",
            "type": "GET"
        },
        "columns": [
            { "data": 0 },
            { "data": 1 },
            { "data": 2 },
            { "data": 3 },
            { "data": 4 },
            { "data": 5 },
            { "data": 6 },
            { "data": 7 }
        ],
        "responsive": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        }
    });

    // Check document status
    $.ajax({
        url: 'action/TanggunganAction.php?act=check_status',
        type: 'GET',
        success: function(response) {
            var result = JSON.parse(response);
            if (result.status) {
                var documents = result.data;
                var allSubmitted = true;
                for (var doc in documents) {
                    if (!documents[doc]) {
                        allSubmitted = false;
                        break;
                    }
                }
                // if (!allSubmitted) {
                //     Swal.fire({
                //         icon: 'warning',
                //         title: 'Dokumen Belum Lengkap',
                //         text: 'Masih ada dokumen yang belum diupload'
                //     });
                // }
            }
        }
    });
});

function viewDocument(filename) {
    window.open('uploads/documents/' + filename, '_blank');
}

</script>