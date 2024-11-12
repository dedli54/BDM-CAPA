<?php 
include '../conexion.php';
          $conexion = new conexion();
          $pdo = $conexion->conectar();

/* 
session_start(); // Inicia la sesión

if (!isset($_SESSION['user_id'])) {
    // Muestra un alert antes de redirigir
    echo "<script>
            alert('La sesión no está iniciada. Por favor, inicia sesión.');
            window.location.href = 'inicioSesion.php';
          </script>";
    exit();
}*/

if ($pdo) {

  $p_categoria_id = 0; 

  $stmt = $pdo->prepare("CALL sp_Categorias(?)");
  $stmt->bindParam(1, $p_categoria_id, PDO::PARAM_INT);

  $stmt->execute();

  $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // Cerrar la conexión
  $stmt->closeCursor();
  $pdo = null;
} else {
  echo "Error de conexión.";
}



?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kardex</title>
    <link rel="stylesheet" href="CSS/bootstrapCSS/bootstrap.min.css">

    
    <link rel="stylesheet" href="CSS/colores.css">
    <link rel="stylesheet" href="CSS/reportes.css">


</head>


<body class="d-flex flex-column min-vh-100">

    <div class="container conLogo">
        <h1 class="titulos">A&B Cursos</h1>
        </div>
    
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

<div class="container mt-5"  >
    <div class="card " > 
        <div class="card-header text-center">Kardex</div> <!--quitar maybe-->
        <div class="card-body">

            

                    <!-- Barra de Filtros -->

<!-- Barra de Filtros -->

 <form id="filtrosKardex" method="POST">
    <div class="container my-3">
        <div class="row">
          <div class="col-auto ms-auto">

            <div class="d-flex flex-wrap align-items-center gap-3">

              <div>
                <label for="fecha1" class="form-label subtitulos">Fecha Inicio</label>
                <input value="<?= isset($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : '' ?>" type="date" id="fecha1" name="fecha_inicio" class="form-control">
              </div>
              <div>
                <label for="fecha2" class="form-label subtitulos" >Fecha Fin</label>
                <input value="<?= isset($_POST['fecha_fin']) ? $_POST['fecha_fin'] : '' ?>" type="date" id="fecha2" name="fecha_fin" class="form-control">
              </div>
      
              
              <div>
                <label for="categoriaID" class="form-label subtitulos">Categoría</label>
                <select id="categoriaID" name="categoria_id" class="form-select">
                  <option selected>Todas</option>
                  
                  <?php
                    foreach ($categorias as $categoria) {
                        echo '<option value="' . $categoria['id'] . '"'; // ID en el valor

                        if (isset($_POST['categoria_id']) && $_POST['categoria_id'] == $categoria['id']) { //Esto es para que se mantenga al llamar al formulario de los filtros
                            echo ' selected';
                        }
                        echo '>' . $categoria['nombre'] . '</option>'; // Nombre de la categoria se muestra 
                    }
                    ?>

                </select>
              </div>
      
              
              <div class="d-flex align-items-center">
                <div class="form-check me-3">
                  <input <?= isset($_POST['activo']) ? 'checked' : '' ?> type="checkbox" class="form-check-input" id="activosBox" name="activo">
                  <label class="form-check-label subtitulos" for="activosBox">Solo activos</label>
                </div>
                <div class="form-check">
                  <input <?= isset($_POST['completado']) ? 'checked' : '' ?> type="checkbox" class="form-check-input" id="complBox" name="completado">
                  <label class="form-check-label subtitulos" for="complBox">Solo completados</label>
                </div>
              </div>
            </div>

            <div class="d-flex justify-content-end mt-3"><button type="submit" class="btn btn-dark">Aplicar filtros</button></div>
          </div>
        </div>
      </div>
  
  </form>




  <div class=" table-responsive">

            <table class="table" id="kardex">
                <thead class="table-dark">
                  <tr>
                    <th scope="col">Num.</th>
                    <th scope="col">Nombre de curso</th> 
                    <th scope="col">Categoria</th>
                    <th scope="col">Estatus de curso</th>
                    <th scope="col">Fecha de inscripcion</th>
                    <th scope="col">Ultima fecha</th>
                    <th scope="col">Niveles tomados</th>
                    <th scope="col">Niveles totales</th>
                    <th scope="col">Diploma</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    
                    $pdo = $conexion->conectar();
                    

                    try {
                      
                        $p_id_alumno = 9; // ID asignar con el $_SESSION['user_id']
                        $p_fecha_inicio = !empty($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : null;
                        $p_fecha_fin = !empty($_POST['fecha_fin']) ? $_POST['fecha_fin'] : null;
                        $p_categoria_id = !empty($_POST['categoria_id']) ? $_POST['categoria_id'] : null;
                        $p_activo = isset($_POST['activo']) ? 1 : null;
                        $p_completado = isset($_POST['completado']) ? 1 : null;

                        $stmt = $pdo->prepare("CALL sp_kardex_filtros(?, ?, ?, ?, ?, ?)");
                        $stmt->execute([$p_id_alumno, $p_fecha_inicio, $p_fecha_fin, $p_categoria_id, $p_activo, $p_completado]);

                        if ($stmt->rowCount() > 0) {
                            $num = 1;
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<tr>";
                                echo "<td>" . $num++ . "</td>";
                                echo "<td>" . htmlspecialchars($row['Nombre_de_curso']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['Categoria']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['Curso_status']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['Fecha_de_inscripcion']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['Ultima_fecha']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['Niveles_tomados']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['Niveles_totales']) . "</td>";
                                if ($row['Curso_terminado']) {
                                    echo "<td><a href='diploma.php?id=" . $row['id_alumno'] . "' target='_blank'>Ver Diploma</a></td>";
                                } else {
                                    echo "<td>No disponible</td>";
                                }
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='9'>No hay registros que coincidan con los filtros aplicados</td></tr>";
                        }

                        $stmt->closeCursor();  // Cerrar el cursor
                        $pdo = null;

                    } catch (PDOException $e) {
                        echo "<tr><td colspan='9'>Error: " . $e->getMessage() . "</td></tr>";
                    }




                  ?>


                </tbody>
            </table></div>

        </div>
    </div>
</div>



<footer class="text-center mt-auto">
    <div class="container">
        <p>&copy; J&A. Todos los derechos reservados.</p>
        <p>
            <a href="#" class="text-white me-3">Privacidad</a>
        </p>
    </div>
</footer>




<script src="JS/Kardex.js"></script>
<script src="JS/bootstrapJS/bootstrap.bundle.min.js"></script>
</body>
</html>