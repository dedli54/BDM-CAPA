<?php
session_start();
require_once '../conexion.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

try {
    $conexion = new Conexion();
    $pdo = $conexion->conectar();
    
    $titulo = $_POST['p_titulo'];
    $descripcion = $_POST['p_descripcion'];
    $precio = (float)$_POST['p_precio'];
    $id_categoria = (int)$_POST['p_id_categoria'];
    $id_curso = (int)$_POST['p_id_curso'];
    $id_maestro = $_SESSION['user_id'];

    // Handle image upload
    $foto = null;
    if (isset($_FILES['p_foto']) && $_FILES['p_foto']['error'] === UPLOAD_ERR_OK) {
        $foto = file_get_contents($_FILES['p_foto']['tmp_name']);
    }

    $stmt = $pdo->prepare("CALL sp_EditCurso(?, ?, ?, ?, ?, ?, ?)");
    $stmt->bindParam(1, $id_curso);
    $stmt->bindParam(2, $titulo);
    $stmt->bindParam(3, $descripcion);
    $stmt->bindParam(4, $precio);
    $stmt->bindParam(5, $id_categoria);
    $stmt->bindParam(6, $foto, PDO::PARAM_LOB);
    $stmt->bindParam(7, $id_maestro);

    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo json_encode($result);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}