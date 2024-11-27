<?php
class conexion {
    private $host = 'localhost'; //le cambie el host y contraseña y user por mi pc recuerden cambiarla cuando prueben ustedes
    private $dbname = 'bdm-capa';  
    private $charset = 'utf8';
    private $user = 'root'; 
    private $password = ''; 
    private $pdo;

    public function conectar() {
        try {
            $this->pdo = new PDO("mysql:host={$this->host};dbname={$this->dbname};charset={$this->charset}", $this->user, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->pdo;
        } catch (PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
            return null;
        }
    }
}
?>

