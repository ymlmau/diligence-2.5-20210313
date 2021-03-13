<?php
/**
 *
 * @author Angel Tello 
 *
 */
require_once 'banService_wsdl.php';
 
//FUNCION DESTINADA A LA CONEXION CON LA BASE DE DATOS
function connection() {
    $mysqli = new mysqli("localhost", "root", "Vecino40Asur", "arcangel_app2", 3306);
    
    if ($mysqli->connect_error) 
     die("Error de Conexion (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
        
    return $mysqli;
}

//VALIDA SI EL USUARIO EXISTE
function auth($user, $pass) {
    $user = trim($user);
    $pass = trim($pass);

    $mysqli = connection();
    
    try {
        $query = "SELECT * FROM user WHERE usuario = ? AND contrasena = ?";
        $stmt = $mysqli->stmt_init();
        $stmt->prepare($query);
        $stmt->bind_param('ss', $user, $pass);
        $stmt->execute();
        $stmt->store_result();

        if($stmt->num_rows === 1) {
            $stmt->close();
            $mysqli->close();
            return TRUE;
        }
        $stmt->close();
        $mysqli->close();
        return FALSE;
    } catch(exception $e) {
        $stmt->close();
        $mysqli->close();
        return FALSE;
    }
}

//FUNCION QUE BUSCA EL LISTADO DE PERSONAS
function GetPersonas($sw_tipo_busqueda, $cedula_provincia, $cedula_inicial, $cedula_tomo, $cedula_asiento, $pasaporte, $persona_nombre, $persona_seguro, $ws_user, $ws_pass) {
	try {	
		//Validaciones
		if ($ws_user == "")
			throw new Exception('Debe enviar el usuario.');
		
		if ($ws_pass == "")
			throw new Exception('Debe enviar la contraseña.');

		if ($cedula_provincia == ""  && $cedula_inicial == "" && $cedula_tomo == "" && $cedula_asiento == "" && $pasaporte == "" && $persona_nombre == "" && $persona_seguro == "")
			throw new Exception('Debe enviar por lo menos un filtro.');
		
		if ($sw_tipo_busqueda == 1) {
			if (($cedula_provincia == "" && $cedula_inicial == "")) 
				throw new Exception('Debe enviar la cédula en formato correcto. La provincia y la inicial no puede estar vacías');

			if ($cedula_tomo == "")
				throw new Exception('Debe enviar la cédula en formato correcto. El tomo no puede estar vacío.');
			
			if ($cedula_asiento == "")
				throw new Exception('Debe enviar la cédula en formato correcto. El asiento no puede estar vacío');

			if ($cedula_inicial != "" && $cedula_inicial != "AV" && $cedula_inicial != "E" && $cedula_inicial != "N" && $cedula_inicial != "PE" && $cedula_inicial != "PI")
				throw new Exception('Debe enviar la cédula en formato correcto. La inicial no es válida.');
		
			if (($cedula_inicial == "AV" || $cedula_inicial == "PI") && $cedula_provincia == "") 
				throw new Exception('Debe enviar la cédula en formato correcto. Las cédulas AV y PI deben llevar provincia.');
			
			if (($cedula_inicial != "" && $cedula_inicial != "AV" && $cedula_inicial != "PI") && $cedula_provincia != "")
				throw new Exception('Debe enviar la cédula en formato correcto. Las cédulas E, N y PE no pueden llevar provincia.');
		}
			
		if ($sw_tipo_busqueda == 2 && $pasaporte == "") 
			throw new Exception('Debe enviar el pasaporte.');
		
		if(!auth($ws_user, $ws_pass)) {
			throw new Exception('Usuario o Clave incorrecta, favor verifique');
		}
		
		//$sqlFiltros = " select seguro, cedula_pasaporte, nombre, razon_social, patrono, telefono1, telefono2, fecha, salario from datos where 1=1 ";
		$sqlFiltros = "select * from all_data where 1=1";
		if ($sw_tipo_busqueda == 0) {
			if ($persona_nombre != "")
				$sqlFiltros .= " and nombre like '%".$persona_nombre."%'";
		
			if ($persona_seguro != "")
				$sqlFiltros .= " and seguro like '%".$persona_seguro."%'";
		} else if ($sw_tipo_busqueda == 1) {
			$sqlFiltros .= " and (cedula_pasaporte = '".$cedula_provincia.$cedula_inicial."-".$cedula_tomo."-".$cedula_asiento."' ";
			$sqlFiltros .= " or cedula_pasaporte = '".$cedula_provincia.$cedula_inicial.substr('00000'.$cedula_tomo, -5).substr('000000'.$cedula_asiento, -6)."') ";
		} else if($sw_tipo_busqueda == 2) {
			$sqlFiltros .= " and cedula_pasaporte like '%".$pasaporte."%' ";
		}
		
		$sqlFiltros .= " limit 1 ";
		
		$mysqli = connection();
		$result = $mysqli->query($sqlFiltros);
		
		$rows = array(); 
		

		while($row = $result->fetch_assoc()) {
			$cedula = $row['cedula_pasaporte'];
			$historial = "SELECT patrono, fecha, salario FROM historial WHERE cedula_pasaporte='$cedula' ORDER BY id_historial DESC LIMIT 24";
			$cn = mysqli_connect('localhost','root','Vecino40Asur','arcangel_APP2','3306');
			$previous = mysqli_query($cn,$historial);
			$previous_check = mysqli_num_rows($previous);
			$continuidad = 'No';
			if($previous_check>=6){
				$continuidad = 'Si';
			}
			$numempleados = "SELECT count(*) as total FROM datos WHERE patrono='" . $row['patrono'] . "'";
				$result3 = mysqli_query($cn,$numempleados);
			mysqli_data_seek($previous,0);
			$promedio = "SELECT (AVG(salario)) as promedio from historial WHERE patrono='". $row['patrono'] . "' AND cedula_pasaporte= '" . $row['cedula_pasaporte'] . "'";
			$result4 = mysqli_query($cn,$promedio);
			while($hdata= mysqli_fetch_array($previous)){

				$history[] = "\nFecha: ". $hdata['fecha'] . " Patrono: " . $hdata['patrono'] . " Salario: " . $hdata['salario'] . "|";
				}
				$row3 = mysqli_fetch_array($result3);
				$row4 = mysqli_fetch_array($result4);
		    $rows[] = array(
	    		"seguro" => $row['seguro'],
	    		"cedula_pasaporte" => $row['cedula_pasaporte'],
	    		"nombre" => $row['nombre'],
	    		"razon_social" => $row['razon_social'],
				"patrono" => $row['patrono'],
				"ruc" => $row['ruc'],
				"direccion" => $row['direccion'],
	    		"telefono1" => $row['telefono1'],
	    		"telefono2" => $row['telefono2'],
				"fecha" => $row['fecha'],
				"salario" => $row['salario'],
				"promedio_salarial" => $row4[0],
				"Seis_Meses_Mas" => $continuidad,
				"Cantidad_Meses" => $previous_check+3,
				"Historial" => implode("|", $history),
				"Total_Empleados" => $row3['total'],
				
			); 
		    $sql = "INSERT INTO sys_logs (log_modulo,log_descripcion,log_empreado,log_usuario,log_fecha) VALUES ('wsBanService.php', 'Se han consultado los datos del empleado con cedula: ".$row['cedula_pasaporte']."','".$row['nombre']."','".$ws_user."',NOW())";
			$mysqli->query($sql);
		}
		
		$result->close();
		
		//$sql = "insert into sys_logs (log_modulo, log_descripcion, log_empreado, log_usuario, log_fecha) values ('wsBanService.php', 'Consulta ejecutada: ".str_replace("'", "''", $sqlFiltros)." devolvio ".count($rows)." resultados.', 'S/N', '".$ws_user."', NOW())";
		//$mysqli->query($sql);
		
		$mysqli->close();
		
		return $rows;
		
	} catch(exception $e) {
		error(1200,$e->getMessage());
		return new soap_fault('-1', '', $e->getMessage(),'');	
	}
}

function error($numero, $texto) { 
	$ddf = fopen('error.log','a'); 
	fwrite($ddf,"[".date("r")."] Error $numero: $texto\r\n"); 
	fclose($ddf); 
}
?>