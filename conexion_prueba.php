

<?php
class Conexion {
    private $host = 'localhost';
    private $dbname = 'BDM'; 
    private $username = 'root'; // Cambia esto según tu configuración
    private $password = ''; // Deja vacío si no tienes contraseña en MySQL (por defecto en XAMPP)
    private $charset = 'utf8';
    private $pdo;

    public function conectar() {
        try {
            $this->pdo = new PDO("mysql:host={$this->host};dbname={$this->dbname};charset={$this->charset}", $this->username, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->pdo;
        } catch (PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
            return null;
        }
    }
}

// Probar la conexión
$conexion = new Conexion();
$pdo = $conexion->conectar();

if ($pdo) {
    echo "Conexión exitosa a la base de datos.";
} else {
    echo "No se pudo conectar a la base de datos.";
}