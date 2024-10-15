CREATE DATABASE bdm-capa;

USING DATABASE bdm-capa;
-- TABLAS--

-- LA PRIMER TABLA QUE SE DEBE CREAR ES LA DE TIPO DE USUARIOS

CREATE TABLE tipo_usuario (
    id TINYINT PRIMARY KEY,
    descripcion VARCHAR(50) NOT NULL
);

-- Insertar los roles (1: Admin, 2: Instructor, 3: Alumno)
INSERT INTO tipo_usuario (id, descripcion)
VALUES
    (1, 'Administrador'),
    (2, 'Instructor'),
    (3, 'Alumno');
    
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

CREATE TABLE reporte_categoria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_admin INT,
    nombre_admin VARCHAR(100),
    nombre_categoria VARCHAR(100),
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_admin) REFERENCES usuario(id)
);


-- Cursos creados por maestros
CREATE TABLE curso (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2),
    contenido TEXT,
    id_maestro INT,
    id_categoria INT,
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
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
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

CREATE TABLE kardex (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_alumno INT,
    id_curso INT,
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
    INSERT INTO auditoria_categoria (id_admin, nombre_admin, nombre_categoria)
    VALUES (NEW.id_admin, admin_nombre, NEW.nombre);
END$$
DELIMITER ;


-- TRIGGERS -- 

-- STORE PROCEDURE --


-- SP para crear usuarios
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
    -- Verifica si el email ya existe
    IF EXISTS (SELECT 1 FROM usuario WHERE email = p_email) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'El email ya está en uso';
    ELSE
        -- Si no existe, inserta el nuevo usuario
        INSERT INTO usuario (nombre_usuario, nombre, apellidos, email, contrasena, tipo_usuario, foto, fecha_nacimiento, telefono)
        VALUES (p_nombre_usuario, p_nombre, p_apellidos, p_email, p_contrasena, p_tipo_usuario, p_foto, p_fecha_nacimiento, p_telefono);

        -- Si el usuario es un instructor, insertar biografía y cuenta bancaria en tabla instructor
        IF p_tipo_usuario = 2 THEN
            INSERT INTO instructor (id, biografia, cuenta_bancaria)
            VALUES (LAST_INSERT_ID(), p_biografia, p_cuenta_bancaria);
        END IF;
    END IF;
END$$

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
    IF p_tipo_usuario = 2 THEN
        UPDATE instructor
        SET biografia = p_biografia,
            cuenta_bancaria = p_cuenta_bancaria
        WHERE id = p_id;
    END IF;
END$$

DELIMITER ;


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
    IN p_id_categoria INT
)
BEGIN
    
    INSERT INTO curso (titulo, descripcion, precio, contenido, id_maestro, id_categoria)
    VALUES (p_titulo, p_descripcion, p_precio, p_contenido, p_id_maestro, p_id_categoria);
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
