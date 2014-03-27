<?php 
	include "lib/twitteroauth.php";
	include "connect_db.php";
	include "autoload.php";
	
	// Credentials for using the Twitter API
	$consumer = "Enter your consumer key";
	$consumersecret = "Enter your consumer secret";
	$accesstoken = "Enter your access token";
	$accesstokensecret = "Enter your access token secret";
	
	// Initiating the TwitteOAuth
	$twitter = new TwitterOAuth($consumer, $consumersecret, $accesstoken, $accesstokensecret);	
?>
