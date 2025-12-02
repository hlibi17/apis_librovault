# LibroVault

LibroVault adalah aplikasi web sederhana untuk mengelola koleksi buku. Aplikasi ini dibangun menggunakan PHP dan MySQL, memungkinkan pengguna untuk menambah, mengedit, menghapus, dan melihat detail buku, termasuk kemampuan untuk melihat file PDF yang terkait.

## Fitur Utama

- **Manajemen Buku**: Tambah, edit, hapus, dan lihat daftar buku.
- **Paginasi**: Navigasi melalui daftar buku dengan paginasi.
- **Penayangan PDF**: Lihat file PDF buku langsung dari aplikasi.
- **Responsif**: Antarmuka yang mudah digunakan dengan desain sederhana.

## Prasyarat

Sebelum menginstal dan menjalankan aplikasi ini, pastikan Anda memiliki:

- **XAMPP** atau server web lokal lainnya yang mendukung PHP dan MySQL (misalnya Apache, MySQL, PHP).
- **PHP** versi 7.4 atau lebih tinggi.
- **MySQL** atau MariaDB.
- Browser web (Chrome, Firefox, dll.) untuk mengakses aplikasi.

## Instalasi

Ikuti langkah-langkah berikut untuk menginstal dan menjalankan LibroVault:

1. **Unduh atau Clone Proyek**:
   - Salin folder proyek ini ke direktori `htdocs` di XAMPP (misalnya: `C:\xampp\htdocs\librovault`).

2. **Jalankan XAMPP**:
   - Buka XAMPP Control Panel.
   - Mulai modul **Apache** dan **MySQL**.

3. **Buat Database**:
   - Buka browser dan akses `http://localhost/phpmyadmin`.
   - Buat database baru dengan nama `librovault`.
   - Import file `librovault.sql` yang ada di folder proyek ke database `librovault`.

4. **Konfigurasi Koneksi Database** (Opsional):
   - Jika diperlukan, edit file `koneksi.php` untuk menyesuaikan pengaturan database (host, username, password).
   - Secara default, aplikasi menggunakan:
     - Host: `localhost`
     - Username: `root`
     - Password: (kosong)
     - Database: `librovault`

5. **Akses Aplikasi**:
   - Jalankan Perintah ini diterminal : php -S localhost:8000
   - Buka browser dan kunjungi `http://localhost:8000`.
   - Aplikasi sekarang siap digunakan!

## Instruksi Penggunaan

### Menambah Buku Baru
1. Dari halaman utama (`index.php`), klik tombol **"+ Tambah Buku"**.
2. Isi formulir dengan detail buku: Judul, Penulis, Tahun, dan unggah file PDF jika ada.
3. Klik **Simpan** untuk menambah buku ke koleksi.

### Mengedit Buku
1. Dari daftar buku di halaman utama, klik tombol **Edit** pada buku yang ingin diubah.
2. Perbarui informasi yang diperlukan dan klik **Simpan**.

### Menghapus Buku
1. Dari daftar buku, klik tombol **Hapus** pada buku yang ingin dihapus.
2. Konfirmasi penghapusan di dialog yang muncul.

### Melihat Detail dan PDF Buku
1. Klik tombol **Detail** pada buku di daftar.
2. Lihat informasi lengkap buku.
3. Jika ada file PDF, klik **Baca** untuk melihatnya.

### Navigasi Paginasi
- Gunakan tombol **Prev** dan **Next** untuk berpindah halaman.
- Klik nomor halaman untuk langsung menuju halaman tersebut.

## Struktur File

- `index.php`: Halaman utama menampilkan daftar buku.
- `tambah.php`: Formulir untuk menambah buku baru.
- `edit.php`: Formulir untuk mengedit buku.
- `hapus.php`: Skrip untuk menghapus buku.
- `detail.php`: Halaman detail buku dan penayangan PDF.
- `koneksi.php`: Konfigurasi koneksi database.
- `Buku.php`: Model untuk tabel buku (menggunakan ORM).
- `orm.php`: Kelas ORM sederhana.
- `librovault.sql`: File SQL untuk membuat struktur database.
- `style.css`: File CSS untuk styling antarmuka.
- `uploads/`: Folder untuk menyimpan file PDF yang diunggah.

## Catatan

- Pastikan folder `uploads/` memiliki izin tulis agar file PDF dapat diunggah.
- Jika terjadi kesalahan koneksi database, periksa pengaturan di `koneksi.php`.
- Aplikasi ini dirancang untuk penggunaan lokal; untuk deployment produksi, pastikan keamanan server dikonfigurasi dengan baik.

## Kontribusi

Jika Anda ingin berkontribusi pada pengembangan LibroVault, silakan fork repositori ini dan buat pull request dengan perubahan Anda.

## Lisensi

Proyek ini menggunakan lisensi MIT. Lihat file LICENSE untuk detail lebih lanjut.
