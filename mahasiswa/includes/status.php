<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Tanggungan</title>
    <style>
        .table-bordered {
            border-collapse: collapse;
            width: 60%;
            margin: 20px 0;
            text-align: center;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid black;
            padding: 8px;
        }

        .table-bordered th {
            background-color: #f2f2f2;
        }

        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 5px;
            color: white;
            font-size: 0.9em;
        }

        .bg-success {
            background-color: green;
        }

        .bg-warning {
            background-color: orange;
        }

        .download-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: gray;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            cursor: not-allowed;
        }

        .download-btn.enabled {
            background-color: green;
            cursor: pointer;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="mb-5">
        <h2>Status Validasi</h2>
        <p>Berikut adalah status permintaan Anda:</p>
        <table class="table-bordered">
            <thead>
                <tr>
                    <th>Jenis Tanggungan</th>
                    <th>Terselesaikan</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="tanggungan-table">
                <tr>
                    <td>Surat Bebas Kompen</td>
                    <td id="date-kompen">-</td>
                    <td><span class="badge bg-warning" id="status-kompen">Pending</span></td>
                </tr>
                <tr>
                    <td>SKLA</td>
                    <td id="date-skla">-</td>
                    <td><span class="badge bg-warning" id="status-skla">Pending</span></td>
                </tr>
                <tr>
                    <td>Surat PKL</td>
                    <td id="date-pkl">-</td>
                    <td><span class="badge bg-warning" id="status-pkl">Pending</span></td>
                </tr>
                <tr>
                    <td>Hasil Tes TOEIC</td>
                    <td id="date-toeic">-</td>
                    <td><span class="badge bg-warning" id="status-toeic">Pending</span></td>
                </tr>
                <tr>
                    <td>SKKM</td>
                    <td id="date-skkm">-</td>
                    <td><span class="badge bg-warning" id="status-skkm">Pending</span></td>
                </tr>
                <tr>
                    <td>Surat Publikasi</td>
                    <td id="date-publikasi">-</td>
                    <td><span class="badge bg-warning" id="status-publikasi">Pending</span></td>
                </tr>
            </tbody>
        </table>
        <a id="download-button" class="download-btn">Download Surat Bebas Tanggungan</a>
    </div>

    <script>
        function updateStatus() {
            const rows = document.querySelectorAll("#tanggungan-table tr");
            let allValidated = true;

            rows.forEach((row, index) => {
                const statusBadge = row.querySelector(".badge");
                const dateCell = row.querySelector("td:nth-child(2)");

                if (statusBadge.textContent === "Pending") {
                    // Simulasikan validasi tanggungan
                    statusBadge.textContent = "Terverifikasi";
                    statusBadge.className = "badge bg-success";

                    // Atur tanggal validasi (hari ini)
                    const currentDate = new Date().toLocaleDateString("id-ID");
                    dateCell.textContent = currentDate;
                } else if (statusBadge.textContent === "Pending") {
                    allValidated = false;
                }
            });

            const downloadButton = document.getElementById("download-button");
            if (allValidated) {
                downloadButton.classList.add("enabled");
                downloadButton.href = "download.php?file=surat_bebas_tanggungan.pdf";
                downloadButton.style.cursor = "pointer";
            } else {
                downloadButton.classList.remove("enabled");
                downloadButton.removeAttribute("href");
                downloadButton.style.cursor = "not-allowed";
            }
        }

        // Panggil fungsi untuk memperbarui status saat semua tanggungan divalidasi
        document.getElementById("download-button").addEventListener("click", () => {
            alert("Surat Bebas Tanggungan berhasil diunduh!");
        });

        // Fungsi untuk simulasi validasi (bisa dihapus jika tidak diperlukan)
        updateStatus();
    </script>
</body>

</html>