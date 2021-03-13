<?php
	include("config.php");
	$filas= "";
	$cabecera = "";
	$pie = "";

	$fecha_inicial = $_POST['finical'];
    $fecha_final = $_POST['ffinal'];

	$mysqli = getDB();
	$sql = "select * from sys_logs where left(log_fecha,10)>='".$fecha_inicial."' and left(log_fecha,10) <= '".$fecha_final."'";
	$result = $mysqli->query($sql);

	while ($row = mysqli_fetch_row($result)) {
		$filas = $filas."<tr>";
		$filas = $filas."<td>".$row[1]."</td>";
		$filas = $filas."<td>".$row[2]."</td>";
		$filas = $filas."<td>".$row[3]."</td>";
		$filas = $filas."<td>".$row[4]."</td>";
		$filas = $filas."<td>".$row[5]."</td>";
		$filas = $filas."</tr>";
	}

	$cuerpo = "<html>
		<head>
		<style>
		table {
			border-collapse: collapse;
			width: 100%;
		}

		h1 {text-align: center}


		@page *{
			margin-top: 0cm;
			margin-bottom: 0cm;
			margin-left: 0cm;
			margin-right: 0cm;
		}

		th, td {
			text-align: left;
			padding: 8px;
		}

		tr:nth-child(even){background-color: #f2f2f2}

		th {
			background-color: #4CAF50;
			color: white;
		}
		</style>
		</head>
		<body>
		<h1>Reporte de Logs</h1>
		<br/>
		<div>
		<table style='width=\"100%\";text-align:center;'>
		<thead>
		<tr>
			<th style='width:20%;'> Modulo </th>
			<th style='width:60%;'> Descripcion </th>
			<th style='width:30%;'> Empleado </th>
			<th style='width:20%;'> Usuario </th>
			<th style='width:10%;'> Fecha  </th>
		</tr>
		</thead>
		<tbody>".$filas."</tbody>
		</table>
		</div>
		<div>
		</div>
		</body>
		</html>";

		$cuerpo = mb_convert_encoding($cuerpo, 'UTF-8', 'UTF-8');
		$file = "Reporte_logs.xls";
        $exel = $cabecera.$cuerpo.$pie;
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=$file");
        echo $exel;
?>
