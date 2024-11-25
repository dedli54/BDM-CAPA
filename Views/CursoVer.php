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

try {
    $curso_id = $_GET['id'] ?? 0;
    
    // get course details
    $stmt = $pdo->prepare("SELECT c.*, u.nombre as autor, cat.nombre as categoria 
                          FROM curso c 
                          JOIN usuario u ON c.id_maestro = u.id
                          JOIN categoria cat ON c.id_categoria = cat.id 
                          WHERE c.id = ?");
    $stmt->execute([$curso_id]);
    $curso = $stmt->fetch();

    // get course levels
    $stmt = $pdo->prepare("SELECT * FROM nivelesCurso 
                          WHERE id_curso = ? 
                          ORDER BY numeroNivel");
    $stmt->execute([$curso_id]);
    $niveles = $stmt->fetchAll();
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

    <!-- Course header -->
    <div class="container mt-5">
        <div class="row">
            <div class="col-12">
                <h2 class="titulos"><?= htmlspecialchars($curso['titulo']) ?></h2>
                <p class="subtitulos-categoria">Por: <?= htmlspecialchars($curso['autor']) ?> | <?= htmlspecialchars($curso['categoria']) ?></p>
            </div>
        </div>
    </div>

    <!-- Course levels -->
    <div class="container">
        <?php if (empty($niveles)): ?>
            <div class="alert alert-info">
                Este curso aún no tiene niveles disponibles.
            </div>
        <?php else: ?>
            <?php foreach($niveles as $nivel): ?>
                <div class="row nivel" id="nivel-<?= $nivel['numeroNivel'] ?>">
                    <hr class="opZero">
                    <h3 class="subtitulos">Nivel <?= htmlspecialchars($nivel['numeroNivel']) ?></h3>
                    <div class="col">
                        <?php if(!empty($nivel['video'])): ?>
                            <div class="d-flex justify-content-center">
                                <video class="w-50" controls>
                                    <source src="<?= str_replace('../Views/', '', htmlspecialchars($nivel['video'])) ?>" type="video/mp4">
                                    Tu navegador no soporta la etiqueta de video.
                                </video>
                            </div>
                        <?php endif; ?>

                        <?php if(!empty($nivel['texto'])): ?>
                            <p class="fs-4">
                                <?= nl2br(htmlspecialchars($nivel['texto'])) ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="d-flex justify-content-between mt-4">
                <button class="btn btn-dark" id="btnAnterior">Nivel Anterior</button>
                <button class="btn btn-dark" id="btnSiguiente">Siguiente Nivel</button>
                
                <button class="btn btn-dark" id="addComentBtn">Agregar comentario</button> <!-- Ver cuando sea el ultimo nivel
                + Solo ver si aún no agrega un comentario
                + Al agregarlo ya podrá ver su Diploma-->

            </div>
        <?php endif; ?>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const niveles = document.querySelectorAll('[id^="nivel-"]');
        let nivelActual = <?php 
            // current progress
            $stmt = $pdo->prepare("SELECT nivel_actual FROM progreso_curso 
                                  WHERE id_alumno = ? AND id_curso = ?");
            $stmt->execute([$_SESSION['user_id'], $curso_id]);
            $progreso = $stmt->fetch();
            echo $progreso ? $progreso['nivel_actual'] - 1 : 0;
        ?>;

        function mostrarNivel(index) {
            // hide levels
            niveles.forEach((nivel, i) => {
                nivel.style.display = i === index ? 'block' : 'none';
            });
            
            // botton visibility 
            document.getElementById('btnAnterior').style.display = index > 0 ? 'block' : 'none';
            document.getElementById('btnSiguiente').style.display = index < niveles.length - 1 ? 'block' : 'none';
            document.getElementById('addComentBtn').style.display = index === niveles.length - 1 ? 'block' : 'none';

            // save progress
            fetch('../Controllers/actualizarProgreso.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    curso_id: <?php echo $curso_id; ?>,
                    nivel: index + 1
                })
            }).catch(error => console.error('Error:', error));

            // update current level
            nivelActual = index;
        }

        
        document.getElementById('btnAnterior').addEventListener('click', function() {
            if (nivelActual > 0) {
                mostrarNivel(nivelActual - 1);
            }
        });

        document.getElementById('btnSiguiente').addEventListener('click', function() {
            if (nivelActual < niveles.length - 1) {
                mostrarNivel(nivelActual + 1);
            }
        });

        // 
        if (niveles.length > 0) {
            mostrarNivel(nivelActual);
        }
    });
    </script>




   
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


        <!--                FORMS OCULTO PARA AGREGAR COMENTARIO         
            <button class="btn btn-lg btn-dark admin" id="buyCurso">Agregar comentario</button> -->

        <div id="overlay" class="overlay"></div>
        <div class="container card buyForms" id="formCategoria">
            
                        <form action="../Controllers/crearComentario.php" method="POST" id="formComentario"> 

                            

                            <div class="row d-flex justify-content-center">
                                <div class="col-7">
                                    <div class="mb-3">
                                    <label for="Calificacion" class="form-label">Calificacion:</label>

                                    <select id="Calificacion" name="Calificacion" class="form-select">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>
                                    </div>

                                    <div class="mb-3">
                                    <label for="Comentario" class="form-label">Comentario:</label>
                                    <input type="text" class="form-control" id="Comentario" name="Comentario" placeholder="Comentarios sobre el curso" required>
                                    </div>
                                </div>


                            </div>


                            <div class="d-flex justify-content-end "> 
                                <button type="submit" class="btn btn-lg btn-dark" id="buyCurso">Agregar</button> <!--No importa mucho el ID porque se manda como SUBMIT-->
                              </div>

                              

                        </form>
                    
        </div>    
<!---->
    <script src="JS/addComent.js"></script>
    
<script src="JS/bootstrapJS/bootstrap.bundle.min.js"></script>
</body>
</html>