<!-- Status Validasi Section -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Status Validasi</h3>
    </div>
    <div class="card-body">
        <!-- Tampilkan status validasi -->
        <h4>Status Validasi</h4>
        <div class="status-container">
            <!-- Menampilkan status dengan ikon dan styling -->
            <p id="statusTextContainer" class="status-text">
                <i id="statusIcon" class="status-icon"></i>
                <strong id="statusText">Menunggu Verifikasi</strong>
            </p>
        </div>

        <!-- Tidak ada tombol untuk mengubah status pada tampilan admin, seperti tampilan mahasiswa -->
        <!-- Hanya menampilkan status tanpa kontrol perubahan -->
    </div>
</div>

<!-- Modal untuk perubahan status validasi -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusModalLabel">Perubahan Status Validasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="statusInput">Pilih Status</label>
                    <select class="form-control" id="statusInput" name="status">
                        <option value="Terverifikasi">Terverifikasi</option>
                        <option value="Menunggu Verifikasi">Menunggu Verifikasi</option>
                        <option value="Ditolak">Ditolak</option>
                    </select>
                </div>
                <button type="button" class="btn btn-primary mt-3" onclick="updateStatus()">Update Status</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Fungsi untuk mengubah status dengan efek visual
    function changeStatus(newStatus) {
        // Update status secara langsung di halaman
        document.getElementById('statusText').textContent = newStatus;

        // Ganti ikon dan warna sesuai status
        updateStatusIconAndColor(newStatus);

        // Tampilkan modal untuk konfirmasi perubahan status
        const statusModal = new bootstrap.Modal(document.getElementById('statusModal'));
        statusModal.show();
    }

    // Fungsi untuk memperbarui status berdasarkan pilihan pengguna
    function updateStatus() {
        const selectedStatus = document.getElementById('statusInput').value;

        // Perbarui status di halaman
        document.getElementById('statusText').textContent = selectedStatus;

        // Ganti ikon dan warna sesuai status
        updateStatusIconAndColor(selectedStatus);

        // Sembunyikan modal setelah update
        const statusModal = new bootstrap.Modal(document.getElementById('statusModal'));
        statusModal.hide();

        // Kirim permintaan ke server untuk memperbarui status (misalnya via AJAX)
        alert('Status berhasil diubah menjadi: ' + selectedStatus);
    }

    // Fungsi untuk mengganti ikon dan warna status
    function updateStatusIconAndColor(status) {
        const statusText = document.getElementById('statusText');
        const statusIcon = document.getElementById('statusIcon');
        
        switch (status) {
            case 'Terverifikasi':
                statusText.style.color = 'green';
                statusIcon.className = 'bi bi-check-circle';  // Ikon verifikasi
                statusIcon.style.color = 'green';
                break;
            case 'Menunggu Verifikasi':
                statusText.style.color = '#FFA500';  // Oranye
                statusIcon.className = 'bi bi-clock';  // Ikon menunggu
                statusIcon.style.color = '#FFA500';
                break;
            case 'Ditolak':
                statusText.style.color = 'red';
                statusIcon.className = 'bi bi-x-circle';  // Ikon ditolak
                statusIcon.style.color = 'red';
                break;
            default:
                statusText.style.color = 'black';
                statusIcon.className = ''; // Default icon
                statusIcon.style.color = 'black';
                break;
        }
    }

    // Inisialisasi status default
    updateStatusIconAndColor('Menunggu Verifikasi');
</script>

<!-- CSS untuk menambahkan style lebih menarik -->
<style>
    .status-container {
        margin-top: 20px;
        padding: 15px;
        background-color: #f8f9fa;
        border-radius: 8px;
        display: flex;
        align-items: center;
        transition: background-color 0.3s;
    }

    .status-text {
        font-size: 1.2em;
        font-weight: bold;
        display: flex;
        align-items: center;
    }

    .status-icon {
        margin-right: 10px;
        font-size: 1.5em;
    }

    .modal-content {
        border-radius: 8px;
    }

    .modal-header {
        background-color: #007bff;
        color: white;
    }

    .form-control {
        border-radius: 8px;
    }
</style>

<!-- Optional: Import Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
