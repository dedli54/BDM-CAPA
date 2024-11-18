<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/chat.css">
    <!--link rel="stylesheet" href="CSS/bootstrapCSS/bootstrap-grid.min.css"-->
    <link rel="stylesheet" href="CSS/bootstrapCSS/bootstrap.min.css">

    <link rel="stylesheet" href="CSS/colores.css">
    <link rel="stylesheet" href="CSS/landPage.css">
    <title>Chat</title>
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

    
    <div class="card-chat">
        <form id="chat">
            <h3 class="col-9">Bandeja de entrada</h3>
            <div class="container">
                <div class="row">
                        <div class="col-3">
                            <div class="container mt-5">
                                <form class="d-flex" role="search">
                                    <input class="form-control me-2 busqueda-input" type="search" placeholder="Buscar..." aria-label="Search">
                                    <button class="btn btn-outline-primary" type="submit">Buscar</button>
                                </form>
                            </div>
                            <p class="mensajes">
                                <img src="IMG/icon.png" alt="perfil" class="foto" width="50" height="50" style="border-radius: 10%;">
                                Chat 1
                            </p>
                            <p class="mensajes">
                                <img src="IMG/icon.png" alt="perfil" class="foto" width="50" height="50" style="border-radius: 10%;">
                                Chat 2
                            </p>
                            <p class="mensajes">
                                <img src="IMG/icon.png" alt="perfil" class="foto" width="50" height="50" style="border-radius: 10%;">
                                Chat 3
                            </p>
                            <p class="mensajes">
                                <img src="IMG/icon.png" alt="perfil" class="foto" width="50" height="50" style="border-radius: 10%;">
                                Chat 4
                            </p>
                            <p class="mensajes">
                                <img src="IMG/icon.png" alt="perfil" class="foto" width="50" height="50" style="border-radius: 10%;">
                                Chat 5
                            </p>
                        </div>
                    <div class="col-9 mensaje">
                        <div class="profesor">
                            Mensaje profesor
                        </div>
                        <div class="propio">
                            Mensaje propio
                        </div>
                    </div>
                        <div class="enviar">
                            <input class="texto" placeholder="Escribe un texto">
                            <button>Enviar</button>
                        </div>
                </div>
            </div>
        </form>
    </div>

    <main class="flex-grow-1 container"></main>

    <footer class="text-center mt-auto">

        
    </footer>
    
    
    <script src="JS/inicioSesion.js"></script>
    
    <script src="JS/bootstrapJS/bootstrap.bundle.min.js"></script>
</body>
</html>