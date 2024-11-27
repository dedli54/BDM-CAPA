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



    include '../conexion.php';
            $conexion = new conexion();
            $pdo = $conexion->conectar();
            
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

    
    $curso_id = $_GET['id'] ?? 1;

    try {

    // Llamar al procedimiento almacenado
    $query = $pdo->prepare("CALL sp_DatosEditCurso(:id_curso)");
    $query->bindParam(':id_curso', $curso_id, PDO::PARAM_INT);
    $query->execute();

    // Obtener el resultado
    $curso = $query->fetch(PDO::FETCH_ASSOC);

    $foto_base64 = null;
    if (!empty($curso['foto'])) {
        $foto_base64 = base64_encode($curso['foto']);
    }

    $id_categoria_seleccionada = $curso['id_categoria'] ?? null;

    $query->closeCursor(); // Necesario al usar procedimientos almacenados con PDO

    $pdo = null;
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar curso</title>
    <link rel="stylesheet" href="CSS/bootstrapCSS/bootstrap.min.css">


    
    <link rel="stylesheet" href="CSS/colores.css">
    <link rel="stylesheet" href="CSS/newCurso.css">

    <style>
        

    </style>
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

<div class="container mt-5 "  >
    <div class="card" > 
        <div class="card-header text-center">
            <h3 class="titulos">Actualizar datos de curso</h3>
        </div>

        
        <div class="card-body"> <!--  Datos curso Ojo, se cambió el ID -->
            <form id="dynamicForm" enctype="multipart/form-data"> 
                <!-- Add hidden input for course ID -->
                <input type="hidden" name="curso_id" value="<?php echo htmlspecialchars($curso_id); ?>">
                <!-- Rest of your form fields remain the same -->
                <div class="row">
                    <!-- Columna 1 -->
                    <div class="col">
                        <div class="mb-3">
                            <label for="cName" class="form-label fs-5 subtitulos">Titulo:</label>
                            <input type="text" class="form-control rounded-5" id="cName" placeholder="Ingresa titulo del curso"
                            value="<?php echo htmlspecialchars($curso['titulo'], ENT_QUOTES); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="cDesc" class="form-label fs-5 subtitulos">Descripcion:</label>
                            <textarea class="form-control textarea-md" id="cDesc" placeholder="Ingresa descripcion"><?php echo htmlspecialchars($curso['descripcion'], ENT_QUOTES); ?></textarea>

                            
                        </div>
                        


                        <div class="mb-3 ">
                            <label for="foto" class="form-label fs-5 subtitulos">Selecciona una imagen:</label>
                            <input type="file" class="form-control rounded-5" id="foto" name="foto" accept="image/jpeg, image/png">
                        </div>
                        <div class="img-container-2 text-center">

                        <img id="" class="img-preview-edit rounded-5" alt="Foto de perfil seleccionada"
                        src="<?php echo !empty($foto_base64) ? 'data:image/jpeg;base64,' . $foto_base64 : 'IMG/sql.png'; ?>">
                        </div>

                        
                    </div>


                    <!-- Columna 2 -->
                    <div class="col">
                        
                        
                        <div class="mb-3 costo">
                            <label for="cTotal" class="form-label fs-5 subtitulos">Costo total:</label>
                            <input type="number" class="form-control rounded-5" id="cTotal" placeholder="Costo por todo el curso"
                            value="<?php echo htmlspecialchars($curso['precio'], ENT_QUOTES); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="cCategoria" class="form-label fs-5 subtitulos">Categoria:</label>
                            <select class="form-select rounded-5" id="cCategoria">
                                    <option selected>Categoria</option>
                                    
                                    <?php
                                        foreach ($categorias as $categoria) {
                                            echo '<option value="' . $categoria['id'] . '"'; // ID en el valor

                                            if ($categoria['id'] == $id_categoria_seleccionada) {
                                                echo ' selected'; // Marca la categoría como seleccionada
                                            }
                                            echo '>' . $categoria['nombre'] . '</option>'; // Nombre de la categoria se muestra 
                                        }
                                        ?>

                                </select>
                        </div>
                        

                        

                    </div>
                </div>




                

<!--

            <div class=" text-center">
                <h3 class="subtitulos">Niveles</h3>
            </div>


              Niveles curso     <hr>
            
                <div class="mb-3">
                    <label for="numFields" class="form-label fs-5 subtitulos">Seleccione el total de niveles</label>
                    <select class="form-select" id="numFields" disabled>
                        <option value="0">Selecciona el número de campos</option>
                        
                       
                    </select>
                </div>
                <div id="fieldsContainer">
                    
                </div>
                
        </div>--> <div class="text-center mt-3">
                    <button type="submit" class="btn btn-dark">Guardar cambios</button>
                </div>
            </form>
            </div></div>
</div>

<main class="flex-grow-1 container">
    <hr class="opZero"><hr class="opZero"><hr class="opZero">
</main>

<footer class="text-center mt-auto">
    <div class="container">
        <p>&copy; J&A. Todos los derechos reservados.</p>
        <p>
            <a href="#" class="text-white me-3">Privacidad</a>
            <a href="#" class="text-white me-3">Términos</a>
            <a href="#" class="text-white">Contacto</a>
        </p>
    </div>
</footer>



<script src="JS/editCurso.js"></script>

<script src="JS/bootstrapJS/bootstrap.bundle.min.js"></script>
</body>
</html>