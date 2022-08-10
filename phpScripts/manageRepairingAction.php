<?php
date_default_timezone_set('Asia/Dhaka');
$toDay = (new DateTime($test))->format("Y-m-d H:i:s");

$conPrefix = '../';
include $conPrefix . 'includes/session.php';
if(isset($_POST['action'])){
    if ($_POST['action'] == "saveRepair"){
        $loginID = $_SESSION['user'];
        $partyName = $_POST['partyName'];
        $referenceBy = $_POST['referenceBy'];
        $date = $_POST['date'];
        $repairDescription=$_POST['repairDescription'];
        $amount=$_POST['amount'];
        try{    
            $conn->begin_transaction();
            $sql = "SELECT LPAD(max(repairOrderNo)+1, 6, 0) as repairOrderNo from tbl_repairing_center";
    		$query = $conn->query($sql);
    		while ($prow = $query->fetch_assoc()) {
    			$repairOrderNo = $prow['repairOrderNo'];
    		}
    		if($repairOrderNo == ""){
    		    $repairOrderNo = "000001";
    		}
            $sql = "INSERT INTO tbl_repairing_center(tbl_partyid, description, date, repairOrderNo, amount, entry_by,entry_date,tbl_userid_reference_by) 
                    VALUES ('$partyName','$repairDescription','$date','$repairOrderNo','$amount','$loginID','$toDay','$referenceBy')";
            if($conn->query($sql)){
                $repairing_center_id = $conn->insert_id;
                if ($amount > 0){
                    $customerType = 'Party';
				    $sql = "SELECT LPAD(IFNULL(max(voucherNo),0)+1, 6, 0) as voucherCode FROM tbl_paymentVoucher WHERE tbl_partyId='$partyName' AND customerType = '$customerType'";
            		$query = $conn->query($sql);
            		while ($prow = $query->fetch_assoc()) {
            			$voucherNo = $prow['voucherCode'];
            		}
            		if($voucherNo == ""){
            		    $voucherNo = "000001";
            		}
                    
				    $sql = "INSERT INTO tbl_paymentVoucher (tbl_partyId, tbl_repairing_center_id, amount, entryBy, paymentMethod, paymentDate, status, remarks, type, voucherType, voucherNo, customerType,entryDate) 
							VALUES ('$partyName', '$repairing_center_id', '$amount', '$loginID', 'CASH', '$date', 'Active', 'Payable for Repairing Code: $repairOrderNo', 'partyPayable', 'Repair', '$voucherNo', '$customerType','$toDay')";
					$conn->query($sql);
					$conn->commit();
					echo json_encode('Success');
				}
            }else{
                $conn->rollBack();
                echo json_encode($conn->error.$sql);
            }
        }catch(Exception $e){
    		$conn->rollBack();
    		echo json_encode('RollBack');
    	}
        $conn->close();
    }
    else if ($_POST['action'] == "deleteRepair"){
        $loginID = $_SESSION['user'];
        $id = $_POST['id'];
        try{    
            $conn->begin_transaction();
            $sql = "UPDATE tbl_repairing_center
                    SET deleted = 'Yes', deleted_by='$loginID', deleted_Date=NOW()
                    WHERE id='$id'";
            if($conn->query($sql)){
               $sql = "UPDATE tbl_paymentVoucher 
                        set deleted='Yes', deletedBy='$loginID', deletedDate=NOW() 
                        WHERE tbl_repairing_center_id='$id' AND voucherType='Repair'";
                $conn->query($sql);
                $conn->commit();
                echo json_encode('Success');
            }else{
                $conn->rollBack();    
                echo json_encode("Error: ".$conn->error.$sql);
            }
            
        }catch(Exception $e){
    		$conn->rollBack();
    		//echo 'RollBack';
    		echo json_encode("Error: RollBack");
    	}
        $conn->close();
    }
}else{
    $sql = "SELECT tbl_repairing_center.id, tbl_repairing_center.repairOrderNo, tbl_party.partyName, tbl_party.tblCity, tbl_party.locationArea, tbl_party.partyPhone, tbl_repairing_center.description, tbl_repairing_center.amount, tbl_repairing_center.date
                FROM tbl_repairing_center
                LEFT OUTER JOIN tbl_party ON tbl_repairing_center.tbl_partyid = tbl_party.id AND tbl_party.deleted = 'No'
                WHERE tbl_repairing_center.deleted='No'  ORDER BY `tbl_repairing_center`.`id` DESC";
    $result = $conn->query($sql);
    $i=1;
    $output = array('data' => array());
    while ($row = $result->fetch_array()) {
        $id = $row['id'];
        
        $button = '	<div class="btn-group">
						<button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
						<i class="fa fa-gear tiny-icon"></i>  <span class="caret"></span></button>
						<ul class="dropdown-menu dropdown-menu-right" style="border: 1px solid gray;" role="menu">
							<li><a href="repairingViewInvoice.php?id='.$row['id'].'" target="_blank"><i class="fa fa-print tiny-icon"></i>View Details</a></li>';
		if(strtolower($_SESSION['userType']) == "admin" || strtolower($_SESSION['userType']) == 'super admin'){
		$button .=  '<li><a href="#" onclick="deleteRepair('.$id.')"><i class="fa fa-edit tiny-icon"></i>Delete</a></li>';
		   
		}
		$button .= '</ul></div>';
        $output['data'][] = array(
            $i++,
            $row['date'],
            $row['repairOrderNo'],
            $row['partyName'].' - '.$row['locationArea'].' - '.$row['tblCity'],
            $row['description'],
            $row['amount'],
            $button
        );
    } // /while 
    echo json_encode($output);
}
?>