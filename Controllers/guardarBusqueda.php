<?php
session_start();

// Verificar si se envió el texto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['textBuscar'])) {
    $_SESSION['textBuscar'] = htmlspecialchars($_POST['textBuscar']);

    header('Location: ../Views/Busqueda.php'); 
    exit;
} else {
    header('Location: ../Views/inicioSesion.php'); 
    exit;
}
?>
