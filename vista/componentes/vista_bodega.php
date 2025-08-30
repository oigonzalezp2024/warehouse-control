<?php
include_once '../../modelo/conexion.php';
$conn = conexion();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>arreglos</title>
</head>
<div class="row"><br><br><br><br>
    <button class="btn btn-success" style="float:right"
        data-toggle="modal"
        data-target="#modalFiltro">Descargar
    </button>
    <button class="btn btn-success"
        data-toggle="modal"
        data-target="#modalFiltro">Fitros
    </button>
    <div>
        <center>
            <h2>Bodega</h2>
        </center>
        <button class="btn navbar-left"
            data-toggle="modal"
            data-target="#modalNuevo">
            <span class="glyphicon glyphicon-plus"></span>
        </button>
    </div>
    <div class="table-responsive">
        <table class="table table-hover table-condensed">
            <thead>
                <tr>
                    <th></th>
                    <th></th>
                    <th style="background-color: #cfffd3c9;">id_item</th>
                    <th style="background-color: #cfffd3c9;">Fotografía</th>
                    <th style="background-color: #cfffd3c9;">proveedor</th>
                    <th style="background-color: #cfffd3c9;">servicio</th>
                    <th style="background-color: #cfffd3c9;">producto</th>
                    <th style="background-color: #cfffd3c9;">precio</th>
                    <th style="background-color: #cfffd3c9;">cantidad</th>
                    <th style="background-color: #cfffd3c9;">fecha creación</th>
                    <th style="background-color: #cfffd3c9;">fecha modificación</th>
                    <th style="background-color: #cfffd3c9;">usuario</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = 'SELECT
cli.id_proveedor cli_id_proveedor,
cli.nombre cli_cliente_nombre,
cli.usuario_id cli_usuario_id,

ser.id_servicio ser_id_servicio,
ser.servicio_nombre ser_servicio_nombre,
ser.usuario_id ser_usuario_id,

pro.id_producto pro_id_producto,
pro.producto_nombre pro_producto_nombre,
pro.fecha_creacion pro_fecha_creacion,
pro.fecha_modificacion pro_fecha_modificacion,
pro.usuario_id pro_usuario_id,

usu.id_usuario usu_id_usuario,
usu.nombre usu_nombre,
usu.celular usu_celular,

b.id_item id_item,
b.proveedor_id proveedor_id,
b.servicio_id servicio_id,
b.producto_id producto_id,
b.precio precio,
b.cantidad cantidad,
b.fecha_creacion fecha_creacion,
b.fecha_modificacion fecha_modificacion,
b.usuario_id usuario_id,
b.ruta_foto ruta_foto
FROM `bodega` b
JOIN proveedores cli ON cli.id_proveedor = b.proveedor_id
JOIN servicios ser ON ser.id_servicio = b.servicio_id
JOIN productos pro ON pro.id_producto = b.producto_id
JOIN usuarios usu ON usu.id_usuario = b.usuario_id
ORDER BY b.id_item DESC';
                $result = mysqli_query($conn, $sql);
                while ($fila = mysqli_fetch_assoc($result)) {
                    $datos = $fila['id_item'] . "||" .
                        $fila['proveedor_id'] . "||" .
                        $fila['servicio_id'] . "||" .
                        $fila['producto_id'] . "||" .
                        $fila['cantidad'] . "||" .
                        $fila['fecha_creacion'] . "||" .
                        $fila['fecha_modificacion'] . "||" .
                        $fila['precio'] . "||" .
                        $fila['usuario_id'];
                ?>
                    <tr>
                        <td>
                            <button class="btn glyphicon glyphicon-plus"
                                data-toggle="modal"
                                data-target="#modalAdicion"
                                onclick="agregaformAdicion('<?php echo $datos; ?>')">
                            </button>
                        </td>
                        <td>
                            <button class="btn glyphicon glyphicon-minus"
                                data-toggle="modal"
                                data-target="#modalSustraccion"
                                onclick="agregaformSustraccion('<?php echo $datos; ?>')">
                            </button>
                        </td>
                        <td style="background-color: #7aef8d;"><?php echo $fila['id_item']; ?></td>
                        <td style="background-color: #7aef8d;">
                            <img src="<?php echo $fila['ruta_foto']; ?>"
                                style="width: 100px; cursor: pointer;"
                                onclick="mostrarFoto('<?php echo $fila['ruta_foto']; ?>')"
                                data-toggle="modal"
                                data-target="#modalFotoDetalle">
                        </td>
                        <td style="background-color: #7aef8d;"><?php echo $fila['cli_cliente_nombre']; ?></td>
                        <td style="background-color: #7aef8d;"><?php echo $fila['ser_servicio_nombre']; ?></td>
                        <td style="background-color: #7aef8d;"><?php echo $fila['pro_producto_nombre']; ?></td>
                        <td style="background-color: #7aef8d;"><?php echo $fila['precio']; ?></td>
                        <td style="background-color: #7aef8d;"><?php echo $fila['cantidad']; ?></td>
                        <td style="background-color: #7aef8d;"><?php echo $fila['fecha_creacion']; ?></td>
                        <td style="background-color: #7aef8d;"><?php echo $fila['fecha_modificacion']; ?></td>
                        <td style="background-color: #7aef8d;"><?php echo $fila['usu_nombre']; ?></td>
                        <td style="text-align:right;">
                            <button class="btn glyphicon glyphicon-pencil"
                                data-toggle="modal"
                                data-target="#modalEdicion"
                                onclick="agregaform('<?php echo $datos; ?>')">
                            </button>
                        </td>
                        <td>
                            <button class="btn glyphicon glyphicon-remove"
                                onclick="preguntarSiNo('<?php echo $fila['id_item']; ?>')">
                            </button>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
        <div>
        </div>
        </body>

</html>
<?php
mysqli_close($conn);
?>