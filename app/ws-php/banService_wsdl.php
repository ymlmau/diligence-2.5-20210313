<?php
/**
 *
 * @author Angel Tello v1.00
 * @author Yamel Mauge 
 *
 */
	require_once 'lib/nusoap.php';
	ini_set("soap.wsdl_cache_enabled", "0"); // deshabilitando cache WSDL
	 
	//$namespace = "http://triplespanama.com/services";
	 
	// Creando el nuevo Soap Server
	$server = new soap_server();

	// Configura our WSDL
	$server->configureWSDL("wsNotes","urn:wsNotes");
	$server->wsdl->schemaTargetNamespace = "urn:wsNotes";
	$server->soap_defencoding = 'UTF-8';
	$server->decode_utf8 = FALSE;
	$server->encode_utf8 = TRUE;
	
	$server->wsdl->addComplexType(
    	'registro', 
        'complexType',
        'struct',
        'all',
        '',
        array(
			'seguro' => array('name' => 'seguro', 'type' => 'xsd:string'),
			'cedula_pasaporte' => array('name' => 'cedula_pasaporte', 'type' => 'xsd:string'),
			'nombre' => array('name' => 'nombre', 'type' => 'xsd:string'),
			'razon_social' => array('name' => 'razon_social', 'type' => 'xsd:string'),
			'patrono' => array('name' => 'patrono', 'type' => 'xsd:string'),
			'ruc' => array('name'=> 'ruc', 'type' => 'xsd:string'),
			'direccion' => array('name'=> 'direccion', 'type' => 'xsd:string'),
			'telefono1' => array('name' => 'telefono1', 'type' => 'xsd:int'),
			'telefono2' => array('name' => 'telefono2', 'type' => 'xsd:int'),
			'fecha' => array('name' => 'fecha', 'type' => 'xsd:string'),
			'salario' => array('name' => 'salario', 'type' => 'xsd:decimal'),
			'promedio_salarial' => array('name' => 'promedio_salarial', 'type' => 'xsd:string'),
			'Seis_Meses_Mas' => array('name' => 'Seis_Meses_Mas',  'type' => 'xsd:string'),
			'Cantidad_Meses' => array('name' => 'Cantidad_Meses',  'type' => 'xsd:int'),
			'Historial' => array('name' => 'history',  'type' => 'xsd:string'),
			'Total_Empleados' => array('name' => 'Total_Empleados',  'type' => 'xsd:int')
			//'antiguedad' => array('name' => 'antiguedad', 'type' => 'xsd:string')
			
		)
    );
	
	$server->wsdl->addComplexType(
		'estructura', 
		'complexType', 
		'array', 
		'', 
		'SOAP-ENC:Array', 
		array(),
		array(
			array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:registro[]')
		), 
		'tns:registro'); 
			
	$server->register(
		// Nombre del Metodo:
		'GetPersonas', 		 
		// Lista de parametros:
		array( 
			'sw_tipo_busqueda' => 'xsd:int', //Obligatorio
		  	'cedula_provincia' => 'xsd:string', //No Obligatorio
		  	'cedula_inicial' => 'xsd:string', //No Obligatorio
			'cedula_tomo' => 'xsd:string', //No Obligatorio
		  	'cedula_asiento' => 'xsd:string', //No Obligatorio
		  	'pasaporte' => 'xsd:string', //No Obligatorio
		  	'persona_nombre' => 'xsd:string', //No Obligatorio
		  	'persona_seguro' => 'xsd:string', //No Obligatorio
		  	'ws_user' => 'xsd:string', //Obligatorio
		  	'ws_pass' => 'xsd:string' //Obligatorio
	 	), 
		// retorno de valores:
		array(
			'return' => 'tns:estructura'
		),
		'wsNotes', // namespace                      
		'urn:wsNotes', // accion SOAP
		'rpc', // estilo
		'encoded', // tipo de uso                                                  
		'El sistema cuenta con los siguientes parametros 
			sw_tipo_busqueda: indica si el tipo de busqueda 0 para búsqueda por nombre o seguro, 1 para busqueda por cedula, 2 para busqueda por pasaporte 
			cedula_provincia: 6-83-525 corresponde al primer numero de la cedula
			cedula_inicial: E-8-12345 corresponse a las iniciales en el primer segmento de la cédula 
			cedula_tomo: 6-83-525 corresponde al segundo numero de la cedula despues del guion
			cedula_asiento: 6-83-525 corresponde al tercer numero de la cedula despues del guion
			pasaporte: corresponde al pasaporte de la persona que se desea buscar
			persona_nombre: corresponde al nombre de la persona que se desea buscar
			persona_seguro: corresponde al numero de seguro social
			ws_user: usuario que cuenta para ingresar al sistema
			ws_pass: constraseña que cuenta para ingresar al sistema</br> 
			Buscar cliente por cedula en parametro enviar para sw_tipo_busqueda el numero 1 y provincia, inicial, tomo y asiento</br>
			Buscar cliente por pasaporte en parametro enviar para sw_tipo_busqueda el numero 2 y el pasaporte</br>
			Buscar cliente por nombre en parametro enviar para sw_tipo_busqueda el numero 0 y persona_nombre</br>
			Buscar cliente por seguro en parametro enviar para sw_tipo_busqueda el numero 0 y persona_seguro' // documentacion
	);

	//llamamos al método service de la clase nusoap 
	$server->service(file_get_contents("php://input"));
	exit();
?>