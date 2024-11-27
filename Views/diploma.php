<!-- diploma.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Diploma - A&B Cursos</title>
    <style>
        @media print {
            .no-print {
                display: none;
            }
        }
        
        .diploma {
            width: 800px;
            height: 600px;
            margin: 0 auto;
            padding: 40px;
            text-align: center;
            border: 20px solid #0a0a23;
            background: white;/* Add background image */
            background-size: 90% 90%; /* Scale image to fit */
            position: relative; /* For proper layering */
        }
        
        /* Add a white semi-transparent overlay to improve text readability */
        .diploma::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.7); /* Semi-transparent white */
            z-index: 0;
        }
        
        /* Ensure content stays above background */
        .diploma-header,
        .diploma-body,
        .diploma-footer {
            position: relative;
            z-index: 1;
        }
        
        .diploma-header {
            color: #0a0a23;
            font-size: 48px;
            margin-bottom: 30px;
            font-family: "Times New Roman", Times, serif;
        }
        
        .diploma-body {
            color: #0a0a23;
            font-size: 24px;
            line-height: 1.6;
            margin: 40px 0;
            font-family: "Times New Roman", Times, serif;
        }
        
        .diploma-footer {
            color: #0a0a23;
            font-size: 18px;
            margin-top: 40px;
            font-family: "Times New Roman", Times, serif;
        }

        .print-btn {
            margin: 20px;
            padding: 10px 20px;
            font-size: 16px;
            background-color: #0a0a23;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body style="margin:0; padding:0;">
    <?php
    $isModal = isset($_GET['modal']);
    session_start();
    require '../conexion.php';
    
    // Get course and user details
    $conexion = new conexion();
    $pdo = $conexion->conectar();
    
    $curso_id = $_GET['id'] ?? 0;
    $user_id = $_SESSION['user_id'] ?? 0;
    
    $stmt = $pdo->prepare("SELECT c.titulo, u.nombre, u.apellidos, 
                              i.nombre as instructor_nombre, i.apellidos as instructor_apellidos,
                              DATE_FORMAT(CURRENT_DATE, '%d/%m/%Y') as fecha_actual 
                       FROM curso c
                       JOIN usuario u ON u.id = ?
                       JOIN usuario i ON i.id = c.id_maestro 
                       WHERE c.id = ?");
    $stmt->execute([$user_id, $curso_id]);
    $data = $stmt->fetch();
    ?>

    <div class="diploma">
        <div class="diploma-header">
        A&B Cursos
        </div>
        
        <div class="diploma-body">
            Se certifica que<br><br>
            <strong><?php echo htmlspecialchars($data['nombre'] . ' ' . $data['apellidos']); ?></strong>
            <br><br>
            Ha completado exitosamente el curso<br><br>
            <strong><?php echo htmlspecialchars($data['titulo']); ?></strong>
        </div>
        
        <div class="diploma-footer">
            Fecha de emisión: <?php echo htmlspecialchars($data['fecha_actual']); ?>
            <br><br>
            Instructor: <?php echo htmlspecialchars($data['instructor_nombre'] . ' ' . $data['instructor_apellidos']); ?>
            <br><br>
        </div>
    </div>

 

</body>
</html>