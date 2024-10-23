<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear usuario</title>
    <link rel="stylesheet" href="CSS/bootstrapCSS/bootstrap.min.css">



    <link rel="stylesheet" href="CSS/colores.css">
    <link rel="stylesheet" href="CSS/formNewUser.css">


</head>
<body class="d-flex flex-column min-vh-100"> 

<div class="container mt-5" style="margin-bottom: 5%;">
    <div class="card">
        <div class="card-header text-center">
            <h4 class="fs-1 titulos">Registrate</h4>
        </div>
        <div class="card-body">
            <form id="newUsuario">
                <div class="row">
                    <!-- Columna 1 -->
                    <div class="col-md-11  col-lg-6">
                        <div class="mb-3">
                            <label for="nombre" class="form-label fs-3 subtitulos">Nombre</label>
                            <input type="text" class="form-control rounded-5" id="nombre" name="nombre" placeholder="Ingresa tu nombre">
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fs-3 subtitulos">Correo Electrónico</label>
                            <input type="text" class="form-control rounded-5" id="email" name="email" placeholder="Ingresa tu correo">
                        </div>

                        <div class="mb-3">
                            <label for="pw" class="form-label fs-3 subtitulos">Contraseña</label>
                            <input type="password" class="form-control rounded-5" id="pw" id="pw" placeholder="Ingresa tu contraseña">
                        </div>

                        <div class="mb-3">
                            <label for="pw_conf" class="form-label fs-3 subtitulos">Confirmar contraseña</label>
                            <input type="password" class="form-control rounded-5" id="pw_conf" placeholder="Confirma tu contraseña">
                        </div>

                        <div class="mb-3 ">
                            <label for="foto" class="form-label fs-3 subtitulos">Selecciona una imagen</label>
                            <input type="file" class="form-control rounded-5" id="foto" name="foto" accept="image/jpeg, image/png">
                        </div>
                        <div class="img-container d-flex justify-content-center">
                            <img id="preview" class="img-preview rounded-5" alt="Foto de perfil seleccionada">
                        </div>

                        
                    </div>


                    <!-- Columna 2 -->
                    <div class="col-md-11  col-lg-6">
                        <div class="mb-3">
                            <label for="apellido" class="form-label fs-3 subtitulos">Apellido</label>
                            <input type="text" class="form-control rounded-5" id="apellido" name="apellido" placeholder="Ingresa tu apellido">
                        </div>
                        <div class="mb-3">
                            <label for="telefono" class="form-label fs-3 subtitulos">Teléfono</label>
                            <input type="number" class="form-control rounded-5 no-spin" id="telefono" name="telefono" placeholder="Ingresa tu teléfono" min="0" step="1">
                        </div>

                        <div class="mb-3">
                            <label for="fecha" class="form-label fs-3 subtitulos">Fecha de nacimiento</label>
                            <input type="date" class="form-control rounded-5" id="fecha" name="fecha">
                        </div>

                        <div class="mb-3">
                            <label for="genero" class="form-label fs-3 subtitulos">Genero</label>
                                <select class="form-select rounded-5" id="genero" name="genero">
                                    <option selected>Genero</option>
                                    <option value="1">Hombre</option>
                                    <option value="2">Mujer</option>
                                    <option value="3">Otro</option>
                                </select>
                        </div>

                        <div class="mb-3">
                            <label for="cuenta" class="form-label fs-3 subtitulos">Tipo de cuenta</label>
                                <select class="form-select rounded-5" id="cuenta" name="cuenta">
                                    <option selected>Tipo de cuenta</option>
                                    <option value="1">Estudiante</option>
                                    <option value="2">Profesor</option>
                                </select>
                        </div>

                        <!-- Boton -->
                        <div class="text-center">
                            <button type="submit" class="btn btn-dark btn-lg">Enviar</button>
                        </div>
                    </div>
                </div>




                
            </form>
        </div>
    </div>
</div>

<main class="flex-grow-1 container">

</main>

<footer class="text-center">
    <div class="container">
        <p>&copy; A&J Cursos todos los derechos reservados.</p>
        
    </div>
</footer>



<script src="JS/NewUser.js"></script>
<script src="JS/bootstrapJS/bootstrap.bundle.min.js"></script>
</body>
</html>
