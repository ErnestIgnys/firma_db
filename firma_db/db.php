<?php
class Database {
    private $host = "localhost";
    private $db_name = "firma_db";
    private $username = "postgres";
    private $password = "postgres";
    public $conn;

    public function connect() {
        $this->conn = null;
        try {
            $this->conn = new PDO("pgsql:host={$this->host};dbname={$this->db_name}",
                $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Błąd połączenia: " . $e->getMessage();
        }
        return $this->conn;
    }
}
?>
