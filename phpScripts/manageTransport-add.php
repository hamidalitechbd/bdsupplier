<?php
$conPrefix = '../';
include $conPrefix . 'includes/session.php';
date_default_timezone_set('Asia/Dhaka');
$toDay = (new DateTime($test))->format("Y-m-d H:i:s");

    if (isset($_POST['updateTransportInfo'])) {
    $challanCode = $_POST['challanCode'];
        $Tid = $_POST['Tid'];
        $transportDate = $_POST['transportDate'];
        $transportChallanNo = $_POST['transportChallanNo'];
        $sql = "UPDATE `tbl_challan` SET tbl_transportinfo_id='$Tid',transport_challan_no='$transportChallanNo',transport_date='$transportDate' WHERE id = '$challanCode'";
	    if($conn->query($sql)){
            echo json_encode('Success');	        
	    }else{
            echo json_encode($conn->error);		        
	    }
    
}
?>    