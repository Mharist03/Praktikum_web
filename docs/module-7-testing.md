@'
# Dokumentasi Pengujian Module 7
## CRUD Update, Delete, Search, dan Filter Mahasiswa

### 1. Update Mahasiswa
Mengubah nama Rina Kurnia menjadi Rina Kurnia Putri.
Hasil: Berhasil.

### 2. Validasi Data Duplikat
Menguji penggunaan NIM yang sudah dimiliki mahasiswa lain.
Hasil: Sistem menolak perubahan dan menampilkan pesan kesalahan.

### 3. Delete Mahasiswa
Menghapus data Rina Kurnia Putri berdasarkan ID.
Hasil: Berhasil. Jumlah data berkurang dari empat menjadi tiga.

### 4. Search Mahasiswa
Mencari mahasiswa berdasarkan nama Andi Saputra.
Hasil: Sistem hanya menampilkan data yang sesuai dengan pencarian.

### 5. Filter Program Studi
Memfilter mahasiswa berdasarkan Program Studi Teknik Informatika.
Hasil: Sistem menampilkan Andi Saputra dan Budi Setiawan.

### 6. GitHub Issue
Issue #1: Implementasi CRUD Update, Delete, Search, dan Filter Mahasiswa.
Status: Closed.

### 7. Commit Implementasi
71e766c - feat: implement mahasiswa update delete search and filter

### 8. Repository
https://github.com/Mharist03/Praktikum_web
'@ | Set-Content -Path ".\docs\module-7-testing.md" -Encoding UTF8