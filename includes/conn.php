<?php
	$conn = new mysqli('localhost', 'root', '','bdsupplier');

	if ($conn->connect_error) {
	    //die("Connection failed: " . $conn->connect_error);
	    die("This site is under construction.");
	}
?>