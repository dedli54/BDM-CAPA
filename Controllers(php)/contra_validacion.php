<?php
// Función para validar la contraseña usando regex
function validar_contrasena($password) {
    $regex = "/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/";
    return preg_match($regex, $password);
}

// Procesar el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir los datos del formulario
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $password = $_POST['pw'];
    $password_conf = $_POST['pw_conf'];
    $telefono = $_POST['telefono'];
    $fecha = $_POST['fecha'];
    $genero = $_POST['genero'];
    $cuenta = $_POST['cuenta'];

    // Validar que las contraseñas coincidan
    if ($password !== $password_conf) {
        echo "Las contraseñas no coinciden.";
        exit;
    }

    // Validar la contraseña con regex
    if (validar_contrasena($password)) {
        // La contraseña es válida, puedes proceder con el registro
        // Encriptar la contraseña antes de guardarla
        // $password_hash = password_hash($password, PASSWORD_DEFAULT);

        echo "Registro exitoso.";
    } else {
        // La contraseña no cumple con los requisitos
        echo "La contraseña debe tener al menos 8 caracteres, una letra mayúscula, un número y un carácter especial.";
    }
}
?>
