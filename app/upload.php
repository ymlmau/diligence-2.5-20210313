<?php
//Send uploaded CSV file to files folder and rename it to datos.csv
	session_start();
	ini_set("memory_limit","2048M");
	set_time_limit(6000);

	if(!empty($_FILES)) {
		if(is_uploaded_file($_FILES['userImage']['tmp_name'])) {
			$sourcePath = $_FILES['userImage']['tmp_name'];
			$targetPath = "file/datos.csv";
			if(move_uploaded_file($sourcePath, $targetPath)) {
				echo ("<SCRIPT LANGUAGE='JavaScript'>$('#loader-icon').hide();</SCRIPT>");
				echo 'Se ha cargado el archivo con exito';
			} else {
				echo "No se ha subido el archivo! ";
			}
		}
	}
?>
