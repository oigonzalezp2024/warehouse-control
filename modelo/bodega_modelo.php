<?php
include 'conexion.php';

date_default_timezone_set('America/Bogota');
$fecha_actual = date('Y-m-d H:i:s');
/*
session_start();
if (!(isset($_SESSION['es_admin']) && $_SESSION['es_admin'] == true)) {
    header("location: https://google.com/");
    exit(); // Detener la ejecución después de la redirección
}
*/
$usuario_id = 39; //$_SESSION['usuario_id'];

$conn = conexion();
// Asegúrate de que $pdo esté disponible, si no, inicialízalo
try {
    $pdo = new PDO("mysql:host=localhost;dbname=empresa", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
    exit();
}

function bodegaCantidad($pdo, $id_item)
{
    $sql = 'SELECT `cantidad` 
    FROM bodega 
    WHERE id_item = :id_item';
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":id_item", $id_item);
    $stmt->execute();
    $rows = $stmt->fetch(PDO::FETCH_ASSOC);
    return $rows['cantidad'];
}

/**
 * Valida que un valor sea un número entero mayor que cero.
 * Si la validación es exitosa, devuelve true.
 * Si la validación falla, lanza una excepción.
 *
 * @param mixed $valor El valor a validar.
 * @return bool
 * @throws Exception
 */
function validarEnteroPositivo($valor)
{
    try {
        $opciones = ['options' => ['min_range' => 1]];
        if (filter_var($valor, FILTER_VALIDATE_INT, $opciones) === false) {
            throw new Exception("El valor '$valor' no es un número entero mayor que cero.");
        }
        return true;
    } catch (Exception $e) {
        throw $e;
    }
}

$accion = $_GET['accion'];

if ($accion == "insertar") {

    $proveedor_id = $_POST['proveedor_id'];
    $servicio_id = $_POST['servicio_id'];
    $producto_id = $_POST['producto_id'];
    $precio = $_POST['precio'];
    $cantidad = (int) $_POST['cantidad'];

    // --- Inicia el manejo de la subida de la fotografía ---
    $ruta_foto = null;
    if (isset($_FILES['fotografia']) && $_FILES['fotografia']['error'] === UPLOAD_ERR_OK) {
        $foto_temp = $_FILES['fotografia']['tmp_name'];
        $nombre_original = basename($_FILES['fotografia']['name']);
        $extension = pathinfo($nombre_original, PATHINFO_EXTENSION);
        
        // **Esta es la línea que debes corregir:**
        // Cambia 'uploads/' por '../uploads/'
        $directorio_destino = '../uploads/'; 
        
        $nombre_archivo_final = uniqid() . '.' . $extension;
        $ruta_destino = $directorio_destino . $nombre_archivo_final;

        if (move_uploaded_file($foto_temp, $ruta_destino)) {
            $ruta_foto = $ruta_destino;
        } else {
            echo "Error al mover el archivo subido.";
            exit();
        }
    }
    // --- Termina el manejo de la subida de la fotografía ---

    if (validarEnteroPositivo($cantidad)) {
        try {
            $pdo->beginTransaction();

            $sql_bodega = "INSERT INTO bodega(
                proveedor_id, servicio_id, producto_id, cantidad, fecha_creacion, usuario_id, ruta_foto, precio
                ) VALUE(
                :proveedor_id, :servicio_id, :producto_id, :cantidad, :fecha_actual, :usuario_id, :ruta_foto, :precio)";

            $stmt_bodega = $pdo->prepare($sql_bodega);
            $stmt_bodega->bindParam(':proveedor_id', $proveedor_id);
            $stmt_bodega->bindParam(':servicio_id', $servicio_id);
            $stmt_bodega->bindParam(':producto_id', $producto_id);
            $stmt_bodega->bindParam(':cantidad', $cantidad);
            $stmt_bodega->bindParam(':fecha_actual', $fecha_actual);
            $stmt_bodega->bindParam(':usuario_id', $usuario_id);
            $stmt_bodega->bindParam(':ruta_foto', $ruta_foto);
            $stmt_bodega->bindParam(':precio', $precio);
            $stmt_bodega->execute();
            $id = $pdo->lastInsertId();

            $sql_historial = "INSERT INTO bodega_h(
                proveedor_id, accion, servicio_id, producto_id, cantidad, fecha_creacion, usuario_id
                ) VALUE(
                :proveedor_id, 'ingreso', :servicio_id, :producto_id, :cantidad, :fecha_actual, :usuario_id)";

            $stmt_historial = $pdo->prepare($sql_historial);
            $stmt_historial->bindParam(':proveedor_id', $proveedor_id);
            $stmt_historial->bindParam(':servicio_id', $servicio_id);
            $stmt_historial->bindParam(':producto_id', $producto_id);
            $stmt_historial->bindParam(':cantidad', $cantidad);
            $stmt_historial->bindParam(':fecha_actual', $fecha_actual);
            $stmt_historial->bindParam(':usuario_id', $usuario_id);
            $stmt_historial->execute();

            $pdo->commit();
            echo $id;

        } catch (PDOException $e) {
            $pdo->rollBack();
            echo "Error de base de datos: " . $e->getMessage();
        }
    } else {
        echo "error: validarEnteroPositivo";
    }
} elseif ($accion == "modificar") {

    $id = $_POST['id_item'];
    $proveedor_id = $_POST['proveedor_id'];
    $servicio_id = $_POST['servicio_id'];
    $producto_id = $_POST['producto_id'];
    $cantidad = $_POST['cantidad'];
    $fecha_creacion = $_POST['fecha_creacion'];
    $fecha_modificacion = $_POST['fecha_modificacion'];
    //$usuario_id = $_POST['usuario_id'];

    $sql = "UPDATE bodega SET
          proveedor_id = '$proveedor_id', 
          servicio_id = '$servicio_id', 
          producto_id = '$producto_id', 
          cantidad = '$cantidad',
          fecha_modificacion = '$fecha_actual', 
          usuario_id = '$usuario_id'
          WHERE id_item = '$id'";

    $consulta = mysqli_query($conn, $sql);
    if ($consulta = true) {
        $sql = "INSERT INTO bodega_h(
            proveedor_id, accion, servicio_id, producto_id, cantidad, fecha_creacion, usuario_id
            )VALUE(
            '$proveedor_id', 'modificación', '$servicio_id', '$producto_id', '$cantidad', '$fecha_actual', '$usuario_id')";

        $consulta = mysqli_query($conn, $sql);
        if ($consulta = true) {
            echo $id;
        }
    }

} elseif ($accion == "adicionar") {

    $id = (int) $_POST['id_item'];
    $cantidad = (bodegaCantidad($pdo, $id)) + ($_POST['cantidad']);
    //$usuario_id = $_POST['usuario_id'];

    $sql = "UPDATE bodega SET
          cantidad = '$cantidad',
          usuario_id = '$usuario_id'
          WHERE id_item = '$id'";

    $consulta = mysqli_query($conn, $sql);
    if ($consulta = true) {
        $sql = "SELECT `id_item`, 
            `proveedor_id`, 
            `servicio_id`, 
            `producto_id`, 
            `cantidad`, 
            `fecha_creacion`, 
            `fecha_modificacion`, 
            `usuario_id` 
            FROM `bodega`
            WHERE `id_item` = $id";
        $result = mysqli_query($conn, $sql);
        while ($fila = mysqli_fetch_assoc($result)) {

            $proveedor_id = $fila['proveedor_id'];
            $servicio_id = $fila['servicio_id'];
            $producto_id = $fila['producto_id'];
            $cantidad = $_POST['cantidad'];
            //$usuario_id = $fila['usuario_id'];

            $sql = "INSERT INTO bodega_h(
                proveedor_id, accion, servicio_id, producto_id, cantidad, fecha_creacion, usuario_id
                )VALUE(
                '$proveedor_id', 'adición', '$servicio_id', '$producto_id', '$cantidad', '$fecha_actual', '$usuario_id')";
    
            $consulta = mysqli_query($conn, $sql);
            if ($consulta = true) {
                echo $id;
            }
        }
    }

} elseif ($accion == "sustraer") {

    $id = $_POST['id_item'];
    $cantidad = (bodegaCantidad($pdo, $id)) - ($_POST['cantidad']);
    //$usuario_id = $_POST['usuario_id'];

    $sql = "UPDATE bodega SET
          cantidad = '$cantidad',
          usuario_id = '$usuario_id'
          WHERE id_item = '$id'";

    $consulta = mysqli_query($conn, $sql);
    if ($consulta = true) {
        $sql = "SELECT `id_item`, 
            `proveedor_id`, 
            `servicio_id`, 
            `producto_id`, 
            `cantidad`, 
            `fecha_creacion`, 
            `fecha_modificacion`, 
            `usuario_id` 
            FROM `bodega`
            WHERE `id_item` = $id";
        $result = mysqli_query($conn, $sql);
        while ($fila = mysqli_fetch_assoc($result)) {

            $proveedor_id = $fila['proveedor_id'];
            $servicio_id = $fila['servicio_id'];
            $producto_id = $fila['producto_id'];
            $cantidad = $_POST['cantidad'];
            //$usuario_id = $fila['usuario_id'];

            $sql = "INSERT INTO bodega_h(
                proveedor_id, accion, servicio_id, producto_id, cantidad, fecha_creacion, usuario_id
                )VALUE(
                '$proveedor_id', 'sustracción', '$servicio_id', '$producto_id', '$cantidad', '$fecha_actual', '$usuario_id')";
    
            $consulta = mysqli_query($conn, $sql);
            if ($consulta = true) {
                echo $id;
            }
        }
    }

} elseif ($accion == "borrar") {

    $id = $_POST['id_item'];

    $sql = "SELECT `id_item`, 
        `proveedor_id`, 
        `servicio_id`, 
        `producto_id`, 
        `cantidad`, 
        `fecha_creacion`, 
        `fecha_modificacion`, 
        `usuario_id` 
        FROM `bodega`
        WHERE `id_item` = $id";
    $result = mysqli_query($conn, $sql);
    while ($fila = mysqli_fetch_assoc($result)) {

        $proveedor_id = $fila['proveedor_id'];
        $servicio_id = $fila['servicio_id'];
        $producto_id = $fila['producto_id'];
        $cantidad = $fila['cantidad'];
        //$usuario_id = $fila['usuario_id'];
    }

    $sql = "DELETE FROM bodega
            WHERE id_item = '$id'";

    $consulta = mysqli_query($conn, $sql);
    if ($consulta = true) {
        $sql = "INSERT INTO bodega_h(
            proveedor_id, accion, servicio_id, producto_id, cantidad, fecha_creacion, usuario_id
            )VALUE(
            '$proveedor_id', 'eliminación', '$servicio_id', '$producto_id', '$cantidad', '$fecha_actual', '$usuario_id')";

        $consulta = mysqli_query($conn, $sql);
        if ($consulta = true) {
            echo $id;
        }
    }
}
