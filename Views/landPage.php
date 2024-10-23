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


<!DOCTYPE HTML>
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<title>Principal</title>
<!--script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous"-->
<link rel="stylesheet" href="CSS/bootstrapCSS/bootstrap.min.css">
<link rel="stylesheet" href="CSS/colores.css">
<link rel="stylesheet" href="CSS/landPage.css">




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

                        <li class="nav-item"></li>
                            <a class="nav-link active textos-2" aria-current="page" href="../Controllers/logout.php">Cerrar sesion</a>
                        </li>
                    </ul>
        
                    <!-- Formulario de búsqueda ajustable -->
                    <form class="d-flex w-auto w-md-50 w-lg-50">
                        <input class="form-control me-2 textos-2" type="search" placeholder="Buscar" aria-label="Buscar">
                        <button class="btn btn-outline-dark textos-2" type="submit">Buscar</button>
                    </form>
        
                </div>
            </div>
        </nav>
        
</div>

    <hr class="opZero">
    
        <div class="container">
            <h2 class="titulos">Cursos recientes</h2>
            <p class="textos"> Aquí encontrarás los cursos recien agregados a nuestra plataforma.</p>
        </div>      
          
          
          <!--                                                                       Cajita con cursos-->
        <div class="container cont-Cursos px-2 ">
        <div class="row rowCursos gx-5" >
    
    
            <!--Aqui agregar cada curso-->
    
            <div class="card col-lg col-md-5 col-sm-11 px-0" >
                <div class="row no-gutters">
                    <div class="col-5">
                        <div class="card-body">
                            <h4 class="card-title subtitulos">SQL Basico</h4>
                            <p class="subtitulos-categoria small">Autor | Categoria</p>
                            <p class="textos">Introduccion a la materia SQL donde...</p>
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
                            <h4 class="card-title subtitulos">Bootstrap</h4>
                            <p class="subtitulos-categoria small">Autor | Categoria</p>
                            <p class="textos">Info de lo que se trata el curso</p>
                            <p class="textos">Costo $20.50</p>
                            <a href="cursosVer.html" class="btn btn-primary btn-sm">Ver completo</a>
                        </div>
                    </div>
                    <div class="col-7">
                        <img class="img-fluid h-100 imgCard" src="IMG/boots.jpg" alt="Imagen del curso">
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

        
        <div class="container"><hr>
            <h2 class="titulos">Top ventas</h2>
            <p class="textos">Aquí se mostrarán los cursos más vendidos</p>
        </div>  


                  <!--                                                                Cajita con cursos-->
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
                                        <a href="cursosVer.html" class="btn btn-primary btn-sm">Ver completo</a>
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

        <div class="container"><hr>
            <h2 class="titulos">Mejor calificados</h2>
            <p class="textos">Aquí se mostrarán todos los cursos con mejor califiación</p>
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
                                        <a href="cursosVer.html" class="btn btn-primary btn-sm">Ver completo</a>
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
    </main>


    <footer class="text-center">
        <div class="container">
            <p class="titulos">&copy; J&A cursos. Todos los derechos reservados.</p>
            <p class="titulos">
                <a href="#" class="text-white me-3">Privacidad</a>
            </p>
        </div>
    </footer>

    
<script src="JS/bootstrapJS/bootstrap.bundle.min.js"></script>
</body>