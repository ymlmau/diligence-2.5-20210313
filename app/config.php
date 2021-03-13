<?php
function getDB(){

$server = 'localhost';
$user = 'root';
$pass = '';
$db = 'arcangel_app2';

try{
    $conn = mysqli_connect($server,$user,$pass,$db);

  }
catch(Exception $e){
    echo "Connection failed: " . $e->getMessage();
  }
  //echo "Connection successful.";
  return $conn;
}

function limpiar_acentos($palabra){
    
    $cambios = array("á" => "a", "é" => "e", "ó" => "o", "ú" => "u", "í" => "i", "ñ" => "n","Á" => "A","É" => "E","Í" => "I","Ó" => "O","Ú" => "U","Ñ" => "N");
    $palabra = strtr($palabra,$cambios);
    
    return $palabra;
}

function sin_acento($palabra){
    $cambiar = array("á","é","í","ó","ú","ñ","Á","É","Í","Ó","Ú","Ñ");
    $reemplazo = array("a","e","i","o","u","n","A","E","I","O","U","N");
    $palabra = str_replace($cambiar, $reemplazo, $palabra);
    return $palabra;
}

function toDate($fecha){
$mes = substr($fecha,0,2);
$ano= substr($fecha,2);
$fecha = date_create($ano . "-" . $mes . "-" . 01);
$fecha = date_format($fecha,'y/m');
return $fecha;
}

?>
