<?php 

	// Connect to database
	$conn = mysqli_connect('localhost', 'Renae', 'Qz2rt!@_=', "web_game");

	// Check connection
	if (!$conn)
	{
		echo 'Connection error: ' . mysqli_connect_error();
	}

 ?>