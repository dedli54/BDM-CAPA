CREATE DATABASE BDM;

USE BDM;

--Recuerda que manejaremos el usuario el profesor y el admin 
--en la misma tabla solamente crearemos una tabla perfiles que haremos un join para saber el estatus 

CREATE TABLE Admin (
    AdminID INT PRIMARY KEY AUTO_INCREMENT,
    Email VARCHAR(255) NOT NULL,
    Contraseña VARCHAR(255) NOT NULL,
    Foto VARCHAR(255) --No se como poner foto jaja xd
);

CREATE TABLE Categoría (
    CategoriaID INT PRIMARY KEY AUTO_INCREMENT,
    Nombre VARCHAR(255) NOT NULL,
    Actividad VARCHAR(255),
    AdminID INT,
    FOREIGN KEY (AdminID) REFERENCES Admin(AdminID)
);


CREATE TABLE Alumno (
    AlumnoID INT PRIMARY KEY AUTO_INCREMENT,
    Email VARCHAR(255) NOT NULL,
    Contraseña VARCHAR(255) NOT NULL,
    Foto VARCHAR(255), -- no se jaja
    Fecha_de_nacimiento DATE,
    --Metodo de pago?
    Fecha_de_Creacion DATE,
    Fecha_de_Actualizacion DATE,
    Estatus VARCHAR(255)
);

CREATE TABLE Maestro (
    MaestroID INT PRIMARY KEY AUTO_INCREMENT,
    Biografía TEXT,
    Email VARCHAR(255) NOT NULL,
    CursoID INT,
    Foto VARCHAR(255),
    Contraseña VARCHAR(255) NOT NULL,
    PayPal VARCHAR(255) NOT NULL,
    CursoID INT,
    FOREIGN KEY (CursoID) REFERENCES Curso(CursoID)
);

 -- no estoy seguro de este
CREATE TABLE Curso (
    CursoID INT PRIMARY KEY AUTO_INCREMENT,
    MaestroID INT,
    CategoriaID INT,
    Niveles VARCHAR(255) NOT NULL,
    Precio DECIMAL(10, 2),
    Calidad INT,
    Contenido TEXT,
    ComentarioID INT,
    FOREIGN KEY (MaestroID) REFERENCES Maestro(MaestroID),
    FOREIGN KEY (CategoriaID) REFERENCES Categoría(CategoriaID)
    FOREIGN KEY (ComentarioID) REFERENCES Comentario(ComentarioID)
);

CREATE TABLE Transacción (
    TransaccionID INT PRIMARY KEY AUTO_INCREMENT,
    AlumnoID INT,
    CursoID INT,
    Monto VARCHAR(255) NOT NULL,
    FOREIGN KEY (AlumnoID) REFERENCES Alumno(AlumnoID),
    FOREIGN KEY (CursoID) REFERENCES Curso(CursoID)
);

CREATE TABLE Comentario (
    ComentarioID INT PRIMARY KEY AUTO_INCREMENT,
    AlumnoID INT,
    Texto TEXT,
    CursoID INT,
    FOREIGN KEY (AlumnoID) REFERENCES Alumno(AlumnoID),
    FOREIGN KEY (CursoID) REFERENCES Curso(CursoID)
);

CREATE TABLE Kardex(
    KardexID INT PRIMARY KEY AUTO_INCREMENT,
    AlumnoID INT,
    CursoID INT,
    Aprobado -- Binario no me acuerdo como se llama en sql
    Califiaccion VARCHAR(255),
    FOREIGN KEY (AlumnoID) REFERENCES Alumno(AlumnoID),
    FOREIGN KEY (CursoID) REFERENCES Curso(CursoID)

)

CREATE TABLE Datos_Bancarios (
Datos_BancariosID INT PRIMARY KEY AUTO_INCREMENT
--EMAIL?
NumTarjeta VARCHAR(255) NOT NULL,
)

-- creo que ya pero tengo dudas jsjsjsj de todos modos falta poner el orden correcto de la creacion de las tablas