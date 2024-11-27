<?php
session_start();
require '../conexion.php';

$response = ['success' => false, 'message' => ''];

try {
    if (!isset($_SESSION['user_id']) || !isset($_POST['curso_id'])) {
        throw new Exception('Datos de sesión o curso inválidos');
    }

    $conexion = new Conexion();
    $pdo = $conexion->conectar();

    
    $id_alumno = (int)$_SESSION['user_id'];
    $id_curso = (int)$_POST['curso_id'];
    $numero_tarjeta = preg_replace('/\s+/', '', $_POST['numero_tarjeta']);
    $fecha_vencimiento = $_POST['vencimiento'];
    $ccv = $_POST['ccv'];

   
    if (!preg_match('/^\d{16}$/', $numero_tarjeta)) {
        throw new Exception('Número de tarjeta inválido');
    }
    if (!preg_match('/^\d{2}\/\d{2}$/', $fecha_vencimiento)) {
        throw new Exception('Fecha de vencimiento inválida');
    }
    if (!preg_match('/^\d{3}$/', $ccv)) {
        throw new Exception('CCV inválido');
    }

    // call the SP
    $stmt = $pdo->prepare("CALL sp_comprar_curso(?, ?, ?, ?, ?)");
    $stmt->execute([
        $id_alumno,
        $id_curso,
        $numero_tarjeta,
        $fecha_vencimiento,
        $ccv
    ]);

    $response['success'] = true;
    $response['message'] = 'Curso comprado exitosamente';

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response);