<?php
require_once __DIR__ . "/BaseModel.php";

class Kelas extends BaseModel
{
    protected string $table      = "kelas";
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
}