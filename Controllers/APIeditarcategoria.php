<?php
header('Content-Type: application/json');
session_start();
require '../conexion.php';

    $data = json_decode(file_get_contents('php://input'), true);
    var_dump($data); 

    
    if (isset($data['id'], $data['nombre'], $data['descripcion'])) {
      
        $id = $data['id'];
        $nombre = $data['nombre'];
        $descripcion = $data['descripcion'];

        
        $conexion = new Conexion();
        $pdo = $conexion->conectar();

        if ($pdo) {
            try {
                
                $stmt = $pdo->prepare("UPDATE categoria SET nombre = :nombre, descripcion = :descripcion WHERE id = :id");
                $stmt->bindParam(':id', $id);
                $stmt->bindParam(':nombre', $nombre);
                $stmt->bindParam(':descripcion', $descripcion);
                
                $stmt->execute();
                var_dump($stmt->errorInfo()); 

                echo json_encode(['success' => true, 'message' => 'Categoría editada exitosamente']);
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Error en la conexión a la base de datos']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Faltan datos para editar la categoría']);
    }
?>