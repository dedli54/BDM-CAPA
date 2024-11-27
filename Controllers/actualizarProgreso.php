<?php
session_start();
require '../conexion.php';

header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents('php://input'), true);
    $curso_id = $data['curso_id'];
    $nivel = $data['nivel'];
    $user_id = $_SESSION['user_id'];

    $conexion = new Conexion();
    $pdo = $conexion->conectar();

    // Call stored procedure to update progress
    $stmt = $pdo->prepare("CALL sp_actualizar_progreso(?, ?, ?)");
    $stmt->execute([$user_id, $curso_id, $nivel]);
    
    // Check if course is completed
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM nivelesCurso WHERE id_curso = ?");
    $stmt->execute([$curso_id]);
    $totalNiveles = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    $isCompleted = $nivel >= $totalNiveles;

    echo json_encode([
        'success' => true,
        'isCompleted' => $isCompleted
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>