<?php
include "connect_db.php";
?>
<html>
	<head>
		<meta charset="UTF-8" />
		<title>Group 11 - Carsideror</title>
		<link rel="stylesheet" type="text/css" href="css/style.css" media="screen" />
		<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
		<script src="http://code.highcharts.com/highcharts.js"></script>
		<script src="http://code.highcharts.com/modules/exporting.js"></script>
		
		<?php
		//get database data
		$post = mysql_real_escape_string($_GET['key_select']);
		
		$query = "SELECT location, id, sum(opinion = 'Positive') as positive, sum(opinion = 'Negative') as negative, sum(opinion = 'Neutral') as neutral FROM results WHERE location IS NOT NULL AND label = '$post' GROUP BY location ORDER BY id ASC LIMIT 80";
		$result = mysql_query($query);

		//assign to arrays
		$datax = array();
		$datay=array();
		$datay1=array();
		$datay2=array();

		while ($row = mysql_fetch_array($result)){
			array_push($datax, $row['location']);
			array_push($datay, $row['positive']);
			array_push($datay1, $row['negative']);
			array_push($datay2, $row['neutral']);
		}
		?>
		<script type="text/javascript">
		$(function () {
				$('#container').highcharts({
					chart: {
						type: 'bar'
					},
					title: {
						text: 'Sentiment Results Clustered by Country'
					},
					subtitle: {
						text: 'Source: twitter.com'
					},
					xAxis: {
						categories: [<?php foreach ($datax as $x){ echo "'" . $x ."',"; } ?>],
						title: {
							text: null
						}
					},
					yAxis: {
						min: 0,
						title: {
							text: 'Number of tweets',
							align: 'high'
						},
						labels: {
							overflow: 'justify'
						}
					},
					tooltip: {
						valueSuffix: ' tweets'
					},
					plotOptions: {
						bar: {
							dataLabels: {
								enabled: true
							}
						}
					},
					legend: {
						layout: 'vertical',
						align: 'right',
						verticalAlign: 'top',
						x: -40,
						y: -10,
						floating: true,
						borderWidth: 1,
						backgroundColor: '#FFFFFF',
						shadow: true
					},
					credits: {
						enabled: false
					},
					series: [{
						name: 'Positive',
						data: [<?php foreach ($datay as $y){ echo $y. ','; } ?>],
						color: '#00FF00'
					}, {
						name: 'Negative',
						data: [<?php foreach ($datay1 as $y1){ echo $y1. ','; } ?>],
						color: '#FF0000'
					}, {
						name: 'Neutral',
						data: [<?php foreach ($datay2 as $y2){ echo $y2. ','; } ?>],
						color: '#C0C0C0'
					}]
				});
			});
		</script>
	</head>
	<body>
		<?php
			include "header.php";
		?>		
		<div class="main">
			<?php
				$post = mysql_real_escape_string($_GET['key_select']);
				echo '<a href=first.php?key_select=' . $post . '>Back</a><br /><br />';
			?>
			<div id="container" style="min-width: 200px; min-height: 600px; margin: 0 auto"></div>
		</div>
		<br />
		<br />
		<hr />
		<?php 
			include "footer.php";
		?>
	</body>
</html>
