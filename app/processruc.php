<?php
	session_start();
	set_time_limit(6000);
	include("config.php");
	
	
	if ($_POST['action'] == 'Procesar') {
    	Procesar();
	} else if ($_POST['action'] == 'Preparar') {
		preparar();
	}
	
	$conn = getDB();
	$conn->query("SET NAMES 'utf8'");
	// Prepare history CSV to be added to ruc table
	function preparar(){
		$input = str_replace('\\', '/', dirname(__FILE__)).'/file/ruc.csv';
		$output = str_replace('\\', '/', dirname(__FILE__)).'/file/preparedRuc.csv';

		if( false !== ($if = fopen($input,'r'))){
			$of = fopen($output,'w');
			$i = 0;
			while( false !== ($data= fgetcsv($if,0,','))){
				if($i!=0){
				    $outputData = array($data[0], $data[1], $data[2]);
					fputcsv($of, $outputData);
				}
			$i++;	
			}
		fclose($if);
		fclose($of);
		echo "<SCRIPT LANGUAGE='JavaScript'>window.alert('Se han preparado los datos con exito.');window.location.href='subirruc.php';</SCRIPT>";
		}
	}

	// Delete all data from ruc Table
	function truncar() {
	    $conn = getDB();
	    $conn->query("SET NAMES 'utf8'");
		$result = $conn->query("TRUNCATE TABLE `ruc`");	
		if (!$result ) print(mysql_error());
					
		$conn->query("INSERT INTO sys_logs (log_modulo,log_descripcion,log_usuario,log_fecha) VALUES ('subirruc.php','Se truncado la tabla de ruc','".$_SESSION['Usuario']."',NOW())");
		$conn->close();
		echo ("<SCRIPT LANGUAGE='JavaScript'>window.alert('Se han borrado los datos con exito.');window.location.href='subirruc.php';</SCRIPT>");
	}
	// Add data from CSV to Datos Table
	function Procesar() {
	    $conn = getDB();
	    $conn->query("SET NAMES 'utf8'");
		// Replace \ for /
		$file_path = str_replace('\\', '/', dirname(__FILE__)).'/file/preparedRuc.csv';
		
		// If there's not file kill the function 
		if (!file_exists($file_path)) {
			die("No se encontró el archivo ".$file_path.".");
		}
		// get current time in Unix epoch format
		$starttime = microtime(true);
		$exito = true;

		$sql = "truncate table ruc";
		if (!$resultado = $conn->query($sql)) {
			print "<p>Error: La ejecución de la consulta falló debido a: </p><ul><li>Query: " . $sql . "</li><li>Errno: " . $conn->errno . "</li><li>Error: " . $conn->error . "</li></ul>";
			$exito = false;
		}
		
		if ($exito) {

			$sql = "insert into sys_logs (log_modulo, log_descripcion, log_usuario, log_fecha) values ('subirruc.php', 'Se truncado la tabla de ruc', '".$_SESSION['Usuario']."', NOW())";
			if (!$resultado = $conn->query($sql)) {
				print "<p>Error: La ejecución de la consulta falló debido a: </p><ul><li>Query: " . $sql . "</li><li>Errno: " . $conn->errno . "</li><li>Error: " . $conn->error . "</li></ul>";
				$exito = false;
			}
		}
			
		if ($exito) {
			$sql = "truncate table rucimport";
			if (!$resultado = $conn->query($sql)) {
				print "<p>Error: La ejecución de la consulta falló debido a: </p><ul><li>Query: " . $sql . "</li><li>Errno: " . $conn->errno . "</li><li>Error: " . $conn->error . "</li></ul>";
				$exito = false;
			}
		}
		
		if ($exito) {
			$sql = "insert into sys_logs (log_modulo, log_descripcion, log_usuario, log_fecha) values ('subirruc.php', 'Se truncado la tabla de importacion de ruc.', '".$_SESSION['Usuario']."', NOW())";
			if (!$resultado = $conn->query($sql)) {
				print "<p>Error: La ejecución de la consulta falló debido a: </p><ul><li>Query: " . $sql . "</li><li>Errno: " . $conn->errno . "</li><li>Error: " . $conn->error . "</li></ul>";
				$exito = false;
			}
		}

		if ($exito) {
			// load data from file in server to table || Maybe Local can be removed so speed will improve.
			$sql = "load data infile '".$file_path."' into table rucimport fields terminated by ',' enclosed by '".'"'."' lines terminated by '\n'";
			if (!$resultado = $conn->query($sql)) {
				print "<p>Error: La ejecución de la consulta falló debido a: </p><ul><li>Query: " . $sql . "</li><li>Errno: " . $conn->errno . "</li><li>Error: " . $conn->error . "</li></ul>";
				$exito = false;
			}
		}
		
		if ($exito) {
			$sql = "select count(1) from rucimport";
			if (!$resultado = $conn->query($sql)) {
				print "<p>Error: La ejecución de la consulta falló debido a: </p><ul><li>Query: " . $sql . "</li><li>Errno: " . $conn->errno . "</li><li>Error: " . $conn->error . "</li></ul>";
				$exito = false;
			} else {
				$c = $resultado->fetch_array()[0];
			}
		}
		
		if ($exito) {
			$sql = "insert ignore into ruc (nom_comercial, razon_social, ruc, observaciones) select * from rucimport";
			if (!$resultado = $conn->query($sql)) {
				print "<p>Error: La ejecución de la consulta falló debido a: </p><ul><li>Query: " . $sql . "</li><li>Errno: " . $conn->errno . "</li><li>Error: " . $conn->error . "</li></ul>";
				$exito = false;
			}
		}
		
		if ($exito) {
			$sql = "insert into sys_logs(log_modulo, log_descripcion, log_usuario, log_fecha) select 'subirruc.php', 'Se han cargardo ".$c." registros', '".$_SESSION['Usuario']."', NOW()";
			if (!$resultado = $conn->query($sql)) {
				print "<p>Error: La ejecución de la consulta falló debido a: </p><ul><li>Query: " . $sql . "</li><li>Errno: " . $conn->errno . "</li><li>Error: " . $conn->error . "</li></ul>";
				$exito = false;
			}
		}
		
		$conn->close();
		
		$endtime = microtime(true);

		$timediff = $endtime - $starttime;

		if ($exito) {
			echo ("<script language='javascript'>window.alert('Importación exitosa! Se importaron ".$c." registros en ".$timediff." segundos.');window.location.href='subirruc.php';</script>");
		}
	}
?>