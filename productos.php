<?php session_start();
include ('sistema/configuracion.php');
$usuario->LoginCuentaConsulta();
$usuario->VerificacionCuenta();
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<title><?php echo TITULO ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<link rel="shortcut icon" href="<?php echo ESTATICO ?>img/favicon.ico">
	<link rel="stylesheet" type="text/css" href="<?php echo ESTATICO ?>css/dataTables.bootstrap.css">
	<?php include(MODULO.'Tema.CSS.php');?>
</head>
<body>
	<?php
	// Menu inicio
	if($usuarioApp['id_perfil']==2){
		include (MODULO.'menu_vendedor.php');
	}elseif($usuarioApp['id_perfil']==1){
		include (MODULO.'menu_admin.php');
	}else{
		echo'<meta http-equiv="refresh" content="0;url='.URLBASE.'cerrar-sesion"/>';
	}
	//Menu Fin
	?>
	<div id="wrap">
		<div class="container">

			<div class="page-header" id="banner">
				<div class="row">
					<div class="col-lg-8 col-md-7 col-sm-6">
						<h1>Productos <a href="<?php echo URLBASE ?>nuevo-producto" class="btn btn-primary"><i class="fa fa-plus"></i> Agregar Nuevo Productos</a></h1>
					</div>
				</div>
			</div>
			<?php
			$ProductosClase->ActualizarInventario();
			$netoSql= $db->SQL("SELECT SUM(totalprecio) AS deudatotal FROM cajatmp WHERE vendedor='{$usuarioApp['id']}'");
			$neto	= $netoSql->fetch_array();
			$TipoDeCambioPincipalSql= $db->SQL("SELECT * FROM `moneda` WHERE rango='1'");
			$TipoDeCambioPincipal	= $TipoDeCambioPincipalSql->fetch_assoc();

			$TipoDeCambioSecundarioSql= $db->SQL("SELECT * FROM `moneda` WHERE rango='2'");
			$TipoDeCambioSecundario	= $TipoDeCambioSecundarioSql->fetch_assoc();
			?>
			<div class="table-responsive" id="productos-container" style="display: none;">
				<table class="table table-bordered" id="productos">
					<thead>
						<tr>
							<th>C&oacute;digo</th>
							<th>Nombre del Producto</th>
							<th>Precio de Costo</th>
							<th>Precio de Venta</th>
							<th>Precio de Costo</th>
							<th>Precio de Venta</th>
							<th>Existencia</th>
							<th>Exist Min</th>
							<th>Categoria</th>
							<th>Proveedores</th>
							<th>opciones</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($ProductosStockArray as $ProductosStockRow):?>
						<tr>
							<td data-title="Code"><?php echo $ProductosStockRow['codigo']; ?></td>
							<td><?php echo $ProductosStockRow['nombre']; ?></td>

							<td data-title="Price" class="numeric"><?php echo $TipoDeCambioPincipal['signo'].' '.$ProductosStockRow['preciocosto']; ?></td>

							<td data-title="Price" class="numeric"><?php echo $TipoDeCambioPincipal['signo'].' '.$ProductosStockRow['precioventa']; ?></td>

							<td data-title="Price" class="numeric"><?php echo $TipoDeCambioSecundario['signo'].' '.$Vendedor->FormatoSaldo($ProductosStockRow['preciocosto']*$TipoDeCambioSecundario['valor']); ?></td>

							<td data-title="Price" class="numeric"><?php echo $TipoDeCambioSecundario['signo'].' '.$Vendedor->FormatoSaldo($ProductosStockRow['precioventa']*$TipoDeCambioSecundario['valor']); ?></td>

							<td><?php echo $ProductosStockRow['stock']; ?></td>
							<td><?php echo $ProductosStockRow['stockMin']; ?></td>
							<td>
								<?php
								$ProveedorSql	= $db->SQL("SELECT nombre FROM `departamento` WHERE id='{$ProductosStockRow['departamento']}'");
								$Proveedor		= $ProveedorSql->fetch_array();
								echo $Proveedor['nombre'];
								?>
							</td>
							<td>
								<?php
								$ProveedorSql	= $db->SQL("SELECT nombre FROM `proveedor` WHERE id='{$ProductosStockRow['proveedor']}'");
								$Proveedor		= $ProveedorSql->fetch_array();
								echo $Proveedor['nombre'];
								?>
							</td>
							<td>
							<button class="btn btn-danger btn-xs" onclick="eliminarProducto(<?php echo $ProductosStockRow['id']; ?>, '<?php echo $ProductosStockRow['nombre']; ?>')"><i class="fa fa-trash"></i></button>
							<a href="<?php echo URLBASE ?>editar-producto/<?php echo $ProductosStockRow['id']; ?>/<?php echo $enlace->LimpiaCadenaTexto($ProductosStockRow['nombre']); ?>/" class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o"></i></a>
							<button class="btn btn-success btn-xs"  type="button" data-toggle="modal" data-target="#AgrearProducto<?php echo $ProductosStockRow['id']; ?>"><i class="fa fa-plus"></i></button>
							</td>
						</tr>
						<!-- Modal -->
						<div class="modal fade" id="AgrearProducto<?php echo $ProductosStockRow['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
						  <div class="modal-dialog" role="document">
							<div class="modal-content">
							  <div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title" id="myModalLabel">Actualizar inventario (<?php echo $ProductosStockRow['nombre']; ?>)</h4>
							  </div>
							  <div class="modal-body">
								<form class="form-horizontal" method="post">
									<input type="hidden" name="IdProducto" value="<?php echo $ProductosStockRow['id']; ?>"/>
									<div class="form-group">
										<div class="col-lg-12">
											<input type="number" min="1" step="1" maxlength="6" class="form-control" placeholder="Cantidad a ingresar al inventario" name="CantidadProducto" onkeypress="return PermitirSoloNumeros(event);" autocomplete="off"  required />
										</div>
									</div>
									<hr/>
									<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
									<button type="submit" name="ActualizarInventario" class="btn btn-primary">Ingresar al Inventario</button>
								</form>
							  </div>
							</div>
						  </div>
						</div>
						<!-- Modal Fin-->
						<?php endforeach; ?>
					</tbody>
				</table>
				<!-- Modal Eliminar-->
				<div class="modal fade" id="EliminarProducto" tabindex="-1" role="dialog" aria-hidden="true">
				  <div class="modal-dialog">
					<div class="modal-content">
					  <div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="myModalLabesl">Eliminar producto del sistema</h4>
					  </div>
					  <div class="modal-body">
						<form method="post" action="" class="form-horizontal" >
							<input type="hidden" name="IdProducto" value="">
							<input type="hidden" name="nombre" value="">
							<div class="form-group">
								<div class="input-group">
									¿Est&aacute; seguro que desea eliminar el producto &quot;<span id="nombreProducto">&nbsp;</span>&quot;&#63;
								</div>
							</div>
							<div class="form-group">
							   <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
								<button type="submit" name="EliminarProducto" class="btn btn-primary">Si, Eliminar</button>
							</div>
						</form>
					  </div>
					</div>
				  </div>
				</div>
				<!-- Modal Eliminar Fin -->
			</div>
		</div>
    </div>
	<?php include (MODULO.'footer.php'); ?>
	<!-- Cargado archivos javascript al final para que la pagina cargue mas rapido -->
	<?php include(MODULO.'Tema.JS.php');?>
	<script type="text/javascript" language="javascript" src="<?php echo ESTATICO ?>js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" language="javascript" src="<?php echo ESTATICO ?>js/dataTables.bootstrap.js"></script>
	<script type="text/javascript" charset="utf-8">
	//Tablas Diseño
	$(document).ready(function() {

		$(window).load(function() {
		  	$("#productos-container").show( "fast", function() {
		  		$('#productos').dataTable({
					"scrollY": false,
					"scrollX": true,
					"order": [[ 1, "asc" ]]
				});
		  	});
		  	
		});
	});
	// Permitir Solo numeros en los input
	function PermitirSoloNumeros(e)
	{
		var keynum = window.event ? window.event.keyCode : e.which;
		if ((keynum == 8) || (keynum == 46))
		return true;

		return /\d/.test(String.fromCharCode(keynum));
	}

    function eliminarProducto(id, nombre) {
    	$('#EliminarProducto #nombreProducto').html(nombre);
    	$("#EliminarProducto input[name='IdProducto']").val(id);
    	$("#EliminarProducto input[name='nombre']").val(nombre);
      	$('#EliminarProducto').modal('show');
    }
	</script>
	<!-- Cargado archivos javascript al final para que la pagina cargue mas rapido Fin -->
</body>
</html>
