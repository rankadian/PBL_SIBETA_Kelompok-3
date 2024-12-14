

<section class="content">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Beranda</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Upload Berkas</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Form Upload Berkas Validasi</h3>
        </div>
        <div class="card-body">
            <form id="uploadForm" action="../action/uploadAction.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Upload Surat Bebas Kompen</label>
                    <input type="file" class="form-control" name="surat_bebas_kompen" id="surat_bebas_kompen" accept=".docx, .pdf">
                    <button type="button" class="btn btn-sm btn-secondary" onclick="saveFile('surat_bebas_kompen')">Save</button>
                    <button type="submit" class="btn btn-primary" id="submit_surat_bebas_kompen" disabled>Submit</button>
                </div>

                <div class="form-group">
                    <label>Upload Surat Validasi PKL</label>
                    <input type="file" class="form-control" name="surat_validasi_pkl" id="surat_validasi_pkl" accept=".docx, .pdf">
                    <button type="button" class="btn btn-sm btn-secondary" onclick="saveFile('surat_validasi_pkl')">Save</button>
                    <button type="submit" class="btn btn-primary" id="submit_surat_validasi_pkl" disabled>Submit</button>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-success">Upload All</button>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
    function saveFile(fileId) {
        const fileInput = document.getElementById(fileId);
        const file = fileInput.files[0];
        const allowedExtensions = /(\.docx|\.pdf)$/i;
        
        if (file) {
            if (!allowedExtensions.exec(file.name)) {
                alert('Only .docx, .pdf files are allowed!');
                fileInput.value = '';
                return false;
            }
            alert('File saved: ' + file.name);
            const submitButton = document.getElementById('submit_' + fileId);
            submitButton.disabled = false;
        } else {
            alert('No file selected!');
        }
    }

    document.getElementById('uploadForm').addEventListener('submit', function(event) {
        const formElements = this.elements;
        let formValid = true;

        for (let i = 0; i < formElements.length; i++) {
            if (formElements[i].type === 'file' && !formElements[i].files[0]) {
                alert('Please select a file before submitting!');
                formValid = false;
                break;
            }
        }

        if (!formValid) {
            event.preventDefault();
        }
    });
</script>
