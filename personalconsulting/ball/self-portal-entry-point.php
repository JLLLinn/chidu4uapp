<?php
	if(isset($_GET['openid'])){
		$openid = $_GET['openid'];
		session_start();
		$_SESSION['openid'] = $openid;

	}
	header('Location: ./self-portal.php');
?>