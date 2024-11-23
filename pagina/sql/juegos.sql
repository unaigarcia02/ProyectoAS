DROP TABLE IF EXISTS "usuario";
DROP TABLE IF EXISTS "reviews";
DROP TABLE IF EXISTS "Carrito";


CREATE TABLE usuario (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    dni VARCHAR(10) NOT NULL UNIQUE,
    fecha_nacimiento DATE NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(100) NOT NULL
);


CREATE TABLE Productos (
    id SERIAL PRIMARY KEY,
    fabricante VARCHAR(100) NOT NULL,
    modelo VARCHAR(100) NOT NULL,
    tipo VARCHAR(100) NOT NULL,
    tamaño VARCHAR(100) NOT NULL,
    ram VARCHAR(100) NOT NULL,
    precio DECIMAL(10, 2) NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Campo de fecha y hora
);

CREATE TABLE reviews (
    id SERIAL PRIMARY KEY,
    usuario VARCHAR(100),
    producto INT NOT NULL,
    comentario TEXT
);


CREATE TABLE Carrito (
    id SERIAL PRIMARY KEY,
    usuario_id INT NOT NULL,
    producto_id INT NOT NULL,
    cantidad INT NOT NULL DEFAULT 1,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Campo de fecha y hora
    FOREIGN KEY (producto_id) REFERENCES Productos(id),
    UNIQUE (usuario_id, producto_id)
    
);

CREATE TABLE Temas (
    id SERIAL PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    contenido TEXT NOT NULL,
    autor VARCHAR(255) NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Respuestas (
    id SERIAL PRIMARY KEY,
    tema_id INT NOT NULL,
    contenido TEXT NOT NULL,
    autor VARCHAR(255) NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (tema_id) REFERENCES Temas(id)
);

INSERT INTO usuario (nombre, dni, fecha_nacimiento, email, password) 
VALUES ( 'hola', '123456789X', '2024-11-13', 'kaka@gmail.com', '1234');

INSERT INTO Productos (fabricante, modelo, tipo, tamaño, ram, precio)
VALUES
  ('Apple', 'MacBook Pro', 'Ultrabook', '13.3', '8', 1339.69),
  ('Apple', 'Macbook Air', 'Ultrabook', '13.3', '8', 898.94),
  ('HP', '250 G6', 'Notebook', '15.6', '8', 575.00),
  ('Apple', 'MacBook Pro', 'Ultrabook', '15.4', '16', 2537.45),
  ('Apple', 'MacBook Pro', 'Ultrabook', '13.3', '8', 1803.60),
  ('Acer', 'Aspire 3', 'Notebook', '15.6', '4', 400.00),
  ('Apple', 'MacBook Pro', 'Ultrabook', '15.4', '16', 2139.97),
  ('Apple', 'Macbook Air', 'Ultrabook', '13.3', '8', 1158.70),
  ('Asus', 'ZenBook UX430UN', 'Ultrabook', '14', '16', 1495.00),
  ('Acer', 'Swift 3', 'Ultrabook', '14', '8', 770.00);



