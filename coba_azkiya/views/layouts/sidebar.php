<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Ambil level dari session
$level = isset($_SESSION['level']) ? $_SESSION['level'] : null;

// Fallback jika level tidak ada
if (!$level) {
    echo "<p>level tidak dikenali. Harap login ulang.</p>";
    exit;
}

$current_page = basename($_SERVER['SCRIPT_NAME']);
?>
<!-- Sidebar -->
<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="dark2">
            <a href="index.html" class="logo">
                <img
                    src="../../../assets/img/brand.png"
                    alt="navbar brand"
                    class="navbar-brand"
                    height="50" />
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
        <!-- End Logo Header -->
    </div>
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">MENU</h4>
                </li>
                <?php if ($level === 'mahasiswa') { ?>
                    <li class="nav-item <?= $current_page == 'dashboard.php' ? 'active' : '' ?>">
                        <a href="dashboard.php">
                            <i class="fas fa-home"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item <?= $current_page == 'cekTanggungan.php' ? 'active' : '' ?>">
                        <a href="cekTanggungan.php">
                            <i class="fas fa-folder-open"></i>
                            <p>Cek Tanggungan</p>
                        </a>
                    </li>
                    <li class="nav-item <?= $current_page == 'uploadBerkas.php' ? 'active' : '' ?>">
                        <a href="uploadBerkas.php">
                            <i class="fas fa-file-upload"></i>
                            <p>Upload Berkas</p>
                        </a>
                    </li>
                    <li class="nav-item <?= $current_page == 'cetakBebasTanggungan.php' ? 'active' : '' ?>">
                        <a href="cetakBebasTanggungan.php">
                            <i class="fas fa-cloud-download-alt"></i>
                            <p>Cetak Bebas Tanggungan</p>
                        </a>
                    </li>
                    <li class="nav-section">
                        <span class="sidebar-mini-icon">
                            <i class="fa fa-ellipsis-h"></i>
                        </span>
                        <h4 class="text-section">OTHERS</h4>
                    </li>
                    <li class="nav-item <?= $current_page == 'settings.php' ? 'active' : '' ?>">
                        <a href="settings.php">
                            <i class="fas fa-cog"></i>
                            <p>Settings</p>
                        </a>
                    </li>
                    <li class="nav-item <?= $current_page == 'account.php' ? 'active' : '' ?>">
                        <a href="account.php">
                            <i class="fas fa-user"></i>
                            <p>Account</p>
                        </a>
                    </li>
                <?php } elseif ($level === 'admin') { ?>
                    <li class="nav-item <?= $current_page == 'dashboard.php' ? 'active' : '' ?>">
                        <a href="dashboard.php">
                            <i class="fas fa-home"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item <?= in_array($current_page, ['berkasPerpus.php', 'berkasJurusan.php', 'berkasAkademik.php']) ? 'active' : '' ?> dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#verifikasiSubmenu" aria-expanded="false">
                            <i class="fas fa-file-signature"></i>
                            <p>Verifikasi Tanggungan</p>
                        </a>
                        <div id="verifikasiSubmenu" class="collapse <?= in_array($current_page, ['berkasPerpus.php', 'berkasJurusan.php', 'berkasAkademik.php']) ? 'show' : '' ?>">
                            <ul class="nav flex-column ms-3">
                                <li class="nav-item">
                                    <a class="nav-link <?= $current_page == 'berkasPerpus.php' ? 'active' : '' ?>" href="berkasPerpus.php">
                                        Berkas Perpustakaan
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?= $current_page == 'berkasJurusan.php' ? 'active' : '' ?>" href="berkasJurusan.php">
                                        Berkas Jurusan
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?= $current_page == 'berkasAkademik.php' ? 'active' : '' ?>" href="berkasAkademik.php">
                                        Berkas Akademik
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item <?= $current_page == 'uploadTemplate.php' ? 'active' : '' ?>">
                        <a href="uploadTemplate.php">
                            <i class="fas fa-file-upload"></i>
                            <p>Upload Template Surat</p>
                        </a>
                    </li>
                    <li class="nav-section">
                        <span class="sidebar-mini-icon">
                            <i class="fa fa-ellipsis-h"></i>
                        </span>
                        <h4 class="text-section">OTHERS</h4>
                    </li>
                    <li class="nav-item <?= $current_page == 'settings.php' ? 'active' : '' ?>">
                        <a href="settings.php">
                            <i class="fas fa-cog"></i>
                            <p>Settings</p>
                        </a>
                    </li>
                <?php } ?>
                <li class="nav-item">
                    <a href="../../../controllers/logout.php">
                        <i class="fas fa-sign-out-alt"></i>
                        <p>Log Out</p>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- End Sidebar -->