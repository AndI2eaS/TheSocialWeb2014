<?php include "top.php"; ?>
<html>
	<head>
		<meta charset="UTF-8" />
		<title>Group 11 - Carsideror</title>
		<link rel="stylesheet" type="text/css" href="css/style.css" media="screen" />
	</head>
	<body>
		<?php
			include "header.php";
		?>		
		<div class="new_main">
			<?php
			$post = mysql_real_escape_string($_GET['key_select']);
			echo '<a href=first.php?key_select=' . $post . '>Back</a><br /><br />';
			
			// Select all the data from the database for a specific chosen brand
			$query = "SELECT DISTINCT(tweet), opinion, label FROM results WHERE label='$post' ORDER BY id DESC LIMIT 100";
			$result = mysql_query($query);
			echo "<table>";
			echo "<th>Tweets</th>";
			echo "<th>Opinion</th>";
			while ($row = mysql_fetch_array($result)){
				// Show the data (tweets) to the user
				if ($row['opinion'] == "Positive"){
					// If the tweet is classified to be positive make the field green
					echo "<tr bgcolor='#00FF00'>";
				}
				else if ($row['opinion'] == "Negative"){
					// If the tweet is classified to be negative make the field red
					echo "<tr bgcolor='#FF0000'>";
				}
				else {
					// If the tweet is classified to be neutral leave the field as is
					echo "<tr>";
				}
				echo "<td>" . $row['tweet'] . "</td>";
				echo "<td>" . $row['opinion'] . "</td>";
				echo "</tr>";
			}
			echo "</table>";
			?>
		</div>
		<br />
		<br />
		<hr />
		<?php 
			include "footer.php";
		?>
	</body>
</html>
