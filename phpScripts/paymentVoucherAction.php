<?php
$conPrefix = '../';
include $conPrefix . 'includes/session.php';

date_default_timezone_set('Asia/Dhaka');
$toDay = (new DateTime($test))->format("Y-m-d H:i:s");

if(isset($_POST['action'])){
    if($_POST['action'] == "loadParty"){
        $partyType = $_POST['partyType'];
        $voucherType = $_POST['voucherType'];
       // echo $partyType . $voucherType;
		if($voucherType == 'paymentReceived' || $voucherType == 'discount'){
		    if($partyType == "PartySale"){
                $sql = "SELECT id, partyName, tblCity ,locationArea
                        FROM tbl_party 
                        WHERE partyType <> 'Suppliers' AND status = 'Active' AND deleted = 'No'
                        ORDER BY id DESC";
                $query = $conn->query($sql);
                echo "<option value=''>~~ Select Party ~~</option>";
				while ($prow = $query->fetch_assoc()) {
					echo "<option value='".$prow['id']."'>" . $prow['partyName'] . " - ".$prow['tblCity']." - ".$prow['locationArea']."</option>";
				}
            }else if ($partyType == "WalkinSale"){
                $sql = "SELECT id, customerName, phoneNo
                        FROM tbl_walkin_customer 
                        WHERE deleted = 'No' and status='Active'
                        ORDER BY id DESC";
                $query = $conn->query($sql);
                echo "<option value=''>~~ Select Walk-in Customer ~~</option>";
				while ($prow = $query->fetch_assoc()) {
					echo "<option value='" . $prow['id'] . "'>" . $prow['customerName'] . " - ".$prow['phoneNo']."</option>";
				}
            }    
        }else if($voucherType == 'payment'){
            $sql = "SELECT id, partyName, contactPerson, tblCity, locationArea 
                    FROM tbl_party
                    WHERE status = 'Active' AND deleted = 'No' AND tblType <> 'Customers'";
            $query = $conn->query($sql);
            echo "<option value=''>~~ Select Walk-in Customer ~~</option>";
			while ($prow = $query->fetch_assoc()) {
				echo "<option value='".$prow['id']."'>" . $prow['partyName'] . " - ".$prow['tblCity']." - ".$prow['locationArea']."</option>";
			}
        }else if($voucherType == 'adjustment'){
            if($partyType == "paymentReceived"){
                $sql = "SELECT id, partyName, contactPerson, tblCity, locationArea 
                    FROM tbl_party
                    WHERE status = 'Active' AND deleted = 'No' AND tblType <> 'Customers'";
                $query = $conn->query($sql);
                echo "<option value=''>~~ Select Walk-in Customer ~~</option>";
    			while ($prow = $query->fetch_assoc()) {
    				echo "<option value='".$prow['id']."'>" . $prow['partyName'] . " - ".$prow['tblCity']." - ".$prow['locationArea']."</option>";
    			}    
            }else if($partyType == "payment"){
                $sql = "SELECT id, partyName, contactPerson, tblCity, locationArea 
                        FROM tbl_party 
                        WHERE partyType <> 'Suppliers' AND status = 'Active' AND deleted = 'No'
                        ORDER BY id DESC";
                $query = $conn->query($sql);
                echo "<option value=''>~~ Select Party ~~</option>";
				while ($prow = $query->fetch_assoc()) {
					echo "<option value='".$prow['id']."'>" . $prow['partyName'] . " - ".$prow['tblCity']." - ".$prow['locationArea']."</option>";
				}
            }else if($partyType == "wipayment"){
                $sql = "SELECT id, customerName, phoneNo
                        FROM tbl_walkin_customer 
                        WHERE deleted = 'No' and status='Active'
                        ORDER BY id DESC";
                $query = $conn->query($sql);
                echo "<option value=''>~~ Select WI Customer ~~</option>";
				while ($prow = $query->fetch_assoc()) {
					echo "<option value='".$prow['id']."'>" . $prow['customerName'] . " - ".$prow['phoneNo']."</option>";
				}
            }
            /*if($partyType == "adjustment"){
                $sql = "SELECT id, partyName, contactPerson, tblCity, locationArea 
                    FROM tbl_party
                    WHERE status = 'Active' AND deleted = 'No' AND tblType <> 'Customers'";
                $query = $conn->query($sql);
                echo "<option value=''>~~ Select Walk-in Customer ~~</option>";
    			while ($prow = $query->fetch_assoc()) {
    				echo "<option value='".$prow['id']."'>" . $prow['partyName'] . " - ".$prow['tblCity']." - ".$prow['locationArea']."</option>";
    			}    
            }else if($partyType == "paymentAdjustment"){
                $sql = "SELECT id, partyName, contactPerson, tblCity, locationArea 
                        FROM tbl_party 
                        WHERE partyType <> 'Suppliers' AND status = 'Active' AND deleted = 'No'
                        ORDER BY id DESC";
                $query = $conn->query($sql);
                echo "<option value=''>~~ Select Party ~~</option>";
				while ($prow = $query->fetch_assoc()) {
					echo "<option value='".$prow['id']."'>" . $prow['partyName'] . " - ".$prow['tblCity']." - ".$prow['locationArea']."</option>";
				}
            }else{
                $sql = "SELECT id, customerName, phoneNo
                        FROM tbl_walkin_customer 
                        WHERE deleted = 'No' and status='Active'
                        ORDER BY id DESC";
                $query = $conn->query($sql);
                echo "<option value=''>~~ Select WI Customer ~~</option>";
				while ($prow = $query->fetch_assoc()) {
					echo "<option value='".$prow['id']."'>" . $prow['customerName'] . " - ".$prow['phoneNo']."</option>";
				}
            }*/
            /*if($partyType == "partyPayable"){
                $sql = "SELECT id, partyName, contactPerson, tblCity, locationArea 
                    FROM tbl_party
                    WHERE status = 'Active' AND deleted = 'No' AND tblType <> 'Customers'";
                $query = $conn->query($sql);
                echo "<option value=''>~~ Select Walk-in Customer ~~</option>";
    			while ($prow = $query->fetch_assoc()) {
    				echo "<option value='".$prow['id']."'>" . $prow['partyName'] . " - ".$prow['tblCity']." - ".$prow['locationArea']."</option>";
    			}    
            }else{
                $sql = "SELECT id, partyName, contactPerson, tblCity, locationArea 
                        FROM tbl_party 
                        WHERE partyType <> 'Suppliers' AND status = 'Active' AND deleted = 'No'
                        ORDER BY id DESC";
                $query = $conn->query($sql);
                echo "<option value=''>~~ Select Party ~~</option>";
				while ($prow = $query->fetch_assoc()) {
					echo "<option value='".$prow['id']."'>" . $prow['partyName'] . " - ".$prow['tblCity']." - ".$prow['locationArea']."</option>";
				}
            }*/
        }
    }
    else if($_POST['action'] == "loadPartyDue"){
        $partyType = $_POST['partyType'];
        $voucherType = $_POST['voucherType'];
        $partyId = $_POST['partyId'];
		if($voucherType == 'paymentReceived'){
		    if($partyType == "PartySale"){
                $customerType = 'Party';    
            }else if ($partyType == "WalkinSale"){
                $customerType = 'WalkinCustomer';
            }    
        }else if($voucherType == 'payment'){
            $customerType = 'Party';
        }else if($voucherType == 'adjustment'){
            //$customerType = 'Party';
            if($partyType == "adjustment"){
                $customerType = 'Party';    
            }else if ($partyType == "wipaymentAdjustment"){
                $customerType = 'WalkinCustomer';
            }   
        }else if($voucherType == 'discount'){
            if($partyType == "PartySale"){
                $customerType = 'Party';    
            }else if ($partyType == "WalkinSale"){
                $customerType = 'WalkinCustomer';
            }
        }
        $sql = "SELECT IFNULL(Sum(CASE tbl_paymentVoucher.type
                   WHEN 'partyPayable' THEN tbl_paymentVoucher.amount
                   WHEN 'paymentReceived' THEN -tbl_paymentVoucher.amount
                   WHEN 'adjustment' THEN  -tbl_paymentVoucher.amount
                   WHEN 'payable' THEN -tbl_paymentVoucher.amount
                   WHEN 'payment' THEN tbl_paymentVoucher.amount
                   WHEN 'paymentAdjustment' THEN tbl_paymentVoucher.amount
                   WHEN 'discount' THEN -tbl_paymentVoucher.amount
               END),0) AS total, voucher_book
                FROM tbl_paymentVoucher
                WHERE tbl_partyId = '".$partyId."' AND customerType = '".$customerType."'  AND  (CASE  WHEN paymentMethod = 'CHEQUE' THEN chequeIssueDate 
                                                                                   ELSE paymentDate
                                                                                   END) < '$toDay' AND deleted = 'No'
               GROUP BY voucher_book 
               ORDER BY voucher_book DESC";
        $query = $conn->query($sql);
        $previousDue = '';
        $book_limit = date("Y");
        $last_book = date("Y")-1;
        $first_book = $last_book;
        $totalDue = 0;
        $i = 0; 
		while ($prow = $query->fetch_assoc()) {
		    //if($i == 0){
		        $first_book = $prow['voucher_book'];      
		        //$i = 1;
		    //}
			$previousDue .= '<option value="'.$prow['voucher_book'].'">Book: '.$prow['voucher_book'].' -> Due: '.$prow['total'].'</option>';
			$last_book = $prow['voucher_book'];
			$totalDue += $prow['total'];
		}
		if($first_book > date("Y")){
		    $previousFirstDue = "";
		    for($i = date("Y"); $i < $first_book; $i++){
		        if($i == date("Y")){
		            $previousFirstDue .= '<option value="'.$i.'" Selected >Book: '.$i.' -> Due: 0</option>';
		        }else{
		            $previousFirstDue .= '<option value="'.$i.'">Book: '.$i.' -> Due: 0</option>';
		        }
		        //$previousDue .= '<option value="'.$i.'">Book: '.$i.' -> Due: 0</option>';
		    }
		    $previousDue = $previousFirstDue.$previousDue;
		}
		if($book_limit > $last_book){
		    for($i = $last_book+1; $i <= $book_limit; $i++){
		        $previousDue .= '<option value="'.$i.'" Selected >Book: '.$i.' -> Due: 0</option>';
		    }
		}
		echo json_encode(array(
            'previousDue' => $previousDue,
            'totalDue' =>    $totalDue
            ));
		//echo $previousDue;
    }
    else if($_POST['action'] == "saveVoucher"){
        $loginID = $_SESSION['user'];
        $partyName=$_POST['partyName'];
        $voucherDate = $_POST['voucherDate'];
        $amount=$_POST['amount'];
        $paymentMethod=$_POST['paymentMethod'];
        $accountNo=$_POST['accountNo'];
        $chequeNumber=$_POST['chequeNumber'];
        $chequeBank=$_POST['chequeBank'];
        //$depositBank=$_POST['depositBank'];
        $remarks=$_POST['remarks'];
        $transitDate=$_POST['transitDate'];
        $getVoucherType = $_POST["voucherType"];
        $book = $_POST['book'];
        if($getVoucherType == 'paymentReceived'){
            $voucherType = $_POST["entryVoucherType"];
        }else if($getVoucherType == 'payment'){
            $voucherType = 'Local Purchase';
        }else if($getVoucherType == 'adjustment'){
            $getVoucherType = $_POST["entryVoucherType"];
            if($getVoucherType == 'paymentReceived'){
                $getVoucherType='paymentReceived';
                $voucherType = 'PurchaseReturn';  
                $customerType = 'Party';
            }
            else if($getVoucherType== 'payment'){
                $getVoucherType='payment';
                $voucherType = 'SalesReturn';  
                $customerType = 'Party';
            }
            else if($getVoucherType== 'wipayment'){
                $getVoucherType='payment';
                $voucherType = 'SalesReturn';  
                $customerType = 'WalkinCustomer';
            }
            /*$getVoucherType = $_POST["entryVoucherType"];
            if($getVoucherType == 'wipaymentAdjustment'){
                $getVoucherType='paymentAdjustment';
                $voucherType = 'SalesReturn';  
                $customerType = 'WalkinCustomer';
            }
            else if($getVoucherType== 'adjustment'){
                 $voucherType = 'PurchaseReturn';
            }
            else{
                 $voucherType = 'SalesReturn';
            }*/
            //if($getVoucherType == 'partyPayable'){
              //  $voucherType = 'PurchaseReturn';
           // }else{
              //  $voucherType = 'SalesReturn';
           // }
        }else if ($getVoucherType == 'discount'){
            $voucherType = 'discount';  
        }
        
        if ($getVoucherType != 'discount' && $_POST["voucherType"] != 'adjustment'){
            if($voucherType == "WalkinSale"){
                $customerType = 'WalkinCustomer';
            }else if($voucherType == "PartySale" || $voucherType == "Local Purchase"){
                $customerType = 'Party';
            }
        }else if ($_POST["voucherType"] == 'adjustment'){
            
        }else{
            if($_POST["entryVoucherType"] == "PartySale"){
                $customerType = 'Party';
            }else{
                $customerType = 'WalkinCustomer';
            }
        }
        
	    $sql = "SELECT LPAD(IFNULL(max(voucherNo),0)+1, 6, 0) as voucherCode FROM tbl_paymentVoucher WHERE tbl_partyId='$partyName' AND customerType = '$customerType'";
		$query = $conn->query($sql);
		while ($prow = $query->fetch_assoc()) {
			$voucherNo = $prow['voucherCode'];
		}
		if($voucherNo == ""){
		    $voucherNo = "000001";
		}
        $sql = "INSERT INTO tbl_paymentVoucher(tbl_partyId, amount, entryBy, paymentMethod, chequeNo, paymentDate, chequeIssueDate, type, remarks, tbl_bankInfoId, voucherType, voucherNo, customerType, chequeBank,entryDate, voucher_book) 
                VALUES ('$partyName','$amount','$loginID','$paymentMethod','$chequeNumber','$voucherDate','$transitDate','$getVoucherType','Voucher Entry for party transaction $remarks','$accountNo','$voucherType','$voucherNo', '$customerType', '$chequeBank','$toDay', '$book')";
        if($conn->query($sql)){
            echo json_encode('Success');
        }else{
            echo json_encode('Error: '.$conn->error);
        }
    }
    else if($_POST['action'] == "saveBulkVoucher"){
        $errorMessage = '';
        $successMessage = 0;
        $loginID = $_SESSION['user'];
        $voucherDate = $_POST['voucherDate'];
        $paymentMethod=$_POST['paymentMethod'];
        $partyNamePOST=$_POST['partyName'];
        $partyNameArray = explode("@!@,",$partyNamePOST);
        $bookPOST=$_POST['book'];
        $bookArray = explode("@!@,",$bookPOST);
        $amountPOST=$_POST['amount'];
        $amountArray = explode("@!@,",$amountPOST);
        $accountNoPOST=$_POST['accountNo'];
        $accountNoArray = explode("@!@,",$accountNoPOST);
        $chequeNumberPOST=$_POST['chequeNumber'];
        $chequeNumberArray = explode("@!@,",$chequeNumberPOST);
        $chequeBankPOST=$_POST['chequeBank'];
        $chequeBankArray = explode("@!@,",$chequeBankPOST);
        //$depositBank=$_POST['depositBank'];
        $remarksPOST=$_POST['remarks'];
        $remarksArray = explode("@!@,",$remarksPOST);
        $transitDatePOST=$_POST['transitDate'];
        $transitDateArray = explode("@!@,",$transitDatePOST);
        $discountsPOST=$_POST['discounts'];
        $discountsArray = explode("@!@,",$discountsPOST);
        //$book = $_POST['book'];
        $getVoucherType = $_POST["voucherType"];
        if($getVoucherType == 'paymentReceived'){
            $voucherType = $_POST["entryVoucherType"];
        }else if($getVoucherType == 'payment'){
            $voucherType = 'Local Purchase';
        }else if($getVoucherType == 'adjustment'){
            $voucherType = $_POST["entryVoucherType"];
        }
        
        if($voucherType == "WalkinSale"){
            $customerType = 'WalkinCustomer';
        }else{
            $customerType = 'Party';
        }
        
        try{    
            $msg = "";
            $conn->begin_transaction();
            for($i = 0; $i < count($partyNameArray); $i++) {
				$partyName = $partyNameArray[$i];
				$book =$bookArray[$i];
				$amount =$amountArray[$i]; 
				$accountNo = $accountNoArray[$i];
				$chequeNumber = $chequeNumberArray[$i];
				$chequeBank = $chequeBankArray[$i];
				$remarks = $remarksArray[$i];
				$transitDate = $transitDateArray[$i];
				$discounts =$discountsArray[$i]; 
				if($i == count($partyNameArray)-1){
					$partyName = substr($partyName, 0, strlen($partyName)-3);
					$book = substr($book, 0,strlen($book)-3);
					$amount = substr($amount, 0,strlen($amount)-3);
					$accountNo = substr($accountNo, 0,strlen($accountNo)-3);
					$chequeNumber = substr($chequeNumber, 0,strlen($chequeNumber)-3);
					$chequeBank = substr($chequeBank, 0,strlen($chequeBank)-3);
					$remarks = substr($remarks, 0,strlen($remarks)-3);
					$transitDate = substr($transitDate, 0,strlen($transitDate)-3);
					$discounts = substr($discounts, 0,strlen($discounts)-3);
				}
				if($partyName != ''){
					$sql = "SELECT LPAD(IFNULL(max(voucherNo),0)+1, 6, 0) as voucherCode FROM tbl_paymentVoucher WHERE tbl_partyId='$partyName' AND customerType = '$customerType'";
					$msg .= $sql;
            		$query = $conn->query($sql);
            		while ($prow = $query->fetch_assoc()) {
            			$voucherNo = $prow['voucherCode'];
            		}
            		if($voucherNo == ""){
            		    $voucherNo = "000001";
            		}
                    $sql = "INSERT INTO tbl_paymentVoucher(tbl_partyId, amount, entryBy, paymentMethod, chequeNo, paymentDate, chequeIssueDate, type, remarks, tbl_bankInfoId, voucherType, voucherNo, customerType, chequeBank,entryDate, voucher_book) 
                            VALUES ('$partyName','$amount','$loginID','$paymentMethod','$chequeNumber','$voucherDate','$transitDate','$getVoucherType','Voucher Entry for party transaction $remarks','$accountNo','$voucherType','$voucherNo', '$customerType', '$chequeBank','$toDay', '$book')";
                    $msg .= $sql;
                    if($conn->query($sql)){
                        $conn->commit();
                        $successMessage++;
                    }else{
                        $errorMessage .= 'Error: '.$conn->error.$sql.'<br>';
                    }
                    if($discounts > 0){
                        $sql = "SELECT LPAD(IFNULL(max(voucherNo),0)+1, 6, 0) as voucherCode FROM tbl_paymentVoucher WHERE tbl_partyId='$partyName' AND customerType = '$customerType'";
    					$msg .= $sql;
                		$query = $conn->query($sql);
                		while ($prow = $query->fetch_assoc()) {
                			$voucherNo = $prow['voucherCode'];
                		}
                		if($voucherNo == ""){
                		    $voucherNo = "000001";
                		}
                        $sql = "INSERT INTO tbl_paymentVoucher(tbl_partyId, amount, entryBy, paymentMethod, paymentDate, type, remarks, voucherType, voucherNo, customerType,entryDate, voucher_book) 
                                VALUES ('$partyName','$discounts','$loginID','CASH','$voucherDate','discount','Voucher Entry for discount transaction $remarks','discount','$voucherNo', '$customerType','$toDay','$book')";
                        $msg .= $sql;
                        if($conn->query($sql)){
                            $conn->commit();
                            $successMessage++;
                        }else{
                            $errorMessage .= 'Error: '.$conn->error.$sql.'<br>';
                        }
                    }
				}
			}
            if($errorMessage == ''){
                $conn->commit();
                echo json_encode('Success');
            }else{
                $conn->rollBack();
                echo json_encode($errorMessage);
            }
        }catch(Exception $e){
    		$conn->rollBack();
    		echo 'RollBack';
    	}
        $conn->close();
    }
    else if($_POST['action'] == "deleteVoucher"){
        $loginID = $_SESSION['user'];
        $id = $_POST['id'];
        $sql = "UPDATE tbl_paymentVoucher 
                SET deleted = 'Yes', deletedBy='$loginID', deletedDate='$toDay'
                WHERE id='$id'";
        if($conn->query($sql)){
            echo json_encode('Success');
        }else{
            echo json_encode('Error: '.$conn->error.$sql);
        }
        
    }
}else if(isset($_GET['voucherType'])){
    $getVoucherType = $_GET['voucherType'];
    if($getVoucherType == 'paymentReceived'){
        if($_GET['sortData'] == "0,0"){
            $sql = "SELECT tbl_paymentVoucher.id ,tbl_paymentVoucher.tbl_partyId, tbl_party.partyName, tbl_walkin_customer.customerName, tbl_paymentVoucher.amount, tbl_paymentVoucher.paymentMethod, tbl_paymentVoucher.paymentDate, tbl_paymentVoucher.remarks, tbl_paymentVoucher.voucherType, tbl_paymentVoucher.chequeIssueDate, tbl_paymentVoucher.chequeNo, tbl_bank_account_info.accountNo, tbl_bank_account_info.accountName, tbl_bank_account_info.bankName, tbl_paymentVoucher.tbl_purchaseId, tbl_paymentVoucher.tbl_sales_id, tbl_sales_return_id, tbl_purchase_return_id, tbl_paymentVoucher.voucher_book
                    FROM tbl_paymentVoucher
                    LEFT OUTER JOIN tbl_party ON (tbl_paymentVoucher.tbl_partyId = tbl_party.id AND tbl_paymentVoucher.customerType = 'Party')
                    LEFT OUTER JOIN tbl_walkin_customer ON (tbl_paymentVoucher.tbl_partyId = tbl_walkin_customer.id AND tbl_paymentVoucher.customerType = 'WalkinCustomer')
                    LEFT OUTER JOIN tbl_bank_account_info ON tbl_paymentVoucher.tbl_bankInfoId = tbl_bank_account_info.id
                    WHERE tbl_paymentVoucher.status='Active' AND tbl_paymentVoucher.deleted='No' AND tbl_paymentVoucher.type = '$getVoucherType'
                    ORDER BY id DESC";
        } else {
            $dates = explode(",",$_GET['sortData']);
            $sql = "SELECT tbl_paymentVoucher.id ,tbl_paymentVoucher.tbl_partyId, tbl_party.partyName, tbl_walkin_customer.customerName, tbl_paymentVoucher.amount, tbl_paymentVoucher.paymentMethod, tbl_paymentVoucher.paymentDate, tbl_paymentVoucher.remarks, tbl_paymentVoucher.voucherType, tbl_paymentVoucher.chequeIssueDate, tbl_paymentVoucher.chequeNo, tbl_bank_account_info.accountNo, tbl_bank_account_info.accountName, tbl_bank_account_info.bankName, tbl_paymentVoucher.tbl_purchaseId, tbl_paymentVoucher.tbl_sales_id, tbl_sales_return_id, tbl_purchase_return_id, tbl_paymentVoucher.voucher_book
                    FROM tbl_paymentVoucher
                    LEFT OUTER JOIN tbl_party ON (tbl_paymentVoucher.tbl_partyId = tbl_party.id AND tbl_paymentVoucher.customerType = 'Party')
                    LEFT OUTER JOIN tbl_walkin_customer ON (tbl_paymentVoucher.tbl_partyId = tbl_walkin_customer.id AND tbl_paymentVoucher.customerType = 'WalkinCustomer')
                    LEFT OUTER JOIN tbl_bank_account_info ON tbl_paymentVoucher.tbl_bankInfoId = tbl_bank_account_info.id
                    WHERE tbl_paymentVoucher.status='Active' AND tbl_paymentVoucher.deleted='No' AND tbl_paymentVoucher.type = '$getVoucherType'  AND tbl_paymentVoucher.paymentDate BETWEEN '".$dates[0]."' AND '".$dates[1]."'
                    ORDER BY id DESC";
        }
    }else if($getVoucherType == 'payment'){
        if($_GET['sortData'] == "0,0"){
            $sql = "SELECT tbl_paymentVoucher.id ,tbl_paymentVoucher.tbl_partyId, tbl_party.partyName,  tbl_paymentVoucher.amount, tbl_paymentVoucher.paymentMethod, tbl_paymentVoucher.paymentDate, tbl_paymentVoucher.remarks, tbl_paymentVoucher.voucherType, tbl_paymentVoucher.chequeIssueDate, tbl_paymentVoucher.chequeNo, tbl_bank_account_info.accountNo, tbl_bank_account_info.accountName, tbl_bank_account_info.bankName, tbl_paymentVoucher.tbl_purchaseId, tbl_paymentVoucher.tbl_sales_id, tbl_sales_return_id, tbl_purchase_return_id, tbl_paymentVoucher.voucher_book
                    FROM tbl_paymentVoucher
                    LEFT OUTER JOIN tbl_party ON tbl_paymentVoucher.tbl_partyId = tbl_party.id and tbl_paymentVoucher.customerType='Party'
                    LEFT OUTER JOIN tbl_bank_account_info ON tbl_paymentVoucher.tbl_bankInfoId = tbl_bank_account_info.id
                    WHERE tbl_paymentVoucher.status='Active'  AND tbl_paymentVoucher.type = '$getVoucherType' AND tbl_paymentVoucher.deleted='No'
                    ORDER BY id DESC";
        } else {
        	$dates = explode(",",$_GET['sortData']);
        	$sql = "SELECT tbl_paymentVoucher.id ,tbl_paymentVoucher.tbl_partyId, tbl_party.partyName,  tbl_paymentVoucher.amount, tbl_paymentVoucher.paymentMethod, tbl_paymentVoucher.paymentDate, tbl_paymentVoucher.remarks, tbl_paymentVoucher.voucherType, tbl_paymentVoucher.chequeIssueDate, tbl_paymentVoucher.chequeNo, tbl_bank_account_info.accountNo, tbl_bank_account_info.accountName, tbl_bank_account_info.bankName, tbl_paymentVoucher.tbl_purchaseId, tbl_paymentVoucher.tbl_sales_id, tbl_sales_return_id, tbl_purchase_return_id, tbl_paymentVoucher.voucher_book
                    FROM tbl_paymentVoucher
                    LEFT OUTER JOIN tbl_party ON tbl_paymentVoucher.tbl_partyId = tbl_party.id and tbl_paymentVoucher.customerType='Party'
                    LEFT OUTER JOIN tbl_bank_account_info ON tbl_paymentVoucher.tbl_bankInfoId = tbl_bank_account_info.id
                    WHERE tbl_paymentVoucher.status='Active'  AND tbl_paymentVoucher.type = '$getVoucherType' AND tbl_paymentVoucher.deleted='No' AND tbl_paymentVoucher.paymentDate BETWEEN '".$dates[0]."' AND '".$dates[1]."'
                    ORDER BY id DESC";
        }
    }else if($getVoucherType == "adjustment"){
        if($_GET['sortData'] == "0,0"){
            $sql ="SELECT tbl_paymentVoucher.id,tbl_paymentVoucher.type ,tbl_paymentVoucher.tbl_partyId, tbl_party.partyName, tbl_walkin_customer.customerName, tbl_paymentVoucher.amount, tbl_paymentVoucher.paymentMethod, tbl_paymentVoucher.paymentDate, tbl_paymentVoucher.remarks, tbl_paymentVoucher.voucherType, tbl_paymentVoucher.chequeIssueDate, tbl_paymentVoucher.chequeNo, tbl_bank_account_info.accountNo, tbl_bank_account_info.accountName, tbl_bank_account_info.bankName, tbl_paymentVoucher.tbl_purchaseId, tbl_paymentVoucher.tbl_sales_id, tbl_sales_return_id, tbl_purchase_return_id, tbl_paymentVoucher.voucher_book
                FROM tbl_paymentVoucher
                LEFT OUTER JOIN tbl_party ON (tbl_paymentVoucher.tbl_partyId = tbl_party.id AND tbl_paymentVoucher.customerType = 'Party')
                LEFT OUTER JOIN tbl_walkin_customer ON (tbl_paymentVoucher.tbl_partyId = tbl_walkin_customer.id AND tbl_paymentVoucher.customerType = 'WalkinCustomer')
                LEFT OUTER JOIN tbl_bank_account_info ON tbl_paymentVoucher.tbl_bankInfoId = tbl_bank_account_info.id
                WHERE tbl_paymentVoucher.status='Active' AND (tbl_paymentVoucher.voucherType='SalesReturn' || tbl_paymentVoucher.voucherType='PurchaseReturn') AND tbl_paymentVoucher.deleted='No' AND (tbl_paymentVoucher.type = 'payable' OR tbl_paymentVoucher.type = 'partyPayable' OR tbl_paymentVoucher.type = 'adjustment' OR tbl_paymentVoucher.type = 'paymentAdjustment')
                ORDER BY id DESC";
        } else {
        	$dates = explode(",",$_GET['sortData']);
            $sql ="SELECT tbl_paymentVoucher.id,tbl_paymentVoucher.type ,tbl_paymentVoucher.tbl_partyId, tbl_party.partyName, tbl_walkin_customer.customerName, tbl_paymentVoucher.amount, tbl_paymentVoucher.paymentMethod, tbl_paymentVoucher.paymentDate, tbl_paymentVoucher.remarks, tbl_paymentVoucher.voucherType, tbl_paymentVoucher.chequeIssueDate, tbl_paymentVoucher.chequeNo, tbl_bank_account_info.accountNo, tbl_bank_account_info.accountName, tbl_bank_account_info.bankName, tbl_paymentVoucher.tbl_purchaseId, tbl_paymentVoucher.tbl_sales_id, tbl_sales_return_id, tbl_purchase_return_id, tbl_paymentVoucher.voucher_book
                    FROM tbl_paymentVoucher
                    LEFT OUTER JOIN tbl_party ON (tbl_paymentVoucher.tbl_partyId = tbl_party.id AND tbl_paymentVoucher.customerType = 'Party')
                    LEFT OUTER JOIN tbl_walkin_customer ON (tbl_paymentVoucher.tbl_partyId = tbl_walkin_customer.id AND tbl_paymentVoucher.customerType = 'WalkinCustomer')
                    LEFT OUTER JOIN tbl_bank_account_info ON tbl_paymentVoucher.tbl_bankInfoId = tbl_bank_account_info.id
                    WHERE tbl_paymentVoucher.status='Active' AND (tbl_paymentVoucher.voucherType='SalesReturn' || tbl_paymentVoucher.voucherType='PurchaseReturn') AND tbl_paymentVoucher.deleted='No' AND (tbl_paymentVoucher.type = 'payable' OR tbl_paymentVoucher.type = 'partyPayable' OR tbl_paymentVoucher.type = 'adjustment' OR tbl_paymentVoucher.type = 'paymentAdjustment') AND tbl_paymentVoucher.paymentDate BETWEEN '".$dates[0]."' AND '".$dates[1]."'
                    ORDER BY id DESC";
        }
    }else if ($getVoucherType == "discount"){
        if($_GET['sortData'] == "0,0"){
                $sql ="SELECT tbl_paymentVoucher.id,tbl_paymentVoucher.type ,tbl_paymentVoucher.tbl_partyId, tbl_party.partyName, tbl_walkin_customer.customerName, tbl_paymentVoucher.amount, tbl_paymentVoucher.paymentMethod, tbl_paymentVoucher.paymentDate, tbl_paymentVoucher.remarks, tbl_paymentVoucher.voucherType, tbl_paymentVoucher.chequeIssueDate, tbl_paymentVoucher.chequeNo, tbl_bank_account_info.accountNo, tbl_bank_account_info.accountName, tbl_bank_account_info.bankName, tbl_paymentVoucher.tbl_purchaseId, tbl_paymentVoucher.tbl_sales_id, tbl_sales_return_id, tbl_purchase_return_id, tbl_paymentVoucher.voucher_book
                    FROM tbl_paymentVoucher
                    LEFT OUTER JOIN tbl_party ON (tbl_paymentVoucher.tbl_partyId = tbl_party.id AND tbl_paymentVoucher.customerType = 'Party')
                    LEFT OUTER JOIN tbl_walkin_customer ON (tbl_paymentVoucher.tbl_partyId = tbl_walkin_customer.id AND tbl_paymentVoucher.customerType = 'WalkinCustomer')
                    LEFT OUTER JOIN tbl_bank_account_info ON tbl_paymentVoucher.tbl_bankInfoId = tbl_bank_account_info.id
                    WHERE tbl_paymentVoucher.status='Active' AND (tbl_paymentVoucher.voucherType='discount') AND tbl_paymentVoucher.deleted='No' AND (tbl_paymentVoucher.type = 'discount')
                    ORDER BY id DESC";
        } else {
        	$dates = explode(",",$_GET['sortData']);
            $sql ="SELECT tbl_paymentVoucher.id,tbl_paymentVoucher.type ,tbl_paymentVoucher.tbl_partyId, tbl_party.partyName, tbl_walkin_customer.customerName, tbl_paymentVoucher.amount, tbl_paymentVoucher.paymentMethod, tbl_paymentVoucher.paymentDate, tbl_paymentVoucher.remarks, tbl_paymentVoucher.voucherType, tbl_paymentVoucher.chequeIssueDate, tbl_paymentVoucher.chequeNo, tbl_bank_account_info.accountNo, tbl_bank_account_info.accountName, tbl_bank_account_info.bankName, tbl_paymentVoucher.tbl_purchaseId, tbl_paymentVoucher.tbl_sales_id, tbl_sales_return_id, tbl_purchase_return_id, tbl_paymentVoucher.voucher_book
                    FROM tbl_paymentVoucher
                    LEFT OUTER JOIN tbl_party ON (tbl_paymentVoucher.tbl_partyId = tbl_party.id AND tbl_paymentVoucher.customerType = 'Party')
                    LEFT OUTER JOIN tbl_walkin_customer ON (tbl_paymentVoucher.tbl_partyId = tbl_walkin_customer.id AND tbl_paymentVoucher.customerType = 'WalkinCustomer')
                    LEFT OUTER JOIN tbl_bank_account_info ON tbl_paymentVoucher.tbl_bankInfoId = tbl_bank_account_info.id
                    WHERE tbl_paymentVoucher.status='Active' AND (tbl_paymentVoucher.voucherType='discount') AND tbl_paymentVoucher.deleted='No' AND (tbl_paymentVoucher.type = 'discount') AND tbl_paymentVoucher.paymentDate BETWEEN '".$dates[0]."' AND '".$dates[1]."'
                    ORDER BY id DESC";
        }
    }
    $result = $conn->query($sql);
    $output = array('data' => array());
    if ($result->num_rows > 0) {
        $unitStatus = "";
        $i = 1;
        while ($row = $result->fetch_array()) {
            $paymentVoucherId = $row['id'];
            // active 
            if ($row['status'] == 'Active') {
                // activate status
                $unitStatus = "<label class='label label-success'>" . $row['status'] . "</label>";
            } else {
                // deactivate status
                $unitStatus = "<label class='label label-danger'>" . $row['status'] . "</label>";
            }
            if($getVoucherType == 'paymentReceived'){
                if($row['voucherType'] == "WalkinSale"){
                    $partyName = $row['customerName'];
                }else{
                    $partyName = $row['partyName'];
                }
            }else if($getVoucherType == 'payment'){
                $partyName = $row['partyName'];
            }    
            else{
                $partyName = $row['partyName'];    
            }
            $button = '	<div class="btn-group">
    						<button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
    						<i class="fa fa-gear tiny-icon"></i>  <span class="caret"></span></button>
    						<ul class="dropdown-menu dropdown-menu-right" style="border: 1px solid gray;" role="menu">
    							<li><a href="viewPaymentVoucher.php?id='.$row['id'].'&vtype='.$getVoucherType.'" target="_blank"><i class="fa fa-print tiny-icon"></i>View Voucher</a></li>';
    		if($row['tbl_sales_id'] == '0' && $row['tbl_purchaseId'] == '0' && $row['tbl_purchase_return_id'] == '0' && $row['tbl_sales_return_id'] == '0' && strtolower($_SESSION['userType']) == 'super admin' || strtolower($_SESSION['userType']) == 'admin support plus'){
    		    $button .=  '<li><a href="#" onclick="deleteVoucher('.$paymentVoucherId.')"><i class="fa fa-trash tiny-icon"></i>Delete</a></li>';
    		}
    		$button .= '</ul>
		            </div>';
            $output['data'][] = array(
                $i++,
                $row['paymentDate'],
                $row['partyName'].''.$row['customerName'].'<br>'.$row['voucher_book'],
                $row['paymentMethod'],
                $row['amount'],
                $row['chequeIssueDate'].' - '.$row['chequeNo'],
                $row['remarks'],
                $button
            );
        } // /while 
    }// if num_rows
    $conn->close();
    echo json_encode($output);
}

?>