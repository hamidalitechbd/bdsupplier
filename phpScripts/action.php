<?php
$conPrefix = '../';
include $conPrefix . 'includes/session.php';
date_default_timezone_set('Asia/Dhaka');
//$toDay = (new DateTime($test))->format("Y-m-d H:i:s");
$toDay = date('Y-m-d H:i:s');
//action.php
//session_start();
if(isset($_POST["action"]))
{
	if($_POST["action"] == "add")
	{
	    $totalStock = 0;
	    $stockError = 0;
	    $currentStockId = '';
		$productType = '';
	    $productId = $_POST["product_id"];
	    $warehouseId = $_POST["warehouse_id"];
		$product_discount = 0;
		if (isset($_POST["product_discount"])) {
			$product_discount = $_POST["product_discount"];
		}
	    /* $sql = "SELECT currentStock AS totalStock, id 
                FROM tbl_currentStock
                WHERE tbl_productsId='$productId' AND tbl_wareHouseId='$warehouseId' AND deleted='No'"; */
				//updated For serialize products
				$sql = "SELECT tbl_currentStock.currentStock AS totalStock, tbl_currentStock.id, tbl_products.type 
                FROM tbl_currentStock
                INNER JOIN tbl_products ON tbl_currentStock.tbl_productsId=tbl_products.id
                WHERE tbl_productsId='$productId' AND tbl_currentStock.tbl_wareHouseId='$warehouseId' AND tbl_currentStock.deleted='No'";
        $result = $conn->query($sql);
        if($result){
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
        if($totalStock > 0){
			$serializeIdArray = array();
			$serializeSaleQtyArray = array();
    		if(isset($_SESSION["shopping_cart"]))
    		{
    			$is_available = 0;
    			foreach($_SESSION["shopping_cart"] as $keys => $values)
    			{
    				if($_SESSION["shopping_cart"][$keys]['product_id'] == $_POST["product_id"] && $_SESSION["shopping_cart"][$keys]['warehouse_id'] == $_POST["warehouse_id"])
    				{
    					$is_available++;
    					if($totalStock >= $_SESSION["shopping_cart"][$keys]['product_quantity'] + $_POST["product_quantity"]){
        					$_SESSION["shopping_cart"][$keys]['product_quantity'] = $_SESSION["shopping_cart"][$keys]['product_quantity'] + $_POST["product_quantity"];
        					//$_SESSION["shopping_cart"][$keys]['product_discount'] = $_SESSION["shopping_cart"][$keys]['product_discount'] + $_POST["product_discount"];
    					}else{
    					    $stockError++;
    					}
    				}
    			}
    			if($is_available == 0)
    			{
    				$item_array = array(
    				    'id'                       =>     $currentStockId,  
    					'product_id'               =>     $_POST["product_id"],  
    					'product_name'             =>     $_POST["product_name"],  
    					'product_price'            =>     $_POST["product_price"],  
    					'min_price'            	   =>     $_POST["min_price"],
    					'max_price'                =>     $_POST["max_price"],
    					'product_quantity'         =>     $_POST["product_quantity"],
    					'warehouse_id'             =>     $_POST["warehouse_id"],
        				'warehouse_name'           =>     $_POST["warehouse_name"],
    					'product_limit'            =>     $totalStock,
    					//'product_discount'         =>     $_POST["product_discount"],
						'product_discount'         =>     $product_discount,
						'product_type'        	   =>     $productType,
    					'serializeIdArray'  	   =>  	  [$serializeIdArray],
						'serializeSaleQtyArray'    =>     [$serializeSaleQtyArray]
    				);
    				$_SESSION["shopping_cart"][] = $item_array;
    			}
    		}
    		else
    		{
    			$item_array = array(
    			    'id'                       =>     $currentStockId,  
    				'product_id'               =>     $_POST["product_id"],  
    				'product_name'             =>     $_POST["product_name"],  
    				'product_price'            =>     $_POST["product_price"],  
    				'min_price'            	   =>     $_POST["min_price"],
    				'max_price'                =>     $_POST["max_price"],
    				'product_quantity'         =>     $_POST["product_quantity"],
    				'warehouse_id'             =>     $_POST["warehouse_id"],
        			'warehouse_name'           =>     $_POST["warehouse_name"],
    				'product_limit'            =>     $totalStock,
    				//'product_discount'         =>     $_POST["product_discount"],
    				'product_discount'         =>     $product_discount,
					'product_type'        	   =>     $productType,
    				'serializeIdArray'  	   =>     [$serializeIdArray],
					'serializeSaleQtyArray'    =>     [$serializeSaleQtyArray]
    			);
    			$_SESSION["shopping_cart"][] = $item_array;
    		}
	    }else{
            $stockError++;
        }
        //echo $stockError;
		$data = array('productType'=>$productType, 'productId'=>$_POST["product_id"], 'warehouseId'=>$_POST["warehouse_id"], 'count'=>$stockError, 'currentStockId'=>$currentStockId);
		echo json_encode($data);
	}

	// Serialize Product
	else if($_POST['action'] == 'showSerializTable'){
		$rows = '';
		$product_id =  $_POST['id'];
		$warehouse_id =  $_POST['warehouseId'];
		$totalQuantityForSale = 0;
        $productSerialId = $_POST['product_id'];
		$matchQuantity =  $_POST['matchQuantity'];
		$totalMatchQuantity = 0;
		if ($matchQuantity == "CheckQuantity") {
			$countLen =  count($_SESSION["shopping_cart"]);
			for ($i = 0; $i < $countLen; $i++) {
				$totalMatchQuantity += array_sum($_SESSION["shopping_cart"][$i]["serializeSaleQtyArray"]);
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
				 foreach ($_SESSION["shopping_cart"] as $keys => $values) {
            		if ($_SESSION["shopping_cart"][$keys]['product_id'] == $product_id && $_SESSION["shopping_cart"][$keys]['warehouse_id'] == $warehouse_id) {
            			$serializeIdArray = $_SESSION["shopping_cart"][$keys]['serializeIdArray'];
            			$item = array_search($tblSerializeProductsId, $serializeIdArray);
            			if ($item > 0) {
            				$saleQuantity = $_SESSION["shopping_cart"][$keys]['serializeSaleQtyArray'][$item];
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
		echo json_encode(array('displayTable' => $rows, "totalQuantityForSale" => $totalQuantityForSale, "totalMatchQuantity" => $totalMatchQuantity));
	}
	// End Serialize Product

	else if($_POST["action"] == 'remove'){
	    $changed = 0;
	    $productId = 0;
		foreach($_SESSION["shopping_cart"] as $keys => $values)
		{
			if($values["id"] == $_POST["id"] && $changed == 0)
			{
			    $productId = $values["product_id"];
				unset($_SESSION["shopping_cart"][$keys]);
				$changed = 1;
				//break;
			}else if($values["product_id"] == $productId){
			    $_SESSION["shopping_cart"][$keys]["status"] = $_SESSION["shopping_cart"][$keys]["status"]-1;
			}
		}
	}
	else if($_POST["action"] == "adjust"){
		$productType = '';
		$productId = '';
		$warehouseId = '';
		$currentStockId = '';
		if(isset($_SESSION["shopping_cart"])){
			foreach($_SESSION["shopping_cart"] as $keys => $values){
				if($_SESSION["shopping_cart"][$keys]['id'] == $_POST["id"]){
					$_SESSION["shopping_cart"][$keys]['product_quantity'] =  $_POST["product_quantity"];
					$_SESSION["shopping_cart"][$keys]['product_price'] =  $_POST["product_price"];
					$_SESSION["shopping_cart"][$keys]['product_limit'] =  $_POST["product_limit"];
					$_SESSION["shopping_cart"][$keys]['product_discount'] =  $_POST["product_discount"];
					// Serialize Product
					if ($_SESSION["shopping_cart"][$keys]['product_type'] == "serialize") {
						if ($_POST['product_type']) {
							$serializeId = $_POST['serializeProductsId'];
							$serializeSaleQty = $_POST['serializeSaleQuantity'];
							$serializeIdExist = TRUE;
							foreach ($_SESSION["shopping_cart"][$keys]['serializeIdArray'] as $key => $value) {
								if ($value == $serializeId) {
									//session()->put("sale_cart_array." . $keys . ".serializeSaleQtyArray." . $key, $serializeSaleQty);
									$_SESSION["shopping_cart"][$keys]['serializeSaleQtyArray'][$key] =  $serializeSaleQty;
									$ddd = $_SESSION["shopping_cart"][$keys]['serializeSaleQtyArray'];
									$serializeIdExist = FALSE;
								}
							}
							if ($serializeIdExist) {
								/*Session::push("sale_cart_array." . $keys . ".serializeIdArray", $serializeId);
								Session::push("sale_cart_array." . $key s . ".serializeSaleQtyArray", $serializeSaleQty);*/
								array_push($_SESSION["shopping_cart"][$keys]['serializeIdArray'],$serializeId);
								
								array_push($_SESSION["shopping_cart"][$keys]['serializeSaleQtyArray'],$serializeSaleQty);
								$ddd = $_SESSION["shopping_cart"][$keys]['serializeSaleQtyArray'];
							}
						}
					}
					$productType = $_SESSION["shopping_cart"][$keys]['product_type'];
					$productId = $_SESSION["shopping_cart"][$keys]['product_id'];
					$warehouseId = $_SESSION["shopping_cart"][$keys]['warehouse_id'];
					$currentStockId = $_SESSION["shopping_cart"][$keys]['id'];
					// End Serialize Product
					break;
				}
			}
		}
		$data = array('productType'=>$productType, 'productId'=>$productId, 'warehouseId'=>$warehouseId, 'currentStockId'=>$currentStockId, 'ddd'=>$ddd);
        echo json_encode($data);
	}
	else if($_POST["action"] == 'empty')
	{
		unset($_SESSION["shopping_cart"]);
	}
	else if($_POST["action"] == 'check_out_cart'){
	    $error=0;
	    $loginID = $_SESSION['user'];
	    $salesDate = $_POST['salesDate'];
	    $customerId= $_POST['customerId'];
    	$customers= $_POST['customers'];
    	$contactNo= $_POST['contactNo'];
    	$customerEmail= $_POST['customerEmail'];
    	$customerAddress= $_POST['customerAddress'];
    	$remarks= $_POST['remarks'];
    	$salesMan=$_POST['salesMan'];
    	$totalAmount=$_POST['totalAmount'];
	    $totalProductDiscount=$_POST['totalProductDiscount'];
    	$salesDiscount=$_POST['salesDiscount'];
    	$totalDiscount=$_POST['totalDiscount'];
	    $grandTotal=$_POST['grandTotal'];
	    $grandTotal = floatval($grandTotal);
	    $grandTotal = round($grandTotal);
	    $paidAmount=$_POST['paidAmount'];
	    $paymentMethod=$_POST['paymentMethod'];
	    $vat=$_POST['vat'];
    	$ait=$_POST['ait'];
    	$carringCost=$_POST['carringCost'];
    	$requisitionNo=$_POST['requisitionNo'];
    	$wareHouse=$_POST['wareHouse'];
    	$productId = $_POST['productId'];
        $productQuantity = $_POST['productQuantity'];
        $productPrice = $_POST['productPrice'];
        $productDiscount = $_POST['productDiscount'];
        $productTotal = $_POST['productTotal'];
        
        $warehouse_ids = $_POST['warehouseId'];
        //echo print_r($warehouse_ids);
        $type=$_POST['type'];
        $productIdArray = explode("@!@,",$productId);
        $productQuantityArray = explode("@!@,",$productQuantity);
        $productPriceArray = explode("@!@,",$productPrice);
        $productDiscountArray = explode("@!@,",$productDiscount);
        $productTotalArray = explode("@!@,",$productTotal);
        $warehouse_idArray = explode("@!@,",$warehouse_ids);
    	$salesOrderNo='';
    	try{
    	    $checkOverQuantity = 0;
    	    for($i = 0; $i < count($productIdArray); $i++) {
    	        $productIdEntry = $productIdArray[$i];
				$productQuantityEntry =$productQuantityArray[$i]; 
				$warehouse_idEntry = $warehouse_idArray[$i];
				if($i == count($productIdArray)-1){
					$productIdEntry = substr($productIdEntry, 0, strlen($productIdEntry)-3);
					$productQuantityEntry = substr($productQuantityEntry, 0,strlen($productQuantityEntry)-3);
					$warehouse_idEntry = substr($warehouse_idEntry, 0, strlen($warehouse_idEntry)-3);
				}
				if($productIdEntry != ''){
				    /*$sql = "SELECT SUM(currentStock) AS totalStock 
                            FROM tbl_currentStock
                            WHERE tbl_productsId='$productIdEntry'";*/
                    $sql = "SELECT SUM(currentStock) AS totalStock 
                    FROM tbl_currentStock
                    WHERE tbl_productsId='$productIdEntry' AND tbl_warehouseId='$warehouse_idEntry' AND deleted='No'";
                    $result = $conn->query($sql);
                    while($row = $result->fetch_assoc()){
                        $totalStock = $row['totalStock'];
                        if($totalStock < $productQuantityEntry){
                            $checkOverQuantity++;
                        }
                    }
				}
    	    }
    	    if ($checkOverQuantity == 0){
        		$conn->begin_transaction();
        		$sql = "SELECT LPAD(max(salesOrderNo)+1, 6, 0) as salesCode from tbl_sales where type='WalkinSale'";
        		$query = $conn->query($sql);
        		while ($prow = $query->fetch_assoc()) {
        			$salesOrderNo = $prow['salesCode'];
        		}
        		if($salesOrderNo == ""){
        		    $salesOrderNo = "000001";
        		}
        		if($customerId == '' || $customerId == '0'){
        		    $sql = "INSERT INTO tbl_walkin_customer(customerName, customerAddress, phoneNo, contactEmail, createdBy, createdDate) 
        		            VALUES ('$customers','$customerAddress','$contactNo','$customerEmail','$loginID','$toDay')";    
    	            if($conn->query($sql)){
            	        $customerId = $conn->insert_id;
    	            }
        		}
            	$sql = "INSERT INTO tbl_sales (salesOrderNo, salesDate, tbl_customerId, tbl_userId, totalAmount, productDiscount, salesDiscount, totalDiscount, grandTotal, vat, ait, createdBy, type, paymentType, remarks,tbl_wareHouseId, carringCost, requisitionNo,  createdDate, paidAmount) 
            	        VALUES ('$salesOrderNo','$salesDate','$customerId','$salesMan','$totalAmount','$totalProductDiscount','$salesDiscount','$totalDiscount','$grandTotal','$vat','$ait','$loginID','$type', '$paymentMethod','$remarks', '$wareHouse','$carringCost', '$requisitionNo', '$toDay', $paidAmount)";
            	if($conn->query($sql)){
            	    $salesId = $conn->insert_id;
            	    for($i = 0; $i < count($productIdArray); $i++) {
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
    						$sql = "INSERT INTO tbl_sales_products (tbl_salesId, tbl_productsId, quantity, units, createdBy, salesAmount, totalAmount, discount, grandTotal, remarks, tbl_wareHouseId, createdDate) 
    						        VALUES ('$salesId','$productIdEntry','$productQuantityEntry','','$loginID','$productPriceEntry','$total','$discountAmount','$productTotalEntry','$productDiscountEntry','$warehouse_idEntry', '$toDay')";
    						if($conn->query($sql)){
    						    
        						$sql = "UPDATE tbl_currentStock 
        								    set salesStock=salesStock+$productQuantityEntry, currentStock=currentStock-$productQuantityEntry, lastUpdatedDate='$toDay', lastUpdatedBy='$loginID'
        								    where tbl_productsId = '$productIdEntry' AND tbl_wareHouseId='$warehouse_idEntry' AND deleted='No'";
        					    $query1 = $conn->query($sql);
        						if($conn->affected_rows == 0){
        							$sql = "insert into tbl_currentStock 
        							            (salesStock, currentStock, tbl_productsId, tbl_wareHouseId, entryBy,entryDate) values 
        							            ('$productQuantityEntry', '-$productQuantityEntry','$productIdEntry','$warehouse_idEntry','$loginID','$toDay')";
        							$query2 = $conn->query($sql);
        						}
        						if($query1 || $query2){
        						    $sql = "UPDATE tbl_products 
                                            SET saleTime=saleTime+1
                                            WHERE id='$productIdEntry' AND deleted='No' AND status='Active'";
                                    $conn->query($sql);
        						    $sql = "SELECT * FROM 
                                            (SELECT tbl_purchaseProducts.id, tbl_purchaseProducts.quantity - tbl_purchaseProducts.returnQuantity-tbl_purchaseProducts.saleQuantity as quantity, tbl_purchaseProducts.createdDate, tbl_purchase.purchaseDate, 'tbl_purchaseProducts' as tblName
                                            FROM tbl_purchaseProducts
                                            INNER JOIN tbl_purchase ON tbl_purchase.id = tbl_purchaseProducts.tbl_purchaseId
                                            WHERE tbl_purchaseProducts.deleted = 'No' AND tbl_purchaseProducts.deleted='No' AND tbl_purchaseProducts.avcoStatus='Yes' AND tbl_purchaseProducts.tbl_productsId='$productIdEntry' AND tbl_purchaseProducts.tbl_wareHouseId='$wareHouse'
                                            UNION
                                            SELECT tbl_purchaseForeignProducts.id, tbl_purchaseForeignProducts.quantity - tbl_purchaseForeignProducts.returnQuantity - tbl_purchaseForeignProducts.saleQuantity as quantity, tbl_purchaseForeignProducts.createdDate, tbl_purchaseForeign.purchaseDate, 'tbl_purchaseForeignProducts' as tblName
                                            FROM tbl_purchaseForeignProducts
                                            INNER JOIN tbl_purchaseForeign ON tbl_purchaseForeign.id = tbl_purchaseForeignProducts.tbl_purchaseForeignId
                                            WHERE tbl_purchaseForeignProducts.deleted = 'No' AND tbl_purchaseForeignProducts.deleted='No' AND tbl_purchaseForeignProducts.avcoStatus='Yes' AND tbl_purchaseForeignProducts.tbl_productsId='$productIdEntry' AND tbl_purchaseForeignProducts.tbl_wareHouseId='$wareHouse') AS dbt
                                            ORDER BY purchaseDate ASC, createdDate ASC";
                                    $result = $conn->query($sql);
                                    $quantity = 0;
                                    $id = 0;
                                    $tblName='';
                                    while($row = $result->fetch_assoc()){
                                        $tblName = $row['tblName'];
                                        $id = $row['id'];
                                        $quantity = $row['quantity'];
                                        if($quantity > $productQuantityEntry){
                                            $sql = "UPDATE $tblName 
                                                        SET saleQuantity=saleQuantity+$productQuantityEntry 
                                                    WHERE id='$id'";
                                            $conn->query($sql);
                                            $productQuantityEntry -= $quantity;
                                            break;
                                        }else if ($quantity == $productQuantityEntry){
                                            $sql = "UPDATE $tblName 
                                                        SET saleQuantity=saleQuantity+$productQuantityEntry, 
                                                        avcoStatus='No' 
                                                    WHERE id='$id'";
                                            $conn->query($sql);
                                            $productQuantityEntry -= $quantity;
                                            break;
                                        }else{
                                            $productQuantityEntry -= $quantity;
                                            $sql = "UPDATE $tblName 
                                                        SET saleQuantity=saleQuantity+$quantity, 
                                                        avcoStatus='No' 
                                                    WHERE id='$id'";
                                            $conn->query($sql);
                                        }
                                    }
                                    
                                    if($productQuantityEntry > 0){
                                        $sql = "UPDATE $tblName 
                                                    SET saleQuantity=saleQuantity+$productQuantityEntry, 
                                                    avcoStatus='No' 
                                                WHERE id='$id'";
                                        $conn->query($sql);
                                    }
        						}
    						}else{
    						    $error++;
                                echo json_encode($conn->error.$sql);	    
                                $conn->rollBack();		    
    						}
    						
    						
    					}
    				}
    				
    				if($error == 0){
    				    $customerType = 'WalkinCustomer';
    				    $sql = "SELECT LPAD(IFNULL(max(voucherNo),0)+1, 6, 0) as voucherCode, LPAD(IFNULL(max(voucherNo),0)+2, 6, 0) as voucherReceiveCode FROM tbl_paymentVoucher WHERE tbl_partyId='$customerId' AND customerType = '$customerType'";
                		$query = $conn->query($sql);
                		while ($prow = $query->fetch_assoc()) {
                			$voucherNo = $prow['voucherCode'];
                			$voucherReceiveNo = $prow['voucherReceiveCode'];
                		}
                		if($voucherNo == ""){
                		    $voucherNo = "000001";
                		    $voucherReceiveNo = "000002";
                		}
    				    //if ($grandTotal > 0){
        				    $sql = "INSERT INTO tbl_paymentVoucher (tbl_partyId, tbl_sales_id, amount, entryBy, paymentMethod, paymentDate, status, remarks, type, voucherType, voucherNo,customerType,entryDate) 
        							VALUES ('$customerId', '$salesId', '$grandTotal', '$loginID', '$paymentMethod', '$salesDate', 'Active', 'Payable for Walkin Sales Code: $salesOrderNo', 'partyPayable', 'WalkinSale', '$voucherNo', '$customerType','$toDay')";
        					$conn->query($sql);
        				//}
    				    if ($paidAmount > 0){
        					$sql = "INSERT INTO tbl_paymentVoucher (tbl_partyId, tbl_sales_id, amount, entryBy, paymentMethod, paymentDate, status, remarks, type, voucherType, voucherNo, customerType,entryDate) 
        							VALUES ('$customerId', '$salesId', '$paidAmount', '$loginID', '$paymentMethod', '$salesDate', 'Active', 'payment for Walkin Sales Code: $salesOrderNo', 'paymentReceived', 'WalkinSale', '$voucherReceiveNo', '$customerType','$toDay')";
        					$conn->query($sql);
        				}
        				
        				// Start Insert Sale Serialize Product
						foreach($_SESSION["shopping_cart"] as $keys => $values){
        					if ($_SESSION["shopping_cart"][$keys]["product_type"] == "serialize") {
								$product_id = $_SESSION["shopping_cart"][$keys]['product_id'];
								$warehouse_id = $_SESSION["shopping_cart"][$keys]['warehouse_id'];
        						foreach ($_SESSION["shopping_cart"][$keys]["serializeIdArray"] as $key => $serializeId) {
        							$serializeSaleQtyArray = $_SESSION["shopping_cart"][$keys]["serializeSaleQtyArray"];
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
						foreach($_SESSION["shopping_cart"] as $keys => $values){
        					if ($_SESSION["shopping_cart"][$keys]["product_type"] == "serialize") {
        						$quantity = 0;
        						foreach ($_SESSION["shopping_cart"][$keys]["serializeIdArray"] as $key => $serializeId) {
        							$serializeSaleQtyArray = $_SESSION["shopping_cart"][$keys]["serializeSaleQtyArray"];
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
        				
        				$conn->commit();
                    	unset($_SESSION["shopping_cart"]);
                    	$data = array( 
                            'msg'=>'Success', 
                            'salesId'=>$salesId);
            			echo json_encode($data);
    				}else{
    				    $conn->rollBack();
    				}
            	}else{
            	    $error++;
    		        echo json_encode($conn->error);	    
    	            $conn->rollBack();		    
            	}
    	    }else{
    	        $error++;
		        echo json_encode("Product quantity must be lower then available quantity");	    
	            $conn->rollBack();		    
    	    }
    	}catch(Exception $e){
    		$conn->rollBack();
    		echo json_encode('RollBack');
    	}
	}
	else if($_POST["action"] == 'fetch_customer'){
	    $phoneNo = $_POST['contact_no'];
		$sql = "SELECT id, customerName, customerAddress, phoneNo, contactEmail, status
                FROM tbl_walkin_customer
                WHERE phoneNo = '$phoneNo' AND status='Active' AND deleted = 'No'";
        $query = $conn->query($sql);
        if($query){
            $row = $query->fetch_assoc();
            echo json_encode($row);
        }else{
            echo json_encode($conn->error.$sql);
        }
	}
	else if($_POST["action"] == 'previousPriceSingle'){
	    $dataString = "<table>";
	    $loginID = $_SESSION['user'];
	    $customerId= $_POST['customers'];
    	$productId = $_POST['productId'];
		if($productId != ''){
		    $sql = "SELECT tbl_sales.salesDate,tbl_sales.salesOrderNo,tbl_sales.id, tbl_products.productCode, tbl_products.productName, tbl_products.modelNo, tbl_sales_products.quantity, tbl_sales_products.salesAmount, tbl_sales_products.discount, tbl_sales_products.remarks, tbl_sales_products.grandTotal  
                    FROM tbl_sales 
                    LEFT OUTER JOIN tbl_sales_products ON tbl_sales_products.tbl_salesId = tbl_sales.id AND tbl_sales_products.deleted = 'No'
                    LEFT OUTER JOIN tbl_products ON tbl_sales_products.tbl_productsId = tbl_products.id AND tbl_products.deleted = 'No'
                    WHERE tbl_sales.tbl_customerId = '".$customerId."' AND tbl_sales_products.tbl_productsId = '".$productId."'
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
                                <tr><td>Invoice No: </td><td><a href='../salesViewDetails.php?id=".$salesId."' target='_blank'>$salesOrderNo</td></tr>
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

	else if($_POST['action'] == "deleteSales"){
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
                        WHERE tbl_sales_id='$id' AND voucherType='WalkinSale'";
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
}else{
    if($_GET['sortData'] == "0,0")
    {
        $sql = "SELECT tbl_sales.id, tbl_sales.salesOrderNo,tbl_sales.type,tbl_sales.salesDate, tbl_walkin_customer.customerName, tbl_walkin_customer.phoneNo, tbl_sales.grandTotal, tbl_users.fname, tbl_users.username, tbl_sales.totalDiscount, tbl_sales.paidAmount, tbl_sales.totalAmount, CONCAT('<ul>', GROUP_CONCAT(CONCAT('<li>', tbl_products.productName,' - ',tbl_products.productCode, ' (',tbl_sales_products.quantity,')') SEPARATOR '</li>'), '</li></ul>') AS salesProducts
                FROM tbl_sales 
                LEFT OUTER JOIN tbl_walkin_customer ON tbl_sales.tbl_customerId = tbl_walkin_customer.id AND tbl_walkin_customer.deleted='No'
                LEFT OUTER JOIN tbl_users ON tbl_sales.tbl_userId = tbl_users.id AND tbl_users.deleted='No'
                LEFT OUTER JOIN tbl_sales_products ON tbl_sales_products.tbl_salesId = tbl_sales.id AND tbl_sales_products.deleted = 'No'
                LEFT OUTER JOIN tbl_products ON tbl_sales_products.tbl_productsId = tbl_products.id
                WHERE tbl_sales.type = 'WalkinSale' AND tbl_sales.deleted='No'
                GROUP BY tbl_sales.id
                ORDER BY salesOrderNo DESC";
    }else if(isset($_GET['sortData'])){
	    $dates = explode(",",$_GET['sortData']);
	    $sql = "SELECT tbl_sales.id, tbl_sales.salesOrderNo,tbl_sales.type,tbl_sales.salesDate, tbl_walkin_customer.customerName, tbl_walkin_customer.phoneNo, tbl_sales.grandTotal, tbl_users.fname, tbl_users.username, tbl_sales.totalDiscount, tbl_sales.paidAmount, tbl_sales.totalAmount, CONCAT('<ul>', GROUP_CONCAT(CONCAT('<li>', tbl_products.productName,' - ',tbl_products.productCode, ' (',tbl_sales_products.quantity,')') SEPARATOR '</li>'), '</li></ul>') AS salesProducts
                FROM tbl_sales 
                LEFT OUTER JOIN tbl_walkin_customer ON tbl_sales.tbl_customerId = tbl_walkin_customer.id AND tbl_walkin_customer.deleted='No'
                LEFT OUTER JOIN tbl_users ON tbl_sales.tbl_userId = tbl_users.id AND tbl_users.deleted='No'
                LEFT OUTER JOIN tbl_sales_products ON tbl_sales_products.tbl_salesId = tbl_sales.id AND tbl_sales_products.deleted = 'No'
                LEFT OUTER JOIN tbl_products ON tbl_sales_products.tbl_productsId = tbl_products.id
                WHERE tbl_sales.type = 'WalkinSale' AND tbl_sales.deleted='No' AND tbl_sales.salesDate BETWEEN '".$dates[0]."' AND '".$dates[1]."'
                GROUP BY tbl_sales.id ORDER BY salesOrderNo DESC";
    }else if(isset($_GET['customerId'])){
        $customerId = $_GET['customerId'];
        $sql = "SELECT tbl_sales.id, tbl_sales.salesOrderNo,tbl_sales.type,tbl_sales.salesDate, tbl_walkin_customer.customerName, tbl_walkin_customer.phoneNo, tbl_sales.grandTotal, tbl_users.fname, tbl_users.username, tbl_sales.totalDiscount, tbl_sales.paidAmount, tbl_sales.totalAmount, CONCAT('<ul>', GROUP_CONCAT(CONCAT('<li>', tbl_products.productName,' - ',tbl_products.productCode, ' (',tbl_sales_products.quantity,')') SEPARATOR '</li>'), '</li></ul>') AS salesProducts
                FROM tbl_sales 
                LEFT OUTER JOIN tbl_walkin_customer ON tbl_sales.tbl_customerId = tbl_walkin_customer.id AND tbl_walkin_customer.deleted='No'
                LEFT OUTER JOIN tbl_users ON tbl_sales.tbl_userId = tbl_users.id AND tbl_users.deleted='No'
                LEFT OUTER JOIN tbl_sales_products ON tbl_sales_products.tbl_salesId = tbl_sales.id AND tbl_sales_products.deleted = 'No'
                LEFT OUTER JOIN tbl_products ON tbl_sales_products.tbl_productsId = tbl_products.id
                WHERE tbl_sales.type = 'WalkinSale' AND tbl_sales.deleted='No' AND tbl_sales.tbl_customerId = '$customerId'
                GROUP BY tbl_sales.id ORDER BY id DESC";
    }
    $result = $conn->query($sql);
    $i=1;
    $output = array('data' => array());
    while ($row = $result->fetch_array()) {
        $salesId = $row['id'];
        $button = '	<div class="btn-group">
						<button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
						<i class="fa fa-gear tiny-icon"></i>  <span class="caret"></span></button>
						<ul class="dropdown-menu dropdown-menu-right" style="border: 1px solid gray;" role="menu">
							<li><a  href="#" onclick="salesReport('.$salesId.',\'WalkinSale\')"><i class="fa fa-print tiny-icon"></i>View Details</a></li>
							<li><a href="sale-return.php?salesId='.$row['id'].'&salesType='.$row['type'].'"><i class="fa fa-mail-reply"></i> Sales Return</a></li>
							<li><a href="#" onclick="createChallan('.$salesId.',\'WalkinSale\')"><i class="fa fa-external-link" aria-hidden="true"></i>Create Challan</a></li>';
		if(strtolower($_SESSION['userType']) == 'super admin' || strtolower($_SESSION['userType']) == 'admin support plus'){					
		    $button .=  '<li><a href="#" onclick="deleteSales(' . $salesId . ')"><i class="fa fa-edit tiny-icon"></i>Delete</a></li>';
		}
		$action .=	    '</ul>
					</div>';
        $output['data'][] = array(
            $i++,
            $row['salesOrderNo'],
            $row['customerName'].'<br>Mobile: '.$row['phoneNo'].'<br>'.$row['salesDate'],
            $row['fname'].' - '.$row['username'],
            $row['salesProducts'],
            'Total: '.$row['totalAmount'].'<br>Discount: '.$row['totalDiscount'].'<br>Grand: '.$row['grandTotal'].'<br>Paid: '.$row['paidAmount'],
            $button
        );
    } // /while 
    echo json_encode($output);    
}
?>