<?php
require_once __DIR__ . "/BaseModel.php";

class Kelas extends BaseModel
{
    protected string $table = "kelas";
    protected string $primaryKey = "id_kelas";

    public function create(string $nama, string $jurusan): bool
    {
        $stmt = $this->pdo->prepare("INSERT INTO kelas (nama_kelas, jurusan) VALUES (?, ?)");
        return $stmt->execute([$nama, $jurusan]);
    }

    public function update($id, string $nama, string $jurusan): bool
    {
        $stmt = $this->pdo->prepare("UPDATE kelas SET nama_kelas = ?, jurusan = ? WHERE id_kelas = ?");
        return $stmt->execute([$nama, $jurusan, $id]);
    }

    // Dipakai sebelum hapus: kelas yang masih punya siswa jangan dihapus
    public function countSiswa($id): int
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM siswa WHERE id_kelas = ?");
        $stmt->execute([$id]);
        return (int) $stmt->fetchColumn();
    }

    public function allWithTotalPresensi(): array
    {
        $sql = "SELECT kelas.id_kelas, kelas.nama_kelas, kelas.jurusan,
                   COUNT(presensi.id) AS jumlah_presensi
            FROM kelas
            LEFT JOIN siswa
                ON siswa.id_kelas = kelas.id_kelas
            LEFT JOIN presensi
                ON presensi.NISN = siswa.NISN
            GROUP BY kelas.id_kelas, kelas.nama_kelas, kelas.jurusan
            ORDER BY kelas.id_kelas";

        return $this->pdo->query($sql)->fetchAll();
    }
}