<?php
$conPrefix = '../';
include $conPrefix . 'includes/session.php';
date_default_timezone_set('Asia/Dhaka');
$toDay = (new DateTime())->format("Y-m-d H:i:s");

if(isset($_POST["action"])){
    if($_POST["action"] == "fetchCart"){
        $output = '
    <div class="table-responsive" id="order_table">
    	<table class="table table-bordered table-striped">
    		<tr>  
                <th style="width:42%;text-align: center;">Product Name</th>
                <th style="width:10%;text-align: center; display:none;">Available</th>
                <th style="width:10%;text-align: center;">Quantity</th>
                <th style="width:18%;text-align: center;">Price</th>  
                <th style="width:15%;text-align: center;">Unit Discount</th>  
                <th style="width:22%;text-align: center;">Total</th>  
                <th style="width:3%;text-align: center;">Action</th>  
            </tr>
    ';
    if(!empty($_SESSION["salesManShopping_cart"]))
    {
        $total_price=0;
		$total_item = 0;
        $totalProductDiscount = 0;
    	foreach($_SESSION["salesManShopping_cart"] as $keys => $values)
    	{
    	    $productDiscount = 0;
			//$max_price = $values["max_price"];
			//$min_price = $values["min_price"];
			//$discount_percent = (($max_price-$min_price)/$max_price)*100;
    		$output .= '
    		<tr>
    			<td>'.str_replace('`','"',str_replace("~","'",$values["product_name"])).'<input type="hidden" id="productId'.$values["product_id"].'" name="productId" value="'.$values["product_id"].'"/></td>
    			<td style="display:none;"><input type="text" id="availableQuantity'.$values["product_id"].'" name="availableQuantity" value="'.$values["product_limit"].'" style="width: 100%;text-align: center;" Readonly/></td>
    			<td><input type="text" id="productQuantity'.$values["product_id"].'" name="productQuantity" value="'.$values["product_quantity"].'" onkeyup="calculateTotal('.$values["product_id"].')" onblur="updateSession('.$values["product_id"].')" style="width: 100%;text-align: center;"/></td>
    			<td align="right">
    			    <input type="text" id="productPrice'.$values["product_id"].'" name="productPrice" value="'.$values["product_price"].'" onkeyup="calculateTotal('.$values["product_id"].')"  onblur="updateSession('.$values["product_id"].')" style="width: 100%;text-align: center;" readonly/>
    			    <input type="hidden" id="productMaxPrice'.$values["product_id"].'" name="productMaxPrice" value="'.$values["max_price"].'"/>
    			    <input type="hidden" id="productMinPrice'.$values["product_id"].'" name="productMinPrice" value="'.$values["min_price"].'"/>
			    </td>
    			<td align="right">
    			    <input type="text" id="productDiscount'.$values["product_id"].'" name="productDiscount" value="'.$values["product_discount"].'" onkeyup="calculateTotal('.$values["product_id"].')"  onblur="updateSession('.$values["product_id"].')" style="width: 100%;text-align: center;" readonly/>
			    </td>';
	        $productTotal = $values["product_quantity"] * $values["product_price"];
    	    if($values["product_discount"] != ""){
    		    $lastValue = substr($values["product_discount"], -1);
    		    if($lastValue == "%"){
    		        $productDiscount = $values["product_price"] * (substr($values["product_discount"], 0, -1)/100);
    		    }else{
    		        $productDiscount = $values["product_discount"];
    		    }
    		    $productDiscount = $productDiscount * $values["product_quantity"];
    		    $productTotal = $productTotal - $productDiscount;
    	    }
    		$output .= '
    		    <td align="right"><span id="productTotal'.$values['product_id'].'"> '.sprintf("%.2f", $productTotal).'</span></td>
    			<td>
    			    <div class="btn-group">
                    	<button type="button" class="btn btn-deafult dropdown-toggle" data-toggle="dropdown"style="border: 1px solid gray;">
                    	<i class="glyphicon glyphicon-option-horizontal" style="color: #000cbd;"></i></button>
                    	<ul class="dropdown-menu dropdown-menu-right" style="border: 1px solid gray;min-width: 100%;" role="menu">
                    		<li style="margin-left: 0px;"><a class="btn btn-secondary btn-xs delete" id="'. $values["product_id"].'" href="#"><span class="glyphicon glyphicon-trash" style="color: red;"></span></a></li>
    			            <li style="margin-left: 0px;"><a class="btn btn-xs previousPriceSingleOrder" id="-'. $values["product_id"].'" href="#"><span class="glyphicon glyphicon-th" style="color: #000cbd;"></span></a></li>
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
            <td colspan="4" align="right">Total
            <br>Product Discount</td>  
            <td align="right"><span class="totalAmount">'.sprintf("%.2f", $total_price).'</span>
            <br><span class="totalProductDiscount" style="width: 100%;text-align: center;">'.$totalProductDiscount.'</span></td>  
            <td></td>  
        </tr>
    	<tr>  
            <td colspan="4" align="right">Sales Discount
            <br>Total Discount</td>
            <td align="right"><input type="text" id="orderDiscount" style="width:100%;text-align: right;" onkeyup="calculateTotalDiscount()" value="0"/>
            <br><span class="totalDiscount" style="width: 100%;text-align: center;">'.$totalProductDiscount.'</span></td>  
            <td></td>  
        </tr>
        <tr style="display:none;">
            <td colspan="4" align="right">Carring Cost</td>
            <td align="right"><input type="text" id="carringCost" name="carringCost" style="width:100%;text-align: right;"  onkeyup="calculateTotalDiscount()" value="0" /></td>  
            <td></td>
        </tr>
        <tr style="display:none;">
            <td colspan="4" align="right">VAT</td>
            <td align="right"><input type="text" id="vat" onkeyup="calculateTotalDiscount()" name="vat" style="width:100%;text-align: right;" autocomplete="off" value="0"/></td>  
            <td></td>
        </tr>
        <tr style="display:none;">
            <td colspan="4" align="right">AIT</td>
            <td align="right"><input type="text" id="ait" name="ait" onkeyup="calculateTotalDiscount()" style="width:100%;text-align: right;" autocomplete="off" value="0" /></td>  
            <td></td>
        </tr>
        <tr>
            <td colspan="4" align="right">Grand Total</td>
            <td align="right"><span class="grandTotal" style="width: 100%;">'.sprintf("%.2f", $total_price).'</span></td>  
            <td></td>
        </tr>
        <tr>
            <td colspan="4" align="right">Payment Method</td>
            <td align="right"><select id="paymentMethod" name="paymentMethod">
                <option value="CASH" selected>Cash</option>
            </select></td>  
            <td></td>
        </tr>
        <tr style="display:none;">
            <td colspan="4" align="right">Cash Amount</td>
            <td align="right"><input type="text" id="paid" name="paid" style="width:100%;text-align: right;" autocomplete="off" value="0"/></td>  
            <td></td>
        </tr>
        
    	';
    }
    else
    {
    	$output .= '
        <tr>
        	<td colspan="4" align="center">
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
    else if($_POST["action"] == 'remove'){
		foreach($_SESSION["salesManShopping_cart"] as $keys => $values)
		{
			if($values["product_id"] == $_POST["product_id"])
			{
				unset($_SESSION["salesManShopping_cart"][$keys]);
			}
		}
	}
	else if($_POST["action"] == "adjust"){
		if(isset($_SESSION["salesManShopping_cart"])){
			foreach($_SESSION["salesManShopping_cart"] as $keys => $values){
				if($_SESSION["salesManShopping_cart"][$keys]['product_id'] == $_POST["product_id"]){
					$_SESSION["salesManShopping_cart"][$keys]['product_quantity'] =  $_POST["product_quantity"];
					$_SESSION["salesManShopping_cart"][$keys]['product_price'] =  $_POST["product_price"];
					$_SESSION["salesManShopping_cart"][$keys]['product_limit'] =  $_POST["product_limit"];
					$_SESSION["salesManShopping_cart"][$keys]['product_discount'] =  $_POST["product_discount"];
					break;
				}
			}
		}
	}
	else if($_POST["action"] == 'empty'){
		unset($_SESSION["salesManShopping_cart"]);
	}
	else if($_POST["action"] == 'check_out_cart'){
	    $error=0;
	    $loginID = $_SESSION['user'];
	    $salesMan=$_SESSION['user'];
	    $orderDate = $_POST['orderDate'];
	    $customerId= $_POST['customerId'];
	    $transportNameId= $_POST['transportName'];
    	$remarks= $_POST['remarks'];
    	$totalAmount=$_POST['totalAmount'];
	    $totalProductDiscount=$_POST['totalProductDiscount'];
    	$orderDiscount=$_POST['orderDiscount'];
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
        $productIdArray = explode("@!@,",$productId);
        $productQuantityArray = explode("@!@,",$productQuantity);
        $productPriceArray = explode("@!@,",$productPrice);
        $productDiscountArray = explode("@!@,",$productDiscount);
        $productTotalArray = explode("@!@,",$productTotal);
        
    	$salesOrderNo='';
    	try{
    	    $checkOverQuantity = 0;
    	    for($i = 0; $i < count($productIdArray); $i++) {
    	        $productIdEntry = $productIdArray[$i];
				$productQuantityEntry =$productQuantityArray[$i]; 
				if($i == count($productIdArray)-1){
					$productIdEntry = substr($productIdEntry, 0, strlen($productIdEntry)-3);
					$productQuantityEntry = substr($productQuantityEntry, 0,strlen($productQuantityEntry)-3);
				}
				if($productIdEntry != ''){
				    $sql = "SELECT SUM(currentStock) AS totalStock 
                            FROM tbl_currentStock
                            WHERE tbl_productsId='$productIdEntry' AND deleted='No'";
                    $result = $conn->query($sql);
                    while($row = $result->fetch_assoc()){
                        $totalStock = $row['totalStock'];
                        if(floatval($totalStock) < floatval($productQuantityEntry)){
                            $checkOverQuantity++;
                        }
                    }
				}
    	    }
    	    if ($checkOverQuantity == 0){
        		$conn->begin_transaction();
        		$sql = "SELECT LPAD(max(orderNo)+1, 6, 0) as orderCode from tbl_orders";
        		$query = $conn->query($sql);
        		while ($prow = $query->fetch_assoc()) {
        			$orderNo = $prow['orderCode'];
        		}
        		if($orderNo == ""){
        		    $orderNo = "000001";
        		}
            	$sql = "INSERT INTO tbl_orders(orderNo, orderDate, tbl_customerId, tbl_transport_info,tbl_userId, totalAmount, productDiscount, ordersDiscount, totalDiscount, grandTotal, vat, ait, createdBy,paymentType, remarks, tbl_wareHouseId, carringCost, requisitionNo, createdDate)
            	        VALUES ('$orderNo','$orderDate','$customerId','$transportNameId','$salesMan','$totalAmount','$totalProductDiscount','$orderDiscount','$totalDiscount','$grandTotal','$vat','$ait','$loginID','$paymentMethod','$remarks', '$wareHouse','$carringCost', '$requisitionNo', '$toDay')";
            	if($conn->query($sql)){
            	    $ordersId = $conn->insert_id;
            	    $countNoOfOrder = 0;
            	    for($i = 0; $i < count($productIdArray); $i++) {
    					$productIdEntry = $productIdArray[$i];
    					$productQuantityEntry =$productQuantityArray[$i]; 
    					$productPriceEntry = $productPriceArray[$i];
    					$productDiscountEntry =$productDiscountArray[$i]; 
    					$productTotalEntry = $productTotalArray[$i];
    					if($i == count($productIdArray)-1){
    						$productIdEntry = substr($productIdEntry, 0, strlen($productIdEntry)-3);
    						$productQuantityEntry = substr($productQuantityEntry, 0,strlen($productQuantityEntry)-3);
    						$productPriceEntry = substr($productPriceEntry, 0, strlen($productPriceEntry)-3);
    						$productDiscountEntry = substr($productDiscountEntry, 0,strlen($productDiscountEntry)-3);
    						$productTotalEntry = substr($productTotalEntry, 0, strlen($productTotalEntry)-3);
    					}
    					if($productIdEntry != ''){
    					    $total = $productQuantityEntry*$productPriceEntry;
    					    if(substr($productDiscountEntry, -1) == '%'){
    					        $discountAmount = $productPriceEntry*(substr($productDiscountEntry,0,-1)/100);
    					    }else{
    					        $discountAmount = $productDiscountEntry;
    					    }
    					    $checkedAmount = $productPriceEntry - $discountAmount;
    						$sql = "INSERT INTO tbl_order_details(tbl_orders_id, tbl_products_id, quantity, units, createdBy, salesAmount, totalAmount, discount, grandTotal, tbl_wareHouseId, remarks, createdDate, checked_amount)
    						        VALUES ('$ordersId','$productIdEntry','$productQuantityEntry','','$loginID','$productPriceEntry','$total',($discountAmount*$productQuantityEntry),'$productTotalEntry','$wareHouse','$productDiscountEntry', '$toDay', '$checkedAmount')";
    						if($conn->query($sql)){
    						    $conn->commit();
                            	unset($_SESSION["salesManShopping_cart"]);
    						}else{
    						    $error++;
                                echo json_encode($conn->error.$sql);	    
                                $conn->rollBack();		    
    						}
    					}
    					$countNoOfOrder = $i;
    				}
    				$sql = "INSERT INTO tbl_notification (notification_title, notification, created_by, created_time, notification_link, order_id, notify_for) 
                            VALUES ('Generate order no# $orderNo','Order# $orderNo, Prepared by ".$user['fname']." ".$user['lname']." With $countNoOfOrder Items','$loginID','$toDay','orderList.php?page=Pending','$ordersId','createOrder')";
                    $conn->query($sql);
    				$data = array( 
                        'msg'=>'Success', 
                        'salesId'=>$ordersId);
        			echo json_encode($data);
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
	// previousPriceSingle check
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
		$dataString .= "<tr><td> $productName</td><td> $brandName - $modelNo</td><td>Min: ? $minSalePrice <br>Max: ? $maxSalePrice</td> <td>$productSpeficiations</td></tr>";
        $dataString .= "</table>";
        
        echo $dataString;
	}
}
?>
