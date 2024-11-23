<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f4f4f4; /* Color gris de fondo */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            position: relative; /* Posición relativa para elementos absolutos */
        }
        body::before {
            content: "";
            background-image: url('imgs/aspiradora.png');
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: -1;
            opacity: 0.2; /* Opacidad de las aspiradoras */
            pointer-events: none; /* No afectar eventos del ratón */
        }
        form {
            width: 300px;
            background: #fff;
            padding: 30px;
            box-sizing: border-box;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="submit"],
        input[type="date"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 5px;
            display: block;
        }
        input[type="submit"] {
            background-color: #555;
            color: #fff;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #333;
        }
    </style>
</head>
<body>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Configuración de la conexión a la base de datos
        $servername = "db";
        $username = "juegosacceso";
        $password = "admin";
        $dbname = "juegos";

        // Crear conexión
        $conn = pg_connect("host=$servername dbname=$dbname user=$username password=$password");

        // Verificar la conexión
        if (!$conn) {
            die("Conexión fallida: " . pg_last_error());
        }

        // Obtener los datos del formulario
        $nombre = $_POST['nombre'];
        $dni = $_POST['dni'];
        $fechaNacimiento = $_POST['fecha_nacimiento'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Consulta SQL para insertar los datos en la tabla usuario
        $sql = "INSERT INTO usuario (nombre, dni, fecha_nacimiento, email, password)
                VALUES ($1, $2, $3, $4, $5)";

        // Preparar la consulta
        $result = pg_prepare($conn, "my_query", $sql);

        // Ejecutar la consulta con los parámetros
        $result = pg_execute($conn, "my_query", array($nombre, $dni, $fechaNacimiento, $email, $password));

        if ($result) {
            // Cerrar conexión
            pg_close($conn);
            ?>
            <div class="message">
                <h2>Usuario Registrado</h2>
                <p>¡El registro se ha realizado con éxito!</p>
                <a href="index.php">Volver a la página de inicio</a>
            </div>
            <?php
            exit(); // Finalizar el script después de la redirección
        } else {
            echo "Error: " . pg_last_error($conn);
        }

        // Cerrar conexión
        pg_close($conn);
    }
    ?>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <h2>Crear tu cuenta</h2>
        <label for="nombre">Nombre y Apellido:</label>
        <input type="text" id="nombre" name="nombre" required>
        
        <label for="dni">DNI:</label>
        <input type="text" id="dni" name="dni" required>

        <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
        <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required>

        <label for="confirm_password">Repetir Contraseña:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
        
        <button type="submit">Registrarse</button>
    </form>

    <script src="script/comprobardatos.js"></script>
</body>
</html>

