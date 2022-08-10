<?php
$conPrefix = '../';
include $conPrefix . 'includes/session.php';

date_default_timezone_set('Asia/Dhaka');
$toDay = (new DateTime($test))->format("Y-m-d H:i:s");

// add customer
if (isset($_POST['saveForeignPurchaseProducts'])) {
    $loginID = $_SESSION['user'];
	$unitPrice = $_POST['unitPrice'];
    $quantity = $_POST['quantity'];
    $walkInCustomerPrice = $_POST['walkInCustomerPrice'];
    $wholeSalePrice = $_POST['wholeSalePrice'];
    $manufacturingDate = $_POST['manufacturingDate'];
    $expiryDate = $_POST['expiryDate'];
    $wareHouseId = $_POST['wareHouseId'];
    $productId=$_POST['productId'];
	$totalAmount = $quantity * $unitPrice;
	if(isset($_POST['sessionId'])){
	    $sessionId = $_POST['sessionId'];
	    // 1. First try to upadate the temp purchase products
	    $sql = "UPDATE tbl_tempForeignPurchaseProducts 
                SET 
                    quantity=quantity+$quantity, 
                    purchaseAmount=((purchaseAmount*quantity)+($unitPrice*$quantity))/(quantity+$quantity), 
                    totalAmount=totalAmount+$totalAmount,
                    wholeSalePrice = ((wholeSalePrice*quantity)+($wholeSalePrice*$quantity))/(quantity+$quantity),
                    walkinCustomerPrice = ((walkinCustomerPrice*quantity)+($walkInCustomerPrice*$quantity))/(quantity+$quantity)
                WHERE 
                	tbl_productsId='$productId' AND 
                    tbl_wareHouseId='$wareHouseId' AND
                    sessionId='$sessionId'";
        if($conn->query($sql)){
            //If 1 is not successfull then insert the temp purchase products
    		if($conn->affected_rows == 0){
    	        $sql = "INSERT INTO tbl_tempForeignPurchaseProducts (tbl_productsId,quantity,wholeSalePrice,walkinCustomerPrice,tbl_wareHouseId,purchaseAmount,totalAmount,manufacturingDate,expiryDate,sessionId, entryBy) 
    			    VALUES ('$productId','$quantity','$wholeSalePrice','$walkInCustomerPrice','$wareHouseId','$unitPrice','$totalAmount','$manufacturingDate','$expiryDate','$sessionId', '$loginID')";
    		    $conn->query($sql);
    		}
    		echo json_encode('Success');
		} else {
    		    echo json_encode($conn->error);
            }
	}else{
	    try{
    	    $sessionId = $_POST['purchaseId'];
    	    $conn->begin_transaction();
    	    $sql = "UPDATE tbl_purchaseForeignProducts 
                    SET 
                        quantity=quantity+$quantity, 
                        purchaseAmount=((purchaseAmount*quantity)+($unitPrice*$quantity))/(quantity+$quantity), 
                        totalAmount=totalAmount+$totalAmount,
                        wholeSalePrice = ((wholeSalePrice*quantity)+($wholeSalePrice*$quantity))/(quantity+$quantity),
                        walkinCustomerPrice = ((walkinCustomerPrice*quantity)+($walkInCustomerPrice*$quantity))/(quantity+$quantity), 
                        lastUpdatedDate=NOW(),
                        lastUpdatedBy='$loginID'
                    WHERE 
                    	tbl_productsId='$productId' AND 
                        tbl_wareHouseId='$wareHouseId' AND
                       	tbl_purchaseForeignId='$sessionId'";
    	    $conn->query($sql);
            //If 1 is not successfull then insert the temp purchase products
    		if($conn->affected_rows == 0){
        	    $sql = "INSERT INTO tbl_purchaseForeignProducts (tbl_productsId, quantity, tbl_purchaseForeignId, purchaseAmount, totalAmount, wholeSalePrice, walkinCustomerPrice, tbl_wareHouseId, manufacturingDate, expiryDate, createdBy,createdDate)
        	            VALUES ('$productId','$quantity','$sessionId','$unitPrice','$totalAmount','$wholeSalePrice','$walkInCustomerPrice','$wareHouseId','$manufacturingDate','$expiryDate','$loginID','$toDay')";
        	    $conn->query($sql);
    		}
            $sql = "update tbl_purchase set totalAmount = totalAmount+$totalAmount, dueAmount=dueAmount+$totalAmount where id='$sessionId'";
            if($conn->query($sql)){
        		$sql = "UPDATE tbl_currentStock 
                            set purchaseStock=purchaseStock+$quantity, currentStock=currentStock+$quantity,lastUpdatedDate='$toDay',lastUpdatedBy='$loginID'
                            where tbl_productsId = '$productId' AND tbl_wareHouseId='$wareHouseId'";
                $conn->query($sql);
        		if($conn->affected_rows == 0){
        			$sql = "insert into tbl_currentStock (purchaseStock, currentStock, tbl_productsId, tbl_wareHouseId, entryBy, entryDate) values ('$quantity', '$quantity','$productId','$wareHouseId', '$loginID','$toDay')";
        			$conn->query($sql);
        		}
                $conn->commit();
                echo json_encode('Success');
            } else {
        		echo json_encode($conn->error);
            }
	    }catch(Exception $e){
	        $conn->rollBack();
		    echo 'RollBack';    
	    }
		
	    
	    
	}
}
// Update Customer or Supplier
if (isset($_POST['saveForeignPurchase'])) {
    $loginID = $_SESSION['user'];
	$purchaseDate = $_POST['purchaseDate'];
	$supplier = $_POST['supplier'];
    $lcNo = $_POST['lcNo'];
    $lcOpeningDate = $_POST['lcOpeningDate'];
    $deliveryDate = $_POST['deliveryDate'];
    $fileNo = $_POST['fileNo'];
    $blNo = $_POST['blNo'];
    $bankInformation = $_POST['bankInformation'];
    $sessionId = $_POST['sessionId'];
    $totalAmount=$_POST['totalAmount'];
    $paidAmount=$_POST['paidAmount'];
    $dueAmount=$_POST['dueAmount'];
    $purchaseCode=0;

    try{
        $conn->begin_transaction();
        $sql = "SELECT LPAD(max(purchaseOrderNo)+1, 6, 0) as purchaseCode from tbl_purchaseForeign";
        $query = $conn->query($sql);
        while ($prow = $query->fetch_assoc()) {
                $purchaseCode = $prow['purchaseCode'];
        }
        if($purchaseCode == ''){
            $purchaseCode = '000001';
        }
        $sql = "select id from tbl_tempForeignPurchaseProducts where sessionId='$sessionId'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            $sql = "INSERT INTO tbl_purchaseForeign (purchaseOrderNo,purchaseDate,lcNo,lcOpeningDate,deliveryDate,fileNo,blNo,tbl_bankInfoId,tbl_supplierId,purchaseType,status,totalAmount,paidAmount,dueAmount,createdBy,createdDate) 
                            VALUES ('$purchaseCode','$purchaseDate','$lcNo','$lcOpeningDate','$deliveryDate','$fileNo','$blNo','$bankInformation','$supplier','Foreign','Active','$totalAmount','$paidAmount','$dueAmount','$loginID','$toDay')";
            if ($conn->query($sql)) {
                $purchaseId = $conn->insert_id;
                $sql = "INSERT INTO tbl_purchaseForeignProducts (tbl_productsId, quantity, tbl_purchaseForeignId, purchaseAmount, totalAmount, wholeSalePrice, walkinCustomerPrice, tbl_wareHouseId, manufacturingDate, expiryDate, createdBy,createdDate)
                                SELECT tbl_productsId, quantity, '$purchaseId', purchaseAmount, totalAmount, wholeSalePrice, walkinCustomerPrice, tbl_wareHouseId, manufacturingDate, expiryDate, '$loginID','$toDay' FROM tbl_tempForeignPurchaseProducts where sessionId='$sessionId';";
                if ($conn->query($sql)) {
                    $sql = "SELECT tbl_productsId, quantity, tbl_wareHouseId 
                                    FROM tbl_purchaseForeignProducts 
                                    where tbl_purchaseForeignId='$purchaseId'";
                    $result = $conn->query($sql);
                    while($row = $result->fetch_assoc()){
                        $quantity = $row['quantity'];
                        $tbl_productsId = $row['tbl_productsId'];
                        $tbl_wareHouseId = $row['tbl_wareHouseId'];
                        $sql = "UPDATE tbl_currentStock 
                                set purchaseStock=purchaseStock+$quantity, currentStock=currentStock+$quantity, lastUpdatedDate='$toDay', lastUpdatedBy='$loginID'
                                where tbl_productsId = '$tbl_productsId' AND tbl_wareHouseId='$tbl_wareHouseId'";
                        $conn->query($sql);
                        if($conn->affected_rows == 0){
                            $sql = "insert into tbl_currentStock (purchaseStock, currentStock, tbl_productsId, tbl_wareHouseId, entryBy,entryDate) 
                                    values ('$quantity', '$quantity','$tbl_productsId','$tbl_wareHouseId','$loginID','$toDay')";
                            $conn->query($sql);
                        }
                    }
                    $sql = "delete from tbl_tempForeignPurchaseProducts where sessionId='$sessionId'";
                    if ($conn->query($sql)) {
                        $customerType = 'Party';
    				    $sql = "SELECT LPAD(IFNULL(max(voucherNo),0)+1, 6, 0) as voucherCode, LPAD(IFNULL(max(voucherNo),0)+2, 6, 0) as voucherReceiveCode FROM tbl_paymentVoucher WHERE tbl_partyId='$supplier' AND customerType = '$customerType'";
                		$query = $conn->query($sql);
                		while ($prow = $query->fetch_assoc()) {
                			$voucherNo = $prow['voucherCode'];
                			$voucherReceiveNo = $prow['voucherReceiveCode'];
                		}
                		if($voucherNo == ""){
                		    $voucherNo = "000001";
                		    $voucherReceiveNo = "000002";
                		}
                        if ($paidAmount > 0){
							$sql = "INSERT INTO tbl_paymentVoucher (tbl_partyId, tbl_purchaseId, amount, entryBy, paymentMethod, paymentDate, status, remarks, type, voucherType, voucherNo, customerType) 
									VALUES ('$supplier', '$purchaseId', '$paidAmount', '$loginID', 'Cash', 'CURDATE()', 'Active', 'payment for Foreign Purchase Code: $purchaseCode', 'payment', 'Foreign Purchase', '$voucherReceiveNo', $customerType)";
							$conn->query($sql);
						}
						if ($totalAmount > 0){
						    $sql = "INSERT INTO tbl_paymentVoucher (tbl_partyId, tbl_purchaseId, amount, entryBy, paymentMethod, paymentDate, status, remarks, type, voucherType) 
									VALUES ('$supplier', '$purchaseId', '$totalAmount', '$loginID', 'Cash', 'CURDATE()', 'Active', 'Payable for Foreign Purchase Code: $purchaseCode', 'payable', 'Foreign Purchase', '$voucherNo', '$customerType')";
							$conn->query($sql);
						}
						
                        $conn->commit();
                        $sessionId = time().uniqid();
                        $data = array( 
                                'msg'=>'Success', 
                                'purchaseId'=>$purchaseId);
        			    echo json_encode($data);
                        //echo json_encode('Success');
                    }else{
                        $conn->rollBack();
                        echo json_encode($conn->error);
                    }
                }else{
                    $conn->rollBack();
                    echo json_encode($conn->error);
                }
            } else {
                $conn->rollBack();
                echo json_encode($conn->error);
            }
        }else{
            $conn->rollBack();
            echo json_encode("Purchase order must have at least one product");
        }
    }catch(Exception $e){
            $conn->rollBack();
            echo 'RollBack';
    }
}

if(isset($_POST['editForeignPurchase'])){
    $loginID = $_SESSION['user'];
	$purchaseId = $_POST['purchaseId'];
	$paidAmount = $_POST['paidAmount'];
	$dueAmount =  $_POST['dueAmount'];
	$totalAmount =  $_POST['totalAmount'];
	$purchaseDate =  $_POST['purchaseDate'];
	$chalanNumber =  $_POST['chalanNumber'];
	
	try{
		$conn->begin_transaction();
		$sql = "select id from tbl_tempForeignPurchaseProducts where sessionId='$sessionId'
				UNION
				select id from tbl_purchaseForeignProducts where tbl_purchaseId='$purchaseId'";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			$sql = "update tbl_purchaseForeign set paidAmount='$paidAmount', dueAmount='$dueAmount', totalAmount='$totalAmount', purchaseDate='$purchaseDate', chalanNo='$chalanNumber', lastUpdatedDate='$toDay', lastUpdatedBy='$loginID' where id='$purchaseId'";
			if ($conn->query($sql)) {
				if ($paidAmount > 0){
					$sql = "select id from tbl_paymentVoucher where tbl_partyId='$supplier' AND tbl_purchaseId='$purchaseId' AND amount='$paidAmount'";
					$result = $conn->query($sql);
					if($result->num_rows == 0){
						$sql = "Update tbl_paymentVoucher set deleted='Yes', deletedBy='$loginID', deletedDate='$toDay' where tbl_purchaseId='$purchaseId'";
						$conn->query($sql);
						$sql = "INSERT INTO tbl_paymentVoucher (tbl_partyId, tbl_purchaseId, amount, entryBy, paymentMethod, paymentDate, status, remarks) 
								VALUES ('$supplier', '$purchaseId', '$paidAmount', '$loginID', 'Cash', CURDATE(), 'Active', 'payment for Purchase Code: $purchaseCode')";
						$conn->query($sql);
					}
				}
				$conn->commit();
				$sessionId = time().uniqid();
				echo json_encode('Success');
			} else {
				echo json_encode($conn->error);
			}
		}else{
			echo json_encode("Purchase order must have at least one product");
		}
	}catch(Exception $e){
		$conn->rollBack();
		echo 'RollBack';
	}
}
//Delete Temporary Purchase Products
if (isset($_POST['deleteTemporaryForeignPurchaseProducts'])) {
    $tempPurchaseProductsId = $_POST['id'];
    $sql = "delete from tbl_tempForeignPurchaseProducts where id='$tempPurchaseProductsId'";
	if($conn->query($sql)){
	    echo json_encode('Success');    
	}else{
	    echo json_encode($conn->error);
	}
	
}

//Delete Purchase Products
if(isset($_POST['deleteForeignPurchaseProducts'])){
    try{
        $purchaseProductsId = $_POST['id'];
        $loginID = $_SESSION['user'];
        $conn->begin_transaction();
        $sql = "select totalAmount, tbl_purchaseForeignId, quantity, tbl_productsId, tbl_wareHouseId from tbl_purchaseForeignProducts where id='$purchaseProductsId'";
        $ressult = $conn->query($sql);
        $totalAmount='';
        $purchaseId='';
        while ($prow = $ressult->fetch_assoc()) {
            $totalAmount = $prow['totalAmount'];
            $purchaseId = $prow['tbl_purchaseId'];
            $quantity = $prow['quantity'];
            $tbl_productsId = $prow['tbl_productsId'];
            $tbl_wareHouseId = $prow['tbl_wareHouseId']; 
        }
        $sql = "update tbl_purchaseForeignProducts set deleted='Yes', deletedBy='$loginID', deletedDate='$toDay' where id='$purchaseProductsId'";
    	if($conn->query($sql)){
    	    $sql = "update tbl_purchaseForeign set totalAmount = totalAmount - $totalAmount, dueAmount=dueAmount - $totalAmount where id='$purchaseId'";
    	    if($conn->query($sql)){
    	        $sql = "update tbl_currentStock set currentStock=currentStock-$quantity, purchaseStock=purchaseStock-$quantity where tbl_wareHouseId='$tbl_wareHouseId' AND tbl_productsId='$tbl_productsId'";
    	        $conn->query($sql);
    	        $conn->commit();
    	        echo json_encode('Success'); 
    	    }
    	       
    	}else{
    	    echo json_encode($conn->error);
    	}
    }catch(Exception $e){
        $conn->rollBack();
        echo json_encode($e->getMessage()); 
    }
}
if(isset($_POST['deleteForeignPurchase'])){
    try{
        $id = $_POST['id'];
        $loginID = $_SESSION['user'];
        $conn->begin_transaction();
        $sql = "UPDATE tbl_paymentVoucher set deleted='Yes', deletedBy='$loginID', deletedDate='$toDay' WHERE tbl_purchaseId='$id' AND voucherType='Foreign Purchase'";
        $conn->query($sql);
        $sql = "SELECT tbl_productsId, tbl_wareHouseId, quantity FROM `tbl_purchaseForeignProducts` WHERE tbl_purchaseForeignId='$id' AND deleted='No'";
        $query = $conn->query($sql);
        while($row = $query->fetch_assoc()){
            $quantity = $row['quantity'];
            $tbl_productsId = $row['tbl_productsId'];
            $tbl_wareHouseId = $row['tbl_wareHouseId'];
            $sql = "UPDATE tbl_currentStock SET currentStock = currentStock-$quantity, purchaseDelete = purchaseDelete+$quantity WHERE tbl_productsId='$tbl_productsId' AND tbl_wareHouseId='$tbl_wareHouseId' AND deleted='No'";
            $conn->query($sql);
        }
        $sql = "UPDATE tbl_purchaseForeignProducts SET deleted='Yes', deletedBy='$loginID', deletedDate='$toDay' WHERE tbl_purchaseForeignId='$id' AND deleted='No'";
        if($conn->query($sql)){
            $sql = "UPDATE tbl_purchaseForeign SET deleted='Yes', deletedBy='$loginID', deletedDate='$toDay' WHERE id='$id'";
            if($conn->query($sql)){
                $conn->commit();
                echo json_encode('Success');     
            }else{
                $conn->rollBack();
                echo json_encode('Error: '.$conn->error());
            }
        }else{
            $conn->rollBack();
            echo json_encode('Error: '.$conn->error());
        }
        
    }catch(Exception $e){
        $conn->rollBack();
        echo json_encode($e->getMessage()); 
    }
}
?>