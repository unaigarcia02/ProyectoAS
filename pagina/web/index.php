<?php
// Iniciar o reanudar la sesión
session_start();

// Si se hace clic en "Cerrar sesión"
if (isset($_POST['cerrar_sesion'])) {
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
?>

<?php
$servername = "db"; // El nombre del servicio de tu base de datos en Docker
$username = "juegosacceso";
$password = "admin";
$dbname = "juegos";
$cantidad = '';

// Crear conexión
$conn = pg_connect("host=$servername dbname=$dbname user=$username password=$password");

// Verificar la conexión
if (!$conn) {
    die("Error de conexión: " . pg_last_error());
}

// Consulta SQL para obtener la cantidad de elementos en el carrito
$sql = "SELECT COUNT(*) AS total_items FROM Carrito";  
$result = pg_query($conn, $sql);

// Verificar si hay resultados y mostrar la cantidad total de elementos en el carrito
if ($result) {
    $row = pg_fetch_assoc($result);
    $cantidad = $row['total_items'];
} else {
    echo 'No hay productos en el carrito.';
}

// Cerrar conexión
pg_close($conn);
?>

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
	    display: flex;
	    justify-content: center;
	}
	.main {
	    width: 100%;
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
    </style>
</head>
<body>
    <header>
        <h1>Portatiles SoloG</h1>
        <div class="button-container">
            <?php
            if (isset($_COOKIE['nombre_usuario'])) {
                echo '<span style="color: #fff; margin-right: 20px;">Bienvenido, ' . $_COOKIE['nombre_usuario'] . '</span>';
                echo '<form method="post" action="'.$_SERVER['PHP_SELF'].'" style="display: inline;"><button type="submit" name="cerrar_sesion">Cerrar Sesión</button></form>';
            } else {
                echo '<button><a href="registro.php">Registro</a></button>';
                echo '<button><a href="inicio.php">Iniciar Sesión</a></button>';
            }
            ?>
        </div>
        <?php  $jelo = 'jeloo'?>
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

    <div class="container">
        <div class="main">
            <h2>Bienvenido a nuestra tienda de portatiles SoloG</h2>
            <p>Descubre una amplia selección de portatiles de alta calidad para mantener tu hogar limpio y fresco.</p>
            <p>Explora nuestra gama de productos y encuentra el producto perfecto para ti.</p>
        </div>
        
    </div>
    <footer>
        <p>&copy; 2024 SoloG Fixtures™</p>
    </footer>
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

