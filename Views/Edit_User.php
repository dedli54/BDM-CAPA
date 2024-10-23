<?php 
session_start(); // Inicia la sesión

if (!isset($_SESSION['user_id'])) {
    // Muestra un alert antes de redirigir
    echo "<script>
            alert('La sesión no está iniciada. Por favor, inicia sesión.');
            window.location.href = 'inicioSesion.php';
          </script>";
    exit();
}

?>


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

    <div class="container conLogo">
        <h1 class="titulos">A&B Cursos</h1>
        </div>

<!--Navbar-->
<div class="container card">

    <nav class="navbar navbar-expand-lg navbar-light ">
      <div class="container-fluid">
          <!-- Logo de la barra de navegación -->
          <!-- <a class="navbar-brand" href="#">A&J</a> -->
          
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarOpciones" aria-controls="navbarOpciones" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
          </button>
          
          <!-- Contenido oculto en dispositivos pequeños -->
          <div class="collapse navbar-collapse" id="navbarOpciones">
              <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                  <li class="nav-item border-end">
                      <a class="nav-link active textos-2" aria-current="page" href="landPage.php">Principal</a>
                  </li>

                  <li class="nav-item border-end">
                      <a class="nav-link active textos-2" aria-current="page" href="perfil.php">Mi perfil</a>
                  </li>

                  <li class="nav-item border-end">
                      <a class="nav-link active textos-2" aria-current="page" href="chat.html">Mis chats</a>
                  </li>

                  
                  <!-- Desplegable de opciones -->
                  <li class="nav-item dropdown border-end">
                      <a class="nav-link dropdown-toggle textos-2" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                          Categorias
                      </a>
                      <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                          <li><a class="dropdown-item textos-2" href="Busqueda.html">Arte</a></li>
                          <li><a class="dropdown-item textos-2" href="Busqueda.html">Matematicas</a></li>
                          <li><a class="dropdown-item textos-2" href="Busqueda.html">Programacion</a></li>
                           <!--li><hr class="dropdown-divider"></li> 
                          <li><a class="dropdown-item textos-2" href="#">Cerrar sesion</a></li--> <!--style letras rojas-->

                      </ul>
                  </li>

                  <li class="nav-item">
                  <a class="nav-link active textos-2" aria-current="page" href="../Controllers/logout.php">Cerrar sesion</a>
                  </li>
              </ul>
  
              <!-- Formulario de búsqueda ajustable -->
              <form class="d-flex w-auto w-md-50 w-lg-50">
                  <input class="form-control me-2 textos-2" type="search" placeholder="Buscar" aria-label="Buscar">
                  <button class="btn btn-outline-dark textos-2" type="submit" formaction="Busqueda.html">Buscar</button>
              </form>
  
          </div>
      </div>
  </nav>
  
</div>





<div class="container mt-5" style="margin-bottom: 5%;">
    <div class="card">
        <div class="card-header text-center">
            <h4 class="fs-1 titulos">Actualiza tus datos</h4>
        </div>
        <div class="card-body">
            <form id="editarUsuario">
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
                            <input type="password" class="form-control rounded-5" id="pw" name="pw" placeholder="Ingresa tu contraseña">
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


<script src="JS/mostrarFotos.js"></script>

<script src="JS/Edit_User.js"></script>
<script src="JS/bootstrapJS/bootstrap.bundle.min.js"></script>
</body>
</html>