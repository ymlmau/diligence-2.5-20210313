<?php
	//echo '<li class="HeaderLi"><a href="consultanombre.php">Consultar Nombre</a></li>';
	echo '<li class="HeaderLi"><a lang="es" href="consultacedula.php">Consultar Cedula</a></li>';
	//echo '<li class="HeaderLi"><a lang="es" href="consultaseguro.php">Consultar Seguro</a></li>';


	if(isset($_SESSION['Nivel']) && $_SESSION['Nivel'] == "1")
	{
		echo '<div class="dropdown">';
			echo '<li class="HeaderLi"><a href="#" class="dropbtn ">Cargar CSVs</a></li>';
			echo '<div class="dropdown-content">';
				echo '<li class="HeaderLi"><a href="subircsv.php">Datos</a></li>';
				echo '<li class="HeaderLi"><a href="subirdireccion.php">Direcciones</a></li>';
				echo '<li class="HeaderLi"><a href="subirhistorial.php">Historial</a></li>';
				echo '<li class="HeaderLi"><a href="subirruc.php">Ruc</a></li>';
			echo '</div>';
		echo '</div>';
			echo '<li class="HeaderLi"><a href="UsuarioCrea.php">Usuarios</a></li>';
			echo '<li class="HeaderLi"><a href="ReporteLogs.php">Logs</a></li>';
			
				
	}

	echo '<li class="HeaderLi"><a lang="es" href="index.php?salir=true" >Salir</a></li>';

	function CedulaFormat($cedula)
	{
        return (strlen($cedula) == 13) ? substr($cedula, 0, 2)."-".substr($cedula, 2, 5)."-".substr($cedula, 7, 6) : $cedula;
	}
?>
