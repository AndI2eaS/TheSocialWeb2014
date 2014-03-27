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
			
			// Select the classified as negative tweets from the database for a specific chosen brand
			$query = "SELECT tweet, opinion, label FROM results WHERE label='$post' AND opinion='Negative' ORDER BY id DESC LIMIT 80";
			$result = mysql_query($query);
			echo "<table>";
			echo "<th>Tweets</th>";
			echo "<th>Opinion</th>";
			while ($row = mysql_fetch_array($result)){
				// Show the data(tweets) to the user
				echo "<tr>";
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
