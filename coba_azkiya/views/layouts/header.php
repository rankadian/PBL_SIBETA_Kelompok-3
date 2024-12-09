<div class="main-header">
    <div class="main-header-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="dark2">
            <a href="index.html" class="logo">
                <img
                    src="../../../assets/img/brand.png"
                    alt="navbar brand"
                    class="navbar-brand"
                    height="20" />
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
    <!-- Navbar Header -->
    <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
        <div class="container-fluid">
            <nav class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex">
                <div>
                    <span>
                        <a href="">
                            <span id="currentDate"></span> <!-- This will display the current date -->
                        </a>
                    </span>
                </div>
            </nav>
            <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                <li class="nav-item topbar-icon dropdown hidden-caret">
                    <a class="nav-link dropdown-toggle" href="#" id="notifDropdown" level="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-bell"></i>
                        <span class="notification">
                            <?= $tanggunganCount['belum_terpenuhi'] > 0 ? $tanggunganCount['belum_terpenuhi'] : ""; ?>
                        </span>
                    </a>
                    <ul class="dropdown-menu notif-box animated fadeIn" aria-labelledby="notifDropdown">
                        <?php if (!$allFulfilled): ?>
                            <li>
                                <div class="dropdown-title">Anda memiliki <?= $tanggunganCount['belum_terpenuhi'] ?> tanggungan yang belum terpenuhi.</div>
                            </li>
                            <li>
                                <div class="notif-scroll scrollbar-outer">
                                    <div class="notif-center">
                                        <a href="cekTanggungan.php">
                                            <div class="notif-icon notif-warning">
                                                <i class="fa fa-exclamation-circle"></i>
                                            </div>
                                            <div class="notif-content">
                                                <span class="block">Selesaikan tanggungan Anda segera.</span>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </li>
                        <?php else: ?>
                            <li>
                                <div class="dropdown-title">Semua tanggungan sudah terpenuhi. Selamat!</div>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
                <li class="nav-item topbar-user dropdown hidden-caret">
                    <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                        <div class="avatar-sm">
                            <img src="../../../assets/img/profile.jpg" alt="..." class="avatar-img rounded-circle" />
                        </div>
                        <span class="profile-username">
                            <span class="op-7">HI,</span>
                            <span class="fw-bold"><?php echo $nama ?></span>
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-user animated fadeIn">
                        <div class="dropdown-user-scroll scrollbar-outer">
                            <li>
                                <div class="user-box">
                                    <div class="avatar-lg">
                                        <img src="assets/img/profile.jpg" alt="image profile" class="avatar-img rounded" />
                                    </div>
                                    <div class="u-text">
                                        <h4><?php echo $panggilan ?></h4>
                                        <p class="text-muted"><?php echo $nim ?></p>
                                        <a href="account.php" class="btn btn-xs btn-secondary btn-sm">View Profile</a>
                                    </div>
                                </div>
                            </li>
                        </div>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
    <!-- End Navbar -->
</div>

<!-- JavaScript to display the current date -->
<script>
    function formatDate(date) {
        const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        const day = days[date.getDay()];
        const month = months[date.getMonth()];
        const dayOfMonth = date.getDate();
        const year = date.getFullYear();

        return `${day}, ${month} ${dayOfMonth}, ${year}`;
    }

    document.getElementById('currentDate').textContent = formatDate(new Date());
</script>
