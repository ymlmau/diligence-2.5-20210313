<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>CSV Loader</title>
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
  </head>
  <body>

  <div class="d-header" >
		<div class="container">
			<div class="titleContent">
				<a class="logomark" href="#" >
					<img src="image/image.png" border="0" width="80" height="50">
				</a>
				<div class="titleHeader" >MOLLAH, MORGAN ABOGADOS</div>
			</div>
		</div>
		<div class="HeaderNavBar">
			<ul class="HeaderItems">
				<?php include('menu.php');?>
			</ul>
		</div>
	</div>
	<div class="Content">
		<div class="frm">
			<div class="frmContent">
				<div class="frmTitle" >
					<h2>Importar RUC</h2>
				</div>

     <div class="container">

      <div class="row mt-5 ml-5">
        <form class="form-horizontal" action="functions.php" method="post" enctype="multipart/form-data" name="upload_ruc">
          <fieldset>
            <legend>Importar RUC</legend>
            <hr>
          <div class="form-group">
            <label  for="ruc_file">Selecciona archivo con RUC</label>
            <input type="file" name="ruc_file" id="ruc_file" class="form-control-file ">
          </div>

          <div class="form-group">
            <div class="">
              <button type="submit" name="import" class="btn btn-danger" value="ruc">Importar RUC</button>
            </div>
          </div>
          </fieldset>
        </form>
      </div>
    </div>

    <!-- JS, Popper.js, and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
  </body>
</html>
