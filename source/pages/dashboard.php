<?php

include_once(__DIR__ . '/../lib/Session.php');

$session = new Session();

if ($session->get('is_login') !== true) {
    header('Location: login.php');
}

$level = $session->get('level');
$username = $session->get('username'); // Assuming you have stored the username in the session  

include_once(__DIR__ . '/../model/GlobalModel.php');

$global = new GlobalModel();


if ($level == 'admin') {
    // Admin Dashboard - Total Reports  
    // Dumm6y data
    $total = $global->getCountData('TB_Mahasiswa');
    $upload = $global->getCountData('TB_Upload')
?>
    <!-- Admin Dashboard Card with Chart -->    
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- VERIFIKASI -->
                <div class="col-lg-2 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3 class="font-weight-bold"><?= $upload; ?></h3>
                            <p class="text-uppercase font-weight-light">VERIFIKASI MAHASISWA</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a href="index.php?page=verif" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- TAMBAH MAHASISWA -->
                <div class="col-lg-2 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3 class="font-weight-bold"><?= $total; ?></h3>
                            <p class="text-uppercase font-weight-light">Total mahasiswa</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="index.php?page=verif" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>


    </section>
<?php
}

if ($level == 'mahasiswa') {
    // Mahasiswa Dashboard - Pending Verification  
    // $upload = 5;   // Dummy value for number of students pending verification
    // $pendingPercentage = $upload * 16;

    // $verifiedPercentage = 96 - $pendingPercentage;
    // if ($pendingPercentage < 96) {
    //     $note = "Anda belum 100% mengupload . Segera lakukan verifikasi.";
    // } else {
    //     $note = "Anda sudah mengupload semua data.";
    // }

?>
    <!-- Mahasiswa Dashboard Card with Chart and Progress Bar -->
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
                <a href="index.php?page=upload" class="btn btn-primary">
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

<?php
}
?>