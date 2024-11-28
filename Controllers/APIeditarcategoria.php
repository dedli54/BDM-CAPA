<?php
header('Content-Type: application/json');
session_start();
require '../conexion.php';

try {
    $conexion = new Conexion();
    $pdo = $conexion->conectar();
    
    if (!$pdo) {
        echo json_encode(['success' => false, 'message' => 'Error al conectar a la base de datos.']);
        exit;
    }

    
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Obtener categorías
        $stmt = $pdo->query("SELECT id, nombre FROM categoria");
        $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($categorias) {
            echo json_encode($categorias);  
        } else {
            echo json_encode(['success' => false, 'message' => 'No hay categorías disponibles.']);
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (isset($data['id'], $data['nombre'], $data['descripcion'])) {
            $id = $data['id'];
            $nombre = trim($data['nombre']);
            $descripcion = trim($data['descripcion']);

            if (empty($id) || empty($nombre) || empty($descripcion)) {
                echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
                exit;
            }

            $stmt = $pdo->prepare("UPDATE categoria SET nombre = :nombre, descripcion = :descripcion WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                echo json_encode(['success' => true, 'message' => 'Categoría editada exitosamente.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'No se encontró la categoría o los datos son los mismos.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Faltan datos para editar la categoría.']);
        }
    }
} catch (Exception $e) {
    
    echo json_encode(['success' => false, 'message' => 'Ocurrió un error inesperado: ' . $e->getMessage()]);
}
?>