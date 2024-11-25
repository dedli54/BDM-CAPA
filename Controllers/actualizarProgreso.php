<?php
session_start();
require '../conexion.php';

$response = ['success' => false, 'message' => ''];

try {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $conexion = new Conexion();
    $pdo = $conexion->conectar();

    $stmt = $pdo->prepare("CALL sp_actualizar_progreso(?, ?, ?)");
    $stmt->execute([
        $_SESSION['user_id'],
        $data['curso_id'],
        $data['nivel']
    ]);

    $response['success'] = true;
    $response['message'] = 'Progreso actualizado';

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response);