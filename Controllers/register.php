<?php
require_once '../conexion.php';

$conexion = new Conexion();
$conn = $conexion->conectar();

if ($conn === null) {
    die("No se pudo conectar a la base de datos.");
}

// 'ESTO' se obtiene con nombres no con IDs
$nombre_usuario = $_POST['nombre'];
$nombre = $_POST['nombre'];
$apellidos = $_POST['apellidos'];
$email = $_POST['email'];
$contrasena = $_POST['pw'];   
$tipo_usuario = $_POST['cuenta'];
$fecha_nacimiento = $_POST['fecha'];
$telefono = $_POST['telefono'];

// Manejo de la imagen
$foto = null;
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $foto = file_get_contents($_FILES['foto']['tmp_name']);
}

try {
    // Prepara el SP
    $stmt = $conn->prepare("CALL sp_crear_usuario(:nombre_usuario, :nombre, :apellidos, :email, :contrasena, :tipo_usuario, :foto, :fecha_nacimiento, :telefono, :biografia, :cuenta_bancaria)");

    // Enlazar los parámetros
    $biografia = null; // TODO Agregar despues o revisar
    $cuenta_bancaria = null; 
    
    

    $stmt->bindValue(':nombre_usuario', $nombre_usuario);
    $stmt->bindValue(':nombre', $nombre);
    $stmt->bindValue(':apellidos', $apellidos);
    $stmt->bindValue(':email', $email);
    $stmt->bindValue(':contrasena', $contrasena);
    $stmt->bindValue(':tipo_usuario', $tipo_usuario);
    $stmt->bindValue(':foto', $foto, PDO::PARAM_LOB);
    $stmt->bindValue(':fecha_nacimiento', $fecha_nacimiento);
    $stmt->bindValue(':telefono', $telefono);
    $stmt->bindValue(':biografia', $biografia);
    $stmt->bindValue(':cuenta_bancaria', $cuenta_bancaria);


    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Usuario registrado correctamente']);
    } else {
        echo json_encode(["status" => "error", "message" => "Error al registrar el usuario"]);
    }



} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
