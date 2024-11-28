<?php
session_start();
require '../conexion.php';

$response = ['success' => false, 'message' => ''];

try {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($_SESSION['user_id']) || !isset($data['curso_id']) || !isset($data['orderID'])) {
        throw new Exception('Datos de sesión o compra inválidos');
    }

    $conexion = new Conexion();
    $pdo = $conexion->conectar();

    try {
        $stmt = $pdo->prepare("CALL sp_comprar_curso_paypal(?, ?, ?)");
        $stmt->execute([
            $_SESSION['user_id'],
            $data['curso_id'],
            $data['orderID']
        ]);
        
        $response['success'] = true;
        $response['message'] = 'Curso comprado exitosamente';
        
    } catch (PDOException $e) {
        // Get the error message from the stored procedure
        throw new Exception($e->getMessage());
    }

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response);