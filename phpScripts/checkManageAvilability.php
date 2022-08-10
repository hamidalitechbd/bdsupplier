<?php
$conPrefix = '../';
include $conPrefix . 'includes/session.php';
	if (!empty($_GET['page'])) {
        $type = $_GET['page'];
        $name = $_GET['name'];
        if ($type == "Unit") {
            if(isset($_GET['id'])){
                $id = $_GET['id'];
                $sql = "SELECT id FROM tbl_units where deleted='No' and unitName='$name' AND id <> '$id'";
            }else{
                $sql = "SELECT id FROM tbl_units where deleted='No' and unitName='$name'";
            }
        } else if ($type == "Brand") {
            if(isset($_GET['id'])){
                $sql = "SELECT id FROM tbl_brands where deleted='No' and brandName='$name' AND id <> '$id'";
            }else{
                $sql = "SELECT id FROM tbl_brands where deleted='No' and brandName='$name'";    
            }
            
        } else if ($type == "Warehouse") {
            if(isset($_GET['id'])){
                $id = $_GET['id'];
                $sql = "SELECT id FROM tbl_warehouse where deleted='No' and wareHouseName='$name' AND id <> '$id'";
            }else{
                $sql = "SELECT id FROM tbl_warehouse where deleted='No' and wareHouseName='$name'";
            }
        } else if ($type == "Category") {
            if(isset($_GET['id'])){
                $id = $_GET['id'];
                $sql = "SELECT id FROM tbl_category where deleted='No' and categoryName='$name' AND id <> '$id'";
            }else{
                $sql = "SELECT id FROM tbl_category where deleted='No' and categoryName='$name'";
            }
        } else if ($type == "PaymentMethod") {
            if(isset($_GET['id'])){
                $id = $_GET['id'];
                $sql = "SELECT id FROM tbl_paymentMethod where deleted='No' and methodName='$name' AND id <> '$id'";
            }else{
                $sql = "SELECT id FROM tbl_paymentMethod where deleted='No' and methodName='$name'";
            }
        } else if ($type == "User Type") {
            if(isset($_GET['id'])){
                $id = $_GET['id'];
                $sql = "SELECT id FROM tbl_accountType where deleted='No' and accountType='$name' AND id <> '$id'";
            }else{
                $sql = "SELECT id FROM tbl_accountType where deleted='No' and accountType='$name'";
            }
        }
        $user_count = $conn->query($sql);
        $row = $user_count->fetch_assoc();
        if($row>0) {
            echo "Already used";
        }else{
        	  echo "Available";
        }
    }
	
?>