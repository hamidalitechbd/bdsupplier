<?php
$conPrefix = '../';
include $conPrefix . 'includes/session.php';

date_default_timezone_set('Asia/Dhaka');
$toDay = (new DateTime())->format("Y-m-d H:i:s");
$toDate = (new DateTime())->format("Y-m-d");
if(isset($_POST["action"])){
    if($_POST['action'] == "save"){
        $error=0;
	    $loginID = $_SESSION['user'];
	    $offerName = $_POST['offerName'];
	    $partyType = $_POST['partyType'];
	    $startDate = $_POST['startDate'];
	    $remainderDate = $_POST['remainderDate'];
	    $endDate = $_POST['endDate'];
	    $products = $_POST['products'];
	    $priority = $_POST['priority'];
	    $offerFor = $_POST['offerFor'];
	    $offerForType = $_POST['offerForType'];
	    $discountAmount = $_POST['discountAmount'];
	    $discountType = $_POST['discountType'];
	    $discountAmount_2 = $_POST['discountAmount_2'];
	    $discountUnit_2 = $_POST['discountUnit_2'];
	    $remarks = $_POST['remarks'];

		$sql = "INSERT INTO tbl_discount_offer(offer_name, date_from, date_to, remainder_date, tbl_products_id, offer_for, unit_for, discount,discount_unit, priority, created_date, created_by, offer_applicable, discount_2, 	discount_unit_2) 
		        VALUES ('$offerName','$startDate','$endDate','$remainderDate','$products','$offerFor','$offerForType','$discountAmount','$discountType','$priority','$toDay','$loginID', '$partyType','$discountAmount_2','$discountUnit_2')";
		$conn->query($sql);
		if($products != '0' && $products != ''){
    		$sql = "SELECT offer_applicable
                    FROM tbl_discount_offer
                    WHERE tbl_products_id = '$products' AND tbl_discount_offer.status='Active' AND deleted = 'No'";
            $result = $conn->query($sql);
            $isdiscount_wi = '0';
            $isdiscount_party = '0';
            $isdiscount_ts = '0';
            while ($row = $result->fetch_array()) {
                if($row['offer_applicable'] == 'wiCustomer'){
                    $isdiscount_wi = '1';
                }else if($row['offer_applicable'] == 'Party'){
                    $isdiscount_party = '1';
                }else if($row['offer_applicable'] == 'TS'){
                    $isdiscount_ts = '1';
                }
            }
            $isdiscount = $isdiscount_wi.$isdiscount_party.$isdiscount_ts;
            $sql = "UPDATE tbl_products
                    SET isdiscount = '$isdiscount'
                    WHERE id='$products' AND deleted='No'";
            $conn->query($sql);
		}
		echo json_encode('Success');
	}else if ($_POST['action'] == "deleteOffer"){
        $loginID = $_SESSION['user'];
        $id = $_POST['id'];
        $sql = "UPDATE tbl_discount_offer
                SET deleted = 'Yes', deleted_by='$loginID', deleted_date='$toDay'
                WHERE id='$id'";
        if($conn->query($sql)){
            $sql = "select tbl_products_id from tbl_discount_offer where id='$id'";
            $result = $conn->query($sql);
            $row = $result->fetch_array();
            $product_id = $row['tbl_products_id'];
            $sql = "UPDATE tbl_products
                    SET isdiscount = '000'
                    WHERE id='$product_id' AND deleted='No'";
            $conn->query($sql);
            $sql = "SELECT offer_applicable
                    FROM tbl_discount_offer
                    WHERE tbl_products_id = '$product_id' AND tbl_discount_offer.status='Active' AND deleted = 'No'";
            $result = $conn->query($sql);
            $isdiscount_wi = '0';
            $isdiscount_party = '0';
            $isdiscount_ts = '0';
            while ($row = $result->fetch_array()) {
                if($row['offer_applicable'] == 'wiCustomer'){
                    $isdiscount_wi = '1';
                }else if($row['offer_applicable'] == 'Party'){
                    $isdiscount_party = '1';
                }else if($row['offer_applicable'] == 'ts'){
                    $isdiscount_ts = '1';
                }
            }
            $isdiscount = $isdiscount_wi.$isdiscount_party.$isdiscount_ts;
            $sql = "UPDATE tbl_products
                    SET isdiscount = '$isdiscount'
                    WHERE id='$id' AND deleted='No'";
            $conn->query($sql);
            echo json_encode('Success');
        }else{
            echo json_encode("Error: ".$conn->error.$sql);
        }
    }else if ($_POST['action'] == "statusOffer"){
        $loginID = $_SESSION['user'];
        $id = $_POST['id'];
        if($_POST['status']=='Active'){
             $sql = "UPDATE tbl_discount_offer SET status = 'Inactive' WHERE id='$id'";
        }else{
            $sql = "UPDATE tbl_discount_offer SET status = 'Active' WHERE id='$id'";
        }
        if($conn->query($sql)){
            echo json_encode('Success');
        }else{
            echo json_encode("Error: ".$conn->error.$sql);
        }
    }
    	/* Discount offer */
	else if($_POST["action"] == 'discountOffer'){
	    $productId = $_POST['productId'];
	    $partyType = $_POST['type'];
		if($productId != ''){
		    /*$sql = "SELECT tbl_discount_offer.id,tbl_products.productName,tbl_brands.brandName,tbl_products.modelNo,tbl_discount_offer.offer_name,tbl_discount_offer.date_from,tbl_discount_offer.date_to,tbl_discount_offer.offer_for,
                    tbl_discount_offer.unit_for,tbl_discount_offer.discount,tbl_discount_offer.discount_unit
                    FROM tbl_discount_offer
                    LEFT JOIN tbl_products ON tbl_products.id=tbl_discount_offer.tbl_products_id
                    LEFT JOIN tbl_brands ON tbl_brands.id=tbl_products.tbl_brandsId
                    WHERE tbl_discount_offer.tbl_products_id='$productId' AND tbl_discount_offer.deleted='No' AND tbl_discount_offer.offer_applicable = '$partyType'
                    ORDER BY priority DESC";*/
             $sql = "SELECT tbl_discount_offer.id,tbl_products.productName,tbl_brands.brandName,tbl_products.modelNo,tbl_discount_offer.offer_name,tbl_discount_offer.date_from, tbl_discount_offer.date_to, tbl_discount_offer.offer_for, tbl_discount_offer.unit_for,tbl_discount_offer.discount,tbl_discount_offer.discount_unit, tbl_discount_offer.discount_2, tbl_discount_offer.discount_unit_2
                    FROM tbl_discount_offer
                    LEFT JOIN tbl_products ON tbl_products.id=tbl_discount_offer.tbl_products_id
                    LEFT JOIN tbl_brands ON tbl_brands.id=tbl_products.tbl_brandsId
                    WHERE tbl_discount_offer.tbl_products_id='$productId' AND tbl_discount_offer.status='Active' AND tbl_discount_offer.deleted='No' AND tbl_discount_offer.offer_applicable = '$partyType' AND '$toDate' BETWEEN date_from AND date_to
                    ORDER BY priority DESC";
            //echo $sql;        
            $result = $conn->query($sql);
               
            $dataString = "<table>";
            $dataString .= "<tr><th>Product Name:</th><th>Offer Name</th><th>Start Date</th><th>End Date</th><th>Description</th></tr>";
            while($row = $result->fetch_assoc()){
                $productName = $row['productName'];
                $brandName = $row['brandName'];
                $modelNo = $row['modelNo'];
                $offer_name = $row['offer_name'];
                $date_from = $row['date_from'];
                $date_to = $row['date_to'];
                $offer_for = $row['offer_for'];
                $unit_for = $row['unit_for'];
                $discount = $row['discount'];
                $discount_unit = $row['discount_unit'];
                $discount_2 = '';
                if($row['discount_2'] > 0 && $row['discount_2'] != ""){
                    $discount_2 = ' and '.$row['discount_2'].' '.$row['discount_unit_2'];
                }
                $dataString .= "<tr><td> $productName<br>$brandName<br>$modelNo</td><td> $offer_name</td><td> $date_from</td><td>$date_to</td><td>$offer_for $unit_for For $discount $discount_unit $discount_2</td></tr>";
            }
		}
        $dataString .= "</table>";
        
        
        echo $dataString;
	}
	else if($_POST["action"] == 'discountOfferPreview'){
	    $type = $_POST['type'];
	      $dataString = '<table class="table table-bordered">
	            <tr style="text-align: center;">
	                <td colspan="6"><img src="images/companylogo/Jafree.jpg"/></td>
	             </tr>
	            <tr style="text-align: center;">
	                   <td colspan="6"> 212, Jubilee Road, Chittagong-4000, Bangladesh.Tel:031-617505,615062 Mobile: 01973105100,01711-325119<br>E-mail:info@jafreetraders.com</td>
	           </tr>
	       </table>
	       <style>
	           .shoaib{font-size: 10px;}
	           
	       </style>';
    if($type == "Party"){
        $checkOverQuantity = 0;
	   
    if(!empty($_SESSION["wholeSaleShopping_cart"])){
        $data = '';
        $i = 0;
        $checkErrorManualMsg='';
        foreach($_SESSION["wholeSaleShopping_cart"] as $keys => $values){
            $warehouseWiseProductArray[$i]['product_id'] = $values['product_id'];
            $warehouseWiseProductArray[$i]['product_quantity'] = $values['product_quantity'];
            $warehouseWiseProductArray[$i]['warehouse_id'] = $values['warehouse_id'];
            $warehouseWiseProductArray[$i]['warehouse_name'] = $values['warehouse_name'];
            if (isset($newArray[$values['product_id']])) {
                $newArray[$values['product_id']] = $newArray[$values['product_id']] + $values['product_quantity'];
            } else {
                $newArray[$values['product_id']] = $values['product_quantity'];
            }
            $i++;
        }
        $checkErrorManual = 1;
	    $dataString .= '<table class="table table-bordered" style="width:100%;">';
        $dataString .= "<tr><th>Product Name</th><th>Warehouse</th><th>Quantity</th><th>Price</th><th>Discount</th><th style='text-align: center;'>Total</th></tr>";    
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
				$product_name = $values['product_name'];
				//$warehouseName = $warehouseWiseProductArray[$i]['warehouse_name'];
				$sql_discountOffer = "SELECT id,offer_applicable,offer_for,unit_for,discount,discount_unit,discount_2,discount_unit_2, offer_name
                                    FROM tbl_discount_offer 
									WHERE '$toDate' BETWEEN date_from AND date_to AND tbl_discount_offer.status='Active' AND deleted = 'No' AND tbl_products_id = '".$productIdEntry."' AND offer_applicable='Party' AND priority > 0
                                    ORDER BY priority DESC, offer_for DESC";
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
                            						$calling_qty = $rest_pc;
                            						for($i = 0; $i < count($warehouseWiseProductArray); $i++){
                            						    if($warehouseWiseProductArray[$i]['product_id'] == $productIdEntry && $calling_qty > 0 && $warehouseWiseProductArray[$i]['product_quantity'] > 0){
                            						        if($warehouseWiseProductArray[$i]['product_quantity'] >= $calling_qty){
                            						            $printQty = $calling_qty;
                            						            $printDiscount = ($productDiscount/$rest_pc)*$calling_qty;
                            						            $printTotal = ($productTotal/$rest_pc)*$calling_qty;
                            						            $warehouseName = $warehouseWiseProductArray[$i]['warehouse_name'];
                            						            //$dataString .= "<tr><td>$product_name</td><td>$warehouseName</td><td>$calling_qty</td><td>$productPriceEntry</td><td>($discountOfferValue/$rest_pc)*$calling_qty</td><td>($productTotal/$rest_pc)*$calling_qty</td></tr>";
                            						            /*$salesProductArray[$row][0] = $productIdEntry;
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
                                        					    $row++;*/
                                        					    $warehouseWiseProductArray[$i]['product_quantity'] -= $calling_qty;
                                        					    $calling_qty = 0;
                            						        }else{
                            						            $printQty = $warehouseWiseProductArray[$i]['product_quantity'];
                            						            $printDiscount = ($productDiscount/$rest_pc)*$warehouseWiseProductArray[$i]['product_quantity'];
                            						            $printTotal = ($productTotal/$rest_pc)*$warehouseWiseProductArray[$i]['product_quantity'];
                            						            $warehouseName = $warehouseWiseProductArray[$i]['warehouse_name'];
                            						            /*$salesProductArray[$row][0] = $productIdEntry;
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
                                        					    $row++;*/
                                        					    $calling_qty -= $warehouseWiseProductArray[$i]['product_quantity'];
                                        					    $warehouseWiseProductArray[$i]['product_quantity'] = 0;
                            						        }
                                    					    $dataString .= "<tr><td>$product_name</td><td>$warehouseName</td><td>$printQty</td><td>$productPriceEntry</td><td>$printDiscount</td><td>$printTotal</td></tr>";
                            						    }else if($calling_qty == 0){
                            						        break;
                            						    }
                            						}
                            					    $newProductQtyArray[$productIdEntry][0]=$productIdEntry;
                            					    $newProductQtyArray[$productIdEntry][1]+=$rest_pc;
                            						    $grandTotalAfterOffer += $productTotal;
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
                                                    $printQty = $discount_pc;
                						            $printDiscount = $productDiscount;
                						            $printTotal = $productTotal;
                						            $warehouseName = "<span style='color:red;'>N/S</span>";
                						            $dataString .= "<tr><td>$product_name</td><td>$warehouseName</td><td>$printQty</td><td>$productPriceEntry</td><td>$printDiscount</td><td>$printTotal</td></tr>";
                    						        /*$salesProductArray[$row][0] = $productIdEntry;
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
                            					    $row++;*/
                            					    $newProductQtyArray[$productIdEntry][0]=$productIdEntry;
                            					    $newProductQtyArray[$productIdEntry][1]+=$discount_pc;
                            						    $grandTotalAfterOffer += $productTotal;
                                						
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
                            						            $printQty = $calling_qty;
                            						            $printDiscount = $row_discountOffer['discount'].'%';
                            						            $printTotal = $productTotal;
                            						            $warehouseName = $warehouseWiseProductArray[$i]['warehouse_name'];
                            						            /*$salesProductArray[$row][0] = $productIdEntry;
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
                                        					    $row++;*/
                                        					    $warehouseWiseProductArray[$i]['product_quantity'] -= $calling_qty;
                                        					    $calling_qty = 0;
                            						        }else{
                            						            $printQty = $warehouseWiseProductArray[$i]['product_quantity'];
                            						            $printDiscount = $row_discountOffer['discount'].'%';
                            						            $printTotal = $productTotal;
                            						            $warehouseName = $warehouseWiseProductArray[$i]['warehouse_name'];
                            						            /*$salesProductArray[$row][0] = $productIdEntry;
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
                                        					    $row++;*/
                                        					    $calling_qty -= $warehouseWiseProductArray[$i]['product_quantity'];
                                        					    $warehouseWiseProductArray[$i]['product_quantity'] = 0;
                            						        }
                                    					    $dataString .= "<tr><td>$product_name</td><td>$warehouseName</td><td>$printQty</td><td>$productPriceEntry</td><td>$printDiscount</td><td>$printTotal</td></tr>";
                            						    }else if($calling_qty == 0){
                            						        break;
                            						    }
                            						}
                            					    $newProductQtyArray[$productIdEntry][0]=$productIdEntry;
                            					    $newProductQtyArray[$productIdEntry][1]+=$discount_quantity;
                            						    $grandTotalAfterOffer += $productTotal;
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
                            					    $calling_qty = $discount_quantity;
                            					    for($i = 0; $i < count($warehouseWiseProductArray); $i++){
                            						    if($warehouseWiseProductArray[$i]['product_id'] == $productIdEntry && $calling_qty > 0 && $warehouseWiseProductArray[$i]['product_quantity'] > 0){
                            						        if($warehouseWiseProductArray[$i]['product_quantity'] >= $calling_qty){
                            						            $printQty = $calling_qty;
                            						            $printDiscount = $productDiscount;
                            						            $printTotal = $productTotal;
                            						            $warehouseName = $warehouseWiseProductArray[$i]['warehouse_name'];
                            						            /*$salesProductArray[$row][0] = $productIdEntry;
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
                                        					    $row++;*/
                                        					    $warehouseWiseProductArray[$i]['product_quantity'] -= $calling_qty;
                                        					    $calling_qty = 0;
                            						        }else{
                            						            $printQty = $warehouseWiseProductArray[$i]['product_quantity'];
                            						            $printDiscount = $productDiscount;
                            						            $printTotal = $productTotal;
                            						            $warehouseName = $warehouseWiseProductArray[$i]['warehouse_name'];
                            						            /*$salesProductArray[$row][0] = $productIdEntry;
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
                                        					    $row++;*/
                                        					    $calling_qty -= $warehouseWiseProductArray[$i]['product_quantity'];
                                        					    $warehouseWiseProductArray[$i]['product_quantity'] = 0;
                            						        }
                                    					    $dataString .= "<tr><td>$product_name</td><td>$warehouseName</td><td>$printQty</td><td>$productPriceEntry</td><td>$printDiscount</td><td>$printTotal</td></tr>";
                            						    }else if($calling_qty == 0){
                            						        break;
                            						    }
                            						}
                            					    $newProductQtyArray[$productIdEntry][0]=$productIdEntry;
                            					    $newProductQtyArray[$productIdEntry][1]+=$discount_quantity;
                            						    $grandTotalAfterOffer += $productTotal;
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
        					    $calling_qty = $total_quantity;
        						for($i = 0; $i < count($warehouseWiseProductArray); $i++){
        						    if($warehouseWiseProductArray[$i]['product_id'] == $productIdEntry && $calling_qty > 0 && $warehouseWiseProductArray[$i]['product_quantity'] > 0){
        						        if($warehouseWiseProductArray[$i]['product_quantity'] >= $calling_qty){
        						            $printQty = $calling_qty;
        						            $printDiscount = $productDiscountEntry;
        						            $printTotal = number_format($productTotal,2);
        						            $warehouseName = $warehouseWiseProductArray[$i]['warehouse_name'];
        						            /*$salesProductArray[$row][0] = $productIdEntry;
                    					    $salesProductArray[$row][1] = $calling_qty;
                    					    $salesProductArray[$row][2] = '';
                    					    $salesProductArray[$row][3] = $productPriceEntry;
                    					    $salesProductArray[$row][4] = $productTotal;
                    					    $salesProductArray[$row][5] = $productDiscountEntry;
                    					    $salesProductArray[$row][6] = $productTotal;
                    					    $salesProductArray[$row][7] = $productDiscount;
                    					    $salesProductArray[$row][8] = 0;
                    					    $salesProductArray[$row][9] = $warehouseWiseProductArray[$i]['warehouse_id'];
                    					    $salesProductArray[$row][10] = $row;
                    					    $salesProductArray[$row][11] = $product_name;
                    					    $salesProductArray[$row][12] = '';
                    					    $row++;*/
                    					    $warehouseWiseProductArray[$i]['product_quantity'] -= $calling_qty;
                    					    $calling_qty = 0;
        						        }else{
        						            $printQty = $warehouseWiseProductArray[$i]['product_quantity'];
        						            $printDiscount = $productDiscount;
        						            $printTotal = number_format($productTotal,2);
        						            $warehouseName = $warehouseWiseProductArray[$i]['warehouse_name'];
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
                    					    $salesProductArray[$row][12] = '';*/
                    					    $row++;
                    					    $calling_qty -= $warehouseWiseProductArray[$i]['product_quantity'];
                    					    $warehouseWiseProductArray[$i]['product_quantity'] = 0;
        						        }
        						        $dataString .= "<tr><td>$product_name</td><td>$warehouseName</td><td>$printQty</td><td>$productPriceEntry</td><td>$printDiscount</td><td style='text-align: right;'>$printTotal</td></tr>";
        						    }else if($calling_qty == 0){
        						        break;
        						    }
        						}
        					    $newProductQtyArray[$productIdEntry][0]=$productIdEntry;
        					    $newProductQtyArray[$productIdEntry][1]+=$total_quantity;
        						    $grandTotalAfterOffer += $productTotal;
            						
                        		$total_price = $total_price + $productTotal;
                        		$total_item = $total_item + 1;
                                $totalProductDiscount = $totalProductDiscount + $productDiscount;
                    	    }
	        }
	    }
		//$dataString = '';
		/*$total_price = 0;
		$totalProductDiscount = 0;
		$total_item = 0;
		$stock_check = 'On';
        $dataString .= '<table>';
        $dataString .= "<tr><th>Product Name:</th><th>Warehouse</th><th>Available</th><th>Quantity</th><th>Price</th><th>Discount</th><th>Total</th></tr>";
    	foreach($_SESSION["wholeSaleShopping_cart"] as $keys => $values){
    	    
    	    $sql_discountOffer = "SELECT id,offer_applicable,offer_for,unit_for,discount,discount_unit,discount_2,discount_unit_2
                                    FROM tbl_discount_offer 
									WHERE '$toDate' BETWEEN date_from AND date_to AND deleted = 'No' AND tbl_products_id = '".$values["product_id"]."' AND offer_applicable='Party' AND priority > 0
                                    ORDER BY priority DESC, offer_for DESC";
                                   
                    //$dataString .= '<tr><td colspan=6>'.$sql_discountOffer.'</td></tr>';
            $result_discountOffer = $conn->query($sql_discountOffer);
            $discount_pc = 0;
            $discount_amount = 0;
            $rest_pc = 0;
            $rest_amount = 0;
            $test = 0;
            $total_quantity = $values["product_quantity"];
            if($result_discountOffer->num_rows > 0 ){
                while($row_discountOffer = $result_discountOffer->fetch_assoc()){
                   
                    if($row_discountOffer['unit_for'] == 'PC'){
                        
						if($row_discountOffer['discount_unit'] == 'PC'){
						    
							$discount_pc = floor($total_quantity / $row_discountOffer['offer_for']);
							$discount_amount = '100%';
							$rest_pc = $discount_pc * $row_discountOffer['offer_for'];
							$discount_pc = $discount_pc * $row_discountOffer['discount'];
							if($discount_pc + $rest_pc < $values["product_limit"]){
								if($rest_pc > 0){
									$dataString .= '
										<tr>
										<td>'.$values["product_name"].'<input type="hidden" id="pproductId'.$values["product_id"].'" name="productId" value="'.$values["product_id"].'"/></td>
										<td><input type="text" Readonly id="pavailableQuantity'.$values["product_id"].'" name="availableQuantity" value="'.$values["product_limit"].'" style="width: 100%;text-align: center;" Readonly/></td>
										<td><input type="text" Readonly id="pproductQuantity'.$values["product_id"].'" name="productQuantity" value="'.$rest_pc.'" onkeyup="calculateTotal('.$values["product_id"].')" onblur="updateSession('.$values["product_id"].')" style="width: 100%;text-align: center;"/></td>
										<td align="right"><input type="text" Readonly id="pproductPrice'.$values["product_id"].'" name="productPrice" value="'.$values["product_price"].'" onkeyup="calculateTotal('.$values["product_id"].')"  onblur="updateSession('.$values["product_id"].')" style="width: 100%;text-align: center;"/></td>';
							
									if($row_discountOffer['discount_unit_2'] == 'TK'){
									    if($row_discountOffer['discount_2'] != 0){
										    $discountOfferValue = ($row_discountOffer['discount_2']*floor($total_quantity / $row_discountOffer['offer_for']));
									    }else{
									        $discountOfferValue = $values['product_discount'];
									    }
									}else{
										$discountOfferValue = $values['product_discount'];
									}
									$dataString .= '<td align="right"><input type="text" Readonly id="pproductDiscount'.$values["product_id"].'" name="productDiscount" value="'.$discountOfferValue.'"  style="width: 100%;text-align: center;" /></td>';
									$productTotal = $rest_pc * $values["product_price"];
									if($discountOfferValue != ""){
										$lastValue = substr($discountOfferValue, -1);
										if($lastValue == "%"){
											$productDiscount = $productTotal * (substr($discountOfferValue, 0, -1)/100);
										}else{
											$productDiscount = $discountOfferValue;
										}
										$productTotal = $productTotal - $productDiscount;
									}
									//$productTotal = $productTotal - $productDiscount;
									$dataString .= '
										<td align="right"><span id="pproductTotal'.$values['product_id'].'"> '.sprintf("%.2f", $productTotal).'</span></td>
										<td>'.$row_discountOffer['offer_name'].'</td>
									</tr>';
									$total_price = $total_price + $productTotal;
									$total_item = $total_item + 1;
									$totalProductDiscount = $totalProductDiscount + $productDiscount;
								}
										
								if($discount_pc > 0){
									$dataString .= '
									<tr>
									<td>'.$values["product_name"].'<input type="hidden" id="pproductId'.$values["product_id"].'" name="productId" value="'.$values["product_id"].'"/></td>
									<td><input type="text" Readonly id="pavailableQuantity'.$values["product_id"].'" name="availableQuantity" value="'.$values["product_limit"].'" style="width: 100%;text-align: center;" Readonly/></td>
									<td><input type="text" Readonly id="pproductQuantity'.$values["product_id"].'" name="productQuantity" value="'.$discount_pc.'" onkeyup="calculateTotal('.$values["product_id"].')" onblur="updateSession('.$values["product_id"].')" style="width: 100%;text-align: center;"/></td>
									<td align="right"><input type="text" Readonly id="pproductPrice'.$values["product_id"].'" name="productPrice" value="'.$values["product_price"].'" onkeyup="calculateTotal('.$values["product_id"].')"  onblur="updateSession('.$values["product_id"].')" style="width: 100%;text-align: center;"/></td>
									<td align="right"><input type="text" Readonly id="pproductDiscount'.$values["product_id"].'" name="productDiscount" value="'.$discount_amount.'" onkeyup="calculateTotal('.$values["product_id"].')"  onblur="updateSession('.$values["product_id"].')" style="width: 100%;text-align: center;" /></td>';
									//$productTotal = $values["product_quantity"] * $values["max_price"];
									$productTotal = $discount_pc * $values["product_price"];
									$productDiscount = $productTotal;
									$productTotal = $productTotal - $productDiscount;
									$dataString .= '
										<td align="right"><span id="pproductTotal'.$values['product_id'].'"> '.sprintf("%.2f", $productTotal).'</span></td>
										<td>'.$row_discountOffer['offer_name'].'</td>
									</tr>';
									$total_price = $total_price + $productTotal;
									$total_item = $total_item + 1;
									$totalProductDiscount = $totalProductDiscount + $productDiscount;
								}
							}else{
								$dataString = '<tr><td colspan="7"><span style="color:red; font-weight:bold;">Product:'.$values["product_name"].' limit is over</span></td></tr>';
								$stock_check = 'Off';
							}
							
							$total_quantity = $total_quantity - ($rest_pc);
						}
					
						if($stock_check == 'On'){
						    
							if($row_discountOffer['discount_unit'] == '%'){
								if($total_quantity >= $row_discountOffer['offer_for']){
									$discount_quantity = $row_discountOffer['offer_for'] * floor($total_quantity / $row_discountOffer['offer_for']);
									$productTotal = $discount_quantity * $values["product_price"];
									$productDiscount = $productTotal * ($row_discountOffer['discount']/100);
									
									$dataString .= '
										<tr>
										<td>'.$values["product_name"].'<input type="hidden" id="pproductId'.$values["product_id"].'" name="productId" value="'.$values["product_id"].'"/></td>
										<td><input type="text" Readonly id="pavailableQuantity'.$values["product_id"].'" name="availableQuantity" value="'.$values["product_limit"].'" style="width: 100%;text-align: center;" Readonly/></td>
										<td><input type="text" Readonly id="pproductQuantity'.$values["product_id"].'" name="productQuantity" value="'.$discount_quantity.'" onkeyup="calculateTotal('.$values["product_id"].')" onblur="updateSession('.$values["product_id"].')" style="width: 100%;text-align: center;"/></td>
										<td align="right"><input type="text" Readonly id="pproductPrice'.$values["product_id"].'" name="productPrice" value="'.$values["product_price"].'" onkeyup="calculateTotal('.$values["product_id"].')"  onblur="updateSession('.$values["product_id"].')" style="width: 100%;text-align: center;"/></td>';
									$dataString .= '<td align="right"><input type="text" Readonly id="pproductDiscount'.$values["product_id"].'" name="productDiscount" value="'.$row_discountOffer['discount'].'%" onkeyup="calculateTotal('.$values["product_id"].')"  onblur="updateSession('.$values["product_id"].')" style="width: 100%;text-align: center;" /></td>';
									
									$productTotal = $productTotal - $productDiscount;
									$dataString .= '
										<td align="right"><span id="pproductTotal'.$values['product_id'].'"> '.sprintf("%.2f", $productTotal).'</span></td>
										<td>'.$row_discountOffer['offer_name'].'</td>
										</tr>';
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
									$dataString .= '
										<tr>
										<td>'.$values["product_name"].'<input type="hidden" id="pproductId'.$values["product_id"].'" name="productId" value="'.$values["product_id"].'"/></td>
										<td><input type="text" Readonly id="pavailableQuantity'.$values["product_id"].'" name="availableQuantity" value="'.$values["product_limit"].'" style="width: 100%;text-align: center;" Readonly/></td>
										<td><input type="text" Readonly id="pproductQuantity'.$values["product_id"].'" name="productQuantity" value="'.$discount_quantity.'" onkeyup="calculateTotal('.$values["product_id"].')" onblur="updateSession('.$values["product_id"].')" style="width: 100%;text-align: center;"/></td>
										<td align="right"><input type="text" Readonly id="pproductPrice'.$values["product_id"].'" name="productPrice" value="'.$values["product_price"].'" onkeyup="calculateTotal('.$values["product_id"].')"  onblur="updateSession('.$values["product_id"].')" style="width: 100%;text-align: center;"/></td>
										<td align="right"><input type="text" Readonly id="pproductDiscount'.$values["product_id"].'" name="productDiscount" value="'.$productDiscount.'" onkeyup="calculateTotal('.$values["product_id"].')"  onblur="updateSession('.$values["product_id"].')" style="width: 100%;text-align: center;" /></td>';
									//echo ($values["max_price"]*$discount_quantity).' - '.$productDiscount;
									$productTotal = ($discount_quantity * $values["product_price"]) - $productDiscount;
									//$productTotal = $productTotal - $productDiscount;
									$dataString .= '
										<td align="right"><span id="pproductTotal'.$values['product_id'].'"> '.sprintf("%.2f", $productTotal).'</span></td>
										<td>'.$row_discountOffer['offer_name'].'</td>
										</tr>';
									$total_price = $total_price + $productTotal;
									$total_item = $total_item + 1;
									$totalProductDiscount = $totalProductDiscount + ($productDiscount);
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
        		$dataString .= '
                	<tr>
        			<td>'.$values["product_name"].'<input type="hidden" id="pproductId'.$values["product_id"].'" name="productId" value="'.$values["product_id"].'"/></td>
        			<td><input type="text" Readonly id="pavailableQuantity'.$values["product_id"].'" name="availableQuantity" value="'.$values["product_limit"].'" style="width: 100%;text-align: center;" Readonly/></td>
        			<td><input type="text" Readonly id="pproductQuantity'.$values["product_id"].'" name="productQuantity" value="'.$total_quantity.'" onkeyup="calculateTotal('.$values["product_id"].')" onblur="updateSession('.$values["product_id"].')" style="width: 100%;text-align: center;"/></td>
        			<td align="right"><input type="text" Readonly id="pproductPrice'.$values["product_id"].'" name="productPrice" value="'.$values["product_price"].'" onkeyup="calculateTotal('.$values["product_id"].')"  onblur="updateSession('.$values["product_id"].')" style="width: 100%;text-align: center;"/></td>
        			<td align="right"><input type="text" Readonly id="pproductDiscount'.$values["product_id"].'" name="productDiscount" value="'.$values["product_discount"].'" onkeyup="calculateTotal('.$values["product_id"].')"  onblur="updateSession('.$values["product_id"].')" style="width: 100%;text-align: center;" /></td>';
    	        //$productTotal = $values["product_quantity"] * $values["max_price"];
    	        $productTotal = $total_quantity * $values["product_price"];
        	    if($values["product_discount"] != ""){
        		    $lastValue = substr($values["product_discount"], -1);
        		    if($lastValue == "%"){
        		        $productDiscount = $productTotal * (substr($values["product_discount"], 0, -1)/100);
        		    }else{
        		        $productDiscount = $values["product_discount"];
        		    }
        		    $productTotal = $productTotal - $productDiscount;
        	    }
        		$dataString .= '
        		    <td align="right"><span id="pproductTotal'.$values['product_id'].'"> '.sprintf("%.2f", $productTotal).'</span></td>
        		</tr>';
        		$total_price = $total_price + $productTotal;
        		
        		$total_item = $total_item + 1;
                $totalProductDiscount = $totalProductDiscount + $productDiscount;
    	    }
    	}
    	$dataString .= '<table class="table table-bordered" border="1">';*/
    	$dataString .= '
    	<tr>  
            <td colspan="5" align="right">Total
            <br>Product Discount</td>  
            <td align="right"><span class="totalAmount">'.sprintf("%.2f", $total_price).'</span>
            <br><span class="totalProductDiscount" style="text-align: center;">'.number_format($totalProductDiscount,2).'</span></td>  
              
        </tr>
    	<tr>  
            <td colspan="5" align="right">Sales Discount
            <br>Total Discount</td>
            <td align="right"><input type="text" id="psalesDiscount" style="width:50%;text-align: right;" onkeyup="calculateTotalDiscount()" value="0"/>
            <br><span class="totalDiscount" style="text-align: center;">'.number_format($totalProductDiscount,2).'</span></td>  
              
        </tr>
        <tr>
            <td colspan="5" align="right">Carring Cost</td>
            <td align="right"><input type="text" id="pcarringCost" name="carringCost" style="width:50%;text-align: right;"  onkeyup="calculateTotalDiscount()" value="0" /></td>  
            
        </tr>
        <tr>
            <td colspan="5" align="right">VAT</td>
            <td align="right"><input type="text" id="pvat" onkeyup="calculateTotalDiscount()" name="vat" style="width:50%;text-align: right;" autocomplete="off" value="0"/></td>  
            
        </tr>
        <tr>
            <td colspan="5" align="right">AIT</td>
            <td align="right"><input type="text" id="pait" name="ait" onkeyup="calculateTotalDiscount()" style="width:50%;text-align: right;" autocomplete="off" value="0" /></td>  
            
        </tr>
        <tr>
            <td colspan="5" align="right">Grand Total</td>
            <td align="right"><span class="pgrandTotal" style="width: 50%;">'.sprintf("%.2f", $total_price).'</span></td>  
            
        </tr>
        <tr>
            <td colspan="5" align="right">Payment Method</td>
            <td align="right"><select id="ppaymentMethod" name="paymentMethod">
                <option value="CASH" selected>Cash</option>
            </select></td>  
            
        </tr>
        <tr>
            <td colspan="5" align="right">Cash Amount</td>
            <td align="right"><input type="text" id="ppaid" name="paid" style="width:50%;text-align: right;" autocomplete="off" value="0"/></td>  
            
        </tr>
        </table>
    	';
    }}
    else if($type == 'TS'){
       if(!empty($_SESSION["temporarySaleShopping_cart"])){
		//$dataString = '';
		$total_price = 0;
		$totalProductDiscount = 0;
		$total_item = 0;
		$stock_check = 'On';
        $dataString .= '<table class="table table-bordered">';
        $dataString .= "<tr><th>Product Name:</th><th>Available</th><th>Quantity</th><th>Price</th><th>Discount</th><th>Total</th></tr>";
    	foreach($_SESSION["temporarySaleShopping_cart"] as $keys => $values){
    	    $sql_discountOffer = "SELECT id,offer_applicable,offer_for,unit_for,discount,discount_unit,discount_2,discount_unit_2
                                    FROM tbl_discount_offer 
									WHERE '$toDate' BETWEEN date_from AND date_to AND tbl_discount_offer.status='Active' AND deleted = 'No' AND tbl_products_id = '".$values["product_id"]."' AND offer_applicable='TS' AND priority > 0
                                    ORDER BY priority DESC, offer_for DESC";
                                   
                    //$dataString .= '<tr><td colspan=6>'.$sql_discountOffer.'</td></tr>';
            $result_discountOffer = $conn->query($sql_discountOffer);
            $discount_pc = 0;
            $discount_amount = 0;
            $rest_pc = 0;
            $rest_amount = 0;
            $test = 0;
            $total_quantity = $values["product_quantity"];
            if($result_discountOffer->num_rows > 0 ){
                while($row_discountOffer = $result_discountOffer->fetch_assoc()){
                   
                    if($row_discountOffer['unit_for'] == 'PC'){
                        
						if($row_discountOffer['discount_unit'] == 'PC'){
							$discount_pc = floor($total_quantity / $row_discountOffer['offer_for']);
							$discount_amount = '100%';
							$rest_pc = $discount_pc * $row_discountOffer['offer_for'];
							$discount_pc = $discount_pc * $row_discountOffer['discount'];
							if($discount_pc + $rest_pc < $values["product_limit"]){
								if($rest_pc > 0){
									$dataString .= '
										<tr>
										<td>'.$values["product_name"].'<input type="hidden" id="pproductId'.$values["product_id"].'" name="productId" value="'.$values["product_id"].'"/></td>
										<td><input type="text" Readonly id="pavailableQuantity'.$values["product_id"].'" name="availableQuantity" value="'.$values["product_limit"].'" style="width: 100%;text-align: center;" Readonly/></td>
										<td><input type="text" Readonly id="pproductQuantity'.$values["product_id"].'" name="productQuantity" value="'.$rest_pc.'" onkeyup="calculateTotal('.$values["product_id"].')" onblur="updateSession('.$values["product_id"].')" style="width: 100%;text-align: center;"/></td>
										<td align="right"><input type="text" Readonly id="pproductPrice'.$values["product_id"].'" name="productPrice" value="'.$values["product_price"].'" onkeyup="calculateTotal('.$values["product_id"].')"  onblur="updateSession('.$values["product_id"].')" style="width: 100%;text-align: center;"/></td>';
									/*if($row_discountOffer['discount_unit_2'] == '%'){
										$discountOfferValue = $row_discountOffer['discount_2'].'%';
									}else */
									if($row_discountOffer['discount_unit_2'] == 'TK'){
										$discountOfferValue = ($row_discountOffer['discount_2']*(floor($total_quantity / $row_discountOffer['offer_for'])));
									}else{
										$discountOfferValue = $values['product_discount'];
									}
									$dataString .= '<td align="right"><input type="text" Readonly id="pproductDiscount'.$values["product_id"].'" name="productDiscount" value="'.$discountOfferValue.'"  style="width: 100%;text-align: center;" /></td>';
									$productTotal = $rest_pc * $values["product_price"];
									if($discountOfferValue != ""){
										$lastValue = substr($discountOfferValue, -1);
										if($lastValue == "%"){
											$productDiscount = $productTotal * (substr($discountOfferValue, 0, -1)/100);
										}else{
											$productDiscount = $discountOfferValue;
										}
										$productTotal = $productTotal - $productDiscount;
									}
									//$productTotal = $productTotal - $productDiscount;
									$dataString .= '
										<td align="right"><span id="pproductTotal'.$values['product_id'].'"> '.sprintf("%.2f", $productTotal).'</span></td>
										<td>'.$row_discountOffer['offer_name'].'</td>
									</tr>';
									$total_price = $total_price + $productTotal;
									$total_item = $total_item + 1;
									$totalProductDiscount = $totalProductDiscount + $productDiscount;
								}
										
								if($discount_pc > 0){
									$dataString .= '
									<tr>
									<td>'.$values["product_name"].'<input type="hidden" id="pproductId'.$values["product_id"].'" name="productId" value="'.$values["product_id"].'"/></td>
									<td><input type="text" Readonly id="pavailableQuantity'.$values["product_id"].'" name="availableQuantity" value="'.$values["product_limit"].'" style="width: 100%;text-align: center;" Readonly/></td>
									<td><input type="text" Readonly id="pproductQuantity'.$values["product_id"].'" name="productQuantity" value="'.$discount_pc.'" onkeyup="calculateTotal('.$values["product_id"].')" onblur="updateSession('.$values["product_id"].')" style="width: 100%;text-align: center;"/></td>
									<td align="right"><input type="text" Readonly id="pproductPrice'.$values["product_id"].'" name="productPrice" value="'.$values["product_price"].'" onkeyup="calculateTotal('.$values["product_id"].')"  onblur="updateSession('.$values["product_id"].')" style="width: 100%;text-align: center;"/></td>
									<td align="right"><input type="text" Readonly id="pproductDiscount'.$values["product_id"].'" name="productDiscount" value="'.$discount_amount.'" onkeyup="calculateTotal('.$values["product_id"].')"  onblur="updateSession('.$values["product_id"].')" style="width: 100%;text-align: center;" /></td>';
									//$productTotal = $values["product_quantity"] * $values["max_price"];
									$productTotal = $discount_pc * $values["product_price"];
									$productDiscount = $productTotal;
									$productTotal = $productTotal - $productDiscount;
									$dataString .= '
										<td align="right"><span id="pproductTotal'.$values['product_id'].'"> '.sprintf("%.2f", $productTotal).'</span></td>
										<td>'.$row_discountOffer['offer_name'].'</td>
									</tr>';
									$total_price = $total_price + $productTotal;
									$total_item = $total_item + 1;
									$totalProductDiscount = $totalProductDiscount + $productDiscount;
								}
							}else{
								$dataString = '<tr><td colspan="7"><span style="color:red; font-weight:bold;">Product:'.$values["product_name"].' limit is over</span></td></tr>';
								$stock_check = 'Off';
							}
							
							$total_quantity = $total_quantity - ($rest_pc);
						}
					
						if($stock_check == 'On'){
						    
							if($row_discountOffer['discount_unit'] == '%'){
								if($total_quantity >= $row_discountOffer['offer_for']){
									$discount_quantity = $row_discountOffer['offer_for'] * floor($total_quantity / $row_discountOffer['offer_for']);
									$productTotal = $discount_quantity * $values["product_price"];
									$productDiscount = $productTotal * ($row_discountOffer['discount']/100);
									
									/*if($row_discountOffer['discount_unit_2'] == 'TK'){
										$productDiscount += ($row_discountOffer['discount_2']*($discount_quantity / $row_discountOffer['offer_for']));
									    	
									}*/
									//$productDiscount = 0; //$row_discountOffer['discount'].'%';
									$dataString .= '
										<tr>
										<td>'.$values["product_name"].'<input type="hidden" id="pproductId'.$values["product_id"].'" name="productId" value="'.$values["product_id"].'"/></td>
										<td><input type="text" Readonly id="pavailableQuantity'.$values["product_id"].'" name="availableQuantity" value="'.$values["product_limit"].'" style="width: 100%;text-align: center;" Readonly/></td>
										<td><input type="text" Readonly id="pproductQuantity'.$values["product_id"].'" name="productQuantity" value="'.$discount_quantity.'" onkeyup="calculateTotal('.$values["product_id"].')" onblur="updateSession('.$values["product_id"].')" style="width: 100%;text-align: center;"/></td>
										<td align="right"><input type="text" Readonly id="pproductPrice'.$values["product_id"].'" name="productPrice" value="'.$values["product_price"].'" onkeyup="calculateTotal('.$values["product_id"].')"  onblur="updateSession('.$values["product_id"].')" style="width: 100%;text-align: center;"/></td>';
									$dataString .= '<td align="right"><input type="text" Readonly id="pproductDiscount'.$values["product_id"].'" name="productDiscount" value="'.$productDiscount.'" onkeyup="calculateTotal('.$values["product_id"].')"  onblur="updateSession('.$values["product_id"].')" style="width: 100%;text-align: center;" /></td>';
									
									$productTotal = $productTotal - $productDiscount;
									$dataString .= '
										<td align="right"><span id="pproductTotal'.$values['product_id'].'"> '.sprintf("%.2f", $productTotal).'</span></td>
										<td>'.$row_discountOffer['offer_name'].'</td>
										</tr>';
									$total_price = $total_price + $productTotal;
									$total_item = $total_item + 1;
									$totalProductDiscount = $totalProductDiscount + $productDiscount;
									$total_quantity = $total_quantity - $discount_quantity;
									/*if($row_discountOffer['discount_unit_2'] == 'PC'){
										$discount_pc = $row_discountOffer['discount_2'] * floor(($total_quantity + $discount_quantity) / $row_discountOffer['offer_for']);
										
										if($discount_pc > 0){
											$discount_amount = '100%';
											$dataString .= '
											<tr>
											<td>'.$values["product_name"].'<input type="hidden" id="pproductId'.$values["product_id"].'" name="productId" value="'.$values["product_id"].'"/></td>
											<td><input type="text" Readonly id="pavailableQuantity'.$values["product_id"].'" name="availableQuantity" value="'.$values["product_limit"].'" style="width: 100%;text-align: center;" Readonly/></td>
											<td><input type="text" Readonly id="pproductQuantity'.$values["product_id"].'" name="productQuantity" value="'.$discount_pc.'" style="width: 100%;text-align: center;"/></td>
											<td align="right"><input type="text" Readonly id="pproductPrice'.$values["product_id"].'" name="productPrice" value="'.$values["max_price"].'" style="width: 100%;text-align: center;"/></td>
											<td align="right"><input type="text" Readonly id="pproductDiscount'.$values["product_id"].'" name="productDiscount" value="'.$discount_amount.'" style="width: 100%;text-align: center;" /></td>';
											//$productTotal = $values["product_quantity"] * $values["max_price"];
											$productTotal = $discount_pc * $values["max_price"];
											$productDiscount = $productTotal;
											$productTotal = $productTotal - $productDiscount;
											$dataString .= '
												<td align="right"><span id="pproductTotal'.$values['product_id'].'"> '.sprintf("%.2f", $productTotal).'</span></td>
												<td>'.$row_discountOffer['offer_name'].'</td>
											</tr>';
											$total_price = $total_price + $productTotal;
											$total_item = $total_item + 1;
											$totalProductDiscount = $totalProductDiscount + $productDiscount;
										}
										
									}*/
									
								}
							}
							if($row_discountOffer['discount_unit'] == 'TK'){
								if($total_quantity >= $row_discountOffer['offer_for']){
									$discount_quantity = $row_discountOffer['offer_for'] * floor($total_quantity / $row_discountOffer['offer_for']);
									$productDiscount = $row_discountOffer['discount']* floor($total_quantity / $row_discountOffer['offer_for']);
									$dataString .= '
										<tr>
										<td>'.$values["product_name"].'<input type="hidden" id="pproductId'.$values["product_id"].'" name="productId" value="'.$values["product_id"].'"/></td>
										<td><input type="text" Readonly id="pavailableQuantity'.$values["product_id"].'" name="availableQuantity" value="'.$values["product_limit"].'" style="width: 100%;text-align: center;" Readonly/></td>
										<td><input type="text" Readonly id="pproductQuantity'.$values["product_id"].'" name="productQuantity" value="'.$discount_quantity.'" onkeyup="calculateTotal('.$values["product_id"].')" onblur="updateSession('.$values["product_id"].')" style="width: 100%;text-align: center;"/></td>
										<td align="right"><input type="text" Readonly id="pproductPrice'.$values["product_id"].'" name="productPrice" value="'.$values["product_price"].'" onkeyup="calculateTotal('.$values["product_id"].')"  onblur="updateSession('.$values["product_id"].')" style="width: 100%;text-align: center;"/></td>
										<td align="right"><input type="text" Readonly id="pproductDiscount'.$values["product_id"].'" name="productDiscount" value="'.$productDiscount.'" onkeyup="calculateTotal('.$values["product_id"].')"  onblur="updateSession('.$values["product_id"].')" style="width: 100%;text-align: center;" /></td>';
									//echo ($values["max_price"]*$discount_quantity).' - '.$productDiscount;
									$productTotal = ($discount_quantity * $values["product_price"]) - $productDiscount;
									//$productTotal = $productTotal - $productDiscount;
									$dataString .= '
										<td align="right"><span id="pproductTotal'.$values['product_id'].'"> '.sprintf("%.2f", $productTotal).'</span></td>
										<td>'.$row_discountOffer['offer_name'].'</td>
										</tr>';
									$total_price = $total_price + $productTotal;
									$total_item = $total_item + 1;
									$totalProductDiscount = $totalProductDiscount + ($productDiscount);
									$total_quantity = $total_quantity - $discount_quantity;
									
									/*if($row_discountOffer['discount_unit_2'] == 'PC'){
										$discount_pc = $row_discountOffer['discount_2'];
										
										if($discount_pc > 0){
											$discount_amount = '100%';
											$dataString .= '
											<tr>
											<td>'.$values["product_name"].'<input type="hidden" id="pproductId'.$values["product_id"].'" name="productId" value="'.$values["product_id"].'"/></td>
											<td><input type="text" Readonly id="pavailableQuantity'.$values["product_id"].'" name="availableQuantity" value="'.$values["product_limit"].'" style="width: 100%;text-align: center;" Readonly/></td>
											<td><input type="text" Readonly id="pproductQuantity'.$values["product_id"].'" name="productQuantity" value="'.$discount_pc.'" style="width: 100%;text-align: center;"/></td>
											<td align="right"><input type="text" Readonly id="pproductPrice'.$values["product_id"].'" name="productPrice" value="'.$values["max_price"].'" style="width: 100%;text-align: center;"/></td>
											<td align="right"><input type="text" Readonly id="pproductDiscount'.$values["product_id"].'" name="productDiscount" value="'.$discount_amount.'" style="width: 100%;text-align: center;" /></td>';
										
											$productTotal = $discount_pc * $values["max_price"];
											$productDiscount = $productTotal;
											$productTotal = $productTotal - $productDiscount;
											$dataString .= '
												<td align="right"><span id="pproductTotal'.$values['product_id'].'"> '.sprintf("%.2f", $productTotal).'</span></td>
												<td>'.$row_discountOffer['offer_name'].'</td>
											</tr>';
											$total_price = $total_price + $productTotal;
											$total_item = $total_item + 1;
											$totalProductDiscount = $totalProductDiscount + $productDiscount;
										}
										
										
										
									}*/
									
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
        		$dataString .= '
                	<tr>
        			<td>'.$values["product_name"].'<input type="hidden" id="pproductId'.$values["product_id"].'" name="productId" value="'.$values["product_id"].'"/></td>
        			<td><input type="text" Readonly id="pavailableQuantity'.$values["product_id"].'" name="availableQuantity" value="'.$values["product_limit"].'" style="width: 100%;text-align: center;" Readonly/></td>
        			<td><input type="text" Readonly id="pproductQuantity'.$values["product_id"].'" name="productQuantity" value="'.$total_quantity.'" onkeyup="calculateTotal('.$values["product_id"].')" onblur="updateSession('.$values["product_id"].')" style="width: 100%;text-align: center;"/></td>
        			<td align="right"><input type="text" Readonly id="pproductPrice'.$values["product_id"].'" name="productPrice" value="'.$values["product_price"].'" onkeyup="calculateTotal('.$values["product_id"].')"  onblur="updateSession('.$values["product_id"].')" style="width: 100%;text-align: center;"/></td>
        			<td align="right"><input type="text" Readonly id="pproductDiscount'.$values["product_id"].'" name="productDiscount" value="'.$values["product_discount"].'" onkeyup="calculateTotal('.$values["product_id"].')"  onblur="updateSession('.$values["product_id"].')" style="width: 100%;text-align: center;" /></td>';
    	        //$productTotal = $values["product_quantity"] * $values["max_price"];
    	        $productTotal = $total_quantity * $values["product_price"];
        	    if($values["product_discount"] != ""){
        		    $lastValue = substr($values["product_discount"], -1);
        		    if($lastValue == "%"){
        		        $productDiscount = $productTotal * (substr($values["product_discount"], 0, -1)/100);
        		    }else{
        		        $productDiscount = $values["product_discount"];
        		    }
        		    $productTotal = $productTotal - $productDiscount;
        	    }
        		$dataString .= '
        		    <td align="right"><span id="pproductTotal'.$values['product_id'].'"> '.sprintf("%.2f", $productTotal).'</span></td>
        		</tr>';
        		$total_price = $total_price + $productTotal;
        		$total_item = $total_item + 1;
                $totalProductDiscount = $totalProductDiscount + $productDiscount;
    	    }
    	}
    	$dataString .= '
    	<tr>  
            <td colspan="5" align="right">Total
            <br>Product Discount</td>  
            <td align="right"><span class="totalAmount">'.sprintf("%.2f", $total_price).'</span>
            <br><span class="totalProductDiscount" style="width: 100%;text-align: center;">'.number_format($totalProductDiscount,2).'</span></td>  
              
        </tr>
    	<tr>  
            <td colspan="5" align="right">Sales Discount
            <br>Total Discount</td>
            <td align="right"><input type="text" id="psalesDiscount" style="width:100%;text-align: right;" onkeyup="calculateTotalDiscount()" value="0"/>
            <br><span class="totalDiscount" style="width: 100%;text-align: center;">'.number_format($totalProductDiscount,2).'</span></td>  
              
        </tr>
        <tr>
            <td colspan="5" align="right">Carring Cost</td>
            <td align="right"><input type="text" id="pcarringCost" name="carringCost" style="width:100%;text-align: right;"  onkeyup="calculateTotalDiscount()" value="0" /></td>  
            
        </tr>
        <tr>
            <td colspan="5" align="right">VAT</td>
            <td align="right"><input type="text" id="pvat" onkeyup="calculateTotalDiscount()" name="vat" style="width:100%;text-align: right;" autocomplete="off" value="0"/></td>  
            
        </tr>
        <tr>
            <td colspan="5" align="right">AIT</td>
            <td align="right"><input type="text" id="pait" name="ait" onkeyup="calculateTotalDiscount()" style="width:100%;text-align: right;" autocomplete="off" value="0" /></td>  
            
        </tr>
        <tr>
            <td colspan="5" align="right">Grand Total</td>
            <td align="right"><span class="pgrandTotal" style="width: 100%;">'.sprintf("%.2f", $total_price).'</span></td>  
            
        </tr>
        <tr>
            <td colspan="5" align="right">Payment Method</td>
            <td align="right"><select id="ppaymentMethod" name="paymentMethod">
                <option value="CASH" selected>Cash</option>
            </select></td>  
            
        </tr>
        <tr>
            <td colspan="5" align="right">Cash Amount</td>
            <td align="right"><input type="text" id="ppaid" name="paid" style="width:100%;text-align: right;" autocomplete="off" value="0"/></td>  
            
        </tr>
        </table>
    	';
    }
   }
    else
    {
    	$dataString .= 'Your Cart is Empty!';
    }
    echo $dataString;      
   }
   else if ($_POST['action'] == 'discountOfferOrderPreview'){
       $output = '<table class="table table-bordered">
	            <tr style="text-align: center;">
	                <td colspan="6"><img src="images/companylogo/Jafree.jpg"/></td>
	             </tr>
	            <tr style="text-align: center;">
	                   <td colspan="6"> 212, Jubilee Road, Chittagong-4000, Bangladesh.Tel:031-617505,615062 Mobile: 01973105100,01711-325119<br>E-mail:info@jafreetraders.com</td>
	           </tr>
	       </table>
	       <style>
	           .shoaib{font-size: 10px;}
	           
	       </style>
	       <table class="table table-bordered">';
       if(!empty($_SESSION["salesManShopping_cart"]))
        {
            $output .= '
        		<tr>
        		    <th>SL</th>
        			<th>Product Info</th>
        			<th>Quantity</th>
        			<th>Offer Quantity</th>
        			<th>Total Quantity</th>
        			<th>Unit Price</th>
        			<th>Unit Discount</th>
        			<th>Total Price</th>
        			<th>Discount Price</th>
        			<th>Grand Total</th>
        		</tr>';
    		$i=1;
            //$output = array('data' => array()); 
        	foreach($_SESSION["salesManShopping_cart"] as $keys => $values)
        	{
    	        $productDiscount = 0;
    	        $productIdEntry = $values["product_id"];
    	        //Offer Start
                $productDiscountEntry = $values["product_discount"];
                $productQuantityEntry = $values["product_quantity"];
                $productPriceEntry = $values["product_price"];
                $productLimit = $values["product_limit"];
                //$productTotal = $productQuantityEntry * $productPriceEntry;
                $sql_discountOffer = "SELECT id,offer_applicable,offer_for,unit_for,discount,discount_unit,discount_2,discount_unit_2, offer_name
                                    FROM tbl_discount_offer 
    								WHERE '$toDate' BETWEEN date_from AND date_to AND tbl_discount_offer.status='Active' AND deleted = 'No' AND tbl_products_id = '".$productIdEntry."' AND offer_applicable='Party' AND priority > 0
                                    ORDER BY priority DESC, offer_for DESC";
                                    
                $result_discountOffer = $conn->query($sql_discountOffer);
                $discount_pc = 0;
                $total_discount_pc = 0;
                $discount_amount = 0;
                $rest_pc = 0;
                $rest_amount = 0;
                $test = 0;
                $stock_check = 'On';
                $discountOfferValue = 0;
                //$discountOfferValueOutput = "";
                $total_quantity = $productQuantityEntry;
                if($result_discountOffer->num_rows > 0 ){
                    while($row_discountOffer = $result_discountOffer->fetch_assoc()){
                        $discountOfferid = $row_discountOffer['id'];
                        if($row_discountOffer['unit_for'] == 'PC'){
    				        if($row_discountOffer['discount_unit'] == 'PC'){
    						    $discount_pc = floor($total_quantity / $row_discountOffer['offer_for']);
    						    if($discount_pc >= 1){
        						    if($row_discountOffer['discount_unit_2'] == 'TK'){
            						    if($row_discountOffer['discount_2'] != 0){
            							    $discountOfferValue += ($row_discountOffer['discount_2']*$discount_pc);
            							    //$discountOfferValueOutput .= $discountOfferValue.' 1-1 ';
        						        }else{
            						        $discountOfferValue += $productDiscountEntry;
            						        //$discountOfferValueOutput .= $discountOfferValue.' 1-2 ';
            						    }
        						    }
        						    /*else{
        						        if($values["product_discount"] != ""){
                            		        $lastValue = substr($values["product_discount"], -1);
                                		    if($lastValue == "%"){
                                		        $productDiscount = $productPriceEntry * (substr($values["product_discount"], 0, -1)/100);
                                		    }else{
                                		        $productDiscount = $values["product_discount"];
                                		    }
                                		    $productTotal = $productTotal - $productDiscount;
                                		    //$discountOfferValue += $productDiscount;
                                        }
            						}*/
    							
    							    $discount_pc = $discount_pc * $row_discountOffer['discount'];
    							    $total_discount_pc += $discount_pc;
							    }
							    $total_quantity -=$row_discountOffer['offer_for'] * floor($total_quantity / $row_discountOffer['offer_for']);
						    }
        					else if($row_discountOffer['discount_unit'] == '%'){
        						if($total_quantity >= $row_discountOffer['offer_for']){
        							$discount_quantity = $row_discountOffer['offer_for'] * floor($total_quantity / $row_discountOffer['offer_for']);
        							$productTotal = $discount_quantity * $productPriceEntry;
        							$discountOfferValue += $productTotal * ($row_discountOffer['discount']/100);
        							//$discountOfferValueOutput .= $discountOfferValue.' 1-4 ';
        							$total_quantity -=$row_discountOffer['offer_for'] * floor($total_quantity / $row_discountOffer['offer_for']);
        						}
        					}
    						else if($row_discountOffer['discount_unit'] == 'TK'){
    							if($total_quantity >= $row_discountOffer['offer_for']){
    								$discount_quantity = $row_discountOffer['offer_for'] * floor($total_quantity / $row_discountOffer['offer_for']);
    								$discountOfferValue += $row_discountOffer['discount']* floor($total_quantity / $row_discountOffer['offer_for']);
    								$total_quantity -=$row_discountOffer['offer_for'] * floor($total_quantity / $row_discountOffer['offer_for']);
                                }
    						}
                        }
                    }
                } else{
                    $max_price = $values["max_price"];
        			$min_price = $values["min_price"];
        			$discount_percent = (($max_price-$min_price)/$max_price)*100;
        			$totalPriceOutput = $productQuantityEntry*$productPriceEntry;
        			//$discountOfferValue += $total_discount_pc*$productPriceEntry;
        			$lastValue = substr($values["product_discount"], -1);
        		    if($lastValue == "%"){
        		        $productDiscount = $productPriceEntry * (substr($values["product_discount"], 0, -1)/100);
        		    }else{
        		        $productDiscount = $values["product_discount"];
        		    }
        		    $discountOfferValue += ($productQuantityEntry*$productDiscount);
        			
        			$total_quantity = 0;
                }
           
                if($total_quantity > 0){
                    if($values["product_discount"] != ""){
        		        $lastValue = substr($values["product_discount"], -1);
            		    if($lastValue == "%"){
            		        $productDiscount = $productPriceEntry * (substr($values["product_discount"], 0, -1)/100);
            		    }else{
            		        $productDiscount = $values["product_discount"];
            		    }
            		    $discountOfferValue += ($total_quantity*$productDiscount);
            		    $totalPriceOutput = ($productQuantityEntry+$total_discount_pc)*$productPriceEntry;
            		    //$productTotal = $productTotal - $productDiscount;
            		    //$discountOfferValue += $productDiscount;
                    }
                }
        		$totalAfterDiscount = $totalPriceOutput - $discountOfferValue;	
            		$output .= '
            		<tr>
            			<td>'.$i++.'</td>
            			<td>'.str_replace('','"',str_replace("~","'",$values["product_name"])).'</td>
            			<td>'.$productQuantityEntry.'</td>
            			<td>'.$total_discount_pc.'</td>
            			<td>'.($productQuantityEntry+$total_discount_pc).'</td>
            			<td>'.$productPriceEntry.'</td>
            			<td>'.$values["product_discount"].'</td>
            			<td>'.$totalPriceOutput.'</td>
            			<td>'.$discountOfferValue.'</td>
            			<td>'.$totalAfterDiscount.'</td>
            		</tr>';
    	    }
            echo $output.'</table>';
        }else{
           echo json_encode("Cart is empty");
       }
   }
   else if($_POST['action'] == 'discountOfferConfirmPreview'){
       $confirmOrderDate = $_POST['confirmOrderDate'];  
        $isChange = $_POST['isChange'];  
        $detailsId = $_POST['detailsId'];  
        $checkChangeQTY = $_POST['checkChangeQTY'];  
        $accountNo = $_POST['accountNo'];
        $bankRferenceNumber = $_POST['bankRferenceNumber'];
        $advanceAmount = $_POST['advanceAmount'];
        $transportName = $_POST['transportName'];
        $orderId = $_POST['orderId'];
        $orderNo = $_POST['orderCode'];
        $ordersId = $orderId;
        $detailsIdArray = explode(",",$detailsId);
        $isChangeArray = explode(",",$isChange);
        $availableQTYArray = explode(",",$checkChangeQTY);
        $output = '<table class="table table-bordered">
	            <tr style="text-align: center;">
	                <td colspan="6"><img src="images/companylogo/Jafree.jpg"/></td>
	             </tr>
	            <tr style="text-align: center;">
	                   <td colspan="6"> 212, Jubilee Road, Chittagong-4000, Bangladesh.Tel:031-617505,615062 Mobile: 01973105100,01711-325119<br>E-mail:info@jafreetraders.com</td>
	           </tr>
	       </table>
	       <style>
	           .shoaib{font-size: 10px;}
	           
	       </style>
	       <table class="table table-bordered">';
	       $output .= '
        		<tr>
        		    <th>SL</th>
        			<th>Product Info</th>
        			<th>Quantity</th>
        			<th>Offer Quantity</th>
        			<th>Total Quantity</th>
        			<th>Unit Price</th>
        			<th>Unit Discount</th>
        			<th>Total Price</th>
        			<th>Discount Price</th>
        			<th>Grand Total</th>
        		</tr>';
        		$sl=1;
        for($i = 0; $i < count($detailsIdArray); $i++) {
            $total_quantity = $availableQTYArray[$i];
            if($detailsIdArray[$i] != "" && $detailsIdArray[$i] != "0" && $total_quantity > 0){
                /*Offer Back Calculation Start*/
               
                
                $sql_discountOffer = "SELECT tbl_discount_offer.id,offer_applicable,offer_for,unit_for,tbl_discount_offer.discount,discount_unit,discount_2,discount_unit_2, offer_name, tbl_order_details.remarks, tbl_order_details.salesAmount, tbl_order_details.checked_amount, tbl_products.productName, tbl_products.productCode
                                        FROM tbl_discount_offer 
                                        LEFT OUTER JOIN tbl_order_details ON tbl_order_details.tbl_products_id = tbl_discount_offer.tbl_products_id
                                        LEFT OUTER JOIN tbl_products ON tbl_discount_offer.tbl_products_id = tbl_products.id
        								WHERE '$toDate' BETWEEN date_from AND date_to AND tbl_discount_offer.status='Active' AND tbl_discount_offer.deleted = 'No' AND tbl_order_details.id = '".$detailsIdArray[$i]."' AND offer_applicable='Party' AND priority > 0
                                        ORDER BY priority DESC, offer_for DESC";
                 //echo json_encode($sql_discountOffer.'+');                       
                $result_discountOffer = $conn->query($sql_discountOffer);
                $discount_pc = 0;
                $total_discount_pc = 0;
                $discountOfferValue = 0;
                $discount_amount = 0;
                $rest_pc = 0;
                    $rest_amount = 0;
                    $test = 0;
                    $stock_check = 'On';
                    $remarks = '';
                    $salesAmount = 0;
                if($result_discountOffer->num_rows > 0 ){
                    
                    while($row_discountOffer = $result_discountOffer->fetch_assoc()){
                        $productInfo =  $row_discountOffer['productName'].' - '. $row_discountOffer['productCode'];;
                        $discountOfferid = $row_discountOffer['id'];
                        $remarks = $row_discountOffer['remarks'];
                        $salesAmount = $row_discountOffer['checked_amount'];
                        if($row_discountOffer['unit_for'] == 'PC'){
    				        if($row_discountOffer['discount_unit'] == 'PC'){
    				            while($total_quantity >= $row_discountOffer['offer_for']){
    				                if(($row_discountOffer['offer_for'] + $row_discountOffer['discount']) <= $total_quantity){
    				                    $total_quantity -= ($row_discountOffer['offer_for'] + $row_discountOffer['discount']);
    				                    $total_discount_pc += $row_discountOffer['discount'];
    				                    $discountOfferValue += ($row_discountOffer['discount']*$salesAmount);
    				                    if(substr($row_discountOffer['remarks'], -1) == '%'){
                    				        $discountOfferValue += $row_discountOffer['offer_for']*($salesAmount*(substr($row_discountOffer['remarks'],0,-1)/100));
                    				    }else{
                    				        $discountOfferValue += $row_discountOffer['offer_for']*$row_discountOffer['remarks'];
                    				    }
                    				    if($row_discountOffer['discount_unit_2'] == 'TK'){
                						    if($row_discountOffer['discount_2'] != 0){
                							    $discountOfferValue += $row_discountOffer['discount_2'];
                						    }
                						}
    				                }else{
    				                    $total_quantity -= $row_discountOffer['offer_for'];
				                        $total_discount_pc += $total_quantity;
				                        $discountOfferValue += ($total_quantity*$salesAmount);
    				                    if(substr($row_discountOffer['remarks'], -1) == '%'){
                    				        $discountOfferValue += $row_discountOffer['offer_for']*($salesAmount*(substr($row_discountOffer['remarks'],0,-1)/100));
                    				    }else{
                    				        $discountOfferValue += $row_discountOffer['offer_for']*$row_discountOffer['remarks'];
                    				    }
				                        $total_quantity = 0;
    				                }
    				            }
    						}
        					else if($row_discountOffer['discount_unit'] == '%'){
        						if($total_quantity >= $row_discountOffer['offer_for']){
        							$discount_quantity = $row_discountOffer['offer_for'] * floor($total_quantity / $row_discountOffer['offer_for']);
        							$productTotal = $discount_quantity * $productPriceEntry;
        							$discountOfferValue += $productTotal * ($row_discountOffer['discount']/100);
        							//$discountOfferValueOutput .= $discountOfferValue.' 1-4 ';
        							$total_quantity -=$row_discountOffer['offer_for'] * floor($total_quantity / $row_discountOffer['offer_for']);
        						}
        					}
    						else if($row_discountOffer['discount_unit'] == 'TK'){
    							if($total_quantity >= $row_discountOffer['offer_for']){
    								$discount_quantity = $row_discountOffer['offer_for'] * floor($total_quantity / $row_discountOffer['offer_for']);
    								$discountOfferValue += $row_discountOffer['discount']* floor($total_quantity / $row_discountOffer['offer_for']);
    								//$discountOfferValueOutput .= $discountOfferValue.' 1-5 ';
    								//$productTotal = ($discount_quantity * $productPriceEntry) - $productDiscount;
    								$total_quantity -=$row_discountOffer['offer_for'] * floor($total_quantity / $row_discountOffer['offer_for']);
                                }
    						}
                        }
                    }
                    if($total_quantity > 0){
                        if(substr($remarks, -1) == '%'){
        			        $discountOfferValue += $total_quantity*($salesAmount*(substr($remarks,0,-1)/100));
        			    }else{
        			        $discountOfferValue += $total_quantity*$remarks;
        			    }
                        //$discountOfferValue += $total_quantity*($row['salesAmount'] * $row['remarks']);
                        //$discountOfferValueOutput .= $discountOfferValue.' 1-6 ';
                    }
                    $grandTotal = ($availableQTYArray[$i]*$salesAmount)-$discountOfferValue;
                        /*Offer Back Calculation End*/
                        $output .= '
            		<tr>
            			<td>'.$sl++.'</td>
            			<td>'.$productInfo.'</td>
            			<td>'.($availableQTYArray[$i]-$total_discount_pc).'</td>
            			<td>'.$total_discount_pc.'</td>
            			<td>'.$availableQTYArray[$i].'</td>
            			<td>'.$salesAmount.'</td>
            			<td>'.$remarks.'</td>
            			<td>'.$availableQTYArray[$i]*$salesAmount.'</td>
            			<td>'.$discountOfferValue.'</td>
            			<td>'.$grandTotal.'</td>
            		</tr>';
                        /*$sql = "UPDATE tbl_order_details 
                                SET status = 'Processing', checked_quantity='".$availableQTYArray[$i]."',checked_total_amount=$availableQTYArray[$i]*checked_amount, offer_quantity='".$total_discount_pc."', offer_discount_amount='".$discountOfferValue."', total_after_discount='".$grandTotal."' 
                                WHERE id='".$detailsIdArray[$i]."'";
                        $conn->query($sql);*/
                        $lastDetailsId = $detailsIdArray[$i];
                        if($updatedAmountArray[$i] != '0'){
                            $countNoOfOrder++;
                        }
                }else{
                     $sql_productDetails = "SELECT tbl_products.productName, tbl_products.productCode, tbl_order_details.remarks, tbl_order_details.checked_amount, checked_quantity,checked_total_amount, offer_quantity,discount, quantity, offer_discount_amount, total_after_discount 
                                        FROM tbl_order_details 
                                        INNER JOIN tbl_products ON tbl_order_details.tbl_products_id = tbl_products.id AND tbl_products.deleted='No'
                                        WHERE tbl_order_details.id = '$detailsIdArray[$i]' AND tbl_order_details.deleted='No'";
                    $result_productDetails = $conn->query($sql_productDetails);
                    while($row_productDetails = $result_productDetails->fetch_assoc()){
                        $productInfo = $row_productDetails['productName'].' - '.$row_productDetails['productCode'];
                        $salesAmount = $row_productDetails['checked_amount'];
                        $remarks = $row_productDetails['remarks'];
                        $discountOfferValue += ($row_productDetails['discount']/$row_productDetails['quantity'])*$availableQTYArray[$i];
                    }
                    $grandTotal = ($availableQTYArray[$i]*$salesAmount)-$discountOfferValue;
                    $output .= '
            		<tr>
            			<td>'.$sl++.'</td>
            			<td>'.$productInfo.'</td>
            			<td>'.($availableQTYArray[$i]-$total_discount_pc).'</td>
            			<td>'.$total_discount_pc.'</td>
            			<td>'.$availableQTYArray[$i].'</td>
            			<td>'.$salesAmount.'</td>
            			<td>'.$remarks.'</td>
            			<td>'.$availableQTYArray[$i]*$salesAmount.'</td>
            			<td>'.$discountOfferValue.'</td>
            			<td>'.$grandTotal.'</td>
            		</tr>';
                    /*$sql = "UPDATE tbl_order_details 
                                SET status = 'Processing', checked_quantity='".$availableQTYArray[$i]."',checked_total_amount=$availableQTYArray[$i]*salesAmount, offer_quantity='".$total_discount_pc."', offer_discount_amount=(discount/quantity)*$availableQTYArray[$i], total_after_discount=($availableQTYArray[$i]*salesAmount)-((discount/quantity)*$availableQTYArray[$i]) 
                                WHERE id='".$detailsIdArray[$i]."'";
                    $conn->query($sql);*/
                    $lastDetailsId = $detailsIdArray[$i];
                    if($updatedAmountArray[$i] != '0'){
                        $countNoOfOrder++;
                    }
                }
                
                
                
            }
        }
        /*$sql = "UPDATE tbl_orders 
                SET tbl_bank_id='$accountNo', bank_reference='$bankRferenceNumber', paidAmount='$advanceAmount', tbl_transport_info='$transportName', confirm_date='$confirmOrderDate', status='Processing'
                WHERE id='$orderId'";
        $conn->query($sql);
        $countNoOfOrder = $conn->affected_rows;*/
        /*$sql = "INSERT INTO tbl_notification (notification_title, notification, created_by, created_time, notification_link, order_id, notify_for) 
                VALUES ('Confirmed order no# $orderNo','Order# $orderNo, Confirmed by ".$user['fname']." ".$user['lname']." With $countNoOfOrder Items','$loginID','$toDay','orderProcessList.php?page=1','$ordersId','confirmOrder')";
        $conn->query($sql);*/
        echo $output;
   }
}
else{
    $sql = "SELECT tbl_discount_offer.id, offer_name,offer_applicable, date_from, date_to,remainder_date, tbl_products_id, offer_for, unit_for, discount, discount_unit, priority, tbl_products.productName,      tbl_products.productCode, tbl_products.modelNo, discount_2, discount_unit_2,tbl_discount_offer.status 
                FROM tbl_discount_offer
                            LEFT OUTER JOIN tbl_products ON tbl_discount_offer.tbl_products_id = tbl_products.id AND tbl_products.deleted = 'No'
                            WHERE tbl_discount_offer.deleted = 'No' AND tbl_discount_offer.status='Active'
            ORDER BY tbl_discount_offer.id DESC";
    $result = $conn->query($sql);
    $i=1;
    $output = array('data' => array());
    while ($row = $result->fetch_array()) {
        $id = $row['id'];
        $statusId=$row['status'];
        if($row['status']=='Active'){
            $status='<b style="color: green;">Active</b>';
        }else{
            $status='<b style="color: red;">Inactive</b>';
        }
        
        $button = '	<div class="btn-group">
						<button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
						<i class="fa fa-gear tiny-icon"></i>  <span class="caret"></span></button>
						<ul class="dropdown-menu dropdown-menu-right" style="border: 1px solid gray;" role="menu">';
		if(strtolower($_SESSION['userType']) == "admin" || strtolower($_SESSION['userType']) == 'super admin'){
		$button .=  '<li><a href="#" onclick="statusUpdateOffer(\'' . $id . '\',\'' . $statusId . '\')"><i class="fa fa-edit tiny-icon"></i>Status Update</a></li>';
		$button .=  '<li><a href="#" onclick="deleteDiscountOffer('.$id.')"><i class="fa fa-edit tiny-icon"></i>Delete</a></li>';
		}
		$button .= '</ul></div>';
		if($row['discount_2'] != ""){
		    $discount2 = " and ".$row["discount_2"]." ".$row["discount_unit_2"];
		}else{
		    $discount2 = "";
		}
        $output['data'][] = array(
            $i++,
            $row['offer_name'].'<br><b style="color: green;">'.$row['offer_applicable'].'</b>',
            $row['productName'].'<br>'.$row['productCode'].'<br>Model: '.$row['modelNo'],
            'S:'.$row['date_from'].'<br>E:'.$row['date_to'].'<br>R:'.$row['remainder_date'],
            $row['offer_for'].' '.$row['unit_for'].' For '.$row['discount'].' '.$row['discount_unit'].$discount2,
            $status.'<br>'.$row['priority'],
            $button
        );
    } // /while 
    echo json_encode($output);
}


?>
