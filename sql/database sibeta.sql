--Query untuk Menampilkan Data Mahasiswa beserta Absensinya
SELECT m.nim, m.nama, m.email, a.jumlah_alpha, a.semester, a.status_validasi AS absensi_status
FROM dbo.TB_MAHASISWA m
JOIN dbo.TB_ABSENSI a ON m.nim = a.mahasiswa_nim;

--Query untuk Menampilkan Data Mahasiswa beserta Tanggungan yang Ada
SELECT m.nim, m.nama, m.email, t.jenis_tanggungan, t.status_validasi AS tanggungan_status
FROM dbo.TB_MAHASISWA m
JOIN dbo.TB_TANGGUNGAN t ON m.nim = t.mahasiswa_nim;

--Query untuk Menampilkan Data Mahasiswa beserta Semua Tanggungan yang Terverifikasi oleh Admin
SELECT m.nim, m.nama, m.email, t.jenis_tanggungan, va.status_validasi AS verifikasi_admin_status
FROM dbo.TB_MAHASISWA m
JOIN dbo.TB_TANGGUNGAN t ON m.nim = t.mahasiswa_nim
JOIN dbo.TB_VERIFIKASI_ADMIN va ON t.id_tanggungan = va.id_tanggungan
WHERE va.status_validasi = 'diajukan ke admin';

--Query untuk Menampilkan Data Mahasiswa beserta Notifikasi
SELECT m.nim, m.nama, m.email, n.pesan, n.tanggal_notifikasi
FROM dbo.TB_MAHASISWA m
JOIN dbo.TB_NOTIFIKASI n ON m.nim = n.mahasiswa_nim
ORDER BY n.tanggal_notifikasi DESC;

--Query untuk Menampilkan Data Mahasiswa, Tanggungan, dan Status Verifikasi KPS
SELECT m.nim, m.nama, m.email, t.jenis_tanggungan, vk.status_validasi AS verifikasi_kps_status
FROM dbo.TB_MAHASISWA m
JOIN dbo.TB_TANGGUNGAN t ON m.nim = t.mahasiswa_nim
JOIN dbo.TB_VERIFIKASI_KPS vk ON t.id_tanggungan = vk.id_tanggungan
WHERE vk.status_validasi = 'diajukan ke kps';

--Query untuk Menampilkan Data Mahasiswa dan Verifikasi Tanggungan Absensi yang Sudah Diverifikasi
SELECT m.nim, m.nama, m.email, t.jenis_tanggungan, va.status_validasi AS verifikasi_admin_status
FROM dbo.TB_MAHASISWA m
JOIN dbo.TB_TANGGUNGAN t ON m.nim = t.mahasiswa_nim
JOIN dbo.TB_VERIFIKASI_ADMIN va ON t.id_tanggungan = va.id_tanggungan
WHERE t.jenis_tanggungan = 'absensi' AND va.status_validasi = 'belum diverifikasi';

--Query untuk Menampilkan Semua Tanggungan Mahasiswa dengan Status UKT dan Verifikasi KPS
SELECT m.nim, m.nama, m.email, u.status_validasi AS ukt_status, vk.status_validasi AS verifikasi_kps_status
FROM dbo.TB_MAHASISWA m
JOIN dbo.TB_TANGGUNGAN t ON m.nim = t.mahasiswa_nim
JOIN dbo.TB_UKT u ON t.ukt_id = u.id_ukt
JOIN dbo.TB_VERIFIKASI_KPS vk ON t.id_tanggungan = vk.id_tanggungan
WHERE t.jenis_tanggungan = 'ukt';

--Query untuk Menampilkan Semua Data Mahasiswa, Tanggungan, dan Surat Bebas Tanggungan
SELECT m.nim, m.nama, m.email, t.jenis_tanggungan, sbt.status_surat AS surat_status
FROM dbo.TB_MAHASISWA m
JOIN dbo.TB_TANGGUNGAN t ON m.nim = t.mahasiswa_nim
JOIN dbo.TB_SURAT_BEBAS_TANGGUNGAN sbt ON t.id_tanggungan = sbt.tanggungan_id
WHERE sbt.status_surat = 'belum diterbitkan';

-- Query untuk Menampilkan Data Mahasiswa dengan Status Verifikasi KPS dan Admin
SELECT m.nim, m.nama, m.email, va.status_validasi AS verifikasi_admin_status, vk.status_validasi AS verifikasi_kps_status
FROM dbo.TB_MAHASISWA m
JOIN dbo.TB_TANGGUNGAN t ON m.nim = t.mahasiswa_nim
JOIN dbo.TB_VERIFIKASI_ADMIN va ON t.id_tanggungan = va.id_tanggungan
JOIN dbo.TB_VERIFIKASI_KPS vk ON t.id_tanggungan = vk.id_tanggungan
WHERE va.status_validasi = 'diajukan ke admin' AND vk.status_validasi = 'diajukan ke kps';

