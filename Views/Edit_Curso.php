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
            <form id="dynamicForm"> 
                <div class="row">
                    <!-- Columna 1 -->
                    <div class="col">
                        <div class="mb-3">
                            <label for="cName" class="form-label fs-5 subtitulos">Titulo:</label>
                            <input type="text" class="form-control rounded-5" id="cName" placeholder="Ingresa titulo del curso">
                        </div>
                        <div class="mb-3">
                            <label for="cDesc" class="form-label fs-5 subtitulos">Descripcion:</label>
                            <textarea class="form-control textarea-md" id="cDesc" placeholder="Ingresa descripcion"></textarea>
                        </div>
                        


                        <div class="mb-3 ">
                            <label for="foto" class="form-label fs-5 subtitulos">Selecciona una imagen:</label>
                            <input type="file" class="form-control rounded-5" id="foto" accept="image/jpeg, image/png">
                        </div>
                        <div class="img-container d-flex justify-content-center">
                            <img id="preview" class="img-preview rounded-5" alt="Foto de perfil seleccionada">
                        </div>

                        
                    </div>


                    <!-- Columna 2 -->
                    <div class="col">
                        <div class="mb-3"> <!--   TO DO FRONT: Java que muestre u oculte informacion dependiendo     -->
                            <input type="checkbox" class="form-check-input" id="cGratis">
                            <label for="cTotal" class="form-label fs-5 subtitulos">Curso gratuito</label>
                        </div>
                        
                        <div class="mb-3 costo">
                            <label for="cTotal" class="form-label fs-5 subtitulos">Costo total:</label>
                            <input type="number" class="form-control rounded-5" id="cTotal" placeholder="Costo por todo el curso">
                        </div>
                        <div class="mb-3 costo">
                            <label for="cNivel" class="form-label fs-5 subtitulos">Costo por nivel:</label>
                            <input type="number" class="form-control rounded-5" id="cNivel" placeholder="Costo por todo el curso">
                        </div>
                        <div class="mb-3">
                            <label for="cCategoria" class="form-label fs-5 subtitulos">Categoria:</label>
                                <select class="form-select rounded-5" id="cCategoria">
                                    <option selected>Categoria</option>
                                    <option value="1">Matematicas</option>
                                    <option value="2">Arte</option>
                                    <option value="3">Computo</option>
                                </select>
                        </div>
                        <div class="mb-3 costo">
                            <label for="freeLvl" class="form-label fs-5 subtitulos">Niveles gratuitos:</label>
                                <select class="form-select rounded-5" id="freeLvl">
                                    <option selected>0</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                </select>
                        </div>

                        

                    </div>
                </div>




                



            <div class=" text-center">
                <h3 class="subtitulos">Niveles</h3>
            </div>


            <!--  Niveles curso  -->    <hr>
            
                <div class="mb-3">
                    <label for="numFields" class="form-label fs-5 subtitulos">Seleccione el total de niveles</label>
                    <select class="form-select" id="numFields">
                        <option value="0">Selecciona el número de campos</option>
                        
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                    </select>
                </div>
                <div id="fieldsContainer">
                    <!-- Campos dinámicos serán insertados aquí -->
                </div>
                <div class="text-center mt-3">
                    <button type="submit" class="btn btn-dark">Guardar</button>
                </div>
            </form>
        </div>
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
            <a href="#" class="text-white me-3">Términos</a>
            <a href="#" class="text-white">Contacto</a>
        </p>
    </div>
</footer>



<script src="JS/addCurso.js"></script>

<script src="JS/bootstrapJS/bootstrap.bundle.min.js"></script>
</body>
</html>