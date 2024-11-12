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
  

} else {
  echo "Error de conexión.";
}


$p_id_usuario = 2;

  $fecha_inicio = null;
  $fecha_fin = null;
  $categoria_id = null;
  $activos = null;

  // Se mandan a llamar para llenarlas sin filtros
  $stmt_resumen = $pdo->prepare("CALL sp_resumen_curso(?, ?, ?, ?, ?)");
  $stmt_resumen->bindParam(1, $p_id_usuario, PDO::PARAM_INT);
  $stmt_resumen->bindParam(2, $fecha_inicio, PDO::PARAM_STR);
  $stmt_resumen->bindParam(3, $fecha_fin, PDO::PARAM_STR);
  $stmt_resumen->bindParam(4, $categoria_id, PDO::PARAM_INT);
  $stmt_resumen->bindParam(5, $activos, PDO::PARAM_BOOL);
  $stmt_resumen->execute();
  $resultados_resumen = $stmt_resumen->fetchAll(PDO::FETCH_ASSOC);
  $stmt_resumen->closeCursor();

  $stmt_alumnos = $pdo->prepare("CALL sp_alumnos_inscritos(?, ?, ?, ?, ?)");
  $stmt_alumnos->bindParam(1, $p_id_usuario, PDO::PARAM_INT);
  $stmt_alumnos->bindParam(2, $fecha_inicio, PDO::PARAM_STR);
  $stmt_alumnos->bindParam(3, $fecha_fin, PDO::PARAM_STR);
  $stmt_alumnos->bindParam(4, $categoria_id, PDO::PARAM_INT);
  $stmt_alumnos->bindParam(5, $activos, PDO::PARAM_BOOL);
  $stmt_alumnos->execute();
  $resultados_alumnos = $stmt_alumnos->fetchAll(PDO::FETCH_ASSOC);
  $stmt_alumnos->closeCursor();

  // Si el formulario es enviado (POST)
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {

      $fecha_inicio = isset($_POST['fecha1']) && !empty($_POST['fecha1']) ? $_POST['fecha1'] : null;
      $fecha_fin = isset($_POST['fecha2']) && !empty($_POST['fecha2']) ? $_POST['fecha2'] : null;
      $categoria_id = isset($_POST['categoria_slc']) && $_POST['categoria_slc'] != 'Todas' ? $_POST['categoria_slc'] : null;
      $activos = isset($_POST['activosBox']) ? 1 : null; // 1 si está marcado, NULL si no está marcado

      

      $stmt_resumen = $pdo->prepare("CALL sp_resumen_curso(?, ?, ?, ?, ?)");
      $stmt_resumen->bindParam(1, $p_id_usuario, PDO::PARAM_INT); // Usar $p_id_usuario con valor fijo
      $stmt_resumen->bindParam(2, $fecha_inicio, PDO::PARAM_STR);
      $stmt_resumen->bindParam(3, $fecha_fin, PDO::PARAM_STR);
      $stmt_resumen->bindParam(4, $categoria_id, PDO::PARAM_INT);
      $stmt_resumen->bindParam(5, $activos, PDO::PARAM_BOOL);

      $stmt_resumen->execute();
      $resultados_resumen = $stmt_resumen->fetchAll(PDO::FETCH_ASSOC);
      $stmt_resumen->closeCursor();

      

      $stmt_alumnos = $pdo->prepare("CALL sp_alumnos_inscritos(?, ?, ?, ?, ?)");
      $stmt_alumnos->bindParam(1, $p_id_usuario, PDO::PARAM_INT); // Usar $p_id_usuario con valor fijo
      $stmt_alumnos->bindParam(2, $fecha_inicio, PDO::PARAM_STR);
      $stmt_alumnos->bindParam(3, $fecha_fin, PDO::PARAM_STR);
      $stmt_alumnos->bindParam(4, $categoria_id, PDO::PARAM_INT);
      $stmt_alumnos->bindParam(5, $activos, PDO::PARAM_BOOL);

      $stmt_alumnos->execute();
      $resultados_alumnos = $stmt_alumnos->fetchAll(PDO::FETCH_ASSOC);
      $stmt_alumnos->closeCursor();
    }


$pdo = null;





?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de ventas</title>
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
        <div class="card-header text-center">Mis ventas</div> <!--quitar maybe-->
        <div class="card-body">

            


<!-- Barra de Filtros -->
<form id="filtrosRPVenta">
  <div class="container my-3">
      <div class="row">
        <div class="col-auto ms-auto">

          <div class="d-flex flex-wrap align-items-center gap-3">

            <div>
              <label for="fecha1" class="form-label subtitulos">Fecha Inicio</label>
              <input type="date" id="fecha1" name="fecha1" class="form-control">
            </div>
            <div>
              <label for="fecha2" class="form-label subtitulos" >Fecha Fin</label>
              <input type="date" id="fecha2" name="fecha2" class="form-control">
            </div>
    
            <div>
              <label for="categoriaID" class="form-label subtitulos">Categoría</label>
              <select id="categoriaID" class="form-select" name="categoria_slc">
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
    
            <!-- Casillas de Verificación -->
            <div class="d-flex align-items-center">
              <div class="form-check me-3">
                <input type="checkbox" class="form-check-input" id="activosBox" name="activosBox">
                <label class="form-check-label subtitulos" for="activosBox">Solo activos</label>
              </div>
            </div>
          </div>
        </div>

        <div class="d-flex justify-content-end mt-3"><button type="submit" class="btn btn-dark">Aplicar filtros</button></div>
      </div>
  </div>
</form>
  
  <hr>
  <h3 class="subtitulos">Tabla de cursos</h3>
        <div class=" table-responsive">

                <table class="table" id="tabla1">
                    <thead class="table-dark">
                      <tr>
                        <th scope="col">Num.</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Fecha creacion</th>
                        <th scope="col">Categoria</th>
                        <th scope="col">Alumnos inscritos</th>
                        <th scope="col">Ingresos totales</th>
                      </tr>
                    </thead>
                    <tbody> 
                      
                        <?php if (!empty($resultados_resumen)): ?>
                    <?php foreach ($resultados_resumen as $resultado): ?>
                        <tr>
                            <td><?= htmlspecialchars($resultado['ID_Curso']) ?></td>
                            <td><?= htmlspecialchars($resultado['Nombre']) ?></td> 
                            <td><?= htmlspecialchars($resultado['Fecha_creacion']) ?></td>
                            <td><?= htmlspecialchars($resultado['Categoria']) ?></td>
                            <td><?= htmlspecialchars($resultado['Alumnos_inscritos']) ?></td>
                            <td><?= htmlspecialchars($resultado['Ingresos_totales']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No se encontraron resultados.</td>
                    </tr>
                <?php endif; ?>

                    </tbody>
                </table>
          </div>

          <h6 class="align-items-right">Ingresos totales por tarjeta: <p>$660.00</p> </h6> 
          <hr>
          <h3 class="subtitulos">Tabla de alumnos</h3>
          <div class=" table-responsive">

            <table class="table" id="tabla2">
                <thead class="table-dark">
                  <tr>
                    <th scope="col">Curso</th>
                    <th scope="col">Alumno</th>
                    <th scope="col">Nivel actual</th>
                    <th scope="col">Inscripcion</th>
                    <th scope="col">Pago</th>
                    <th scope="col">Forma de pago</th>
                  </tr>
                </thead>
                <tbody>
                
                <?php if (!empty($resultados_alumnos)): ?>
                <?php foreach ($resultados_alumnos as $alumno): ?>
                    <tr>
                        <td><?= htmlspecialchars($alumno['Curso']) ?></td> <!-- Id del curso -->
                        <td><?= htmlspecialchars($alumno['Curso']) ?></td>
                        <td><?= htmlspecialchars($alumno['Alumno']) ?></td>
                        <td><?= htmlspecialchars($alumno['Inscripcion']) ?></td>
                        <td><?= htmlspecialchars($alumno['Pago']) ?></td>
                        <td><?= htmlspecialchars($alumno['Pago']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No se encontraron resultados.</td>
                </tr>
            <?php endif; ?>                

                </tbody>
            </table>
      </div>

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



<script src="JS/reportVenta.js"></script>
<script src="JS/bootstrapJS/bootstrap.bundle.min.js"></script>
</body>
</html>