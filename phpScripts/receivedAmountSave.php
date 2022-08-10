<?php $conPrefix = '../';
include $conPrefix . 'includes/session.php';
date_default_timezone_set('Asia/Dhaka');
$toDay = (new DateTime())->format("Y-m-d H:i:s");
$toDate = (new DateTime())->format("Y-m-d");

    if($_POST['orderId']!=''){
        $loginID = $_SESSION['user'];
    	$id=$_POST['orderId'];
    	$recvAmount=$_POST['recvAmount'];
    	
    	$sql = "Update tbl_orders set received_amount='$recvAmount',rcv_date='$toDate',received_by='$loginID', lastUpdatedDate='$toDay', lastUpdatedBy='$loginID' where id='".$id."'";
    	if (mysqli_query($conn, $sql)) {
    		echo json_encode(array("statusCode"=>200));
    	} 
    	else {
    		echo json_encode(array("statusCode"=>201));
    	}
    	mysqli_close($conn);
	
    }
    if($_POST['oId2']!=''){
        $loginID = $_SESSION['user'];
    	$oId2=$_POST['oId2'];
    	$bkashid=$_POST['bkashid'];
    	$bkashAmount=$_POST['bkashAmount'];
    	
    	$sql = "Update tbl_orders set bkash_number='$bkashid',bkash_amount='$bkashAmount',bkash_rcv_date='$toDate', lastUpdatedDate='$toDay', lastUpdatedBy='$loginID' where id='".$oId2."'";
    	if (mysqli_query($conn, $sql)) {
    		echo json_encode(array("statusCode"=>200));
    	} 
    	else {
    		echo json_encode(array("statusCode"=>201));
    	}
    	mysqli_close($conn);
	
    }
	// Cancel Order

    if($_POST['action'] == "cancelOrder"){
        $loginID = $_SESSION['user'];
	    $id = $_POST['id'];
	    $sql = "UPDATE tbl_orders set status='Cancel',deletedDate='$toDay',deletedBy='$loginID',deleted='Yes' WHERE id='".$id."'";
            
        if($conn->query($sql)){
            echo json_encode('Success');     
        }
        else{
             echo json_encode('Error: '.$conn->error());
        }
       
	}
	
	// Status Change check to pending

    if($_POST['action'] == "changeCheckStatus"){
        $loginID = $_SESSION['user'];
	    $id = $_POST['id'];
	    $sql = "UPDATE tbl_orders set status='Pending',lastUpdatedDate='$toDay',lastUpdatedBy='$loginID' WHERE id='".$id."'";
            
        if($conn->query($sql)){
            $sql = "UPDATE tbl_order_details 
                    SET status='Pending',lastUpdatedDate='$toDay',lastUpdatedBy='$loginID' 
                    WHERE tbl_orders_id='".$id."'";
            $conn->query($sql);
            echo json_encode('Success');     
        }
        else{
             echo json_encode('Error: '.$conn->error());
        }
       
	}
	
	// Status Change Process to Check

    if($_POST['action'] == "changeProcessStatus"){
        $loginID = $_SESSION['user'];
	    $id = $_POST['id'];
	    $sql = "UPDATE tbl_orders set status='Checked',lastUpdatedDate='$toDay',lastUpdatedBy='$loginID' WHERE id='".$id."'";
            
        if($conn->query($sql)){
            echo json_encode('Success');     
        }
        else{
             echo json_encode('Error: '.$conn->error());
        }
       
	}
   
?>