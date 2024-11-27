<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
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


$stmt = $pdo->prepare("SELECT nombre, apellidos FROM usuario WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$currentUser = $stmt->fetch();

// get the fucking user list
$stmt = $pdo->prepare("SELECT id, nombre, apellidos FROM usuario WHERE id != ?");
$stmt->execute([$_SESSION['user_id']]);
$users = $stmt->fetchAll();

// selected chat messages
$chat_with = isset($_GET['user']) ? $_GET['user'] : null;
if ($chat_with) {
    $stmt = $pdo->prepare(
        "SELECT m.*, u.nombre as emisor_nombre 
         FROM mensajes m 
         JOIN usuario u ON m.emisor_id = u.id
         WHERE (emisor_id = ? AND receptor_id = ?) 
         OR (emisor_id = ? AND receptor_id = ?)
         ORDER BY fecha_envio ASC"
    );
    $stmt->execute([$_SESSION['user_id'], $chat_with, $chat_with, $_SESSION['user_id']]);
    $messages = $stmt->fetchAll();
}
?>

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

    
    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                <h3>Bienvenido a tus mensajes <?= htmlspecialchars($currentUser['nombre'] . ' ' . $currentUser['apellidos']) ?></h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Users list -->
                    <div class="col-md-4 border-right">
                        <div class="users-list">
                            <?php foreach ($users as $user): ?>
                                <a href="?user=<?= $user['id'] ?>" class="user-item d-flex align-items-center p-2 border-bottom text-decoration-none">
                                    <div class="user-info">
                                        <span class="user-name"><?= htmlspecialchars($user['nombre'] . ' ' . $user['apellidos']) ?></span>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <!-- Chat area -->
                    <div class="col-md-8">
                        <?php if ($chat_with): ?>
                            <div class="chat-messages" id="chatMessages" style="height: 400px; overflow-y: auto;">
                                <?php foreach ($messages as $message): ?>
                                    <div class="message <?= $message['emisor_id'] == $_SESSION['user_id'] ? 'sent' : 'received' ?> p-2 mb-2">
                                        <div class="message-content">
                                            <?= htmlspecialchars($message['mensaje']) ?>
                                        </div>
                                        <small class="text-muted"><?= $message['fecha_envio'] ?></small>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <form action="send_message.php" method="POST" class="mt-3">
                                <input type="hidden" name="receptor_id" value="<?= $chat_with ?>">
                                <div class="input-group">
                                    <input type="text" name="mensaje" class="form-control" placeholder="Escribe un mensaje..." required>
                                    <button type="submit" class="btn btn-primary">Enviar</button>
                                </div>
                            </form>
                        <?php else: ?>
                            <div class="text-center pt-5">
                                <p>Selecciona un usuario para comenzar un chat</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <main class="flex-grow-1 container"></main>

    <footer class="text-center mt-auto">

        
    </footer>
    
    
    <script src="JS/inicioSesion.js"></script>
    
    <script src="JS/bootstrapJS/bootstrap.bundle.min.js"></script>
</body>
</html>