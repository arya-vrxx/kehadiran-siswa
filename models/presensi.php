<?php
require_once __DIR__ . "/BaseModel.php";

class Presensi extends BaseModel
{
    protected string $table      = "presensi";
    protected string $primaryKey = "id";

    // Catat satu tap. Tanggal_Waktu diisi otomatis oleh database (default CURRENT_TIMESTAMP)
    public function create(string $nisn): bool
    {
        $stmt = $this->pdo->prepare("INSERT INTO presensi (NISN) VALUES (?)");
        return $stmt->execute([$nisn]);
    }

    // Rekap presensi (JOIN presensi -> siswa -> kelas), bisa dicari dan difilter
    public function rekap(string $keyword = "", string $tanggal = "", string $idKelas = ""): array
    {
        $sql = "SELECT presensi.id, presensi.NISN, presensi.Tanggal_Waktu,
                       siswa.Nama, kelas.nama_kelas
                FROM presensi
                LEFT JOIN siswa ON presensi.NISN = siswa.NISN
                LEFT JOIN kelas ON siswa.id_kelas = kelas.id_kelas";

        $where  = [];
        $params = [];

        // Setiap filter hanya ditambahkan kalau diisi
        if ($keyword !== "") {
            $where[]  = "(siswa.Nama LIKE ? OR presensi.NISN LIKE ?)";
            $params[] = "%" . $keyword . "%";
            $params[] = "%" . $keyword . "%";
        }
        if ($tanggal !== "") {
            $where[]  = "DATE(presensi.Tanggal_Waktu) = ?";
            $params[] = $tanggal;
        }
        if ($idKelas !== "") {
            $where[]  = "siswa.id_kelas = ?";
            $params[] = $idKelas;
        }

        if (!empty($where)) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }

        $sql .= " ORDER BY presensi.Tanggal_Waktu DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}