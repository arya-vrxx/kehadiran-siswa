<?php
require_once __DIR__ . "/BaseModel.php";

class Siswa extends BaseModel
{
    protected string $table      = "siswa";
    protected string $primaryKey = "NISN";

    // Daftar siswa beserta nama kelasnya (JOIN), bisa dicari berdasarkan nama atau NISN
    public function allWithKelas(string $keyword = ""): array
    {
        $sql = "SELECT siswa.NISN, siswa.Nama, siswa.Alamat, kelas.nama_kelas, kelas.jurusan
                FROM siswa
                LEFT JOIN kelas ON siswa.id_kelas = kelas.id_kelas";

        $params = [];

        if ($keyword !== "") {
            $sql .= " WHERE siswa.Nama LIKE ? OR siswa.NISN LIKE ?";
            $params[] = "%" . $keyword . "%";
            $params[] = "%" . $keyword . "%";
        }

        $sql .= " ORDER BY siswa.Nama";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function create(string $nisn, string $nama, string $alamat, int $idKelas): bool
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO siswa (NISN, Nama, Alamat, id_kelas) VALUES (?, ?, ?, ?)"
        );
        // Alamat boleh kosong: simpan sebagai NULL
        return $stmt->execute([$nisn, $nama, $alamat === "" ? null : $alamat, $idKelas]);
    }

    // NISN tidak ikut diubah (dipakai sebagai acuan di tabel presensi)
    public function update(string $nisn, string $nama, string $alamat, int $idKelas): bool
    {
        $stmt = $this->pdo->prepare(
            "UPDATE siswa SET Nama = ?, Alamat = ?, id_kelas = ? WHERE NISN = ?"
        );
        return $stmt->execute([$nama, $alamat === "" ? null : $alamat, $idKelas, $nisn]);
    }

    // Dipakai sebelum hapus: siswa yang sudah punya presensi jangan dihapus
    public function countPresensi(string $nisn): int
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM presensi WHERE NISN = ?");
        $stmt->execute([$nisn]);
        return (int) $stmt->fetchColumn();
    }
}