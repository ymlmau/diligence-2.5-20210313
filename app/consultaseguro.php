<?php
	session_start();
	if(!isset($_SESSION['Usuario']) && $_SESSION['Usuario'] == ''){
		header("location:index.php");
	}
	$title = "Consultar por Seguro";
	include("header.php");
	include("config.php");
	$cn = getDB();
	$seguro = trim(htmlentities(isset($_POST['seguro']) && $_POST['seguro'] != '' ? $_POST['seguro'] : '----'));
	$opt = trim(htmlentities(isset($_POST['opt']) && $_POST['opt'] != '' ? $_POST['opt'] : '----'));	
?>
<div class="Content">
		<div class="frm">
			<div class="frmContent">
				<div class="frmTitle" >
					<h2>Consulta de personal por Seguro</h2>
				</div>
				<div class="frmFields" >
					<form method="post" action="consultaseguro.php">
						Seguro:
						<p><input type="text" name="seguro" value="" required="required"></p>
						<p><input type="submit" name="btnBuscar" value="Buscar"></p>
					</form>
<?php
	echo "<strong>Buscando</strong>: $seguro";
	$sql1 = "SELECT seguro, cedula_pasaporte FROM datos WHERE seguro like '%$seguro%' limit 100";
	$result = mysqli_query($cn,$sql1);
	$result_check=mysqli_num_rows($result);
?>
					<p>Coincidencias: <?= $result_check ?><p>
<?php mysqli_data_seek ($result, 0); ?>
					<form name='add' method="post">
						<select name="opt" id="opt">
							<?php if($result_check > 0){
								while ($row = mysqli_fetch_array($result)) {?>
									<option value="<?= $row['cedula_pasaporte'] ?>" ><?= $row['seguro']; ?></option>
									<?php
								}}else{
									$row['cedula_pasaporte'] = '';
								}
								?>
						</select>
						<input type="submit" name="btnEnviar" value="Enviar" />
					</form>
					<?php
  			//$sql2 = "SELECT * FROM datos a LEFT JOIN direccion b on a.patrono = b.nom_comercial LEFT JOIN ruc c on a.patrono = c.razonsocial WHERE a.cedula = '$opt' LIMIT 1";
				$sql2 = "SELECT * FROM all_data WHERE cedula_pasaporte = '$opt'";
				$result2 = mysqli_query($cn,$sql2);
				$cn->query("SET NAMES 'utf8'");
				//$result = mysqli_query($cn, "SELECT *,(SELECT count(*) FROM datos WHERE patrono=d1.patrono) as canemple FROM datos d1 WHERE REPLACE(cedula, ' ', '') = REPLACE('".$opt."', ' ', '')");

				while ($row2 = mysqli_fetch_assoc($result2)) {
					$num_empleados = "SELECT count(*) as total FROM datos WHERE patrono='" . $row2['patrono'] . "'";
		$result3= mysqli_query($cn,$num_empleados);
		$row3 = mysqli_fetch_array($result3);
		$cedula  = $row2['cedula_pasaporte'];
		$hsql = "SELECT cedula_pasaporte from historial WHERE cedula_pasaporte = '$cedula'";
		$antique = $cn->query($hsql);
		if($antique){$history = 'Si';}
		else{$history = 'No';}
?>
					<ul>
						<li>Seguro: <?= $row2['seguro'] ?></li>
						<li>Cedula: <?= CedulaFormat($row2['cedula_pasaporte']) ?></li>
						<li>Nombre: <?= $row2['nombre'] ?></li>
						<li>Tel&eacute;fono: <?= $row2['telefono1'] ?></li>
						<li>Tel&eacute;fono: <?= $row2['telefono2'] ?></li>
						<li>Salario: $<?= $row2['salario'] ?></li>
						<li>Patrono: <?= $row2['patrono'] ?></li>
						<li>RUC: <?= $row2['ruc'] ?></li>
						<li>Direcci&oacute;n: <?= $row2['direccion'] ?></li>
						<li>+6 meses: <?= $history ?> </li>
					</ul>
					<p>-<?= $row2['patrono'] ?> tiene: <?= $row3['total'] ?> empleados</p>
<?php
		$cn->query("INSERT INTO sys_logs (log_modulo,log_descripcion,log_empreado,log_usuario,log_fecha) VALUES ('consultaseguro.php','Se han consultado los datos del empleado con cedula: ".$row2['cedula_pasaporte']."','".$row2['nombre']."','".$_SESSION['Usuario']."',NOW())");
	}
?>
				</div>
			</div>
		</div>
	</div>
<?php 
include("footer.php");
?>
