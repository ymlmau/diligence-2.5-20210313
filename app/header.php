<?php  header('Content-type: text/html; charset=utf-8'); ?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title><?= $title; ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
	<link rel="stylesheet" href="css/style.css" type="text/css" />
	<link rel="stylesheet" href="css/dropdown.css" type="text/css"/>
    <link rel="stylesheet" href="css/subircsv.css">
    <script src="css/jquery-2.1.1.min.js" type="text/javascript"></script>
    <script src="js/jquery.form.min.js" type="text/javascript"></script>
</head>
<body>
<div class="d-header" >
		<div class="container">
			<div class="titleContent d-none d-xl-block">
				<a class="logomark" href="#" >
					<img src="image/image.png" border="0" width="80" height="50">
				</a>
				<div class="titleHeader" >Mollah Abogados</div>
			</div>
		</div>
		<div class="HeaderNavBar">
			<ul class="HeaderItems">
				<?php include('menu.php');?>
			</ul>
		</div>
	</div>

 