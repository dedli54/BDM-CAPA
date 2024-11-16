CREATE DATABASE BDM;

USING DATABASE BDM;

/* test
usando workbech, además no se pudo crear el trigger: tg_categoria_creada
CREATE DATABASE `bdm-capa`;

USE `bdm-capa`;
*/

-- TABLAS-- 

-- LA PRIMER TABLA QUE SE DEBE CREAR ES LA DE TIPO DE USUARIOS

CREATE TABLE tipo_usuario (
    id TINYINT PRIMARY KEY,
    descripcion VARCHAR(50) NOT NULL
);

-- Insertar los roles (1: Admin, 2: Instructor, 3: Alumno)
INSERT INTO tipo_usuario (id, descripcion)
VALUES
    (1, 'Alumno'),
    (2, 'Instructor'),
    (3, 'Administrador');

/* EDITE LA TABLA PORQUE LA FUNCIONALIDAD DEL PROYECTO ES 1 Alumno, 2 Profe y 3 Admin
UPDATE tipo_usuario 
SET descripcion = 'Alumno'
WHERE id = 1;

UPDATE tipo_usuario 
SET descripcion = 'Administrador'
WHERE id = 3; -- select * from tipo_usuario
*/
    
-- Se cambio la tabla por que se me hizo muy innecesario tener 2 tablas admin y mejor agrege como campo no obligatorio biografia  cuenta bancaria por si es un usuario tipo instructor
CREATE TABLE usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_usuario VARCHAR(100) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL,
    foto BLOB, -- Puedes usar BLOB o VARCHAR(255), dependiendo de tu decisión
    fecha_nacimiento DATE,
    intentos_fallidos TINYINT DEFAULT 0,
    estado BOOLEAN DEFAULT TRUE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    telefono BIGINT,
    tipo_usuario TINYINT NOT NULL, -- 1: Admin, 2: Instructor, 3: Alumno
    biografia TEXT, -- Campo adicional para el instructor
    cuenta_bancaria VARCHAR(50), -- Campo adicional para el instructor
    FOREIGN KEY (tipo_usuario) REFERENCES tipo_usuario(id)
);
-- Insertar 3 usuarios con perfiles diferentes(DATOS DUMMY)
INSERT INTO usuario (nombre_usuario, nombre, apellidos, email, contrasena, tipo_usuario, fecha_nacimiento, telefono, biografia, cuenta_bancaria)
VALUES 
    ('admin_user', 'Admin', 'User', 'admin@example.com', 'password123', 1, '1980-05-10', 1234567890, NULL, NULL), -- Administrador
    ('instructor_user', 'John', 'Doe', 'john@example.com', 'password123', 2, '1985-07-15', 1234567891, 'Experienced instructor', '1234567890123456'), -- Instructor
    ('student_user', 'Jane', 'Smith', 'jane@example.com', 'password123', 3, '2000-09-20', 1234567892, NULL, NULL); -- Alumna


CREATE TABLE categoria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT
);

INSERT INTO `bdm-capa`.`categoria` (`nombre`, `descripcion`)
VALUES  ('Matematicas','Matematicas'),
		('Arte','Arte'),
		('Computo','Computo');


CREATE TABLE reporte_categoria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_admin INT,
    nombre_admin VARCHAR(100),
    nombre_categoria VARCHAR(100),
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_admin) REFERENCES usuario(id)
);

alter table curso
add column fe_Creacion DATETIME DEFAULT CURRENT_TIMESTAMP ;


-- Cursos creados por maestros
CREATE TABLE curso (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    descripcion TEXT,
    status BOOL DEFAULT 1,
    foto BLOB,
    precio DECIMAL(10,2),
    contenido TEXT,
    id_maestro INT,
    id_categoria INT,
    niveles INT DEFAULT 1, -- recien agregado
    fe_Creacion DATETIME DEFAULT CURRENT_TIMESTAMP, -- same
    FOREIGN KEY (id_maestro) REFERENCES usuario(id),
    FOREIGN KEY (id_categoria) REFERENCES Categoria(id)
);


-- Esta tabla guardara los datos de que alumno se inscribio a tal curso
CREATE TABLE inscripcion (
    id_alumno INT,
    id_curso INT,
    fecha_inscripcion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_alumno, id_curso),
    FOREIGN KEY (id_alumno) REFERENCES Usuario(id),
    FOREIGN KEY (id_curso) REFERENCES Curso(id)
);

-- transacciones realizadas por los alumnos hacia los maestros PREGUNTAR AL PROFE , TENGO DUDAS DE LA ESTRUCTURACION DE LOS DATOS EN ESTA TABLA
CREATE TABLE transaccion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_alumno INT,
    id_curso INT,
    monto DECIMAL(10,2),
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- fecha de compra igual a fecha de la inscripcion
    estatus BOOLEAN,-- estatus de la transaccion , rechazada o aceptada
    FOREIGN KEY (id_alumno) REFERENCES Usuario(id),
    FOREIGN KEY (id_curso) REFERENCES Curso(id)
);


CREATE TABLE comentario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_alumno INT,
    id_curso INT,
    texto TEXT,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_alumno) REFERENCES Usuario(id), -- Referenciamos al alumno que hizo el comentario
    FOREIGN KEY (id_curso) REFERENCES Curso(id) -- referenciamos a que curso se vera el comentario
);
-- drop table kardex
CREATE TABLE kardex (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_alumno INT,
    id_curso INT,
    lvl_Actual INT DEFAULT 0, -- nivel en el que se encuentra
    calificacion DECIMAL(4,2),
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_alumno) REFERENCES Usuario(id),
    FOREIGN KEY (id_curso) REFERENCES Curso(id)
);

-- TABLAS --

-- VIEWS --

-- Esta View lo que hace es traer datos principales del usuario, como el nombre de la cuenta , el correo , nombre del usuario , y con la otra tabla tipo de usuario trae el tipo de usuario que sea
CREATE VIEW vw_reporte_usuarios AS
SELECT u.id, u.nombre_usuario, u.nombre, u.apellidos, u.email, tu.descripcion AS tipo_usuario
FROM usuario u
JOIN tipo_usuario tu ON u.tipo_usuario = tu.id;


-- ---------------------------------------
-- para que el triger de la categoria creada funcione debe crearse esta view y a su vez la tabla de reporte categoria
CREATE VIEW vw_reporte_categorias_creadas AS
SELECT ac.id_admin, u.nombre_usuario AS nombre_admin, COUNT(ac.id) AS total_categorias, 
       DATE(ac.fecha_creacion) AS fecha_creacion
FROM reporte_categoria ac
JOIN usuario u ON ac.id_admin = u.id
GROUP BY ac.id_admin, u.nombre_usuario, DATE(ac.fecha_creacion);


-- VIEWS --

-- TRIGGERS -- 

DELIMITER $$
CREATE TRIGGER tg_categoria_creada
AFTER INSERT ON categoria
FOR EACH ROW
BEGIN
    DECLARE admin_nombre VARCHAR(100);
    
    -- Obtener el nombre del administrador que está creando la categoría
    SELECT nombre_usuario INTO admin_nombre
    FROM usuario
    WHERE id = NEW.id_admin AND tipo_usuario = 1; -- 1 es el tipo para Administrador

    -- Registrar la categoría creada en la tabla de auditoría
    INSERT INTO reporte_categoria (id_admin, nombre_admin, nombre_categoria)
    VALUES (NEW.id_admin, admin_nombre, NEW.nombre);
END$$
DELIMITER ;


-- TRIGGERS -- 

-- STORE PROCEDURE -- 

-- SP para crear usuarios
DROP PROCEDURE sp_crear_usuario

/*
DELIMITER $$

CREATE PROCEDURE sp_crear_usuario(
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
    -- Aqui validamos que sea algun formato correcto de gmail como .uanl .com .org
    IF p_email NOT REGEXP '^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\\.[A-Za-z]{2,}$' THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'El formato del email no es válido';
    END IF;

    -- Aqui validamos los requisitos de la contraseña
    IF p_contrasena NOT REGEXP '^(?=.[a-z])(?=.[A-Z])(?=.\\d)(?=.[@$!%?&])[A-Za-z\\d@$!%?&]{8,}$' THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La contraseña no cumple con los requisitos: debe tener al menos 8 caracteres, una letra mayúscula, una letra minúscula, un número y un carácter especial';
    END IF;

    -- Aqui validamos si el email ya existe
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
    END IF;
END$$

DELIMITER ;

*/
DELIMITER //

CREATE PROCEDURE sp_crear_usuario( -- el chido, el que jala
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
    
    IF p_email NOT REGEXP '^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\\.[A-Za-z]{2,}$' THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'El formato del email no es válido';
    END IF;

IF p_contrasena NOT REGEXP '^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d)(?=.*[@$!%*?&.])[A-Za-z\\d@$!%*?&.]{8,}$' THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La contraseña no cumple con los requisitos: debe tener al menos 8 caracteres, una letra mayúscula, una letra minúscula, un número y un carácter especial';
END IF;


    
    IF EXISTS (SELECT 1 FROM usuario WHERE email = p_email) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'El email ya está en uso';
    ELSE
        
        INSERT INTO usuario (nombre_usuario, nombre, apellidos, email, contrasena, tipo_usuario, foto, fecha_nacimiento, telefono)
        VALUES (p_nombre_usuario, p_nombre, p_apellidos, p_email, p_contrasena, p_tipo_usuario, p_foto, p_fecha_nacimiento, p_telefono);

        
        IF p_tipo_usuario = 2 THEN
            INSERT INTO instructor (id, biografia, cuenta_bancaria)
            VALUES (LAST_INSERT_ID(), p_biografia, p_cuenta_bancaria);
        END IF;
    END IF;
END //

DELIMITER ;




-- SP para editar usuarios
DELIMITER $$

CREATE PROCEDURE sp_editar_usuario(
    IN p_id INT,
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
    UPDATE usuario
    SET nombre_usuario = p_nombre_usuario,
        nombre = p_nombre,
        apellidos = p_apellidos,
        email = p_email,
        contrasena = p_contrasena,
        tipo_usuario = p_tipo_usuario,
        foto = p_foto,
        fecha_nacimiento = p_fecha_nacimiento,
        telefono = p_telefono
    WHERE id = p_id;

    -- Si el usuario es un instructor, actualizar biografía y cuenta bancaria
    /*
    IF p_tipo_usuario = 2 THEN
        UPDATE instructor
        SET biografia = p_biografia,
            cuenta_bancaria = p_cuenta_bancaria
        WHERE id = p_id;
    END IF;*/
END$$

DELIMITER ;

/*
DELIMITER // -- El que funciona

CREATE PROCEDURE sp_crear_usuario(
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
END //

DELIMITER ;

*/


DELIMITER ;

-- SP visualizar usuarios , podria usarse para ver perfil o para una visualizacion en los reportes de algun administrados
DELIMITER $$

CREATE PROCEDURE sp_consultar_usuario(
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

-- SP Eliminado logico , esto lo que hara solamente sera pasar a ser un usuario inactivo 

DELIMITER $$

CREATE PROCEDURE sp_eliminar_logico_usuario(
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


-- SP para eliminar un usuario , OJO, esto no es un desactivado es una ELIMINACION TOTAL, falta crear el SP para desactivar un usuario pero que siga existiendo en la BD
DELIMITER $$

CREATE PROCEDURE sp_eliminar_usuario(
    IN p_id INT
)
BEGIN
    -- Si el usuario es un instructor, eliminar su información adicional
    IF (SELECT tipo_usuario FROM usuario WHERE id = p_id) = 2 THEN
        DELETE FROM instructor WHERE id = p_id;
    END IF;

    -- Eliminar al usuario de la tabla usuario
    DELETE FROM usuario WHERE id = p_id;
END$$

DELIMITER ;

-- STORE PROCEDURE --

-- SP agregar inscripcion a un curso --

DELIMITER $$

CREATE PROCEDURE sp_agregar_inscripcion (
    IN p_id_alumno INT,
    IN p_id_curso INT
)
BEGIN
    -- Verifica si curso existe
    IF EXISTS (SELECT 1 FROM curso WHERE id = p_id_curso) THEN
        -- Verifica si ya esta inscrito
        IF NOT EXISTS (SELECT 1 FROM inscripcion WHERE id_alumno = p_id_alumno AND id_curso = p_id_curso) THEN
            -- Nueva Inscripcion
            INSERT INTO inscripcion (id_alumno, id_curso)
            VALUES (p_id_alumno, p_id_curso);
        END IF;
    END IF;
END $$

DELIMITER ;

-- SP para borrar inscripcion --

DELIMITER $$

CREATE PROCEDURE sp_borrar_inscripcion (
    IN p_id_alumno INT,
    IN p_id_curso INT
)
BEGIN
    -- Eliminar inscripción si existe
    DELETE FROM inscripcion
    WHERE id_alumno = p_id_alumno AND id_curso = p_id_curso;
END $$

DELIMITER ;

-- SP agregar curso para maestro --


DELIMITER $$

CREATE PROCEDURE sp_agregar_curso (
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
END $$


DELIMITER ;

-- SP editar curso para maestro--
DELIMITER $$

CREATE PROCEDURE sp_editar_curso (
    IN p_id INT,
    IN p_titulo VARCHAR(255),
    IN p_descripcion TEXT,
    IN p_precio DECIMAL(10,2),
    IN p_contenido TEXT,
    IN p_id_maestro INT,
    IN p_id_categoria INT
)
BEGIN
    
    UPDATE curso
    SET titulo = p_titulo,
        descripcion = p_descripcion,
        precio = p_precio,
        contenido = p_contenido,
        id_maestro = p_id_maestro,
        id_categoria = p_id_categoria
    WHERE id = p_id;
END $$

DELIMITER ;

-- SP borrar curso--

DELIMITER $$

CREATE PROCEDURE sp_borrar_curso (
    IN p_id_curso INT,
    IN p_id_maestro INT
)
BEGIN
    
    DELETE FROM curso
    WHERE id = p_id_curso AND id_maestro = p_id_maestro;
END $$

DELIMITER ;

-- STORE PROCEDURE--

-- PROCEDURE PARA EL LOGIN con lo de 3 intentos actualizado

DELIMITER $$
CREATE PROCEDURE `sp_login`( 
    IN p_email VARCHAR(100),
    IN p_contrasena VARCHAR(255)
)
BEGIN
    DECLARE v_id INT;
    DECLARE v_tipo_usuario TINYINT;
    DECLARE v_estado TINYINT;
    DECLARE v_intentos INT;

    -- agarra la info del user
    SELECT id, tipo_usuario, estado, intentos_fallidos 
    INTO v_id, v_tipo_usuario, v_estado, v_intentos
    FROM usuario 
    WHERE email = p_email;

    -- checa si existe
    IF v_id IS NULL THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Email o contraseña incorrectos';
    END IF;

    -- checa si esta bloqueada
    IF v_estado = 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Cuenta bloqueada';
    END IF;

    -- valida contra
    IF p_contrasena = (SELECT contrasena FROM usuario WHERE id = v_id) THEN
        -- contra correcta restablece intentos
        UPDATE usuario 
        SET intentos_fallidos = 0 
        WHERE id = v_id;
        
        -- regresa user info
        SELECT v_id AS id, v_tipo_usuario AS tipo_usuario;
    ELSE
        -- aumenta los intentos fallidos
        SET v_intentos = v_intentos + 1;
        
        UPDATE usuario 
        SET intentos_fallidos = v_intentos,
            estado = IF(v_intentos >= 3, 0, 1)
        WHERE id = v_id;

        -- errore de mensaje :P
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Email o contraseña incorrectos';
    END IF;

END$$
DELIMITER ;


-- Así se usa: CALL sp_login('admin@example.com', 'password123');
-- select * from usuario

-- SP 
DELIMITER //

CREATE PROCEDURE sp_crear_usuario(
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
    -- Aqui validamos que sea algun formato correcto de gmail como .uanl .com .org
    IF p_email NOT REGEXP '^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\\.[A-Za-z]{2,}$' THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'El formato del email no es válido';
    END IF;

    -- Aqui validamos los requisitos de la contraseña
    IF p_contrasena NOT REGEXP '^(?=.[a-z])(?=.[A-Z])(?=.\\d)(?=.[@$!%?&])[A-Za-z\\d@$!%?&]{8,}$' THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La contraseña no cumple con los requisitos: debe tener al menos 8 caracteres, una letra mayúscula, una letra minúscula, un número y un carácter especial';
    END IF;

    -- Aqui validamos si el email ya existe
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
    END IF;
END$$

DELIMITER //

-- Nomas agregas este SP a la base de datos y tambien cambie el validacion_usuario.php para que llame a este SP

-- Estados -1: Cuenta bloqueada, 0: Contraseña incorrecta, 1: Inicio de sesion exitoso
CREATE PROCEDURE sp_consultar_usuario(
    IN p_email VARCHAR(100),
    IN p_contrasena VARCHAR(255),
    OUT p_estado INT
    
)
BEGIN
    DECLARE v_contrasena_db VARCHAR(255);
    DECLARE v_intentos_fallidos INT;
    DECLARE v_estado INT;

    -- Verificar si el usuario existe y obtener su estado y contraseña
    SELECT contrasena, intentos_fallidos, estado 
    INTO v_contrasena_db, v_intentos_fallidos, v_estado 
    FROM usuario 
    WHERE email = p_email;

    -- Checar si el usuario esta habilitado
    IF v_estado = 0 THEN
        SET p_estado = -1; -- Usuario deshabilitado
    ELSE
        -- Validar la contraseña
        IF v_contrasena_db = p_contrasena THEN
            -- Si la contra es correcta reinicia el contador de intentos y retorna exito
            UPDATE usuario 
            SET intentos_fallidos = 0 
            WHERE email = p_email;
            SET p_estado = 1; -- Inicio de sesion exitoso
        ELSE
            -- Si la contra esta equivocada agrega +1 a los intento fallidos
            SET v_intentos_fallidos = v_intentos_fallidos + 1;
            UPDATE usuario 
            SET intentos_fallidos = v_intentos_fallidos 
            WHERE email = p_email;

            -- Checar si se debe deshabilitar a el usuaro
            IF v_intentos_fallidos >= 3 THEN
                UPDATE usuario 
                SET estado = 0 
                WHERE email = p_email;
                SET p_estado = -1; -- Usuario deshabilitado despues de 3 intentos
            ELSE
                SET p_estado = 0; -- Intento fallido, pero la cuenta sigue activa
            END IF;
        END IF;
    END IF;
END //

DELIMITER ;





-- 08/11/2024 Julian: Para organizarnos mejor puse acá lo nuevo que agregue, además del cambio en la tabla de cursos para guardar imagen
-- NIVELES DEL CURSO
CREATE TABLE nivelesCurso (
    id INT AUTO_INCREMENT PRIMARY KEY,
    video VARCHAR(255),
    texto TEXT,
    numeroNivel int,
    id_curso INT,
    FOREIGN KEY (id_curso) REFERENCES curso(id)
);

-- Procedure para crear niveles

DROP PROCEDURE IF EXISTS sp_niveles_curso;

DELIMITER $$

CREATE PROCEDURE sp_niveles_curso (
    IN p_video VARCHAR(255),
    IN p_texto TEXT,
    IN p_numero INT 
)
BEGIN

DECLARE idCurso  INT;
SET idCurso = (SELECT max(id) FROM curso); 

 insert into nivelesCurso (video,texto,numeroNivel,id_curso)
 values (p_video,p_texto,p_numero, idCurso);
    
    
END $$


DELIMITER ;
-- select * from nivelesCurso
-- CALL sp_niveles_curso('','','');



-- Views de reportes

/* --DATOS DE RELLENO

-- Inserciones en la tabla inscripcion
INSERT INTO inscripcion (id_alumno, id_curso)
VALUES 
    (1, 1), -- Alumno con id 1 inscrito en el curso con id 1
    (2, 1); -- Alumno con id 2 inscrito en el curso con id 1

-- Inserciones en la tabla transaccion
INSERT INTO transaccion (id_alumno, id_curso, monto, estatus)
VALUES 
    (1, 1, 120.00, TRUE), -- Transacción aceptada para alumno 1 en curso 1
    (2, 1, 120.00, FALSE); -- Transacción rechazada para alumno 2 en curso 1

-- Inserciones en la tabla comentario
INSERT INTO comentario (id_alumno, id_curso, texto)
VALUES 
    (1, 1, 'Muy buen curso, aprendí mucho.'),
    (2, 1, 'Interesante, aunque me gustaría ver más ejemplos.');

-- Inserciones en la tabla kardex
INSERT INTO kardex (id_alumno, id_curso, lvl_Actual, calificacion)
VALUES 
    (1, 1, 2, 88.50), -- Alumno con id 1 en nivel 2, calificación 88.50 en curso 1
    (2, 1, 1, 92.00); -- Alumno con id 2 en nivel 1, calificación 92.00 en curso 1

*/

-- |||||||||||||||||||||||||||||||||||||||||||||| -VISTAS PARA REPORTES- ||||||||||||||||||||||||||||||||||||||||||||||

/*
DROP VIEW IF EXISTS vista_Kardex;
DROP VIEW IF EXISTS vista_ResumenCurso;
DROP VIEW IF EXISTS vista_AlumnosInscritos;
DROP VIEW IF EXISTS vista_AlumnoRpt;
DROP VIEW IF EXISTS vista_InstructorRpt;

*/


-- Vista Kardex

CREATE VIEW IF NOT EXISTS vista_Kardex AS
SELECT I.id_alumno,
	   C.titulo AS Nombre_de_curso,
    C.status AS Curso_status,
	   Cat.id AS Categoria_ID,
	   Cat.nombre AS Categoria,
    CASE 
        WHEN K.lvl_Actual >= C.niveles THEN 1 
        ELSE 0
    END AS Curso_terminado,
    I.fecha_inscripcion AS Fecha_de_inscripcion,
    K.fecha AS Ultima_fecha, -- Ultima fecha que cursó algun nivel
    K.lvl_Actual AS Niveles_tomados,
    C.niveles AS Niveles_totales
FROM curso C 
JOIN categoria cat ON C.id_categoria = cat.id
JOIN inscripcion I ON I.id_curso = C.id
JOIN transaccion T ON t.id_curso = C.id AND T.id_alumno = I.id_alumno
JOIN kardex K ON K.id_curso = c.id AND K.id_alumno = I.id_alumno;



-- 			|||||| 	REPORTE DE VENTAS  ||||||

-- drop view if exists vista_ResumenCurso
-- drop view if exists vista_AlumnosInscritos
-- vista ventas por curso
select * from curso
select * from inscripcion where id_curso = 1;

UPDATE curso 
SET status = 0
WHERE id = 3;


CREATE VIEW IF NOT EXISTS vista_ResumenCurso AS
SELECT  C.id AS ID_Curso,
		C.id_maestro AS id_maestro,
		C.titulo AS Nombre, -- Nombre del curso
		C.status AS Curso_st,
		C.fe_Creacion AS Fecha_creacion,
		CAT.id AS Categoria_ID,
		CAT.nombre AS Categoria,
		COUNT(I.id_alumno) AS Alumnos_inscritos,
		IFNULL(SUM(T.monto), 0) AS Ingresos_totales
FROM curso C
JOIN categoria CAT ON C.id_categoria = CAT.id
LEFT JOIN inscripcion I ON I.id_curso = C.id
LEFT JOIN  transaccion T ON T.id_curso = C.id AND T.estatus = TRUE
GROUP BY C.id;

-- Vista ventas por alumno (trans individuales)


CREATE VIEW IF NOT EXISTS vista_AlumnosInscritos AS
SELECT I.id_alumno,
    C.titulo AS Curso,
    C.id_maestro AS Maestro_ID,
    C.status AS Curso_st,
    U.nombre AS Alumno,
    K.lvl_Actual AS Nivel_actual,
    I.fecha_inscripcion AS Inscripcion,
    T.monto AS Pago,
		CAT.id AS Categoria_ID,
		CAT.nombre AS Categoria
    -- T.forma_pago AS Forma_de_pago -- Aun no está en la tabla
FROM curso C
JOIN categoria CAT ON C.id_categoria = CAT.id
JOIN inscripcion I ON I.id_curso = C.id
JOIN usuario U ON U.id = I.id_alumno
LEFT JOIN kardex K ON K.id_curso = C.id AND K.id_alumno = I.id_alumno
LEFT JOIN transaccion T ON T.id_curso = C.id AND T.id_alumno = I.id_alumno AND T.estatus = TRUE;










    
--
-- PROCEDURES PARA LAS VENTANAS HTML

-- drop procedure if exists sp_kardex_filtros


-- select id,nombre,tipo_usuario from usuario WHERE tipo_usuario = 1;

/*Más datos de relleno para pruebas*/
/*
INSERT INTO inscripcion (id_alumno, id_curso, fecha_inscripcion) VALUES
    (3, 2, '2024-01-15'),
    (3, 3, '2024-02-10'),
    (6, 6, '2024-03-05'),
    (6, 8, '2024-04-01'),
    (8, 2, '2024-05-20'),
    (8, 6, '2024-06-15'),
    (9, 3, '2024-07-25'),
    (9, 8, '2024-08-30');
    
    INSERT INTO transaccion (id_alumno, id_curso, monto, fecha, estatus) VALUES
    (3, 2, 100.00, '2024-01-15', TRUE),
    (3, 3, 150.00, '2024-02-10', TRUE),
    (6, 6, 200.00, '2024-03-05', TRUE),
    (6, 8, 250.00, '2024-04-01', TRUE),
    (8, 2, 100.00, '2024-05-20', TRUE),
    (8, 6, 200.00, '2024-06-15', TRUE),
    (9, 3, 150.00, '2024-07-25', TRUE),
    (9, 8, 250.00, '2024-08-30', TRUE);
    
    INSERT INTO kardex (id_alumno, id_curso, lvl_Actual, calificacion, fecha) VALUES
    (3, 2, 3, 95.00, '2024-01-20'),
    (3, 3, 5, 90.00, '2024-02-15'),
    (6, 6, 2, 85.00, '2024-03-10'),
    (6, 8, 3, 88.00, '2024-04-05'),
    (8, 2, 1, 80.00, '2024-05-25'),
    (8, 6, 2, 92.00, '2024-06-20'),
    (9, 3, 4, 89.00, '2024-07-30'),
    (9, 8, 5, 94.00, '2024-09-05');
*/
-- select * from curso;
-- select * from nivelescurso



CALL sp_kardex_filtros(3, '2024-01-01', '2024-12-31', 2, TRUE, 1);
CALL sp_kardex_filtros(9, '2024-07-01', '2024-09-01', NULL, TRUE, NULL); 

DELIMITER $$

CREATE PROCEDURE sp_kardex_filtros(
    IN p_id_alumno INT,
    IN p_fecha_inicio DATE,
    IN p_fecha_fin DATE,
    IN p_categoria_id INT,
    IN p_activo BOOLEAN,
    IN p_completado INT
)
BEGIN
    SELECT V.id_alumno,
           V.Nombre_de_curso, -- TH Nombre de curso
           V.Curso_status, -- TH Estatus de curso (activo inactivo)
           V.Categoria_ID,
           V.Categoria, -- TH categoria
           V.Curso_terminado, -- TH Diploma  
           V.Fecha_de_inscripcion, -- TH fecha de inscripcion
           V.Ultima_fecha, -- TH ultima fecha
           V.Niveles_tomados, -- TH Niveles tomados 
           V.Niveles_totales -- TH Niveles totales
    FROM vista_Kardex V
    WHERE (V.id_alumno = p_id_alumno OR p_id_alumno IS NULL)
      AND (V.Fecha_de_inscripcion >= p_fecha_inicio OR p_fecha_inicio IS NULL)
      AND (V.Fecha_de_inscripcion <= p_fecha_fin OR p_fecha_fin IS NULL)
      AND (V.Categoria_ID = p_categoria_id OR p_categoria_id = 0 OR p_categoria_id IS NULL)
      AND (V.Curso_status = p_activo OR p_activo IS NULL)
      AND (V.Curso_terminado = p_completado OR p_completado IS NULL)
    ORDER BY V.Ultima_fecha DESC;

END $$

DELIMITER ;

-- FILTROS VENTA DE CURSOS

DELIMITER $$
-- TABLA CURSOS
CREATE PROCEDURE sp_resumen_curso( -- Muestra los cursos del profesor
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

END $$

DELIMITER ;

DELIMITER $$ 
-- DROP PROCEDURE IF EXISTS sp_resumen_curso
-- DROP PROCEDURE IF EXISTS sp_alumnos_inscritos
-- call sp_resumen_curso(0,null,null,null,null)
-- call sp_alumnos_inscritos(2,null,null,null,null)
CREATE PROCEDURE sp_alumnos_inscritos(
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

END $$

DELIMITER ;

-- select * from categoria

/*
INSERT INTO `bdm-capa`.`categoria` (`nombre`, `descripcion`)
VALUES ('Matematicas','Matematicas'),('Arte','Arte'), ('Computo','Computo');
*/
-- |||||||||||||||||||||||||||||||||||||||||||||||||||||||||| Mostrar las categorias en los select: ||||||||||||||||||||||||||||||||||||
-- DROP PROCEDURE if exists sp_Categorias
DELIMITER $$ 

-- Mostrar todas las categorias en los SELECT
CREATE PROCEDURE sp_Categorias(
    IN p_categoria_id INT
)
BEGIN

    IF p_categoria_id IS NULL OR p_categoria_id = 0 THEN
        SELECT id, nombre FROM categoria;
    ELSE
        SELECT id, nombre, descripcion FROM categoria WHERE id = p_categoria_id; -- Una en especifico (por si se ocupa idk)
    END IF;
END $$

DELIMITER ;
-- call sp_Categorias (0)


-- |||||||||||||||||||||||||||||||||||||||||||||||||||||||||| Reportes para admin ||||||||||||||||||||||||||||||||||||

-- USE `bdm-capa`;
-- select * from inscripcion

-- Info para admin de alumnos 

/*
DROP VIEW IF EXISTS vista_AlumnoRpt;
DROP VIEW IF EXISTS vista_InstructorRpt;
*/
CREATE VIEW IF NOT EXISTS vista_AlumnoRpt AS
SELECT U.id AS ID,
	U.intentos_fallidos AS intentos,
    U.estado, -- Activo inactivo
    CONCAT(U.nombre, ' ', U.apellidos) AS Nombre,
    U.fecha_creacion AS Fecha_ingreso,
    -- K.lvl_Actual,
    -- C.niveles,
    COUNT(I.id_curso) AS Cursos_inscritos,
    ROUND((SUM(
			CASE 
				WHEN K.lvl_Actual >= C.niveles THEN 1
				ELSE 0
			END) / COUNT(I.id_curso)) * 100, 2
    ) AS Porcentaje_terminados
FROM usuario U
LEFT JOIN inscripcion I ON I.id_alumno = U.id
LEFT JOIN curso C ON C.id = I.id_curso
LEFT JOIN kardex K ON K.id_alumno = U.id AND K.id_curso = C.id
WHERE  U.tipo_usuario = 1  -- Alumno 
GROUP BY  U.id; 

-- ------------------ select * from usuario

CREATE VIEW IF NOT EXISTS vista_InstructorRpt AS
SELECT 
    U.id AS ID,
	U.intentos_fallidos AS intentos,
    U.estado,
    CONCAT(U.nombre, ' ', U.apellidos) AS Nombre,
    U.fecha_creacion AS Fecha_ingreso,
    COUNT(DISTINCT C.id) AS Cursos_totales,
    IFNULL(SUM(T.monto), 0) AS Ganancias
FROM usuario U
LEFT JOIN curso C ON C.id_maestro = U.id
LEFT JOIN transaccion T ON T.id_curso = C.id AND T.estatus = TRUE
WHERE U.tipo_usuario = 2  -- Instructor
GROUP BY U.id; 



DELIMITER $$ 

-- DROP PROCEDURE IF EXISTS sp_reporteUser

CREATE PROCEDURE sp_reporteUser(
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
END $$

DELIMITER ;

-- select * from kardex

call sp_reporteUser (2)
