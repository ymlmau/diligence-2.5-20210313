<?php
	session_start();
	set_time_limit(6000);
	include("config.php");
	
	if ($_POST['action'] == 'Procesar') {
    	Procesar();
	} else if ($_POST['action'] == 'Preparar') {
		preparar();
	}
	else if($_POST['action'] == 'Borrar'){
		truncar();
	}
	

	// Prepare history CSV to be added to historial table
	function preparar(){
		$input = str_replace('\\', '/', dirname(__FILE__)).'/file/historial.csv';
		$output = str_replace('\\', '/', dirname(__FILE__)).'/file/preparedHistory.csv';

		if( false !== ($if = fopen($input,'r'))){
			$of = fopen($output,'w');
			$i = 0;
			while( false !== ($data= fgetcsv($if,0))){
				if($i!=0){
					
					$outputData = array($data[1], $data[2], $data[3], $data[7], $data[8]);
					if(strlen($outputData[3]) !=4){
						$outputData[3] = '0' . $outputData[3];
					}
					fputcsv($of, $outputData);
				}
			$i++;	
			}
		fclose($if);
		fclose($of);
		echo "<SCRIPT LANGUAGE='JavaScript'>window.alert('Se han preparado los datos con exito.');window.location.href='subirhistorial.php';</SCRIPT>";
		}
	}

	// Delete all data from historial Table
	function truncar() {
		$conn = getDB();
		$result = $conn->query("TRUNCATE TABLE `historial`");	
		if (!$result ) print(mysqli_error());
					
		$conn->query("INSERT INTO sys_logs (log_modulo,log_descripcion,log_usuario,log_fecha) VALUES ('subirhistorial.php','Se truncado la tabla de historial','".$_SESSION['Usuario']."',NOW())");
		$conn->close();
		echo ("<SCRIPT LANGUAGE='JavaScript'>window.alert('Se han borrado los datos con exito.');window.location.href='subirhistorial.php';</SCRIPT>");
	}
	// Add data from CSV to Datos Table
	function Procesar() 
	{
		// Replace \ for /
		$file_path = str_replace('\\', '/', dirname(__FILE__)).'/file/preparedHistory.csv';
		
		// If there's not file kill the function 
		if (!file_exists($file_path)) 
		{
			die("No se encontró el archivo ".$file_path.".");
		}

		$mysqli = getDB();
		// get current time in Unix epoch format
		$starttime = microtime(true);
		$exito = true;

		/*$sql = "truncate table historial";
		if (!$resultado = $mysqli->query($sql)) 
		{
			print "<p>Error: La ejecución de la consulta falló debido a: </p><ul><li>Query: " . $sql . "</li><li>Errno: " . $mysqli->errno . "</li><li>Error: " . $mysqli->error . "</li></ul>";
			$exito = false;
		}*/
		
		if ($exito) {
			$sql = "insert into sys_logs (log_modulo, log_descripcion, log_usuario, log_fecha) values ('subirhistorial.php', 'Se truncado la tabla de historial', '".$_SESSION['Usuario']."', NOW())";
			if (!$resultado = $mysqli->query($sql)) {
				print "<p>Error: La ejecución de la consulta falló debido a: </p><ul><li>Query: " . $sql . "</li><li>Errno: " . $mysqli->errno . "</li><li>Error: " . $mysqli->error . "</li></ul>";
				$exito = false;
			}
		}
			
		if ($exito) 
		{
			$sql = "truncate table HistoryImport";
			if (!$resultado = $mysqli->query($sql)) 
			{
				print "<p>Error: La ejecución de la consulta falló debido a: </p><ul><li>Query: " . $sql . "</li><li>Errno: " . $mysqli->errno . "</li><li>Error: " . $mysqli->error . "</li></ul>";
				$exito = false;
			}
		}
		
		if ($exito) 
		{
			$sql = "insert into sys_logs (log_modulo, log_descripcion, log_usuario, log_fecha) values ('subirhistorial.php', 'Se truncado la tabla de importacion de historial.', '".$_SESSION['Usuario']."', NOW())";
			if (!$resultado = $mysqli->query($sql)) 
			{
				print "<p>Error: La ejecución de la consulta falló debido a: </p><ul><li>Query: " . $sql . "</li><li>Errno: " . $mysqli->errno . "</li><li>Error: " . $mysqli->error . "</li></ul>";
				$exito = false;
			}
		}

		if ($exito) 
		{
			// load data from file in server to table || Maybe Local can be removed so speed will improve.
			$sql = "load data infile '".$file_path."' into table HistoryImport fields terminated by ',' enclosed by '".'"'."' lines terminated by '\n'";
			if (!$resultado = $mysqli->query($sql)) 
			{
				print "<p>Error: La ejecución de la consulta falló debido a: </p><ul><li>Query: " . $sql . "</li><li>Errno: " . $mysqli->errno . "</li><li>Error: " . $mysqli->error . "</li></ul>";
				$exito = false;
			}
		}
		
		if ($exito) 
		{
			$sql = "select count(1) from HistoryImport";
			if (!$resultado = $mysqli->query($sql)) 
			{
				print "<p>Error: La ejecución de la consulta falló debido a: </p><ul><li>Query: " . $sql . "</li><li>Errno: " . $mysqli->errno . "</li><li>Error: " . $mysqli->error . "</li></ul>";
				$exito = false;
			}
			else 
			{
				$c = $resultado->fetch_array()[0];
			}
		}
		
		if ($exito) 
		{
			$sql = "insert ignore into historial (cedula_pasaporte, nombre, patrono,fecha,salario) select * from HistoryImport";
			if (!$resultado = $mysqli->query($sql)) 
			{
				print "<p>Error: La ejecución de la consulta falló debido a: </p><ul><li>Query: " . $sql . "</li><li>Errno: " . $mysqli->errno . "</li><li>Error: " . $mysqli->error . "</li></ul>";
				$exito = false;
			}
		}
		
		if ($exito) 
		{
			$sql = "insert into sys_logs(log_modulo, log_descripcion, log_usuario, log_fecha) select 'subirhistorial.php', 'Se han cargardo ".$c." registros', '".$_SESSION['Usuario']."', NOW()";
			if (!$resultado = $mysqli->query($sql))
			{
				print "<p>Error: La ejecución de la consulta falló debido a: </p><ul><li>Query: " . $sql . "</li><li>Errno: " . $mysqli->errno . "</li><li>Error: " . $mysqli->error . "</li></ul>";
				$exito = false;
			}
		}
		
		$mysqli->close();
		
		$endtime = microtime(true);

		$timediff = $endtime - $starttime;

		if ($exito) 
		{
			echo ("<script language='javascript'>window.alert('Importación exitosa! Se importaron ".$c." registros en ".$timediff." segundos.');window.location.href='subirhistorial.php';</script>");
		}
	}
?>