<?php
	session_start();
	include("config.php");

	$Nombre = $_POST['Nombre'];
	$Usuario = $_POST['Usuario'];
	$Contrasenia = $_POST['Contrasenia'];
	$ReContraseña = $_POST['ReContraseña'];

	if($Nombre == "")
		echo ("<SCRIPT LANGUAGE='JavaScript'>window.alert('Debe ingresar Nonmbre.');window.location.href='UsuariosCrea.html';</SCRIPT>");
	elseif($Usuario == "" )
		echo ("<SCRIPT LANGUAGE='JavaScript'>window.alert('Debe ingresar el Usuario.');window.location.href='UsuariosCrea.html';</SCRIPT>");
	elseif($Contrasenia == "" )
		echo ("<SCRIPT LANGUAGE='JavaScript'>window.alert('Debe ingresar la contrasena.');window.location.href='UsuariosCrea.html';</SCRIPT>");
	elseif($ReContraseña == "" )
		echo ("<SCRIPT LANGUAGE='JavaScript'>window.alert('Debe volver a ingresar la constraseña.');window.location.href='UsuariosCrea.html';</SCRIPT>");
	elseif($Contrasenia != $ReContraseña )
		echo ("<SCRIPT LANGUAGE='JavaScript'>window.alert('Las contraseña no coinciden');window.location.href='UsuariosCrea.html';</SCRIPT>");
	else {
		$mysqli = getDB();

		$sql = "SELECT usuario FROM user WHERE usuario = '".$Usuario."'";
		if ($result = $mysqli->query($sql)) {
			$row_cnt = $result->num_rows;
			if ($row_cnt != 0) {
				echo ("<SCRIPT LANGUAGE='JavaScript'>window.alert('Este usuario ya existe favor intente con otro');window.location.href='UsuariosCrea.html';</SCRIPT>");
			} else {
				$sql = "INSERT INTO user ( usuario, Nombre, contrasena, nivel) Values('" . $Usuario  . "','" . $Nombre . "','" .	$Contrasenia . "',0)";
				if ($result = $mysqli->query($sql)) {
					echo ("<SCRIPT LANGUAGE='JavaScript'>window.alert('Se ha insertado el usuario con exito.');window.location.href='UsuariosCrea.html';</SCRIPT>");
					$sql = "INSERT INTO sys_logs (log_modulo,log_descripcion,log_usuario,log_fecha) VALUES ('UsuariosCrea.php','Se ha creado el usuario: " . $Usuario ."','".$_SESSION['Usuario']."',NOW())";
					$mysqli->query($sql);
				}
			}
		}
	}
?>
