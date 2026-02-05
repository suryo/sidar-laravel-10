# 🚀 Fitur Aplikasi SIDAR Laravel 10

Dokumen ini menjelaskan secara detail fitur-fitur yang telah diimplementasikan dalam aplikasi SIDAR (Sistem Informasi Daily Activity Report) versi Laravel 10.

---

## 🔐 1. Otentikasi & Keamanan (Authentication)

- **Login**: Halaman masuk untuk pengguna terdaftar.
- **Logout**: Keluar dari sesi aplikasi dengan aman.
- **Role-Based Access Control (RBAC)**: Hak akses dibatasi berdasarkan peran pengguna (Staff, Supervisor, Manager, Admin, dll). Menggunakan _Spatie Permission_.
- **Impersonasi**: Admin dapat "login sebagai" karyawan lain untuk tujuan debugging atau bantuan teknis.

## 🏠 2. Dashboard

Halaman utama yang menampilkan ringkasan informasi penting (widget statistik, pengumuman, atau _pending tasks_).

## 📅 3. Daily Activity Report (DAR)

Modul inti untuk pelaporan aktivitas harian karyawan.

- **CRUD DAR**: Karyawan dapat membuat, melihat, mengedit, dan menghapus laporan aktivitas harian mereka.
- **Approval Workflow**: Supervisor dan Manager dapat melihat dan menyetujui (Approve) atau menolak (Reject) DAR bawahan mereka.
- **Filter & Search**: Pencarian laporan berdasarkan tanggal atau status.

## 📍 4. Presensi (Attendance)

Sistem pencatatan kehadiran karyawan.

- **Check-In**: Mencatat waktu masuk kerja. (Mendukung koordinat lokasi/GPS).
- **Check-Out**: Mencatat waktu pulang kerja.
- **Riwayat Presensi**: Karyawan dapat melihat log kehadiran mereka sendiri.
- **Checkout View**: Tampilan khusus untuk melakukan check-out.

## 🏖️ 5. Cuti & Izin (Leaves)

Manajemen pengajuan cuti dan izin tidak masuk kerja.

- **Pengajuan Cuti**: Form untuk mengajukan cuti dengan tanggal dan alasan.
- **Approval Cuti**: Atasan dapat menyetujui atau menolak pengajuan cuti.
- **Quota Management**: Sistem melacak sisa kuota cuti tahunan karyawan (misal: 12 hari).

## 💰 6. Klaim & Reimbursement (Claims)

Sistem pengajuan klaim pengeluaran (reimbursement).

- **Pengajuan Klaim**: Karyawan menginput detail pengeluaran, nominal, dan bukti (jika ada).
- **Approval Klaim**: Proses persetujuan berjenjang untuk pencairan dana.

## 📊 7. Laporan & Analitik (Reports)

Menyediakan berbagai laporan untuk manajemen dan HR.

- **Employee Summary**: Ringkasan data karyawan.
- **Gap Analysis**: Analisis kesenjangan (kompetensi/kinerja).
- **Out of Town**: Laporan dinas luar kota.
- **Late Permission**: Laporan izin terlambat.
- **Leave Report**: Rekapitulasi penggunaan cuti.

## ✉️ 8. Persuratan (Correspondence)

Manajemen surat-menyurat resmi internal/eksternal.

- **Kelola Surat (CRUD)**: Membuat dan mengelola surat keluar/masuk.
- **Approval Surat**: Persetujuan draf surat sebelum diterbitkan.
- **Template Surat**: Mengelola format standar surat (Hanya Admin).

## 🗓️ 9. Kalender & Hari Libur (Calendar)

- **Manajemen Hari Libur**: Admin dapat menambahkan hari libur nasional atau cuti bersama yang akan berdampak pada perhitungan hari kerja.

## 🛠️ 10. Master Data (Admin Only)

Manajemen data referensi sistem. Hanya dapat diakses oleh Admin atau pengguna dengan izin khusus.

- **Jabatan (Roles)**: Mengelola nama jabatan dan level akses. Logic khusus untuk mencegah penghapusan role 'admin'.
- **Departemen**: Mengelola daftar departemen dalam perusahaan.
- **Akses Area**: Mengelola zona akses fisik (misal: Lantai 1, Gudang, Server Room) dengan kode warna.
- **Unit Usaha**: Mengelola unit bisnis atau anak perusahaan.
- **Lokasi Kerja**: Mengelola lokasi kantor (Latitude, Longitude, Radius) untuk validasi presensi (Geofencing).

## ⚙️ 11. Administrasi (Administration)

Pengaturan sistem dan manajemen pengguna.

- **Manajemen Karyawan**: Tambah, Edit, Nonaktifkan pengguna.
    - Informasi Pribadi (NIK, Email, Telepon).
    - Informasi Organisasi (Departemen, Divisi, Lokasi, Role).
    - Informasi Supervisor (Atasan Langsung).
    - Relasi ke Master Data baru (Akses Area & Unit Usaha).
- **Pengaturan Menu**: Mengelola visibilitas menu sidebar berdasarkan role (Dinamis).

## 🌐 12. Dukungan Multi-Bahasa (Multi-Language)

Sistem mendukung penggunaan dalam dua bahasa untuk memudahkan pengguna.

- **Bahasa Indonesia (Default)**: Bahasa utama aplikasi.
- **Bahasa Inggris**: Opsi bahasa sekunder.
- **Penyimpanan Preferensi**: Pilihan bahasa disimpan dalam sesi pengguna.

---

_Dibuat otomatis oleh AI Assistant pada 4 Februari 2026._
