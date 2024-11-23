<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configuración de conexión
$host = "db";
$port = "5432"; // Puerto por defecto de PostgreSQL
$dbname = "juegos";
$username = "juegosacceso";
$password = "admin";

// Crear conexión con PostgreSQL
$conn = pg_connect("host=$host port=$port dbname=$dbname user=$username password=$password");

// Definir un mensaje inicial vacío
$message = '';

// Verificar la conexión
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Usar sentencias preparadas para prevenir inyecciones SQL
    $dni = $_POST['dni'];
    $pw = $_POST['pw'];

    // Consulta SQL utilizando una sentencia preparada
    $result = pg_prepare($conn, "my_query", "SELECT nombre FROM usuario WHERE dni=$1 AND password=$2");
    $result = pg_execute($conn, "my_query", array($dni, $pw));

    // Verificar si se encontró un usuario
    if (pg_num_rows($result) == 1) {
        $userData = pg_fetch_assoc($result);
        $nombreDeUsuario = $userData['nombre'];

        // Obtener el nombre de usuario antes de establecer la cookie
        setcookie('nombre_usuario', $nombreDeUsuario, time() + (86400 * 30), '/');

        // Iniciar la sesión y asignar el nombre de usuario
        session_start();
        $_SESSION['nombre_usuario'] = $nombreDeUsuario;
        $message = 'Inicio de sesión exitoso';
    } else {
        // Usuario o contraseña incorrectos
        $message = 'Usuario o contraseña incorrectos';
    }
}

// Cerrar conexión
pg_close($conn);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <style>
        /* Estilos CSS */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            position: relative;
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
        input[type="password"],
        button {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 5px;
            display: block;
        }
        button {
            background-color: #555;
            color: #fff;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #333;
        }
        .message {
            text-align: center;
            padding: 30px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .message h2 {
            color: #333;
            font-size: 28px;
            margin-bottom: 20px;
        }
        .message p {
            color: #555;
            font-size: 18px;
            margin-bottom: 30px;
        }
        .message a {
            text-decoration: none;
            color: #fff;
            padding: 12px 24px;
            background-color: #555;
            border-radius: 5px;
            font-size: 18px;
            transition: background-color 0.3s ease;
        }
        .message a:hover {
            background-color: #333;
        }
    </style>
</head>
<body>
    <?php if ($message === 'Inicio de sesión exitoso'): ?>
        <div class="message">
            <h2>Inicio de Sesión Exitoso</h2>
            <p>Tu inicio de sesión ha sido exitoso.</p>
            <a href="index.php">Volver a la página de inicio</a>
        </div>
    <?php else: ?>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <h2>Iniciar Sesión</h2>
            <label for="dni">DNI:</label>
            <input type="text" id="dni" name="dni" required>
            
            <label for="pw">Contraseña:</label>
            <input type="password" id="pw" name="pw" required>
            
            <button type="submit" id="loginButton">Iniciar Sesión</button>
        </form>
    <?php endif; ?>
</body>
</html>

