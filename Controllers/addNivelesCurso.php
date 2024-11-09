<?php
require '../conexion.php';

$response = ['success' => false, 'message' => '']; // JSON de respuesta

try {
    $conexion = new Conexion();
    $pdo = $conexion->conectar();

    if (!$pdo) {
        throw new Exception('No se pudo conectar a la base de datos');
    }

    $p_texto = $_POST['p_texto'] ?? '';
    $p_numero = $_POST['p_numero'] ?? 1;
    $p_video = '';

    if (isset($_FILES['p_video']) && $_FILES['p_video']['error'] === UPLOAD_ERR_OK) {
        $nombreArchivo = uniqid() . "_" . $_FILES['p_video']['name']; // 
        $rutaTemporal = $_FILES['p_video']['tmp_name'];
        $rutaDestino = "../Views/Videos/" . $nombreArchivo;
        
        if (!move_uploaded_file($rutaTemporal, $rutaDestino)) {
            throw new Exception("No se pudo subir el archivo de video.");
        }
        
        $p_video = $rutaDestino;
    }

    $stmt = $pdo->prepare("CALL sp_niveles_curso(:p_video, :p_texto, :p_numero)");
    $stmt->bindParam(':p_video', $p_video);
    $stmt->bindParam(':p_texto', $p_texto);
    $stmt->bindParam(':p_numero', $p_numero, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = "Nivel agregado exitosamente";
    } else {
        throw new Exception("No se pudo agregar el nivel.");
    }

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
} finally {
    echo json_encode($response);
    if (isset($pdo)) {
        $pdo = null;
    }
}
?>
