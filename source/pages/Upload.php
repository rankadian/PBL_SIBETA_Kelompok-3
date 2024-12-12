<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Mahasiswa</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
</head>

<body>
    <div class="container">
        <h1>Upload Mahasiswa</h1>
        <!-- Form Upload -->
        <h4>Upload Bukti Laporan</h4>

        <!-- Form Upload Laporan Tugas Akhir -->
        <form id="uploadLaporanForm" action="action/UploadAction.php?act=save" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="laporan_tugas_akhir">Upload Laporan Tugas Akhir (PDF)</label>
                <input type="file" class="form-control" name="laporan_tugas_akhir" id="laporan_tugas_akhir" accept=".pdf" required>
            </div>
            <div class="form-group">
                <input type="hidden" name="IDSurat" value="123"> <!-- Ensure the ID is correctly passed -->
            </div>
            <button type="submit" class="btn btn-primary">Submit Laporan</button>
        </form>


        <hr>

        <!-- Form Upload Program/Aplikasi -->
        <form id="uploadProgramForm" action="action/UploadAction.php?act=save" method="post" enctype="multipart/form-data">
            <div class="form-group" id="form-data">
                <label for="program_aplikasi">Upload Program/Aplikasi (ZIP/RAR)</label>
                <input type="file" class="form-control" name="program_aplikasi" id="program_aplikasi" accept=".zip,.rar" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit Program</button>
        </form>
    </div>

    <script>
        // Fungsi untuk mengunggah file laporan tugas akhir
        function uploadLaporan() {
            var formData = new FormData($('#uploadLaporanForm')[0]); // Create a FormData object from the form
            $.ajax({
                url: $('#uploadLaporanForm').attr('action'), // The URL where the form is submitted
                type: 'POST', // POST method
                data: formData, // Form data
                processData: false, // Don't let jQuery process the data
                contentType: false, // Don't let jQuery set content type
                success: function(response) {
                    var result = JSON.parse(response);
                    if (result.status) {
                        alert('Laporan berhasil diunggah!');
                        $('#uploadLaporanForm')[0].reset(); // Reset form after success
                    } else {
                        alert('Gagal mengunggah laporan: ' + result.message);
                    }
                },
                error: function(xhr, status, error) {
                    alert('Terjadi kesalahan: ' + error);
                }
            });
        }


        // Fungsi untuk mengunggah file program/aplikasi
        function uploadProgram() {
            var formData = new FormData($('#uploadProgramForm')[0]);
            $.ajax({
                url: $('#uploadProgramForm').attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    var result = JSON.parse(response);
                    if (result.status) {
                        alert('Program berhasil diunggah!');
                        $('#uploadProgramForm')[0].reset(); // Reset form
                    } else {
                        alert('Gagal mengunggah program: ' + result.message);
                    }
                },
                error: function(xhr, status, error) {
                    alert('Terjadi kesalahan: ' + error);
                }
            });
        }

        // Event handler untuk tombol submit
        $(document).ready(function() {
            $('#uploadLaporanForm').on('submit', function(e) {
                e.preventDefault();
                uploadLaporan();
            });

            $('#uploadProgramForm').on('submit', function(e) {
                e.preventDefault();
                uploadProgram();
            });
        });
    </script>
</body>

</html>