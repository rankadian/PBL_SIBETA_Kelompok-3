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
    // Dummy data
    $verifikasiMHS = 15; // Dummy number of reports uploaded by students
    $mahasiswa = 4; // Dummy number of students

?>
    <!-- Admin Dashboard Card with Chart -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- VERIFIKASI -->
                <div class="col-lg-2 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3 class="font-weight-bold"><?= $verifikasiMHS; ?></h3>
                            <p class="text-uppercase font-weight-light">VERIFIKASI MAHASISWA</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a href="index.php?page=status2" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- TAMBAH MAHASISWA -->
                <div class="col-lg-2 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3 class="font-weight-bold"><?= $mahasiswa; ?></h3>
                            <p class="text-uppercase font-weight-light">Total mahasiswa</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="index.php?page=tambah1" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>


    </section>
<?php
}

if ($level == 'mahasiswa') {
    // Mahasiswa Dashboard - Pending Verification  
    $upload = 5;   // Dummy value for number of students pending verification
    $pendingPercentage = $upload * 16;

    $verifiedPercentage = 96 - $pendingPercentage;
    if ($pendingPercentage < 96) {
        $note = "Anda belum 100% mengupload . Segera lakukan verifikasi.";
    } else {
        $note = "Anda sudah mengupload semua data.";
    }

?>
    <!-- Mahasiswa Dashboard Card with Chart and Progress Bar -->
    <section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- total upload -->
            <div class="col-lg-2 col-6  ">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3 class="font-weight-bold"><?= $upload; ?></h3>
                        <p class="text-uppercase font-weight-light">TOTAL UPLOAD</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                    <a href="index.php?page=upload" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <!-- catatan -->
            <div class="col-lg-3 col-6  ">
                <div class="alert alert-warning">
                    <h4>Catatan:</h4>
                    <p><?= $note; ?></p>
                </div>
                
            </div>
        </div>
        <div class="text-left">
                    <a href="index.php?page=upload" class="btn btn-primary"><i class="fas fa-upload"></i> Tanggungan Saya!</a>
                </div>
    </div>
</section>

<?php
}
?>