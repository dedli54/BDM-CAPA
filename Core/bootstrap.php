<?php


// Inicia la sesión solo si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


require_once __DIR__ . '/Middleware/Middleware.php';
require_once __DIR__ . '/Middleware/Guest.php';
require_once __DIR__ . '/Middleware/Authenticated.php';
require_once __DIR__ . '/Middleware/RoleMiddleware.php';


