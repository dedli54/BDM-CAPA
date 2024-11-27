USE `bdm-capa`; -- Documento con nuevos cambios en la BD

/*
CREATE TABLE `comentario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_alumno` int(11) DEFAULT NULL,
  `id_curso` int(11) DEFAULT NULL,
  `texto` text DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id_alumno` (`id_alumno`),
  KEY `id_curso` (`id_curso`),
  CONSTRAINT `comentario_ibfk_1` FOREIGN KEY (`id_alumno`) REFERENCES `usuario` (`id`),
  CONSTRAINT `comentario_ibfk_2` FOREIGN KEY (`id_curso`) REFERENCES `curso` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
*/

alter table `comentario`
add column `calificacion` int; 

-- Procedure para guardar comentario
DELIMITER $$

CREATE PROCEDURE sp_agregar_comentario(
    IN p_id_alumno INT,
    IN p_id_curso INT,
    IN p_texto TEXT,
    IN p_calificacion INT
)
BEGIN
    
    IF p_calificacion < 1 OR p_calificacion > 5 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'La calificación debe estar entre 1 y 5';
    ELSE
        INSERT INTO comentario (id_alumno, id_curso, texto, calificacion)
        VALUES (p_id_alumno, p_id_curso, p_texto, p_calificacion);
    END IF;
END$$

DELIMITER ;

-- sp_Categorias utilizado para conseguir las categorias

DELIMITER $$

CREATE PROCEDURE sp_agregar_categoria (
    IN p_nombre VARCHAR(100),
    IN p_descripcion TEXT
)
BEGIN
    -- Verificar si el nombre ya existe
    IF NOT EXISTS (
        SELECT 1
        FROM categoria
        WHERE nombre = p_nombre
    ) THEN
        -- Insertar la nueva categoría
        INSERT INTO categoria (nombre, descripcion)
        VALUES (p_nombre, p_descripcion);
    ELSE
        -- Mensaje de error si el nombre ya existe (opcional)
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'El nombre de la categoría ya existe.';
    END IF;
END$$

DELIMITER ;


-- PROCEDURE PARA COMPRAR UN CURSO

DELIMITER $$

CREATE PROCEDURE sp_registrar_inscripcion (
    IN p_id_alumno INT,
    IN p_id_curso INT
)
BEGIN
    
    IF NOT EXISTS ( 
        SELECT 1 FROM inscripcion WHERE id_alumno = p_id_alumno AND id_curso = p_id_curso
    ) AND NOT EXISTS (
        SELECT 1 FROM kardex WHERE id_alumno = p_id_alumno AND id_curso = p_id_curso
    ) AND NOT EXISTS (
        SELECT 1 FROM transaccion WHERE id_alumno = p_id_alumno AND id_curso = p_id_curso
    ) THEN
        
        INSERT INTO inscripcion (id_alumno, id_curso)
        VALUES (p_id_alumno, p_id_curso);

        
        INSERT INTO kardex (id_alumno, id_curso, calificacion)
        VALUES (p_id_alumno, p_id_curso, 0);

        
        INSERT INTO transaccion (id_alumno, id_curso, monto, estatus)
        VALUES (p_id_alumno, p_id_curso, (SELECT precio FROM curso WHERE id = p_id_curso), 1);
    ELSE
        -- Si ya hay registros de ese alumno con ese curso muestra esto (No debe de aparecer) 
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Alumno ya tiene este curso';
    END IF;
END$$

DELIMITER ;

-- CALL sp_registrar_inscripcion(6, 8);
-- TRIGGERS

-- Este trigger es para que se actualice el total de niveles
DELIMITER $$

CREATE TRIGGER trg_update_niveles
AFTER INSERT ON nivelescurso
FOR EACH ROW
BEGIN
    -- Comprobar si el numeroNivel es mayor al número de niveles del curso
    IF NEW.numeroNivel > (SELECT niveles FROM curso WHERE id = NEW.id_curso) THEN
        -- Actualizar el número de niveles en el curso
        UPDATE curso
        SET niveles = NEW.numeroNivel
        WHERE id = NEW.id_curso;
    END IF;
END$$

DELIMITER ;
