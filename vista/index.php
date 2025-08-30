<?php
require_once "../modelo/conexion.php";
/*
session_start();
if (!(isset($_SESSION['es_admin']) && $_SESSION['es_admin'] == true)) {
    header("location: https://google.com");
    exit(); // Detener la ejecución después de la redirección
}
*/
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title>Bodega</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<?php
	include('librerias.php');
	?>
	<script src="../controlador/funciones_bodega.js"></script>
</head>

<body id="body">
	<?php
	include 'header.php';
	?>
	<div class="container">
		<div id="tabla"></div>
	</div>
	<!-- MODAL PARA FILTRADO DE DATOS -->
	<div class="modal fade" id="modalFotoDetalle" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Detalle de la Foto</h4>
                </div>
                <div class="modal-body">
                    <img id="imagenModal" src="" class="img-responsive" style="height: auto;">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
	<!-- MODAL PARA FILTRADO DE DATOS -->
	<div class="modal fade" id="modalFiltro" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog modal-sm" role="document">
			<div class="modal-content">
				<form action="../bodega_informe/excel/descargar.php" method="get">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<h4 class="modal-title" id="myModalLabel">Filtrar datos</h4>
					</div>
					<div class="modal-body">

						<label for="proveedor">Proveedor:</label>
						<input type="text" name="proveedor" class="form-control input-sm">
						<fieldset>
							<label for="servicio">Servicio:</label>
							<input type="text" name="servicio" class="form-control input-sm">
						</fieldset>
						<fieldset>
							<label for="producto">Producto:</label>
							<input type="text" name="producto" class="form-control input-sm">
						</fieldset>
						<fieldset hidden>
							<label for="cantidad">Cantidad:</label>
							<input type="text" name="cantidad" class="form-control input-sm">
						</fieldset>
						<fieldset>
							<label for="fecha_inicio">fecha de creación desde:</label>
							<input type="date" name="fecha_inicio_cre" class="form-control input-sm">
						</fieldset>
						<fieldset>
							<label for="fecha_fin">fecha de creación hasta:</label>
							<input type="date" name="fecha_fin_cre" class="form-control input-sm">
						</fieldset>
						<fieldset>
							<label for="fecha_inicio">fecha de modificacion desde:</label>
							<input type="date" name="fecha_inicio_mod" class="form-control input-sm">
						</fieldset>
						<fieldset>
							<label for="fecha_fin">fecha de modificacion hasta:</label>
							<input type="date" name="fecha_fin_mod" class="form-control input-sm">
						</fieldset>
					</div>
					<div class="modal-footer">
						<input type="submit" class="btn btn-primary">
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- MODAL PARA INSERTAR REGISTROS -->
	<div class="modal fade" id="modalNuevo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog modal-sm" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title" id="myModalLabel">Agregar a bodega</h4>
				</div>
				<form id="uploadForm" action="your_upload_script.php" method="post" enctype="multipart/form-data">
					<div class="modal-body">
						<?php
						$option = 'cliente_nombre';
						$sql = 'SELECT id_proveedor, nombre FROM proveedores ORDER BY id_proveedor DESC;';
						$stmt = $pdo->prepare($sql);
						$stmt->execute();
						$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
						?>
						<label for="proveedor_id">Cliente:</label>
						<select name="proveedor_id" id="proveedor_id" class="form-control input-sm" required="">
							<?php
							foreach ($rows as $row) {
							?>
								<option value="<?php echo $row['id_proveedor']; ?>"><?php echo $row['id_proveedor']; ?> - <?php echo $row['nombre']; ?></option>
							<?php
							}
							?>
						</select>
						<?php
						$option = 'servicio_nombre';
						$sql = "SELECT id_servicio, servicio_nombre, usuario_id 
							FROM servicios
							ORDER BY id_servicio DESC";
						$stmt = $pdo->prepare($sql);
						$stmt->execute();
						$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
						?>
						<label for="servicio_id">Servicio:</label>
						<select name="servicio_id" id="servicio_id" class="form-control input-sm" required="">
							<?php
							foreach ($rows as $row) {
							?>
								<option value="<?php echo $row['id_servicio']; ?>"><?php echo $row['id_servicio']; ?> - <?php echo $row['servicio_nombre']; ?></option>
							<?php
							}
							?>
						</select>
						<?php
						$option = 'producto_nombre';
						$sql = "SELECT id_producto, producto_nombre, bodegas FROM productos;";
						$stmt = $pdo->prepare($sql);
						$stmt->execute();
						$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
						?>
						<label for="producto_id">Producto:</label>
						<select name="producto_id" id="producto_id" class="form-control input-sm" required="">
							<?php
							foreach ($rows as $row) {
							?>
								<option value="<?php echo $row['id_producto']; ?>"><?php echo $row['id_producto']; ?> - <?php echo $row['producto_nombre']; ?></option>
							<?php
							}
							?>
						</select>
						<label>Cantidad:</label>
						<input type="number" id="cantidad" class="form-control input-sm" required="">
						<input hidden="" type="text" id="fecha_creacion">
						<input hidden="" type="text" id="fecha_modificacion">
						<?php
						$option = 'nombre';
						$sql = 'SELECT id_usuario, nombre, celular FROM usuarios ORDER BY id_usuario DESC;';
						$stmt = $pdo->prepare($sql);
						$stmt->execute();
						$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
						?>
						<label for="usuario_id">Usuario:</label>
						<select name="usuario_id" id="usuario_id" class="form-control input-sm" required="">
							<?php
							foreach ($rows as $row) {
							?>
								<option value="<?php echo $row['id_usuario']; ?>"><?php echo $row['id_usuario']; ?> - <?php echo $row['nombre']; ?></option>
							<?php
							}
							?>
						</select>
						<label for="precio">Precio</label>
						<input type="number" id="precio" class="form-control input-sm" required="">
						<label for="foto">Fotografía:</label>
						<input type="file" id="foto" name="foto" class="form-control input-sm" accept=".jpg, .jpeg, .png, .avif, .svg">
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" data-dismiss="modal" id="guardarnuevo">
							Agregar
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- MODAL PARA EDICION DE DATOS-->
	<div class="modal fade" id="modalEdicion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog modal-sm" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title" id="myModalLabel">Actualizar datos</h4>
				</div>
				<div class="modal-body">
					<input type="number" hidden="" id="id_itemu">
					<?php
					$option = 'cliente_nombre';
					$sql = 'SELECT id_proveedor, nombre FROM proveedores ORDER BY id_proveedor DESC;';
					$stmt = $pdo->prepare($sql);
					$stmt->execute();
					$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
					?>
					<label for="proveedor_idu">proveedor_id</label>
					<select name="proveedor_idu" id="proveedor_idu" class="form-control input-sm" required="">
						<?php
						foreach ($rows as $row) {
						?>
							<option value="<?php echo $row['id_proveedor']; ?>"><?php echo $row['id_proveedor']; ?> - <?php echo $row['nombre']; ?></option>
						<?php
						}
						?>
					</select>
					<?php
					$option = 'servicio_nombre';
					$sql = "SELECT id_servicio, servicio_nombre, usuario_id 
                        FROM servicios
                        ORDER BY id_servicio DESC";
					$stmt = $pdo->prepare($sql);
					$stmt->execute();
					$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
					?>
					<label for="servicio_idu">servicio_id</label>
					<select name="servicio_idu" id="servicio_idu" class="form-control input-sm" required="">
						<?php
						foreach ($rows as $row) {
						?>
							<option value="<?php echo $row['id_servicio']; ?>"><?php echo $row['id_servicio']; ?> - <?php echo $row['servicio_nombre']; ?></option>
						<?php
						}
						?>
					</select>
					<?php
					$option = 'producto_nombre';
					$sql = "SELECT id_producto, producto_nombre, bodegas FROM productos";
					$stmt = $pdo->prepare($sql);
					$stmt->execute();
					$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
					?>
					<label for="producto_idu">producto_id</label>
					<select name="producto_idu" id="producto_idu" class="form-control input-sm" required="">
						<?php
						foreach ($rows as $row) {
						?>
							<option value="<?php echo $row['id_producto']; ?>"><?php echo $row['id_producto']; ?> - <?php echo $row['producto_nombre']; ?></option>
						<?php
						}
						?>
					</select>
					<label>cantidad</label>
					<input type="text" id="cantidadu" class="form-control input-sm" required="">
					<label>fecha_creacion</label>
					<input type="text" id="fecha_creacionu" class="form-control input-sm" required="">
					<label>fecha_modificacion</label>
					<input type="text" id="fecha_modificacionu" class="form-control input-sm" required="">
					<?php
					$option = 'nombre';
					$sql = 'SELECT id_usuario, nombre, celular FROM usuarios ORDER BY id_usuario DESC;';
					$stmt = $pdo->prepare($sql);
					$stmt->execute();
					$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
					?>
					<label for="usuario_idu">usuario_id</label>
					<select name="usuario_idu" id="usuario_idu" class="form-control input-sm" disabled required="">
						<?php
						foreach ($rows as $row) {
						?>
							<option value="<?php echo $row['id_usuario']; ?>"><?php echo $row['id_usuario']; ?> - <?php echo $row['nombre']; ?></option>
						<?php
						}
						?>
					</select>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-warning" data-dismiss="modal" id="actualizadatos">
						Actualizar
					</button>
				</div>
			</div>
		</div>
	</div>
	<!-- MODAL PARA ADICION DE DATOS-->
	<div class="modal fade" id="modalAdicion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog modal-sm" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title" id="myModalLabel">Actualizar datos</h4>
				</div>
				<div class="modal-body">
					<input type="number" hidden="" id="id_itema">
					<?php
					$option = 'cliente_nombre';
					$sql = 'SELECT id_proveedor, nombre FROM proveedores ORDER BY id_proveedor DESC;';
					$stmt = $pdo->prepare($sql);
					$stmt->execute();
					$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
					?>
					<label hidden="" for="proveedor_ida">proveedor_id</label>
					<select hidden="" name="proveedor_ida" id="proveedor_ida">
						<?php
						foreach ($rows as $row) {
						?>
							<option value="<?php echo $row['id_proveedor']; ?>"><?php echo $row['id_proveedor']; ?> - <?php echo $row['nombre']; ?></option>
						<?php
						}
						?>
					</select>
					<?php
					$option = 'servicio_nombre';
					$sql = 'SELECT id_servicio, servicio_nombre, usuario_id FROM servicios ORDER BY id_servicio DESC;';
					$stmt = $pdo->prepare($sql);
					$stmt->execute();
					$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
					?>
					<label hidden="" for="servicio_ida">servicio_id</label>
					<select hidden="" name="servicio_ida" id="servicio_ida">
						<?php
						foreach ($rows as $row) {
						?>
							<option value="<?php echo $row['id_servicio']; ?>"><?php echo $row['id_servicio']; ?> - <?php echo $row['servicio_nombre']; ?></option>
						<?php
						}
						?>
					</select>
					<?php
					$option = 'producto_nombre';
					$sql = 'SELECT id_producto, producto_nombre, fecha_creacion FROM productos ORDER BY id_producto DESC;';
					$stmt = $pdo->prepare($sql);
					$stmt->execute();
					$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
					?>
					<label hidden="" for="producto_ida">producto_id</label>
					<select hidden="" name="producto_ida" id="producto_ida">
						<?php
						foreach ($rows as $row) {
						?>
							<option value="<?php echo $row['id_producto']; ?>"><?php echo $row['id_producto']; ?> - <?php echo $row['producto_nombre']; ?></option>
						<?php
						}
						?>
					</select>
					<label>cantidad</label>
					<input type="text" id="cantidada" class="form-control input-sm" required="">
					<label hidden="">fecha_creacion</label>
					<input hidden="" type="text" id="fecha_creaciona">
					<label hidden="">fecha_modificacion</label>
					<input hidden="" type="text" id="fecha_modificaciona">
					<?php
					$option = 'nombre';
					$sql = 'SELECT id_usuario, nombre, celular FROM usuarios ORDER BY id_usuario DESC;';
					$stmt = $pdo->prepare($sql);
					$stmt->execute();
					$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
					?>
					<label hidden="" for="usuario_ida">usuario_id</label>
					<select hidden="" name="usuario_ida" id="usuario_ida">
						<?php
						foreach ($rows as $row) {
						?>
							<option value="<?php echo $row['id_usuario']; ?>"><?php echo $row['id_usuario']; ?> - <?php echo $row['nombre']; ?></option>
						<?php
						}
						?>
					</select>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-warning" data-dismiss="modal" id="adicion">
						Actualizar
					</button>
				</div>
			</div>
		</div>
	</div>
	<!-- MODAL PARA SUSTRACCION DE DATOS-->
	<div class="modal fade" id="modalSustraccion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog modal-sm" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title" id="myModalLabel">Actualizar datos</h4>
				</div>
				<div class="modal-body">
					<input type="number" hidden="" id="id_itemr">
					<?php
					$option = 'cliente_nombre';
					$sql = 'SELECT id_proveedor, nombre FROM proveedores ORDER BY id_proveedor DESC;';
					$stmt = $pdo->prepare($sql);
					$stmt->execute();
					$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
					?>
					<label hidden="" for="proveedor_idr">proveedor_id</label>
					<select hidden="" name="proveedor_idr" id="proveedor_idr">
						<?php
						foreach ($rows as $row) {
						?>
							<option value="<?php echo $row['id_proveedor']; ?>"><?php echo $row['id_proveedor']; ?> - <?php echo $row['nombre']; ?></option>
						<?php
						}
						?>
					</select>
					<?php
					$option = 'servicio_nombre';
					$sql = 'SELECT id_servicio, servicio_nombre, usuario_id FROM servicios ORDER BY id_servicio DESC;';
					$stmt = $pdo->prepare($sql);
					$stmt->execute();
					$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
					?>
					<label hidden="" for="servicio_idr">servicio_id</label>
					<select hidden="" name="servicio_idr" id="servicio_idr">
						<?php
						foreach ($rows as $row) {
						?>
							<option value="<?php echo $row['id_servicio']; ?>"><?php echo $row['id_servicio']; ?> - <?php echo $row['servicio_nombre']; ?></option>
						<?php
						}
						?>
					</select>
					<?php
					$option = 'producto_nombre';
					$sql = 'SELECT id_producto, producto_nombre, fecha_creacion FROM productos ORDER BY id_producto DESC;';
					$stmt = $pdo->prepare($sql);
					$stmt->execute();
					$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
					?>
					<label hidden="" for="producto_idr">producto_id</label>
					<select hidden="" name="producto_idr" id="producto_idr">
						<?php
						foreach ($rows as $row) {
						?>
							<option value="<?php echo $row['id_producto']; ?>"><?php echo $row['id_producto']; ?> - <?php echo $row['producto_nombre']; ?></option>
						<?php
						}
						?>
					</select>
					<label>cantidad</label>
					<input type="text" id="cantidadr" class="form-control input-sm" required="">
					<label hidden="">fecha_creacion</label>
					<input hidden="" type="text" id="fecha_creacionr">
					<label hidden="">fecha_modificacion</label>
					<input hidden="" type="text" id="fecha_modificacionr">
					<?php
					$option = 'nombre';
					$sql = 'SELECT id_usuario, nombre, celular FROM usuarios ORDER BY id_usuario DESC;';
					$stmt = $pdo->prepare($sql);
					$stmt->execute();
					$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
					?>
					<label hidden="" for="usuario_idr">usuario_id</label>
					<select hidden="" name="usuario_idr" id="usuario_idr">
						<?php
						foreach ($rows as $row) {
						?>
							<option value="<?php echo $row['id_usuario']; ?>"><?php echo $row['id_usuario']; ?> - <?php echo $row['nombre']; ?></option>
						<?php
						}
						?>
					</select>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-warning" data-dismiss="modal" id="sustraccion">
						Actualizar
					</button>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$(document).ready(function() {
			$('#tabla').load('componentes/vista_bodega.php');
		});
	</script>
	<script type="text/javascript">
    $(document).ready(function() {
			$('#guardarnuevo').click(function() {
				// Captura los valores de los campos
				let proveedor_id = $('#proveedor_id').val();
				let servicio_id = $('#servicio_id').val();
				let producto_id = $('#producto_id').val();
				let cantidad = $('#cantidad').val();
				let fecha_creacion = $('#fecha_creacion').val();
				let fecha_modificacion = $('#fecha_modificacion').val();
				let usuario_id = $('#usuario_id').val();
				let precio = $('#precio').val();
				// **Paso clave**: Captura el archivo de la imagen
				// .files[0] obtiene el primer archivo del input de tipo file
				let fotografia = $('#foto')[0].files[0];

				// Llama a la función, pasando el archivo de la foto como un argumento
				bodegaAgregarMaterial(proveedor_id, servicio_id, producto_id, cantidad, fecha_creacion, fecha_modificacion, usuario_id, fotografia, precio);
			});
			$('#actualizadatos').click(function() {
				bodegaModificarMaterial();
			});
			$('#adicion').click(function() {
				bodegaAdicionarMaterial();
			});
			$('#sustraccion').click(function() {
				bodegaSustraer();
			});
		});
	</script>
	<?php
	include './footer.php';
	?>
</body>

</html>