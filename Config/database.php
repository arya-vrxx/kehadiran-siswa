<?php
class Database
{
    private string $host     = "localhost";
    private string $user     = "root";
    private string $password = "";
    private string $dbname   = "presensisekolah";
    private string $charset  = "utf8mb4";

    private ?PDO $pdo = null;

    public function getConnection(): PDO
    {
        if ($this->pdo === null) {
            $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset={$this->charset}";

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                $this->pdo = new PDO($dsn, $this->user, $this->password, $options);
            } catch (PDOException $e) {
                error_log($e->getMessage());
                die("Koneksi database gagal. Silakan coba lagi nanti.");
            }
        }

        return $this->pdo;
    }
}