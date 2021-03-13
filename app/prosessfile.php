<?php
	session_start();
	set_time_limit(6000);
	include("config.php");
	
	if ($_POST['action'] == 'Procesar') {
    	Procesar();
	} else if ($_POST['action'] == 'Truncar') {
		truncar();
	}
	
	// Delete all data from datos Table
	function truncar() {
		$conn = getDB();
		$result = $conn->query("TRUNCATE TABLE `datos`");	
		if (!$result ) print(mysql_error());
					
		$conn->query("INSERT INTO sys_logs (log_modulo,log_descripcion,log_usuario,log_fecha) VALUES ('subircsv.php','Se truncado la tabla de datos','".$_SESSION['Usuario']."',NOW())");
		$conn->close();
		echo ("<SCRIPT LANGUAGE='JavaScript'>window.alert('Se han borrado los datos con exito.');window.location.href='subircsv.php';</SCRIPT>");
	}
	// Add data from CSV to Datos Table
	function Procesar() {
		// Replace \ for /
		$file_path = str_replace('\\', '/', dirname(__FILE__)).'/file/datos.csv';
		
		// If there's not file kill the function 
		if (!file_exists($file_path)) {
			die("No se encontró el archivo ".$file_path.".");
		}
		$mysqli = getDB();
		// get current time in Unix epoch format
		$starttime = microtime(true);
		$exito = true;

		$sql = "truncate table datos";
		if (!$resultado = $mysqli->query($sql)) {
			print "<p>Error: La ejecución de la consulta falló debido a: </p><ul><li>Query: " . $sql . "</li><li>Errno: " . $mysqli->errno . "</li><li>Error: " . $mysqli->error . "</li></ul>";
			$exito = false;
		}
		
		if ($exito) {
			$sql = "insert into sys_logs (log_modulo, log_descripcion, log_usuario, log_fecha) values ('subircsv.php', 'Se truncado la tabla de datos', '".$_SESSION['Usuario']."', NOW())";
			if (!$resultado = $mysqli->query($sql)) {
				print "<p>Error: La ejecución de la consulta falló debido a: </p><ul><li>Query: " . $sql . "</li><li>Errno: " . $mysqli->errno . "</li><li>Error: " . $mysqli->error . "</li></ul>";
				$exito = false;
			}
		}
			
		if ($exito) {
			$sql = "truncate table csvimport";
			if (!$resultado = $mysqli->query($sql)) {
				print "<p>Error: La ejecución de la consulta falló debido a: </p><ul><li>Query: " . $sql . "</li><li>Errno: " . $mysqli->errno . "</li><li>Error: " . $mysqli->error . "</li></ul>";
				$exito = false;
			}
		}
		
		if ($exito) {
			$sql = "insert into sys_logs (log_modulo, log_descripcion, log_usuario, log_fecha) values ('subircsv.php', 'Se truncado la tabla de importacion.', '".$_SESSION['Usuario']."', NOW())";
			if (!$resultado = $mysqli->query($sql)) {
				print "<p>Error: La ejecución de la consulta falló debido a: </p><ul><li>Query: " . $sql . "</li><li>Errno: " . $mysqli->errno . "</li><li>Error: " . $mysqli->error . "</li></ul>";
				$exito = false;
			}
		}

		if ($exito) {
			// load data from file in server to table || Maybe Local can be removed so speed will improve.
			$sql = "load data infile '".$file_path."' into table csvimport fields terminated by ',' lines terminated by '\n' ignore 1 lines;";
			if (!$resultado = $mysqli->query($sql)) {
				print "<p>Error: La ejecución de la consulta falló debido a: </p><ul><li>Query: " . $sql . "</li><li>Errno: " . $mysqli->errno . "</li><li>Error: " . $mysqli->error . "</li></ul>";
				$exito = false;
			}
		}
		
		if ($exito) {
			$sql = "select count(1) from CSVImport";
			if (!$resultado = $mysqli->query($sql)) {
				print "<p>Error: La ejecución de la consulta falló debido a: </p><ul><li>Query: " . $sql . "</li><li>Errno: " . $mysqli->errno . "</li><li>Error: " . $mysqli->error . "</li></ul>";
				$exito = false;
			} else {
				$c = $resultado->fetch_array()[0];
			}
		}
		
		if ($exito) {
			$sql = "insert into datos (seguro, cedula_pasaporte, nombre, patrono, razon_social, telefono1, telefono2, fecha, salario) select nullif(seguro, ''), cedula_pasaporte, nombre, patrono, razon_social, nullif(telefono1, '0000000'), nullif(telefono2, '0000000'), fecha, cast(salario as decimal(9, 2)) from CSVImport";
			if (!$resultado = $mysqli->query($sql)) {
				print "<p>Error: La ejecución de la consulta falló debido a: </p><ul><li>Query: " . $sql . "</li><li>Errno: " . $mysqli->errno . "</li><li>Error: " . $mysqli->error . "</li></ul>";
				$exito = false;
			}
		}
		
		if ($exito) {
			$sql = "insert into sys_logs(log_modulo, log_descripcion, log_usuario, log_fecha) select 'subircsv.php', 'Se han cargardo ".$c." registros', '".$_SESSION['Usuario']."', NOW()";
			if (!$resultado = $mysqli->query($sql)) {
				print "<p>Error: La ejecución de la consulta falló debido a: </p><ul><li>Query: " . $sql . "</li><li>Errno: " . $mysqli->errno . "</li><li>Error: " . $mysqli->error . "</li></ul>";
				$exito = false;
			}
		}
		
		$mysqli->close();
		
		$endtime = microtime(true);

		$timediff = $endtime - $starttime;

		if ($exito) {
			echo ("<script language='javascript'>window.alert('Importación exitosa! Se importaron ".$c." registros en ".$timediff." segundos.');window.location.href='subircsv.php';</script>");
		}
	}
?>