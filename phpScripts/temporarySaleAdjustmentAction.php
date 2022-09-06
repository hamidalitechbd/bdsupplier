<?php
$conPrefix = '../';
include $conPrefix . 'includes/session.php';

date_default_timezone_set('Asia/Dhaka');
//$toDay = (new DateTime($test))->format("Y-m-d H:i:s");
$toDay = (new DateTime())->format("Y-m-d");

function checkProducts($TSproductsIdArray, $productQuantityArray, $returnTSproductsIdArray, $returnQuantityArray)
{
	$msg = 'Failed';
	for ($i = 0; $i < count($TSproductsIdArray); $i++) {
		$TSproductsIdEntry = $TSproductsIdArray[$i];
		$productQuantityEntry = $productQuantityArray[$i];
		if ($i == count($TSproductsIdArray) - 1) {
			$TSproductsIdEntry = substr($TSproductsIdEntry, 0, strlen($TSproductsIdEntry) - 3);
			$productQuantityEntry = substr($productQuantityEntry, 0, strlen($productQuantityEntry) - 3);
		}





		for ($i = 0; $i < count($returnTSproductsIdArray); $i++) {
			$quantityEntry = $returnQuantityArray[$i];
			if ($quantityEntry > 0) {
				$returnTSproductsIdEntry = $returnTSproductsIdArray[$i];
				if ($i == count($returnQuantityArray) - 1) {
					$quantityEntry = substr($quantityEntry, 0, strlen($quantityEntry) - 3);
					$returnTSproductsIdArray = substr($returnTSproductsIdArray, 0, strlen($returnTSproductsIdArray) - 3);
				}
			}
		}
	}
}

//session_start();
if (isset($_POST["action"])) {
	if ($_POST["action"] == "salesAdjustment") {
		$error = 0;
		$loginID = $_SESSION['user'];
		$salesDate = $_POST['salesDate'];
		$customers = $_POST['customers'];
		$totalAmount = $_POST['totalAmount'];
		$salesDiscount = $_POST['salesDiscount'];
		$grandTotal = $_POST['grandTotal'];
		$paidAmount = $_POST['paidAmount'];
		$paymentMethod = $_POST['paymentMethod'];
		$vat = $_POST['vat'];
		$ait = $_POST['ait'];
		$wareHouse = $_POST['wareHouse'];

		$pojectName = $_POST['pojectName'];
		$requisitionNo = $_POST['requisitionNo'];
		$remarks = $_POST['remarks'];


		$TSproductsId = $_POST['TSproductsId'];
		$productQuantity = $_POST['productQuantity'];
		$productPrice = $_POST['productPrice'];
		$productDiscount = $_POST['productDiscount'];
		$productTotal = $_POST['productTotal'];
		$returnTSproductsId = $_POST['returnTSproductsId'];
		$returnQuantity = $_POST['returnQuantity'];
		$remainingZeroQuantityId = $_POST['remainingZeroQuantityId'];
		$TSproductsIdArray = explode("@!@,", $TSproductsId);
		$productQuantityArray = explode("@!@,", $productQuantity);
		$productPriceArray = explode("@!@,", $productPrice);
		$productDiscountArray = explode("@!@,", $productDiscount);
		$productTotalArray = explode("@!@,", $productTotal);
		$returnTSproductsIdArray = explode("@!@,", $returnTSproductsId);
		$returnQuantityArray = explode("@!@,", $returnQuantity);
		$remainingZeroQuantityIdArray = explode("@!@,", $remainingZeroQuantityId);
		$salesOrderNo = '';

		// Serialize Product
		$stockQuantities = $_POST['stockQuantities'];
		$stockQuantityArray = explode(",", $stockQuantities);

		$tbl_serialize_productsIds = $_POST['tbl_serialize_productsIds'];
		$tbl_serialize_productsIdsArray = explode(",", $tbl_serialize_productsIds);

		$newStockQuantities = $_POST['newStockQuantities'];
		$newStockQuantityArray = explode(",", $newStockQuantities);
		// End Serialize Product
		$salesId = '';
		try {
			$conn->begin_transaction();
			if ($TSproductsIdArray[0] != '') {
				$sql = "SELECT LPAD(max(salesOrderNo)+1, 6, 0) as salesCode from tbl_sales where type='TS'";
				$query = $conn->query($sql);
				while ($prow = $query->fetch_assoc()) {
					$salesOrderNo = $prow['salesCode'];
				}
				if ($salesOrderNo == "") {
					$salesOrderNo = "000001";
				}
				$sql = "SELECT Sum(CASE tbl_paymentVoucher.type
                       WHEN 'partyPayable' THEN tbl_paymentVoucher.amount
                       WHEN 'paymentReceived' THEN -tbl_paymentVoucher.amount
                       WHEN 'adjustment' THEN  -tbl_paymentVoucher.amount
                       WHEN 'payable' THEN -tbl_paymentVoucher.amount
                       WHEN 'payment' THEN tbl_paymentVoucher.amount
                       WHEN 'paymentAdjustment' THEN tbl_paymentVoucher.amount
                       WHEN 'discount' THEN -tbl_paymentVoucher.amount
                       END) AS total
                        FROM tbl_paymentVoucher
                        WHERE tbl_partyId = '" . $customers . "' AND customerType = 'Party' and deleted='No'";
				$query = $conn->query($sql);
				while ($prow = $query->fetch_assoc()) {
					$previousDue = $prow['total'];
				}
				if ($previousDue == '') {
					$previousDue = 0;
				}
				$totalDue = $previousDue - $paidAmount  + $grandTotal;
				$sql = "INSERT INTO tbl_sales (salesOrderNo, salesDate, tbl_customerId, totalAmount, projectName ,requisitionNo , remarks,salesDiscount, totalDiscount, grandTotal, vat, ait, createdBy, type, paymentType, tbl_wareHouseId, previousDue, paidAmount, totalDue,createdDate) 
            	        VALUES ('$salesOrderNo','$salesDate','$customers','$totalAmount','$pojectName','$requisitionNo','$remarks','$salesDiscount','$salesDiscount','$grandTotal','$vat','$ait','$loginID','TS','$paymentMethod','$wareHouse', '$previousDue', '$paidAmount', '$totalDue','$toDay')";
				if ($conn->query($sql)) {
					$salesId = $conn->insert_id;
					for ($i = 0; $i < count($TSproductsIdArray); $i++) {
						$TSproductsIdEntry = $TSproductsIdArray[$i];
						$productQuantityEntry = $productQuantityArray[$i];
						$productPriceEntry = $productPriceArray[$i];
						$productDiscountEntry = $productDiscountArray[$i];
						$productTotalEntry = $productTotalArray[$i];
						if ($i == count($TSproductsIdArray) - 1) {
							$TSproductsIdEntry = substr($TSproductsIdEntry, 0, strlen($TSproductsIdEntry) - 3);
							$productQuantityEntry = substr($productQuantityEntry, 0, strlen($productQuantityEntry) - 3);
							$productPriceEntry = substr($productPriceEntry, 0, strlen($productPriceEntry) - 3);
							$productDiscountEntry = substr($productDiscountEntry, 0, strlen($productDiscountEntry) - 3);
							$productTotalEntry = substr($productTotalEntry, 0, strlen($productTotalEntry) - 3);
						}
						if ($TSproductsIdEntry != '') {
							$total = $productQuantityEntry * $productPriceEntry;
							if (substr($productDiscountEntry, -1) == '%') {
								$discountAmount = $total * (substr($productDiscountEntry, 0, -1) / 100);
							} else {
								$discountAmount = $productDiscountEntry;
							}

							$sql = "INSERT INTO tbl_sales_products (tbl_salesId, tbl_productsId, quantity, units, createdBy, salesAmount, totalAmount, discount, grandTotal, remarks, tbl_wareHouseId, tbl_TSProductsId,createdDate) 
    						        SELECT '$salesId',tbl_productsId,'$productQuantityEntry','','$loginID','$productPriceEntry','$total','$discountAmount','$productTotalEntry','$productDiscountEntry',tbl_wareHouseId, id,'$toDay'
    					            FROM tbl_tsalesproducts 
                                    WHERE id='$TSproductsIdEntry' AND deleted='No'";
							if ($conn->query($sql)) {
								$sql = "UPDATE tbl_tsalesproducts 
                                        SET soldQuantity=soldQuantity+$productQuantityEntry 
                                        WHERE id='$TSproductsIdEntry' and deleted='No'";
								$conn->query($sql);
							} else {
								$error++;
								echo json_encode($conn->error . $sql);
								$conn->rollBack();
							}
						}
					}
					$customerType = 'Party';
					$sql = "SELECT LPAD(IFNULL(max(voucherNo),0)+1, 6, 0) as voucherCode, LPAD(IFNULL(max(voucherNo),0)+2, 6, 0) as voucherReceiveCode FROM tbl_paymentVoucher WHERE tbl_partyId='$customers' AND customerType = '$customerType'";
					$query = $conn->query($sql);
					while ($prow = $query->fetch_assoc()) {
						$voucherNo = $prow['voucherCode'];
						$voucherReceiveNo = $prow['voucherReceiveCode'];
					}
					if ($voucherNo == "") {
						$voucherNo = "000001";
						$voucherReceiveNo = "000002";
					}
					//if ($grandTotal > 0){
					$sql = "INSERT INTO tbl_paymentVoucher (tbl_partyId, tbl_sales_id, amount, entryBy, paymentMethod, paymentDate, status, remarks, type, voucherType, voucherNo, customerType,entryDate) 
    							VALUES ('$customers', '$salesId', '$grandTotal', '$loginID', '$paymentMethod', '$salesDate', 'Active', 'Payable for Temporary Sales Code: $salesOrderNo', 'partyPayable', 'TS', '$voucherNo', '$customerType','$toDay')";
					$conn->query($sql);
					//}
					if ($paidAmount > 0) {
						$sql = "INSERT INTO tbl_paymentVoucher (tbl_partyId, tbl_sales_id, amount, entryBy, paymentMethod, paymentDate, status, remarks, type, voucherType, voucherNo, customerType,entryDate) 
    							VALUES ('$customers', '$salesId', '$paidAmount', '$loginID', '$paymentMethod', '$salesDate', 'Active', 'payment for Temporary Sales Code: $salesOrderNo', 'paymentReceived', 'TS', '$voucherReceiveNo', '$customerType','$toDay')";
						$conn->query($sql);
					}
				}
			}


			if ($returnTSproductsIdArray[0] != '') {
				$sql = "SELECT LPAD(max(salesReturnOrderNo)+1, 6, 0) as salesReturnOrderNo 
            	            FROM tbl_sales_return 
                            WHERE salesType='TS'";
				$query = $conn->query($sql);
				$salesReturnOrderNo = '';
				while ($prow = $query->fetch_assoc()) {
					$salesReturnOrderNo = $prow['salesReturnOrderNo'];
				}
				if ($salesReturnOrderNo == '') {
					$salesReturnOrderNo = '000001';
				}
				$sql = "INSERT INTO tbl_sales_return(tbl_sales_id, tbl_customer_id, salesType, salesReturnOrderNo, returnDate, entryBy, remarks,entryDate) 
            	            VALUES ('0','$customers','TS','$salesReturnOrderNo','$salesDate','$loginID','Return of temporary sales','$toDay')";
				if ($conn->query($sql)) {
					$salesReturnId = $conn->insert_id;
					for ($i = 0; $i < count($returnTSproductsIdArray); $i++) {
						$quantityEntry = $returnQuantityArray[$i];
						if ($quantityEntry > 0) {
							$returnTSproductsIdEntry = $returnTSproductsIdArray[$i];
							if ($i == count($returnQuantityArray) - 1) {
								$quantityEntry = substr($quantityEntry, 0, strlen($quantityEntry) - 3);
								$returnTSproductsIdArray = substr($returnTSproductsIdArray, 0, strlen($returnTSproductsIdArray) - 3);
							}
							if ($quantityEntry != '' && $quantityEntry > 0) {
								$sql = "INSERT INTO tbl_sales_product_return (tbl_sales_return_id, tbl_products_id, quantity, tbl_wareHouseId, entryBy, remarks, tbl_salesProductsId,entryDate) 
                	                        SELECT '$salesReturnId', tbl_productsId, '$quantityEntry', '$wareHouse', '$loginID', 'TS Return', id ,'$toDay'
                                            FROM tbl_tsalesproducts 
                                            WHERE id='$returnTSproductsIdEntry' AND deleted='No'";
								if ($conn->query($sql)) {
									$returnProductsId = $conn->insert_id;
									$sql = "SELECT tbl_products_id, salePrice, grandTotal, tbl_wareHouseId  
            						            FROM tbl_sales_product_return 
            						            WHERE id='$returnProductsId'";
									$res = $conn->query($sql);
									if ($res) {
										$productsId = '';
										$wareHouseId = '';
										while ($row = $res->fetch_assoc()) {
											$productsId = $row['tbl_products_id'];
											$wareHouseId = $row['tbl_wareHouseId'];
											$totalAmount = $totalAmount + $row['grandTotal'];
										}
										//If purchase products return entry then update the current stock
										$sql = "UPDATE tbl_currentStock 
                                                        set salesReturnStock=salesReturnStock+$quantityEntry, currentStock=currentStock+$quantityEntry,lastUpdatedDate='$toDay',lastUpdatedBy='$loginID'
                                                        where tbl_productsId = '$productsId' AND tbl_wareHouseId='$wareHouseId'";
										$res = $conn->query($sql);
										if (!$res) {
											$error = $error + 1;
											break;
										}
										if ($conn->affected_rows == 0) {
											$sql = "insert into tbl_currentStock (salesReturnStock, currentStock, tbl_productsId, tbl_wareHouseId, entryBy,entryDate) 
                                    			        values ('$quantityEntry', '$quantityEntry','$productsId','$wareHouseId', '$loginID','$toDay')";
											$res = $conn->query($sql);
											if (!$res) {
												$error = $error + 1;
												break;
											}
										}
										$sql = "UPDATE tbl_tsalesproducts 
                                                    SET returnedQuantity=returnedQuantity+$quantityEntry 
                                                    WHERE id='$returnTSproductsIdEntry' and deleted='No'";
										$conn->query($sql);
									} else {
										$error = $error + 1;
										$conn->rollBack();
										echo json_encode('Error: ' . $conn->error . $sql);
										break;
									}
								} else {
									$error = $error + 1;
									$conn->rollBack();
									echo json_encode('Error: ' . $conn->error . $sql);
									break;
								}
							}
						}
					}    //End for loop


					//====================== Start Serialize Product Return ======================//
					//===== Update in Existing table =====//
					$tbl_tSalesId = '';
					foreach ($tbl_serialize_productsIdsArray as $key => $tbl_serialize_productsId) {
						if (strpos($tbl_serialize_productsId, '@') == true) {
							$tbl_tSalesIdAndProductIdAndWarahouseId = explode("@", $tbl_serialize_productsId);
							$tbl_tSalesId = $tbl_tSalesIdAndProductIdAndWarahouseId[0];
							$tempProductId = $tbl_tSalesIdAndProductIdAndWarahouseId[1];
							//$tempWarehouseId = $tbl_tSalesIdAndProductIdAndWarahouseId[2];
							$tempWarehouseId = $wareHouse;
							continue;
						}
						$returnQuantity = $stockQuantityArray[$key];
						// Update Quantity 
						if ($returnQuantity > 0) {
							$update_serialize = "UPDATE tbl_serialize_products set quantity=quantity+$returnQuantity, is_sold='ON' 
                                                   where id='$tbl_serialize_productsId'";
							$updateResult = $conn->query($update_serialize);
							if ($updateResult) {
								$returnSql = "INSERT INTO tbl_sale_serialize_products_return (tbl_sales_return_id, returned_quantity, salesType, created_by, created_date) 
                                		              values ('$salesReturnId','$returnQuantity','TS','$loginID','$toDay')";
								$result = $conn->query($returnSql);
							}
						}
					} //End
					//===== End Update in Existing table =====//

					//===== Insert Newly =====//
					$tempProductId = 0;
					$tempWarehouseId = 0;
					$maxNumber = 0;
					foreach ($newStockQuantityArray as $key => $newStockQuantity) {
						if (strpos($newStockQuantity, '@') == true) {
							$tbl_tSalesIdAndProductIdAndWarahouseId = explode("@", $newStockQuantity);
							//$tbl_tSalesId = $tbl_tSalesIdAndProductIdAndWarahouseId[0];
							$tempProductId = $tbl_tSalesIdAndProductIdAndWarahouseId[1];
							//$tempWarehouseId = $tbl_tSalesIdAndProductIdAndWarahouseId[2];
							$tempWarehouseId = $wareHouse;
							// Generate Serial Number
							$sql_serializeProduct = "select max(serial_no) as serial from tbl_serialize_products where tbl_productsId='$tempProductId'";
							$query_serializeProduct = $conn->query($sql_serializeProduct);
							$row_serializeProduct = $query_serializeProduct->fetch_assoc();
							$maxNumber = $row_serializeProduct['serial'];
							// End
							continue;
						}
						$returnQuantity = $newStockQuantity;
						// Insert
						if ($returnQuantity > 0) {
							$maxNumber++;
							$sql_insert_serialize = "INSERT INTO `tbl_serialize_products`(`tbl_productsId`, `warehouse_id`, `serial_no`, `quantity`, `created_by`, `created_date`) 
                                                  VALUES ('$tempProductId','$tempWarehouseId','$maxNumber','$returnQuantity','$loginID','$toDay')";
							$insertResult = $conn->query($sql_insert_serialize);
							if ($insertResult) {
								$returnSql = "INSERT INTO tbl_sale_serialize_products_return (tbl_sales_return_id, returned_quantity, salesType, created_by, created_date) 
                                		              values ('$salesReturnId', '$returnQuantity','TS','$loginID','$toDay')";
								$result = $conn->query($returnSql);
							}
						}
						// End
					}
					//===== End Insert Newly =====//
					//====================== End Serialize Product Return ======================//


				} else {
					$conn->rollBack();
					echo json_encode('Error: ' . $conn->error . $sql);
				}
			}

			if ($error == 0) {
				$conn->commit();
				for ($i = 0; $i < count($remainingZeroQuantityIdArray); $i++) {
					/*$remainingZeroQuantityIdEntry = $remainingZeroQuantityIdArray[$i];
					if($i == count($remainingZeroQuantityIdArray)-1){
						$remainingZeroQuantityIdEntry = substr($remainingZeroQuantityIdEntry, 0, strlen($remainingZeroQuantityIdEntry)-3);
					}
					if($remainingZeroQuantityIdEntry != ''){
					    $sql = "UPDATE tbl_tsalesproducts
                                SET status = 'Adjusted'
                                WHERE id = '$remainingZeroQuantityIdEntry'";
                        if($conn->query($sql)){
                            
                        }else{
                            echo json_encode('Error: '.$conn->error.$sql);    
                        }
					}*/
					$sql = "UPDATE tbl_tsalesproducts 
                            SET status = 'Adjusted' 
                            WHERE status = 'Running' AND quantity <= soldQuantity + returnedQuantity AND deleted = 'No'";
					if ($conn->query($sql)) {
					} else {
						echo json_encode('Error: ' . $conn->error . $sql);
					}
				}
				if ($salesId == "") {
					$salesId = 0;
				}
				if ($salesReturnId == "") {
					$salesReturnId = 0;
				}
				$data = array(
					'msg' => 'Success',
					'salesId' => $salesId,
					'salesReturnId' => $salesReturnId
				);
				echo json_encode($data);
			} else {
				$conn->rollBack();
			}
			/*}else{
        	    $error++;
		        echo json_encode($conn->error.$sql);	    
	            $conn->rollBack();		    
        	}*/
		} catch (Exception $e) {
			$conn->rollBack();
			echo json_encode('RollBack');
		}
	} else if ($_POST['action'] == "showSerializTable") {
		$rows = '';
		$tbl_tSalesId =  $_POST['tbl_tSalesId'];
		$product_id =  $_POST['product_id'];
		$warehouse_id =  $_POST['warehouseId'];
		$totalQuantityForReturn = 0;
		$tbl_serialize_productsIdArray = [];
		$serializeProducts = [];
		$sql_serializeProducts = "SELECT id, warehouse_id, quantity, used_quantity FROM tbl_serialize_products
        WHERE  tbl_productsId='$product_id' AND warehouse_id='$warehouse_id' AND quantity>used_quantity AND deleted='No' AND is_sold='ON'";
		$result_serializeProducts = $conn->query($sql_serializeProducts);
		if ($result_serializeProducts->num_rows > 0) {
			$key = 0;
			while ($row_serializeProducts = $result_serializeProducts->fetch_array()) {
				$serializeProducts[$key] = $row_serializeProducts;
				$key++;
			}
		} else {
			$rows .= '<tr class="bg-warning"><td colspan="4">Stock Not Avaialable For Sale...</td></tr>';
		}
		echo json_encode(array('serializeProducts' => $serializeProducts, "tbl_serialize_productsIdArray" => $tbl_serialize_productsIdArray));
		//=========== End Serialize Product Return ===========//
	}
} else if ($_POST['customers']) {
	$customers = $_POST['customers'];
	$sql = "SELECT tbl_tsalesproducts.id, tbl_tsalesproducts.tbl_tSalesId, tbl_tsalesproducts.quantity, tbl_tsalesproducts.tbl_productsId, tbl_tsalesproducts.tbl_wareHouseId, tbl_tsalesproducts.amount, tbl_tsalesproducts.soldQuantity, tbl_tsalesproducts.returnedQuantity, tbl_products.productName, tbl_products.productCode, tbl_products.type, tbl_units.unitName
            FROM tbl_tsalesproducts
            LEFT OUTER JOIN tbl_products ON tbl_tsalesproducts.tbl_productsId = tbl_products.id
            LEFT OUTER JOIN tbl_units ON tbl_products.units = tbl_units.id
            LEFT OUTER JOIN tbl_temporary_sale ON tbl_tsalesproducts.tbl_tSalesId = tbl_temporary_sale.id AND tbl_temporary_sale.deleted = 'No'
            WHERE tbl_tsalesproducts.deleted = 'No' AND tbl_tsalesproducts.status = 'Running' AND tbl_temporary_sale.tbl_customerId = '$customers'";
	$result = $conn->query($sql);
	$i = 1;
	$output = '<thead>
				  <th>SN#</th>
				  <th>Product Name</th>
				  <th>Product Code</th>
				  <th>Total Quantity</th>
    			  <th>Sold Quantity</th>
				  <th>Returned Quantity</th>   
				  <th>Sale Quantity</th>
				  <th>Unit Price</th>
				  <th>Return Quantity</th>   
				  <th>Remaining</th>
				  <th>Total</th>
				  <th width="8%">Action</th>
				</thead>';
	while ($row = $result->fetch_array()) {
		$TSProductsId = $row['id'];
		$productName = $row['productName'];
		$productCode = $row['productCode'];
		// For Serialize
		$tbl_tSalesId = $row['tbl_tSalesId'];
		$productType = $row['type'];
		$tbl_productsId = $row['tbl_productsId'];
		$tbl_wareHouseId = $row['tbl_wareHouseId'];
		// End
		$quantity = $row['quantity'];
		$soldQuantity = $row['soldQuantity'];
		$returnedQuantity = $row['returnedQuantity'];
		$unitName = $row['unitName'];
		$unitPrice = $row['amount'];
		$remainingQuantity = $quantity - ($soldQuantity + $returnedQuantity);
		$totalPrice = 0;
		$button = '';
		$disabled = '';
		if ($productType == "serialize") {
			//$button .= ' <a href="#" class="btn btn-info btn-flat btn-sm" onclick="showSerializTable(' . $tbl_tSalesId . ',' . $TSProductsId . ',' . $tbl_wareHouseId . ',' . $i . ',' . $tbl_productsId . ')"><i class="fa fa-edit"></i></a>';
			$button = '<button class="btn btn-primary btn-sm btn-flat" type="button" onclick="showSerializTable(' . $tbl_tSalesId . ',' . $TSProductsId . ',' . $tbl_wareHouseId . ',' . $i . ',' . $tbl_productsId . ')"> <i class="fa fa-eye"></i> </button> ';
			$disabled = 'disabled';
		}
		$button .= '<a href="#" class="btn btn-danger btn-sm" onclick="removeTSProducts(' . $TSProductsId . ')"><i class="fa fa-trash tiny-icon"></i></a> ';
		$output .= '<tr id="' . $TSProductsId . '">
                        <td>' . $i . '</td>
                        <td>' . $productName . '</td>
                        <td>' . $productCode . '</td>
                        <td><span id="quantity_' . $TSProductsId . '" name="quantity[' . $TSProductsId . ']">' . $quantity . '</span> ' . $unitName . '</td>
                        <td><span id="soldQuantity_' . $TSProductsId . '" name="soldQuantity[' . $TSProductsId . ']">' . $soldQuantity . '</span> ' . $unitName . '</td>
                        <td><span id="returnedQuantity_' . $TSProductsId . '" name="returnedQuantity[' . $TSProductsId . ']">' . $returnedQuantity . '</span> ' . $unitName . '</td>
                        <td><input type="text" style="width: 60%;text-align: center;" id="saleQuantity_' . $TSProductsId . '" name="saleQuantity[' . $TSProductsId . ']" value = "0" onkeyup="totalCalculation(' . $TSProductsId . ')" /> ' . $unitName . '</td>
                        <td><input type="text" style="width: 90%;text-align: center;" id="unitPrice_' . $TSProductsId . '" name="unitPrice[' . $TSProductsId . ']" value = "' . $unitPrice . '" onkeyup="totalCalculation(' . $TSProductsId . ')" /></td>
                        <td><input type="text" style="width: 60%;text-align: center;" id="returnQuantity_' . $TSProductsId . '" name="returnQuantity[' . $TSProductsId . ']" value = "0" onkeyup="totalCalculation(' . $TSProductsId . ')" ' . $disabled . ' /> ' . $unitName . '</td>
                        <td style="text-align: center;"><input type="hidden" id="totalRemainingQuantity_' . $TSProductsId . '" name="totalRemainingQuantity[' . $TSProductsId . ']" value = "' . $remainingQuantity . '"><span id="remainingQuantity_' . $TSProductsId . '" name="remainingQuantity[' . $TSProductsId . ']">' . $remainingQuantity . '</span> ' . $unitName . '</td>
                        <td><span id="totalPrice_' . $TSProductsId . '" name="totalPrice[' . $TSProductsId . ']">' . $totalPrice . '</span></td>
                        <td>' . $button . '</td>
                    </tr>';
		$i++;
	} // /while 
	$output .= '<tr><td colspan="8" style="text-align:right;">Total Price</td><td colspan="4"><span id="total">0</span></td></tr>
                <tr><td colspan="8" style="text-align:right;">Discount</td><td colspan="4"><input type="text" id="discount" name="discount" value="0" onkeyup="calculateDiscount()"/></td></tr>
                <tr><td colspan="8" style="text-align:right;">Grand Total</td><td colspan="4"><span id="grandTotal">0</span></td></tr>
                <tr><td colspan="8" style="text-align:right;">Payment Method</td><td colspan="4"><select id="paymentMethod" name="paymentMethod"><option value="CASH">Cash</option></select></td></tr>
                <tr><td colspan="8" style="text-align:right;">Paid Amount</td><td colspan="4"><input type="text" id="paidAmount" name="paidAmount" value="0" /></td></tr>
                <tr><td colspan="8" style="text-align:right;"></td><td colspan="4">
                    <a type="submit"  href="#" class="btn btn-default" id="btn_saleAndAdjust" style="width: 40%;box-shadow: 1px 1px 1px 0px #909090;"><span class="glyphicon glyphicon-shopping-cart" style="color: #000cbd;"></span> Sale and Adjust</a></td></tr>';
	$data = array(
		'tableDetails'		=>	$output
	);
	echo json_encode($data);
}
