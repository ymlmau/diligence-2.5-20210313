<?php
	session_start();
	if(isset($_GET['salir']) && $_GET['salir'] ==  'true'){
		$_SESSION['Usuario'] ='';
		session_destroy();
	}
	if(isset($_SESSION['Usuario']) && $_SESSION['Usuario'] != ''){
		header("location:consultacedula.php");
	}
	$title="Login";
	include("config.php");
	
if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
		$usuario = $_POST['usuario'];
		$pass = $_POST['pass'];
		if($usuario == "") {
			echo '<script language="javascript">alert("Introduzca el usuario");</script>';
		} elseif($pass == "" ) {
			echo '<script language="javascript">alert("introduzca la contraseña");</script>';
		} else {
			$mysqli = getDB();
			$sql= "SELECT usuario, nivel FROM user WHERE usuario = '".$usuario."' and contrasena = '".$pass."'";
			if ($result = $mysqli->query($sql)) {
				$row_cnt = $result->num_rows;

				if ($row_cnt == 0)
					echo '<script language="javascript">alert("Usuario o contraseña incorrecta");</script>';
				else {
					while ($row = mysqli_fetch_row($result)) {
						$_SESSION['Usuario'] = $row[0];
						$_SESSION['Nivel'] = $row[1];

						$mysqli->query("INSERT INTO sys_logs (log_modulo,log_descripcion,log_usuario,log_fecha) VALUES ('index.php','Ha ingresado al sistema el usuario: ".$_SESSION['Usuario']." y tiene nivel:  ".$_SESSION['Nivel']."','".$_SESSION['Usuario']."',NOW())");

						header("location: consultacedula.php");
					}
				}
				$result->close();
			}
			$mysqli->close();
		}
	}
?>
<html lang="es">
    <head>
    	<link rel="stylesheet" href="css/login.css" type="text/css"></link>
    	<link rel="stylesheet" href="css/login.js" type="text/css"></link>
    </head>
    <body>
        <div class="login-page">
            <div class="form">
                <form method="post" name="principal" class="login-form">
                    <input type="text" name="usuario" placeholder="Usuario" />
                    <input type="password" name="pass" placeholder="Contraseña" />
                    <button>Ingresar</button>
                </form>
            </div>
        </div>
    </body>
</html>
