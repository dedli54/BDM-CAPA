
<?php
session_start(); 
session_unset(); 
session_destroy(); 

header('Location: ../Views/inicioSesion.php'); // Redirige a la página de inicio de sesión
exit();