<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE-edge">
		<meta charset="utf-8">
		<title>Indexation</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<?php
			require '../back/select_step_entry.php';
		?>
		<script>
			var dataJson = <?php echo json_encode($result_data);?>
		</script>
	</head>
	<body>
		<div id="wrapper">
			<div id="header" class="container-fluid mb-0 p-0">
			</div>			

			<div id="navbar" class="container-fluid p-0">
				<nav class="navbar navbar-expand-lg navbar-dark bg-dark m-0 justify-content-around">
					<a href="index.php" class="nav-link">Accueil</a>
				</nav>
			</div>
		

			<div id="content" class="container d-flex flex-column">
				<div id="chartContainerGlobal" style="height: 300px; width: 100%;"></div>
				<div id="chartContainerLastThirtyOneDays" style="height: 300px; width: 100%; top: 300px"></div>
				<div id="chartContainerYear" style="height: 300px; width: 100%; top: 300px"></div>
				<script type="text/javascript" src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
				<script src="./js/tools.js"></script>
			</div>

			<div id="footer" class="container-fluid p-0 mt-auto"></div>
		</div>
	</body>
</html>