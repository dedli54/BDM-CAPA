<?php
session_start();
require_once '../conexion.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['curso_id'])) {
    echo json_encode(['success' => false, 'message' => 'Acceso no autorizado']);
    exit;
}

try {
    $conexion = new Conexion();
    $pdo = $conexion->conectar();
    
    $stmt = $pdo->prepare("CALL sp_soft_delete_curso(?, ?)");
    $stmt->execute([$_POST['curso_id'], $_SESSION['user_id']]);
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($result);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}