<?php
session_start();
require '../conexion.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header('Location: inicioSesion.php');
    exit();
}

try {
    $conexion = new conexion();
    $pdo = $conexion->conectar();
    
    // detalles de curso
    $stmt = $pdo->prepare("
        SELECT c.*, cat.nombre as categoria, 
        CONCAT(u.nombre, ' ', u.apellidos) as autor 
        FROM curso c
        JOIN categoria cat ON c.id_categoria = cat.id
        JOIN usuario u ON c.id_maestro = u.id 
        WHERE c.id = ? AND c.status = 1
    ");
    
    $stmt->execute([$_GET['id']]);
    $curso = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$curso) {
        throw new Exception('Course not found');
    }
    
    $stmt->closeCursor();
    $pdo = null;
    
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>
<!DOCTYPE HTML>
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<title>Curso</title>
<!--script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous"-->
<link rel="stylesheet" href="CSS/bootstrapCSS/bootstrap.min.css">
<link rel="stylesheet" href="CSS/colores.css">
<link rel="stylesheet" href="CSS/perfil.css">






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
                                              <a class="nav-link active textos-2" aria-current="page" href="landPage.html">Principal</a>
                                          </li>
                  
                                          <li class="nav-item border-end">
                                              <a class="nav-link active textos-2" aria-current="page" href="perfil.html">Mi perfil</a>
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
                  
                                          <li class="nav-item"></li>
                                              <a class="nav-link active textos-2" aria-current="page" href="inicioSesion.html">Cerrar sesion</a>
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



    <hr class="opZero">

    <div class="container mt-5 "  >
        <div class="card" > 
            
            <div class="card-body">

                <div class="row allign-items-center">
                    <div class="col-12 col-md-4">
                            <img id="imgPerfil" class="rounded-2 img-curso" alt="Foto de perfil" src="IMG\sql.png" >

                    </div>
                    <div class="col-11 col-md-8"> 

                        <h2 class="titulos">Introduccion a SQL</h2>
                        <h6 class="subtitulos-categoria">Categoria: Programacion</h6>
                        <p class=" fs-5 textos">
                            En este curso aprenderas sobre SQL (Structured Query Language) es un lenguaje estándar utilizado para gestionar y manipular bases de datos relacionales. Permite realizar diversas operaciones como consultar, actualizar, insertar o eliminar datos dentro de una base de datos.    
                        </p>


                    </div>

                </div>

            </div>
        </div>
    </div>

    <div class="container">

        

        <div class="d-flex justify-content-end btnDiv"> 
            <button class="btn btn-lg btn-dark" id="buyCurso">Comprar curso ($100.50)</button>
          </div>

    </div>
    


    <!--    Comentarios     -->
        <div class="container mt-4 comentCont justify-content-center">

            <h4 class="subtitulos">Comentarios de gente que terminó el curso:</h4>

            <div class="row centro-vertical">
            <div class="col-2 text-center">
                <!-- Foto de perfil -->
                <img src="IMG/sql.png" alt="Foto de Perfil" class="foto-perfil">
            </div>
            <div class="col-9">
                <div class="comentario">
                
                <div class="calificacion">Juan Pérez</div>
                <div class="calificacion">Calificación: ★★★☆☆</div>
                
                <p>Este curso le falto algo más de info.</p>
                </div>
            </div>
            </div>
            <hr class="w-75 mx-auto">
            <div class="row centro-vertical">
                <div class="col-2 text-center">
                    <!-- Foto de perfil -->
                    <img src="IMG/sql.png" alt="Foto de Perfil" class="foto-perfil">
                </div>
                <div class="col-9">
                    <div class="comentario">
                    
                    <div class="calificacion">Jose Aureliano</div>
                    <div class="calificacion">Calificación: ★★★★★</div>
                    
                    <p>Este curso está muy completo. Recomendadisimo.</p>
                    </div>
                </div>
                </div>
        </div>

        <!--                FORMS OCULTO PARA PAGAR         -->
        <div id="overlay" class="overlay"></div>
        <div class="container card buyForms" id="formBuy">
            
                        <form>
                            <div class="mb-3">
                            <label for="numeroTarjeta" class="form-label">Número de Tarjeta</label>
                            <input type="text" class="form-control" id="numeroTarjeta" placeholder="1234 5678 9012 3456" required>
                            </div>

                            

                            <div class="row d-flex justify-content-center">
                                <div class="col-5">
                                    <div class="mb-3">
                                    <label for="vencimiento" class="form-label">Fecha de Vencimiento</label>
                                    <input type="text" class="form-control" id="vencimiento" placeholder="MM/AA" required>
                                    </div>
                                </div>

                                <div class="col-5">
                                    <div class="mb-3">
                                        <label for="ccv" class="form-label">CCV</label>
                                        <input type="text" class="form-control" id="ccv" placeholder="123" required>
                                    </div>
                                </div>

                                <!--div class="col-5">
                                    <div class="mb-3">
                                        <label for="ccv" class="form-label">CCV</label>
                                        <input type="text" class="form-control" id="ccv" placeholder="123" required>
                                    </div>
                                </div-->
                            </div>


                            <div class="d-flex justify-content-end "> 
                                <button type="submit" class="btn btn-lg btn-dark" id="buyCurso">Pagar</button>
                              </div>

                        </form>
                    
        </div>


    <main class="flex-grow-1 container">
    <hr class="opZero"><hr class="opZero"><hr class="opZero">

    </main>


    <footer class="text-center mt-auto">
        <div class="container">
            <p class="titulos">&copy; J&A cursos. Todos los derechos reservados.</p>
            <p class="titulos">
                <a href="#" class="text-white me-3">Privacidad</a>
            </p>
        </div>
    </footer>

    
<script src="JS/comprarCurso.js"></script>
<script src="JS/bootstrapJS/bootstrap.bundle.min.js"></script>
</body>