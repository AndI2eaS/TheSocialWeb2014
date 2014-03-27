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
		<div class="main">
			<?php
				// Assign a value to the label used to search tweets by GET
				$label = $_GET['key_select'];
							
				// Create a variable to use for sentiment analysis from the PHPInsight Sentiment API and classification of the tweets to categories
				$sentiment = new \PHPInsight\Sentiment();
					
				// If the submit button has been pressed
				if (isset($_GET['submit'])){
					// Initiate the twitter API Get/search tweets
					$tweets = $twitter->get('https://api.twitter.com/1.1/search/tweets.json?q=%23'.$_GET['key_select'].'&lang=en&result_type=mixed&count=100&include_retweets=false');
					
					// Check if there are values
					if(isset($tweets->statuses) && is_array($tweets->statuses)) {
						
						// Foreach of the values that we have do some checks, classify and prepare the values for clustering
						foreach($tweets->statuses as $tweet) {
							// Make the object a string/assign value to blank, to insert it into database
							if ($tweet->user->location != null){
								$value = (string) $tweet->user->location;
							}
							else{
								$value = "NULL";
							}
							// Some regular expression statements in order to clean the data and process them better
							if (preg_match("/[0-9]/",$value)){
								$value = "NULL";
							}
							else if (stripos($value, ',')){
								$value = substr($value, 0, stripos($value, ','));
							}
							else if (stripos($value, '-')){
								$value = substr($value, 0, stripos($value, '-'));
							}
							else if (stripos($value, 'and')){
								$value = substr($value, 0, stripos($value, 'and'));
							}
							else if (stripos($value, '_')){
								$value = substr($value, 0, stripos($value, '_'));
							}
							else if (stripos($value, '/')){
								$value = substr($value, 0, stripos($value, '/'));
							}
							else if (stripos($value, '&')){
								$value = substr($value, 0, stripos($value, '&'));
							}
							else if (strlen($value)>20){
								$value = "NULL";
							}
							else if (preg_match ("/^[a-zA-Z0-9 #%$@!]+$/", $value, $match)){
								$value = "NULL";
							}
							// Sentiment calculation and classification of the data
							$class = $sentiment->categorise($tweet->text);
							if ($class == "pos"){
								$class = "Positive";
							}
							else if ($class == "neg"){
								$class = "Negative";
							}
							else {
								$class = "Neutral";
							}
							
							// Insert the data into the database. Case: value location = NULL
							if ($value == "NULL") {
								$sql = "INSERT INTO results VALUES ('', '$tweet->text', $value, '$class', '$label')";
							}
							// Insert the data into the database. Case: value location = NOT NULL
							else{
								$sql = "INSERT INTO results VALUES ('', '$tweet->text', '$value', '$class', '$label')";
							}
							$result = mysql_query($sql);
						}
					}
					
					// Function via we change the values of the data by using Google API thus clustering the data from cities into countries
					function reverse_geocode($location) {
						$location = str_replace(" ", "+", "$location");
						$url = "http://maps.google.com/maps/api/geocode/json?address=$location&sensor=false";
						$result = file_get_contents("$url");
						$json = json_decode($result);
						$country = '';
						
						foreach ($json->results as $k => $result)
						{
							if ($k == 0) {
								foreach($result->address_components as $addressPart) {
									if ((in_array('locality', $addressPart->types)) && (in_array('political', $addressPart->types))) {
										$city = $addressPart->long_name;
									} else if((in_array('administrative_area_level_1', $addressPart->types)) && (in_array('political', $addressPart->types))) {
										$state = $addressPart->long_name;
									}
									else if((in_array('country', $addressPart->types)) && (in_array('political', $addressPart->types))) {
										$country = $addressPart->long_name;
									}
								}
							} else {
								break; //Will come out of loop directly after getting result.
							}
						}
						
						if($country != '')
							$location = $country;
						
						// Return the country of the given location
						return "$country";
					}
					// Get data from the database to analyze and change their values accordingly in order to make them available for clustering
					$first = "SELECT location, id FROM results WHERE location IS NOT NULL AND label='$label' ORDER BY id ASC LIMIT 80";
					$first_result = mysql_query($first);
					
					// Update the fields into the database and have data clustered by country
					while ($row2 = mysql_fetch_array($first_result)){
						// Run the function that will do the actual clustering
						$newLocation = reverse_geocode($row2['location']);
						// Update the database with the new values after clustering
						$second = "UPDATE results SET location = '$newLocation' WHERE label = '$label' AND id='" . $row2['id'] . "'";
						$second_result = mysql_query($second);
					}
					
				}
				// Some options for the user
				echo "<h3>Choose the visualization you prefer</h3><br />";
				
				echo "See tweets classified as <a href='positive_tweets.php?key_select=" . $_GET['key_select'] . "'>Positive</a> / <a href='negative_tweets.php?key_select=" . $_GET['key_select'] . "'>Negative</a> / <a href='neutral_tweets.php?key_select=" . $_GET['key_select'] . "'>Neutral</a> / <a href='all_tweets.php?key_select=" . $_GET['key_select'] . "'>All</a><br />";
				echo "<a href='bar.php?key_select=" . $_GET['key_select'] . "'>See sentiment by location</a><br />";
				echo "<a href='pie.php?key_select=" . $_GET['key_select'] . "'>Overall sentiment percentages</a><br />";
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
