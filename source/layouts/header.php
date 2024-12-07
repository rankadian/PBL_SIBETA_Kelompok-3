<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="#" class="nav-link">Home</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="#" class="nav-link">Contact</a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Navbar Search -->
        <li class="nav-item">
            <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                <i class="fas fa-search"></i>
            </a>
            <div class="navbar-search-block">
                <form class="form-inline">
                    <div class="input-group input-group-sm">
                        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-navbar" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                            <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </li>

        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                <span class="badge badge-warning navbar-badge" id="notification-count">0</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header" id="notification-header">0 Notifications</span>
                <div class="dropdown-divider"></div>
                <!-- Notifikasi akan dimasukkan di sini -->
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                <i class="fas fa-th-large"></i>
            </a>
        </li>
    </ul>
    <script>
        // Fungsi untuk menghitung waktu relatif
        function timeAgo(time) {
            const now = new Date();
            const diffInSeconds = Math.floor((now - new Date(time)) / 1000);

            const minute = 60;
            const hour = 60 * minute;
            const day = 24 * hour;
            const week = 7 * day;
            const month = 30 * day;
            const year = 365 * day;

            if (diffInSeconds < minute) {
                return `${diffInSeconds} detik yang lalu`;
            } else if (diffInSeconds < hour) {
                const minutes = Math.floor(diffInSeconds / minute);
                return `${minutes} menit yang lalu`;
            } else if (diffInSeconds < day) {
                const hours = Math.floor(diffInSeconds / hour);
                return `${hours} jam yang lalu`;
            } else if (diffInSeconds < week) {
                const days = Math.floor(diffInSeconds / day);
                return `${days} hari yang lalu`;
            } else if (diffInSeconds < month) {
                const weeks = Math.floor(diffInSeconds / week);
                return `${weeks} minggu yang lalu`;
            } else if (diffInSeconds < year) {
                const months = Math.floor(diffInSeconds / month);
                return `${months} bulan yang lalu`;
            } else {
                const years = Math.floor(diffInSeconds / year);
                return `${years} tahun yang lalu`;
            }
        }

        // Fungsi untuk mengambil dan menampilkan notifikasi
        function fetchNotifications() {
            $.ajax({
                url: 'get_notifications.php', // URL untuk mendapatkan data notifikasi
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    // Update jumlah notifikasi
                    const notificationCount = response.length;
                    $('#notification-count').text(notificationCount);
                    $('#notification-header').text(notificationCount + ' Notifications');

                    // Kosongkan dropdown sebelumnya dan tambahkan notifikasi baru
                    const dropdownMenu = $('.dropdown-menu');
                    dropdownMenu.find('.dropdown-item').not('.dropdown-header').remove();

                    response.forEach(function(notification) {
                        const relativeTime = timeAgo(notification.timestamp); // Waktu relatif
                        const notificationItem = `
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-bell mr-2"></i> ${notification.message}
                            <span class="float-right text-muted text-sm">${relativeTime}</span>
                        </a>
                    `;
                        dropdownMenu.append(notificationItem); // Menambahkan item notifikasi
                    });
                },
                error: function() {
                    console.error('Gagal mengambil notifikasi');
                }
            });
        }

        // Lakukan polling setiap 5 detik untuk memperbarui notifikasi
        setInterval(fetchNotifications, 5000);

        // Panggil pertama kali saat halaman dimuat
        fetchNotifications();
    </script>

</nav>
<!-- /.navbar -->