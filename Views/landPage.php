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

require '../conexion.php';

// test de conexcion 
try {
    $conexion = new conexion();
    $pdo = $conexion->conectar();
    
    if (!$pdo) {
        throw new Exception('Could not connect to database');
    }
} catch (Exception $e) {
    die("Connection error: " . $e->getMessage());
}

// agarrar los cursos recientes
$stmt = $pdo->prepare("CALL sp_obtener_cursos(0, 'recientes')");
$stmt->execute();
$cursos_recientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt->closeCursor();

// los mas vendidos  
$stmt = $pdo->prepare("CALL sp_obtener_cursos(0, 'vendidos')");
$stmt->execute();
$cursos_vendidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt->closeCursor();

// the bought courses are called here
$stmt = $pdo->prepare("CALL sp_obtener_cursos_comprados(?)");
$stmt->execute([$_SESSION['user_id']]);
$cursos_comprados = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt->closeCursor();

$pdo = null;
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
    
        <div class="container">
            <h2 class="titulos">Cursos disponibles</h2>
            <p class="textos"> Aquí encontrarás los cursos disponibles en la plataforma.</p>
        </div>      
          
          
          <!--                                                                       Cajita con cursos-->
        <div class="container cont-Cursos px-2">
    <div class="row rowCursos gx-5">
        <?php foreach ($cursos_recientes as $curso): ?>
            <div class="card col-lg-5 col-md-5 col-sm-11 px-0">
                <div class="row no-gutters">
                    <div class="col-5">
                        <div class="card-body">
                            <h4 class="card-title subtitulos"><?= htmlspecialchars($curso['titulo']) ?></h4>
                            <p class="subtitulos-categoria small"><?= htmlspecialchars($curso['autor']) ?> | <?= htmlspecialchars($curso['categoria']) ?></p>
                            <p class="textos"><?= htmlspecialchars($curso['descripcion']) ?></p>
                            <p class="textos">Costo $<?= number_format($curso['precio'], 2) ?></p>
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <a href="CursoComprar.php?id=<?= $curso['id'] ?>" class="btn btn-primary btn-sm">Leer más</a>
                            <?php else: ?>
                                <a href="inicioSesion.php" class="btn btn-primary btn-sm">Iniciar sesión para ver</a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-7">
                        <img class="img-fluid h-100 imgCard" 
                             src="data:image/jpeg;base64,<?= base64_encode($curso['foto']) ?>" 
                             alt="Imagen del curso">
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!----->


        
        <div class="container"><hr>
            <h2 class="titulos">Cursos comprados</h2>
            <p class="textos">Aquí estan tus cursos comprados</p>
        </div>  

        <div class="container cont-Cursos px-2">
            <div class="row rowCursos gx-5">
                <?php if (!empty($cursos_comprados)): ?>
                    <?php foreach ($cursos_comprados as $curso): ?>
                        <div class="card col-lg-5 col-md-5 col-sm-11 px-0">
                            <div class="row no-gutters">
                                <div class="col-5">
                                    <div class="card-body">
                                        <h4 class="card-title subtitulos"><?= htmlspecialchars($curso['titulo']) ?></h4>
                                        <p class="subtitulos-categoria small">
                                            <?= htmlspecialchars($curso['autor']) ?> | <?= htmlspecialchars($curso['categoria']) ?>
                                        </p>
                                        <p class="textos"><?= htmlspecialchars($curso['descripcion']) ?></p>
                                        <p class="textos">Costo $<?= number_format($curso['precio'], 2) ?></p>
                                        <a href="CursoVer.php?id=<?= $curso['id'] ?>" class="btn btn-primary btn-sm">Ver curso</a>
                                    </div>
                                </div>
                                <div class="col-7">
                                    <img class="img-fluid h-100 imgCard" 
                                         src="data:image/jpeg;base64,<?= base64_encode($curso['foto']) ?>" 
                                         alt="Imagen del curso">
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center">
                        <p class="textos">No has comprado ningún curso todavía.</p>
                    </div>
                <?php endif; ?>
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