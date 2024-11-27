<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require '../conexion.php';

// test de conexcion 
try {
    $conexion = new conexion();
    $pdo = $conexion->conectar();
    
    if (!$pdo) {
        throw new Exception('Could not connect to database');
    }
} catch (Exception $e) {
    die("Connection error: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['mensaje'])) {
    $conexion = new Conexion();
    $pdo = $conexion->conectar();
    
    $stmt = $pdo->prepare("INSERT INTO mensajes (emisor_id, receptor_id, mensaje) VALUES (?, ?, ?)");
    $stmt->execute([
        $_SESSION['user_id'],
        $_POST['receptor_id'],
        $_POST['mensaje']
    ]);
}

header('Location: chat.php?user=' . $_POST['receptor_id']);