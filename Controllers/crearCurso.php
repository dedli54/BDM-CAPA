<?php
// header('Content-Type: application/json');
// session_start();
require '../conexion.php';

$response = ['success' => false, 'message' => ''];//JSON

try {
    // Crear una instancia de la conexión
    $conexion = new Conexion();
    $pdo = $conexion->conectar();

    if (!$pdo) {
        throw new Exception('No se pudo conectar a la base de datos');
    }

    // Preparar los parámetros desde el formulario
    $titulo = $_POST['p_titulo'];
    $descripcion = $_POST['p_descripcion'];
    $precio = (float)$_POST['p_precio'];
    $contenido = $_POST['p_contenido'];
    $id_maestro = (int)$_POST['p_id_maestro'];
    $id_categoria = (int)$_POST['p_id_categoria'];

    // Manejar la imagen
    $foto = null;
    if (isset($_FILES['p_foto']) && $_FILES['p_foto']['error'] === UPLOAD_ERR_OK) {
        $foto = file_get_contents($_FILES['p_foto']['tmp_name']); // Convertir la imagen a binario
    } else {
        throw new Exception('Error al cargar la imagen');
    }

    
    $stmt = $pdo->prepare("CALL sp_agregar_curso(:titulo, :descripcion, :precio, :contenido, :id_maestro, :id_categoria, :foto)");


    $stmt->bindParam(':titulo', $titulo, PDO::PARAM_STR);
    $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
    $stmt->bindParam(':precio', $precio, PDO::PARAM_STR);
    $stmt->bindParam(':contenido', $contenido, PDO::PARAM_STR);
    $stmt->bindParam(':id_maestro', $id_maestro, PDO::PARAM_INT);
    $stmt->bindParam(':id_categoria', $id_categoria, PDO::PARAM_INT);
    $stmt->bindParam(':foto', $foto, PDO::PARAM_LOB); // BLOB para la imagen

    // Ejecutar el procedimiento y verificar si tuvo éxito
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Curso creado exitosamente';
    } else {
        throw new Exception('Error al ejecutar el procedimiento almacenado');
    }

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
} finally {
    echo json_encode($response); // Responder con JSON
    if (isset($pdo)) {
        $pdo = null; // Cerrar la conexión
    }
}
?>