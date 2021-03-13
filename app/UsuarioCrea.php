
<?php
	session_start();
	$title= "Crear un usuario";
	include("header.php");
	include("config.php");
?>
		<div class="Content">
			<div class="frm">
				<div class="frmContent">
					<div class="frmTitle"><h2>Creación de Usuario</h2></div>
					<div class="frmFields" >
						<form method="post" action="UsuariosCrea.php">
							Nombre: <br />
							<input type="text"  name="Nombre" value="" required="required"><br /> 
							Usuario: <br />
							<input type="text" name="Usuario" value="" required="required"><br /> 
							Contraseña: <br />
							<input type="password" name="Contrasenia" value="" required="required"><br /> 
							Repetir Contraseña: <br />
							<input type="password" name="ReContraseña" value="" required="required"><br /> 
							<br />
							<input type="submit" value="Enviar"><br/> 
					</form>
				</div>
			</div>
		</div>
	</div>
	<?php
	include('footer.php');
?>
