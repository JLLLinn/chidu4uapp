<?php
	if(isset($_GET['openid'])){
		$openid = $_GET['openid'];
	}else {
		$openid = "";
	}
	session_start();
	$_SESSION['openid'] = $openid;
	header('Location: ./index.php');
?>