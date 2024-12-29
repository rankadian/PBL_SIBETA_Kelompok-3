<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Upload Berkas</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Upload</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<!-- Main content -->
<section class="content">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Upload Mahasiswa</h3>
            <div class="card-tools">
            </div>
        </div>
        <div class="card-body">
            <form action="action/UploadAction.php?act=save" method="post" id="form-tambah" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="file_skla">Scan Surat SKLA</label>
                    <input type="file" class="form-control" name="file_skla" id="file_skla" accept=".pdf, .doc, .docx" required>
                    <small class="form-text text-muted">Hanya file PDF, DOC, dan DOCX yang diperbolehkan.</small>
                </div>
                <div class="form-group">
                    <label for="file_kompensasi">Scan Surat Kompensasi</label>
                    <input type="file" class="form-control" name="file_kompensasi" id="file_kompensasi" accept=".pdf, .doc, .docx" required>
                    <small class="form-text text-muted">Hanya file PDF, DOC, dan DOCX yang diperbolehkan.</small>
                </div>
                <div class="form-group">
                    <label for="file_ukt">Scan Surat Lunas UKT</label>
                    <input type="file" class="form-control" name="file_ukt" id="file_ukt" accept=".pdf, .doc, .docx" required>
                    <small class="form-text text-muted">Hanya file PDF, DOC, dan DOCX yang diperbolehkan.</small>
                </div>
                <div class="form-group">
                    <label for="file_skkm">Scan Surat SKKM</label>
                    <input type="file" class="form-control" name="file_skkm" id="file_skkm" accept=".pdf, .doc, .docx" required>
                    <small class="form-text text-muted">Hanya file PDF, DOC, dan DOCX yang diperbolehkan.</small>
                </div>
                <div class="form-group">
                    <label for="file_toeic">Scan TOEIC</label>
                    <input type="file" class="form-control" name="file_toeic" id="file_toeic" accept=".pdf, .doc, .docx" required>
                    <small class="form-text text-muted">Hanya file PDF, DOC, dan DOCX yang diperbolehkan.</small>
                </div>
                <div class="form-group">
                    <label for="file_publikasi">Scan Publikasi</label>
                    <input type="file" class="form-control" name="file_publikasi" id="file_publikasi" accept=".pdf, .doc, .docx" required>
                    <small class="form-text text-muted">Hanya file PDF, DOC, dan DOCX yang diperbolehkan.</small>
                </div>
                <button type="submit" class="btn btn-md btn-primary">Upload Semua File</button>
            </form>
        </div>
    </div>
</section>

<script>
$(document).ready(function() {
    $('#form-tambah').on('submit', function(e) {
        e.preventDefault();

        // Disable submit button
        $(this).find('button[type="submit"]').prop('disabled', true);

        // Create FormData object
        var formData = new FormData(this);

        // Check if all files are selected
        var fileInputs = $(this).find('input[type="file"]');
        var allFilesSelected = true;
        var missingFiles = [];

        fileInputs.each(function() {
            if (!this.files || !this.files[0]) {
                allFilesSelected = false;
                missingFiles.push($(this).prev('label').text());
            }
        });

        if (!allFilesSelected) {
            Swal.fire({
                icon: 'warning',
                title: 'File Belum Lengkap!',
                html: 'Mohon pilih file berikut:<br>' + missingFiles.join('<br>'),
                showConfirmButton: true
            });
            $(this).find('button[type="submit"]').prop('disabled', false);
            return;
        }

        // Validate file sizes and types
        var maxSize = 10 * 1024 * 1024; // 10MB
        var allowedTypes = ['pdf', 'doc', 'docx'];
        var invalidFiles = [];

        fileInputs.each(function() {
            var file = this.files[0];
            var fileSize = file.size;
            var fileType = file.name.split('.').pop().toLowerCase();
            
            if (fileSize > maxSize) {
                invalidFiles.push($(this).prev('label').text() + ' (ukuran terlalu besar, maksimal 10MB)');
            }
            
            if (!allowedTypes.includes(fileType)) {
                invalidFiles.push($(this).prev('label').text() + ' (tipe file tidak diizinkan)');
            }
        });

        if (invalidFiles.length > 0) {
            Swal.fire({
                icon: 'error',
                title: 'File Tidak Valid!',
                html: 'File berikut tidak memenuhi syarat:<br>' + invalidFiles.join('<br>'),
                showConfirmButton: true
            });
            $(this).find('button[type="submit"]').prop('disabled', false);
            return;
        }

        // Show loading indicator
        Swal.fire({
            title: 'Sedang Mengupload...',
            html: 'Mohon tunggu sebentar<br><small>Proses ini mungkin memerlukan beberapa saat</small>',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Send AJAX request
        $.ajax({
            url: 'action/UploadAction.php?act=save',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                try {
                    var result = JSON.parse(response);
                    if (result.status) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: result.message,
                            showConfirmButton: true
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.reload();
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            html: result.message.replace(/\n/g, '<br>'),
                            showConfirmButton: true
                        });
                    }
                } catch (e) {
                    console.error('Error parsing response:', e);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Terjadi kesalahan sistem',
                        showConfirmButton: true
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Gagal menghubungi server',
                    showConfirmButton: true
                });
            },
            complete: function() {
                // Re-enable submit button
                $('#form-tambah').find('button[type="submit"]').prop('disabled', false);
            }
        });
    });

    // Preview file when selected
    $('input[type="file"]').on('change', function() {
        var file = this.files[0];
        if (file) {
            var fileSize = (file.size / 1024 / 1024).toFixed(2); // Convert to MB
            var fileType = file.name.split('.').pop().toLowerCase();
            var validType = ['pdf', 'doc', 'docx'].includes(fileType);
            
            $(this).next('small').html(
                'File terpilih: ' + file.name + ' (' + fileSize + ' MB)<br>' +
                '<span class="' + (validType ? 'text-success' : 'text-danger') + '">' +
                (validType ? '✓ Tipe file valid' : '✗ Tipe file tidak valid') + '</span>'
            );
        }
    });
});
</script>