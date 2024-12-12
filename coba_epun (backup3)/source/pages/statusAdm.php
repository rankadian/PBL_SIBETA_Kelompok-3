<!-- Status Validasi Section -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Status Validasi</h3>
    </div>
    <div class="card-body">
        <!-- Tampilkan status validasi berdasarkan level pengguna atau status verifikasi -->
        <h4>Status Validasi</h4>
        <p>Status: <strong id="statusText">Menunggu Verifikasi</strong></p>

        <!-- Tombol untuk mengubah status -->
        <button class="btn btn-success" onclick="changeStatus('Terverifikasi')">Set Terverifikasi</button>
        <button class="btn btn-warning" onclick="changeStatus('Menunggu Verifikasi')">Set Menunggu Verifikasi</button>
        <button class="btn btn-danger" onclick="changeStatus('Ditolak')">Set Ditolak</button>

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
    </div>
</div>

<script>
    // Fungsi untuk mengubah status
    function changeStatus(newStatus) {
        // Update status secara langsung di halaman
        document.getElementById('statusText').textContent = newStatus;

        // Tampilkan modal untuk konfirmasi perubahan status
        const statusModal = new bootstrap.Modal(document.getElementById('statusModal'));
        statusModal.show();
    }

    // Fungsi untuk memperbarui status berdasarkan pilihan pengguna
    function updateStatus() {
        const selectedStatus = document.getElementById('statusInput').value;

        // Perbarui status di halaman
        document.getElementById('statusText').textContent = selectedStatus;

        // Sembunyikan modal setelah update
        const statusModal = new bootstrap.Modal(document.getElementById('statusModal'));
        statusModal.hide();

        // Kirim permintaan ke server untuk memperbarui status (misalnya via AJAX)
        alert('Status berhasil diubah menjadi: ' + selectedStatus);
    }
</script>