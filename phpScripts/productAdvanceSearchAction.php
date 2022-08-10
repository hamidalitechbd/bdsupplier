<?php
$conPrefix = '../';
include $conPrefix . 'includes/session.php';
if(isset($_POST['id']))
{
    $id = $_POST['id'];
    $sql = "SELECT currentStock, tbl_warehouse.wareHouseName, tbl_warehouse.id
            FROM tbl_currentStock
            INNER JOIN tbl_warehouse ON tbl_currentStock.tbl_wareHouseId = tbl_warehouse.id
            WHERE tbl_currentStock.tbl_productsId='$id' AND tbl_currentStock.deleted='No' AND tbl_warehouse.deleted = 'No'";
    $result = $conn->query($sql);
    $output = '';
    $total = 0;
    while ($row = $result->fetch_array()) {
         $output .= '<table style="width:100%;margin-bottom: 1%;"><tr><td style="width: 62%;font-size: 12px;"><b>'.$row['wareHouseName'].'</b></td><td style="width: 22%;"><b>'.$row['currentStock'].'</b></td>
         <td>';
         if($row['currentStock'] > 0){
            $output .= '<a href="#" class="advanceButton" onclick="selectProducts('.$id.','.$row['id'].')"><i class="fa fa-plus"></i></a>';
         }
         $output .= '</td></tr></table>';    
        $total = $total + $row['currentStock'];
    }
    echo json_encode('<b style="font-size: 14px;color: blue;">Total Stock: '.$total.'</b><br>'.$output);
}
else
{
    $sql = "SELECT tbl_products.productName, tbl_products.id, tbl_products.productCode, tbl_products.modelNo, tbl_category.categoryName, tbl_brands.brandName, tbl_products.minSalePrice, 
            tbl_products.maxSalePrice, tbl_products.isdiscount, GROUP_CONCAT(CONCAT(tbl_productspecification.specificationName,': ',tbl_productspecification.specificationValue) SEPARATOR ' <br>') AS productSpecification, 
            '' AS wareHouseStock, tbl_units.unitName, '' as totalStock
            FROM tbl_products
            inner JOIN tbl_category ON tbl_products.categoryId = tbl_category.id AND tbl_category.deleted = 'No'
            LEFT OUTER JOIN tbl_brands ON tbl_products.tbl_brandsId = tbl_brands.id AND tbl_brands.deleted = 'No'
            LEFT OUTER JOIN tbl_units ON tbl_products.units = tbl_units.id AND tbl_units.deleted = 'No'
            LEFT OUTER JOIN tbl_productspecification ON tbl_productspecification.tbl_productsId = tbl_products.id AND tbl_productspecification.deleted = 'No'
            WHERE tbl_products.deleted = 'No' AND tbl_products.status = 'Active' AND tbl_category.status = 'Active'
            GROUP BY tbl_products.id";
    $result = $conn->query($sql);
    $output = array('data' => array());
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_array()) {
            /*if($row['productImage'] == '' || $row['productImage'] == ' '){
                $productImage = "images/broken_image.png";
            }else{
                $productImage = "images/products/thumb/".$row['productImage'];
            }*/
            $isdiscount = $row['isdiscount'];
			$discountOffer = "";
			$type = $_GET['page'];
			$productId = $row['id'];
			if($type == "wiCustomer" && substr($isdiscount,0,1) == '1'){
			    $discountOffer = "<a onclick=discountOffer('$type','$productId')><img src='images/discount.png'/><span style='display:none;'>Discount Offer</span></a>";
			}else if($type == "partySale" && substr($isdiscount,1,1) == '1'){
			    $discountOffer = "<a onclick=discountOffer('Party','$productId')><img src='images/discount.png'/><span style='display:none;'>Discount Offer</span></a>";
			}else if($type == "temporarySale" && substr($isdiscount,2,1) == '1'){
			    $discountOffer = "<a onclick=discountOffer('TS','$productId')><img src='images/discount.png'/><span style='display:none;'>Discount Offer</span></a>";
			}
			if($type == "purchase"){
			    $addProducts = '<a class="btn btn-primary btn-flat" onclick="selectProducts('.$row['id'].')"><i class="fa fa-plus"></i></a>';
			}else{
			    $addProducts = "";
			}
            $output['data'][] = array(
                $row['productName']. '-' .$row['productCode'].'<br>'.$discountOffer,
                /*'<a onclick="selectProducts('.$row['id'].')"><img src="'.$productImage.'" style="width:50px; height:50px;"/></a>',*/
                $row['categoryName'],
                
               /*$row['unitName'],
                $row['brandName'],*/
                '<b>Brand : </b>'.$row['brandName'].'<br><b>Model : </b>'.$row['modelNo'].'<br>'.$row['productSpecification'],
                /*'Total Stock = '.$row['totalStock'].' '.$row['unitName'].'<br>'.$row['wareHouseStock'],*/
                'Price = <b>'.$row['maxSalePrice'].'</b><br>dPrice = <b>'.$row['minSalePrice'].'</b>',
                '<div id="stock_'.$row['id'].'"></div>',
                $addProducts.
                 '<a class="btn btn-primary btn-flat" onclick="ViewProductStock('.$row['id'].')"><i class="fa fa-eye"></i></a>'
                
            );
        } // /while 
    }// if num_rows
    
    $conn->close();
    
    echo json_encode($output);
}
?>