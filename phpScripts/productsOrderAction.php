<?php
$conPrefix = '../';
include $conPrefix . 'includes/session.php';
if(isset($_POST["action"])){
	if($_POST["action"] == "add"){
	    $totalStock = 0;
	    $productId = $_POST["product_id"];
	    $sql = "SELECT SUM(currentStock) AS totalStock 
                FROM tbl_currentStock
                WHERE tbl_productsId='$productId' AND deleted='No'";
        $result = $conn->query($sql);
        if($result){
            while($row = $result->fetch_assoc()){
                $totalStock = $row['totalStock'];
                if($totalStock == ''){
                    $totalStock = 0;
                }
            }
        }else{
            $totalStock = 0;	
        }
		if(isset($_SESSION["salesManShopping_cart"])){
			$is_available = 0;
			foreach($_SESSION["salesManShopping_cart"] as $keys => $values){
				if($_SESSION["salesManShopping_cart"][$keys]['product_id'] == $_POST["product_id"]){
					$is_available++;
					$_SESSION["salesManShopping_cart"][$keys]['product_quantity'] = $_SESSION["salesManShopping_cart"][$keys]['product_quantity'] + $_POST["product_quantity"];
					$lastValue = substr($_SESSION["salesManShopping_cart"][$keys]['product_discount'], -1);
        		    if($lastValue != "%"){
    					$_SESSION["salesManShopping_cart"][$keys]['product_discount'] = $_SESSION["salesManShopping_cart"][$keys]['product_discount'] + $_POST["product_discount"];
        		    }
				}
			}
			if($is_available == 0){
			    $discount_percent = (($_POST["max_price"]-$_POST["min_price"])/$_POST["max_price"])*100;
				$item_array = array(
					'product_id'               =>     $_POST["product_id"],  
					'product_name'             =>     $_POST["product_name"],  
					'product_price'            =>     $_POST["max_price"],
					'min_price'            	   =>     $_POST["min_price"],
					'max_price'                =>     $_POST["max_price"],					
					'product_quantity'         =>     $_POST["product_quantity"],
					'product_limit'            =>     $totalStock,
					'product_discount'         =>     $discount_percent.'%'
				);
				$_SESSION["salesManShopping_cart"][] = $item_array;
			}
		} else {
		    $discount_percent = (($_POST["max_price"]-$_POST["min_price"])/$_POST["max_price"])*100;
			$item_array = array(
				'product_id'               =>     $_POST["product_id"],  
				'product_name'             =>     $_POST["product_name"],  
				'product_price'            =>     $_POST["max_price"],  
				'min_price'            	   =>     $_POST["min_price"],
				'max_price'                =>     $_POST["max_price"],
				'product_quantity'         =>     $_POST["product_quantity"],
				'product_limit'            =>     $totalStock,
				'product_discount'         =>     $discount_percent.'%'
			);
			$_SESSION["salesManShopping_cart"][] = $item_array;
		}
	}
	else if ($_POST["action"] == 'fetchCartIcon'){
	    $item = 0;
	    if(!empty($_SESSION["salesManShopping_cart"]))
        {
            $item = count($_SESSION["salesManShopping_cart"]);
        }
        else{
            $item = 0;
        }
        echo $item;
	}
	else if($_POST['action'] == 'orderRegenerate'){
	    $orderId = $_POST['order_id'];
	    $sql = "SELECT tbl_order_details.tbl_products_id, tbl_order_details.quantity, tbl_order_details.discount, tbl_products.maxSalePrice, tbl_products.minSalePrice, tbl_products.productName
                FROM tbl_order_details
                INNER JOIN tbl_products ON tbl_order_details.tbl_products_id = tbl_products.id
                WHERE tbl_order_details.tbl_orders_id = '$orderId'";
        $resultProduct = $conn->query($sql);
        if($resultProduct){
            while($rowProduct = $resultProduct->fetch_assoc()){
                $totalStock = 0;
        	    $productId = $rowProduct["tbl_products_id"];
        	    
        	    $sql = "SELECT SUM(currentStock) AS totalStock 
                        FROM tbl_currentStock
                        WHERE tbl_productsId='$productId'";
                $result = $conn->query($sql);
                if($result){
                    while($row = $result->fetch_assoc()){
                        $totalStock = $row['totalStock'];
                        if($totalStock == ''){
                            $totalStock = 0;
                        }
                    }
                }else{
                    $totalStock = 0;	
                }
                
        		if(isset($_SESSION["salesManShopping_cart"])){
        			$is_available = 0;
        			foreach($_SESSION["salesManShopping_cart"] as $keys => $values){
        				if($_SESSION["salesManShopping_cart"][$keys]['product_id'] == $rowProduct["tbl_products_id"]){
        					$is_available++;
        					$_SESSION["salesManShopping_cart"][$keys]['product_quantity'] = $_SESSION["salesManShopping_cart"][$keys]['product_quantity'] + $rowProduct["quantity"];
        					$lastValue = substr($_SESSION["salesManShopping_cart"][$keys]['product_discount'], -1);
                		    if($lastValue != "%"){
            					$_SESSION["salesManShopping_cart"][$keys]['product_discount'] = $_SESSION["salesManShopping_cart"][$keys]['product_discount'] + $rowProduct["discount"];
                		    }
        				}
        			}
        			if($is_available == 0){
        			    $discount_percent = (($rowProduct["maxSalePrice"]-$rowProduct["minSalePrice"])/$rowProduct["maxSalePrice"])*100;
        				$item_array = array(
        					'product_id'               =>     $rowProduct["tbl_products_id"],  
        					'product_name'             =>     $rowProduct["productName"],  
        					'product_price'            =>     $rowProduct["maxSalePrice"],
        					'min_price'            	   =>     $rowProduct["minSalePrice"],
        					'max_price'                =>     $rowProduct["maxSalePrice"],					
        					'product_quantity'         =>     $rowProduct["quantity"],
        					'product_limit'            =>     $totalStock,
        					'product_discount'         =>     $discount_percent.'%'
        				);
        				$_SESSION["salesManShopping_cart"][] = $item_array;
        			}
        		} else {
        		    $discount_percent = (($rowProduct["maxSalePrice"]-$rowProduct["minSalePrice"])/$rowProduct["maxSalePrice"])*100;
        			$item_array = array(
        				'product_id'               =>     $rowProduct["tbl_products_id"],  
        				'product_name'             =>     $rowProduct["productName"],  
        				'product_price'            =>     $rowProduct["maxSalePrice"],  
        				'min_price'            	   =>     $rowProduct["minSalePrice"],
        				'max_price'                =>     $rowProduct["maxSalePrice"],
        				'product_quantity'         =>     $rowProduct["quantity"],
        				'product_limit'            =>     $totalStock,
        				'product_discount'         =>     $discount_percent.'%'
        			);
        			$_SESSION["salesManShopping_cart"][] = $item_array;
        		}
        		
            }
            echo "Success";
        }else{
            echo json_encode(0);	
        }
	}
}
else{
    /*$sql = "SELECT tbl_category.categoryName, tbl_products.productCode, tbl_products.productName, tbl_products.lotNumber, tbl_products.modelNo, tbl_units.unitName, tbl_products.id, tbl_products.`status`, tbl_brands.brandName, tbl_products.minSalePrice, 
            tbl_products.maxSalePrice, tbl_products.productImage, CONCAT('<ul>', GROUP_CONCAT(CONCAT('<li>',tbl_productspecification.specificationName, ': ', tbl_productspecification.specificationValue) SEPARATOR '</li>') ,'</li></ul>') AS productSpeficiations
        FROM tbl_products
        LEFT OUTER JOIN tbl_category ON tbl_products.categoryId = tbl_category.id AND tbl_category.deleted = 'No'
        LEFT OUTER JOIN tbl_brands ON tbl_products.tbl_brandsId = tbl_brands.id AND tbl_brands.deleted = 'No'
        LEFT OUTER JOIN tbl_units ON tbl_products.units = tbl_units.id AND tbl_units.deleted = 'No'
        LEFT OUTER JOIN tbl_productspecification ON tbl_productspecification.tbl_productsId = tbl_products.id AND tbl_productspecification.deleted = 'No'
        WHERE tbl_products.deleted = 'No'
        GROUP BY tbl_products.id
        ORDER BY id DESC";*/
    $brandId= '';
    if(isset($_GET['id'])){
        $brandId= $_GET['id'];    
    }else{
        $brandId = '0';
    }
    if($brandId != '0'){
        $sql_executive = " AND tbl_products.tbl_brandsId = '$brandId' ";    
    }else{
        $sql_executive = "";       
    }
    $sql = "SELECT * FROM
            (SELECT SUM(tbl_currentStock.currentStock) AS totalStock, dbt.* FROM
            (SELECT tbl_category.categoryName, tbl_products.productCode, tbl_products.productName, tbl_products.lotNumber, tbl_products.modelNo, tbl_units.unitName, tbl_products.id, tbl_products.`status`, tbl_brands.brandName, tbl_products.minSalePrice, 
                        tbl_products.maxSalePrice,tbl_products.isdiscount,tbl_products.productImage, CONCAT('<ul>', GROUP_CONCAT(CONCAT('<li>',tbl_productspecification.specificationName, ': ', tbl_productspecification.specificationValue) SEPARATOR '</li>') ,'</li></ul>') AS productSpeficiations
                    FROM tbl_products
                    INNER JOIN tbl_category ON tbl_products.categoryId = tbl_category.id AND tbl_category.deleted = 'No'
                    INNER JOIN tbl_brands ON tbl_products.tbl_brandsId = tbl_brands.id AND tbl_brands.deleted = 'No'
                    INNER JOIN tbl_units ON tbl_products.units = tbl_units.id AND tbl_units.deleted = 'No'
                    LEFT OUTER JOIN tbl_productspecification ON tbl_productspecification.tbl_productsId = tbl_products.id AND tbl_productspecification.deleted = 'No'
                    WHERE tbl_products.deleted = 'No' ".$sql_executive."
                    GROUP BY tbl_products.id
                    ORDER BY id DESC) AS dbt
                    INNER JOIN tbl_currentStock ON dbt.id = tbl_currentStock.tbl_productsId 
                    WHERE tbl_currentStock.deleted='No' 
                    GROUP BY dbt.id) AS dbt_all
                    WHERE dbt_all.totalStock > 0
                    ORDER BY dbt_all.id";
    $result = $conn->query($sql);
    $output = array('data' => array());
    if ($result->num_rows > 0) {
        $unitStatus = "";
        $i = 1;
        while ($row = $result->fetch_array()) {
            $unitId = $row['id'];
            if($row['productImage'] == '' || $row['productImage'] == ' '){
                $productImage = "images/broken_image.png";
            }else{
                $productImage = "images/products/thumb/".$row['productImage'];
            }
            // active 
            if ($row['status'] == 'Active') {
                // activate status
                $unitStatus = "<label class='label label-success'>" . $row['status'] . "</label>";
            } else {
                // deactivate status
                $unitStatus = "<label class='label label-danger'>" . $row['status'] . "</label>";
            }
    
            
            $maxSalePrice = $row['maxSalePrice'];
            $minSalePrice = $row['minSalePrice'];
            
            if(strtolower($_SESSION['userType']) == 'sales executive'){
                $priceDetails = "Price : $maxSalePrice <br>dPrice : $minSalePrice";
            }else{
                $priceDetails = "Price : $maxSalePrice";
            }
            
            if($maxSalePrice == ""){
                $maxSalePrice = 0;
            }
            if($minSalePrice == ""){
                $minSalePrice = 0;
            }
            if($maxSalePrice != 0){
                $discount = ((($maxSalePrice-$minSalePrice) / (float)$maxSalePrice)*100);
            }else{
                $discount = "N/C";
            }
            
            if(strtolower($_SESSION['userType']) == 'super admin' || strtolower($_SESSION['userType']) == 'sales executive'){					
                $id = $row['id'];
                $productName = $row['productName'];
                $productName.= '<br><b>Brand</b>: '.$row['brandName'];
                $productName.= ' <b>Model</b>: '.$row['modelNo'];
                $productName = str_replace("'","~",str_replace('"','`',$productName));
                $params = "'$id','$productName','$maxSalePrice','$minSalePrice','$maxSalePrice','1'";
                $button = '<a href="#" onclick="add_to_cart('.$params.')"><button class="btn btn-success  btn-sm btn-flat"><i class="fa fa-shopping-cart" aria-hidden="true"> Add to cart</i></button></a>';
                //$button = '<a href="#" onclick="add_to_cart(, '.$row['productName'].', product_price, min_price, max_price, product_quantity)"><button class="btn btn-warning  btn-sm btn-flat"><i class="fa fa-shopping-cart" aria-hidden="true"></i></button></a>';
            }else{
                $button = '';
            }
            $isdiscount = $row['isdiscount'];
			$discountOffer = "";
			$type = 'partySale';
			$productId = $row['id'];
			if($type == "wiCustomer" && substr($isdiscount,0,1) == '1'){
			    $discountOffer = "<a onclick=discountOffer('$type','$productId')><img src='images/discount.png'/><span style='display:none;'>Discount Offer</span></a>";
			}else if($type == "partySale" && substr($isdiscount,1,1) == '1'){
			    $discountOffer = "<a onclick=discountOffer('Party','$productId')><img src='images/discount.png'/><span style='display:none;'>Discount Offer</span></a>";
			}else if($type == "temporarySale" && substr($isdiscount,2,1) == '1'){
			    $discountOffer = "<a onclick=discountOffer('TS','$productId')><img src='images/discount.png'/><span style='display:none;'>Discount Offer</span></a>";
			}
            $output['data'][] = array(
                $i++,
                '<img src="'.$productImage.'" style="width:50px; height:50px;"/>',
                '<td style="width: 50%;">'.$row['productName'].' / '.$row['productCode'].'<br>'.$row['categoryName'].' / '.$row['brandName'].' / '.$row['modelNo'].'<br>'.$row['productSpeficiations'].'</td>',
                $priceDetails,
                '<b style="color:green;">Available</b><br>'.$discountOffer,
                $button
            );
        } // /while 
    }// if num_rows
    
    $conn->close();
    
    echo json_encode($output);
    }
?>