/* Copia manual de la DB */



/*
-- en workbench puedes crear una nueva BD o borrar la anterior,
-- Seleccionas todo con ctrl + A 
-- Le das correr y tendras 31 notificaciones de creacion (si limpiaste el action output)

OJO debes de poner el nombre CORRECTO de la BD en las VIEWS

CREATE DATABASE `bdm-capa3`;

USE `bdm-capa3`;
DROP DATABASE `bdm-capa3`

-- curso - cat - user

-- categoria

-- user
	--tipo user
*/



CREATE TABLE `tipo_usuario` (
  `id` tinyint(4) NOT NULL,
  `descripcion` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_usuario` varchar(100) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `foto` blob DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `intentos_fallidos` tinyint(4) DEFAULT 0,
  `estado` tinyint(1) DEFAULT 1,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `telefono` bigint(20) DEFAULT NULL,
  `tipo_usuario` tinyint(4) NOT NULL,
  `biografia` text DEFAULT NULL,
  `cuenta_bancaria` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `tipo_usuario` (`tipo_usuario`),
  CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`tipo_usuario`) REFERENCES `tipo_usuario` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `categoria` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `reporte_categoria` ( -- JJ: aún no la uso
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_admin` int(11) DEFAULT NULL,
  `nombre_admin` varchar(100) DEFAULT NULL,
  `nombre_categoria` varchar(100) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id_admin` (`id_admin`),
  CONSTRAINT `reporte_categoria_ibfk_1` FOREIGN KEY (`id_admin`) REFERENCES `usuario` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;




CREATE TABLE `curso` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `contenido` text DEFAULT NULL,
  `id_maestro` int(11) DEFAULT NULL,
  `id_categoria` int(11) DEFAULT NULL,
  `foto` blob DEFAULT NULL,
  `niveles` int(11) DEFAULT 1,
  `fe_Creacion` datetime DEFAULT current_timestamp(),
  `status` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `id_maestro` (`id_maestro`),
  KEY `id_categoria` (`id_categoria`),
  CONSTRAINT `curso_ibfk_1` FOREIGN KEY (`id_maestro`) REFERENCES `usuario` (`id`),
  CONSTRAINT `curso_ibfk_2` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `nivelescurso` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `video` varchar(255) DEFAULT NULL,
  `texto` text DEFAULT NULL,
  `numeroNivel` int(11) DEFAULT NULL,
  `id_curso` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_curso` (`id_curso`),
  CONSTRAINT `nivelescurso_ibfk_1` FOREIGN KEY (`id_curso`) REFERENCES `curso` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `inscripcion` (
  `id_alumno` int(11) NOT NULL,
  `id_curso` int(11) NOT NULL,
  `fecha_inscripcion` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_alumno`,`id_curso`),
  KEY `id_curso` (`id_curso`),
  CONSTRAINT `inscripcion_ibfk_1` FOREIGN KEY (`id_alumno`) REFERENCES `usuario` (`id`),
  CONSTRAINT `inscripcion_ibfk_2` FOREIGN KEY (`id_curso`) REFERENCES `curso` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `kardex` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_alumno` int(11) DEFAULT NULL,
  `id_curso` int(11) DEFAULT NULL,
  `lvl_Actual` int(11) DEFAULT 0,
  `calificacion` decimal(4,2) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id_alumno` (`id_alumno`),
  KEY `id_curso` (`id_curso`),
  CONSTRAINT `kardex_ibfk_1` FOREIGN KEY (`id_alumno`) REFERENCES `usuario` (`id`),
  CONSTRAINT `kardex_ibfk_2` FOREIGN KEY (`id_curso`) REFERENCES `curso` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `transaccion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_alumno` int(11) DEFAULT NULL,
  `id_curso` int(11) DEFAULT NULL,
  `monto` decimal(10,2) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `estatus` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_alumno` (`id_alumno`),
  KEY `id_curso` (`id_curso`),
  CONSTRAINT `transaccion_ibfk_1` FOREIGN KEY (`id_alumno`) REFERENCES `usuario` (`id`),
  CONSTRAINT `transaccion_ibfk_2` FOREIGN KEY (`id_curso`) REFERENCES `curso` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;










-- |||||||||||||||||||||||||||| VIEWS ||||||||||||||||||||||||||||||||

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `bdm-capa3`.`vista_alumnorpt` AS select `u`.`id` AS `ID`,`u`.`intentos_fallidos` AS `intentos`,`u`.`estado` AS `estado`,concat(`u`.`nombre`,' ',`u`.`apellidos`) AS `Nombre`,`u`.`fecha_creacion` AS `Fecha_ingreso`,count(`i`.`id_curso`) AS `Cursos_inscritos`,round(sum(case when `k`.`lvl_Actual` >= `c`.`niveles` then 1 else 0 end) / count(`i`.`id_curso`) * 100,2) AS `Porcentaje_terminados` from (((`bdm-capa`.`usuario` `u` left join `bdm-capa`.`inscripcion` `i` on(`i`.`id_alumno` = `u`.`id`)) left join `bdm-capa`.`curso` `c` on(`c`.`id` = `i`.`id_curso`)) left join `bdm-capa`.`kardex` `k` on(`k`.`id_alumno` = `u`.`id` and `k`.`id_curso` = `c`.`id`)) where `u`.`tipo_usuario` = 1 group by `u`.`id`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `bdm-capa3`.`vista_alumnosinscritos` AS select `i`.`id_alumno` AS `id_alumno`,`c`.`titulo` AS `Curso`,`c`.`id_maestro` AS `Maestro_ID`,`c`.`status` AS `Curso_st`,`u`.`nombre` AS `Alumno`,`k`.`lvl_Actual` AS `Nivel_actual`,`i`.`fecha_inscripcion` AS `Inscripcion`,`t`.`monto` AS `Pago`,`cat`.`id` AS `Categoria_ID`,`cat`.`nombre` AS `Categoria` from (((((`bdm-capa`.`curso` `c` join `bdm-capa`.`categoria` `cat` on(`c`.`id_categoria` = `cat`.`id`)) join `bdm-capa`.`inscripcion` `i` on(`i`.`id_curso` = `c`.`id`)) join `bdm-capa`.`usuario` `u` on(`u`.`id` = `i`.`id_alumno`)) left join `bdm-capa`.`kardex` `k` on(`k`.`id_curso` = `c`.`id` and `k`.`id_alumno` = `i`.`id_alumno`)) left join `bdm-capa`.`transaccion` `t` on(`t`.`id_curso` = `c`.`id` and `t`.`id_alumno` = `i`.`id_alumno` and `t`.`estatus` = 1));

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `bdm-capa3`.`vista_instructorrpt` AS select `u`.`id` AS `ID`,`u`.`intentos_fallidos` AS `intentos`,`u`.`estado` AS `estado`,concat(`u`.`nombre`,' ',`u`.`apellidos`) AS `Nombre`,`u`.`fecha_creacion` AS `Fecha_ingreso`,count(distinct `c`.`id`) AS `Cursos_totales`,ifnull(sum(`t`.`monto`),0) AS `Ganancias` from ((`bdm-capa`.`usuario` `u` left join `bdm-capa`.`curso` `c` on(`c`.`id_maestro` = `u`.`id`)) left join `bdm-capa`.`transaccion` `t` on(`t`.`id_curso` = `c`.`id` and `t`.`estatus` = 1)) where `u`.`tipo_usuario` = 2 group by `u`.`id`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `bdm-capa3`.`vista_kardex` AS select `i`.`id_alumno` AS `id_alumno`,`c`.`titulo` AS `Nombre_de_curso`,`c`.`status` AS `Curso_status`,`cat`.`id` AS `Categoria_ID`,`cat`.`nombre` AS `Categoria`,case when `k`.`lvl_Actual` >= `c`.`niveles` then 1 else 0 end AS `Curso_terminado`,`i`.`fecha_inscripcion` AS `Fecha_de_inscripcion`,`k`.`fecha` AS `Ultima_fecha`,`k`.`lvl_Actual` AS `Niveles_tomados`,`c`.`niveles` AS `Niveles_totales` from ((((`bdm-capa`.`curso` `c` join `bdm-capa`.`categoria` `cat` on(`c`.`id_categoria` = `cat`.`id`)) join `bdm-capa`.`inscripcion` `i` on(`i`.`id_curso` = `c`.`id`)) join `bdm-capa`.`transaccion` `t` on(`t`.`id_curso` = `c`.`id` and `t`.`id_alumno` = `i`.`id_alumno`)) join `bdm-capa`.`kardex` `k` on(`k`.`id_curso` = `c`.`id` and `k`.`id_alumno` = `i`.`id_alumno`));

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `bdm-capa3`.`vista_resumencurso` AS select `c`.`id` AS `ID_Curso`,`c`.`id_maestro` AS `id_maestro`,`c`.`titulo` AS `Nombre`,`c`.`status` AS `Curso_st`,`c`.`fe_Creacion` AS `Fecha_creacion`,`cat`.`id` AS `Categoria_ID`,`cat`.`nombre` AS `Categoria`,count(`i`.`id_alumno`) AS `Alumnos_inscritos`,ifnull(sum(`t`.`monto`),0) AS `Ingresos_totales` from (((`bdm-capa`.`curso` `c` join `bdm-capa`.`categoria` `cat` on(`c`.`id_categoria` = `cat`.`id`)) left join `bdm-capa`.`inscripcion` `i` on(`i`.`id_curso` = `c`.`id`)) left join `bdm-capa`.`transaccion` `t` on(`t`.`id_curso` = `c`.`id` and `t`.`estatus` = 1)) group by `c`.`id`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `bdm-capa3`.`vw_reporte_categorias_creadas` AS select `ac`.`id_admin` AS `id_admin`,`u`.`nombre_usuario` AS `nombre_admin`,count(`ac`.`id`) AS `total_categorias`,cast(`ac`.`fecha_creacion` as date) AS `fecha_creacion` from (`bdm-capa`.`reporte_categoria` `ac` join `bdm-capa`.`usuario` `u` on(`ac`.`id_admin` = `u`.`id`)) group by `ac`.`id_admin`,`u`.`nombre_usuario`,cast(`ac`.`fecha_creacion` as date);

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `bdm-capa3`.`vw_reporte_usuarios` AS select `u`.`id` AS `id`,`u`.`nombre_usuario` AS `nombre_usuario`,`u`.`nombre` AS `nombre`,`u`.`apellidos` AS `apellidos`,`u`.`email` AS `email`,`tu`.`descripcion` AS `tipo_usuario` from (`bdm-capa`.`usuario` `u` join `bdm-capa`.`tipo_usuario` `tu` on(`u`.`tipo_usuario` = `tu`.`id`));









-- |||||||||||||||||||||||||||| STORED PROCEDURES ||||||||||||||||||||||||||||||||

DELIMITER $$
CREATE PROCEDURE `sp_agregar_curso`( 
    IN p_titulo VARCHAR(255),
    IN p_descripcion TEXT,
    IN p_precio DECIMAL(10,2),
    IN p_contenido TEXT,
    IN p_id_maestro INT,
    IN p_id_categoria INT,
    IN p_foto BLOB
)
BEGIN
    
    INSERT INTO curso (titulo, descripcion, precio, contenido, id_maestro, id_categoria, foto)
    VALUES (p_titulo, p_descripcion, p_precio, p_contenido, p_id_maestro, p_id_categoria,p_foto);
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `sp_alumnos_inscritos`( -- Reporte de ventar por alumno (con o sin filtros)
    IN p_id_usuario INT,
    IN p_fecha_inicio DATE,
    IN p_fecha_fin DATE,
    IN p_categoria_id INT,
    IN p_activo BOOLEAN
)
BEGIN
    SELECT V.id_alumno,
           V.Curso, -- Nombre del curso
           V.Curso_st, 
           V.Alumno, -- Alumno
           V.Nivel_actual,
           V.Inscripcion, -- fecha
           V.Pago -- pago + FaltaForma de pago default tarjeta
    FROM vista_AlumnosInscritos V
    WHERE (V.Maestro_ID = p_id_usuario OR p_id_usuario IS NULL OR p_id_usuario = 0)
      AND (V.Inscripcion >= p_fecha_inicio OR p_fecha_inicio IS NULL)
      AND (V.Inscripcion <= p_fecha_fin OR p_fecha_fin IS NULL)
      AND (V.Categoria_ID = p_categoria_id OR p_categoria_id = 0 OR p_categoria_id IS NULL)
      AND (V.Curso_st = p_activo OR p_activo IS NULL)
    ORDER BY V.Curso;

END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `sp_Categorias`( -- muestra las categorias
    IN p_categoria_id INT
)
BEGIN

    IF p_categoria_id IS NULL OR p_categoria_id = 0 THEN
        SELECT id, nombre FROM categoria;
    ELSE
        SELECT id, nombre, descripcion FROM categoria WHERE id = p_categoria_id; -- Una en especifico (por si se ocupa idk)
    END IF;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `sp_consultar_usuario`( 
    IN p_id INT
)
BEGIN
    -- Consulta la información del usuario
    SELECT u.*, tu.descripcion AS tipo_usuario
    FROM usuario u
    JOIN tipo_usuario tu ON u.tipo_usuario = tu.id
    WHERE u.id = p_id;
    
    -- Si es un instructor, mostrar la biografía y cuenta bancaria
    IF (SELECT tipo_usuario FROM usuario WHERE id = p_id) = 2 THEN
        SELECT biografia, cuenta_bancaria
        FROM instructor
        WHERE id = p_id;
    END IF;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `sp_crear_usuario`(
    IN p_nombre_usuario VARCHAR(100),
    IN p_nombre VARCHAR(100),
    IN p_apellidos VARCHAR(100),
    IN p_email VARCHAR(100),
    IN p_contrasena VARCHAR(255),
    IN p_tipo_usuario TINYINT,
    IN p_foto BLOB,
    IN p_fecha_nacimiento DATE,
    IN p_telefono BIGINT,
    IN p_biografia TEXT,
    IN p_cuenta_bancaria VARCHAR(50)
)
BEGIN
    -- Validación del formato del email
    IF p_email NOT REGEXP '^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\\.[A-Za-z]{2,}$' THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'El formato del email no es válido';
    END IF;

IF p_contrasena NOT REGEXP '^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d)(?=.*[@$!%*?&.])[A-Za-z\\d@$!%*?&.]{8,}$' THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La contraseña no cumple con los requisitos: debe tener al menos 8 caracteres, una letra mayúscula, una letra minúscula, un número y un carácter especial';
END IF;


    -- Validar si el email ya existe
    IF EXISTS (SELECT 1 FROM usuario WHERE email = p_email) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'El email ya está en uso';
    ELSE
        -- Insertar el nuevo usuario si las validaciones son correctas
        INSERT INTO usuario (nombre_usuario, nombre, apellidos, email, contrasena, tipo_usuario, foto, fecha_nacimiento, telefono)
        VALUES (p_nombre_usuario, p_nombre, p_apellidos, p_email, p_contrasena, p_tipo_usuario, p_foto, p_fecha_nacimiento, p_telefono);

        -- Si el usuario es un instructor, insertar biografía y cuenta bancaria
        IF p_tipo_usuario = 2 THEN
            INSERT INTO instructor (id, biografia, cuenta_bancaria)
            VALUES (LAST_INSERT_ID(), p_biografia, p_cuenta_bancaria);
        END IF;
    END IF;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `sp_eliminar_logico_usuario`(
    IN p_id INT
)
BEGIN
    -- Verifica si el usuario ya está inactivo
    IF (SELECT estado FROM usuario WHERE id = p_id) = FALSE THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'El usuario ya está inactivo';
    ELSE
        -- Cambiar el estado del usuario a inactivo (FALSE)
        UPDATE usuario
        SET estado = FALSE
        WHERE id = p_id;
    END IF;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `sp_eliminar_usuario`(
    IN p_id INT
)
BEGIN
    -- Si el usuario es un instructor
    IF (SELECT tipo_usuario FROM usuario WHERE id = p_id) = 2 THEN
        DELETE FROM instructor WHERE id = p_id;
    END IF;

    -- Eliminar al usuario de la tabla usuario
    DELETE FROM usuario WHERE id = p_id;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `sp_kardex_filtros`( -- Reporte de kardex
    IN p_id_alumno INT,
    IN p_fecha_inicio DATE,
    IN p_fecha_fin DATE,
    IN p_categoria_id INT,
    IN p_activo BOOLEAN,
    IN p_completado INT
)
BEGIN
    SELECT V.id_alumno,
           V.Nombre_de_curso,
           V.Curso_status,
           V.Categoria_ID,
           V.Categoria,
           V.Curso_terminado,
           V.Fecha_de_inscripcion,
           V.Ultima_fecha,
           V.Niveles_tomados,
           V.Niveles_totales
    FROM vista_Kardex V
    WHERE (V.id_alumno = p_id_alumno OR p_id_alumno IS NULL)
      AND (V.Fecha_de_inscripcion >= p_fecha_inicio OR p_fecha_inicio IS NULL)
      AND (V.Fecha_de_inscripcion <= p_fecha_fin OR p_fecha_fin IS NULL)
      AND (V.Categoria_ID = p_categoria_id OR p_categoria_id = 0 OR p_categoria_id IS NULL)
      AND (V.Curso_status = p_activo OR p_activo IS NULL)
      AND (V.Curso_terminado = p_completado OR p_completado IS NULL)
    ORDER BY V.Ultima_fecha DESC;

END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `sp_login`( 
    IN p_email VARCHAR(100),
    IN p_contrasena VARCHAR(255)
)
BEGIN
    DECLARE v_id INT;
    DECLARE v_tipo_usuario TINYINT;

    -- Verifica si el email y la contraseña son correctos
    SELECT id, tipo_usuario INTO v_id, v_tipo_usuario
    FROM usuario
    WHERE email = p_email AND contrasena = p_contrasena;

    -- Rretorna el id y tipo de usuario
    IF v_id IS NOT NULL THEN
        SELECT v_id AS id, v_tipo_usuario AS tipo_usuario;
    ELSE
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Email o contraseña incorrectos';
    END IF;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `sp_niveles_curso`( -- Agrega los niveles al curso creado 
    IN p_video VARCHAR(255),
    IN p_texto TEXT,
    IN p_numero INT 
)
BEGIN

DECLARE idCurso  INT;
SET idCurso = (SELECT max(id) FROM curso); 

 insert into nivelesCurso (video,texto,numeroNivel,id_curso)
 values (p_video,p_texto,p_numero, idCurso);
    
    
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `sp_reporteUser`( -- Reporte para admins (1 para alumnos y 2 para maestros)
    IN p_Vista INT
)
BEGIN
    IF p_Vista = 1 THEN
    
        SELECT V.ID,
			V.intentos,
            V.estado,
            V.Nombre,
            V.Fecha_ingreso,
            V.Cursos_inscritos,
            V.Porcentaje_terminados
        FROM vista_AlumnoRpt V  -- SELECT *  FROM vista_AlumnoRpt V 
        WHERE V.estado = 1
        ORDER BY V.ID;
        
    ELSEIF p_Vista = 2 THEN
    
        SELECT  V.ID,
				V.intentos,
				V.estado,
				V.Nombre,
				V.Fecha_ingreso,
				V.Cursos_totales,
				V.Ganancias
        FROM vista_InstructorRpt V
        WHERE V.estado = 1
        ORDER BY V.ID;
        
    ELSE
        SELECT 'Error en procedure' AS error;
    END IF;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `sp_resumen_curso`( -- Reporte de ventas del curso para profesor
    IN p_id_usuario INT,
    IN p_fecha_inicio DATE,
    IN p_fecha_fin DATE,
    IN p_categoria_id INT,
    IN p_activo BOOLEAN
)
BEGIN
    SELECT V.ID_Curso, -- Num
           V.id_maestro, -- 
           V.Nombre, -- Nombre
           V.Curso_st,
           V.Fecha_creacion, -- Fecha creacion
           V.Categoria_ID,
           V.Categoria, -- Categoria
           V.Alumnos_inscritos, -- Alumnos Inscritos 
           V.Ingresos_totales -- Ingresos totales
    FROM vista_ResumenCurso V
    WHERE (V.Fecha_creacion >= p_fecha_inicio OR p_fecha_inicio IS NULL)
      AND (V.Fecha_creacion <= p_fecha_fin OR p_fecha_fin IS NULL)
      AND (V.Categoria_ID = p_categoria_id OR p_categoria_id = 0 OR p_categoria_id IS NULL)
      AND (V.Curso_st = p_activo OR p_activo IS NULL)
      AND (V.id_maestro = p_id_usuario OR p_id_usuario IS NULL OR p_id_usuario = 0)
    ORDER BY V.ID_Curso;

END$$
DELIMITER ;


-- |||||||||||||||||||||||||||||| Inserts importantes:

INSERT INTO tipo_usuario (id, descripcion)
VALUES
    (1, 'Alumno'),
    (2, 'Instructor'),
    (3, 'Administrador');
    
INSERT INTO usuario (nombre_usuario, nombre, apellidos, email, contrasena, tipo_usuario, fecha_nacimiento, telefono, biografia, cuenta_bancaria)
VALUES 
    ('admin_user', 'Admin', 'User', 'admin@example.com', 'password123', 3, '1980-05-10', 1234567890, NULL, NULL), -- Administrador
    ('instructor_user', 'John', 'Doe', 'john@example.com', 'password123', 2, '1985-07-15', 1234567891, 'Experienced instructor', '1234567890123456'), -- Instructor
    ('student_user', 'Jane', 'Smith', 'jane@example.com', 'password123', 1, '2000-09-20', 1234567892, NULL, NULL); -- Alumna
    
INSERT INTO `bdm-capa`.`categoria` (`nombre`, `descripcion`)
VALUES  ('Matematicas','Matematicas'),
		('Arte','Arte'),
		('Computo','Computo');


        DELIMITER $$

CREATE PROCEDURE sp_obtener_cursos(
    IN p_categoria_id INT,
    IN p_tipo VARCHAR(20) -- 'recientes', 'vendidos', 'calificados'
)
BEGIN
    CASE p_tipo
        WHEN 'recientes' THEN
            -- conseguir los mas recientes
            SELECT 
                c.id,
                c.titulo,
                c.descripcion,
                c.precio,
                c.foto,
                cat.nombre as categoria,
                CONCAT(u.nombre, ' ', u.apellidos) as autor
            FROM curso c
            JOIN categoria cat ON c.id_categoria = cat.id 
            JOIN usuario u ON c.id_maestro = u.id
            WHERE (c.id_categoria = p_categoria_id OR p_categoria_id = 0)
            AND c.status = 1
            ORDER BY c.fe_Creacion DESC
            LIMIT 6;
            
        WHEN 'vendidos' THEN
            -- conseguir los cursos mas vendidos 
            SELECT 
                c.id,
                c.titulo,
                c.descripcion,
                c.precio,
                c.foto,
                cat.nombre as categoria,
                CONCAT(u.nombre, ' ', u.apellidos) as autor,
                COUNT(i.id_curso) as total_ventas
            FROM curso c
            JOIN categoria cat ON c.id_categoria = cat.id
            JOIN usuario u ON c.id_maestro = u.id
            LEFT JOIN inscripcion i ON c.id = i.id_curso
            WHERE (c.id_categoria = p_categoria_id OR p_categoria_id = 0)
            AND c.status = 1
            GROUP BY c.id
            ORDER BY total_ventas DESC
            LIMIT 6;
    END CASE;
END$$

DELIMITER ;


CREATE VIEW vista_curso_detalle AS
SELECT 
    c.*,
    cat.nombre as categoria,
    CONCAT(u.nombre, ' ', u.apellidos) as autor,
    COUNT(DISTINCT i.id_alumno) as total_inscritos,
    AVG(CASE WHEN com.id IS NOT NULL THEN 1 ELSE 0 END) as promedio_calificacion
FROM curso c
JOIN categoria cat ON c.id_categoria = cat.id 
JOIN usuario u ON c.id_maestro = u.id
LEFT JOIN inscripcion i ON i.id_curso = c.id
LEFT JOIN comentario com ON com.id_curso = c.id
WHERE c.status = 1
GROUP BY c.id;