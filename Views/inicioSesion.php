
<?php require '../conexion.php'; ?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesion</title>
    <link rel="stylesheet" href="CSS/bootstrapCSS/bootstrap.min.css">


    <link rel="stylesheet" href="CSS/colores.css">
    <link rel="stylesheet" href="CSS/formNewUser.css">

</head>
<body class="d-flex flex-column min-vh-100">

<div class="container mt-5 cardFondo" >
    <div class="card cardLogin">
        <div class="card-header text-center">
            <h4 class="fs-1 titulos">Iniciar Sesion</h4>
        </div>

        <div class="card-body ">
            <form id="loginForm">
                <div class="row justify-content-center">
                    <!-- Columna 1 -->
                    <div class="col-8">
                        <div class="mb-3">
                            <label for="email" class="form-label subtitulos fs-5 fs-md-1 fs-lg-3 fs-xl-4">Correo:</label>
                            <input type="text" class="form-control rounded-5" id="email" name="email" placeholder="Ingresa tu correo">
                        </div>
                        <div class="mb-3">
                            <label for="pw" class="form-label fs-5 fs-md-2 fs-lg-3 fs-xl-4 subtitulos">Contraseña:</label>
                            <input type="password" class="form-control rounded-5" id="pw" name="password" placeholder="Ingresa tu contraseña" > <!-- maxlength="8" -->
                        </div>
                    </div>


                    
                </div>

                <div class="row text-center">
                    <div class="col">
                        <button type="button" class="btn btn-outline-dark" onclick="window.location.href='NewUser.php'">Crear usuario</button>
                    </div>
                    
                    <div class="col">
                        <button type="submit" class="btn btn-dark">Enviar</button></div>
                </div>
            </form>
        </div>
    </div>
</div>

<main class="flex-grow-1 container">

</main>

<footer class="text-center mt-auto">
    <div class="container">
        <p>&copy; A&J Cursos todos los derechos reservados.</p>
        <p>
        </p>
    </div>
</footer>


<script src="JS/inicioSesion.js"></script>

<script src="JS/bootstrapJS/bootstrap.bundle.min.js"></script>
</body>
</html>
