
<?php
	$servername = "localhost";
	$username = "chidsjwb";
	$password = "chidu4udev01";
	$dbname="chidu_base";
	
	// Create connection
	$conn = new mysqli($servername, $username, $password,$dbname);
	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
	else {
		mysqli_set_charset ( $conn , 'utf8' );
	}
?>