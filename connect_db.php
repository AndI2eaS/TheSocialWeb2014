<?php
	// Set database connection
	mysql_connect('localhost','root','');
	mysql_select_db('thesocialweb');
  
	//query the database using utf-8 collation
	mysql_query("SET NAMES 'utf8'");
?>
