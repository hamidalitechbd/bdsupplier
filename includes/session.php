<?php
	session_start();
	include $conPrefix.'includes/conn.php';
	$timezone = 'Asia/Dhaka';
	date_default_timezone_set($timezone);

	if(!isset($_SESSION['user']) || trim($_SESSION['user']) == ''){
		header('location: index.php');
	}

	$sql = "SELECT * FROM tbl_users WHERE id='".$_SESSION['user']."'";
	$query = $conn->query($sql);
	$user = $query->fetch_assoc();
    $toDay = (new DateTime())->format("Y-m-d H:i:s");
    $loginID = $_SESSION['user'];
    function ordersGoBackToPending(){
        global $conn;
        global $toDay;
        global $loginID;
        $sql1 = "UPDATE tbl_orders
                SET tbl_orders.status='Pending', lastUpdatedDate='$toDay', lastUpdatedBy='$loginID'
                WHERE tbl_orders.status <> 'Completed' AND tbl_orders.status<>'Cancel' AND DATE_FORMAT(tbl_orders.lastUpdatedDate, '%Y-%m-%d') <> DATE_FORMAT('$toDay', '%Y-%m-%d')";
        
        $conn->query($sql1);
        $sql = "UPDATE tbl_order_details
                SET tbl_order_details.status='Pending', lastUpdatedDate='$toDay', lastUpdatedBy='$loginID'
                WHERE tbl_order_details.status <> 'Completed' AND tbl_order_details.status <> 'Cancel' AND DATE_FORMAT(tbl_order_details.lastUpdatedDate, '%Y-%m-%d') <> DATE_FORMAT('$toDay', '%Y-%m-%d')";
        $conn->query($sql);
    }
	
?>