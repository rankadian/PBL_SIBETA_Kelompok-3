

<section class="content">
    <div class="container-fluid">
        <!-- Dashboard Header -->
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Beranda</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Beranda</li>
                </ol>
            </div>
        </div>
    </div>

    <!-- File Upload Section -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Form Upload Bukti Validasi</h3>
        </div>
        <div class="card-body">
            <h4>Upload Bukti Laporan</h4>
            <form id="uploadForm" action="uploadAction.php" method="post" enctype="multipart/form-data">
                
                <!-- Upload Laporan Tugas Akhir (PDF/DOCX) -->
                <!-- <div class="form-group">
                    <label>Upload Laporan Tugas Akhir</label>
                    <input type="file" class="form-control" name="laporan_tugas_akhir" id="laporan_tugas_akhir" accept=".docx, .pdf, .jpg, .jpeg">
                    <button type="button" class="btn btn-sm btn-secondary" onclick="saveFile('laporan_tugas_akhir')">Save</button>
                    <button type="submit" class="btn btn-primary" id="submit_laporan_tugas_akhir" disabled>Submit</button>
                </div> -->
                
                <!-- Upload Surat Publikasi (PDF/DOCX) -->
                <!-- <div class="form-group">
                    <label>Upload Surat Publikasi</label>
                    <input type="file" class="form-control" name="surat_publikasi" id="surat_publikasi" accept=".docx, .pdf, .jpg, .jpeg">
                    <button type="button" class="btn btn-sm btn-secondary" onclick="saveFile('surat_publikasi')">Save</button>
                    <button type="submit" class="btn btn-primary" id="submit_surat_publikasi" disabled>Submit</button>
                </div> -->

                <!-- Upload Surat Bebas Kompen (PDF/DOCX) -->
                <div class="form-group">
                    <label>Upload Surat Bebas Kompen</label>
                    <input type="file" class="form-control" name="surat_bebas_kompen" id="surat_bebas_kompen" accept=".docx, .pdf, .jpg, .jpeg">
                    <button type="button" class="btn btn-sm btn-secondary" onclick="saveFile('surat_bebas_kompen')">Save</button>
                    <button type="submit" class="btn btn-primary" id="submit_surat_bebas_kompen" disabled>Submit</button>
                </div>

                <!-- Upload Surat Validasi PKL (PDF/DOCX) -->
                <div class="form-group">
                    <label>Upload Surat Validasi PKL</label>
                    <input type="file" class="form-control" name="surat_validasi_pkl" id="surat_validasi_pkl" accept=".docx, .pdf, .jpg, .jpeg">
                    <button type="button" class="btn btn-sm btn-secondary" onclick="saveFile('surat_validasi_pkl')">Save</button>
                    <button type="submit" class="btn btn-primary" id="submit_surat_validasi_pkl" disabled>Submit</button>
                </div>

                <!-- Upload Surat SKLA (PDF/DOCX) -->
                <div class="form-group">
                    <label>Upload Surat SKLA</label>
                    <input type="file" class="form-control" name="surat_skla" id="surat_skla" accept=".docx, .pdf, .jpg, .jpeg">
                    <button type="button" class="btn btn-sm btn-secondary" onclick="saveFile('surat_skla')">Save</button>
                    <button type="submit" class="btn btn-primary" id="submit_surat_skla" disabled>Submit</button>
                </div>

                <!-- Upload Hasil Tes TOEIC (PDF/DOCX) -->
                <div class="form-group">
                    <label>Upload Hasil Tes TOEIC</label>
                    <input type="file" class="form-control" name="hasil_tes_toeic" id="hasil_tes_toeic" accept=".docx, .pdf, .jpg, .jpeg">
                    <button type="button" class="btn btn-sm btn-secondary" onclick="saveFile('hasil_tes_toeic')">Save</button>
                    <button type="submit" class="btn btn-primary" id="submit_hasil_tes_toeic" disabled>Submit</button>
                </div>

                <!-- Upload Surat Hasil SKKM (PDF/DOCX) -->
                <div class="form-group">
                    <label>Upload Surat Hasil SKKM</label>
                    <input type="file" class="form-control" name="surat_hasil_skkm" id="surat_hasil_skkm" accept=".docx, .pdf, .jpg, .jpeg">
                    <button type="button" class="btn btn-sm btn-secondary" onclick="saveFile('surat_hasil_skkm')">Save</button>
                    <button type="submit" class="btn btn-primary" id="submit_surat_hasil_skkm" disabled>Submit</button>
                </div>

                <!-- Upload All Button -->
                <div class="form-group">
                    <button type="submit" class="btn btn-success">Upload All</button>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
    // Function to handle saving files temporarily
    function saveFile(fileId) {
        const fileInput = document.getElementById(fileId);
        const file = fileInput.files[0];

        // Validasi file extension (hanya .docx, .pdf, .jpg, .jpeg yang diperbolehkan)
        const allowedExtensions = /(\.docx|\.pdf|\.jpg|\.jpeg)$/i;
        if (file) {
            if (!allowedExtensions.exec(file.name)) {
                alert('Only .docx, .pdf, .jpg, and .jpeg files are allowed!');
                fileInput.value = ''; // Clear the input field
                return false;
            }
            alert('File saved: ' + file.name);
            // Enable the submit button
            const submitButton = document.getElementById('submit_' + fileId);
            submitButton.disabled = false;
        } else {
            alert('No file selected!');
        }
    }

    // Function to handle the form submission (e.g., upload files)
    document.getElementById('uploadForm').addEventListener('submit', function(event) {
        const formElements = this.elements;
        let formValid = true;

        // Check if all required files are selected before submitting
        for (let i = 0; i < formElements.length; i++) {
            if (formElements[i].type === 'file' && !formElements[i].files[0]) {
                alert('Please select a file before submitting!');
                formValid = false;
                break;
            }
        }

        if (!formValid) {
            event.preventDefault(); // Prevent form submission if file is not selected
        }
    });
</script>
