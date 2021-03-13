<?php
	session_start();
	if(!isset($_SESSION['Usuario'])){
		header("Location: index.php");
	}
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8" />
	<link rel="stylesheet" href="css/style.css" type="text/css" />
</head>

	<body>
		<div class="d-header" >
			<div class="container">
				<div class="titleContent">
					<a class="logomark" href="#" >
						<img src="image/image.png" border="0" width="80" height="50">
					</a>
					<div class="titleHeader" >MOLLAH, MORGAN ABOGADOS</div>
				</div>
			</div>
			<div class="HeaderNavBar">
					<ul class="HeaderItems">
						<?php include('Menu.php');?>
					</ul>
			</div>
		</div><!--d-header-->

<div class="Content">
	<div class="frm">
		<div class="frmContent">
			<div class="frmTitle" >
				<h2>Test</h2>
			</div>

<?php
				$db_host = 'localhost';
				$db_user = 'root';
				$db_pass = '';

				$database = 'arcangel_APP';

				$nombre = trim(htmlentities(isset($_POST['nombre']) && $_POST['nombre'] != '' ? $_POST['nombre'] : '----'));

				$opt = trim(htmlentities(isset($_POST['opt']) && $_POST['opt'] != '' ? $_POST['opt'] : '----'));


				$cn=mysqli_connect($db_host, $db_user, $db_pass);
				if (!$cn) die("No se pudo establecer conexión a la base de datos");

				$rpta=mysqli_select_db($cn, $database);
				if (!$rpta) die("base de datos no existe");?>


			<div class="frmFields" >
				<form method="post" action="test.php">
					Nombre:
					<p><input type="text" name="nombre" value="" required="required"></p>
					<p><input type="submit" name="btnBuscar" value="Buscar" /></p>
				</form>

			<?php
	echo "Buscando: $nombre";
	$sql1 = "SELECT nombre, cedula FROM datos where nombre like '%$nombre%' ORDER BY nombre ASC limit 100";
	$result = mysqli_query($cn,$sql1);
	$result_check = mysqli_num_rows($result);
?>

	<p>Coincidencias:<?= $result_check ?></p>
<?php
mysqli_data_seek($result,0);?>
<form name='add' method="post">
		<select name="opt" id="opt">

	<?php if($result_check > 0){
		while ($row = mysqli_fetch_array($result)){?>
			<option value="<?= $row['cedula'] ?>" ><?= $row['nombre']; ?></option>
		<?php
	}}
?>
		</select>
		<input type='submit' name='btnEnviar' value="Enviar" />
	</form>

	<?php
		//$cedula = $row['cedula'];
		//$sql2 = "SELECT * FROM datos WHERE cedula='$opt'";
		$sql2= "SELECT * from datos a left join direccion b on a.patrono = b.nom_comercial left join ruc c on a.patrono = c.razonsocial where a.cedula = '$opt'";
		$result2 = mysqli_query($cn,$sql2);
		$cn->query("SET NAMES 'utf8'");
		//$result3 = mysqli_query($cn, "SELECT *,(SELECT count(*) FROM datos WHERE patrono=$row2['patrono']) as canemple FROM datos d1 WHERE REPLACE(cedula, ' ', '') = REPLACE('".$opt."', ' ', '')");

		while($row2 = mysqli_fetch_assoc($result2)){
		$empleados = "SELECT count(*) as total FROM datos WHERE patrono='" . $row2['patrono'] ."'";
		$result3 = mysqli_query($cn,$empleados );
		$row3 = mysqli_fetch_array($result3);

		//while ($row = mysqli_fetch_assoc($result)) {
	?>
	<ul>
		<li>Seguro: <?= $row2['seguro'] ?></li>
		<li>Cedula: <?= CedulaFormat($row2['cedula']) ?></li>
		<li>Nombre: <?= $row2['nombre'] ?></li>
		<li>Tel&eacute;fono: <?= $row2['tel1'] ?></li>
		<li>Tel&eacute;fono: <?= $row2['tel2'] ?></li>
		<li>Salario: $<?= $row2['salario'] ?></li>
		<li>Patrono: <?= $row2['patrono'] ?></li>
		<li>RUC: <?= $row2['ruc'] ?></li>
		<li>Direccion: <?= $row2['direccion']?></li>
	</ul>
	<p>-<?= $row2['patrono'] ?> tiene: <?= $row3['total'] ?> empleados</p>
<?php
}
?>

	    				</div>
	    			</div>
	    		</div>
	    	</div>
	    </body>
	</html>
