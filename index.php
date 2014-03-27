<?php include "top.php"; ?>
<html>
	<head>
		<meta charset="UTF-8" />
		<title>Group 11 - Carsideror</title>
		<link rel="stylesheet" type="text/css" href="css/style.css" media="screen" />
		<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
		<!-- Jquery function that will allow to have the functionality of clicking a button and assigning a value to a text field -->
		<script type="text/javascript">
			$(function() {
				$('.main .keyword').click(function() {
					var value = $(this).val();
					var input = $('#key_select');
					input.val(value);
					return false;
				});
			});
		</script>
	</head>
	<body>
		<?php
			include "header.php";
		?>		
		<div class="main">
			<form action ="first.php" method="GET">
				Pick a brand and see the results<br /><br />
				<!-- The options a user has -->
				<input type="button" class="keyword" name="Honda" value="HONDA" />
				<input type="button" class="keyword" name="Mercedes" value="MERCEDES" />
				<input type="button" class="keyword" name="BMW" value="BMW" />
				<input type="button" class="keyword" name="VOLVO" value="VOLVO" />
				<input type="button" class="keyword" name="audi" value="AUDI" />
				<input type="button" class="keyword" name="FORD" value="FORD" />
				<input type="button" class="keyword" name="CITROEN" value="CITROEN" />
				<br /><br />
				<!-- The field is read only so it can only take specific values -->
				<label><input id="key_select" name="key_select" type="text" size="11" readonly="readonly"/>
				<br /><input type="submit" class="send" name="submit" value="Search" /></label>
			</form>
		</div>
		<br />
		<br />
		<hr />
		<?php 
			include "footer.php";
		?>
	</body>
</html>
