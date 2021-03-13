<?php
	session_start();
	if(!isset($_SESSION['Usuario']) && $_SESSION['Usuario'] == ''){
		header("location:index.php");
	}
	$title="Consulta por Nombre";
	include("header.php");
	include("config.php");
?>
	<div class="Content">
		<div class="frm">
			<div class="frmContent">
				<div class="frmTitle" >
					<h2>Consulta de Personal</h2>
				</div>
<?php
	$cn=  getDB();
	$nombre = isset($_POST['nombre']) && $_POST['nombre'] != '' ? $_POST['nombre'] : '----';
	$opt = isset($_POST['opt']) && $_POST['opt'] != '' ? $_POST['opt'] : '----';	
?>

    				<div class="frmFields" >
    					<form method="post" action="consultanombre.php">
    						Nombre:
    						<p><input type="text" name="nombre" value="" required="required"></p>
    						<p><input type="submit" name="btnBuscar" value="Buscar" /></p>
    					</form>
<?php
echo "Buscando: $nombre";
//$sql1 = "SELECT nombre, cedula FROM datos where nombre like '%$nombre%' ORDER BY nombre ASC limit 100";
$sql1 = "SELECT * FROM busca_nombre WHERE nombre like '%$nombre%' ORDER BY nombre ASC LIMIT 100";
$result = mysqli_query($cn,$sql1);
$result_check = mysqli_num_rows($result);
?>
    						<p>Coincidencias:<?= $result_check ?></p>
<?php	mysqli_data_seek ($result, 0);?>
    			<form name='add' method="post">
    				<select name="opt" id="opt">
							<?php if($result_check > 0){
								while ($row = mysqli_fetch_array($result)){?>
									<option value="<?= $row['cedula_pasaporte'] ?>" ><?= $row['nombre']; ?></option>
								<?php
							}}else {
								$row['cedula_pasaporte'] = '';
							}
							?>
    						</select>
    						<input type='submit' name='btnEnviar' value="Enviar" />
    					</form>
							<?php
								$cedula = $row['cedula_pasaporte'];
								//$sql2= "SELECT * from datos a left join direccion b on a.patrono = b.nom_comercial left join ruc c on a.patrono = c.razonsocial where a.cedula = '$opt'";
								$sql2 = "SELECT * from all_data WHERE cedula_pasaporte = '$opt' limit 1";
								$result2 = mysqli_query($cn,$sql2);
								$cn->query("SET NAMES 'utf8'");
								//$result3 = mysqli_query($cn, "SELECT *,(SELECT count(*) FROM datos WHERE patrono=$row2['patrono']) as canemple FROM datos d1 WHERE REPLACE(cedula, ' ', '') = REPLACE('".$opt."', ' ', '')");

								while($row2 = mysqli_fetch_assoc($result2)){
								$empleados = "SELECT count(*) as total FROM datos WHERE patrono='" . $row2['patrono'] ."'";
								$result3 = mysqli_query($cn,$empleados );
								$row3 = mysqli_fetch_array($result3);
								$empleado = $row2['nombre'];
								$cedula = $row2['cedula_pasaporte'];
								$hsql = "SELECT cedula_pasaporte from historial WHERE cedula_pasaporte = '$cedula'";
								$antique = $cn->query($hsql);
								if($antique){$history = 'Si';}
								else{$history = 'No';}
								//while ($row = mysqli_fetch_assoc($result)) {
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
								<li>Direcci&oacute;n: <?= $row2['direccion']?></li>
								<li>+6 meses: <?=$history?></li>
							</ul>
							<p>-<?= $row2['patrono'] ?> tiene: <?= $row3['total'] ?> empleados</p>
								<?php $cn->query("INSERT INTO sys_logs (log_modulo,log_descripcion,log_empreado,log_usuario,log_fecha) VALUES ('consultacedula.php','Se han consultado los datos del empleado con cedula: ".$row2['cedula_pasaporte']."','".$row2['nombre']."','".$_SESSION['Usuario']."',NOW())");
						}
						?>

    				</div>
    			</div>
    		</div>
    	</div>
   <?php include("footer.php");?>