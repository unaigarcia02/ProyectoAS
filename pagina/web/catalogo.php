<?php
// Iniciar o reanudar la sesión
// Iniciar o reanudar la sesión

session_start();

// Si se hace clic en "Cerrar sesión"
if(isset($_POST['cerrar_sesion'])) {
    // Eliminar todas las variables de sesión
    session_unset();

    // Destruir la sesión
    session_destroy();

    // Eliminar la cookie 'nombre_usuario'
    setcookie('nombre_usuario', '', time() - 3600, '/');

    // Redirigir a la página de inicio o a donde desees después de cerrar sesión
    header("Location: index.php");
    exit();
}
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

// Manejo del formulario de compra
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['producto_id'])) {
    $producto_id = $_POST['producto_id'];
    $nombre = $_COOKIE['nombre_usuario'] ?? '';

    if ($nombre) {
        // Obtener el id del usuario
        $sql2 = "SELECT id FROM usuario WHERE nombre = $1";
        $result = pg_query_params($conn, $sql2, array($nombre));

        if ($result && pg_num_rows($result) > 0) {
            $rowProductos = pg_fetch_assoc($result);
            $idUsuario = $rowProductos['id'];

            // Insertar en la tabla Carrito
            $sqlInsert = "INSERT INTO Carrito (usuario_id, producto_id, cantidad) VALUES ($1, $2, $3)
                          ON CONFLICT (usuario_id, producto_id) DO UPDATE 
                          SET cantidad = Carrito.cantidad + EXCLUDED.cantidad";
            $resultInsert = pg_query_params($conn, $sqlInsert, array($idUsuario, $producto_id, 1));

            if ($resultInsert) {
            } else {
                echo "Error al agregar el producto: " . pg_last_error($conn);
            }
        } else {
            echo "Error al encontrar el usuario: " . pg_last_error($conn);
        }
    } else {
        echo "Por favor, inicia sesión para comprar.";
    }
}

// Cerrar conexión
pg_close($conn);


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Catálogo de Productos</title>
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
            transition: background-color 0.3s ease;
        }
        nav a:hover {
            background-color: #555;
        }
                .full-width-container {
            /* Estilos para el contenedor de ancho completo */
            width: 100%;
            background-color: #f0f0f0;
            padding: 20px 0;
        }
        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
        }
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
        .filter-container {
            text-align: center;
            margin-bottom: 20px;
        }

        /* Estilos para el formulario de selección */
        .filter-form {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
        }

        .filter-form label {
            margin-right: 10px;
            font-size: 18px;
            color: #fff;
        }

        .filter-form select {
            padding: 8px 12px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .filter-form input[type="submit"] {
            padding: 8px 16px;
            background-color: #555;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .filter-form input[type="submit"]:hover {
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
    </style>
</head>
<body>
    <header>
        <h1>Catálogo de Productos</h1>
        <div class="button-container">
            <?php
            // Verificación de autenticación
            if (isset($_COOKIE['nombre_usuario'])) {
                // Mostrar el nombre del usuario si está autenticado
                echo '<span style="color: #fff; margin-right: 20px;">Bienvenido, ' . $_COOKIE['nombre_usuario'] . '</span>';
                echo '<form method="post" action="'.$_SERVER['PHP_SELF'].'" style="display: inline;"><button type="submit" name="cerrar_sesion">Cerrar Sesión</button></form>';
            } else {
                // Mostrar botones de registro e inicio de sesión si no está autenticado
                echo '<button><a href="registro.php" style="text-decoration: none; color: #fff;">Registro</a></button>';
                echo '<button><a href="inicio.php" style="text-decoration: none; color: #fff;">Iniciar Sesión</a></button>';
            }
            ?>
            

        </div>
    </header>
    <div class="cart-container" style="display: none;">
    <!-- Contenido del carrito va aquí -->
    <!-- Por ejemplo: -->
    <p>Producto 1 - $10</p>
    <p>Producto 2 - $20</p>
</div>

 <div id="cartModal" class="modal">
        <div class="modal-content product-container">

            <span class="close">&times;</span>
            <p>
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
                            }
                        } else {
                            echo "Error al obtener detalles del producto: " . pg_last_error($conn);
                        }
                    }
                    echo "<form action='pagar.php' method='GET'>";
                    echo "<input type='submit' value='Pagar' style='width: 100%; padding: 8px 16px; background-color: #555; color: #fff; border: none; border-radius: 5px; cursor: pointer; transition: background-color 0.3s ease;'>";
                    echo "</form>";
                } else {
                    echo "No hay productos en el carrito.";
                }
            } else {
                echo "ID de usuario no válido.";
            }
        } else {
            echo "Error al encontrar el usuario: " . pg_last_error($conn);
        }
    } else {
        echo "Por favor, inicia sesión para ver tu carrito.";
    }

    // Cerrar conexión
    pg_close($conn);
} else {
    echo "Inicia sesión para ver tu carrito.";
}

?>

            </p>
        </div>
    </div>

    <nav>
        <a href="index.php">Inicio</a>
        <a href="catalogo.php">Productos</a>
        <a href="contacto.php">Contacto</a>
<?php 
        if (isset($_COOKIE['nombre_usuario'])) {
        echo '<a href="#" id="cartIcon" style="margin-left: auto;">';
	echo '<img src="imgs/carrito.png" alt="Carrito de compras" style="width: 30px; height: 30px;">';
	echo '</a>';
}

?>
</nav>
<?php
    
$servername = "db";
$username = "juegosacceso";
$password = "admin";
$dbname = "juegos";
    
    // Crear conexión
$conn = pg_connect("host=$servername dbname=$dbname user=$username password=$password");
    
    
// Leer el archivo línea por línea
$laptopData = [];
$jsonFile = '/app/dataset/laptop_prices.json';

if (file_exists($jsonFile)) {
    $lines = file($jsonFile);
    foreach ($lines as $line) {
        $laptop = json_decode(trim($line), true);
        if (json_last_error() === JSON_ERROR_NONE) {
            $laptopData[] = $laptop;
        } else {
            echo "Error al decodificar línea: " . json_last_error_msg();
        }
    }
}

// Limitar la salida a solo 5 laptops
$maxItems = 10;
$count = 0;

foreach ($laptopData as $laptop) {
    if ($count >= $maxItems) {
        break; // Salir del bucle si se han mostrado los 5 elementos
    }
$sqlCheck = "SELECT COUNT(*) FROM Productos 
             WHERE modelo = $1 
             AND tipo = $2
             AND precio = $3";
    $resultCheck = pg_query_params($conn, $sqlCheck, array($laptop['Product'], $laptop['TypeName'], $laptop['Price_euros']));


    if ($resultCheck && pg_fetch_result($resultCheck, 0) == 0) {
        // Si no existe, procede a insertar
        $sqlInsert = "INSERT INTO Productos (fabricante, modelo, tipo, tamaño, ram, precio) VALUES ($1, $2, $3, $4, $5, $6)";
        $resultInsert = pg_query_params($conn, $sqlInsert, array(
            $laptop['Company'], 
            $laptop['Product'], 
            $laptop['TypeName'], 
            $laptop['Inches'], 
            $laptop['Ram'], 
            $laptop['Price_euros']
        ));
}

    // Obtener el ID del producto insertado usando el modelo
    $sqlSelect = "SELECT id FROM Productos WHERE modelo = $1 AND tipo = $2
             AND precio = $3";
    $resultSelect = pg_query_params($conn, $sqlSelect, array($laptop['Product'], $laptop['TypeName'], $laptop['Price_euros']));

    if ($resultSelect) {
        // Verificar si se obtuvo al menos un resultado
        if ($row = pg_fetch_assoc($resultSelect)) {
            $producto_id = $row['id'];
        } else {
            echo "No se encontró el producto después de la inserción.";
        }
    } else {
        echo "Error al obtener el ID del producto: " . pg_last_error($conn);
    }


            echo "<div class='product'>";
    echo "Fabricante: " . $laptop['Company'] . "<br>";
    echo "Modelo: " . $laptop['Product'] . "<br>";
    echo "Tipo: " . $laptop['TypeName'] . "<br>";
    echo "Tamaño de pantalla: " . $laptop['Inches'] . "<br>";
    echo "RAM: " . $laptop['Ram'] . " GB<br>";
    echo "Precio: " . $laptop['Price_euros'] . " €<br>";
    echo "<hr>";
            echo '<form method="POST" action="">'; // La acción se envía al mismo script
        echo '<input type="hidden" name="producto_id" value="' . $producto_id . '">';
        echo '<input type="submit" value="Comprar">';
        echo '</form>';

        echo "</div>";
    $count++; // Incrementar el contador
}


?>
        
  
<script>
    // Obtener el elemento del modal y el botón de cierre
    var cartModal = document.getElementById('cartModal');
    var closeBtn = document.querySelector('.close');

    // Función para abrir el modal
    function openModal() {
        cartModal.style.display = 'block';
    }

    // Función para cerrar el modal
    function closeModal() {
        cartModal.style.display = 'none';
    }

    // Agregar eventos para abrir y cerrar el modal
    document.getElementById('cartIcon').addEventListener('click', openModal);
    closeBtn.addEventListener('click', closeModal);
    window.onclick = function(event) {
        if (event.target == cartModal) {
            closeModal();
        }
    };
</script>
</body>
</html>

