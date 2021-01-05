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
			window.onload = main;

			var dataJson = <?php echo json_encode($result_data);?>

			/**
			 * Change les clés en x et y
			 */
			dataJson = JSON.parse(JSON.stringify(dataJson).split('"step_count":').join('"y":'));
			dataJson = JSON.parse(JSON.stringify(dataJson).split('"date_creation":').join('"x":'));
			console.log(dataJson);
			
			/**
			 * Itère dans l'array JSON, inverse la date et créer un object Date()
			 */
			for(var object of dataJson) {
				console.log(object.x);
				object.x = object.x.split("/").reverse();
				object.x = new Date(object.x[0], object.x[1] - 1, object.x[2]);
			}
			

			var highestStepEntry = {y:0};

			for(var object of dataJson) {
				//console.log(object.y);
				if (object.y > highestStepEntry.y) {
					highestStepEntry = object;
					console.log(object);
				}
			}

			for (var object of dataJson) {
				if (object == highestStepEntry) {
					object.indexLabel = "Valeur la plus haute";
					object.markerColor = "red";
				}
			}

			// Moyenne
			var moyenne = 0;
			var arrayMoyenne = [];

			for (var object of dataJson) {
				moyenne += object.y;
			}
			moyenne /= dataJson.length;

			for (var object of dataJson) {
				var newObj = new Object();
				newObj.x = object.x;
				newObj.y = moyenne;
				arrayMoyenne.push(newObj);
			}

			// 31 dernières journées
			var today = new Date();	
			var currentDate = new Date (today.getFullYear(), today.getMonth(), today.getDate());
			var lastThirtyOneDayDate = new Date (today.getFullYear(), today.getMonth()-1, today.getDate());
			

			// Array des 31 derniers jours
			var arrayMoyenneLastThirtyOneDay = [];
			var moyenne = 0;

			for (var object of dataJson) {
				if (object.x.getMonth() >= lastThirtyOneDayDate.getMonth() || (object.x.getMonth() == 0 && lastThirtyOneDayDate.getMonth() == 11)) {
					var newObj = new Object();
					newObj.x = object.x;
					newObj.y = object.y;
					arrayMoyenneLastThirtyOneDay.push(newObj);
					moyenne += object.y;
				}
			}

			// Moyenne des 31 derniers jours
			moyenne /= arrayMoyenneLastThirtyOneDay.length;

			for (var object of arrayMoyenneLastThirtyOneDay) {
				object.y = moyenne;
			}

			function chartGlobal () {

				var chart = new CanvasJS.Chart("chartContainerGlobal", {
					animationEnabled: true,
					title: {
						text: "Nombre de pas par jour"
					},
					axisX: {
						title: "Jour",
						//minimum: new Date(2015, 01, 25),
						//maximum: new Date(2017, 02, 15),
						valueFormatString: "D/MM/YYYY"
					},
					axisY: {
						title: "Nombre de pas",
						titleFontColor: "#4F81BC",
						includeZero: true,
						suffix: " pas"
					},
					toolTip: {
						shared: true
					},
					data: [
						{
							indexLabelFontColor: "darkSlateGray",
							name: "Moyenne",
							type: "area",
							color: "rgba(165, 223, 0, 0.7)",
							xValueFormatString: "D/MM/YYYY",
							yValueFormatString: "## ### ### pas",
							dataPoints: arrayMoyenne
						},
						{
						indexLabelFontColor: "darkSlateGray",
						name: "Nombre de pas par jour",
						type: "area",
						color: "rgba(0,75,141,0.7)",
						xValueFormatString: "D/MM/YYYY",
						yValueFormatString: "## ### ### pas",
						dataPoints: /*[
							{ x: new Date(2015, 02, 1), y: 74.4, label: "Q1-2015" },
							{ x: new Date(2015, 05, 1), y: 61.1, label: "Q2-2015" },
							{ x: new Date(2015, 08, 1), y: 47.0, label: "Q3-2015" },
							{ x: new Date(2015, 11, 1), y: 48.0, label: "Q4-2015" },
							{ x: new Date(2016, 02, 1), y: 74.8, label: "Q1-2016" },
							{ x: new Date(2016, 05, 1), y: 51.1, label: "Q2-2016" },
							{ x: new Date(2016, 08, 1), y: 40.4, label: "Q3-2016" },
							{ x: new Date(2016, 11, 1), y: 45.5, label: "Q4-2016" },
							{ x: new Date(2017, 02, 1), y: 78.3, label: "Q1-2017", indexLabel: "Highest", markerColor: "red" }
						]*/
						dataJson
					}]
				});
				chart.render();

			}

			function chartLastThirtyOneDays () {

				var chart = new CanvasJS.Chart("chartContainerLastThirtyOneDays", {
					animationEnabled: true,
					title: {
						text: "Nombre de pas par jour des 31 derniers jours"
					},
					axisX: {
						title: "Jour",
						minimum: lastThirtyOneDayDate,
						maxi1um: currentDate,
						valueFormatString: "D/MM/YYYY"
					},
					axisY: {
						title: "Nombre de pas",
						titleFontColor: "#4F81BC",
						includeZero: true,
						suffix: " pas"
					},
					toolTip: {
						shared: true
					},
					data: [
					{
						indexLabelFontColor: "darkSlateGray",
						name: "Moyenne",
						type: "area",
						color: "rgba(165, 223, 0, 0.7)",
						xValueFormatString: "D/MM/YYYY",
						yValueFormatString: "## ### ### pas",
						dataPoints: arrayMoyenneLastThirtyOneDay
					},
					{
						indexLabelFontColor: "darkSlateGray",
						name: "Nombre de pas par jour des 31 derniers jours",
						type: "area",
						color: "rgba(223, 0, 0, 0.7)",
						xValueFormatString: "D/MM/YYYY",
						yValueFormatString: "## ### ### pas",
						dataPoints: dataJson
					}
					]
				});
				chart.render();

			}

			function main() {
				chartGlobal();
				chartLastThirtyOneDays();
			}

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
				<script type="text/javascript" src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
			</div>

			<div id="footer" class="container-fluid p-0 mt-auto"></div>
		</div>
	</body>
</html>