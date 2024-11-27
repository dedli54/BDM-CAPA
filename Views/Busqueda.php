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

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

include '../conexion.php';
          $conexion = new conexion();
          $pdo = $conexion->conectar();

    // Llena el Select de categorias
    if ($pdo) {

    $p_categoria_id = 0; 

    $stmt = $pdo->prepare("CALL sp_Categorias(?)");
    $stmt->bindParam(1, $p_categoria_id, PDO::PARAM_INT);

    $stmt->execute();

    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Cerrar la conexión
    $stmt->closeCursor();
    } else {
    echo "Error de conexión.";
    }
    
    // Aplicar filtros:


    $fecha1 = isset($_POST['fecha1']) && $_POST['fecha1'] !== '' ? $_POST['fecha1'] : null;
    $fecha2 = isset($_POST['fecha2']) && $_POST['fecha2'] !== '' ? $_POST['fecha2'] : null;
    $categoria_id = isset($_POST['cCategoria']) && $_POST['cCategoria'] != 'Categoria' ? $_POST['cCategoria'] : null;



    if ($pdo) {
        try {
            // Llamar al procedimiento almacenado con los parámetros correctos
            $sql = "CALL sp_busqueda(?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            
            // Enlazar los parámetros para el procedimiento
            $stmt->bindParam(1, $fecha1, PDO::PARAM_STR);
            $stmt->bindParam(2, $fecha2, PDO::PARAM_STR);
            $stmt->bindParam(3, $categoria_id, PDO::PARAM_INT);
            $stmt->bindParam(4, $textBuscar, PDO::PARAM_STR);
            $stmt->bindParam(5, $user_id, PDO::PARAM_INT);
            
            // Ejecutar el procedimiento
            $stmt->execute();
    
            // Obtener el resultado de la consulta
            $cursos = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $cursos[] = $row;
            }
        } catch (PDOException $e) {
            echo "Error al ejecutar el procedimiento: " . $e->getMessage();
        }
        
        // Cerrar la conexión
        $pdo = null;
    } else {
        echo "No se pudo conectar a la base de datos.";
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
                    <form  id="formBusqueda" name="formBusqueda" method="POST">
                        <div class="row">
                            <div class="col-auto ms-auto">
                            <!-- Barra de Filtros -->
                            <div class="d-flex flex-wrap align-items-center gap-3">
                                <!-- Rango de Fechas -->
                                

                                <div>
                                <label for="fecha1" class="form-label subtitulos" id="dtpInicio">Fecha Inicio</label>
                                <input type="date" id="fecha1" name="fecha1" class="form-control"
                                    value="<?php echo isset($_POST['fecha1']) ? htmlspecialchars($_POST['fecha1']) : ''; ?>">
                                </div>

                                <div>
                                <label for="fecha2" class="form-label subtitulos" >Fecha Fin</label>
                                <input type="date" id="fecha2" name="fecha2" class="form-control"
                                value="<?php echo isset($_POST['fecha2']) ? htmlspecialchars($_POST['fecha2']) : ''; ?>">
                                </div>
                        
                                <!-- Select -->
                                <div>
                                    <label for="categoriaID" class="form-label subtitulos">Categoría</label>
                                    

                                    <select class="form-select" id="cCategoria" name="cCategoria"> 
                                        <option value="Categoria">Categoria</option>
                                        <?php
                                            foreach ($categorias as $categoria) {
                                                echo '<option value="' . $categoria['id'] . '"';
                                                
                                                // Verificar si el valor enviado coincide con la opción actual
                                                if (isset($_POST['cCategoria']) && $_POST['cCategoria'] == $categoria['id']) {
                                                    echo ' selected'; // Mantener seleccionado
                                                }
                                                
                                                echo '>' . htmlspecialchars($categoria['nombre']) . '</option>'; // Nombre seguro de la categoría
                                            }
                                        ?>
                                    </select>

                                </div>
                        
                                <!-- Casillas de Verificación --><button class="btn btn-outline-dark textos-2" type="submit">Buscar</button>
                                
                            </div>
                            </div>
                        </div>
                    </form>
                </div>


                <h5 class="subtitulos-categoria">Resultados de "<?php echo $textBuscar; ?>":</h5>
  
      
        </div>
    </div>
</div>

          
                  <!--                                    |||||||||||||||||||||||| Cajita con cursos ||||||||||||||||||||           -->
                  <div class="container cont-Cursos px-2 ">
                    <div class="row rowCursos gx-5 resultadoBusqueda cursos-lista" >
                
                
                    <?php foreach ($cursos as $curso): ?>
                    <div class="card col-lg-5 col-md-5 col-sm-11 px-0">
                        <div class="row no-gutters">
                            <div class="col-5">
                                <div class="card-body">
                                    <h4 class="card-title subtitulos"><?= htmlspecialchars($curso['titulo']) ?></h4>
                                    <p class="subtitulos-categoria small"><?= htmlspecialchars($curso['nombre_maestro']) ?> | <?= htmlspecialchars($curso['categoria_nombre']) ?></p>
                                    <p class="textos"><?= htmlspecialchars($curso['descripcion']) ?></p>
                                    <p class="textos">Costo $<?= number_format($curso['precio'], 2) ?></p>
                                    <?php if (isset($_SESSION['user_id'])): ?> <!---->

                                        

                                        <?php /*  <?php echo $curso['estado_inscripcion'] ? 'Inscrito' : 'No Inscrito'; ?>*/ ?>

                                            <?php if ($curso['estado_inscripcion']): ?>
                                            <!-- Si está inscrito, mostrar botón para ver el curso -->
                                            <a href="CursoVer.php?id=<?= htmlspecialchars($curso['id']); ?>" class="btn btn-primary btn-sm">Ver curso</a>
                                            <?php else: ?>
                                                <!-- Si no está inscrito, mostrar botón para comprar el curso -->
                                                <a href="CursoComprar.php?id=<?= htmlspecialchars($curso['id']); ?>" class="btn btn-success btn-sm">Comprar curso</a>
                                            <?php endif; ?>

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



<script src="JS/busqueda.js"></script>

<script src="JS/bootstrapJS/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
/*
<div class="cursos-lista">
    <?php if (!empty($cursos)): ?>
        <?php foreach ($cursos as $curso): ?>
            <div class="curso">
                <h5><?php echo htmlspecialchars($curso['titulo']); ?></h5>
                <p><?php echo htmlspecialchars($curso['descripcion']); ?></p>
                <p><strong>Precio:</strong> <?php echo htmlspecialchars($curso['precio']); ?></p>
                <p><strong>Instructor:</strong> <?php echo htmlspecialchars($curso['nombre_maestro']); ?></p>
                <p><strong>Estado de Inscripción:</strong> <?php echo $curso['estado_inscripcion'] ? 'Inscrito' : 'No Inscrito'; ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No se encontraron cursos.</p>
    <?php endif; ?>
</div>
*/
?>