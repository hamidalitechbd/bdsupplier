<?php
$conPrefix = '../';
include $conPrefix . 'includes/session.php';
/*$sql = "SELECT tbl_category.categoryName, tbl_products.productCode, tbl_products.productName, tbl_products.lotNumber, tbl_products.modelNo, tbl_units.unitName, tbl_products.id, tbl_products.`status`, tbl_brands.brandName, tbl_products.minSalePrice, 
            tbl_products.maxSalePrice, tbl_products.productImage, CONCAT('<ul>', GROUP_CONCAT(CONCAT('<li>',tbl_productspecification.specificationName, ': ', tbl_productspecification.specificationValue) SEPARATOR '</li>') ,'</li></ul>') AS productSpeficiations
        FROM tbl_products
        LEFT OUTER JOIN tbl_category ON tbl_products.categoryId = tbl_category.id AND tbl_category.deleted = 'No'
        LEFT OUTER JOIN tbl_units ON tbl_units.id=tbl_products.units AND tbl_units.deleted = 'No'
        LEFT OUTER JOIN tbl_brands ON tbl_products.tbl_brandsId=tbl_brands.id AND tbl_brands.deleted = 'No'
        LEFT OUTER JOIN tbl_productspecification ON tbl_productspecification.tbl_productsId = tbl_products.id AND tbl_productspecification.deleted = 'No'
        WHERE tbl_category.status = 'Active' AND tbl_products.deleted = 'No'
        GROUP BY tbl_products.id
        ORDER BY id DESC";*/
    $sql = "SELECT tbl_category.categoryName, tbl_products.productCode, tbl_products.productName, tbl_products.productDescriptions, tbl_products.lotNumber, tbl_products.modelNo, tbl_units.unitName, tbl_products.id, tbl_products.`status`, tbl_brands.brandName, tbl_products.minSalePrice,tbl_products.type, tbl_products.stock_check, tbl_products.items_in_box,  
            tbl_products.maxSalePrice,tbl_products.purchasePrice, tbl_products.carton_unit, tbl_products.productImage, CONCAT('<ul>', GROUP_CONCAT(CONCAT('<li>',tbl_productspecification.specificationName, ': ', tbl_productspecification.specificationValue) SEPARATOR '</li>') ,'</li></ul>') AS productSpeficiations
        FROM tbl_products
        LEFT OUTER JOIN tbl_category ON tbl_products.categoryId = tbl_category.id AND tbl_category.deleted = 'No'
        LEFT OUTER JOIN tbl_brands ON tbl_products.tbl_brandsId = tbl_brands.id AND tbl_brands.deleted = 'No'
        LEFT OUTER JOIN tbl_units ON tbl_products.units = tbl_units.id AND tbl_units.deleted = 'No'
        LEFT OUTER JOIN tbl_productspecification ON tbl_productspecification.tbl_productsId = tbl_products.id AND tbl_productspecification.deleted = 'No'
        WHERE tbl_products.deleted = 'No'
        GROUP BY tbl_products.id
        ORDER BY id DESC";
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
        
        if(strtolower($_SESSION['userType']) == "admin coordinator" || strtolower($_SESSION['userType']) == "admin support" || strtolower($_SESSION['userType']) == "admin support plus" || strtolower($_SESSION['userType']) == 'super admin' || strtolower($_SESSION['userType']) == "admin sales"){					
            $button = '<a href="#" onclick="editItem(' . $row['id'] . ')"><button class="btn btn-warning  btn-sm btn-flat"><i class="fa fa-edit"></i> Edit</button></a>';
            $button .= '<a  class="btn" href="#" onclick="editOpenStock(' . $row['id'] . ')"><i class="fas fa-edit"></i> Update Opening Stock</a>';
        }else{
            $button = '';
        }
        $maxSalePrice = $row['maxSalePrice'];
        $minSalePrice = $row['minSalePrice'];
        $purchasePrice = $row['purchasePrice'];
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
        
        $output['data'][] = array(
            $i++,
            $row['categoryName'].'<br><b>Type:</b> '.$row['type'].'<br><b>Stock Check:</b> '.$row['stock_check'].'<br><b>Items-Box:</b> '.$row['items_in_box'],
            '<img src="'.$productImage.'" style="width:50px; height:50px;"/>',
            $row['productName'].'<br><b>Code</b> : '.$row['productCode'].'<br><b>Brand</b> : '.$row['brandName'].'<br><b>Model</b> : '.$row['modelNo'].'<br><b>Description</b> : '.$row['productDescriptions'],
            $row['unitName'],
            $row['productSpeficiations'].'<br>Carton: '.$row['carton_unit'],
            $minSalePrice,
            $maxSalePrice,
            $purchasePrice,
            $numberAsString = number_format($discount, 2),
            $unitStatus,
            $button
        );
    } // /while 
}// if num_rows

$conn->close();

echo json_encode($output);
?>