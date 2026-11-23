# Aplikasi Presensi Siswa

Aplikasi Presensi Siswa adalah aplikasi berbasis web yang dibuat untuk
membantu proses pencatatan kehadiran siswa di sekolah.

Aplikasi ini menggunakan sistem RFID yang disimulasikan melalui form
untuk mencatat kehadiran siswa saat melakukan tap kartu. Aplikasi dibuat
menggunakan PHP Native dengan konsep OOP, PDO, dan database MariaDB
tanpa menggunakan framework.

## Fitur Aplikasi

| No. | Fitur | Keterangan |
|-----|-------|------------|
| 1 | Login | Masuk ke dalam aplikasi menggunakan akun admin. |
| 2 | Logout | Keluar dari akun yang sedang digunakan. |
| 3 | Kelola Kelas | Menambah, melihat, mengubah, dan menghapus data kelas. |
| 4 | Kelola Siswa | Mengatur data siswa, mulai dari menambah sampai menghapus data. |
| 5 | Simulasi Tap Kartu | Mencatat kehadiran siswa melalui simulasi tap kartu RFID. |
| 6 | Rekap Presensi | Melihat dan menghapus data riwayat presensi. |
| 7 | Pencarian Siswa | Memudahkan admin mencari data siswa. |
| 8 | Filter Presensi | Mencari dan menyaring data presensi sesuai kebutuhan. |

## Kebutuhan Sistem

Untuk menjalankan aplikasi ini, diperlukan beberapa perangkat lunak
berikut:

-   PHP versi 8.0 atau lebih baru.
-   MariaDB atau MySQL sebagai database.
-   Ekstensi PHP `pdo_mysql` dan `mbstring`.
-   XAMPP atau server PHP bawaan.

## Cara Menjalankan Aplikasi

1.  Pindahkan folder proyek ke dalam folder `htdocs` jika menggunakan
    XAMPP.
2.  Buat database dengan mengimpor file `database/schema.sql` melalui
    phpMyAdmin.
3.  Atur koneksi database pada file `Config/database.php`, seperti nama
    database, username, dan password.
4.  Jalankan file `seed.php` untuk membuat data awal berupa akun admin
    dan data kelas.
5.  Jalankan Apache dan MySQL melalui XAMPP.
6.  Buka aplikasi melalui browser dengan alamat
    `http://localhost/nama-folder/login.php`.

## Akun Login

Berikut akun yang sudah disediakan untuk mencoba aplikasi:

| Username | Password |
|----------|----------|
| admin | admin123 |

**Catatan:** Ganti password bawaan jika aplikasi digunakan di luar
keperluan demo.

## Struktur Folder

Struktur folder dibuat agar setiap bagian aplikasi memiliki tempat dan
fungsi masing-masing.

``` text
├── config/database.php   Pengaturan koneksi database
├── models/               Model untuk data kelas, siswa, dan presensi
├── layout/               Header, sidebar, dan footer
├── assets/style.css      Pengaturan tampilan aplikasi
├── kelas/                Halaman pengelolaan data kelas
├── siswa/                Halaman pengelolaan data siswa
├── presensi/             Simulasi tap kartu dan rekap presensi
├── database/schema.sql   Struktur database
├── seed.php              Pengisian data awal
├── index.php             Halaman utama aplikasi
├── login.php             Halaman masuk
└── logout.php            Proses keluar dari akun
```

## Penjelasan Sistem

Aplikasi ini dibuat menggunakan konsep OOP (*Object-Oriented
Programming*), yaitu konsep pemrograman yang menggunakan class dan
object.

Pada bagian model, terdapat `BaseModel` yang berfungsi sebagai dasar
untuk model lainnya. Model seperti `Kelas`, `Siswa`, dan `Presensi`
mewarisi fungsi dari `BaseModel` dan memiliki fungsi tambahan sesuai
kebutuhan masing-masing.

Untuk keamanan dan pengelolaan data, aplikasi ini menggunakan beberapa
hal:

-   **PDO:** Digunakan untuk menghubungkan PHP dengan database.
-   **Prepared Statement:** Membantu mencegah SQL Injection.
-   **`htmlspecialchars()`:** Digunakan untuk mengamankan teks yang
    ditampilkan pada halaman.
-   **`password_hash()` dan `password_verify()`:** Digunakan untuk
    menyimpan dan memeriksa password dalam bentuk hash.

Sistem presensi tidak menggunakan kolom status hadir atau tidak hadir
secara langsung. Siswa yang melakukan tap kartu akan tercatat sebagai
hadir, sedangkan siswa yang tidak melakukan tap tidak memiliki catatan
kehadiran.

Data kelas yang masih memiliki siswa dan data siswa yang sudah memiliki
riwayat presensi tidak bisa dihapus agar data tetap terjaga.
