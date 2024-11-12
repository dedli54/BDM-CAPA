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


?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de usuarios</title>
    <!--link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"-->
    <link rel="stylesheet" href="CSS/bootstrapCSS/bootstrap.min.css">
    

    
    <link rel="stylesheet" href="CSS/colores.css">
    <link rel="stylesheet" href="CSS/reportes.css">


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

<div class="container mt-5"  >
    <div class="card " > 
        <div class="card-header text-center subtitulos">Reporte de usuarios</div> <!--quitar maybe-->
        <div class="card-body">

            

                    <!-- Barra de Filtros -->


  <h3 class="subtitulos">Tabla de alumnos</h3>
        <div class=" table-responsive">

                <table class="table">
                  <thead class="table-dark">
                    <tr>
                      <th scope="col">ID</th>
                      <th scope="col">Nombre</th>
                      <th scope="col">Fecha ingreso</th>
                      <th scope="col">Cursos inscritos</th>
                      <th scope="col">% de terminados</th>
                      <th scope="col">Cuenta bloqueada (3 intentos)</th>
                    </tr>
                  </thead>
                  <tbody>
                    
                  <?php
                    
                    $pdo = $conexion->conectar();
                    

                    try {
                      
                        $p_Vista = 1; // ID asignar con el $_SESSION['user_id']

                        $stmt = $pdo->prepare("CALL sp_reporteUser(?)");
                        $stmt->execute([$p_Vista]);

                        if ($stmt->rowCount() > 0) {
                            $num = 1;
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<tr>";
                                // echo "<td>" . $num++ . "</td>";
                                echo "<td>" . htmlspecialchars($row['ID']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['Nombre']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['Fecha_ingreso']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['Cursos_inscritos']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['Porcentaje_terminados']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['intentos']) . "</td>";
                                /*if ($row['Curso_terminado']) {
                                    echo "<td><a href='diploma.php?id=" . $row['id_alumno'] . "' target='_blank'>Ver Diploma</a></td>";
                                } else {
                                    echo "<td>No disponible</td>";
                                }*/
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
                </table>
          </div>

         <hr>
          <h3 class="subtitulos">Tabla de instructores</h3>
          <div class=" table-responsive">

            <table class="table">
                <thead class="table-dark">
                  <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Fecha ingreso</th>
                    <th scope="col">Cursos totales</th>
                    <th scope="col">Ganancias</th>
                    <th scope="col">Cuenta bloqueada (3 intentos)</th>
                  </tr>
                </thead>
                <tbody>
                  
                <?php
                    
                    $pdo = $conexion->conectar();
                    

                    try {
                      
                        $p_Vista = 2; // ID asignar con el $_SESSION['user_id']

                        $stmt = $pdo->prepare("CALL sp_reporteUser(?)");
                        $stmt->execute([$p_Vista]);

                        if ($stmt->rowCount() > 0) {
                            $num = 1;
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<tr>";
                                // echo "<td>" . $num++ . "</td>";
                                echo "<td>" . htmlspecialchars($row['ID']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['Nombre']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['Fecha_ingreso']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['Cursos_totales']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['Ganancias']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['intentos']) . "</td>";
                                /*if ($row['Curso_terminado']) {
                                    echo "<td><a href='diploma.php?id=" . $row['id_alumno'] . "' target='_blank'>Ver Diploma</a></td>";
                                } else {
                                    echo "<td>No disponible</td>";
                                }*/
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




<script src="JS/bootstrapJS/bootstrap.bundle.min.js"></script>
</body>
</html>