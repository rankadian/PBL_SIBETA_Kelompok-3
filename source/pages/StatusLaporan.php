<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Laporan - SIBETA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .status-card {
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .status-step {
            position: relative;
            padding: 20px;
            border-left: 3px solid #e9ecef;
            margin-bottom: 15px;
        }
        
        .status-step.active {
            border-left: 3px solid #198754;
        }
        
        .status-step .step-number {
            width: 30px;
            height: 30px;
            background-color: #e9ecef;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: absolute;
            left: -17px;
            top: 20px;
            font-weight: bold;
        }
        
        .status-step.active .step-number {
            background-color: #198754;
            color: white;
        }
        
        .status-step .step-content {
            margin-left: 20px;
        }
        
        .status-badge {
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
            display: inline-block;
            margin-top: 10px;
        }
        
        .status-badge.pending {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .status-badge.success {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-badge.rejected {
            background-color: #f8d7da;
            color: #721c24;
        }

        .document-list {
            margin-top: 20px;
        }

        .document-item {
            padding: 15px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .document-item:hover {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h2 class="mb-4">Status Verifikasi Laporan</h2>
                
                <div class="status-card bg-white p-4">
                    <div id="stepProgress">
                        <!-- Steps will be loaded here -->
                    </div>

                    <div id="documentList" class="document-list">
                        <!-- Document list will be loaded here -->
                    </div>
                </div>
                
                <div id="downloadSection" class="text-center mt-4" style="display: none;">
                    <a href="./pages/LaporanBebasTanggungan/generate_pdf.php" class="btn btn-success">
                        <i class="fas fa-download me-2"></i>Download Surat Bebas Tanggungan
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            loadStatus();
        });

        function loadStatus() {
            fetch('action/StatusLaporanAction.php?act=load')
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        updateStepProgress(data.data.current_step);
                        updateDocumentList(data.data.documents, data.data.verification_status);
                        if (data.data.can_download) {
                            document.getElementById('downloadSection').style.display = 'block';
                        }
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function updateStepProgress(currentStep) {
            const steps = [
                {
                    title: 'Pengajuan Laporan',
                    description: 'Laporan telah diajukan dan menunggu verifikasi'
                },
                {
                    title: 'Verifikasi Dokumen',
                    description: 'Pemeriksaan kelengkapan dan kesesuaian dokumen'
                },
                {
                    title: 'Hasil Verifikasi',
                    description: 'Status akhir verifikasi laporan'
                }
            ];

            const stepsHtml = steps.map((step, index) => {
                const stepNumber = index + 1;
                const isActive = stepNumber <= currentStep;
                return `
                    <div class="status-step ${isActive ? 'active' : ''}">
                        <div class="step-number">${stepNumber}</div>
                        <div class="step-content">
                            <h5>${step.title}</h5>
                            <p class="text-muted mb-2">${step.description}</p>
                            ${stepNumber === currentStep ? `
                                <span class="status-badge ${currentStep === 3 ? 'success' : 'pending'}">
                                    ${currentStep === 3 ? 'Selesai' : 'Dalam Proses'}
                                </span>
                            ` : ''}
                        </div>
                    </div>
                `;
            }).join('');

            document.getElementById('stepProgress').innerHTML = stepsHtml;
        }

        function updateDocumentList(documents, verificationStatus) {
            const documentListHtml = documents.map(doc => `
                <div class="document-item">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="mb-1">${doc.Jenis_Surat}</h6>
                            <p class="mb-1 text-muted">Diupload: ${doc.TanggalDibuat}</p>
                            ${doc.TanggalVerifikasi ? `<p class="mb-1 text-muted">Diverifikasi: ${doc.TanggalVerifikasi}</p>` : ''}
                            ${doc.Catatan ? `<p class="mb-2 text-muted">Catatan: ${doc.Catatan}</p>` : ''}
                        </div>
                        <span class="status-badge ${doc.StatusVerifikasi === 'Disetujui' ? 'success' : 'rejected'}">
                            ${doc.StatusVerifikasi}
                        </span>
                    </div>
                </div>
            `).join('');

            document.getElementById('documentList').innerHTML = documentListHtml;
        }
    </script>
</body>
</html>