<?php
session_start();
require '../conexion.php';

try {
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('Usuario no autenticado');
    }

    $id_alumno = $_SESSION['user_id'];
    $id_curso = $_POST['curso_id'];
    $texto = $_POST['comentario'];
    $calificacion = $_POST['calificacion'];

    $conexion = new Conexion();
    $pdo = $conexion->conectar();

    $stmt = $pdo->prepare("CALL sp_agregar_comentario(?, ?, ?, ?)");
    $stmt->execute([$id_alumno, $id_curso, $texto, $calificacion]);

    header('Location: ../Views/CursoVer.php?id=' . $id_curso . '&success=true');

} catch (Exception $e) {
    header('Location: ../Views/CursoVer.php?id=' . $id_curso . '&error=' . urlencode($e->getMessage()));
}
?>