<?php session_start();
include ('sistema/configuracion.php');
$usuario->LoginCuentaConsulta();
$usuario->VerificacionCuenta();
$fechaActual = FechaActual();
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<title>Cajas | <?php echo TITULO ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<link rel="shortcut icon" href="<?php echo ESTATICO ?>img/favicon.ico">
	<link rel="stylesheet" type="text/css" href="<?php echo ESTATICO ?>css/dataTables.bootstrap.css">
	<link rel="stylesheet" type="text/css" href="<?php echo ESTATICO ?>css/calendario/bootstrap-datepicker3.css">
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
						<h1>Caja del Sistema</h1>
					</div>
				</div>
			</div>
			<div class="row">
				<?php
				$sistema->AperturaCaja();
				$sistema->CierreCaja();
				$sistema->CajaChica();

				$TipoDeCambioPincipalSql= $db->SQL("SELECT * FROM `moneda` WHERE rango='1'");
				$TipoDeCambioPincipal	= $TipoDeCambioPincipalSql->fetch_assoc();

				$TipoDeCambioSecundarioSql= $db->SQL("SELECT * FROM `moneda` WHERE rango='2'");
				$TipoDeCambioSecundario	= $TipoDeCambioSecundarioSql->fetch_assoc();

				function tofloat($num) {
				    $dotPos = strrpos($num, '.');
				    $commaPos = strrpos($num, ',');
				    $sep = (($dotPos > $commaPos) && $dotPos) ? $dotPos :
				        ((($commaPos > $dotPos) && $commaPos) ? $commaPos : false);
				  
				    if (!$sep) {
				        return floatval(preg_replace("/[^0-9]/", "", $num));
				    }

				    return floatval(
				        preg_replace("/[^0-9]/", "", substr($num, 0, $sep)) . '.' .
				        preg_replace("/[^0-9]/", "", substr($num, $sep+1, strlen($num)))
				    );
				}
				?>
				<div class=" col-md-8">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#Caja" data-toggle="tab">Caja</a></li>
						<li><a href="#AperturaRegistro" data-toggle="tab">Apertura de Caja</a></li>
						<li><a href="#CierreRegistro" data-toggle="tab">Cierre de Caja</a></li>
					</ul>
					<div id="myTabContent" class="tab-content">
						<div class="tab-pane fade active in" id="Caja">
							<table class="table table-bordered" id="CajaGeneral">
								<thead>
									<tr>
										<td><strong>#</strong></td>
										<td><strong>Monto <?php echo $TipoDeCambioPincipal['signo'];?></strong></td>
										<td><strong>Monto <?php echo $TipoDeCambioSecundario['signo'];?></strong></td>
										<td><strong><center>FechaRegistro</center></strong></td>
										<td><strong><center>Editar</center></strong></td>
									</tr>
								</thead>
								<tbody>
									<?php foreach($CajaArray as $CajaRow): ?>
									<tr>
										<th><?php echo $CajaRow['id']; ?></th>
										<td data-title="Price" class="numeric"><?php echo $TipoDeCambioPincipal['signo'].' '.$Vendedor->Formato(toFloat($CajaRow['monto'])*$TipoDeCambioPincipal['valor']); ?></td>
										<td data-title="Price" class="numeric"><?php echo $TipoDeCambioSecundario['signo'].' '.$Vendedor->Formato(toFloat($CajaRow['monto'])*$TipoDeCambioSecundario['valor']); ?></td>

										<td><center><?php echo $CajaRow['fecha'].' '.$CajaRow['hora']; ?></center></td>
										<td><button class="btn btn-primary btn-xs">Registro</button></td>
									</tr>
									<?php endforeach?>
								</tbody>
							</table>
						</div>
						<div class="tab-pane fade" id="AperturaRegistro">
							<div class="table-responsive">
								<table class="table table-bordered table table-hover" id="AperturaRegistroTabla">
									<thead>
										<tr>
											<th>#</th>
											<th>Monto <?php echo $TipoDeCambioPincipal['signo'];?></th>
											<th>Monto <?php echo $TipoDeCambioSecundario['signo'];?></th>
											<th>FechaRegistro</th>
											<th>HoraRegistro</th>
											<th>Estado</th>
											<th>Opci&oacute;n</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach($CajaAperturaRegistroArray as $CajaAperturaRegistroRow): ?>
										<tr>
											<th><?php echo $CajaAperturaRegistroRow['id']; ?></th>
											<td data-title="Price" class="numeric">$ <?php echo $CajaAperturaRegistroRow['monto']; ?></td>
											<td data-title="Price" class="numeric"><?php echo $TipoDeCambioSecundario['signo'].' '.$Vendedor->Formato(toFloat($CajaAperturaRegistroRow['monto'])*$TipoDeCambioSecundario['valor']); ?></td>
											<td><?php echo $CajaAperturaRegistroRow['fecha']; ?></td>
											<td><?php echo $CajaAperturaRegistroRow['hora']; ?></td>
											<td>
											<?php
											if($CajaAperturaRegistroRow['tipo'] == 1){
												echo'<span class="label label-success">Activo</span>';
											}else{
												echo'<span class="label label-danger">Desactivado</span>';
											}
											?>
											</td>
											<td><button class="btn btn-primary btn-xs">Editar</button></td>
										</tr>
										<?php endforeach?>
									</tbody>
								</table>
							</div>
						</div>
						<div class="tab-pane fade" id="CierreRegistro">
							<div class="table-responsive">
								<table class="table table-bordered table table-hover" id="CierreRegistroTabla">
									<thead>
										<tr>
											<th>#</th>
											<th>Monto <?php echo $TipoDeCambioPincipal['signo'];?></th>
											<th>Monto <?php echo $TipoDeCambioSecundario['signo'];?></th>
											<th>FechaRegistro</th>
											<th>HoraRegistro</th>
											<th>Estado</th>
											<th>Opci&oacute;n</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach($CajaCierreRegistroArray as $CajaCierreRegistroRow): ?>
										<tr>
											<th><?php echo $CajaCierreRegistroRow['id']; ?></th>
											<td data-title="Price" class="numeric">$ <?php echo $TipoDeCambioPincipal['signo'].' '.$CajaCierreRegistroRow['monto']; ?></td>
											<td data-title="Price" class="numeric"><?php echo $TipoDeCambioSecundario['signo'].' '.$Vendedor->Formato(toFloat($CajaCierreRegistroRow['monto'])*$TipoDeCambioSecundario['valor']); ?></td>
											<td><?php echo $CajaCierreRegistroRow['fecha']; ?></td>
											<td><?php echo $CajaCierreRegistroRow['hora']; ?></td>
											<td>
											<?php
											if($CajaCierreRegistroRow['habilitado'] == 1){
												echo'<span class="label label-success">Activo</span>';
											}else{
												echo'<span class="label label-danger">Desactivado</span>';
											}
											?>
											</td>
											<td><button class="btn btn-primary btn-xs">Editar</button></td>
										</tr>
										<?php endforeach?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<?php
					$TipoOperacionQuery	= $db->SQL("SELECT tipo  FROM `cajaregistros` WHERE habilitado='1' ORDER BY id DESC LIMIT 1");
					$TipoOperacion		= $TipoOperacionQuery->fetch_array();
					?>
					<table class="table table-bordered">
						<tbody>
							<tr class="well">
								<td><center>
									<strong>Caja Principal |
										<?php
											if(is_null($TipoOperacion) || $TipoOperacion['tipo'] == 2): echo'Apertura de Caja'; else: echo'Cierre de Caja'; endif;
										?>
									</strong>
								</center></td>
							</tr>
							<tr>
								<td>
									<form method="post" action="" class="form-horizontal">
										<label>Tipo de Operaci&oacute;n</label>
										<div class="form-group">
											<?php if(is_null($TipoOperacion) || $TipoOperacion['tipo'] == 2): ?>
											<div class="col-md-6">
												<div class="radio">
													<label>
														<input type="radio" name="tipo" id="SorteoMedioDia" value="1" checked />
														Apertura de Caja
													</label>
												</div>
											</div>
											<div class="col-md-6">
												<div class="radio">
													<label>
														<input type="radio" name="tipo" id="SorteoNocturno" value="2" disabled />
														Cierre de Caja
													</label>
												</div>
											</div>
											<?php else:?>
											<div class="col-md-6">
												<div class="radio">
													<label>
														<input type="radio" name="tipo" id="SorteoMedioDia" value="1" disabled />
														Apertura de Caja
													</label>
												</div>
											</div>
											<div class="col-md-6">
												<div class="radio">
													<label>
														<input type="radio" name="tipo" id="SorteoNocturno" value="2" checked />
														Cierre de Caja
													</label>
												</div>
											</div>
											<?php endif;?>
										</div>
										<hr/>
										<div class="form-group">
											<label class="control-label" for="fecha">Fecha:</label>
											<div class="input-daterange input-group" id="fecha">
												<input type="text" class="input-md form-control" name="fecha" value="<?php echo FechaActual(); ?>" required /><div class="input-group-addon"><span class="glyphicon glyphicon-th" aria-hidden="true"></span></div>
											</div>
										</div>
										<hr/>
										<?php if(is_null($TipoOperacion) || $TipoOperacion['tipo'] == 2): ?>
										<div class="form-group">
											<label>Monto</label>
											<div class="input-group">
												<span class="input-group-addon">$</span>
												<input type="text" class="form-control" name="monto" required="" onkeypress="return PermitirSoloNumeros(event);"  placeholder="Monto de la operaci&oacute;n" autocomplete="off" required/>
											</div>
										</div>
										<hr/>
										<?php
										else:
										endif
										?>
										<div class="form-group">
											<?php if(is_null($TipoOperacion) || $TipoOperacion['tipo'] == 2): ?>
												<button type="submit" name="AperturaCaja" class="btn btn-primary">Realizar Apertura de Caja</button>
											<?php else:?>
												<button type="submit" name="CierreCaja" class="btn btn-primary">Realizar Cierre de Caja</button>
											<?php endif;?>
											<button type="reset" class="btn btn-default">Cancelar</button>
										</div>
									</form>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<hr/>
			<div class="row">
				<legend>Tipos de Pagos</legend>
				<div class="col-md-9">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#PagosTarjeta" data-toggle="tab">Pagos con Tarjeta</a></li>
						<li><a href="#PagosEfectivo" data-toggle="tab">Pagos con efectivo</a></li>
					</ul>
					<div id="myTabContent" class="tab-content">
						<div class="tab-pane fade active in" id="PagosTarjeta">
							<div class="table-responsive">
								<table class="table table-bordered table-hover" id="PagoTarjeta">
									<thead>
										<tr>
											<th>#</th>
											<th>Monto <?php echo $TipoDeCambioPincipal['signo'];?></th>
											<th>Monto <?php echo $TipoDeCambioSecundario['signo'];?></th>
											<th>FechaRegistro</th>
											<th>HoraRegistro</th>
											<th>Estado</th>
											<th>Opci&oacute;n</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach($CajaCierreRegistroTarjetaArray as $CajaCierreRegistroTarjetaRow): ?>
										<tr>
											<th><?php echo $CajaCierreRegistroTarjetaRow['id']; ?></th>
											<td data-title="Price" class="numeric"><?php echo $TipoDeCambioPincipal['signo'].' '.$CajaCierreRegistroTarjetaRow['total']; ?></td>
											<td data-title="Price" class="numeric"><?php echo $TipoDeCambioSecundario['signo'].' '.$Vendedor->Formato(toFloat($CajaCierreRegistroTarjetaRow['total'])*$TipoDeCambioSecundario['valor']); ?></td>
											<td><?php echo $CajaCierreRegistroTarjetaRow['fecha']; ?></td>
											<td><?php echo $CajaCierreRegistroTarjetaRow['hora']; ?></td>
											<td>
											<?php
											if($CajaCierreRegistroTarjetaRow['habilitado'] == 1){
												echo'<span class="label label-success">Activo</span>';
											}else{
												echo'<span class="label label-danger">Desactivado</span>';
											}
											?>
											</td>
											<td><button class="btn btn-primary btn-xs">Editar</button></td>
										</tr>
										<?php endforeach?>
									</tbody>
								</table>
							</div>
						</div>
						<div class="tab-pane fade" id="PagosEfectivo">
							<div class="table-responsive">
								<table class="table table-bordered table-hover" id="PagoEfectivo">
									<thead>
										<tr>
											<th>#</th>
											<th>Monto <?php echo $TipoDeCambioPincipal['signo'];?></th>
											<th>Monto <?php echo $TipoDeCambioSecundario['signo'];?></th>
											<th>FechaRegistro</th>
											<th>HoraRegistro</th>
											<th>Estado</th>
											<th>Opci&oacute;n</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach($CajaCierreRegistroEfectivoArray as $CajaCierreRegistroEfectivoRow): ?>
										<tr>
											<th><?php echo $CajaCierreRegistroEfectivoRow['id']; ?></th>
											<td data-title="Price" class="numeric"><?php echo $TipoDeCambioPincipal['signo'].' '.$CajaCierreRegistroEfectivoRow['total']; ?></td>
											<td data-title="Price" class="numeric"><?php echo $TipoDeCambioSecundario['signo'].' '.$Vendedor->Formato(toFloat($CajaCierreRegistroEfectivoRow['total'])*$TipoDeCambioSecundario['valor']); ?></td>
											<td><?php echo $CajaCierreRegistroEfectivoRow['fecha']; ?></td>
											<td><?php echo $CajaCierreRegistroEfectivoRow['hora']; ?></td>
											<td>
											<?php
											if($CajaCierreRegistroEfectivoRow['habilitado'] == 1){
												echo'<span class="label label-success">Activo</span>';
											}else{
												echo'<span class="label label-danger">Desactivado</span>';
											}
											?>
											</td>
											<td><button class="btn btn-primary btn-xs">Editar</button></td>
										</tr>
										<?php endforeach?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="panel panel-primary">
						<?php
						$ResumenVentaDiaTotalSql = $db->SQL("SELECT SUM(total) AS total FROM `factura` WHERE fecha='{$fechaActual}' AND habilitado='1'");
						$ResumenVentaDiaTotal = $ResumenVentaDiaTotalSql->fetch_array();
						
						$ResumenVentaDiaEfectivoSql = $db->SQL("SELECT SUM(total) AS total FROM `factura` WHERE tipo='1' AND fecha='{$fechaActual}' AND habilitado='1'");
						$ResumenVentaDiaEfectivo = $ResumenVentaDiaEfectivoSql->fetch_array();
						
						$ResumenVentaDiaTarjetaSql = $db->SQL("SELECT SUM(total) AS total FROM `factura` WHERE  tipo='0' AND fecha='{$fechaActual}' AND habilitado='1'");
						$ResumenVentaDiaTarjeta = $ResumenVentaDiaTarjetaSql->fetch_array();
						?>	
						<div class="panel-heading">
							<h3 class="panel-title">Resumen De Venta <?php echo @$TipoDeCambioPincipal['signo']; ?> </h3>
						</div>
						<div class="panel-body">
							Venta Total: <?php echo @$TipoDeCambioPincipal['signo'].$ResumenVentaDiaTotal['total']; ?><br/>
							Venta Efetivo: <?php echo @$TipoDeCambioPincipal['signo'].$ResumenVentaDiaEfectivo['total']; ?><br/>
							Venta Tarjeta: <?php echo @$TipoDeCambioPincipal['signo'].$ResumenVentaDiaTarjeta['total']; ?><br/>
						</div>
					</div>

					<div class="panel panel-primary">
						<div class="panel-heading">
							<h3 class="panel-title">Resumen De Venta - <?php echo @$TipoDeCambioSecundario['signo']; ?></h3>
						</div>
						<div class="panel-body">
							Venta Total: <?php echo @$TipoDeCambioSecundario['signo'].$Vendedor->FormatoSaldo($ResumenVentaDiaTotal['total']*$TipoDeCambioSecundario['valor']); ?><br/>
							Venta Efetivo: <?php echo @$TipoDeCambioSecundario['signo'].$Vendedor->FormatoSaldo($ResumenVentaDiaEfectivo['total']*$TipoDeCambioSecundario['valor']); ?><br/>
							Venta Tarjeta: <?php echo @$TipoDeCambioSecundario['signo'].$Vendedor->FormatoSaldo($ResumenVentaDiaTarjeta['total']*$TipoDeCambioSecundario['valor']); ?><br/>
						</div>
					</div>
				</div>
			</div>
			<hr/>
			<div class="row">
			<legend>Caja Chica</legend>
				<div class=" col-md-8">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#CajaChicaGeneral" data-toggle="tab">Caja</a></li>
						<li><a href="#CajaChicaRegistroEntradaDinero" data-toggle="tab">Entrada de Dinero a Caja Chica</a></li>
						<li><a href="#CajaChicaRegistroSalidaDinero" data-toggle="tab">Salida de Dinero de la Cierre de Caja</a></li>
					</ul>
					<div id="myTabContent" class="tab-content">
						<div class="tab-pane fade active in" id="CajaChicaGeneral">
							<table class="table table-bordered" id="CajaChicaGeneralTabla">
								<thead>
									<tr>
										<td><strong>#</strong></td>
										<th><strong>Monto <?php echo $TipoDeCambioPincipal['signo'];?></strong></th>
										<th><strong>Monto <?php echo $TipoDeCambioSecundario['signo'];?></strong></th>
										<td><strong><center>FechaRegistro</center></strong></td>
										<td><strong><center>Editar</center></strong></td>
									</tr>
								</thead>
								<tbody>
									<?php foreach($CajaChicaArray as $CajaChicaRow): ?>
									<tr>
										<th><?php echo $CajaChicaRow['id']; ?></th>
										<td><?php echo $TipoDeCambioPincipal['signo'].$CajaChicaRow['monto']; ?></td>
										<td><?php echo $TipoDeCambioSecundario['signo'].$CajaChicaRow['monto']; ?></td>
										<td><center><?php echo $CajaChicaRow['fecha'].' '.$CajaChicaRow['hora']; ?></center></td>
										<td><button class="btn btn-primary btn-xs">Registro</button></td>
									</tr>
									<?php endforeach?>
								</tbody>
							</table>
						</div>
						<div class="tab-pane fade" id="CajaChicaRegistroEntradaDinero">
							<div class="table-responsive">
								<table class="table table-bordered table table-hover" id="CajaChicaRegistroEntradaDineroTabla">
									<thead>
										<tr>
											<th>#</th>
											<th>Monto <?php echo $TipoDeCambioPincipal['signo'];?></th>
											<th>Monto <?php echo $TipoDeCambioSecundario['signo'];?></th>
											<th>FechaRegistro</th>
											<th>HoraRegistro</th>
											<th>Estado</th>
											<th>Opci&oacute;n</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach($CajaChicaRegistroEntradaDineroArray as $CajaChicaRegistroEntradaDineroRow): ?>
										<tr>
											<th><?php echo $CajaChicaRegistroEntradaDineroRow['id']; ?></th>
											<td data-title="Price" class="numeric"><?php echo $TipoDeCambioPincipal['signo'].' '.$CajaChicaRegistroEntradaDineroRow['monto']; ?></td>
											<td data-title="Price" class="numeric"><?php echo $TipoDeCambioSecundario['signo'].' '.$CajaChicaRegistroEntradaDineroRow['monto']*$TipoDeCambioSecundario['valor']; ?></td>
											<td><?php echo $CajaChicaRegistroEntradaDineroRow['fecha']; ?></td>
											<td><?php echo $CajaChicaRegistroEntradaDineroRow['hora']; ?></td>
											<td>
											<?php
											if($CajaChicaRegistroEntradaDineroRow['habilitado'] == 1){
												echo'<span class="label label-success">Activo</span>';
											}else{
												echo'<span class="label label-danger">Desactivado</span>';
											}
											?>
											</td>
											<td><button class="btn btn-primary btn-xs">Editar</button></td>
										</tr>
										<?php endforeach?>
									</tbody>
								</table>
							</div>
						</div>
						<div class="tab-pane fade" id="CajaChicaRegistroSalidaDinero">
							<div class="table-responsive">
								<table class="table table-bordered table table-hover" id="CajaChicaRegistroSalidaDineroTabla">
									<thead>
										<tr>
											<th>#</th>
											<th>Monto <?php echo $TipoDeCambioPincipal['signo'];?></th>
											<th>Monto <?php echo $TipoDeCambioSecundario['signo'];?></th>
											<th>FechaRegistro</th>
											<th>HoraRegistro</th>
											<th>Estado</th>
											<th>Opci&oacute;n</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach($CajaChicaRegistroSalidaDineroArray as $CajaChicaRegistroSalidaDineroRow): ?>
										<tr>
											<th><?php echo $CajaChicaRegistroSalidaDineroRow['id']; ?></th>
											<td data-title="Price" class="numeric"><?php echo $TipoDeCambioPincipal['signo'].' '.$CajaChicaRegistroSalidaDineroRow['monto']; ?></td>
											<td data-title="Price" class="numeric"><?php echo $TipoDeCambioSecundario['signo'].' '.$CajaChicaRegistroSalidaDineroRow['monto']*$TipoDeCambioSecundario['valor']; ?></td>
											<td><?php echo $CajaChicaRegistroSalidaDineroRow['fecha']; ?></td>
											<td><?php echo $CajaChicaRegistroSalidaDineroRow['hora']; ?></td>
											<td>
											<?php
											if($CajaChicaRegistroSalidaDineroRow['habilitado'] == 1){
												echo'<span class="label label-success">Activo</span>';
											}else{
												echo'<span class="label label-danger">Desactivado</span>';
											}
											?>
											</td>
											<td><button class="btn btn-primary btn-xs">Editar</button></td>
										</tr>
										<?php endforeach?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<table class="table table-bordered">
						<tbody>
							<tr class="well">
								<td><center><strong>Caja Chica | <?php echo TITULO ?></strong></center></td>
							</tr>
							<tr>
								<td>
									<form method="post" action="" class="form-horizontal">
										<label>Tipo de Operaci&oacute;n</label>
										<div class="form-group">
											<div class="col-md-6">
												<div class="radio">
													<label>
														<input type="radio" name="tipo" value="0" required />
														Entrada de Dinero
													</label>
												</div>
											</div>
											<div class="col-md-6">
												<div class="radio">
													<label>
														<input type="radio" name="tipo" value="1" required />
														Salida de Dinero
													</label>
												</div>
											</div>
										</div>
										<hr/>
										<div class="form-group">
											<label class="control-label" for="fecha">Fecha:</label>
											<div class="input-daterange input-group" id="fecha">
												<input type="text" class="input-md form-control" name="fecha" value="<?php echo FechaActual(); ?>" disabled /><div class="input-group-addon"><span class="glyphicon glyphicon-th" aria-hidden="true"></span></div>
											</div>
										</div>
										<hr/>
										<div class="form-group">
											<label>Monto</label>
											<div class="input-group">
												<span class="input-group-addon">$</span>
												<input type="text" class="form-control" name="monto" required="" onkeypress="return PermitirSoloNumeros(event);"  placeholder="Monto de la operaci&oacute;n" autocomplete="off" required/>
											</div>
										</div>
										<hr/>
										<div class="form-group">
											<label>Comentario</label>
											<textarea type="text" class="form-control" name="comentario" placeholder="Escriba un comentario" autocomplete="off" required ></textarea>
										</div>
										<hr/>
										<div class="form-group">
											<button type="submit" name="CajaChicaOperacion" class="btn btn-primary">Realizar Operaci&oacute;n</button>
											<button type="reset" class="btn btn-default">Cancelar</button>
										</div>
									</form>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
    </div>
	<?php include (MODULO.'footer.php'); ?>
	<!-- Cargado archivos javascript al final para que la pagina cargue mas rapido -->
	<?php include(MODULO.'Tema.JS.php');?>
	<script type="text/javascript" language="javascript" src="<?php echo ESTATICO ?>js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" language="javascript" src="<?php echo ESTATICO ?>js/dataTables.bootstrap.js"></script>
	<script type="text/javascript" language="javascript" src="<?php echo ESTATICO ?>js/calendario/bootstrap-datepicker.js"></script>
	<script type="text/javascript" language="javascript" src="<?php echo ESTATICO ?>js/calendario/bootstrap-datepicker.min.js"></script>
	<script type="text/javascript" language="javascript" src="<?php echo ESTATICO ?>js/calendario/locales/bootstrap-datepicker.es.min.js"></script>
	<script type="text/javascript" >
	$('#fecha').datepicker({
		format: "dd-mm-yyyy",
		language: "es",
		beforeShowDay: function (date){
			if (date.getMonth() == (new Date()).getMonth())
			  switch (date.getDate()){
				case 12:
				  return "green";
			}
		}
	});

	//Tablas Diseño
	$(document).ready(function() {
		/*$('#AperturaRegistroTabla').dataTable({
			"scrollY": false,
			"scrollX": true
		});
	
		$('#CierreRegistroTabla').dataTable({
			"scrollY": false,
			"scrollX": true
		});
	
		$('#CajaGeneral').dataTable({
			"scrollY": false,
			"scrollX": true
		});
	
		$('#PagoTarjeta').dataTable({
			"scrollY": false,
			"scrollX": true
		});
	
		$('#PagoEfectivo').dataTable({
			"scrollY": false,
			"scrollX": true
		});
	
		$('#CajaChicaGeneralTabla').dataTable({
			"scrollY": false,
			"scrollX": true
		});
	
		$('#CajaChicaRegistroEntradaDineroTabla').dataTable({
			"scrollY": false,
			"scrollX": true
		});
	
		$('#CajaChicaRegistroSalidaDineroTabla').dataTable({
			"scrollY": false,
			"scrollX": true
		});*/
	});

	// Permitir Solo numeros en los input
	function PermitirSoloNumeros(e)
	{
		var keynum = window.event ? window.event.keyCode : e.which;
		if ((keynum == 8) || (keynum == 46))
		return true;
		 
		return /\d/.test(String.fromCharCode(keynum));
	}
	</script>
	<!-- Cargado archivos javascript al final para que la pagina cargue mas rapido Fin -->
</body>
</html>
