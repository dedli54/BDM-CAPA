<?php
session_start(); 

require '../conexion.php'; 

try {
    
    $conexion = new Conexion();
    $pdo = $conexion->conectar();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $nameCat = $_POST['nameCat'] ?? null;
        $definicion = $_POST['definicion'] ?? null;


        if (!empty($nameCat) && !empty($definicion)) {

            $sql = "CALL sp_agregar_categoria(:nameCat, :definicion)";
            $stmt = $pdo->prepare($sql);


            $stmt->bindParam(':nameCat', $nameCat, PDO::PARAM_STR);
            $stmt->bindParam(':definicion', $definicion, PDO::PARAM_STR);

            if ($stmt->execute()) {
                echo "Categoría agregada correctamente.";
            } else {
                echo "Error al agregar la categoría.";
            }
            

        } else {
            echo "Necesitas llenar ambos campos.";
        }
    } else {
        echo "Método de solicitud no permitido.";
    }
} catch (PDOException $e) {
    

    if ($e->getCode() === '45000') { 
        echo "Error: La categoría ya existe.";
    } else {

        echo "Error: " . $e->getMessage();
    }
}
