<?php
header('Content-Type: application/json');
session_start();
require '../conexion.php';


$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'];
$password = $data['password'];

$conexion = new Conexion();
$pdo = $conexion->conectar();

if ($pdo) {
    try {
        $stmt = $pdo->prepare("CALL sp_login(:email, :contrasena)");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':contrasena', $password);
        
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $_SESSION['user_id'] = $result['id']; // ID del usuario
            $_SESSION['user_type'] = $result['tipo_usuario']; // Tipo de usuario

            echo json_encode(['success' => true, 'data' => $result]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Email o contraseña incorrectos']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Error en la conexión']);
}
?>