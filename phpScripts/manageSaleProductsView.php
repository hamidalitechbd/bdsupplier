<?php
    $conPrefix = '../';
    include $conPrefix . 'includes/session.php';
    if(isset($_POST['wareHouseId'])){
        $wareHouseId = $_POST['wareHouseId'];
        $type = $_POST['type'];
        if($type == "wiCustomer"){
            /*$sql = "SELECT tbl_products.id, productCode, productName,modelNo, units, productDescriptions, productImage, tbl_category.categoryName, tbl_brands.brandName, IFNULL(tbl_products.saleTime,0) AS saleTime, tbl_currentStock.currentStock, IFNULL(dbt_avcoPrice.avcoPrice,0) AS avcoPrice
                    FROM tbl_products
                    INNER JOIN tbl_category ON tbl_products.categoryId = tbl_category.id 
                    	AND tbl_category.deleted='No' AND tbl_category.status='Active'
                    LEFT OUTER JOIN tbl_brands ON tbl_products.tbl_brandsId = tbl_brands.id 
                    	AND tbl_brands.deleted='No' AND tbl_brands.status='Active'
                    LEFT OUTER JOIN tbl_currentStock ON tbl_products.id = tbl_currentStock.tbl_productsId
                    	AND tbl_currentStock.tbl_wareHouseId='$wareHouseId'
                    LEFT OUTER JOIN (SELECT CEILING(SUM(dbt.quantity*dbt.walkinCustomerPrice)/SUM(dbt.quantity)) as avcoPrice, dbt.tbl_productsId FROM
                                        (SELECT quantity-saleQuantity as quantity, walkinCustomerPrice, tbl_productsId 
                                            FROM tbl_purchaseProducts 
                                            WHERE avcoStatus='Yes' AND deleted='No'
                                            UNION
                                            SELECT quantity-saleQuantity as quantity, walkinCustomerPrice, tbl_productsId
                                            FROM tbl_purchaseForeignProducts
                                            WHERE avcoStatus='Yes' AND deleted='No') as dbt
                                        GROUP BY dbt.tbl_productsId) as dbt_avcoPrice ON tbl_products.id = dbt_avcoPrice.tbl_productsId
                    WHERE tbl_products.status='Active' AND tbl_products.deleted='No' 
                    ORDER BY saleTime DESC, currentStock DESC
                    LIMIT 200";*/
            /*$sql = "SELECT tbl_products.id, productCode, productName,modelNo,productDescriptions, productImage,tbl_units.unitName,tbl_category.categoryName, tbl_brands.brandName, IFNULL(tbl_products.saleTime,0) AS saleTime, tbl_currentStock.currentStock, IFNULL(tbl_products.maxSalePrice,0) AS avcoPrice, IFNULL(dbt_productTotal.totalStock, 0) AS totalStock, GROUP_CONCAT(CONCAT(tbl_productspecification.specificationName,': ',tbl_productspecification.specificationValue) SEPARATOR ' <br>') AS productSpecification
                    FROM tbl_products
                    LEFT OUTER JOIN tbl_productspecification ON tbl_productspecification.tbl_productsId = tbl_products.id AND tbl_productspecification.deleted = 'No'
                    INNER JOIN tbl_category ON tbl_products.categoryId = tbl_category.id AND tbl_category.deleted='No' AND tbl_category.status='Active'
                    LEFT OUTER JOIN tbl_brands ON tbl_products.tbl_brandsId = tbl_brands.id AND tbl_brands.deleted='No' AND tbl_brands.status='Active'
                    LEFT OUTER JOIN tbl_currentStock ON tbl_products.id = tbl_currentStock.tbl_productsId AND tbl_currentStock.tbl_wareHouseId='$wareHouseId'
                    LEFT OUTER JOIN tbl_units ON tbl_units.id=tbl_products.units
                    LEFT OUTER JOIN (SELECT TRUNCATE(SUM(currentStock),0) as totalStock, tbl_productsId 
                                    FROM tbl_currentStock 
                                    WHERE deleted = 'No'
                                    GROUP BY tbl_productsId) as dbt_productTotal ON dbt_productTotal.tbl_productsId = tbl_products.id
                    WHERE tbl_products.status='Active' AND tbl_products.deleted='No' AND dbt_productTotal.totalStock > 0
                    GROUP BY tbl_products.id
                    ORDER BY saleTime DESC, currentStock DESC
                    Limit 200";*/
            $sql = "SELECT dbt_allinfo.*, SUM(tbl_currentStock.currentStock) AS totalStock FROM
                    (SELECT tbl_products.id,tbl_products.isdiscount, productCode, productName,modelNo,productDescriptions, productImage,tbl_units.unitName,tbl_category.categoryName, tbl_brands.brandName, IFNULL(tbl_products.saleTime,0) AS saleTime, tbl_currentStock.currentStock, IFNULL(tbl_products.maxSalePrice,0) AS avcoPrice, IFNULL(tbl_products.maxSalePrice,0) AS maxSalePrice, IFNULL(tbl_products.minSalePrice,0) AS minSalePrice, GROUP_CONCAT(CONCAT(tbl_productspecification.specificationName,': ',tbl_productspecification.specificationValue) SEPARATOR ' <br>') AS productSpecification
                    					FROM tbl_products
                                        LEFT OUTER JOIN tbl_productspecification ON tbl_productspecification.tbl_productsId = tbl_products.id AND tbl_productspecification.deleted = 'No'
                                        LEFT OUTER JOIN tbl_category ON tbl_products.categoryId = tbl_category.id AND tbl_category.deleted='No' AND tbl_category.status='Active'
                                        LEFT OUTER JOIN tbl_brands ON tbl_products.tbl_brandsId = tbl_brands.id AND tbl_brands.deleted='No' AND tbl_brands.status='Active'
                                        LEFT OUTER JOIN tbl_currentStock ON tbl_products.id = tbl_currentStock.tbl_productsId AND tbl_currentStock.tbl_wareHouseId='$wareHouseId'
                                        LEFT OUTER JOIN tbl_units ON tbl_units.id=tbl_products.units
                                        WHERE tbl_products.status='Active' AND tbl_products.deleted='No' AND tbl_currentStock.currentStock > 0
                                        GROUP BY tbl_products.id
                                        ORDER BY saleTime DESC, currentStock DESC 
                                        Limit 200) as dbt_allinfo
                                        inner JOIN tbl_currentStock ON tbl_currentStock.tbl_productsId = dbt_allinfo.id AND tbl_currentStock.deleted='No'
                                        GROUP BY dbt_allinfo.id
                                        ORDER BY saleTime DESC, currentStock DESC";
        } else if ($type == 'Party'){
            
            /*$sql = "SELECT dbt_allinfo.*, SUM(tbl_currentStock.currentStock) AS totalStock FROM
                    (SELECT tbl_products.id,tbl_products.isdiscount, tbl_products.productCode, productName,modelNo,productDescriptions, productImage,tbl_units.unitName,tbl_category.categoryName, tbl_brands.brandName, IFNULL(tbl_products.saleTime,0) AS saleTime, tbl_currentStock.currentStock, IFNULL(tbl_products.minSalePrice,0) AS avcoPrice, IFNULL(tbl_products.maxSalePrice,0) AS maxSalePrice, IFNULL(tbl_products.minSalePrice,0) AS minSalePrice, GROUP_CONCAT(CONCAT(tbl_productspecification.specificationName,': ',tbl_productspecification.specificationValue) SEPARATOR ' <br>') AS productSpecification
                    					FROM tbl_products
                                        LEFT OUTER JOIN tbl_productspecification ON tbl_productspecification.tbl_productsId = tbl_products.id AND tbl_productspecification.deleted = 'No'
                                        LEFT OUTER JOIN tbl_category ON tbl_products.categoryId = tbl_category.id AND tbl_category.deleted='No' AND tbl_category.status='Active'
                                        LEFT OUTER JOIN tbl_brands ON tbl_products.tbl_brandsId = tbl_brands.id AND tbl_brands.deleted='No' AND tbl_brands.status='Active'
                                        LEFT OUTER JOIN tbl_currentStock ON tbl_products.id = tbl_currentStock.tbl_productsId AND tbl_currentStock.tbl_wareHouseId='$wareHouseId'
                                        LEFT OUTER JOIN tbl_units ON tbl_units.id=tbl_products.units
                                        WHERE tbl_products.status='Active' AND tbl_products.deleted='No' AND tbl_currentStock.currentStock > 0
                                        GROUP BY tbl_products.id
                                        ORDER BY saleTime DESC, currentStock DESC 
                                        Limit 200) as dbt_allinfo
                                        inner JOIN tbl_currentStock ON tbl_currentStock.tbl_productsId = dbt_allinfo.id AND tbl_currentStock.deleted='No'
                                        GROUP BY dbt_allinfo.id
                                        ORDER BY saleTime DESC, currentStock DESC";*/
            $sql = "SELECT dbt_allinfo.*, SUM(tbl_currentStock.currentStock) AS totalStock FROM
                    (SELECT tbl_products.id,tbl_products.isdiscount, tbl_products.productCode, productName,modelNo,productDescriptions, productImage,tbl_units.unitName,tbl_category.categoryName, tbl_brands.brandName, IFNULL(tbl_products.saleTime,0) AS saleTime, tbl_currentStock.currentStock, IFNULL(tbl_products.minSalePrice,0) AS avcoPrice, IFNULL(tbl_products.maxSalePrice,0) AS maxSalePrice, IFNULL(tbl_products.minSalePrice,0) AS minSalePrice, GROUP_CONCAT(CONCAT(tbl_productspecification.specificationName,': ',tbl_productspecification.specificationValue) SEPARATOR ' <br>') AS productSpecification
                    					FROM tbl_products
                                        LEFT OUTER JOIN tbl_productspecification ON tbl_productspecification.tbl_productsId = tbl_products.id AND tbl_productspecification.deleted = 'No'
                                        LEFT OUTER JOIN tbl_category ON tbl_products.categoryId = tbl_category.id AND tbl_category.deleted='No' AND tbl_category.status='Active'
                                        LEFT OUTER JOIN tbl_brands ON tbl_products.tbl_brandsId = tbl_brands.id AND tbl_brands.deleted='No' AND tbl_brands.status='Active'
                                        LEFT OUTER JOIN tbl_currentStock ON tbl_products.id = tbl_currentStock.tbl_productsId AND tbl_currentStock.tbl_wareHouseId='$wareHouseId'
                                        LEFT OUTER JOIN tbl_units ON tbl_units.id=tbl_products.units
                                        WHERE tbl_products.status='Active' AND tbl_products.deleted='No' AND tbl_currentStock.currentStock > 0 AND (tbl_products.isdiscount like '%10' OR tbl_products.isdiscount like '%11')
                                        GROUP BY tbl_products.id
                                        ORDER BY currentStock DESC) as dbt_allinfo
                                        inner JOIN tbl_currentStock ON tbl_currentStock.tbl_productsId = dbt_allinfo.id AND tbl_currentStock.deleted='No'
                                        GROUP BY dbt_allinfo.id
                                        ORDER BY currentStock DESC";
                
        }else{
           
            /*$sql = "SELECT dbt_allinfo.*, SUM(tbl_currentStock.currentStock) AS totalStock FROM
                    (SELECT tbl_products.id,tbl_products.isdiscount, tbl_products.productCode, productName,modelNo,productDescriptions, productImage,tbl_units.unitName,tbl_category.categoryName, tbl_brands.brandName, IFNULL(tbl_products.saleTime,0) AS saleTime, tbl_currentStock.currentStock, IFNULL(tbl_products.minSalePrice,0) AS avcoPrice, IFNULL(tbl_products.maxSalePrice,0) AS maxSalePrice, IFNULL(tbl_products.minSalePrice,0) AS minSalePrice, GROUP_CONCAT(CONCAT(tbl_productspecification.specificationName,': ',tbl_productspecification.specificationValue) SEPARATOR ' <br>') AS productSpecification
                    					FROM tbl_products
                                        LEFT OUTER JOIN tbl_productspecification ON tbl_productspecification.tbl_productsId = tbl_products.id AND tbl_productspecification.deleted = 'No'
                                        LEFT OUTER JOIN tbl_category ON tbl_products.categoryId = tbl_category.id AND tbl_category.deleted='No' AND tbl_category.status='Active'
                                        LEFT OUTER JOIN tbl_brands ON tbl_products.tbl_brandsId = tbl_brands.id AND tbl_brands.deleted='No' AND tbl_brands.status='Active'
                                        LEFT OUTER JOIN tbl_currentStock ON tbl_products.id = tbl_currentStock.tbl_productsId AND tbl_currentStock.tbl_wareHouseId='$wareHouseId'
                                        LEFT OUTER JOIN tbl_units ON tbl_units.id=tbl_products.units
                                        WHERE tbl_products.status='Active' AND tbl_products.deleted='No' AND tbl_currentStock.currentStock > 0
                                        GROUP BY tbl_products.id
                                        ORDER BY saleTime DESC, currentStock DESC 
                                        Limit 200) as dbt_allinfo
                                        inner JOIN tbl_currentStock ON tbl_currentStock.tbl_productsId = dbt_allinfo.id AND tbl_currentStock.deleted='No'
                                        GROUP BY dbt_allinfo.id
                                        ORDER BY saleTime DESC, currentStock DESC";*/
            $sql = "SELECT dbt_allinfo.*, SUM(tbl_currentStock.currentStock) AS totalStock FROM
                    (SELECT tbl_products.id,tbl_products.isdiscount, tbl_products.productCode, productName,modelNo,productDescriptions, productImage,tbl_units.unitName,tbl_category.categoryName, tbl_brands.brandName, IFNULL(tbl_products.saleTime,0) AS saleTime, tbl_currentStock.currentStock, IFNULL(tbl_products.minSalePrice,0) AS avcoPrice, IFNULL(tbl_products.maxSalePrice,0) AS maxSalePrice, IFNULL(tbl_products.minSalePrice,0) AS minSalePrice, GROUP_CONCAT(CONCAT(tbl_productspecification.specificationName,': ',tbl_productspecification.specificationValue) SEPARATOR ' <br>') AS productSpecification
                    					FROM tbl_products
                                        LEFT OUTER JOIN tbl_productspecification ON tbl_productspecification.tbl_productsId = tbl_products.id AND tbl_productspecification.deleted = 'No'
                                        LEFT OUTER JOIN tbl_category ON tbl_products.categoryId = tbl_category.id AND tbl_category.deleted='No' AND tbl_category.status='Active'
                                        LEFT OUTER JOIN tbl_brands ON tbl_products.tbl_brandsId = tbl_brands.id AND tbl_brands.deleted='No' AND tbl_brands.status='Active'
                                        LEFT OUTER JOIN tbl_currentStock ON tbl_products.id = tbl_currentStock.tbl_productsId AND tbl_currentStock.tbl_wareHouseId='$wareHouseId'
                                        LEFT OUTER JOIN tbl_units ON tbl_units.id=tbl_products.units
                                        WHERE tbl_products.deleted='No' AND tbl_currentStock.currentStock > 0 AND tbl_products.isdiscount like '%1'
                                        GROUP BY tbl_products.id
                                        ORDER BY saleTime DESC, currentStock DESC) as dbt_allinfo
                                        inner JOIN tbl_currentStock ON tbl_currentStock.tbl_productsId = dbt_allinfo.id AND tbl_currentStock.deleted='No'
                                        GROUP BY dbt_allinfo.id
                                        ORDER BY currentStock DESC";
        }
        //echo $sql;
        $results = $conn->query($sql);
        echo '<style>
            	.ytube{
                    /*float: left;
                    margin-right: 1.2%;*/
                    background-color: #e1e1e1;
                    border-radius: 3px;
                    border: 1px solid #FFF;
                    margin-bottom: 6px;
                    /*cursor: pointer;*/
                }
                .ytube a{
                    cursor: pointer;
                }
                .textBox {
                  width: 100px;
                  height: 30px;
                  overflow: hidden;
                  padding: 5px;
                  position: relative;
                  color: #393939;
                }
                .textBox1 {
                  width: 100px;
                  height: 28px;
                  overflow: hidden;
                  padding: 4px;
                  position: relative;
                  color: #393939;
                }
                .textBox2 {
                  width: 100px;
                  height: 28px;
                  overflow: hidden;
                  padding: 4px;
                  position: relative;
                  color: #393939;
                }
                .textBox span, .textBox1 span{
                  position: absolute;
                  white-space: nowrap;
                  transform: translateX(0);
                  transition: 2s;
                }
                .textBox span, .textBox2 span{
                  position: absolute;
                  white-space: nowrap;
                  transform: translateX(0);
                  transition: 2s;
                }
                .textBox:hover span {
                  transform: translateX(calc(125px - 120%));
                }
                .textBox1:hover span {
                  transform: translateX(calc(100px - 140%));
                }
                .textBox2:hover span {
                  transform: translateX(calc(50px - 50%));
                }
            </style>';
         
		while($row = $results->fetch_assoc()){
			$productImage = '';
			if($row['productImage'] == ''){
				$productImage = 'images/broken_image.png';
			}else{
				$productImage = 'images/products/thumb/'.$row['productImage'];
			}
			$productCode = $row['productCode'];
			$productName = $row['productName'];
			$modelNo = $row['modelNo'];
			$uName = $row['unitName'];
			$brandName = $row['brandName'];
			$avcoPrice = $row['avcoPrice'];
			$maxPrice = $row['maxSalePrice'];
			$minPrice = $row['minSalePrice'];
			$currentStock = $row['currentStock'];
			$totalStock = $row['totalStock'];
			$productSpecification = $row['productSpecification'];
			$category=$row['categoryName'];
			if($currentStock == ""){
			    $currentStock = "0";
			}
			//<li class='list-group-item' style='padding: 1px;text-align: center;'><div style='width: 100%;font-size: 12px;'><b style='border-right: 1px solid gray;margin-right: 5px;'>A: $currentStock $uName</b><b>T: $totalStock $uName </b></div></li>
			$productId = $row['id'];
			$isdiscount = $row['isdiscount'];
			$discountOffer = 0;
			if($type == "wiCustomer" && substr($isdiscount,0,1) == '1'){
			    $discountOffer = 1;
			}else if($type == "Party" && substr($isdiscount,1,1) == '1'){
			    $discountOffer = 1;
			}else if($type == "TS" && substr($isdiscount,2,1) == '1'){
			    $discountOffer = 1;
			} 
			echo "<div class='col-md-4 results'>
					<div class='ytube'>
						<a href='#' name='add_to_cart' id='".$productId."' class='add_to_cart'>
						<img src='$productImage' width='100%' height='90' style='padding: 2%;'/>
						<ul class='list-group' style='margin-bottom: 1%;'>
                            <li class='list-group-item textBox'><span>$productName - $productCode</span></li>
                            <li class='list-group-item textBox1'><span style='width:100%;'><b style='font-size:12px;'>A: $currentStock $uName | T: $totalStock $uName </b></span></li>
                            </a>
                            <li class='list-group-item textBox2'><span><b>  &#2547;  $avcoPrice </b></span>"; 
                            if($discountOffer == 1){
                                echo "<a onclick=discountOffer('$type','$productId')><img src='images/discount.png' style='margin: -5% 0% 0% 50%;'/></a>";
                              /*echo "<a class='btn btn-xs discountOffer' id='$productId' href='#'><img src='images/discount.png' style='margin: -5% 0% 0% 65%;'/></a>";*/
                            }
                        echo "</li>
                            <li class='list-group-item textBox1'><span>$brandName - $modelNo</span></li>
                        </ul>
						<span style='display:none;'>$category</span>
						<input type='hidden' name='quantity' id='quantity".$productId."' class='form-control' value='1' />
						<input type='hidden' name='hidden_name' id='name".$productId."' value='$productName - $brandName - $modelNo' />
						<input type='hidden' name='hidden_price' id='price".$productId."' value='$avcoPrice' />
						<input type='hidden' name='hidden_max_price' id='max_price".$productId."' value='$maxPrice' />
						<input type='hidden' name='hidden_min_price' id='min_price".$productId."' value='$minPrice' />
						<div id='productSpac".$productId."' style='display:none;'>$productSpecification</div>
						<div id='totalStock".$productId."' style='display:none;'>$totalStock</div>";			
			echo "</div> </div>";
		}
    }
?>