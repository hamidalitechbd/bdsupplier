<?php
	session_start();
	include $conPrefix.'includes/conn.php';

	if(!isset($_SESSION['user']) || trim($_SESSION['user']) == ''){
		header('location: index.php');
	}

	$sql = "SELECT * FROM tbl_users WHERE id='".$_SESSION['user']."'";
	$query = $conn->query($sql);
	$user = $query->fetch_assoc();
	$timezone = 'Asia/Dhaka';
	date_default_timezone_set($timezone);
?>