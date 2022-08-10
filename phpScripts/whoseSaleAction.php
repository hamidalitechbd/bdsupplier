<?php
$conPrefix = '../';
include $conPrefix . 'includes/session.php';

date_default_timezone_set('Asia/Dhaka');
$toDay = (new DateTime())->format("Y-m-d H:i:s");
$toDate = (new DateTime())->format("Y-m-d");

//session_start();
/*function findStoreQuantity($product_id, $warehouse_id, $serializeId)
{
	foreach ($_SESSION["wholeSaleShopping_cart"] as $keys => $values) {
		if ($_SESSION["wholeSaleShopping_cart"][$keys][$keys]['product_id'] == $product_id && $_SESSION["wholeSaleShopping_cart"][$keys][$keys]['warehouse_id'] == $warehouse_id) {
			$serializeIdArray = $_SESSION["wholeSaleShopping_cart"][$keys][$keys]['serializeIdArray'];
			$item = array_search($serializeId, $serializeIdArray);
			if ($item > 0) {
				return $_SESSION["wholeSaleShopping_cart"][$keys][$keys]['serializeSaleQtyArray'][$item];
			} else {
				return '';
			}
		}
	}
}*/
if(isset($_POST["action"])){
	if($_POST["action"] == "add"){
	    $totalStock = 0;
	    $stockError = 0;
	    $currentStockId = '';
	    $productId = $_POST["product_id"];
	    $warehouseId = $_POST["warehouse_id"];
	    /*$sql = "SELECT currentStock AS totalStock, id 
                FROM tbl_currentStock
                INNER JOIN 
                WHERE tbl_productsId='$productId' AND tbl_wareHouseId='$warehouseId' AND deleted='No'";*/
                //updated For serialize products
        $sql = "SELECT tbl_currentStock.currentStock AS totalStock, tbl_currentStock.id, tbl_products.type 
                FROM tbl_currentStock
                INNER JOIN tbl_products ON tbl_currentStock.tbl_productsId=tbl_products.id
                WHERE tbl_productsId='$productId' AND tbl_currentStock.tbl_wareHouseId='$warehouseId' AND tbl_currentStock.deleted='No'";
        $result = $conn->query($sql);
        if($result){
            $productType = '';
            while($row = $result->fetch_assoc()){
                $productType = $row['type'];
                $totalStock += $row['totalStock'];
                $currentStockId = $row['id'];
            }
            if($totalStock == ''){
                $totalStock = 0;
            }
        }else{
            $totalStock = 0;
            $currentStockId=rand();
        }
        $is_productSame = 0;
        if($totalStock > 0){
            $serializeIdArray = array();
			$serializeSaleQtyArray = array();
    		if(isset($_SESSION["wholeSaleShopping_cart"])){
    			$is_available = 0;
    			
    			foreach($_SESSION["wholeSaleShopping_cart"] as $keys => $values){
    				if($_SESSION["wholeSaleShopping_cart"][$keys]['product_id'] == $_POST["product_id"] && $_SESSION["wholeSaleShopping_cart"][$keys]['warehouse_id'] == $_POST["warehouse_id"]){
    					$is_available++;
    					$is_productSame = 0;
    					if($totalStock >= $_SESSION["wholeSaleShopping_cart"][$keys]['product_quantity'] + $_POST["product_quantity"]){
        					$_SESSION["wholeSaleShopping_cart"][$keys]['product_quantity'] = $_SESSION["wholeSaleShopping_cart"][$keys]['product_quantity'] + $_POST["product_quantity"];
        					$lastValue = substr($_SESSION["wholeSaleShopping_cart"][$keys]['product_discount'], -1);
                		    if($lastValue != "%"){
            					$_SESSION["wholeSaleShopping_cart"][$keys]['product_discount'] = $_SESSION["wholeSaleShopping_cart"][$keys]['product_discount'] + $_POST["product_discount"];
                		    }
    					}else{
    					    $stockError++;
    					}
    					break;
    				}else if($_SESSION["wholeSaleShopping_cart"][$keys]['product_id'] == $_POST["product_id"]){
    				    $is_productSame=1;
    				}
    			}
    			if($is_available == 0){
    			    $discount_percent = (($_POST["max_price"]-$_POST["min_price"])/$_POST["max_price"])*100;
    				$item_array = array(
    					'id'                       =>     $currentStockId,  
    					'product_id'               =>     $_POST["product_id"],  
    					'product_name'             =>     $_POST["product_name"],  
    					'product_price'            =>     $_POST["max_price"],
    					'min_price'            	   =>     $_POST["min_price"],
    					'max_price'                =>     $_POST["max_price"],					
    					'product_quantity'         =>     $_POST["product_quantity"],
    					'warehouse_id'             =>     $_POST["warehouse_id"],
    					'warehouse_name'           =>     $_POST["warehouse_name"],
    					'product_limit'            =>     $totalStock,
    					'product_discount'         =>     $discount_percent.'%',
    				    'status'                   =>     $is_productSame,
    				    'product_type'        	   =>     $productType,
    				    'serializeIdArray'  	   =>  [$serializeIdArray],
					    'serializeSaleQtyArray'    =>  [$serializeSaleQtyArray]
    				);
    				$_SESSION["wholeSaleShopping_cart"][] = $item_array;
    			}
    		} else {
    		    $discount_percent = (($_POST["max_price"]-$_POST["min_price"])/$_POST["max_price"])*100;
    			$item_array = array(
    			    'id'                       =>     $currentStockId,  
    				'product_id'               =>     $_POST["product_id"],  
    				'product_name'             =>     $_POST["product_name"],  
    				'product_price'            =>     $_POST["max_price"],  
    				'min_price'            	   =>     $_POST["min_price"],
    				'max_price'                =>     $_POST["max_price"],
    				'product_quantity'         =>     $_POST["product_quantity"],
    				'warehouse_id'             =>     $_POST["warehouse_id"],
    				'warehouse_name'           =>     $_POST["warehouse_name"],
    				'product_limit'            =>     $totalStock,
    				'product_discount'         =>     $discount_percent.'%',
    				'status'                   =>     $is_productSame,
    				'product_type'        	   =>     $productType,
    				'serializeIdArray'  	   =>  [$serializeIdArray],
					'serializeSaleQtyArray'    =>  [$serializeSaleQtyArray]
    			);
    			$_SESSION["wholeSaleShopping_cart"][] = $item_array;
    		}
        }else{
            $stockError++;
        }
        /*(data.productType == "serialize") { 
                                    let productId = data.productId;
                                    let warehouseId = data.warehouseId;
                                    data.count*/
        $data = array('productType'=>$productType, 'productId'=>$_POST["product_id"], 'warehouseId'=>$_POST["warehouse_id"], 'count'=>$stockError, 'currentStockId'=>$currentStockId);
        echo json_encode($data);
	}
	else if($_POST["action"] == 'remove'){
	    $changed = 0;
	    $productId = 0;
		foreach($_SESSION["wholeSaleShopping_cart"] as $keys => $values)
		{
			if($values["id"] == $_POST["id"] && $changed == 0)
			{
			    $productId = $values["product_id"];
				unset($_SESSION["wholeSaleShopping_cart"][$keys]);
				$changed = 1;
				//break;
			}else if($values["product_id"] == $productId){
			    $_SESSION["wholeSaleShopping_cart"][$keys]["status"] = $_SESSION["wholeSaleShopping_cart"][$keys]["status"]-1;
			}
		}
	}
	else if($_POST["action"] == "adjust"){
	    $productType = '';
		$productId = '';
		$warehouseId = '';
		$currentStockId = '';
		if(isset($_SESSION["wholeSaleShopping_cart"])){
			foreach($_SESSION["wholeSaleShopping_cart"] as $keys => $values){
				if($_SESSION["wholeSaleShopping_cart"][$keys]['id'] == $_POST["id"]){
					$_SESSION["wholeSaleShopping_cart"][$keys]['product_quantity'] =  $_POST["product_quantity"];
					$_SESSION["wholeSaleShopping_cart"][$keys]['product_price'] =  $_POST["product_price"];
					$_SESSION["wholeSaleShopping_cart"][$keys]['product_limit'] =  $_POST["product_limit"];
					$_SESSION["wholeSaleShopping_cart"][$keys]['product_discount'] =  $_POST["product_discount"];
					// Serialize Product
					if ($_SESSION["wholeSaleShopping_cart"][$keys]['product_type'] == "serialize") {
						if ($_POST['product_type']) {
							$serializeId = $_POST['serializeProductsId'];
							$serializeSaleQty = $_POST['serializeSaleQuantity'];
							$serializeIdExist = TRUE;
							foreach ($_SESSION["wholeSaleShopping_cart"][$keys]['serializeIdArray'] as $key => $value) {
								if ($value == $serializeId) {
									//session()->put("sale_cart_array." . $keys . ".serializeSaleQtyArray." . $key, $serializeSaleQty);
									$_SESSION["wholeSaleShopping_cart"][$keys]['serializeSaleQtyArray'][$key] =  $serializeSaleQty;
									$ddd = $_SESSION["wholeSaleShopping_cart"][$keys]['serializeSaleQtyArray'];
									$serializeIdExist = FALSE;
								}
							}
							if ($serializeIdExist) {
								/*Session::push("sale_cart_array." . $keys . ".serializeIdArray", $serializeId);
								Session::push("sale_cart_array." . $keys . ".serializeSaleQtyArray", $serializeSaleQty);*/
								array_push($_SESSION["wholeSaleShopping_cart"][$keys]['serializeIdArray'],$serializeId);
								
								array_push($_SESSION["wholeSaleShopping_cart"][$keys]['serializeSaleQtyArray'],$serializeSaleQty);
								$ddd = $_SESSION["wholeSaleShopping_cart"][$keys]['serializeSaleQtyArray'];
							}
						}
					}
					$productType = $_SESSION["wholeSaleShopping_cart"][$keys]['product_type'];
					$productId = $_SESSION["wholeSaleShopping_cart"][$keys]['product_id'];
					$warehouseId = $_SESSION["wholeSaleShopping_cart"][$keys]['warehouse_id'];
					$currentStockId = $_SESSION["wholeSaleShopping_cart"][$keys]['id'];
					// End Serialize Product
					break;
				}
			}
		}
		$data = array('productType'=>$productType, 'productId'=>$productId, 'warehouseId'=>$warehouseId, 'currentStockId'=>$currentStockId, 'ddd'=>$ddd);
        echo json_encode($data);
	}
	else if($_POST["action"] == 'empty'){
		unset($_SESSION["wholeSaleShopping_cart"]);
	}
	else if($_POST["action"] == 'FinalSalesConfirmation'){
	    $error=0;
	    $checkOverQuantity = 0;
		$salesId = 0;
	    $loginID = $_SESSION['user'];
	    $sales = explode(",",$_POST['sales']);
    	$salesProduct = explode(",",$_POST['salesProduct']);
    	$vouchers = explode(",",$_POST['vouchers']);
    	$rowIds = $_POST['rowId'];
    	$updateWarehouses = $_POST['updateWarehouse'];
    	$rowIdArray = explode(",",$rowIds);
    	$updateWarehouseArray = explode(",",$updateWarehouses);
    	$ind = 13;
    	for($i = 0; $i < count($rowIdArray); $i++){
    	    if($rowIdArray[$i] != ""){
    	        $salesProduct[($rowIdArray[$i]*$ind)+9] = $updateWarehouseArray[$i];
    	    }
    	}
    	
    	//$noofSaleProducts = count($salesProduct)/$ind;
    	$wirehouseProduct = array(array());
    	
    	for($i = 0; $i < count($salesProduct); $i=$i+$ind){
    	    $updateWarehouseFlag = 0;
    	    for($j =0; $j < count($wirehouseProduct); $j++){
    	        if($wirehouseProduct[$j][0] == $salesProduct[$i] && $wirehouseProduct[$j][1] == $salesProduct[$i+9]){
    	            $wirehouseProduct[$j][2] = $wirehouseProduct[$j][2] + $salesProduct[$i+1]; 
    	            $updateWarehouseFlag= 1;
    	        }
    	    }
    	    if($i == 0){
    	        $j = 0;
    	    }
    	    
    	    if($updateWarehouseFlag == 0){
    	        $wirehouseProduct[$j][0] = $salesProduct[$i];
    	        $wirehouseProduct[$j][1] = $salesProduct[$i+9];
    	        $wirehouseProduct[$j][2] = $salesProduct[$i+1];
    	    }
    	}
    	$actualError = "";
    	//echo json_encode(print_r($wirehouseProduct));
    	for($i = 0; $i < count($wirehouseProduct); $i++){
    	    $productIdEntry = $wirehouseProduct[$i][0];
    	    $warehouse_idEntry = $wirehouseProduct[$i][1];
    	    $productQuantityEntry = $wirehouseProduct[$i][2];
    	    $sql = "SELECT SUM(currentStock) AS totalStock 
                    FROM tbl_currentStock
                    WHERE tbl_productsId='$productIdEntry' AND tbl_warehouseId='$warehouse_idEntry' AND deleted='No'";
            
            $result = $conn->query($sql);
            while($row = $result->fetch_assoc()){
                $totalStock = $row['totalStock'];
                if($totalStock < $productQuantityEntry){
                    $checkOverQuantity++;
                    $actualError .= $sql." ".$productQuantityEntry;
                }else{
                    $actualError .= "OK -> ".$totalStock.$productQuantityEntry;
                }
            }
    	}
    	//$checkOverQuantity = 1;
    	if($checkOverQuantity == 0){
        	$sql = "SELECT LPAD(max(salesOrderNo)+1, 6, 0) as salesCode from tbl_sales where type='PartySale'";
    		$query = $conn->query($sql);
    		while ($prow = $query->fetch_assoc()) {
    			$salesOrderNo = $prow['salesCode'];
    		}
    		if($salesOrderNo == ""){
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
                        WHERE tbl_partyId = '".$sales[1]."' AND customerType = 'Party' and deleted='No'";
    		$query = $conn->query($sql);
    		while ($prow = $query->fetch_assoc()) {
    			$previousDue = $prow['total'];
    		}
    		if($previousDue == ''){
    		    $previousDue = 0;
    		}
    		$totalDue = $previousDue - $sales[17]  + $sales[7];
    		$grandTotalAfterOffer = $sales[18];
    		$salesDate = $sales[0];
    		$paidAmount = $sales[17];
            $sql = "INSERT INTO tbl_sales (salesOrderNo, salesDate, tbl_customerId, tbl_userId, totalAmount, productDiscount, salesDiscount, totalDiscount, grandTotal, vat, ait, createdBy, type, paymentType, remarks, tbl_wareHouseId, carringCost, requisitionNo, tbl_transport_info, projectName, previousDue, paidAmount, totalDue, createdDate) 
        	        VALUES ('$salesOrderNo','$sales[0]','$sales[1]','$sales[2]','$sales[3]','$sales[4]','$sales[5]','$sales[6]','$grandTotalAfterOffer','$sales[8]','$sales[9]','$loginID','$sales[10]',
        	        '$sales[11]','$sales[12]','','$sales[13]','$sales[14]','$sales[15]','$sales[16]','$previousDue', '$sales[17]', '$totalDue', '$toDay')";
	        //echo json_encode($sql);
                	if($conn->query($sql)){
                	    $salesId = $conn->insert_id;
        				for($i = 0; $i < count($salesProduct); $i=$i+$ind){
        				    //$ind = $i + 1;
        				    if($salesProduct[$i] != ""){
            				    $productIdEntry = $salesProduct[$i];
        					    $qty = $salesProduct[$i+1];
        					    $productPriceEntry = $salesProduct[$i+3];
        					    $productTotal = $salesProduct[$i+4];
        					    $discountOfferValue = $salesProduct[$i+5];
        					    $grandTotal = $salesProduct[$i+6];
        					    $productDiscount = $salesProduct[$i+7];
        					    $discountId = $salesProduct[$i+8];
        					    $warehouse_idEntry = $salesProduct[$i+9];
            				    $sql = "INSERT INTO tbl_sales_products (tbl_salesId, tbl_productsId, quantity, units, createdBy, salesAmount, totalAmount, discount, grandTotal, remarks, tbl_wareHouseId,createdDate,tbl_discount_offer_id) 
        						        VALUES ($salesId,$productIdEntry,$qty,'','$loginID','$productPriceEntry','$productTotal','$discountOfferValue','$grandTotal',
        						        '$productDiscount','$warehouse_idEntry','$toDay','$discountId')";
        			            $conn->query($sql);
        			            $sql = "UPDATE tbl_currentStock 
        								    set salesStock=salesStock+$qty, currentStock=currentStock-$qty, lastUpdatedDate='$toDay', lastUpdatedBy='$loginID'
        								    where tbl_productsId = '$productIdEntry' AND tbl_wareHouseId='$warehouse_idEntry' AND deleted='No'";
        					    $query1 = $conn->query($sql);
        						if($conn->affected_rows == 0){
        							$sql = "insert into tbl_currentStock 
        							            (salesStock, currentStock, tbl_productsId, tbl_wareHouseId, entryBy,entryDate) values 
        							            ('$qty', '-$qty','$productIdEntry','$warehouse_idEntry','$loginID','$toDay')";
        							$query2 = $conn->query($sql);
        						}
        						if($query1 || $query2){
        						    $sql = "UPDATE tbl_products 
                                            SET saleTime=saleTime+1
                                            WHERE id='$productIdEntry' AND deleted='No' AND status='Active'";
                                    $conn->query($sql);
        						    
        						}
        				    }
        				}
						// Start Insert Sale Serialize Product
						foreach($_SESSION["wholeSaleShopping_cart"] as $keys => $values){
        					if ($_SESSION["wholeSaleShopping_cart"][$keys]["product_type"] == "serialize") {
								$product_id = $_SESSION["wholeSaleShopping_cart"][$keys]['product_id'];
								$warehouse_id = $_SESSION["wholeSaleShopping_cart"][$keys]['warehouse_id'];
        						foreach ($_SESSION["wholeSaleShopping_cart"][$keys]["serializeIdArray"] as $key => $serializeId) {
        							$serializeSaleQtyArray = $_SESSION["wholeSaleShopping_cart"][$keys]["serializeSaleQtyArray"];
									$sale_quantity = $serializeSaleQtyArray[$key];
        							if (empty($serializeId) || empty($serializeSaleQtyArray[$key])) {
        								continue;
        							}
									$sql_saleSerializeProduct = "INSERT INTO `sale_serialize_products`(`sale_id`, `product_id`, `warehouse_id`, `tbl_serialize_products_id`, `sale_quantity`, `created_by`, `created_date`) 
        						        VALUES ($salesId,$product_id,$warehouse_id,'$serializeId','$sale_quantity','$loginID','$toDay')";
        			            	$conn->query($sql_saleSerializeProduct);
								}
							}
						} 
						// End Insert sale Serialize Product
						//Start Update Serialize Product
						foreach($_SESSION["wholeSaleShopping_cart"] as $keys => $values){
        					if ($_SESSION["wholeSaleShopping_cart"][$keys]["product_type"] == "serialize") {
        						$quantity = 0;
        						foreach ($_SESSION["wholeSaleShopping_cart"][$keys]["serializeIdArray"] as $key => $serializeId) {
        							$serializeSaleQtyArray = $_SESSION["wholeSaleShopping_cart"][$keys]["serializeSaleQtyArray"];
        							if (empty($serializeId)) {
        								continue;
        							}
        							$sql_serializeProduct = "select * from tbl_serialize_products where id='$serializeId'";
        							$result_serializeProduct = $conn->query($sql_serializeProduct);
        							if ($result_serializeProduct->num_rows > 0) {
        							    while($row_serializeProduct = $result_serializeProduct->fetch_assoc()){
            								$totalSerializeQuantity = ($row_serializeProduct['used_quantity'] + $serializeSaleQtyArray[$key]);
            								$update_serialize = "Update tbl_serialize_products set used_quantity='$totalSerializeQuantity'";
            								if ($row_serializeProduct['quantity'] == $totalSerializeQuantity) {
            								    $update_serialize .= ", is_sold='OFF'";
            								}
            								$update_serialize .= " WHERE id='$serializeId'";
            								$conn->query($update_serialize);
        							    }
        							}
        						}
        					} 
    					}
						// End Update Serialize Product
        				if($error == 0){
        				    $customerType = 'Party';
        				    $sql = "SELECT LPAD(IFNULL(max(voucherNo),0)+1, 6, 0) as voucherCode, LPAD(IFNULL(max(voucherNo),0)+2, 6, 0) as voucherReceiveCode 
        				            FROM tbl_paymentVoucher 
        				            WHERE tbl_partyId='$customers' AND customerType = '$customerType'";
                    		$query = $conn->query($sql);
                    		while ($prow = $query->fetch_assoc()) {
                    			$voucherNo = $prow['voucherCode'];
                    			$voucherReceiveNo = $prow['voucherReceiveCode'];
                    		}
                    		if($voucherNo == ""){
                    		    $voucherNo = "000001";
                    		    $voucherReceiveNo = "000002";
                    		}
                    		//if ($grandTotalAfterOffer > 0){
            					$customers = $vouchers[0];
            					$grandTotalAfterOffer = $vouchers[1];
            					$paymentMethod = $vouchers[2];
            					$payable = $vouchers[3];
            					$type = $vouchers[4];
            					$sql = "INSERT INTO tbl_paymentVoucher (tbl_partyId, tbl_sales_id, amount, entryBy, paymentMethod, paymentDate, status, remarks, type, voucherType, voucherNo, customerType,entryDate) 
            							VALUES ('$customers', '$salesId', '$grandTotalAfterOffer', '$loginID', '$paymentMethod', '$salesDate', 'Active', 'Payable for Party Sales Code: $salesOrderNo', 'partyPayable', 'PartySale', '$voucherNo', '$customerType','$toDay')";
            					$conn->query($sql);
            				/*}else{
            				    $grandTotal = 0;
            				}*/
        				    if ($vouchers[5] != ""){
            					
            					$customers = $vouchers[5];
            					$paidAmount = $vouchers[6];
            					$paymentMethod = $vouchers[7];
            					$payable = $vouchers[8];
            					$type = $vouchers[9];
            					$sql = "INSERT INTO tbl_paymentVoucher (tbl_partyId, tbl_sales_id, amount, entryBy, paymentMethod, paymentDate, status, remarks, type, voucherType, voucherNo, customerType,entryDate) 
            							VALUES ('$customers', '$salesId', '$paidAmount', '$loginID', '$paymentMethod', '$salesDate', 'Active', 'payment for Party Sales Code: $salesOrderNo', 'paymentReceived', 'PartySale', '$voucherReceiveNo', '$customerType','$toDay')";
            					$conn->query($sql);
            				}else{
            				    $paidAmount = 0;
            				}
            				$conn->commit();
                        	unset($_SESSION["wholeSaleShopping_cart"]);
                			$data = array( 
                                        'msg'=>'Success', 
                                        'salesId'=>$salesId
                                        ); 
            			echo json_encode($data);
        				}else if($checkOverQuantity > 0){
        				    echo json_encode('Product Stock is not available');
        				    $conn->rollBack();
        				}else{
        				    echo json_encode(print_r($salesProductArray));
        				    $conn->rollBack();
        				}
                	
        	    }else{
        	        $error++;
    		        echo json_encode("Product quantity must be lower then available quantity 55".$sql);	    
    	            $conn->rollBack();	
        	    }
	    }else{
	        echo json_encode("Product quantity must be lower then available quantity 66".$actualError.print_r($wirehouseProduct));	 
	    }
		// End FinalSalesConfirmation
	}
	else if($_POST["action"] == 'check_out_cart'){
	    $error=0;
	    $loginID = $_SESSION['user'];
	    $salesDate = $_POST['salesDate'];
    	$customers= $_POST['customers'];
    	$salesMan=$_POST['salesMan'];
    	$totalAmount=$_POST['totalAmount'];
	    $totalProductDiscountInput=$_POST['totalProductDiscount'];
    	$salesDiscount=$_POST['salesDiscount'];
    	$totalDiscount=$_POST['totalDiscount'];
	    $grandTotal=$_POST['grandTotal'];
	    $grandTotal = floatval($grandTotal);
	    $grandTotal = round($grandTotal);
	    $paidAmount=$_POST['paidAmount'];
	    $paymentMethod=$_POST['paymentMethod'];
	    $vat=$_POST['vat'];
    	$ait=$_POST['ait'];
    	$transport = $_POST['transport'];
    	$projectName = $_POST['projectName'];
    	$remarks = $_POST['remarks'];
    	$carringCost=$_POST['carringCost'];
    	$requisitionNo=$_POST['requisitionNo'];
    	$wareHouse=$_POST['wareHouse'];
    	$productId = $_POST['productId'];
        $productQuantity = $_POST['productQuantity'];
        $productPrice = $_POST['productPrice'];
        $productDiscount = $_POST['productDiscount'];
        $productTotal = $_POST['productTotal'];
        $warehouse_ids = $_POST['warehouse_id'];
        $type=$_POST['type'];
        $productIdArray = explode("@!@,",$productId);
        $productQuantityArray = explode("@!@,",$productQuantity);
        $productPriceArray = explode("@!@,",$productPrice);
        $productDiscountArray = explode("@!@,",$productDiscount);
        $productTotalArray = explode("@!@,",$productTotal);
        $warehouse_idArray = explode("@!@,",$warehouse_ids);
    	$salesOrderNo='';
    	$total_price=0;
		$total_item=0;
		$totalProductDiscount = 0;
		$warehouseWiseProductArray = array(array());
		$voucherEntryArray = array(array());
		$salesEntry = array();
		$grandTotalAfterOffer = 0; // added by hamid
    	try{
    	    $checkOverQuantity = 0;
    	    if(isset($_SESSION["wholeSaleShopping_cart"])){
    	        $data = '';
    	        if(count($_SESSION["wholeSaleShopping_cart"]) == count($productIdArray)){
    	            $i = 0;
    	            $checkErrorManualMsg='';
    	            foreach($_SESSION["wholeSaleShopping_cart"] as $keys => $values){
    	                $warehouseWiseProductArray[$i]['product_id'] = $values['product_id'];
    	                $warehouseWiseProductArray[$i]['product_quantity'] = $values['product_quantity'];
    	                $warehouseWiseProductArray[$i]['warehouse_id'] = $values['warehouse_id'];
                        if (isset($newArray[$values['product_id']])) {
                            $newArray[$values['product_id']] = $newArray[$values['product_id']] + $values['product_quantity'];
                        } else {
                            $newArray[$values['product_id']] = $values['product_quantity'];
                        }
                        $i++;
    	            }
    	            //$checkOverQuantity++;
    	            //$checkErrorManualMsg = $newArray[0];
    	            //echo print_r($warehouseWiseProductArray);
    	        }else{
    	            $checkOverQuantity++;
    	            $checkErrorManualMsg = "Session and Cart data not matched... Please try again or contact with administrator.";
    	        }
    	        $checkErrorManual = 1;
    		}
    		$i = 0; 
    		$salesProductArray=array (array());
    		$newProductQtyArray=array (array());
    	    $row = 0;
    	    foreach($_SESSION["wholeSaleShopping_cart"] as $keys => $values){
    	        $i++;
    	        if($values['status'] == 0){
        	        $productIdEntry = $values['product_id'];
    				$productQuantityEntry =$newArray[$productIdEntry]; 
    				$productDiscountEntry = $values['product_discount'];
    				$productPriceEntry = $values['product_price'];
    				$product_name = str_replace(",",".",$values['product_name']);;
    				/*$warehouse_idEntry = $values[$i];
    				if($i == count($productIdArray)-1){
    					$productIdEntry = substr($productIdEntry, 0, strlen($productIdEntry)-3);
    					$productQuantityEntry = substr($productQuantityEntry, 0,strlen($productQuantityEntry)-3);
    					$warehouse_idEntry = substr($warehouse_idEntry, 0, strlen($warehouse_idEntry)-3);
    				}
    				if($productIdEntry != ''){
    				    $sql = "SELECT SUM(currentStock) AS totalStock 
                                FROM tbl_currentStock
                                WHERE tbl_productsId='$productIdEntry' AND tbl_warehouseId='$warehouse_idEntry'";
                        $result = $conn->query($sql);
                        while($row = $result->fetch_assoc()){
                            $totalStock = $row['totalStock'];
                            if($totalStock < $productQuantityEntry){
                                $checkOverQuantity++;
                            }
                        }
    				}*/
    				
    				
    				
    				$sql_discountOffer = "SELECT id,offer_applicable,offer_for,unit_for,discount,discount_unit,discount_2,discount_unit_2, offer_name
                                    FROM tbl_discount_offer 
									WHERE '$toDate' BETWEEN date_from AND date_to AND deleted = 'No' AND status='Active' AND tbl_products_id = '".$productIdEntry."' AND offer_applicable='Party' AND priority > 0
                                    ORDER BY offer_for DESC, priority DESC";
                            $result_discountOffer = $conn->query($sql_discountOffer);
                            $discount_pc = 0;
                            $discount_amount = 0;
                            $rest_pc = 0;
                            $rest_amount = 0;
                            $test = 0;
                            $discountOfferValue = 0;
                            $stock_check = 'On';
                            $total_quantity = $productQuantityEntry;
                            if($result_discountOffer->num_rows > 0 ){
                                while($row_discountOffer = $result_discountOffer->fetch_assoc()){
                                    $discountOfferid = $row_discountOffer['id'];
                                    if($row_discountOffer['unit_for'] == 'PC'){
                						if($row_discountOffer['discount_unit'] == 'PC'){
                							$discount_pc = floor($total_quantity / $row_discountOffer['offer_for']);
                							$discount_amount = '100%';
                							$rest_pc = $discount_pc * $row_discountOffer['offer_for'];
                								if($rest_pc > 0){
                									if($row_discountOffer['discount_unit_2'] == 'TK'){
                									    if($row_discountOffer['discount_2'] != 0){
                										$discountOfferValue = ($row_discountOffer['discount_2']*$discount_pc);
                									    }else{
                									        $discountOfferValue = $productDiscountEntry;
                									    }
                									}else{
                										$discountOfferValue = $productDiscountEntry;
                									}
                									$productTotal = $rest_pc * $productPriceEntry;
                									if($discountOfferValue != ""){
                										$lastValue = substr($discountOfferValue, -1);
                										if($lastValue == "%"){
                											$productDiscount = $productTotal * (substr($discountOfferValue, 0, -1)/100);
                										}else{
                											$productDiscount = $discountOfferValue;
                										}
                										$productTotal = $productTotal - $productDiscount;
                									}
                									if($discount_pc >= 1){
                        							    $discount_pc = $discount_pc * $row_discountOffer['discount'];
                        							}
                									/*$sql = "INSERT INTO tbl_sales_products (tbl_salesId, tbl_productsId, quantity, units, createdBy, salesAmount, totalAmount, discount, grandTotal, remarks, tbl_wareHouseId,createdDate) 
                            						        VALUES ('$salesId','$productIdEntry','$rest_pc','','$loginID','$productPriceEntry','$productTotal','$discountOfferValue','$productTotal','$productDiscount','$warehouse_idEntry','$toDay')";*/
                            						$calling_qty = $rest_pc;
                            						for($i = 0; $i < count($warehouseWiseProductArray); $i++){
                            						    if($warehouseWiseProductArray[$i]['product_id'] == $productIdEntry && $calling_qty > 0 && $warehouseWiseProductArray[$i]['product_quantity'] > 0){
                            						        if($warehouseWiseProductArray[$i]['product_quantity'] >= $calling_qty){
                            						            $salesProductArray[$row][0] = $productIdEntry;
                                        					    $salesProductArray[$row][1] = $calling_qty;
                                        					    $salesProductArray[$row][2] = '';
                                        					    $salesProductArray[$row][3] = $productPriceEntry;
                                        					    $salesProductArray[$row][4] = ($productTotal/$rest_pc)*$calling_qty;
                                        					    $salesProductArray[$row][5] = ($discountOfferValue/$rest_pc)*$calling_qty;
                                        					    $salesProductArray[$row][6] = ($productTotal/$rest_pc)*$calling_qty;
                                        					    $salesProductArray[$row][7] = ($productDiscount/$rest_pc)*$calling_qty;
                                        					    $salesProductArray[$row][8] = 0;
                                        					    $salesProductArray[$row][9] = $warehouseWiseProductArray[$i]['warehouse_id'];
                                        					    $salesProductArray[$row][10] = $row;
                                        					    $salesProductArray[$row][11] = $product_name;
                                        					    $salesProductArray[$row][12] = '';
                                        					    $row++;
                                        					    $warehouseWiseProductArray[$i]['product_quantity'] -= $calling_qty;
                                        					    $calling_qty = 0;
                            						        }else{
                            						            $salesProductArray[$row][0] = $productIdEntry;
                                        					    $salesProductArray[$row][1] = $warehouseWiseProductArray[$i]['product_quantity'];
                                        					    $salesProductArray[$row][2] = '';
                                        					    $salesProductArray[$row][3] = $productPriceEntry;
                                        					    $salesProductArray[$row][4] = ($productTotal/$rest_pc)*$warehouseWiseProductArray[$i]['product_quantity'];
                                        					    $salesProductArray[$row][5] = ($discountOfferValue/$rest_pc)*$warehouseWiseProductArray[$i]['product_quantity'];;
                                        					    $salesProductArray[$row][6] = ($productTotal/$rest_pc)*$warehouseWiseProductArray[$i]['product_quantity'];
                                        					    $salesProductArray[$row][7] = ($productDiscount/$rest_pc)*$warehouseWiseProductArray[$i]['product_quantity'];
                                        					    $salesProductArray[$row][8] = 0;
                                        					    $salesProductArray[$row][9] = $warehouseWiseProductArray[$i]['warehouse_id'];
                                        					    $salesProductArray[$row][10] = $row;
                                        					    $salesProductArray[$row][11] = $product_name;
                                        					    $salesProductArray[$row][12] = '';
                                        					    $row++;
                                        					    $calling_qty -= $warehouseWiseProductArray[$i]['product_quantity'];
                                        					    $warehouseWiseProductArray[$i]['product_quantity'] = 0;
                            						        }
                                    					    
                            						    }else if($calling_qty == 0){
                            						        break;
                            						    }
                            						}
                            					    $newProductQtyArray[$productIdEntry][0]=$productIdEntry;
                            					    $newProductQtyArray[$productIdEntry][1]+=$rest_pc;
                            						//if($conn->query($sql)){
                            						    $grandTotalAfterOffer += $productTotal;
                                						/*$sql = "UPDATE tbl_currentStock 
                                								    set salesStock=salesStock+$rest_pc, currentStock=currentStock-$rest_pc, lastUpdatedDate='$toDay', lastUpdatedBy='$loginID'
                                								    where tbl_productsId = '$productIdEntry' AND tbl_wareHouseId='$warehouse_idEntry'";
                                					    $query1 = $conn->query($sql);
                                						if($conn->affected_rows == 0){
                                							$sql = "insert into tbl_currentStock 
                                							            (salesStock, currentStock, tbl_productsId, tbl_wareHouseId, entryBy,entryDate) values 
                                							            ('$rest_pc', '-$rest_pc','$productIdEntry','$warehouse_idEntry','$loginID','$toDay')";
                                							$query2 = $conn->query($sql);
                                						}
                                						if($query1 || $query2){
                                						    $sql = "UPDATE tbl_products 
                                                                    SET saleTime=saleTime+1
                                                                    WHERE id='$productIdEntry' AND deleted='No' AND status='Active'";
                                                            $conn->query($sql);
                                						    
                                						}*/
                            						/*}else{
                            						    $error++;
                                                        echo json_encode($conn->error.$sql);	    
                                                        $conn->rollBack();		    
                            						}*/
                									$total_price = $total_price + $productTotal;
                									$total_item = $total_item + 1;
                									$totalProductDiscount = $totalProductDiscount + $productDiscount;
                								}
                										
                								if($discount_pc > 0){
                									$productTotal = $discount_pc * $productPriceEntry;
                									$productDiscount = $productTotal;
                									$productTotal = $productTotal - $productDiscount;
                									/*$sql = "INSERT INTO tbl_sales_products (tbl_salesId, tbl_productsId, quantity, units, createdBy, salesAmount, totalAmount, discount, grandTotal, remarks, tbl_wareHouseId,createdDate,tbl_discount_offer_id) 
                            						        VALUES ('$salesId','$productIdEntry','$discount_pc','','$loginID','$productPriceEntry','$productTotal','100%','$productTotal','$productDiscount','$warehouse_idEntry','$toDay','$discountOfferid')";*/
                            						$sql_availableWarehouse = "SELECT tbl_warehouse.id, tbl_warehouse.wareHouseName
                                                            FROM `tbl_currentStock`
                                                            INNER JOIN tbl_warehouse ON tbl_currentStock.tbl_wareHouseId = tbl_warehouse.id
                                                            WHERE tbl_currentStock.tbl_productsId = '$productIdEntry' AND tbl_currentStock.deleted = 'No' AND tbl_warehouse.deleted = 'No' AND tbl_currentStock.currentStock > '$discount_pc'";        
                                                    $result_availableWarehouse = $conn->query($sql_availableWarehouse);
                                                    $availableWarehouse = "<option value=''>Select Warehouse</option>";
                                                    while($row_availableWarehouse = $result_availableWarehouse->fetch_assoc()){
                                                        $availableWarehouse .= "<option value='".$row_availableWarehouse['id']."'>".$row_availableWarehouse['wareHouseName']."</option>";
                                                    }        
                    						        $salesProductArray[$row][0] = $productIdEntry;
                            					    $salesProductArray[$row][1] = $discount_pc;
                            					    $salesProductArray[$row][2] = '';
                            					    $salesProductArray[$row][3] = $productPriceEntry;
                            					    $salesProductArray[$row][4] = $productTotal;
                            					    $salesProductArray[$row][5] = '100%';
                            					    $salesProductArray[$row][6] = $productTotal;
                            					    $salesProductArray[$row][7] = $productDiscount;
                            					    $salesProductArray[$row][8] = $discountOfferid;
                            					    $salesProductArray[$row][9] = 0;
                            					    $salesProductArray[$row][10] = $row;
                            					    $salesProductArray[$row][11] = $product_name;
                            					    $salesProductArray[$row][12] = $availableWarehouse;
                            					    $row++;
                            					    $newProductQtyArray[$productIdEntry][0]=$productIdEntry;
                            					    $newProductQtyArray[$productIdEntry][1]+=$discount_pc;
                            						//if($conn->query($sql)){
                            						    $grandTotalAfterOffer += $productTotal;
                                						/*$sql = "UPDATE tbl_currentStock 
                                								    set salesStock=salesStock+$discount_pc, currentStock=currentStock-$discount_pc, lastUpdatedDate='$toDay', lastUpdatedBy='$loginID'
                                								    where tbl_productsId = '$productIdEntry' AND tbl_wareHouseId='$warehouse_idEntry'";
                                					    $query1 = $conn->query($sql);
                                						if($conn->affected_rows == 0){
                                							$sql = "insert into tbl_currentStock 
                                							            (salesStock, currentStock, tbl_productsId, tbl_wareHouseId, entryBy,entryDate) values 
                                							            ('$discount_pc', '-$discount_pc','$productIdEntry','$warehouse_idEntry','$loginID','$toDay')";
                                							$query2 = $conn->query($sql);
                                						}
                                						if($query1 || $query2){
                                						    $sql = "UPDATE tbl_products 
                                                                    SET saleTime=saleTime+1
                                                                    WHERE id='$productIdEntry' AND deleted='No' AND status='Active'";
                                                            $conn->query($sql);
                                						}*/
                            						/*}else{
                            						    $error++;
                                                        echo json_encode($conn->error.$sql);	    
                                                        $conn->rollBack();		    
                            						}*/
                									$total_price = $total_price + $productTotal;
                									$total_item = $total_item + 1;
                									$totalProductDiscount = $totalProductDiscount + $productDiscount;
                								}
                							
                							$total_quantity = $total_quantity - ($rest_pc);
                						}
                						
                						if($stock_check == 'On'){
                						   
                							if($row_discountOffer['discount_unit'] == '%'){
                								if($total_quantity >= $row_discountOffer['offer_for']){
                									$discount_quantity = $row_discountOffer['offer_for'] * floor($total_quantity / $row_discountOffer['offer_for']);
                									$productTotal = $discount_quantity * $productPriceEntry;
                									$productDiscount = $productTotal * ($row_discountOffer['discount']/100);
                									/*if($row_discountOffer['discount_unit_2'] == 'TK'){
                										$productDiscount += ($row_discountOffer['discount_2']*($discount_quantity / $row_discountOffer['offer_for']));
                									}*/
                									
                									
                									$productTotal = $productTotal - $productDiscount;
                									
            										/*$sql = "INSERT INTO tbl_sales_products (tbl_salesId, tbl_productsId, quantity, units, createdBy, salesAmount, totalAmount, discount, grandTotal, remarks, tbl_wareHouseId,createdDate, tbl_discount_offer_id) 
                            						        VALUES ('$salesId','$productIdEntry','$discount_quantity','','$loginID','$productPriceEntry','$productTotal','".$row_discountOffer['discount']."%','$productTotal','$productDiscount','$warehouse_idEntry','$toDay','$discountOfferid')";*/
                    						        /*$salesProductArray[$row][0] = $productIdEntry;
                            					    $salesProductArray[$row][1] = $discount_quantity;
                            					    $salesProductArray[$row][2] = '';
                            					    $salesProductArray[$row][3] = $productPriceEntry;
                            					    $salesProductArray[$row][4] = $productTotal;
                            					    $salesProductArray[$row][5] = $row_discountOffer['discount'].'%';
                            					    $salesProductArray[$row][6] = $productTotal;
                            					    $salesProductArray[$row][7] = $productDiscount;
                            					    $salesProductArray[$row][8] = $discountOfferid;
                            					    $row++;*/
                            					    $calling_qty = $discount_quantity;
                            						for($i = 0; $i < count($warehouseWiseProductArray); $i++){
                            						    if($warehouseWiseProductArray[$i]['product_id'] == $productIdEntry && $calling_qty > 0 && $warehouseWiseProductArray[$i]['product_quantity'] > 0){
                            						        if($warehouseWiseProductArray[$i]['product_quantity'] >= $calling_qty){
                            						            $salesProductArray[$row][0] = $productIdEntry;
                                        					    $salesProductArray[$row][1] = $calling_qty;
                                        					    $salesProductArray[$row][2] = '';
                                        					    $salesProductArray[$row][3] = $productPriceEntry;
                                        					    $salesProductArray[$row][4] = $productTotal;
                                        					    $salesProductArray[$row][5] = $row_discountOffer['discount'].'%';
                                        					    $salesProductArray[$row][6] = $productTotal;
                                        					    $salesProductArray[$row][7] = $productDiscount;
                                        					    $salesProductArray[$row][8] = $discountOfferid;
                                        					    $salesProductArray[$row][9] = $warehouseWiseProductArray[$i]['warehouse_id'];
                                        					    $salesProductArray[$row][10] = $row;
                                        					    $salesProductArray[$row][11] = $product_name;
                                        					    $salesProductArray[$row][12] = '';
                                        					    $row++;
                                        					    $warehouseWiseProductArray[$i]['product_quantity'] -= $calling_qty;
                                        					    $calling_qty = 0;
                            						        }else{
                            						            $salesProductArray[$row][0] = $productIdEntry;
                                        					    $salesProductArray[$row][1] = $warehouseWiseProductArray[$i]['product_quantity'];
                                        					    $salesProductArray[$row][2] = '';
                                        					    $salesProductArray[$row][3] = $productPriceEntry;
                                        					    $salesProductArray[$row][4] = $productTotal;
                                        					    $salesProductArray[$row][5] = $row_discountOffer['discount'].'%';
                                        					    $salesProductArray[$row][6] = $productTotal;
                                        					    $salesProductArray[$row][7] = $productDiscount;
                                        					    $salesProductArray[$row][8] = $discountOfferid;
                                        					    $salesProductArray[$row][9] = $warehouseWiseProductArray[$i]['warehouse_id'];
                                        					    $salesProductArray[$row][10] = $row;
                                        					    $salesProductArray[$row][11] = $product_name;
                                        					    $salesProductArray[$row][12] = '';
                                        					    $row++;
                                        					    $calling_qty -= $warehouseWiseProductArray[$i]['product_quantity'];
                                        					    $warehouseWiseProductArray[$i]['product_quantity'] = 0;
                            						        }
                                    					    
                            						    }else if($calling_qty == 0){
                            						        break;
                            						    }
                            						}
                            					    $newProductQtyArray[$productIdEntry][0]=$productIdEntry;
                            					    $newProductQtyArray[$productIdEntry][1]+=$discount_quantity;
                            						//if($conn->query($sql)){
                            						    $grandTotalAfterOffer += $productTotal;
                                						/*$sql = "UPDATE tbl_currentStock 
                                								    set salesStock=salesStock+$discount_quantity, currentStock=currentStock-$discount_quantity, lastUpdatedDate='$toDay', lastUpdatedBy='$loginID'
                                								    where tbl_productsId = '$productIdEntry' AND tbl_wareHouseId='$warehouse_idEntry'";
                                					    $query1 = $conn->query($sql);
                                						if($conn->affected_rows == 0){
                                							$sql = "insert into tbl_currentStock 
                                							            (salesStock, currentStock, tbl_productsId, tbl_wareHouseId, entryBy,entryDate) values 
                                							            ('$discount_quantity', '-$discount_quantity','$productIdEntry','$warehouse_idEntry','$loginID','$toDay')";
                                							$query2 = $conn->query($sql);
                                						}
                                						if($query1 || $query2){
                                						    $sql = "UPDATE tbl_products 
                                                                    SET saleTime=saleTime+1
                                                                    WHERE id='$productIdEntry' AND deleted='No' AND status='Active'";
                                                            $conn->query($sql);
                                						}*/
                            						/*}else{
                            						    $error++;
                                                        echo json_encode($conn->error.$sql);	    
                                                        $conn->rollBack();		    
                            						}*/
                										
                										
                									$total_price = $total_price + $productTotal;
                									$total_item = $total_item + 1;
                									$totalProductDiscount = $totalProductDiscount + $productDiscount;
                									$total_quantity = $total_quantity - $discount_quantity;
                								}
                							}
                							if($row_discountOffer['discount_unit'] == 'TK'){
                								if($total_quantity >= $row_discountOffer['offer_for']){
                									$discount_quantity = $row_discountOffer['offer_for'] * floor($total_quantity / $row_discountOffer['offer_for']);
                									$productDiscount = $row_discountOffer['discount']* floor($total_quantity / $row_discountOffer['offer_for']);
                									
                									$productTotal = ($discount_quantity * $productPriceEntry) - $productDiscount;
                								
                										
            										/*$sql = "INSERT INTO tbl_sales_products (tbl_salesId, tbl_productsId, quantity, units, createdBy, salesAmount, totalAmount, discount, grandTotal, remarks, tbl_wareHouseId,createdDate, tbl_discount_offer_id) 
                            						        VALUES ('$salesId','$productIdEntry','$discount_quantity','','$loginID','$productPriceEntry','$productTotal','$productDiscount','$productTotal','$productDiscount','$warehouse_idEntry','$toDay','$discountOfferid')";*/
                    						        /*$salesProductArray[$row][0] = $productIdEntry;
                            					    $salesProductArray[$row][1] = $discount_quantity;
                            					    $salesProductArray[$row][2] = '';
                            					    $salesProductArray[$row][3] = $productPriceEntry;
                            					    $salesProductArray[$row][4] = $productTotal;
                            					    $salesProductArray[$row][5] = $productDiscount;
                            					    $salesProductArray[$row][6] = $productTotal;
                            					    $salesProductArray[$row][7] = $productDiscount;
                            					    $salesProductArray[$row][8] = $discountOfferid;
                            					    $row++;*/
                            					    $calling_qty = $discount_quantity;
                            					    for($i = 0; $i < count($warehouseWiseProductArray); $i++){
                            						    if($warehouseWiseProductArray[$i]['product_id'] == $productIdEntry && $calling_qty > 0 && $warehouseWiseProductArray[$i]['product_quantity'] > 0){
                            						        if($warehouseWiseProductArray[$i]['product_quantity'] >= $calling_qty){
                            						            $salesProductArray[$row][0] = $productIdEntry;
                                        					    $salesProductArray[$row][1] = $calling_qty;
                                        					    $salesProductArray[$row][2] = '';
                                        					    $salesProductArray[$row][3] = $productPriceEntry;
                                        					    $salesProductArray[$row][4] = $productTotal;
                                        					    $salesProductArray[$row][5] = $productDiscount;
                                        					    $salesProductArray[$row][6] = $productTotal;
                                        					    $salesProductArray[$row][7] = $productDiscount;
                                        					    $salesProductArray[$row][8] = $discountOfferid;
                                        					    $salesProductArray[$row][9] = $warehouseWiseProductArray[$i]['warehouse_id'];
                                        					    $salesProductArray[$row][10] = $row;
                                        					    $salesProductArray[$row][11] = $product_name;
                                        					    $salesProductArray[$row][12] = '';
                                        					    $row++;
                                        					    $warehouseWiseProductArray[$i]['product_quantity'] -= $calling_qty;
                                        					    $calling_qty = 0;
                            						        }else{
                            						            $salesProductArray[$row][0] = $productIdEntry;
                                        					    $salesProductArray[$row][1] = $warehouseWiseProductArray[$i]['product_quantity'];
                                        					    $salesProductArray[$row][2] = '';
                                        					    $salesProductArray[$row][3] = $productPriceEntry;
                                        					    $salesProductArray[$row][4] = $productTotal;
                                        					    $salesProductArray[$row][5] = $productDiscount;
                                        					    $salesProductArray[$row][6] = $productTotal;
                                        					    $salesProductArray[$row][7] = $productDiscount;
                                        					    $salesProductArray[$row][8] = $discountOfferid;
                                        					    $salesProductArray[$row][9] = $warehouseWiseProductArray[$i]['warehouse_id'];
                                        					    $salesProductArray[$row][10] = $row;
                                        					    $salesProductArray[$row][11] = $product_name;
                                        					    $salesProductArray[$row][12] = '';
                                        					    $row++;
                                        					    $calling_qty -= $warehouseWiseProductArray[$i]['product_quantity'];
                                        					    $warehouseWiseProductArray[$i]['product_quantity'] = 0;
                            						        }
                                    					    
                            						    }else if($calling_qty == 0){
                            						        break;
                            						    }
                            						}
                            					    $newProductQtyArray[$productIdEntry][0]=$productIdEntry;
                            					    $newProductQtyArray[$productIdEntry][1]+=$discount_quantity;
                            						//if($conn->query($sql)){
                            						    $grandTotalAfterOffer += $productTotal;
                                						/*$sql = "UPDATE tbl_currentStock 
                                								    set salesStock=salesStock+$discount_quantity, currentStock=currentStock-$discount_quantity, lastUpdatedDate='$toDay', lastUpdatedBy='$loginID'
                                								    where tbl_productsId = '$productIdEntry' AND tbl_wareHouseId='$warehouse_idEntry'";
                                					    $query1 = $conn->query($sql);
                                						if($conn->affected_rows == 0){
                                							$sql = "insert into tbl_currentStock 
                                							            (salesStock, currentStock, tbl_productsId, tbl_wareHouseId, entryBy,entryDate) values 
                                							            ('$discount_quantity', '-$discount_quantity','$productIdEntry','$warehouse_idEntry','$loginID','$toDay')";
                                							$query2 = $conn->query($sql);
                                						}
                                						if($query1 || $query2){
                                						    $sql = "UPDATE tbl_products 
                                                                    SET saleTime=saleTime+1
                                                                    WHERE id='$productIdEntry' AND deleted='No' AND status='Active'";
                                                            $conn->query($sql);
                                						    
                                						}*/
                            						/*}else{
                            						    $error++;
                                                        echo json_encode($conn->error.$sql);	    
                                                        $conn->rollBack();		    
                            						}*/
                									$total_price = $total_price + $productTotal;
                									$total_item = $total_item + 1;
                									$totalProductDiscount = $totalProductDiscount + ($productDiscount*$total_quantity);
                									$total_quantity = $total_quantity - $discount_quantity;
                									
                								}
                							}
                						}
                								
                					}
                                }
                            }else{
                                $rest_pc = $total_quantity;
                                $discount_pc = 0;
                                $discount_amount = 0;
                            }
                    	    $productDiscount = 0;
                    	    if($total_quantity > 0){
                        		
                    	        $productTotal = $total_quantity * $productPriceEntry;
                        	    if($productDiscountEntry != ""){
                        		    $lastValue = substr($productDiscountEntry, -1);
                        		    if($lastValue == "%"){
                        		        $productDiscount = $productTotal * (substr($productDiscountEntry, 0, -1)/100);
                        		    }else{
                        		        $productDiscount = $productDiscountEntry;
                        		    }
                        		    $productTotal = $productTotal - $productDiscount;
                        	    }
                        		
                        		/*$sql = "INSERT INTO tbl_sales_products (tbl_salesId, tbl_productsId, quantity, units, createdBy, salesAmount, totalAmount, discount, grandTotal, remarks, tbl_wareHouseId,createdDate) 
        						        VALUES ('$salesId','$productIdEntry','$total_quantity','','$loginID','$productPriceEntry','$productTotal','$productDiscountEntry','$productTotal','$productDiscount','$warehouse_idEntry','$toDay')";*/
					            /*$salesProductArray[$row][0] = $productIdEntry;
        					    $salesProductArray[$row][1] = $total_quantity;
        					    $salesProductArray[$row][2] = '';
        					    $salesProductArray[$row][3] = $productPriceEntry;
        					    $salesProductArray[$row][4] = $productTotal;
        					    $salesProductArray[$row][5] = $productDiscountEntry;
        					    $salesProductArray[$row][6] = $productTotal;
        					    $salesProductArray[$row][7] = $productDiscount;
        					    $salesProductArray[$row][8] = 0;
        					    $row++;*/
        					    $calling_qty = $total_quantity;
        						for($i = 0; $i < count($warehouseWiseProductArray); $i++){
        						    if($warehouseWiseProductArray[$i]['product_id'] == $productIdEntry && $calling_qty > 0 && $warehouseWiseProductArray[$i]['product_quantity'] > 0){
        						        if($warehouseWiseProductArray[$i]['product_quantity'] >= $calling_qty){
        						            $salesProductArray[$row][0] = $productIdEntry;
                    					    $salesProductArray[$row][1] = $calling_qty;
                    					    $salesProductArray[$row][2] = '';
                    					    $salesProductArray[$row][3] = $productPriceEntry;
                    					    $salesProductArray[$row][4] = ($productTotal/$total_quantity)*$calling_qty;
                    					    $salesProductArray[$row][5] = ($productDiscount/$total_quantity)*$calling_qty;
                    					    $salesProductArray[$row][6] = ($productTotal/$total_quantity)*$calling_qty;
                    					    $salesProductArray[$row][7] = ($productDiscount/$total_quantity)*$calling_qty;
                    					    $salesProductArray[$row][8] = 0;
                    					    $salesProductArray[$row][9] = $warehouseWiseProductArray[$i]['warehouse_id'];
                    					    $salesProductArray[$row][10] = $row;
                    					    $salesProductArray[$row][11] = $product_name;
                    					    $salesProductArray[$row][12] = '';
                    					    $row++;
                    					    $warehouseWiseProductArray[$i]['product_quantity'] -= $calling_qty;
                    					    $calling_qty = 0;
        						        }else{
        						            $salesProductArray[$row][0] = $productIdEntry;
                    					    $salesProductArray[$row][1] = $warehouseWiseProductArray[$i]['product_quantity'];
                    					    $salesProductArray[$row][2] = '';
                    					    $salesProductArray[$row][3] = $productPriceEntry;
                    					    $salesProductArray[$row][4] = ($productTotal/$total_quantity)*$warehouseWiseProductArray[$i]['product_quantity'];
                    					    $salesProductArray[$row][5] = ($productDiscount/$total_quantity)*$warehouseWiseProductArray[$i]['product_quantity'];
                    					    $salesProductArray[$row][6] = ($productTotal/$total_quantity)*$warehouseWiseProductArray[$i]['product_quantity'];
                    					    $salesProductArray[$row][7] = ($productDiscount/$total_quantity)*$warehouseWiseProductArray[$i]['product_quantity'];
                    					    $salesProductArray[$row][8] = 0;
                    					    $salesProductArray[$row][9] = $warehouseWiseProductArray[$i]['warehouse_id'];
                    					    $salesProductArray[$row][10] = $row;
                    					    $salesProductArray[$row][11] = $product_name;
                    					    $salesProductArray[$row][12] = '';
                    					    $row++;
                    					    $calling_qty -= $warehouseWiseProductArray[$i]['product_quantity'];
                    					    $warehouseWiseProductArray[$i]['product_quantity'] = 0;
        						            /*$salesProductArray[$row][0] = $productIdEntry;
                    					    $salesProductArray[$row][1] = $warehouseWiseProductArray[$i]['product_quantity'];
                    					    $salesProductArray[$row][2] = '';
                    					    $salesProductArray[$row][3] = $productPriceEntry;
                    					    $salesProductArray[$row][4] = $productTotal;
                    					    $salesProductArray[$row][5] = $discountOfferValue;
                    					    $salesProductArray[$row][6] = $productTotal;
                    					    $salesProductArray[$row][7] = $productDiscount;
                    					    $salesProductArray[$row][8] = 0;
                    					    $salesProductArray[$row][9] = $warehouseWiseProductArray[$i]['warehouse_id'];
                    					    $salesProductArray[$row][10] = $row;
                    					    $salesProductArray[$row][11] = $product_name;
                    					    $salesProductArray[$row][12] = '';
                    					    $row++;
                    					    $calling_qty -= $warehouseWiseProductArray[$i]['product_quantity'];
                    					    $warehouseWiseProductArray[$i]['product_quantity'] = 0;*/
        						        }
        						    }else if($calling_qty == 0){
        						        break;
        						    }
        						}
        					    $newProductQtyArray[$productIdEntry][0]=$productIdEntry;
        					    //$newProductQtyArray[$productIdEntry][1]+=$total_quantity;
								$newProductQtyArray[$productIdEntry][0]+=$total_quantity; // added by hamid
        						//if($conn->query($sql)){
        						    $grandTotalAfterOffer += $productTotal;
            						/*$sql = "UPDATE tbl_currentStock 
            								    set salesStock=salesStock+$total_quantity, currentStock=currentStock-$total_quantity, lastUpdatedDate='$toDay', lastUpdatedBy='$loginID'
            								    where tbl_productsId = '$productIdEntry' AND tbl_wareHouseId='$warehouse_idEntry'";
            					    $query1 = $conn->query($sql);
            						if($conn->affected_rows == 0){
            							$sql = "insert into tbl_currentStock 
            							            (salesStock, currentStock, tbl_productsId, tbl_wareHouseId, entryBy,entryDate) values 
            							            ('$total_quantity', '-$total_quantity','$productIdEntry','$warehouse_idEntry','$loginID','$toDay')";
            							$query2 = $conn->query($sql);
            						}
            						if($query1 || $query2){
            						    $sql = "UPDATE tbl_products 
                                                SET saleTime=saleTime+1
                                                WHERE id='$productIdEntry' AND deleted='No' AND status='Active'";
                                        $conn->query($sql);
            						    
            						}*/
        						/*}else{
        						    $error++;
                                    echo json_encode($conn->error.$sql);	    
                                    $conn->rollBack();		    
        						}*/
                        		$total_price = $total_price + $productTotal;
                        		$total_item = $total_item + 1;
                                $totalProductDiscount = $totalProductDiscount + $productDiscount;
                    	    }
    	        }
    	    }
    	    if ($checkOverQuantity == 0){
        		$conn->begin_transaction();
        		/*$sql = "SELECT LPAD(max(salesOrderNo)+1, 6, 0) as salesCode from tbl_sales where type='PartySale'";
        		$query = $conn->query($sql);
        		while ($prow = $query->fetch_assoc()) {
        			$salesOrderNo = $prow['salesCode'];
        		}
        		if($salesOrderNo == ""){
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
                    WHERE tbl_partyId = '".$customers."' AND customerType = 'Party' and deleted='No'";
        		$query = $conn->query($sql);
        		while ($prow = $query->fetch_assoc()) {
        			$previousDue = $prow['total'];
        		}
        		if($previousDue == ''){
        		    $previousDue = 0;
        		}
        		$totalDue = $previousDue - $paidAmount  + $grandTotal;*/
        		//$grandTotalAfterOffer=0;
        		$salesEntry[0]=$salesDate;
        		$salesEntry[1]=$customers;
        		$salesEntry[2]=$salesMan;
        		$salesEntry[3]=$totalAmount;
        		$salesEntry[4]=$totalProductDiscount;
        		$salesEntry[5]=$salesDiscount;
        		$salesEntry[6]=$totalDiscount;
        		$salesEntry[7]=$grandTotal;
        		$salesEntry[8]=$vat;
        		$salesEntry[9]=$ait;
        		$salesEntry[10]=$type;
        		$salesEntry[11]=$paymentMethod;
        		$salesEntry[12]=$remarks;
        		$salesEntry[13]=$carringCost;
        		$salesEntry[14]=$requisitionNo;
        		$salesEntry[15]=$transport;
        		$salesEntry[16]=$projectName;
        		$salesEntry[17]=$paidAmount;
        		
            	/*$sql = "INSERT INTO tbl_sales (salesOrderNo, salesDate, tbl_customerId, tbl_userId, totalAmount, productDiscount, salesDiscount, totalDiscount, grandTotal, vat, ait, createdBy, type, paymentType, remarks, tbl_wareHouseId, carringCost, requisitionNo, tbl_transport_info, projectName, previousDue, paidAmount, totalDue, createdDate) 
            	        VALUES ('$salesOrderNo','$salesDate','$customers','$salesMan','$totalAmount','$totalProductDiscount','$salesDiscount','$totalDiscount',
            	        '$grandTotal','$vat','$ait','$loginID','$type','$paymentMethod','$remarks','$wareHouse','$carringCost', '$requisitionNo', '$transport', '$projectName', 
            	        '$previousDue', '$paidAmount', '$totalDue', '$toDay')";
            	if($conn->query($sql)){
            	    $salesId = $conn->insert_id;*/
            	    /*for($i = 0; $i < count($productIdArray); $i++) {
    					$productIdEntry = $productIdArray[$i];
    					$productQuantityEntry =$productQuantityArray[$i]; 
    					$productPriceEntry = $productPriceArray[$i];
    					$productDiscountEntry =$productDiscountArray[$i]; 
    					$productTotalEntry = $productTotalArray[$i];
    					$warehouse_idEntry = $warehouse_idArray[$i];
    					if($i == count($productIdArray)-1){
    						$productIdEntry = substr($productIdEntry, 0, strlen($productIdEntry)-3);
    						$productQuantityEntry = substr($productQuantityEntry, 0,strlen($productQuantityEntry)-3);
    						$productPriceEntry = substr($productPriceEntry, 0, strlen($productPriceEntry)-3);
    						$productDiscountEntry = substr($productDiscountEntry, 0,strlen($productDiscountEntry)-3);
    						$productTotalEntry = substr($productTotalEntry, 0, strlen($productTotalEntry)-3);
    						$warehouse_idEntry = substr($warehouse_idEntry, 0, strlen($warehouse_idEntry)-3);
    					}
    					if($productIdEntry != ''){
    					    $total = $productQuantityEntry*$productPriceEntry;
    					    if(substr($productDiscountEntry, -1) == '%'){
    					        $discountAmount = $total*(substr($productDiscountEntry,0,-1)/100);
    					    }else{
    					        $discountAmount = $productDiscountEntry;
    					    }
    					     
    					    
    					    
    					}
    				}*/
    				/*foreach($newProductQtyArray as $rowData){
    				    foreach($_SESSION["wholeSaleShopping_cart"] as $keys => $values){
    				        if($values['product_id'] == $rowData[0]){
        				        $sql = "SELECT SUM(currentStock) AS totalStock 
                                        FROM tbl_currentStock
                                        WHERE tbl_productsId='".$rowData[0]."' AND tbl_warehouseId='".$values['warehouse_id']."'";
                                $result = $conn->query($sql);
                                while($row = $result->fetch_assoc()){
                                    $totalStock = $row['totalStock'];
                                    if($totalStock < $rowData[1]){
                                        $checkOverQuantity++;
                                        $error = 1;
                                    }
                                }
    				        }
    				    }
    				}*/
    				
    				if($error == 0){
    				    /*$customerType = 'Party';
    				    $sql = "SELECT LPAD(IFNULL(max(voucherNo),0)+1, 6, 0) as voucherCode, LPAD(IFNULL(max(voucherNo),0)+2, 6, 0) as voucherReceiveCode 
    				            FROM tbl_paymentVoucher 
    				            WHERE tbl_partyId='$customers' AND customerType = '$customerType'";
                		$query = $conn->query($sql);
                		while ($prow = $query->fetch_assoc()) {
                			$voucherNo = $prow['voucherCode'];
                			$voucherReceiveNo = $prow['voucherReceiveCode'];
                		}
                		if($voucherNo == ""){
                		    $voucherNo = "000001";
                		    $voucherReceiveNo = "000002";
                		}*/
                		//if ($grandTotalAfterOffer > 0){
                		    $grandTotalAfterOffer = round($grandTotalAfterOffer - $salesDiscount + $vat + $ait + $carringCost);
        				    /*$sql = "UPDATE tbl_sales set grandTotal='$grandTotalAfterOffer' where id='$salesId'";
        				    $conn->query($sql);*/
        				    $salesEntry[18]=$grandTotalAfterOffer;
        				    /*$sql = "INSERT INTO tbl_paymentVoucher (tbl_partyId, tbl_sales_id, amount, entryBy, paymentMethod, paymentDate, status, remarks, type, voucherType, voucherNo, customerType,entryDate) 
        							VALUES ('$customers', '$salesId', '$grandTotalAfterOffer', '$loginID', '$paymentMethod', '$salesDate', 'Active', 'Payable for Party Sales Code: $salesOrderNo', 'partyPayable', 'PartySale', '$voucherNo', '$customerType','$toDay')";
        					$conn->query($sql);*/
        					$voucherEntryArray[0][0] = $customers;
        					$voucherEntryArray[0][1] = $grandTotalAfterOffer;
        					$voucherEntryArray[0][2] = $paymentMethod;
        					$voucherEntryArray[0][3] = 'partyPayable';
        					$voucherEntryArray[0][4] = 'PartySale';
        				/*}else{
        				    $grandTotal = 0;
        				}*/
    				    if ($paidAmount > 0){
        					/*$sql = "INSERT INTO tbl_paymentVoucher (tbl_partyId, tbl_sales_id, amount, entryBy, paymentMethod, paymentDate, status, remarks, type, voucherType, voucherNo, customerType,entryDate) 
        							VALUES ('$customers', '$salesId', '$paidAmount', '$loginID', '$paymentMethod', '$salesDate', 'Active', 'payment for Party Sales Code: $salesOrderNo', 'paymentReceived', 'PartySale', '$voucherReceiveNo', '$customerType','$toDay')";
        					$conn->query($sql);*/
        					$voucherEntryArray[1][0] = $customers;
        					$voucherEntryArray[1][1] = $paidAmount;
        					$voucherEntryArray[1][2] = $paymentMethod;
        					$voucherEntryArray[1][3] = 'paymentReceived';
        					$voucherEntryArray[1][4] = 'PartySale';
        				}else{
        				    $paidAmount = 0;
        				}
        				
        				$conn->commit();
                    	//unset($_SESSION["wholeSaleShopping_cart"]);
                    	$data = array( 
                                        'msg'=>'Success', 
                                        'sales'=>$salesEntry,
                                        'salesProduct'=>$salesProductArray,
                                        'vouchers'=>$voucherEntryArray
                                        ); 
            			echo json_encode($data);
    				}else if($checkOverQuantity > 0){
    				    echo json_encode('Product Stock is not available');
    				    $conn->rollBack();
    				}else{
    				    echo json_encode(print_r($salesProductArray));
    				    $conn->rollBack();
    				}
            	/*}else{
            	    $error++;
    		        echo json_encode($conn->error.$sql);	    
    	            $conn->rollBack();		    
            	}*/
    	    }else if($checkErrorManual == 1){
    	        $error++;
		        echo json_encode($checkErrorManualMsg);	    
	            $conn->rollBack();	
    	    }else{
    	        $error++;
		        echo json_encode("Product quantity must be lower then available quantity");	    
	            $conn->rollBack();	
    	    }
    	}catch(Exception $e){
    		$conn->rollBack();
    		echo json_encode('RollBack');
    	}
		// End check_out_cart
	}
	else if($_POST["action"] == 'deleteSales'){
	    $id = $_POST['id'];
	    $sql = "SELECT id 
                FROM tbl_sales_return
                WHERE deleted = 'No' AND tbl_sales_id = '$id'";
	    $result = $conn->query($sql);
	    if($result -> num_rows > 0){
	        echo json_encode("Not possible to delete because this invoice have sales");
	    }else{
	        try{
                $loginID = $_SESSION['user'];
                $sql = "SELECT tbl_productsId, tbl_wareHouseId, quantity 
                        FROM tbl_sales_products 
                        WHERE tbl_salesId='$id' AND deleted='No'";
                $resultSalesProducts = $conn->query($sql);
                
                $conn->begin_transaction();
                $sql = "UPDATE tbl_paymentVoucher 
                        set deleted='Yes', deletedBy='$loginID', deletedDate='$toDay' 
                        WHERE tbl_sales_id='$id' AND voucherType='PartySale'";
                $conn->query($sql);
                while($row = $resultSalesProducts->fetch_assoc()){
                    $quantity = $row['quantity'];
                    $tbl_productsId = $row['tbl_productsId'];
                    $tbl_wareHouseId = $row['tbl_wareHouseId'];
                    $sql = "UPDATE tbl_currentStock 
                            SET currentStock = currentStock+$quantity, salesDelete = salesDelete+$quantity, lastUpdatedDate='$toDay', lastUpdatedBy='$loginID' 
                            WHERE tbl_productsId='$tbl_productsId' AND tbl_wareHouseId='$tbl_wareHouseId' AND deleted='No'";
                    $conn->query($sql);
                }
                $sql = "UPDATE tbl_sales_products 
                        SET deleted='Yes', deletedBy='$loginID', deletedDate='$toDay' 
                        WHERE tbl_salesId='$id' AND deleted='No'";
                if($conn->query($sql)){
                    $sql = "UPDATE tbl_sales 
                            SET deleted='Yes', deletedBy='$loginID', deletedDate='$toDay' 
                            WHERE id='$id'";
                    if($conn->query($sql)){
                       
                        $conn->commit();
                        echo json_encode('Success');     
                    }else{
                        echo json_encode('Error: '.$conn->error());
                    }
                }else{
                    echo json_encode('Error: '.$conn->error());
                }
            }catch(Exception $e){
                $conn->rollBack();
                echo json_encode($e->getMessage()); 
            }
	    }
	}
	else if($_POST['action'] == 'deleteFS'){
	    $id = $_POST['id'];
	    
        try{
            $loginID = $_SESSION['user'];
            $sql = "SELECT tbl_productsId, tbl_wareHouseId, quantity, tbl_TSProductsId 
                    FROM tbl_sales_products 
                    WHERE tbl_salesId='$id' AND deleted='No'";
            $resultSalesProducts = $conn->query($sql);
            $conn->begin_transaction();
            $sql = "UPDATE tbl_paymentVoucher 
                    set deleted='Yes', deletedBy='$loginID', deletedDate='$toDay' 
                    WHERE tbl_sales_id='$id' ";
            $conn->query($sql);
            while($row = $resultSalesProducts->fetch_assoc()){
                $quantity = $row['quantity'];
                $tbl_productsId = $row['tbl_productsId'];
                $tbl_wareHouseId = $row['tbl_wareHouseId'];
                $tSalesProductsId = $row['tbl_TSProductsId'];
                
                
                $sql = "UPDATE tbl_tsalesproducts
                        SET soldQuantity = soldQuantity-$quantity, status='Running'
                        WHERE id = '$tSalesProductsId'";
                $conn->query($sql);
            }
            $sql = "UPDATE tbl_sales_products 
                    SET deleted='Yes', deletedBy='$loginID', deletedDate='$toDay' 
                    WHERE tbl_salesId='$id' AND deleted='No'";
            if($conn->query($sql)){
                $sql = "UPDATE tbl_sales 
                        SET deleted='Yes', deletedBy='$loginID', deletedDate='$toDay' 
                        WHERE id='$id'";
                if($conn->query($sql)){
                    
                    $conn->commit();
                    echo json_encode('Success');     
                }else{
                    echo json_encode('Error: '.$conn->error());
                }
            }else{
                echo json_encode('Error: '.$conn->error());
            }
        }catch(Exception $e){
            $conn->rollBack();
            echo json_encode($e->getMessage()); 
        }
	    //}
	}
	else if($_POST["action"] == 'previousPrice'){
	    $dataString = '<table><thead>
	                        <th style="width: 10%;" class="col-md-2">Date</th>
	                        <th style="width: 10%;" class="col-md-2">Sales Invoice</th>
                            <th class="col-md-2">Product Name - Pcode</th> 
                            <th style="width: 6%;" class="col-md-1">Quantity</th>
                            <th style="width: 8%;" class="col-md-2">Unit Price</th>
                            <th style="width: 7%;" class="col-md-2">Discount</th>
                            <th style="width: 10%;" class="col-md-2">Total</th>
                        </thead>';
	    $loginID = $_SESSION['user'];
	    $salesDate = $_POST['salesDate'];
    	$customers= $_POST['customers'];
    	$productId = $_POST['productId'];
        $productIdArray = explode("@!@,",$productId);
        for($i = 0; $i < count($productIdArray); $i++) {
			$productIdEntry = $productIdArray[$i];
			if($i == count($productIdArray)-1){
				$productIdEntry = substr($productIdEntry, 0, strlen($productIdEntry)-3);
			}
			if($productIdEntry != ''){
			    $sql = "SELECT tbl_sales.salesDate,tbl_sales.salesOrderNo,tbl_sales.id, tbl_products.productCode, tbl_products.productName, tbl_products.modelNo, tbl_sales_products.quantity, tbl_sales_products.salesAmount, tbl_sales_products.discount, tbl_sales_products.remarks, tbl_sales_products.grandTotal  
                        FROM tbl_sales 
                        LEFT OUTER JOIN tbl_sales_products ON tbl_sales_products.tbl_salesId = tbl_sales.id AND tbl_sales_products.deleted = 'No'
                        LEFT OUTER JOIN tbl_products ON tbl_sales_products.tbl_productsId = tbl_products.id AND tbl_products.deleted = 'No'
                        WHERE tbl_sales.tbl_customerId = '".$customers."' AND tbl_sales_products.tbl_productsId = '".$productIdEntry."'
                        ORDER BY tbl_sales.createdDate DESC Limit 1";
                        
                $result = $conn->query($sql);
                
                while($row = $result->fetch_assoc()){
                    $salesId = $row['id'];
                    $salesDate = $row['salesDate'];
                    $salesOrderNo = $row['salesOrderNo'];
                    $productName = $row['productName'].' - '.$row['productCode'];
                    $quantity = $row['quantity'];
                    $unitPrice = $row['salesAmount'];
                    $discount = $row['remarks'];
                    $discountAmount = $row['discount'];
                    $totalAmount = $row['grandTotal'];
                    $dataString .= "<tr><td>$salesDate</td><td><a href='../wholesalesViewDetails.php?id=".$salesId."' target='_blank'>$salesOrderNo</td><td>$productName</td><td>$quantity</td><td>$unitPrice</td><td>$discount</td><td>$totalAmount</td></tr>";
                }
			}
        }
        $dataString .= "</table>";
        echo $dataString;
	}
	else if($_POST["action"] == 'previousPriceSingle'){
	    $dataString = "<table>";
	    $loginID = $_SESSION['user'];
    	$customers= $_POST['customers'];
    	$productId = $_POST['productId'];
		if($productId != ''){
		    $sql = "SELECT tbl_sales.salesDate,tbl_sales.salesOrderNo,tbl_sales.id, tbl_products.productCode, tbl_products.productName, tbl_products.modelNo, tbl_sales_products.quantity, tbl_sales_products.salesAmount, tbl_sales_products.discount, tbl_sales_products.remarks, tbl_sales_products.grandTotal  
                    FROM tbl_sales 
                    LEFT OUTER JOIN tbl_sales_products ON tbl_sales_products.tbl_salesId = tbl_sales.id AND tbl_sales_products.deleted = 'No'
                    LEFT OUTER JOIN tbl_products ON tbl_sales_products.tbl_productsId = tbl_products.id AND tbl_products.deleted = 'No'
                    WHERE tbl_sales.tbl_customerId = '".$customers."' AND tbl_sales_products.tbl_productsId = '".$productId."'
                    ORDER BY tbl_sales.createdDate DESC Limit 1";
                    
            $result = $conn->query($sql);
            
            while($row = $result->fetch_assoc()){
                $salesId = $row['id'];
                $salesDate = $row['salesDate'];
                $salesOrderNo = $row['salesOrderNo'];
                $productName = $row['productName'].' - '.$row['productCode'];
                $quantity = $row['quantity'];
                $unitPrice = $row['salesAmount'];
                $discount = $row['remarks'];
                $discountAmount = $row['discount'];
                $totalAmount = $row['grandTotal'];
                $dataString .= "<tr><td>Date: </td><td>$salesDate</td></tr>
                                <tr><td>Invoice No: </td><td><a href='../wholesalesViewDetails.php?id=".$salesId."' target='_blank'>$salesOrderNo</td></tr>
                                <tr><td>Product Name: </td><td>$productName</td></tr>
                                <tr><td>Quantity: </td><td>$quantity</td></tr>
                                <tr><td>Unit Price: </td><td>$unitPrice</td></tr>
                                <tr><td>Discount: </td><td>$discount</td></tr>
                                <tr><td>Total Amount: </td><td>$totalAmount</td></tr>";
            }
            if($salesId == ''){
                $dataString .= "<tr><td colspan='2'>No data found</td></tr>";        
            }
		}
        $dataString .= "</table>";
        echo $dataString;
	}
	else if($_POST["action"] == 'previousPriceSingleOrder'){
	    $dataString = "<table>";
	    $loginID = $_SESSION['user'];
    	$customers= $_POST['customers'];
    	$productId = $_POST['productId'];
		if($productId != ''){
		    $sql = "SELECT tbl_sales.salesDate,tbl_sales.salesOrderNo,tbl_sales.id, tbl_products.productCode, tbl_products.productName, tbl_products.modelNo, tbl_sales_products.quantity, tbl_sales_products.salesAmount, tbl_sales_products.discount, tbl_sales_products.remarks, tbl_sales_products.grandTotal  
                    FROM tbl_sales 
                    LEFT OUTER JOIN tbl_sales_products ON tbl_sales_products.tbl_salesId = tbl_sales.id AND tbl_sales_products.deleted = 'No'
                    LEFT OUTER JOIN tbl_products ON tbl_sales_products.tbl_productsId = tbl_products.id AND tbl_products.deleted = 'No'
                    WHERE tbl_sales.tbl_customerId = '".$customers."' AND tbl_sales_products.tbl_productsId = '".$productId."'
                    ORDER BY tbl_sales.createdDate DESC Limit 1";
           // echo $sql;          
            $result = $conn->query($sql);
            
            while($row = $result->fetch_assoc()){
                $salesId = $row['id'];
                $salesDate = $row['salesDate'];
                $salesOrderNo = $row['salesOrderNo'];
                $productName = $row['productName'].' - '.$row['productCode'];
                $quantity = $row['quantity'];
                $unitPrice = $row['salesAmount'];
                $discount = $row['remarks'];
                $discountAmount = $row['discount'];
                $totalAmount = $row['grandTotal'];
                $dataString .= "<tr><td>Date: </td><td>$salesDate</td></tr>
                                <tr><td>Invoice No: </td><td><a href='../wholesalesViewDetails.php?id=".$salesId."' target='_blank'>$salesOrderNo</td></tr>
                                <tr><td>Product Name: </td><td>$productName</td></tr>
                                <tr><td>Quantity: </td><td>$quantity</td></tr>
                                <tr><td>Unit Price: </td><td>$unitPrice</td></tr>
                                <tr><td>Discount: </td><td>$discount</td></tr>
                                <tr><td>Total Amount: </td><td>$totalAmount</td></tr>";
            }
            if($salesId == ''){
                $dataString .= "<tr><td colspan='2'>No data found</td></tr>";        
            }
		}
        $dataString .= "</table>";
        echo $dataString;
	}
	else if($_POST["action"] == 'previousPriceSingleTs'){
	    $dataString = "<table>";
	    $loginID = $_SESSION['user'];
    	$customers= $_POST['customers'];
    	$productId = $_POST['productId'];
		if($productId != ''){
		    /*$sql = "SELECT tbl_temporary_sale.id,tbl_temporary_sale.tSalesDate,tbl_temporary_sale.tsNo,tbl_products.productCode, tbl_products.productName, tbl_products.modelNo, tbl_tsalesproducts.quantity, tbl_tsalesproducts.amount,tbl_tsalesproducts.discount,tbl_tsalesproducts.grandTotal  
                    FROM tbl_temporary_sale 
                    LEFT OUTER JOIN tbl_tsalesproducts ON tbl_tsalesproducts.tbl_tSalesId = tbl_temporary_sale.id AND tbl_tsalesproducts.deleted = 'No'
                    LEFT OUTER JOIN tbl_products ON tbl_tsalesproducts.tbl_productsId = tbl_products.id AND tbl_products.deleted = 'No'
                    WHERE tbl_temporary_sale.tbl_customerId = '".$customers."' AND tbl_tsalesproducts.tbl_productsId = '".$productId."'
                    ORDER BY tbl_temporary_sale.createdDate DESC Limit 1";*/
            $sql = "SELECT tbl_sales.id,tbl_sales.salesDate,tbl_sales.salesOrderNo,tbl_products.productCode, tbl_products.productName, tbl_products.modelNo, tbl_sales_products.quantity, tbl_sales_products.salesAmount, tbl_sales_products.discount, tbl_sales_products.grandTotal  
                    FROM tbl_sales 
                    LEFT OUTER JOIN tbl_sales_products ON tbl_sales_products.tbl_salesId = tbl_sales.id AND tbl_sales_products.deleted = 'No'
                    LEFT OUTER JOIN tbl_products ON tbl_sales_products.tbl_productsId = tbl_products.id AND tbl_products.deleted = 'No'
                    WHERE tbl_sales.tbl_customerId = '".$customers."' AND tbl_sales_products.tbl_productsId = '".$productId."' AND tbl_sales.type='TS'
                    ORDER BY tbl_sales.createdDate DESC Limit 1";
            $result = $conn->query($sql);
            
            while($row = $result->fetch_assoc()){
                $salesId = $row['id'];
                $salesDate = $row['salesDate'];
                $salesOrderNo = $row['salesOrderNo'];
                $productName = $row['productName'].' - '.$row['productCode'];
                $quantity = $row['quantity'];
                $unitPrice = $row['salesAmount'];
                $discountAmount = $row['discount'];
                $totalAmount = $row['grandTotal'];
                $dataString .= "<tr><td>Date: </td><td>$salesDate</td></tr>
                                <tr><td>Invoice No FS: </td><td><a href='../fsSalesViewDetails.php?id=".$salesId."' target='_blank'>$salesOrderNo</td></tr>
                                <tr><td>Product Name: </td><td>$productName</td></tr>
                                <tr><td>Quantity: </td><td>$quantity</td></tr>
                                <tr><td>Unit Price: </td><td>$unitPrice</td></tr>
                                <tr><td>Discount: </td><td>$discount</td></tr>
                                <tr><td>Total Amount: </td><td>$totalAmount</td></tr>";
            }
            if($salesId == ''){
                $dataString .= "<tr><td colspan='2'>No data found</td></tr>";        
            }
		}
        $dataString .= "</table>";
        echo $dataString;
	}
	else if($_POST["action"] == 'productSpecification'){
	    
	    $loginID = $_SESSION['user'];
    	$customers= $_POST['customers'];
    	$productId = $_POST['productId'];
		if($productId != ''){
		    $sql = "SELECT tbl_productspecification.tbl_productsId,tbl_products.productName,tbl_brands.brandName,tbl_products.modelNo,tbl_products.minSalePrice,tbl_products.maxSalePrice,CONCAT('<ul>', GROUP_CONCAT(CONCAT('<li>',tbl_productspecification.specificationName, ': ', tbl_productspecification.specificationValue) SEPARATOR '</li>') ,'</li></ul>') AS productSpeficiations 
                    FROM `tbl_productspecification` 
                    LEFT JOIN tbl_products ON tbl_products.id=tbl_productspecification.tbl_productsId AND tbl_products.deleted='No'
                    LEFT JOIN tbl_brands ON tbl_brands.id=tbl_products.tbl_brandsId
                    WHERE tbl_products.id='$productId'";
             //echo $sql;       
            $result = $conn->query($sql);
               
            $dataString = "<table>";
            while($row = $result->fetch_assoc()){
                $specId = $row['tbl_productsId'];
                $productName = $row['productName'];
                $brandName = $row['brandName'];
                $modelNo = $row['modelNo'];
                $minSalePrice = $row['minSalePrice'];
                $maxSalePrice = $row['maxSalePrice'];
                $productSpeficiations = $row['productSpeficiations'];
                //$dataString .= "<tr><td>$specificationName</td><td>$specificationValue</td></tr>";
            }
             
            if($specId == ''){
                $dataString .= "<tr><td colspan='1'>No data found</td></tr>";        
            }
		}
		$dataString .= "<tr><th>Product Name:</th><th> Brands/Model</th><th> Price </th><td>Speficiations</td></tr>";
		$dataString .= "<tr><td> $productName</td><td> $brandName - $modelNo</td><td>Min: ৳ $minSalePrice <br>Max: ৳ $maxSalePrice</td> <td>$productSpeficiations</td></tr>";
        $dataString .= "</table>";
        
        echo $dataString;
	}
	else if($_POST["action"] == "findTotalQuantity"){
	    $productId = $_POST['product_id'];
	    $warehouseId = $_POST['warehouse_id'];
	    $sql = "SELECT SUM(currentStock) AS totalStock 
                FROM tbl_currentStock
                WHERE tbl_productsId='$productId' AND tbl_warehouseId='$warehouseId' AND deleted='No'";
        $result = $conn->query($sql);
        if($result){
            while($row = $result->fetch_assoc()){
                $totalStock = $row['totalStock'];
                if($totalStock > 0){
                    echo json_encode($totalStock);	
                }else{
                    echo json_encode(0);	
                }
            }
        }else{
            echo json_encode(0);	
        }
	}
	else if($_POST['action'] == 'showSerializTable'){
		$rows = '';
		$product_id =  $_POST['id'];
		$warehouse_id =  $_POST['warehouseId'];
		$totalQuantityForSale = 0;
        $productSerialId = $_POST['product_id'];
		$matchQuantity =  $_POST['matchQuantity'];
		$totalMatchQuantity = 0;
		if ($matchQuantity == "CheckQuantity") {
			$countLen =  count($_SESSION["wholeSaleShopping_cart"]);
			for ($i = 0; $i < $countLen; $i++) {
				$totalMatchQuantity += array_sum($_SESSION["wholeSaleShopping_cart"][$i]["serializeSaleQtyArray"]);
			}
			echo json_encode(array('displayTable' => $rows, "totalQuantityForSale" => $totalQuantityForSale, "totalMatchQuantity" => $totalMatchQuantity));
			return;
		}
		$sql_serializeProducts = "SELECT tbl_serialize_products.id,tbl_serialize_products.tbl_productsId,tbl_serialize_products.purchase_id,tbl_serialize_products.serial_no,tbl_serialize_products.quantity,tbl_serialize_products.used_quantity 
		                            FROM tbl_serialize_products
		                            WHERE tbl_serialize_products.tbl_productsId = '$product_id' AND tbl_serialize_products.warehouse_id='$warehouse_id' AND tbl_serialize_products.deleted='No' AND tbl_serialize_products.status='Active' AND tbl_serialize_products.is_sold='ON'
		                            ORDER BY tbl_serialize_products.id";
        $result_serializeProducts = $conn->query($sql_serializeProducts);
        if($result_serializeProducts->num_rows > 0 ){
            $key = 0;
            while ($row_serializeProducts = $result_serializeProducts->fetch_array()) {
				$remainingQty = ($row_serializeProducts['quantity'] - $row_serializeProducts['used_quantity']);
				$tblSerializeProductsId = $row_serializeProducts['id'];
				//$saleQuantity = findStoreQuantity($product_id, $warehouse_id, $tblSerializeProductsId); // Function Calling
				$saleQuantity = '';
				/* comment by hamid */
				 foreach ($_SESSION["wholeSaleShopping_cart"] as $keys => $values) {
            		if ($_SESSION["wholeSaleShopping_cart"][$keys]['product_id'] == $product_id && $_SESSION["wholeSaleShopping_cart"][$keys]['warehouse_id'] == $warehouse_id) {
            			$serializeIdArray = $_SESSION["wholeSaleShopping_cart"][$keys]['serializeIdArray'];
            			$item = array_search($tblSerializeProductsId, $serializeIdArray);
            			if ($item > 0) {
            				$saleQuantity = $_SESSION["wholeSaleShopping_cart"][$keys]['serializeSaleQtyArray'][$item];
            			} else {
            				$saleQuantity = 0;
            			}
            		}
            	}
				$totalQuantityForSale += intval($saleQuantity);
				$rows .= '<tr><td>' . ($key + 1) . '</td>' .
					'<td>' . $row_serializeProducts['serial_no'] . '</td><td id="serializeRemainingQty_' . $tblSerializeProductsId . '">' . $remainingQty . '</td><td><input class="form-control only-number input-sm stockQuantity' . $key .
					'" id="stockQuantity_' . $tblSerializeProductsId . '" type="text" name="stockQuantity" placeholder=" ... " required oninput="calculateTotalQuantity(this.value,' . $productSerialId . ',' . $warehouse_id . ',' . $tblSerializeProductsId . ')" value="' . $saleQuantity . '"></td></tr>';
			
                $key++;
            }
		} else {
			$rows .= '<tr class="bg-warning"><td colspan="4">Stock Not Avaialable For Sale...</td></tr>';
		}
		//echo json_encode($saleQuantity);
		echo json_encode(array('displayTable' => $rows, "totalQuantityForSale" => $totalQuantityForSale, "totalMatchQuantity" => $totalMatchQuantity));
	}
}
else if (isset($_POST['fetchCart'])){
    $total_price = 0;
    $total_item = 0;
    $totalProductDiscount = 0;
    $discountDisable = '';
    $amountDisable = '';
    if(strtolower($_SESSION['userType']) == 'super admin' || strtolower($_SESSION['userType']) == 'admin support' || strtolower($_SESSION['userType']) == 'admin support plus'){
        $discountDisable = '';
        $amountDisable = '';
    }else if(strtolower($_SESSION['userType']) == 'admin coordinator'){
        $discountDisable = 'Disabled';
        $amountDisable = '';
    }else{
        $discountDisable = 'Disabled';
        $amountDisable = '';
    }
    $maxRowId = 1;
    $output = '
    <div class="table-responsive" id="order_table">
    	<table class="table table-bordered table-striped">
    		<tr style="background-color: #e1e1e1;font-size: 12px;">  
                <th style="width:25%;text-align: center;">Product Name</th>
                <th style="width:20%;text-align: center;">Warehouse</th>
                <th style="width:10%;text-align: center;">Available</th>
                <th style="width:8%;text-align: center;">Quantity</th>
                <th style="width:20%;text-align: center;">Price</th>  
                <th style="width:8%;text-align: center;">Discount</th>  
                <th style="width:25%;text-align: center;">Total</th>  
                <th style="width:3%;text-align: center;">Action</th>  
            </tr>
    ';
    if(!empty($_SESSION["wholeSaleShopping_cart"]))
    {
    	foreach($_SESSION["wholeSaleShopping_cart"] as $keys => $values)
    	{
    	    $productDiscount = 0;
    	    $maxRowId = $values["id"];
			//$max_price = $values["max_price"];
			//$min_price = $values["min_price"];
			//$discount_percent = (($max_price-$min_price)/$max_price)*100;
    		$output .= '<tr  style="background-color: #e1e1e1;font-size: 12px;">
    			<td>'.$values["product_name"].'<input type="hidden" id="productId'.$values["id"].'" name="productId" value="'.$values["product_id"].'"/><input type="hidden" id="id'.$values["product_id"].'" name="id" value="'.$values["id"].'"/></td>
    			<td>'.$values["warehouse_name"].'<input type="hidden" id="warehouseId'.$values["id"].'" name="warehouse_id" value="'.$values["warehouse_id"].'"/><input type="hidden" id="warehouseName'.$values["id"].'" name="warehouse_name" value="'.$values["warehouse_name"].'"/></td>
    			<td><input type="text" id="availableQuantity'.$values["id"].'" name="availableQuantity" value="'.$values["product_limit"].'" style="width: 100%;text-align: center;" Readonly/></td>
    			<td><input type="text" id="productQuantity'.$values["id"].'" name="productQuantity" value="'.$values["product_quantity"].'" onkeyup="calculateTotal('.$values["id"].')" onblur="updateSession('.$values["id"].',true)" style="width: 100%;text-align: center;"/>';
    		if ($values["product_type"] == "serialize") {
				$output .= '<a href="#" onclick="showSerializTable(' . $values["product_id"] . ', ' . $values["warehouse_id"] . ', ' . $values["product_quantity"] . ', ' . $values["id"] . ')"> <i class="fa fa-edit"> </i> </a>';
				$output .= '<input type="hidden" name="checkSerialize" value="' . $values["id"] . ',' . $values["warehouse_id"] . '" />';
			} else {
				$output .= '';
			}	
			$output .='</td>
    			<td align="right">
    			    <input type="text" id="productPrice'.$values["id"].'" name="productPrice" value="'.$values["product_price"].'" onkeyup="calculateTotal('.$values["id"].')"  onblur="updateSession('.$values["id"].',true)" style="width: 100%;text-align: center;" '.$amountDisable.'/>
    			    <input type="hidden" id="productMaxPrice'.$values["id"].'" name="productMaxPrice" value="'.$values["max_price"].'"/>
    			    <input type="hidden" id="productMinPrice'.$values["id"].'" name="productMinPrice" value="'.$values["min_price"].'"/>
			    </td>
    			<td align="right">
    			    <input type="text" id="productDiscount'.$values["id"].'" name="productDiscount" value="'.$values["product_discount"].'" onkeyup="calculateTotal('.$values["id"].')"  onblur="updateSession('.$values["id"].',true)" style="width: 100%;text-align: center;" '.$discountDisable.'/>
			    </td>';
	        $productTotal = $values["product_quantity"] * $values["product_price"];
    	    if($values["product_discount"] != ""){
    		    $lastValue = substr($values["product_discount"], -1);
    		    if($lastValue == "%"){
    		        $productDiscount = $productTotal * (substr($values["product_discount"], 0, -1)/100);
    		    }else{
    		        $productDiscount = $values["product_discount"];
    		    }
    		    $productTotal = $productTotal - $productDiscount;
    	    }
    		$output .= '
    		    <td align="right"><span id="productTotal'.$values['id'].'"> '.sprintf("%.2f", $productTotal).'</span></td>
    			<td>
    			    <div class="btn-group">
                    	<button type="button" class="btn btn-deafult dropdown-toggle" data-toggle="dropdown"style="border: 1px solid gray;">
                    	<i class="glyphicon glyphicon-option-horizontal" style="color: #000cbd;"></i></button>
                    	<ul class="dropdown-menu dropdown-menu-right" style="border: 1px solid gray;min-width: 100%;" role="menu">
                    		<li style="margin-left: 0px;"><a class="btn btn-secondary btn-xs delete" id="'. $values["id"].'" href="#"><span class="glyphicon glyphicon-trash" style="color: red;"></span></a></li>
    			            <li style="margin-left: 0px;"><a class="btn btn-xs previousPriceSingle" id="-'. $values["product_id"].'" href="#"><span class="glyphicon glyphicon-th" style="color: #000cbd;"></span></a></li>
                    	    <li style="margin-left: 0px;"><a class="btn btn-xs productSpecification" id="-'. $values["product_id"].'" href="#"><span class="glyphicon glyphicon-check" style="color: #000cbd;"></span></a></li>
                    	</ul>
                    </div>
			    </td>
    		</tr>';
    		$total_price = $total_price + $productTotal;
    		$total_item = $total_item + 1;
            $totalProductDiscount = $totalProductDiscount + $productDiscount;
    	}
    	$output .= '
    	<tr>  
            <td colspan="6" align="right">Total
            <br>Product Discount</td>  
            <td align="right"><span class="totalAmount">'.sprintf("%.2f", $total_price).'</span>
            <br><span class="totalProductDiscount" style="width: 100%;text-align: center;">'.$totalProductDiscount.'</span></td>  
            <td></td>  
        </tr>
    	<tr>  
            <td colspan="6" align="right">Sales Discount
            <br>Total Discount</td>
            <td align="right"><input type="text" id="salesDiscount" style="width:100%;text-align: right;" onkeyup="calculateTotalDiscount()" value="0"/>
            <br><span class="totalDiscount" style="width: 100%;text-align: center;">'.$totalProductDiscount.'</span></td>  
            <td></td>  
        </tr>
        <tr>
            <td colspan="6" align="right">Carring Cost</td>
            <td align="right"><input type="text" id="carringCost" name="carringCost" style="width:100%;text-align: right;"  onkeyup="calculateTotalDiscount()" value="0" /></td>  
            <td></td>
        </tr>
        <tr>
            <td colspan="6" align="right">VAT</td>
            <td align="right"><input type="text" id="vat" onkeyup="calculateTotalDiscount()" name="vat" style="width:100%;text-align: right;" autocomplete="off" value="0"/></td>  
            <td></td>
        </tr>
        <tr>
            <td colspan="6" align="right">AIT</td>
            <td align="right"><input type="text" id="ait" name="ait" onkeyup="calculateTotalDiscount()" style="width:100%;text-align: right;" autocomplete="off" value="0" /></td>  
            <td></td>
        </tr>
        <tr>
            <td colspan="6" align="right">Grand Total</td>
            <td align="right"><span class="grandTotal" style="width: 100%;">'.sprintf("%.2f", $total_price).'</span></td>  
            <td></td>
        </tr>
        <tr>
            <td colspan="6" align="right">Payment Method</td>
            <td align="right"><select id="paymentMethod" name="paymentMethod">
                <option value="CASH" selected>Cash</option>
            </select></td>  
            <td></td>
        </tr>
        <tr>
            <td colspan="6" align="right">Cash Amount</td>
            <td align="right"><input type="text" id="paid" name="paid" style="width:100%;text-align: right;" autocomplete="off" value="0"/>  
            <input type="hidden" id="maxRowId" name="maxRowId" style="width:100%;text-align: right;" autocomplete="off" value="'.$maxRowId.'" Readonly/></td>  
            <td></td>
        </tr>
        
    	';
    }
    else
    {
    	$output .= '
        <tr>
        	<td colspan="5" align="center">
        		Your Cart is Empty!
        	</td>
        </tr>
        ';
    }
    $output .= '</table></div>';
    $data = array(
    	'cart_details'		=>	$output,
    	'total_price'		=>	'&#2547;' . sprintf("%.2f", $total_price),
    	'total_item'		=>	$total_item
    );
    echo json_encode($data);
}
else{
    if(isset($_GET['sortData'])){
        $dates = explode(",",$_GET['sortData']);    
        $sql = "SELECT tbl_sales.id, tbl_sales.salesOrderNo, tbl_sales.salesDate, tbl_sales.type, tbl_party.partyName,tbl_party.locationArea,tbl_party.tblCity, tbl_sales.grandTotal, tbl_users.fname, tbl_users.username, tbl_sales.totalDiscount, tbl_sales.paidAmount, tbl_sales.totalAmount, CONCAT('<ul>', GROUP_CONCAT(CONCAT('<li>', tbl_products.productName,' - ',tbl_products.productCode, ' (',tbl_sales_products.quantity,')') SEPARATOR '</li>'), '</li></ul>') AS salesProducts
                FROM tbl_sales 
                LEFT OUTER JOIN tbl_party ON tbl_sales.tbl_customerId = tbl_party.id AND tbl_party.deleted='No'
                LEFT OUTER JOIN tbl_users ON tbl_sales.tbl_userId = tbl_users.id AND tbl_users.deleted='No'
                LEFT OUTER JOIN tbl_sales_products ON tbl_sales_products.tbl_salesId = tbl_sales.id AND tbl_sales_products.deleted = 'No'
                LEFT OUTER JOIN tbl_products ON tbl_sales_products.tbl_productsId = tbl_products.id
                WHERE tbl_sales.type = 'PartySale' AND tbl_sales.deleted='No' AND tbl_sales.salesDate BETWEEN '".$dates[0]."' AND '".$dates[1]."'  
                GROUP BY tbl_sales.id
                ORDER BY id DESC";
    }
    else if(isset($_GET['customerId'])){
        $customerId = $_GET['customerId'];
        $sql = "SELECT tbl_sales.id, tbl_sales.salesOrderNo, tbl_sales.salesDate, tbl_sales.type, tbl_party.partyName,tbl_party.locationArea,tbl_party.tblCity, tbl_sales.grandTotal, tbl_users.fname, tbl_users.username, tbl_sales.totalDiscount, tbl_sales.paidAmount, tbl_sales.totalAmount, CONCAT('<ul>', GROUP_CONCAT(CONCAT('<li>', tbl_products.productName,' - ',tbl_products.productCode, ' (',tbl_sales_products.quantity,')') SEPARATOR '</li>'), '</li></ul>') AS salesProducts
                FROM tbl_sales 
                LEFT OUTER JOIN tbl_party ON tbl_sales.tbl_customerId = tbl_party.id AND tbl_party.deleted='No'
                LEFT OUTER JOIN tbl_users ON tbl_sales.tbl_userId = tbl_users.id AND tbl_users.deleted='No'
                LEFT OUTER JOIN tbl_sales_products ON tbl_sales_products.tbl_salesId = tbl_sales.id AND tbl_sales_products.deleted = 'No'
                LEFT OUTER JOIN tbl_products ON tbl_sales_products.tbl_productsId = tbl_products.id
                WHERE tbl_sales.type = 'PartySale' AND tbl_sales.deleted='No' AND tbl_sales.tbl_customerId = '$customerId'
                GROUP BY tbl_sales.id
                ORDER BY id DESC";
    }
        
    $result = $conn->query($sql);
    $i=1;
    $output = array('data' => array());
    while ($row = $result->fetch_array()) {
        $salesId = $row['id'];
        
		//<li><a href="#" onclick="createChallan('.$salesId.',\'PartySale\')"><i class="fa fa-external-link" aria-hidden="true"></i>Create Challan</a></li>';
							
        $button = '	<div class="btn-group">
						<button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
						<i class="fa fa-gear tiny-icon"></i> <span class="caret"></span></button>
						<ul class="dropdown-menu dropdown-menu-right" style="border: 1px solid gray;" role="menu">
							<li><a href="#" onclick="salesReport('.$salesId.',\'PartySale\')"><i class="fa fa-print tiny-icon"></i>View Details</a></li>
							<li><a href="sale-return.php?salesId='.$salesId.'&salesType='.$row['type'].'"><i class="fa fa-mail-reply"></i>Sales Return</a></li>';
		if(strtolower($_SESSION['userType']) == 'super admin' || strtolower($_SESSION['userType']) == 'admin support plus'){
		    $button .=  '<li><a href="#" onclick="deleteSales(' . $salesId . ')"><i class="fa fa-edit tiny-icon"></i>Delete</a></li>';
		}
		$button .= '</ul></div>';
        $output['data'][] = array(
            $i++,
            $row['salesOrderNo'],
            $row['partyName'].'<br>'.$row['locationArea'].' '.$row['tblCity'].'<br>'.$row['salesDate'],
            $row['fname'].' - '.$row['username'],
            $row['salesProducts'],
            'Total: '.$row['totalAmount'].'<br>Discount: '.$row['totalDiscount'].'<br>Grand: '.$row['grandTotal'].'<br>Paid: '.$row['paidAmount'],
            $button
        );
    } // /while 
    echo json_encode($output);    
}
?>