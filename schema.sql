-- Skema database aplikasi presensi siswa (MariaDB/MySQL)
-- Cara pakai: impor file ini lewat phpMyAdmin, atau jalankan:
--   mysql -u root -p < database/schema.sql

CREATE DATABASE IF NOT EXISTS presensisekolah CHARACTER SET utf8mb4;
USE presensisekolah;

-- Kelas
CREATE TABLE IF NOT EXISTS kelas (
    id_kelas   INT          NOT NULL AUTO_INCREMENT,
    nama_kelas VARCHAR(20)  NULL,
    jurusan    VARCHAR(20)  NULL,
    PRIMARY KEY (id_kelas)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Siswa (NISN sebagai primary key)
CREATE TABLE IF NOT EXISTS siswa (
    NISN     VARCHAR(10)  NOT NULL,
    Nama     VARCHAR(100) NOT NULL,
    Alamat   VARCHAR(250) NULL,
    id_kelas INT          NOT NULL,
    PRIMARY KEY (NISN),
    CONSTRAINT fk_siswa_kelas
        FOREIGN KEY (id_kelas) REFERENCES kelas (id_kelas)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Presensi: satu baris per tap kartu (tidak ada kolom status,
-- siswa yang tidak tap dianggap tidak hadir)
CREATE TABLE IF NOT EXISTS presensi (
    id            INT         NOT NULL AUTO_INCREMENT,
    NISN          VARCHAR(10) NOT NULL,
    Tanggal_Waktu DATETIME    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    CONSTRAINT fk_presensi_siswa
        FOREIGN KEY (NISN) REFERENCES siswa (NISN)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Pengguna (Administrator). Password disimpan sebagai hash.
CREATE TABLE IF NOT EXISTS users (
    id_user  INT          NOT NULL AUTO_INCREMENT,
    username VARCHAR(50)  NOT NULL,
    password VARCHAR(255) NOT NULL,
    PRIMARY KEY (id_user),
    UNIQUE KEY uq_users_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;