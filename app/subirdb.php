<?php
include_once("inc/config.php"); # incluye archivo de configuracion
include_once("inc/funcion.php"); # incluye archibo de funciones
$db_host = 'localhost';
$db_user = 'arcangel_APP';
$db_pass = 'Arcang31';

$database = 'arcangel_APP';
//$table = 'datos';
$cn=mysql_connect($db_host, $db_user, $db_pass);
if (!$cn) die("No se pudo establecer conexión a la base de datos");

$rpta=mysql_select_db("$database",$cn);
if (!$rpta) die("base de datos no existe") ;
		//mysqli_query($cn,'SET FOREIGN_KEY_CHECKS=0;');
		//mysqli_query($cn,'select * from datos') or die('Error: '.mysql_error());
		//mysqli_query($cn,'SET FOREIGN_KEY_CHECKS=1;');

$result = mysql_query("TRUNCATE TABLE `datos`");	
if (!$result ) print(mysql_error());

//$fila = 2;
if (($gestor = fopen("file/BASE DE DATOS1.csv", "r")) !== FALSE) {
    $c = 0;
	while (($data = fgetcsv($gestor, 1000, ";","\t")) !== FALSE) {
		//echo "<p>",$c,",","</p>";
		$c=$c+1;

		//Limpiezs de caracter ' 
		$nom1 = addslashes($data[2]);
		$nom2 = addslashes($data[3]);
		$nom3 = addslashes($data[4]);
		
		//Insertamos los datos con los valores...
		if ($data[3] != '') {
			$sql = "INSERT into datos values('$data[0]','$data[1]','$nom1','$nom2','$nom3','$data[5]','$data[6]','$data[7]','$data[8]','$data[9]')";
			$result =  mysql_query($sql);
			if (!$result) { 
				print(mysql_error());
				echo $sql;
			}
		}
	}
}
	fclose($gestor);
	echo "Importación exitosa!";
	echo $c."Registros importados";
	mysqli_close($cn);
?>