<?php 
session_start(); // Inicia la sesión

if (!isset($_SESSION['user_id'])) {
    http_response_code(401); // Respuesta 401 si no está autenticado
    echo json_encode(['error' => 'La sesión no está iniciada.']);
    exit();
}

header('Content-Type: application/json'); // Configura el encabezado para JSON

// Aquí va tu lógica para obtener datos de perfil
$data = [
    'user_id' => $_SESSION['user_id'],
    'user_type' => $_SESSION['user_type'],
    // Otras propiedades del perfil que quieras incluir
];
echo json_encode($data); // Envía el JSON al cliente
?>
