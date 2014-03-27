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
		$post = mysql_real_escape_string($_GET['key_select']);

		// Get data from the database
		$query = "SELECT opinion FROM results WHERE label='$post' ORDER BY id DESC LIMIT 80";
		$result = mysql_query($query);
		
		// Get the total amount of rows returned from the query
		$total = mysql_num_rows($result);
		
		// Initiate the arrays that are going to be used
		$positive = array();
		$negative = array();
		$neutral = array();
		
		// While I have results push data into the arrays depending on the value of the row classified by positive/negative/neutral
		while($row = mysql_fetch_array($result)){
			if ($row['opinion'] == 'Positive'){
				array_push($positive, $row['opinion']);
			}
			else if ($row['opinion'] == 'Negative'){
				array_push($negative, $row['opinion']);
			}
			else{
				array_push($neutral, $row['opinion']);
			}
		}
		
		// Calculate the percentages for the classified categories
		$pos_percentage = (count($positive)/$total)*100;
		$neg_percentage = (count($negative)/$total)*100;
		$neu_percentage = (count($neutral)/$total)*100;
		?>
		<!-- Script that will create the pie chart, using data from php embedded in javascript -->
		<script type="text/javascript">
		$(function () {
		$('#container').highcharts({
			chart: {
				plotBackgroundColor: null,
				plotBorderWidth: null,
				plotShadow: false
			},
			title: {
				text: 'Overall Sentiment Percentages'
			},
			tooltip: {
				pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
			},
			plotOptions: {
				pie: {
					allowPointSelect: true,
					cursor: 'pointer',
					dataLabels: {
						enabled: true,
						color: '#000000',
						connectorColor: '#000000',
						format: '<b>{point.name}</b>: {point.percentage:.1f} %'
					}
				}
			},
			series: [{
				type: 'pie',
				name: 'Percentage of tweets',
				colors: ['#FF0000', '#00FF00', '#C0C0C0'],
				data: [
					['Negative',   <?php echo $neg_percentage; ?>],
					{
						name: 'Positive',
						y: <?php echo $pos_percentage; ?>,
						sliced: true,
						selected: true
					},
					['Neutral',    <?php echo $neu_percentage; ?>]
				]
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
				// Create the back button to make it easier for the user to go back
				$post = mysql_real_escape_string($_GET['key_select']);
				echo '<a href=first.php?key_select=' . $post . '>Back</a><br /><br />';
			?>
		<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
		</div>
		<br /><br /><hr />
		<?php 
			include "footer.php";
		?>
	</body>
</html>
