<!-- pages/mahasiswa.php -->

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Dashboard Mahasiswa</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
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
            <h3 class="card-title">Informasi Tanggung Jawab Per Semester</h3>
        </div>
        <div class="card-body">
            <!-- Display Table of Tanggunan Per Semester -->
            <table class="table table-sm table-bordered table-striped" id="table-tanggungjawab">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Semester</th>
                        <th>Status Tanggung Jawab</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Dynamic Data from Backend -->
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- Modal for File Upload (Save/Submit) -->
<div class="modal fade" id="upload-bukti-modal" style="display: none;" aria-hidden="true">
    <form action="action/mahasiswaAction.php?act=upload" method="post" id="form-upload-bukti" enctype="multipart/form-data">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Upload Bukti</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="upload-file">Pilih File</label>
                        <input type="file" class="form-control" name="upload_file" id="upload-file" required>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-control" name="status" id="status" required>
                            <option value="" disabled selected>Pilih Status</option>
                            <option value="save">Save</option>
                            <option value="submit">Submit</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </form>
</div>

