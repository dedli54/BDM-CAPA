

<!DOCTYPE HTML>
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<title>Perfil</title>
<!--script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous"-->
<link rel="stylesheet" href="CSS/bootstrapCSS/bootstrap.min.css">
<link rel="stylesheet" href="CSS/colores.css">
<link rel="stylesheet" href="CSS/landPage.css"> <!--Necesaria para dar formato a las Tarjetas Curso-->
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


    <hr class="opZero">

    <div class="container mt-5 "  >
        <div class="card" > 
            <!--div class="card-header text-center">
                <h3 class="titulos">Niveles del curso</h3>
            </div-->
            <div class="card-body">

                <div class="row">
                    <div class="col-3 d-flex justify-content-end">
                            <img src="IMG/sql.png" alt="Imagen circular" class="img-fluid rounded-circle fotoPerfil" >

                    </div>
                    <div class="col-8">

                        <h3 class="titulos">Nombre</h3>
                        <h4 class="subtitulos alumno">  Rol: Alumno</h4>
                        <h4 class="subtitulos profesor">Rol: Profesor</h4>
                        <h4 class="subtitulos admin">   Rol: Admin</h4>
                        <p class="textos-2">81-8080-8081     emeil@email.com</p>


                    </div>

                </div>

            </div>

            
        </div>
    </div>

    <div class="d-flex justify-content-end btnDiv"> 
        <a href="Edit_User.php"><button class="btn btn-lg btn-dark">Editar mis datos</button></a>
      </div>

      <div class="d-flex justify-content-end btnDiv">

        <a href="chat.html"><button class="btn btn-lg btn-dark" type="submit">Mis chats</button></a>

      </div>

    <div class="container">
        <hr class="opZero">
    <h2 class="titulos">Mis cursos</h2><hr>
    </div>
    
    <div class="container cont-Cursos px-2 ">
        <div class="row rowCursos gx-5" >
    
    
            <!--Aqui agregar cada curso-->
    
            
    
            <div class="card col-lg-5 col-md-5 col-sm-11 px-0">
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
             
    
        </div>
    
            
    
        </div>

        <div class="container">
            <hr class="opZero">
        <h2 class="titulos">Reportes</h2><hr>
        <p class="subtitulos-categoria">*Solo se verá uno dependiendo el tipo de perfil (alumno, instructor ó admin)</p>
        </div>

        <div class="container">
            <div class="row">
                <div class="col d-flex justify-content-between">
                    <a href="Kardex.html" class="btn btn-dark alumno">Ver Kardex</a>
                    <a href="reportVenta.html" class="btn btn-dark profesor">Reporte de Ventas</a>
                    <a href="reportUsers.html" class="btn btn-dark admin">Reporte de Usuarios</a>
                </div>
            </div>
        </div>

        <hr>
        <div class="d-flex justify-content-end btnDiv"> 
            <button class="btn btn-lg btn-dark admin" id="buyCurso">Agregar Categoria</button>
        </div>
        <div class="d-flex justify-content-end btnDiv"> 
            <button class="btn btn-lg btn-dark admin" id="">Agregar Admin</button>
        </div>
            <hr class="opZero">


            <div class="d-flex justify-content-end btnDiv">

            <a href="cursoNuevo.html"><button class="btn btn-lg btn-dark profesor" type="submit">Crear curso</button></a>

          </div>



        <!--                FORMS OCULTO PARA AGREGAR CATEGORIA         -->
        <div id="overlay" class="overlay"></div>
        <div class="container card buyForms" id="formBuy">
            






                        <form>

                            

                            <div class="row d-flex justify-content-center">
                                <div class="col-5">
                                    <div class="mb-3">
                                    <label for="vencimiento" class="form-label">Nombre de categoria (no repetir)</label>
                                    <input type="text" class="form-control" id="vencimiento" placeholder="Categoria New" required>
                                    </div>
                                </div>


                            </div>


                            <div class="d-flex justify-content-end "> 
                                <button type="submit" class="btn btn-lg btn-dark" id="buyCurso">Agregar</button>
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

    
<script src="JS/perfil.js"></script>
</body>