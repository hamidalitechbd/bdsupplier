<?php
$conPrefix = '../';
include $conPrefix . 'includes/session.php';

date_default_timezone_set('Asia/Dhaka');
//$toDay = (new DateTime($test))->format("Y-m-d H:i:s");
$toDay = date('Y-m-d h:i:s', time());

// add customer
if (isset($_POST['saveTemporaryPurchaseProducts'])) {
	$loginID = $_SESSION['user'];
	$unitPrice = $_POST['unitPrice'];
	$quantity = $_POST['quantity'];
	$walkInCustomerPrice = $_POST['walkInCustomerPrice'];
	$wholeSalePrice = $_POST['wholeSalePrice'];
	$manufacturingDate = $_POST['manufacturingDate'];
	$expiryDate = $_POST['expiryDate'];
	$wareHouseId = $_POST['wareHouseId'];
	$productId = $_POST['productId'];
	$totalAmount = $quantity * $unitPrice;
	if (isset($_POST['sessionId'])) {
		$sessionId = $_POST['sessionId'];
		// 1. First try to upadate the temp purchase products
		$sql = "UPDATE tbl_tempPurchaseProducts 
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
		if ($conn->query($sql)) {
			//If 1 is not successfull then insert the temp purchase products
			if ($conn->affected_rows == 0) {
				$sql = "INSERT INTO tbl_tempPurchaseProducts (tbl_productsId,quantity,wholeSalePrice,walkinCustomerPrice,tbl_wareHouseId,purchaseAmount,totalAmount,manufacturingDate,expiryDate,sessionId, entryBy) 
    			    VALUES ('$productId','$quantity','$wholeSalePrice','$walkInCustomerPrice','$wareHouseId','$unitPrice','$totalAmount','$manufacturingDate','$expiryDate','$sessionId', '$loginID')";
				$conn->query($sql);
				$insertedId = $conn->insert_id;
				$productType = $_POST['productType'];
				if ($productType == 'serialize') {
					$data = '';
					$id = $productId;
					$warehouseId = $wareHouseId;
					$serialNumbers = $_POST['serialNumbers'];
					$stockQuantities = $_POST['stockQuantities'];
					$serialNumbers_array = (explode(",", $serialNumbers));
					$stockQuantities_array = (explode(",", $stockQuantities));
					for ($i = 0; $i < count($serialNumbers_array); $i++) {
						$serialNumber = $serialNumbers_array[$i];
						$item_quantity = $stockQuantities_array[$i];
						$sql_tempSerialize_insert = "INSERT INTO tbl_temp_serialize_products(tbl_productsId, warehouse_id, session_id, tbl_temp_purchase_product_id, serial_no, quantity, created_by, created_date) 
                                                    VALUES ('$id','$warehouseId','$sessionId','$insertedId','$serialNumber','$item_quantity','$loginID','$toDay')";
						$conn->query($sql_tempSerialize_insert);
					}
					/*if ($_SESSION["purchase_cart_array"] != null) {
            			foreach($_SESSION["purchase_cart_array"] as $keys => $values) {
            			    echo $_SESSION["purchase_cart_array"][$keys]['product_id'] . $id . $_SESSION["purchase_cart_array"][$keys]['warehouse_id']. $warehouseId;
            				if ($_SESSION["purchase_cart_array"][$keys]['product_id'] == $id && $_SESSION["purchase_cart_array"][$keys]['warehouse_id'] == $warehouseId) {
            					// Serialize Product
            					$serialNumbers_array = (explode(",", $serialNumbers));
            					$stockQuantities_array = (explode(",", $stockQuantities));
            					$_SESSION["purchase_cart_array"][$keys]['serialNumbers'] =  $serialNumbers_array;
            					$_SESSION["purchase_cart_array"][$keys]['stockQuantities'] =  $stockQuantities_array;
            					// End Serialize Product
            					$data = "Success";
            				}
            			}
            		} else {
            			$data = "NULL DATA";
            		}
            		print_r($data);*/
				}
			}

			echo json_encode('Success');
		} else {
			echo json_encode($conn->error);
		}
	} else {
		try {
			$sessionId = $_POST['purchaseId'];
			$conn->begin_transaction();
			$sql = "UPDATE tbl_purchaseProducts 
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
                       	tbl_purchaseId='$sessionId'";
			$conn->query($sql);
			//If 1 is not successfull then insert the temp purchase products
			if ($conn->affected_rows == 0) {
				$sql = "INSERT INTO tbl_purchaseProducts (tbl_productsId, quantity, tbl_purchaseId, purchaseAmount, totalAmount, wholeSalePrice, walkinCustomerPrice, tbl_wareHouseId, manufacturingDate, expiryDate, createdBy)
        	            VALUES ('$productId','$quantity','$sessionId','$unitPrice','$totalAmount','$wholeSalePrice','$walkInCustomerPrice','$wareHouseId','$manufacturingDate','$expiryDate','$loginID')";
				$conn->query($sql);
			}
			$sql = "update tbl_purchase set totalAmount = totalAmount+$totalAmount, dueAmount=dueAmount+$totalAmount where id='$sessionId'";
			if ($conn->query($sql)) {
				$sql = "UPDATE tbl_currentStock 
                            set purchaseStock=purchaseStock+$quantity, currentStock=currentStock+$quantity, lastUpdatedDate=NOW(), lastUpdatedBy='$loginID'
                            where tbl_productsId = '$productId' AND tbl_wareHouseId='$wareHouseId'";
				$conn->query($sql);
				if ($conn->affected_rows == 0) {
					$sql = "insert into tbl_currentStock (purchaseStock, currentStock, tbl_productsId, tbl_wareHouseId, entryBy) values ('$quantity', '$quantity','$productId','$wareHouseId', '$loginID')";
					$conn->query($sql);
				}
				$conn->commit();
				echo json_encode('Success');
			} else {
				echo json_encode($conn->error);
			}
		} catch (Exception $e) {
			$conn->rollBack();
			echo 'RollBack';
		}
	}
}
// Save final purchase information
if (isset($_POST['savePurchase'])) {
	$loginID = $_SESSION['user'];
	$purchaseDate = $_POST['purchaseDate'];
	$supplier = $_POST['supplier'];
	$chalanNumber = $_POST['chalanNumber'];
	$sessionId = $_POST['sessionId'];
	$totalAmount = $_POST['totalAmount'];
	$paidAmount = $_POST['paidAmount'];
	$dueAmount = $_POST['dueAmount'];
	$purchaseCode = 0;

	try {
		$conn->begin_transaction();
		$sql = "SELECT LPAD(max(purchaseOrderNo)+1, 6, 0) as purchaseCode from tbl_purchase";
		$query = $conn->query($sql);
		while ($prow = $query->fetch_assoc()) {
			$purchaseCode = $prow['purchaseCode'];
		}
		$sql = "select id from tbl_tempPurchaseProducts where sessionId='$sessionId'";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			$sql = "INSERT INTO tbl_purchase (purchaseOrderNo,purchaseDate,tbl_supplierId,purchaseType,status,totalAmount,paidAmount,dueAmount, chalanNo,createdBy,createdDate) 
					VALUES ('$purchaseCode','$purchaseDate','$supplier','Local','Active','$totalAmount','$paidAmount','$dueAmount', '$chalanNumber','$loginID','$toDay')";
			if ($conn->query($sql)) {
				$purchaseId = $conn->insert_id;
				$sql = "INSERT INTO tbl_purchaseProducts (tbl_productsId, quantity, tbl_purchaseId, purchaseAmount, totalAmount, wholeSalePrice, walkinCustomerPrice, tbl_wareHouseId, manufacturingDate, expiryDate, createdBy, createdDate)
						SELECT tbl_productsId, quantity, '$purchaseId', purchaseAmount, totalAmount, wholeSalePrice, walkinCustomerPrice, tbl_wareHouseId, manufacturingDate, expiryDate, '$loginID', '$today' FROM tbl_tempPurchaseProducts where sessionId='$sessionId';";
				if ($conn->query($sql)) {
					$purchaseProductId = $conn->insert_id;
					$sql = "SELECT tbl_productsId, quantity, tbl_wareHouseId, id 
							FROM tbl_purchaseProducts 
							where tbl_purchaseId='$purchaseId'";
					$result = $conn->query($sql);
					while ($row = $result->fetch_assoc()) {
						$quantity = $row['quantity'];
						$tbl_productsId = $row['tbl_productsId'];
						$tbl_wareHouseId = $row['tbl_wareHouseId'];
						$sql = "UPDATE tbl_currentStock 
								    set purchaseStock=purchaseStock+$quantity, currentStock=currentStock+$quantity, lastUpdatedDate='$toDay', lastUpdatedBy='$loginID'
								    where tbl_productsId = '$tbl_productsId' AND tbl_wareHouseId='$tbl_wareHouseId'";
						$conn->query($sql);
						if ($conn->affected_rows == 0) {
							$sql = "insert into tbl_currentStock 
							            (purchaseStock, currentStock, tbl_productsId, tbl_wareHouseId, entryBy,entryDate) values 
							            ('$quantity', '$quantity','$tbl_productsId','$tbl_wareHouseId','$loginID','$toDay')";
							$conn->query($sql);
						}
						//echo $sql.$conn->affected_rows;
					}
					$sql_insert_serialize = "INSERT INTO tbl_serialize_products(tbl_productsId, warehouse_id, purchase_id, tbl_purchase_product_id, serial_no, quantity, created_by, created_date) 
                                            SELECT tbl_productsId, warehouse_id, '$purchaseId', '$purchaseProductId', serial_no, quantity, '$loginID', '$toDay'
                                            FROM tbl_temp_serialize_products
                                            WHERE tbl_temp_serialize_products.session_id='$sessionId'";
					$conn->query($sql_insert_serialize);
					$sql_delete_tempSerialize = "delete from tbl_temp_serialize_products where session_id='$sessionId'";
					$conn->query($sql_delete_tempSerialize);
					$sql = "delete from tbl_tempPurchaseProducts where sessionId='$sessionId'";
					if ($conn->query($sql)) {
						$customerType = 'Party';
						$sql = "SELECT LPAD(IFNULL(max(voucherNo),0)+1, 6, 0) as voucherCode, LPAD(IFNULL(max(voucherNo),0)+2, 6, 0) as voucherReceiveCode FROM tbl_paymentVoucher WHERE tbl_partyId='$supplier' AND customerType = '$customerType'";
						$query = $conn->query($sql);
						while ($prow = $query->fetch_assoc()) {
							$voucherNo = $prow['voucherCode'];
							$voucherReceiveNo = $prow['voucherReceiveCode'];
						}
						if ($voucherNo == "") {
							$voucherNo = "000001";
							$voucherReceiveNo = "000002";
						}
						if ($totalAmount > 0) {
							$sql = "INSERT INTO tbl_paymentVoucher (tbl_partyId, tbl_purchaseId, amount, entryBy, paymentMethod, paymentDate, status, remarks, type, voucherType, voucherNo, customerType) 
									VALUES ('$supplier', '$purchaseId', '$totalAmount', '$loginID', 'Cash', '$purchaseDate', 'Active', 'Payable for Purchase Code: $purchaseCode', 'payable', 'Local Purchase', '$voucherNo', '$customerType')";
							$conn->query($sql);
						}

						if ($paidAmount > 0) {
							$sql = "INSERT INTO tbl_paymentVoucher (tbl_partyId, tbl_purchaseId, amount, entryBy, paymentMethod, paymentDate, status, remarks, type, voucherType, voucherNo, customerType) 
									VALUES ('$supplier', '$purchaseId', '$paidAmount', '$loginID', 'Cash', '$purchaseDate', 'Active', 'payment for Purchase Code: $purchaseCode', 'payment', 'Local Purchase', '$voucherReceiveNo', '$customerType')";
							$conn->query($sql);
						}

						$conn->commit();
						$sessionId = time() . uniqid();
						$data = array(
							'msg' => 'Success',
							'purchaseId' => $purchaseId
						);
						echo json_encode($data);
						//echo json_encode('Success');
					} else {
						echo json_encode($conn->error);
					}
				} else {
					echo json_encode($conn->error);
				}
				//$_SESSION['success'] = 'Entry Updated successfully';
			} else {
				echo json_encode($conn->error);
			}
		} else {
			echo json_encode("Purchase order must have at least one product");
		}
	} catch (Exception $e) {
		$conn->rollBack();
		echo 'RollBack';
	}
	/*try {
        
        $conn->beginTransaction();
 
        $stmt = $conn->prepare("INSERT INTO tbl_purchase (purchaseOrderNo,purchaseDate,tbl_supplierId,purchaseType,status,totalAmount,paidAmount,dueAmount) 
			VALUES ('$purchaseCode','$purchaeDate','$supplier','Local','Active','$totalAmount','$paidAmount','$dueAmount')");
     
        $stmt->execute();
        
        $stmt = $conn->prepare("INSERT INTO tbl_purchaseProducts (tbl_productsId, quantity, tbl_purchaseId, purchaseAmount, totalAmount, wholeSalePrice, walkinCustomerPrice, tbl_wareHouseId, manufacturingDate, expiryDate, createdBy)
                SELECT tbl_productsId, quantity, '$purchaseId', purchaseAmount, totalAmount, wholeSalePrice, walkinCustomerPrice, tbl_wareHouseId, manufacturingDate, expiryDate, '$loginID' FROM tbl_tempPurchaseProducts where sessionId='$sessionId';");
        $stmt->execute();
     
        $stmt = $conn->prepare("delete from tbl_tempPurchaseProducts where sessionId='$sessionId'");
        $stmt->execute();
     
        $conn->commit();
        
        echo json_encode('Success');
    } catch (PDOException $e) {
        $conn->rollBack();
        //die($e->getMessage());
        echo json_encode('Error: '.$e->getMessage());
    }*/

	//header('location: manage-view.php?page='.$unitType);
}

if (isset($_POST['editPurchase'])) {
	$loginID = $_SESSION['user'];
	$purchaseId = $_POST['purchaseId'];
	$paidAmount = $_POST['paidAmount'];
	$dueAmount =  $_POST['dueAmount'];
	$totalAmount =  $_POST['totalAmount'];
	$purchaseDate =  $_POST['purchaseDate'];
	$chalanNumber =  $_POST['chalanNumber'];

	try {
		$conn->begin_transaction();
		$sql = "select id from tbl_tempPurchaseProducts where sessionId='$sessionId'
				UNION
				select id from tbl_purchaseProducts where tbl_purchaseId='$purchaseId'";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			$sql = "update tbl_purchase set paidAmount='$paidAmount', dueAmount='$dueAmount', totalAmount='$totalAmount', purchaseDate='$purchaseDate', chalanNo='$chalanNumber', lastUpdatedDate='$toDay', lastUpdatedBy='$loginID' where id='$purchaseId'";
			if ($conn->query($sql)) {
				if ($paidAmount > 0) {
					$sql = "select id from tbl_paymentVoucher where tbl_partyId='$supplier' AND tbl_purchaseId='$purchaseId' AND amount='$paidAmount' AND voucherType='Local Purchase'";
					$result = $conn->query($sql);
					if ($result->num_rows > 0) {
						$rows = $result->fetch_assoc();
						$paymentVoucherId = $rows['id'];
						$sql = "Update tbl_paymentVoucher set deleted='Yes', deletedBy='$loginID', deletedDate='$toDay' where tbl_purchaseId='$paymentVoucherId'";
						$conn->query($sql);
						$sql = "INSERT INTO tbl_paymentVoucher (tbl_partyId, tbl_purchaseId, amount, entryBy, entryDate, paymentMethod, paymentDate, status, remarks) 
								VALUES ('$supplier', '$purchaseId', '$paidAmount', '$loginID','$toDay', 'Cash', '$purchaseDate', 'Active', 'payment for Purchase Code: $purchaseCode')";
						$conn->query($sql);
					} else {
						$sql = "INSERT INTO tbl_paymentVoucher (tbl_partyId, tbl_purchaseId, amount, entryBy, entryDate, paymentMethod, paymentDate, status, remarks) 
								VALUES ('$supplier', '$purchaseId', '$paidAmount', '$loginID','$toDay', 'Cash', '$purchaseDate', 'Active', 'payment for Purchase Code: $purchaseCode')";
						$conn->query($sql);
					}
				}
				$conn->commit();
				$sessionId = time() . uniqid();
				echo json_encode('Success');
			} else {
				echo json_encode($conn->error);
			}
		} else {
			echo json_encode("Purchase order must have at least one product");
		}
	} catch (Exception $e) {
		$conn->rollBack();
		echo 'RollBack';
	}
}
//Delete Temporary Purchase Products
if (isset($_POST['deleteTemporaryPurchaseProducts'])) {
	$tempPurchaseProductsId = $_POST['id'];
	$sql = "delete from tbl_tempPurchaseProducts where id='$tempPurchaseProductsId'";
	if ($conn->query($sql)) {
		$sql_delete_tempSerialize = "delete from tbl_temp_serialize_products where tbl_temp_purchase_product_id='$tempPurchaseProductsId'";
		$conn->query($sql_delete_tempSerialize);
		echo json_encode('Success');
	} else {
		echo json_encode($conn->error);
	}
}

//Delete Purchase Products
if (isset($_POST['deletePurchaseProducts'])) {
	try {
		$purchaseProductsId = $_POST['id'];
		$loginID = $_SESSION['user'];
		$conn->begin_transaction();
		$sql = "select totalAmount, tbl_purchaseId, quantity, tbl_productsId, tbl_wareHouseId from tbl_purchaseProducts where id='$purchaseProductsId'";
		$ressult = $conn->query($sql);
		$totalAmount = '';
		$purchaseId = '';
		while ($prow = $ressult->fetch_assoc()) {
			$totalAmount = $prow['totalAmount'];
			$purchaseId = $prow['tbl_purchaseId'];
			$quantity = $prow['quantity'];
			$tbl_productsId = $prow['tbl_productsId'];
			$tbl_wareHouseId = $prow['tbl_wareHouseId'];
		}
		$sql = "update tbl_purchaseProducts set deleted='Yes', deletedBy='$loginID', deletedDate='$toDay' where id='$purchaseProductsId'";
		if ($conn->query($sql)) {
			$sql = "update tbl_purchase set totalAmount = totalAmount - $totalAmount, dueAmount=dueAmount - $totalAmount where id='$purchaseId'";
			if ($conn->query($sql)) {
				$sql = "update tbl_currentStock set currentStock=currentStock-$quantity, purchaseStock=purchaseStock-$quantity, lastUpdatedDate='$toDay', lastUpdatedBy='$loginID' where tbl_wareHouseId='$tbl_wareHouseId' AND tbl_productsId='$tbl_productsId'";
				$conn->query($sql);
				$conn->commit();
				echo json_encode('Success');
			}
		} else {
			echo json_encode($conn->error);
		}
	} catch (Exception $e) {
		$conn->rollBack();
		echo json_encode($e->getMessage());
	}
}
if (isset($_POST['deletePurchase'])) {
	try {
		$id = $_POST['id'];
		$loginID = $_SESSION['user'];
		$sql = "SELECT id 
                FROM tbl_purchase_return
                WHERE tbl_purchaseId = '$id' and deleted='No'";
		$result = $conn->query($sql);
		if ($result->num_rows == 0) {
			$conn->begin_transaction();
			$sql = "UPDATE tbl_paymentVoucher set deleted='Yes', deletedBy='$loginID', deletedDate='$toDay' WHERE tbl_purchaseId='$id' AND voucherType='Local Purchase'";
			$conn->query($sql);
			$sql = "SELECT tbl_productsId, tbl_wareHouseId, quantity FROM tbl_purchaseProducts WHERE tbl_purchaseId='$id' AND deleted='No'";
			$query = $conn->query($sql);
			while ($row = $query->fetch_assoc()) {
				$quantity = $row['quantity'];
				$tbl_productsId = $row['tbl_productsId'];
				$tbl_wareHouseId = $row['tbl_wareHouseId'];
				$sql = "UPDATE tbl_currentStock SET currentStock = currentStock-$quantity, purchaseDelete = purchaseDelete+$quantity, lastUpdatedDate='$toDay', lastUpdatedBy='$loginID' WHERE tbl_productsId='$tbl_productsId' AND tbl_wareHouseId='$tbl_wareHouseId' AND deleted='No'";
				$conn->query($sql);
			}
			$sql = "UPDATE tbl_purchaseProducts SET deleted='Yes', deletedBy='$loginID', deletedDate='$toDay' WHERE tbl_purchaseId='$id' AND deleted='No'";
			if ($conn->query($sql)) {
				$sql = "UPDATE tbl_purchase SET deleted='Yes', deletedBy='$loginID', deletedDate='$toDay' WHERE id='$id'";
				if ($conn->query($sql)) {

					//====================== Start Serialize Product Delete ======================//
					// Delete
					$serialize_purchase_delete_sql = "UPDATE tbl_serialize_products
													  SET deleted='Yes', deleted_by='$loginID', deleted_date='$toDay' 
													  WHERE purchase_id='$id' AND deleted='No'";
					$conn->query($serialize_purchase_delete_sql);
					// End Delete
					//====================== End Serialize Product Delete ======================//

					$conn->commit();
					echo json_encode('Success');
				} else {
					echo json_encode('Error: ' . $conn->error());
				}
			} else {
				echo json_encode('Error: ' . $conn->error());
			}
		} else {
			echo json_encode('Error: Not possible to delete. Because this purchase have purchase return');
		}
	} catch (Exception $e) {
		$conn->rollBack();
		echo json_encode($e->getMessage());
	}
}

if (isset($_POST['action'])) {
	$action = $_POST['action'];
	if ($action == "showSerializTable") {
		unset($_SESSION["purchase_cart_array"]);
		$product_id = $_POST['id'];
		$warehouse_id = $_POST['warehouseId'];
		$totalQuantity = $_POST['quantity'];
		$items_in_box = $_POST['items_in_box'];
		$trId  = 0;
		$rows = '';
		$tempSerialNums = array();
		$tempQuantities = array();
		$sql_serializeProduct = "select max(serial_no) as serial from tbl_serialize_products where tbl_productsId='$product_id'";
		$query_serializeProduct = $conn->query($sql_serializeProduct);
		$row_serializeProduct = $query_serializeProduct->fetch_assoc();
		$maxNumber = $row_serializeProduct['serial'];
		//$totalQuantity = count(Session::get("purchase_cart_array")[$keys]['stockQuantities']) == 1 ? Session::get("purchase_cart_array")[$keys]['stockQuantities'][0] : 0;
		if ($trId == 0) {
			$function = 'onchange="generateSerialNo(this.value)"';
		}
		$avarageQty = ceil($totalQuantity / $items_in_box);
		for ($i = 0; $i < $avarageQty; $i++) {
			if ($totalQuantity < $items_in_box) {
				$items_in_box = $totalQuantity;
			}
			$rows .= '<tr id="row' . $i . '">' .
				'<td>' . ($i + 1) . '</td>' .
				'<td><input class="form-control input-sm stockQuantity' . $i .
				'" id="stockQuantity" type="text" name="stockQuantity" placeholder=" Quantity... " required oninput="calculateTotalQuantity()" value="' . $items_in_box . '"  ></td>';
			$rows .=
				'<td><input class="form-control input-sm serialNo' . $i .
				'" id="serialNo" type="text" name="serialNo" placeholder=" Serial Number... " required value="' . ++$maxNumber . '" ' . $function . '><td><a href="#" onclick="removeRow(' . $i . ')" style="color:red;"><i class="fa fa-trash"> </i> </a></td></td></tr>';
			$tempSerialNums[$i] = $maxNumber;
			$tempQuantities[$i] = $items_in_box;
			$totalQuantity -= $items_in_box;
		}
		/*if ($_SESSION["purchase_cart_array"] != null) {
            $is_available = 0;
			foreach($_SESSION["purchase_cart_array"] as $keys => $values){
			    if($_SESSION["purchase_cart_array"][$keys]['product_id'] == $product_id && $_SESSION["purchase_cart_array"][$keys]['warehouse_id'] == $warehouse_id){
			        $is_available++;
			        $_SESSION["purchase_cart_array"][$keys]['serialNumbers'] = $tempSerialNums;
			        $_SESSION["purchase_cart_array"][$keys]['stockQuantities'] = $tempQuantities;
			    }
			}
			if($is_available == 0){
    		    $item_array = array(
        					'product_id'               =>     $_POST["product_id"],  
        					'warehouse_id'             =>     $_POST["warehouse_id"],
        					'serialNumbers'            =>     $tempSerialNums,
        					'stockQuantities'            =>     $tempQuantities
        				);
        				$_SESSION["purchase_cart_array"][] = $item_array;
    		}
		}else{
		    $item_array = array(
    					'product_id'               =>     $_POST["product_id"],  
    					'warehouse_id'             =>     $_POST["warehouse_id"],
    					'serialNumbers'            =>     $tempSerialNums,
    					'stockQuantities'            =>     $tempQuantities
    				);
    				$_SESSION["purchase_cart_array"][] = $item_array;
		}*/

		echo json_encode(['displayTable' => $rows]);
	} else if ($action == 'updateCart') {
		/*$data = '';
        $id = $_POST['id'];
        $warehouseId = $_POST['warehouseId'];
        $serialNumbers = $_POST['serialNumbers'];
        $stockQuantities = $_POST['stockQuantities'];
        print_r($serialNumbers);
        if ($_SESSION["purchase_cart_array"] != null) {
            print_r($_SESSION["purchase_cart_array"]);
			foreach($_SESSION["purchase_cart_array"] as $keys => $values) {
			    $data .= 'Ok';
				if ($_SESSION["purchase_cart_array"][$keys]['product_id'] == $id && $_SESSION["purchase_cart_array"][$keys]['warehouse_id'] == $warehouseId) {
					// Serialize Product
					$serialNumbers_array = (explode(",", $serialNumbers));
					$stockQuantities_array = (explode(",", $stockQuantities));
					$_SESSION["purchase_cart_array"][$keys]['serialNumbers'] =  $serialNumbers_array;
					$_SESSION["purchase_cart_array"][$keys]['stockQuantities'] =  $stockQuantities_array;
					// End Serialize Product
					$data = "Success";
				}
			}
		} else {
			$data = "NULL DATA";
		}
		print_r($data);*/
	} else if ($action == "fetchCart") {
		/*$product_id = $_POST['id'];
        $rows = '';
        $warehouse_id = $_POST['warehouse_id'];
        if ($_SESSION["purchase_cart_array"] != null) {
            foreach ($_SESSION["purchase_cart_array"] as $keys => $values) {
				if ($_SESSION["purchase_cart_array"][$keys]['product_id'] == $product_id && $_SESSION["purchase_cart_array"][$keys]['warehouse_id'] == $warehouse_id) {
                    foreach ($_SESSION["purchase_cart_array"][$keys]['stockQuantities'] as $key => $stockQty) {
            			$serialNum = $_SESSION["purchase_cart_array"][$keys]['serialNumbers'][$key];
            			if ($key == 0) {
            				$function = 'onchange="generateSerialNo(this.value)"';
            			}
            			$rows .= '<tr id="row' . $key . '">' .
            				'<td>' . ($key + 1) . '</td>' .
            				'<td><input class="form-control input-sm stockQuantity' . $key .
            				'" id="stockQuantity" type="text" name="stockQuantity" placeholder=" Quantity... " required oninput="calculateTotalQuantity()"  value="' . $stockQty . '"  ></td>';
            			$rows .=
            				'<td><input class="form-control input-sm serialNo' . $key .
            				'" id="serialNo" type="text" name="serialNo" placeholder=" Serial Number... " required value="' . $serialNum . '" ' . $function . '><td><a href="#" onclick="removeRow(' . $key . ')" style="color:red;"><i class="fa fa-trash"> </i> </a></td></td></tr>';
            		}
				}
            }
        }
        echo json_encode(array('cart'=>$rows));*/
	}
}
