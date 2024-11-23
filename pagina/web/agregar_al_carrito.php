<?php
$servername = "db";
$username = "juegosacceso";
$password = "admin";
$dbname = "juegos";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Comprobación de método POST y existencia de la cookie de usuario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_COOKIE['nombre_usuario'])) {
    $nombre_usuario = $_COOKIE['nombre_usuario'];
    $producto_id = isset($_POST['producto_id']) ? intval($_POST['producto_id']) : 0; // Asegurando que es un número

    // Consulta para obtener el ID del usuario
    $stmt = $conn->prepare("SELECT id FROM usuario WHERE nombre = ?");
    $stmt->bind_param("s", $nombre_usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    $parametro1 = $_POST['parametro1'];

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $idUsuario = $row['id'];

        // Inserción en la tabla Carrito
        $stmt = $conn->prepare("INSERT INTO Carrito (usuario_id, producto_id, cantidad) VALUES (?, ?, 1) ON DUPLICATE KEY UPDATE cantidad = cantidad + 1");
        $stmt->bind_param("ii", $idUsuario, $parametro1);

        if ($stmt->execute()) {
            echo "Producto agregado al carrito correctamente.";
        } else {
            echo "Error al agregar el producto al carrito: " . $conn->error;
        }
    } else {
        echo "Usuario no encontrado.";
    }
} else {
    echo "Por favor, inicie sesión o seleccione un producto.";
}

// Cerrar conexión
$conn->close();
?>

