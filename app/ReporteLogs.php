session_start();
	if(!isset($_SESSION['Usuario']) && $_SESSION['Usuario'] == ''){
		header("location:index.php");
	}
	$title="Consulta por Cédula";
	include("header.php");
	include("config.php");
	?>
		<div class="Content">
			<div class="frm">
				<div class="frmContent">
					<div class="frmTitle"><h2>Reporte de logs</h2></div>
					<div class="frmFields" >
						<form method="post" action="ReporteLogs1.php">
							Fecha Inicial :<br />
							<input type="date"  name="finical" value="" required><br /> 
							Fecha Final :<br />
							<input type="date" name="ffinal" value="" required><br /> 
							<br />
							<input type="submit" value="Generar"><br /> 
						</form>
					</div>
				</div>
			</div>
		</div>
<?php
	include('footer.php');
?>
