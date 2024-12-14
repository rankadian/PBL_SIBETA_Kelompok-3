<?php
// Example: Fetch the status from session or database
// Assuming we fetch the status from session (or use database logic as needed)
$status = isset($_SESSION['status']) ? $_SESSION['status'] : 'Menunggu Verifikasi'; // Default value

// Check the status of the file
if ($status == 'Terverifikasi') {
    echo '<a href="' . $filePath . '" class="btn btn-success" download>Download</a>';
} else {
    echo '<span>File belum diverifikasi oleh admin</span>';
}
?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Status Validasi</h3>
    </div>
    <div class="card-body">
        <!-- Display status validation -->
        <h4>Status Validasi</h4>
        <div class="status-container">
            <!-- Display status with icon and styling -->
            <p id="statusTextContainer" class="status-text">
                <i id="statusIcon" class="status-icon"></i>
                <strong id="statusText"><?php echo $status; ?></strong> <!-- Displaying status dynamically -->
            </p>
        </div>
    </div>
</div>

<!-- Modal for status validation change -->
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
    // Function to change status with visual effects
    function changeStatus(newStatus) {
        // Update status directly on the page
        document.getElementById('statusText').textContent = newStatus;

        // Change icon and color based on status
        updateStatusIconAndColor(newStatus);

        // Show modal for confirming the change
        const statusModal = new bootstrap.Modal(document.getElementById('statusModal'));
        statusModal.show();
    }

    // Function to update status based on user selection
    function updateStatus() {
        const selectedStatus = document.getElementById('statusInput').value;

        // Update status on the page
        document.getElementById('statusText').textContent = selectedStatus;

        // Change icon and color according to status
        updateStatusIconAndColor(selectedStatus);

        // Hide the modal after update
        const statusModal = new bootstrap.Modal(document.getElementById('statusModal'));
        statusModal.hide();

        // Optionally send an AJAX request to the server to update the status
        alert('Status berhasil diubah menjadi: ' + selectedStatus);
    }

    // Function to update the icon and color based on status
    function updateStatusIconAndColor(status) {
        const statusText = document.getElementById('statusText');
        const statusIcon = document.getElementById('statusIcon');
        
        switch (status) {
            case 'Terverifikasi':
                statusText.style.color = 'green';
                statusIcon.className = 'bi bi-check-circle';  // Verified icon
                statusIcon.style.color = 'green';
                break;
            case 'Menunggu Verifikasi':
                statusText.style.color = '#FFA500';  // Orange color
                statusIcon.className = 'bi bi-clock';  // Waiting icon
                statusIcon.style.color = '#FFA500';
                break;
            case 'Ditolak':
                statusText.style.color = 'red';
                statusIcon.className = 'bi bi-x-circle';  // Rejected icon
                statusIcon.style.color = 'red';
                break;
            default:
                statusText.style.color = 'black';
                statusIcon.className = ''; // Default icon
                statusIcon.style.color = 'black';
                break;
        }
    }

    // Initialize the default status
    updateStatusIconAndColor('<?php echo $status; ?>');
</script>

<!-- CSS for a more attractive style -->
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
