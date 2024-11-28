<?php
session_start(); // Asegúrate de iniciar la sesión al principio del archivo PHP
$textBuscar = isset($_SESSION['textBuscar']) ? htmlspecialchars($_SESSION['textBuscar']) : ''; // Verifica si 'textBuscar' está definido

if (!isset($_SESSION['user_id'])) {
    // Muestra un alert antes de redirigir
    echo "<script>
            alert('La sesión no está iniciada. Por favor, inicia sesión.');
            window.location.href = 'inicioSesion.php';
          </script>";
    exit();
}


require_once __DIR__ . '/../Core/bootstrap.php';

use Core\Middleware\RoleMiddleware;


$middleware = new RoleMiddleware();
$middleware->handle('admin');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="CSS/bootstrapCSS/bootstrap.min.css">

    
    <link rel="stylesheet" href="CSS/colores.css">
    <link rel="stylesheet" href="CSS/formNewUser.css">

    
    <title>Alta administrador</title>
</head>
<body class="d-flex flex-column min-vh-100">

    <div class="container conLogo">
        <h1 class="titulos">A&B Cursos</h1>
        </div>

<!--Navbar-->
    <div class="container card">

        <nav class="navbar navbar-expand-lg navbar-light ">
        <div class="container-fluid">
            
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarOpciones" aria-controls="navbarOpciones" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            
            <div class="collapse navbar-collapse" id="navbarOpciones">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item border-end">
                        <a class="nav-link active textos-2" aria-current="page" href="landPage.php">Principal</a>
                    </li>

                    <li class="nav-item border-end">
                        <a class="nav-link active textos-2" aria-current="page" href="perfil.php">Mi perfil</a>
                    </li>

                    <li class="nav-item border-end">
                        <a class="nav-link active textos-2" aria-current="page" href="chat.php">Mis chats</a>
                    </li>

                    
                    

                    <li class="nav-item">
                        <a class="nav-link active textos-2" aria-current="page" href="../Controllers/logout.php">Cerrar sesion</a>
                    </li>
                </ul>

                <!-- Form de búsqueda  -->
                <form class="d-flex w-auto w-md-50 w-lg-50" method="POST" action="../Controllers/guardarBusqueda.php">
                    <input class="form-control me-2 textos-2" type="search" name="textBuscar" placeholder="Buscar" aria-label="Buscar">
                    <button class="btn btn-outline-dark textos-2" type="submit">Buscar</button>
                </form>

            </div>
        </div>
        </nav>

    </div>



    <div class="container mt-5" style="margin-bottom: 5%;">
        <div class="card">
            <div class="card-header text-center">
                <h4 class="fs-1 titulos">Registrar nuevo Admin</h4>
            </div>
            <div class="card-body">
                <form id="newUsuario">
                    <div class="row">
                        <!-- Columna 1 -->
                        <div class="col-md-11  col-lg-6">
                            <div class="mb-3">
                                <label for="nombre_usuario">Nombre de usuario:</label>
                                <input class="form-control rounded-5" type="text" name="Nombre de Usuario" id="nombre_usuario" placeholder="Nombre de Usuario">
                            </div>
                            <div class="mb-3">
                                <label for="apellido">Apellido:</label>
                            <input class="form-control rounded-5" type="text" name="apellidos" id="apellido" placeholder="Apellido">
                            </div>
                            <div class="mb-3">
                                <label for="email">Correo Electrónico:</label>
                                <input class="form-control rounded-5" type="email" name="email" id="email" placeholder="Correo electrónico">
                            </div>
                            <div class="mb-3">
                                <label for="telefono">Teléfono:</label>
                                <input class="form-control rounded-5 no-spin" type="number" name="telefono" id="telefono" placeholder="Teléfono" min="0" step="1">
                            </div>
                            <div class="mb-3">
                                <label for="fecha_nacimiento">Fecha de nacimiento:</label>
                                <input class="form-control rounded-5" type="date" name="fecha_nacimiento" id="fecha_nacimiento">
                            </div>



                            
                            
                            
                            
                        </div>
    
    
                        <!-- Columna 2 -->
                        <div class="col-md-11  col-lg-6">
                            <div class="mb-3">
                                <label for="contraseña">Contraseña:</label>
                                <input class="form-control rounded-5" type="password" name="contraseña" id="contraseña" placeholder="Contraseña">
                            </div>
                            <div class="mb-3">
                                <label for="genero">Género</label>
                                <select class="form-select rounded-5" name="Género" id="genero">
                                    <option selected>Género</option>
                                    <option value="1">Masculino</option>
                                    <option value="2">Femenino</option>
                                </select>
                            </div>

                            <!--div class="mb-3">
                                <label for="">Selecciona una imagen:</label>
                                <input class="form-control rounded-5" type="file" name="imagen" id="imagen" accept="image/jpeg, image/png">
                            </div-->

                            <div class="mb-3 ">
                                <label for="foto" class="">Selecciona una imagen</label>
                                <input type="file" class="form-control rounded-5" id="foto" accept="image/jpeg, image/png">
                            </div>
                            <div class="img-container d-flex justify-content-center">
                                <img id="preview" class="img-preview rounded-5" alt="Foto de perfil seleccionada">
                            </div>

                            <div class="mb-3">
                                <label for="tipo_de_cuenta">Tipo de cuenta:</label>
                                <select class="form-select rounded-5" name="Tipo de cuenta" id="tipo_de_cuenta">
                                    <option selected>Tipo de cuenta</option>
                                    <option value="1">Administrador</option>
                                </select>
                            </div>

                            
                            
                            <div class="text-center">
                                
                                <button type="submit" class="btn btn-dark btn-lg">Crear nuevo Admin</button>
                            </div>

                        </div>
                    </div>
    
    
    
    
                    
                </form>
            </div>
        </div>
    </div>

    

    <script src="JS/mostrarFotos.js"></script>
    <script src="JS/NewAdmin.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>  
</body>
</html>