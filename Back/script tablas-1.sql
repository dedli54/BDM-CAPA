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


-- La tabla "padre" por asi decirlo ya que en esta tabla se almacenara toda la informacion 
CREATE TABLE usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_usuario VARCHAR(100) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL,
    foto BLOB, -- PREGUNTAR QUE ES MAS RECOMENDABLE SI USAR BLOB O USAR VARCHAR 
    fecha_nacimiento DATE,
    intentos_fallidos TINYINT DEFAULT 0,
    estado BOOLEAN DEFAULT TRUE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    telefono bigint,
    tipo_usuario TINYINT NOT NULL,
    FOREIGN KEY (tipo_usuario) REFERENCES tipo_usuario(id)
);

-- NOTA , Si las tablas de instructor y admin son tan cortitas es por que ya comparten la mayoria de informacion tanto admin como instructor como alumno , solo el ID los diferenciara el ID de la tabla tipo perfil
CREATE TABLE  instructor(
    id INT PRIMARY KEY,
    biografia TEXT,
    cuenta_bancaria VARCHAR(50),
    FOREIGN KEY (id) REFERENCES Usuario(id)
);

-- RECUERDA solo los admins pueden dar de alta categorias
CREATE TABLE admin (
    id INT PRIMARY KEY,
    FOREIGN KEY (id) REFERENCES Usuario(id)
);


CREATE TABLE categoria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT
);


-- -------------------------------------------------------------------------

-- Prototipo de consulta multitabla en la cual se vera los usuarios con su respectivo nivel jerarquico en el sistema
-- nota 2: podria usarse como view para la revision final 
SELECT u.id, u.nombre_usuario, u.nombre, u.apellidos, u.email, tu.descripcion AS tipo_usuario
FROM usuario u
JOIN tipo_usuario tu ON u.tipo_usuario = tu.id;

-- --------------------------------------------------------------------------


-- Cursos creados por maestros
CREATE TABLE curso (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2),
    contenido TEXT,
    id_maestro INT,
    id_categoria INT,
    FOREIGN KEY (id_maestro) REFERENCES Maestro(id),
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

-- STORE PROCEDURE


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
    IN p_telefono BIGINT
)
BEGIN
    -- Verifica si el email ya existe
    IF EXISTS (SELECT 1 FROM usuario WHERE email = p_email) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'El email ya está en uso';
    ELSE
        -- Si no existe, inserta el nuevo usuario
        INSERT INTO usuario (nombre_usuario, nombre, apellidos, email, contrasena, tipo_usuario, foto, fecha_nacimiento, telefono)
        VALUES (p_nombre_usuario, p_nombre, p_apellidos, p_email, p_contrasena, p_tipo_usuario, p_foto, p_fecha_nacimiento, p_telefono);
    END IF;
END$$

DELIMITER ;

--SP para editar usuarios
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
    IN p_telefono BIGINT
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
END$$

DELIMITER ;

-- SP visualizar usuarios , podria usarse para ver perfil o para una visualizacion en los reportes de algun administrados
DELIMITER $$
	CREATE PROCEDURE sp_consultar_usuario(
    IN p_id INT
)
BEGIN
    SELECT *
    FROM usuario
    WHERE id = p_id;
END$$

DELIMITER ;


--SP para eliminar un usuario , OJO, esto no es un desactivado es una ELIMINACION TOTAL, falta crear el SP para desactivar un usuario pero que siga existiendo en la BD
DELIMITER $$
	CREATE PROCEDURE sp_eliminar_usuario(
    IN p_id INT
)
BEGIN
    DELETE FROM usuario
    WHERE id = p_id;
END$$

DELIMITER ;

-- PD FINAL : LOS COMENTARIOS SI LOS HICE YO , NO USE CHATGPT XD BORRA ESTO BETOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOO