<?php
include('lib/Session.php');
$session = new Session();
if ($session->get('is_login') !== true) {
  header('Location: login.php');
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ADMIN SIBETA</title>
  <!-- Favicons -->
  <link href="../assets/img/sibetaV3.png" rel="icon">


  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="adminlte/plugins/fontawesome-free/css/all.min.css">

  <!-- DataTables -->
  <!-- <link rel="stylesheet" href="adminlte/plugins/datatablesbs4/css/dataTables.bootstrap4.min.css"> 
  <link rel="stylesheet" href="adminlte/plugins/datatablesresponsive/css/responsive.bootstrap4.min.css">  -->
  <link rel="stylesheet" href="adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <link rel="stylesheet" href="../source/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../source/adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">



  <!-- Theme style -->
  <link rel="stylesheet" href="adminlte/dist/css/adminlte.min.css">

  <!-- jQuery -->
  <script src="adminlte/plugins/jquery/jquery.min.js"></script>
</head>

<body class="hold-transition sidebar-mini">
  <!-- Site wrapper -->
  <div class="wrapper">
    <!-- Navbar -->
    <?php include('layouts/header.php'); ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href=" index.php" class="brand-link">
        <img src="../assets/img/sibetaV3.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">SIBETA</span>
      </a>

      <!-- Sidebar -->
      <?php include('layouts/sidebar.php'); ?>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->

      <?php
      $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

      switch (strtolower($page)) {
        case 'dashboard':
          include('pages/dashboard.php');
          break;
        case 'buku':
          include('pages/buku.php');
          break;
        case 'kategori':
          include('pages/kategori.php');
          break;
        case 'user':
          include('pages/user.php');
          break;
        case 'mahasiswa':
          include('pages/mahasiswa.php');
          break;
        case 'upload':
          include('pages/uploadMhs.php');
          break;
        case 'status':
          include('pages/statusMhs.php');
          break;
        default:
          // Cek apakah file 404.php ada
          if (file_exists('pages/404.php')) {
            include('pages/404.php');
          } else {
            echo 'Halaman 404 tidak ditemukan';
          }
          break;
      }

      ?>

      <!-- Main content -->
      <section class="content">

        <!-- Default box -->
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Title</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
          <div class="card-body">
            Selamat Datang Administrator. Anda login sebagai admin.
            <br>
            Start creating your amazing application!
          </div>
          <!-- /.card-body -->
          <div class="card-footer">
            Footer
          </div>
          <!-- /.card-footer-->
        </div>
        <!-- /.card -->

      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <?php include('layouts/footer.php'); ?>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
  </div>
  <!-- ./wrapper -->

  <!-- Bootstrap 4 -->
  <script src="adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->

  <!-- jQuery Validation -->
  <script src="adminlte/plugins/jquery-validation/jquery.validate.min.js"></script>
  <script src="adminlte/plugins/jquery-validation/additional-methods.min.js"></script>
  <script src="adminlte/plugins/jquery-validation/localization/messages_id.min.js"></script>
  <!-- DataTables  & Plugins -->
  <script src="adminlte/plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
  <script src="adminlte/plugins/datatables-
responsive/js/dataTables.responsive.min.js"></script>
  <script src="adminlte/plugins/datatables-
responsive/js/responsive.bootstrap4.min.js"></script>
  <script src="adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
  <script src="adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
  <script src="adminlte/plugins/jszip/jszip.min.js"></script>
  <script src="adminlte/plugins/pdfmake/pdfmake.min.js"></script>
  <script src="adminlte/plugins/pdfmake/vfs_fonts.js"></script>
  <script src="adminlte/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
  <script src="adminlte/plugins/datatables-buttons/js/buttons.print.min.js"></script>
  <script src="adminlte/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>


  <script src="adminlte/dist/js/adminlte.min.js"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="adminlte/dist/js/demo.js"></script>
</body>

</html>