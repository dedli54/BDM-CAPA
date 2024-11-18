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
    
    //  Query para ver la visat 
    $stmt = $pdo->prepare("SELECT * FROM vista_curso_detalle WHERE id = ?");
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



    <hr class="opZero">

    <div class="container mt-5 "  >
        <div class="card" > 
            
            <div class="card-body">

                <div class="row allign-items-center">
                    <div class="col-12 col-md-4">
                            <img id="imgPerfil" class="rounded-2 img-curso" alt="Foto de perfil" src="IMG\sql.png" >

                    </div>
                    <div class="col-11 col-md-8"> 

                        <h2 class="titulos"><?= htmlspecialchars($curso['titulo']) ?></h2>
                        <h6 class="subtitulos-categoria">Categoria: <?= htmlspecialchars($curso['categoria']) ?></h6>
                        <p class="fs-5 textos"><?= htmlspecialchars($curso['descripcion']) ?></p>
                        <p class="fs-5 textos">Instructor: <?= htmlspecialchars($curso['autor']) ?></p>
                        <div class="d-flex justify-content-end btnDiv">
                            <button class="btn btn-lg btn-dark" id="buyCurso">
                                Comprar curso ($<?= number_format($curso['precio'], 2) ?>)
                            </button>
                        </div>


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