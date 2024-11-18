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


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado de busqueda</title>
    <link rel="stylesheet" href="CSS/bootstrapCSS/bootstrap.min.css">

    
    <link rel="stylesheet" href="CSS/colores.css">
    <link rel="stylesheet" href="CSS/landPage.css">


</head>


<body class="d-flex flex-column min-vh-100">

    <div class="container conLogo">
        <h1 class="titulos">A&B Cursos</h1>
        </div>
    
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

<!--  Filtros + cosos  -->
<div class="container mt-5"  >
    <div class="card " > 
        
        <div class="card-body">

<!-- Barra de Filtros -->
              <div class="container my-3">
                  <div class="row">
                    <div class="col-auto ms-auto">
                      <!-- Barra de Filtros -->
                      <div class="d-flex flex-wrap align-items-center gap-3">
                        <!-- Rango de Fechas -->
                        <div>
                          <label for="fecha1" class="form-label subtitulos" id="dtpInicio">Fecha Inicio</label>
                          <input type="date" id="fecha1" class="form-control">
                        </div>
                        <div>
                          <label for="fecha2" class="form-label subtitulos" >Fecha Fin</label>
                          <input type="date" id="fecha2" class="form-control">
                        </div>
                
                        <!-- Select -->
                        <div>
                          <label for="categoriaID" class="form-label subtitulos">Categoría</label>
                          <select id="categoriaID" class="form-select">
                            <option selected>Todas</option>
                            <option value="1">Matematicas</option>
                            <option value="2">Arte</option>
                          </select>
                        </div>
                
                        <!-- Casillas de Verificación -->
                        
                      </div>
                    </div>
                  </div>
                </div>


                <h5 class="subtitulos-categoria">Resultados de "<?php echo $textBuscar; ?>":</h5>
  
      
        </div>
    </div>
</div>

          
                  <!--                                                                 Cajita con cursos-->
                  <div class="container cont-Cursos px-2 ">
                    <div class="row rowCursos gx-5" >
                
                
                        <!--Aqui agregar cada curso-->
                
                        <div class="card col-lg col-md-5 col-sm-11 px-0" >
                            <div class="row no-gutters">
                                <div class="col-5">
                                    <div class="card-body">
                                        <h4 class="card-title subtitulos">Nombre curso</h4>
                                        <p class="subtitulos-categoria small">Autor | Categoria</p>
                                        <p class="textos">Info de lo que se trata el curso...</p>
                                        <p class="textos">Costo $20.50</p>
                                        <a href="CursoComprar.html" class="btn btn-primary btn-sm">Leer más</a>
                                    </div>
                                </div>
                                <div class="col-7">
                                    <img class="img-fluid h-100 imgCard" src="IMG/sql.png" alt="Imagen del curso">
                                </div>
                            </div>
                        </div>  
                
                        <div class="card col-lg col-md-5 col-sm-11 px-0">
                            <div class="row no-gutters">
                                <div class="col-5">
                                    <div class="card-body">
                                        <h4 class="card-title subtitulos">Nombre curso</h4>
                                        <p class="subtitulos-categoria small">Autor | Categoria</p>
                                        <p class="textos">Info de lo que se trata el curso</p>
                                        <p class="textos">Costo $20.50</p>
                                        <a href="CursoVer.html" class="btn btn-primary btn-sm">Ver completo</a>
                                    </div>
                                </div>
                                <div class="col-7">
                                    <img class="img-fluid h-100 imgCard" src="IMG/sql.png" alt="Imagen del curso">
                                </div>
                            </div>
                        </div>  
                
                        <div class="card col-lg col-md-5 col-sm-11 px-0">
                            <div class="row no-gutters">
                                <div class="col-5">
                                    <div class="card-body">
                                        <h4 class="card-title subtitulos">Nombre curso</h4>
                                        <p class="subtitulos-categoria small">Autor | Categoria</p>
                                        <p class="textos">Info de lo que se trata el curso</p>
                                        <p class="textos">Costo $20.50</p>
                                        <a href="#" class="btn btn-primary btn-sm">Leer más</a>
                                    </div>
                                </div>
                                <div class="col-7">
                                    <img class="img-fluid h-100 imgCard" src="IMG/sql.png" alt="Imagen del curso">
                                </div>
                            </div>
                        </div>          
                
                    </div>
                
                        
                
                    </div>




<main class="flex-grow-1 container">
  <hr class="opZero"><hr class="opZero"><hr class="opZero">
</main>

<footer class="text-center mt-auto">
    <div class="container">
        <p>&copy; J&A. Todos los derechos reservados.</p>
        <p>
            <a href="#" class="text-white me-3">Privacidad</a>
        </p>
    </div>
</footer>




<script src="JS/bootstrapJS/bootstrap.bundle.min.js"></script>
</body>
</html>