<?php
session_start(); // Inicia la sesión

$textBuscar = isset($_SESSION['textBuscar']) ? htmlspecialchars($_SESSION['textBuscar']) : ''; // No recuerdo si se usa o solo en Busqueda, ahorita

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

try {
    
    // Get courses created by the professor (user)
    $stmt = $pdo->prepare("CALL sp_obtener_cursos(?, ?, ?)");
    $stmt->execute([0, 'profesor', $_SESSION['user_id']]);
    $cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();

} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}

try {
    
    // Obtener info del alumno (user)
    $stmt = $pdo->prepare("select * from usuario where id = ?");
    $userId = (int) $_SESSION['user_id'];
    $stmt->execute([$userId]);
    $usuario = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();


    if ($usuario[0]['foto']) {
        $fotoBase64 = base64_encode($usuario[0]['foto']);
        $fotoSrc = "data:image/jpeg;base64," . $fotoBase64;
    } else {
        // Imagen predeterminada
        $fotoSrc = "IMG/sql.png";
    }

} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}

?>


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
            <!--div class="card-header text-center">
                <h3 class="titulos">Niveles del curso</h3>
            </div-->
            <div class="card-body">

                <div class="row">
                <div class="col-3 d-flex justify-content-end">
    <img src="<?php echo $fotoSrc; ?>" alt="Imagen circular" class="img-fluid rounded-circle fotoPerfil" style="max-width: 100%; height: auto;">
</div>
                    <div class="col-8">

                        <h3 class="titulos"><?= htmlspecialchars($usuario[0]['nombre']) ?> <?= htmlspecialchars($usuario[0]['apellidos']) ?></h3>
                        <h4 class="subtitulos alumno">  Rol: Alumno</h4>
                        <h4 class="subtitulos profesor">Rol: Profesor</h4>
                        <h4 class="subtitulos admin">   Rol: Admin</h4>
                        <p class="textos-2"><?= htmlspecialchars($usuario[0]['telefono']) ?> | <?= htmlspecialchars($usuario[0]['email']) ?></p>


                    </div>

                </div>

            </div>

            
        </div>
    </div>

    <div class="d-flex justify-content-end btnDiv"> 
        <a href="Edit_User.php"><button class="btn btn-lg btn-dark">Editar mis datos</button></a>
      </div>

      <div class="d-flex justify-content-end btnDiv">

        <a href="chat.php"><button class="btn btn-lg btn-dark" type="submit">Mis chats</button></a>

      </div>

    <div class="container">
        <hr class="opZero">
    <h2 class="titulos profesor">Mis cursos creados</h2><hr class = "profesor">
    </div>
    
    <div class="container cont-Cursos px-2 profesor">
    <div class="row rowCursos gx-5">
    <?php if (!empty($cursos)): ?>
        <?php foreach ($cursos as $curso): ?>
            <div class="card col-lg-5 col-md-5 col-sm-11 px-0">
                <div class="row no-gutters">
                    <div class="col-5">
                        <div class="card-body">
                            <h4 class="card-title subtitulos"><?= htmlspecialchars($curso['titulo']) ?></h4>
                            <p class="subtitulos-categoria small"><?= htmlspecialchars($curso['categoria']) ?></p>
                            <p class="textos">Alumnos inscritos: <?= htmlspecialchars($curso['Alumnos_inscritos']) ?></p>
                            <p class="textos">Ingresos: $<?= number_format($curso['Ingresos_totales'], 2) ?></p>
                            <div class="btn-group">
                                <a href="Edit_Curso.php?id=<?= $curso['id'] ?>" class="btn btn-primary btn-sm">Editar curso</a>
                                <button onclick="deleteCourse(<?= $curso['id'] ?>)" class="btn btn-danger btn-sm">Eliminar curso</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-7">
                        <img class="img-fluid h-100 imgCard" 
                             src="data:image/jpeg;base64,<?= base64_encode($curso['foto']) ?>" 
                             alt="<?= htmlspecialchars($curso['titulo']) ?>">
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="text-center textos">No has creado ningún curso todavía.</p>
    <?php endif; ?>
</div>
</div>

        <div class="container">
            <hr class="opZero">
        <h2 class="titulos">Reportes</h2><hr>
        
        </div>

        <div class="container">
            <div class="row">
                <div class="col d-flex flex-column">
                    <a href="Kardex.php" class="btn btn-dark alumno btn-block mb-2">Ver Kardex</a>
                    <a href="reportVenta.php" class="btn btn-dark profesor btn-block mb-2">Reporte de Ventas</a>
                    <a href="reportUsers.php" class="btn btn-dark admin btn-block mb-2">Reporte de Usuarios</a>
                </div>
            </div>
        </div>

        <hr>
        <div class="d-flex justify-content-end btnDiv"> 
            <button class="btn btn-lg btn-dark admin" id="buyCurso">Agregar Categoria</button>
        </div>
        <div class="d-flex justify-content-end btnDiv"> 
            <!--<button class="btn btn-lg btn-dark admin" id=""  >Agregar Admin</button>  NewAdministrador -->
            <a href="NewAdministrador.php" class="btn btn-lg btn-dark admin">Crear cuenta para Admin</a>
        </div>
            <hr class="opZero">


            <div class="d-flex justify-content-end btnDiv">

            <a href="cursoNuevo.php"><button class="btn btn-lg btn-dark profesor" type="submit">Crear curso</button></a>

          </div>



        <!--                FORMS OCULTO PARA AGREGAR CATEGORIA         -->
        <div id="overlay" class="overlay"></div>
        <div class="container card buyForms" id="formCategoria">
            
                        <form action="../Controllers/crear_categoria.php" method="POST" id="formCategoria1"> 

                            

                            <div class="row d-flex justify-content-center">
                                <div class="col-5">
                                    <div class="mb-3">
                                    <label for="nameCat" class="form-label">Nombre de categoria (no repetir):</label>
                                    <input type="text" class="form-control" id="nameCat" name="nameCat" placeholder="Categoria New" required>
                                    </div>

                                    <div class="mb-3">
                                    <label for="definicion" class="form-label">Definicion:</label>
                                    <input type="text" class="form-control" id="definicion" name="definicion" placeholder="Definicion" required>
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

    
<script src="JS/addCategoria.js"></script>  <!-- Mostrar Ventana flotante  formCategoria  -->
<script src="JS/perfil.js"></script> <!-- Esconde las acciones de perfil dependiendo del tipo de usuario --> 
</body>