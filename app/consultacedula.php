<?php
	session_start();
	if(!isset($_SESSION['Usuario']) && $_SESSION['Usuario'] == ''){
		header("location:index.php");
	}
	$title="Consulta por Cédula";
	include("header.php");
	include("config.php");
	$busqueda = '';
	$tipoDocumento = (isset($_POST['tipoDocumento']) && $_POST['tipoDocumento'] != '') ? $_POST['tipoDocumento'] : '';
	if ($tipoDocumento == '1') {
		$provincia = trim(htmlentities(isset($_POST['provincia']) && $_POST['provincia'] != '' ? $_POST['provincia'] : ''));
		$inicial = trim(htmlentities(isset($_POST['inicial']) && $_POST['inicial'] != '' ? $_POST['inicial'] : ''));
		$tomo = trim(htmlentities(isset($_POST['tomo']) && $_POST['tomo'] != '' ? $_POST['tomo'] : ''));
		$asiento = trim(htmlentities(isset($_POST['asiento']) && $_POST['asiento'] != '' ? $_POST['asiento'] : ''));
		$busqueda = $provincia . $inicial . $tomo . $asiento;
		$where = " where cedula_pasaporte like '";
		$where .= ($provincia.$inicial != '') ? $provincia.$inicial : '%';
		$where .= '-';
		$where .= ($tomo != '') ? $tomo : '%';
		$where .= '-';
		$where .= ($asiento != '') ? $asiento : '%';
		$where .= "' or cedula_pasaporte like '";
		$where .= ($provincia.$inicial != '') ? $provincia.$inicial : '%';
		$where .= ($tomo != '') ? substr('00000'.$tomo, -5) : '%';
		$where .= ($asiento != '') ? substr('000000'.$asiento, -6) : '%';
		$where .= "' ";

	} else if ($tipoDocumento == '2') {
		$where = " where cedula like '";
		$where .= (isset($_POST['pasaporte']) && $_POST['pasaporte'] != '') ? '%'.$_POST['pasaporte'].'%' : '%';
		$where .= "' ";
	} else {
		$where = " where 1 = 0 ";
	}

	$opt = (isset($_POST['opt']) && $_POST['opt'] != '' ? $_POST['opt'] : '----');

	$cn = getDB();
	
?>
<div class="Content">
		<div class="frm">
			<div class="frmContent">

				<div class="frmTitle" >
					<h2>Consulta de personal por cedula</h2>
				</div>

				<div class="frmFields" >
					<form method="post" action="consultacedula.php">
						<div class="form-check-inline">
							<input class="form-check-input" type="radio" id="rbCedula" value="1" name="tipoDocumento" checked> 
							<label class="form-check-label" for="rbCedula">Cédula</label>

							<input class="form-check-input" type="radio" id="rbPasaporte" value="2" name="tipoDocumento"> 
							<label class="form-check-label" for="rbPasaporte">Pasaporte</label>
						</div>
						
						<hr>
						
						<div class="form-row">

								<div id="grpCedula">
									<div class="form-inline">


											<div class=""><select class="form-control" id="provincia" name="provincia">
												<option value=""></option>
												<option value="1">01</option>
												<option value="2">02</option>
												<option value="3">03</option>
												<option value="4">04</option>
												<option value="5">05</option>
												<option value="6">06</option>
												<option value="7">07</option>
												<option value="8">08</option>
												<option value="9">09</option>
												<option value="10">10</option>
											</select></div>
											<div class=""><select class="form-control" id="inicial" name="inicial">
												<option value=""></option>
												<option value="AV">AV</option>
												<option value="E">E</option>
												<option value="N">N</option>
												<option value="PE">PE</option>
												<option value="PI">PI</option>
											</select></div>
											<div class=""><input class="form-control" type="text"  id="tomo" maxlength="5" name="tomo" value="" style="width: 75px;"></div>
											<div class=""><input class="form-control" type="text"  id="asiento" maxlength="6" name="asiento" value="" style="width: 75px;"></div>

									</div>
								</div>
								<div id="grpPasaporte" style="display : none;" >
									<input class="form-control" type="text" id="pasaporte" name="pasaporte" value="">
								</div>
								<div class="col-2"><input class="btn btn-primary btn-block" type="submit" value="Buscar"></div>
						</div>
					</form>
					<br>
<?php
	echo "<p>Buscando: <strong>$busqueda</strong></p>";
	$sql1 ="select distinct(cedula_pasaporte) as cedula_pasaporte from datos ".$where." limit 1";
	$result = mysqli_query($cn, $sql1);
	$result_check = mysqli_num_rows($result);
?>
					<p>Coincidencias: <?= $result_check ?><p>
<?php mysqli_data_seek ($result, 0); ?>
					<div class="form-inline">
					<form name='add' method="post">
						<select class="form-control" name="opt" id="opt">
<?php if($result_check > 0){
	while ($row = mysqli_fetch_array($result)) {?>
							<option value="<?= $row['cedula_pasaporte'] ?>"><?= $row['cedula_pasaporte'] ?></option>
<?php
}}
		else{
			$row['cedula_pasaporte'] = '';
		}?>
						</select>
						<input class="btn btn-success" type='submit' name='submit'/>
					</form>
				</div>
					<br>
<?php
	$cedula = $row['cedula_pasaporte'];
	//$sql2 = "SELECT * FROM datos a LEFT JOIN direccion b on a.patrono = b.nom_comercial LEFT JOIN ruc c on a.patrono = c.razonsocial WHERE a.cedula = '$opt'";
	$sql2= "SELECT * from all_data where cedula_pasaporte = '$opt' limit 1";
	$result2 = mysqli_query($cn, $sql2);
	$cn->query("SET NAMES 'utf8'");
	//$result = mysqli_query($cn, "SELECT *,(SELECT count(*) FROM datos WHERE patrono=d1.patrono) as canemple FROM datos d1 WHERE REPLACE(cedula, ' ', '') = REPLACE('".$opt."', ' ', '')");
	while ($row2 = mysqli_fetch_assoc($result2)) {
		$num_empleados = "SELECT count(*) as total FROM datos WHERE patrono='" . $row2['patrono'] . "'";
		$result3 = mysqli_query($cn,$num_empleados);
		$row3 = mysqli_fetch_array($result3);
		$empleado = $row2['nombre'];
		$cedula = $row2['cedula_pasaporte'];
		//$hsql = "SELECT cedula_pasaporte from historial WHERE cedula_pasaporte = '$cedula'";
		//$antique = $cn->query($hsql);
		//if($antique){$history = 'Si';}
		//else{$history = 'No';}
?>
					<table class="table table-sm  table-light ">
							<thead>
								<tr>
									<th colspan="2">Fecha de datos: <?= ToDate($row2['fecha']) ?></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<th colspan="2">Datos de empleado</th>
								</tr>

							<tr>
								<th>Seguro:</th>
								<td><?= $row2['seguro'] ?></td>
							</tr>
						<tr>
							<th>Cedula:</th> 
							<td><?= CedulaFormat($row2['cedula_pasaporte']) ?></td>
						</tr>
						<tr>
							<th>Nombre:</th> 
							<td><?= $row2['nombre'] ?></td>
						</tr>
						<tr>
						<tr>
							<th>Salario:</th> 
							<td><?= $row2['salario'] ?></td>
						</tr>
							<th colspan="2">Datos del patrono</th>
						</tr>
						<tr>
							<th>Patrono:</th> 
							<td><?= $row2['patrono'] ?></td>
						</tr>
						<tr>
							<th>RUC:</th> 
							<td><?= $row2['ruc'] ?></td>
						</tr>
						<tr>
							<th>Tel&eacute;fono:</th> 
							<td><?= $row2['telefono1'] ?></td>
						</tr>
						<tr>
							<th>Tel&eacute;fono:</th> 
							<td><?= $row2['telefono2'] ?></td>
						</tr>
						<tr>
							<th>Direcci&oacute;n:</th> 
							<td><?= $row2['direccion'] ?></td>
						</tr>
						<!--tr>
							<th>+ 6 Meses:</th> 
							<td><?= $history ?></td>
						</tr-->
						</tbody>
					</table>
					<?php
						$historial = "SELECT patrono,fecha,salario from historial where cedula_pasaporte='$cedula' ORDER BY id_historial DESC LIMIT 24";
						$previous = mysqli_query($cn,$historial);
						$previous_check = mysqli_num_rows($previous) + 3;			
					?>
					<hr>
					<p><strong>Historial Laboral</strong></p>
					<?php if($previous_check >= 6){
							$continuidad = 'Si';
					}
						else{
							$continuidad = 'No';
						}
						?>
					<p>Seis Meses o Mas: <strong><?= $continuidad ?></strong></p>
					<p>Cantidad de Meses en Historial: <b><?=$previous_check?></b></p>
					<table class="table table-sm table-light">
						<thead>
							<tr>
								<th>Fecha</th>
								<th>Patrono</th>
								<th>Salario</th>
							</tr>
						</thead>
						<tbody>
						<?php
							if($previous_check>0){
								$promedio = 0 ;
								$conteo = 0;
								mysqli_data_seek ($previous, 0);
								while($hdata = mysqli_fetch_array($previous)){
									$conteo += 1;
									$promedio += $hdata['salario'];
									?> 
									<tr>
										<td><?= $hdata['fecha'] ?></td>
										<td><?= $hdata['patrono'] ?></td>
										<td><?= $hdata['salario'] ?></td>
									</tr>	
							
							<?php	}
							echo "<p>Promedio Salarial: <b>" . floatval($promedio/$conteo). "</b></p>";	
						}
							?>
							
						</tbody>
					</table>
					<hr>
					<p><strong><?= $row2['patrono'] ?></strong> tiene: <?= $row3['total'] ?> empleados</p>
<?php
		$cn->query("INSERT INTO sys_logs (log_modulo,log_descripcion,log_empreado,log_usuario,log_fecha) VALUES ('consultacedula.php','Se han consultado los datos del empleado con cedula: ".$row2['cedula_pasaporte']."','".$row2['nombre']."','".$_SESSION['Usuario']."',NOW())");
	}
?>
				</div>
			</div>
		</div>
	</div>
	<!--Hide or Show Passport or Cedula choice -->
	<script>
		$(document).on("click", "input[name='tipoDocumento']", function() {
			if ($('#rbCedula').is(':checked')) {
				 $('#grpCedula').show();
				 $('#grpPasaporte').hide();
			} else if ($('#rbPasaporte').is(':checked')) {
				 $('#grpCedula').hide();
				 $('#grpPasaporte').show();
			} else {
				 $('#grpCedula').hide();
				 $('#grpPasaporte').hide();
			}
		});
	</script>
<?php
	include('footer.php');
?>
