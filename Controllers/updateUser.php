<?php
require_once '../conexion.php';

$conexion = new Conexion();
$conn = $conexion->conectar();

if ($conn === null) {
    die("No se pudo conectar a la base de datos.");
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_start();

    $user_id = $_SESSION['user_id'];
    $tipo_usuario = $_SESSION['user_type'];

    // Captura los datos del formulario
    $nombre_usuario = $_POST['nombre'];
    $nombre = $_POST['nombre']; // Suponiendo que tienes un campo con id 'nombre'
    $apellidos = $_POST['apellido']; // Ojo
    $email = $_POST['email'];
    $contrasena = $_POST['pw'];
    
    $foto = null; //
    $fecha_nacimiento = $_POST['fecha'];
    $telefono = $_POST['telefono'];
    $biografia = ""; // 
    $cuenta_bancaria = ""; // 

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $foto = file_get_contents($_FILES['foto']['tmp_name']);
    }

    // Preparar la consulta usando PDO
    $stmt = $conn->prepare("CALL sp_editar_usuario(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    // Vincular parámetros usando bindValue
    $stmt->bindValue(1, $user_id, PDO::PARAM_INT);
    $stmt->bindValue(2, $nombre_usuario, PDO::PARAM_STR);
    $stmt->bindValue(3, $nombre, PDO::PARAM_STR);
    $stmt->bindValue(4, $apellidos, PDO::PARAM_STR);
    $stmt->bindValue(5, $email, PDO::PARAM_STR);
    $stmt->bindValue(6, $contrasena, PDO::PARAM_STR); // Encriptar la contraseña
    $stmt->bindValue(7, $tipo_usuario, PDO::PARAM_INT);
    $stmt->bindValue(8, $foto, PDO::PARAM_LOB); // Para el BLOB
    $stmt->bindValue(9, $fecha_nacimiento, PDO::PARAM_STR);
    $stmt->bindValue(10, $telefono, PDO::PARAM_INT);
    $stmt->bindValue(11, $biografia, PDO::PARAM_STR); // 
    $stmt->bindValue(12, $cuenta_bancaria, PDO::PARAM_STR); // 

    // Ejecutar el procedimiento
    if ($stmt->execute()) {
        echo "Usuario editado exitosamente.";
    } else {
        echo "Error: " . implode(", ", $stmt->errorInfo()); // Muestra errores detallados
    }

    $stmt->closeCursor(); // Cierra el cursor
    $conn = null; // Cierra la conexión
}
?>