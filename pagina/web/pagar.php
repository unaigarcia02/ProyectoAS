<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Portatiles SoloG</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        header {
            background-color: #333;
            color: #fff;
            padding: 10px 0;
            text-align: center;
        }
        nav {
            background-color: #444;
            color: #fff;
            text-align: center;
            padding: 10px 0;
        }
        nav a {
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
        }
        nav a:hover {
            background-color: #555;
        }
        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
        }
        .main {
            float: left;
            width: 70%;
            background: #fff;
            padding: 20px;
            box-sizing: border-box;
        }
        .sidebar {
            float: right;
            width: 30%;
            background: #f4f4f4;
            padding: 20px;
            box-sizing: border-box;
        }
        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 10px 0;
            position: absolute;
            bottom: 0;
            width: 100%;
        }
        /* Estilos para los botones */
        .button-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .button-container button {
            padding: 10px 20px;
            background-color: #555;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-right: 5px; /* Reducimos el espacio entre los botones */
        }

        .button-container button a {
            text-decoration: none;
            color: #fff;
        }

        .button-container button:last-child {
            margin-right: 0; /* Eliminamos el margen derecho del último botón */
        }

        .button-container button:hover {
            background-color: #333;
        }
 .product {
            display: flex;
            background-color: #fff;
            margin: 20px 0;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            align-items: center; /* Centrar verticalmente */
        }
        .product img {
            width: 200px;
            height: auto;
            margin-right: 20px;
        }
        .product-info {
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }
        .details-column {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            margin-right: 20px;
        }
        .price-cart {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
        }
        .styled-button {
    padding: 8px 16px;
    background-color: #555;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    margin-right: 5px; /* Ajusta el margen derecho si es necesario */
}

.styled-button:hover {
    background-color: #333;
}
.modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.7); /* Fondo oscuro con transparencia */
        }

        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
                .container {
            display: flex;
            justify-content: space-between;
        }
        .main {
            flex: 70%; /* 70% del ancho para la sección del carrito */
        }
        .side-form {
            flex: 30%; /* 30% del ancho para el formulario */
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-left: 20px;
        }
        .side-form h2 {
            color: #333;
        }

        .side-form form {
            display: flex;
            flex-direction: column;
        }

        .side-form form label,
        .side-form form p {
            margin-bottom: 5px;
            font-weight: bold;
        }

        .side-form form textarea {
            min-height: 100px;
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            resize: vertical;
        }

        .side-form form input[type="radio"] {
            margin-right: 5px;
        }

        .side-form form input[type="submit"] {
            background-color: #0056b3;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .side-form form input[type="submit"]:hover {
            background-color: #003d82;
        }

        footer {
            text-align: center;
            margin-top: 20px;
            padding: 20px 0;
            background: #333;
            color: white;
        }
    </style>
</head>
<body>
    <header>
        <h1>Portatiles SoloG</h1>
    </header>

    <nav>
        <!-- Navegación (puedes copiarla del otro archivo) -->
        <a href="index.php">Inicio</a>
        <a href="catalogo.php">Productos</a>
        <a href="contacto.php">Contacto</a>
    </nav>

    <div class="container">
        <div class="main">
            <h2>Proceso de Pago</h2>
<?php
if (isset($_COOKIE['nombre_usuario'])) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    $servername = "db";
    $username = "juegosacceso";
    $password = "admin";
    $dbname = "juegos";
    
    // Crear conexión
    $conn = pg_connect("host=$servername dbname=$dbname user=$username password=$password");
    
    // Verificar la conexión
    if (!$conn) {
        die("Error de conexión: " . pg_last_error());
    }
    
    // Obtener el id del usuario
    $nombre = $_COOKIE['nombre_usuario'] ?? '';

    if ($nombre) {
        // Consulta SQL para obtener el id del usuario
        $sql2 = "SELECT id FROM usuario WHERE nombre = $1";
        $result = pg_query_params($conn, $sql2, array($nombre));

        if ($result && pg_num_rows($result) > 0) {
            $rowProductos = pg_fetch_assoc($result);
            $idUsuario = $rowProductos['id'];

            // Asegúrate de que el idUsuario no esté vacío
            if (!empty($idUsuario)) {
                // Consulta SQL para obtener los productos del carrito
                $sql = "SELECT * FROM Carrito WHERE usuario_id = $1";
                $resultCarrito = pg_query_params($conn, $sql, array($idUsuario));

                // Verificar si hay resultados y mostrarlos
                if ($resultCarrito && pg_num_rows($resultCarrito) > 0) {
                    $total = 0; // Inicializar total

                    while ($rowCarrito = pg_fetch_assoc($resultCarrito)) {
                        $idProducto = $rowCarrito['producto_id'];
                        $cantidad = $rowCarrito['cantidad'];

                        // Consulta para obtener detalles del producto
                        $sqlProductos = "SELECT * FROM Productos WHERE id = $1";
                        $resultProductos = pg_query_params($conn, $sqlProductos, array($idProducto));

                        if ($resultProductos && pg_num_rows($resultProductos) > 0) {
                            while ($rowProductos = pg_fetch_assoc($resultProductos)) {
                                $fabricante = $rowProductos['fabricante'];
                                $nombreProducto = $rowProductos['modelo']; // Cambiar 'nombre' a 'modelo'
                                $tipo = $rowProductos['tipo'];
                                $tamaño = $rowProductos['tamaño'];
                                $ram = $rowProductos['ram'];
                                $precio = $rowProductos['precio'];
                                $precioFin = $precio * $cantidad;

                                // Mostrar la información del producto
                                echo "<div class='product'>";
                                echo "<div class='product-info'>";
                                echo "<h2><a>$fabricante $nombreProducto </a></h2>";
                                echo "<div class='details-column'>";
                                echo "<p>Tipo: $tipo</p>"; 
                                echo "<p>Tamaño: $tamaño</p>";
                                echo "<p>RAM: $ram</p>";
                                echo "</div>";
                                echo "</div>";
                                echo "<div class='price-cart'>";
                                echo "<p>Cantidad: $cantidad</p>";
                                echo "<p>Precio Total: $precioFin €</p>";
                                echo "</div>";
                                echo "</div>";  

                                $total += $precioFin; // Sumar al total
                            }
                        } else {
                            echo "Error al obtener los detalles del producto: " . pg_last_error($conn);
                        }
                    }
                    echo "<p>Total a pagar: $total €</p>";
                    echo "<form action='pagao.php' method='GET'>";
                    echo "<input type='submit' value='Pagar' style='width: 100%; padding: 8px 16px; background-color: #555; color: #fff; border: none; border-radius: 5px; cursor: pointer; transition: background-color 0.3s ease;'>";
                    echo "</form>";
                } else {
                    echo "No hay productos en tu carrito.";
                }
            }
        } else {
            echo "Error al obtener el ID del usuario: " . pg_last_error($conn);
        }
    } else {
        echo "Inicia sesión para ver tu carro.";
    }

    // Cerrar conexión
    pg_close($conn);
}
?>

            <!-- Aquí puedes agregar el contenido del carrito y el total -->
    

            <!-- Formulario de pago (ejemplo básico) -->

        </div>
        <div class="side-form">
            <h2>Información de Envío y Pago</h2>
            <form action="procesar_pago.php" method="post">
                <label for="direccion">Dirección de Envío:</label>
                <textarea id="direccion" name="direccion" required></textarea>

                <p>Método de Pago:</p>
                <input type="radio" id="paypal" name="metodo_pago" value="PayPal" required>
                <label for="paypal">PayPal</label><br>
                <input type="radio" id="tarjeta" name="metodo_pago" value="TarjetaCredito">
                <label for="tarjeta">Tarjeta de Crédito</label><br>


            </form>
        </div>
    </div>
    </div>

</body>
</html>
