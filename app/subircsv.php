<?php
	$title =  "Subir Datos de personal";
	session_start();
	include("header.php");
?>
<script type="text/javascript">
$(document).ready(function() { 
	//if submit button is clicked
	 $('#uploadForm').submit(function(e) {	
		// if input[file] !empty
		if($('#userImage').val()) {
			// prevent form from refreshing page
			e.preventDefault();
			//$('#loader-icon').show();
			$(this).ajaxSubmit({ 
				target:   '#targetLayer', 
				beforeSubmit: function() {
				  $("#progress-bar").width('0%');
				},
				uploadProgress: function (event, position, total, percentComplete){	
					$("#progress-bar").width(percentComplete + '%');
					$("#progress-bar").html('<div id="progress-status">' + percentComplete +' %</div>')
					if (percentComplete == 100)
						$('#loader-icon').show();
				},
				success:function (){
					$('#loader-icon').hide();
				},
				resetForm: true 
			}); 
			return false; 
		}
	});
}); 

function mostrar(){
	//document.getElementById('Mensaje').InnerHTML = 'label text'; 
	$('#loader-icon').show();
	//document.add.submit();
}
</script>

	<div class="Content">
		<div class="frm">
			<div class="frmContent" style="background-color: white;">
				<div class="frmTitle" >
					<h2>Cargar Datos de personal</h2>
				</div>
				<!--Upload files to server-->
				<div class="frmFields" >
					<form id="uploadForm" action="upload.php" method="post">
					<div>
					<label>Presiona examinar para selecionar el archivo csv:</label>
					<p><input name="userImage" id="userImage" type="file" class="demoInputBox" /></p>
					</div>
					<div><p><input type="submit" id="btnSubmit" value="Subir" class="btnSubmit" /></p></div>
					<div id="progress-div"><div id="progress-bar"></div></div>
					<div id="targetLayer"></div>
					</form>
					
					<div id="loader-icon" style="display:none; width:100%; text-align:center;">
						<label id="Mensaje"><strong>Espere mientras cargan los registros ....</strong></label>
						<p><img src="<?php 	$icon = "LoaderIcon.gif"; echo $icon ; ?>" width="134px" height="100px" /></p>
					</div>

					<!---->
					<form id="uploadForm" action="prosessfile.php" method="post">
						<label><strong>Botones de funcionalidad...</strong></label>
						<p><input type="submit" id="procesar" onclick="mostrar(); return true;"  name="action" value="Procesar" class="btnSubmit" />
						<input type="submit" id="truncar" name="action"  value="Truncar" class="btnSubmit" /></p>
					</form>
					</div>
				</div>
			</div>
		</div>
<?php include('footer.php'); ?>