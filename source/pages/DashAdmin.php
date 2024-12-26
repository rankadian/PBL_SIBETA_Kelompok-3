<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Dashboard Admin</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Beranda</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<!-- Main content -->
<section class="content">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Berkas Mahasiswa</h3>
        </div>
        <div class="card-body">
            <table class="table table-sm table-bordered table-striped" id="table-data">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Berkas</th>
                        <th>Tanggal Unggah</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</section>

<script>
$(document).ready(function() {
    loadData();
});

function loadData() {
    $.ajax({
        url: 'action/DashAdminAction.php?act=load',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            let html = '';
            if (response.data) {
                response.data.forEach((item, index) => {
                    html += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${item.NamaSurat}</td>
                            <td>${item.TanggalUpload}</td>
                            <td>${item.StatusVerifikasi || 'Menunggu Verifikasi'}</td>
                            <td>
                                <button class="btn btn-sm btn-primary" onclick="tambahKeVerifikasi('${item.IDUpload}')">
                                    Tambah ke Verifikasi
                                </button>
                            </td>
                        </tr>
                    `;
                });
            }
            $('#table-data tbody').html(html);
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memuat data');
        }
    });
}

function tambahKeVerifikasi(idUpload) {
    $.ajax({
        url: 'action/DashAdminAction.php?act=create',
        type: 'POST',
        data: {
            IDUpload: idUpload
        },
        dataType: 'json',
        success: function(response) {
            if (response.status) {
                alert('Berhasil menambahkan data ke verifikasi');
                loadData();
            } else {
                alert(response.message || 'Gagal menambahkan data ke verifikasi');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menambahkan data');
        }
    });
}
</script>