<?php
class conexion {
    private $host = 'localhost';
    private $dbname = 'bdm-capa'; 
    private $charset = 'utf8';
    private $pdo;

    public function conectar() {
        try {
            $this->pdo = new PDO("mysql:host={$this->host};dbname={$this->dbname};charset={$this->charset}");
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->pdo;
        } catch (PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
            return null;
        }
    }
}
?>