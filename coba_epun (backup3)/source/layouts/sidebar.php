<?php
// Check if the user is logged in
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
} else {
    $username = ''; // Default value if not logged in
}
?>
<div class="sidebar">
    <!-- Sidebar user (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <!-- <div class="image"> 
            <img src="adminlte/dist/img/user2-160x160.jpg" class="img-circle elevation-2" 
alt="User Image"> 
        </div>  -->
        <div class="info">
            <a href="#" class="d-block">Login Sebagai : <?php echo htmlspecialchars($username) ?></a>
        </div>
    </div>

    <!-- SidebarSearch Form -->
    <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
            <input class="form-control form-control-sidebar" type="search"
                placeholder="Search" aria-label="Search">
            <div class="input-group-append"><button class="btn btn-sidebar"><i class="fas 
fa-search fa-fw"></i></button></div>
        </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
            data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class 
            with font-awesome or any other icon font library -->
            <li class="nav-item">
                <a href="index.php" class="nav-link">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>Dashboard</p>
                </a>
            </li>

            <?php
            if ($_SESSION['level'] == 'admin') {
            ?>

                <li class="nav-item">
                    <a href="index.php?page=kategori" class="nav-link">
                        <i class="nav-icon fas fa-bookmark"></i>
                        <p>Kategori Buku</p>
                    </a>
                </li>
                <li class="nav-item ">
                    <a href="index.php?page=buku" class="nav-link">
                        <i class="nav-icon fas fa-book"></i>
                        <p>Buku</p>
                    </a>
                </li>
                <li class="nav-item ">
                    <a href="index.php?page=buku" class="nav-link">
                        <i class="nav-icon fas fa-user  "></i>
                        <p>Upload Bukti</p>
                    </a>
                </li>
                </li>
                <li class="nav-item ">
                    <a href="index.php?page=status2" class="nav-link">
                        <i class="nav-icon fas fa-columns"></i>
                        <p>Status Validasi</p>
                    </a>
                </li>

            <?php
            }
            ?>

            <?php
            if ($_SESSION['level'] == 'mahasiswa') {
            ?>

                <li class="nav-item "> <!--border bottom buat garis paling akhir -->
                    <a href="index.php?page=download" class="nav-link">
                        <i class="nav-icon fas fa-file"></i>
                        <p>Download Surat</p>
                    </a>
                </li>

                <li class="nav-item "> <!--border bottom buat garis paling akhir -->
                    <a href="index.php?page=upload" class="nav-link">
                        <i class="nav-icon fas fa-file"></i>
                        <p>Upload Surat</p>
                    </a>
                </li>

                <li class="nav-item border-bottom"> <!--border bottom buat garis paling akhir -->
                    <a href="index.php?page=status" class="nav-link">
                        <i class="nav-icon fas fa-file"></i>
                        <p>Status Validasi</p>
                    </a>
                </li>

            <?php
            }
            ?>


            <li class="nav-item">
                <a href="action/auth.php?act=logout" class="nav-link">
                    <i class="nav-icon fas fa-sign-out-alt"></i>
                    <p>Logout</p>
                </a>
            </li>

        </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>