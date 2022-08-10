<?php
$conPrefix = '../';
include $conPrefix . 'includes/session.php';
if(isset($_GET['sessionId'])){
    $sessionId = $_GET['sessionId'];
    $sql = "SELECT tbl_productsId,quantity,wholeSalePrice,walkinCustomerPrice,purchaseAmount,totalAmount,tbl_products.productCode, tbl_products.productName, tbl_tempPurchaseProducts.id, tbl_units.unitName, tbl_brands.brandName, tbl_products.modelNo
            FROM tbl_tempPurchaseProducts 
            INNER JOIN tbl_products ON tbl_tempPurchaseProducts.tbl_productsId = tbl_products.id
            LEFT OUTER JOIN tbl_units ON tbl_products.units = tbl_units.id
            LEFT OUTER JOIN tbl_brands ON tbl_products.tbl_brandsId = tbl_brands.id
            WHERE sessionId='$sessionId'
            ORDER BY id DESC";
}else if (isset($_GET['userId'])){
	$entryBy = $_SESSION['user'];
	$sql = "select tbl_productsId,quantity,wholeSalePrice,walkinCustomerPrice,purchaseAmount,totalAmount,tbl_products.productCode, tbl_products.productName, tbl_tempPurchaseProducts.id,tbl_tempPurchaseProducts.sessionId , tbl_units.unitName, tbl_brands.brandName, tbl_products.modelNo
            FROM tbl_tempPurchaseProducts 
            INNER JOIN tbl_products ON tbl_tempPurchaseProducts.tbl_productsId = tbl_products.id
            LEFT OUTER JOIN tbl_units ON tbl_products.units = tbl_units.id
            LEFT OUTER JOIN tbl_brands ON tbl_products.tbl_brandsId = tbl_brands.id
            WHERE entryBy='$entryBy'
            ORDER BY id DESC";
}else{
    $sessionId = $_GET['id'];
    $sql = "select tbl_productsId,quantity,wholeSalePrice,walkinCustomerPrice,purchaseAmount,totalAmount,tbl_products.productCode, tbl_products.productName, tbl_purchaseProducts.id, tbl_units.unitName, tbl_brands.brandName, tbl_products.modelNo 
            FROM tbl_purchaseProducts 
            INNER JOIN tbl_products ON tbl_purchaseProducts.tbl_productsId = tbl_products.id 
            LEFT OUTER JOIN tbl_units ON tbl_products.units = tbl_units.id
            LEFT OUTER JOIN tbl_brands ON tbl_products.tbl_brandsId = tbl_brands.id
            WHERE tbl_purchaseProducts.tbl_purchaseId='$sessionId' AND tbl_purchaseProducts.deleted='No'
            ORDER BY id DESC";
}
$result = $conn->query($sql);
$output = array('data' => array());
echo '<thead style="background-color: #e1e1e1;">
			  <th>SN</th>
			  <th>Product Name</th>
			  <th>Product Code</th>
			  <th>Model No</th>
			  <th>Brand Name</th>
			  <th>Unit Price</th>                  
			  <th>Quantity</th>
			  <th>Total</th>
			  <th style="width:6%;">Action</th>
			</thead>';
if ($result->num_rows > 0) {
    $unitStatus = "";
    $i = 1;
    $grandTotal = 0;
    while ($row = $result->fetch_array()) {
        if(isset($_GET['sessionId']) || isset($_GET['userId'])){
			if(isset($_GET['userId'])){
				$sessionId = $row['sessionId'];
			}
            $button = '<a class="btn btn-danger btn-sm btn-flat" href="#" onclick="deleteTemporaryPurchaseProducts(' . $row['id'] . ')"><i class="fa fa-trash"></i></a>';
        }else{
            $button = '<a href="#"  class="btn btn-danger btn-sm btn-flat" onclick="deletePurchaseProducts(' . $row['id'] . ')"><i class="fa fa-trash"></i></a>';
        }
        echo '<tr>
            <td>'.$i++.'</td>
            <td>'.$row['productName'].'</td>
            <td>'.$row['productCode'].'</td>
            <td>'.$row['modelNo'].'</td>
            <td>'.$row['brandName'].'</td>
            <td>'.$row['purchaseAmount'].'</td>
            <td>'.$row['quantity'].' '.$row['unitName'].'</td>
            <td>'.$row['totalAmount'].'</td>
            <td>'.$button.'</td>
            </tr>';
            $grandTotal = $grandTotal + $row['totalAmount'];
    } // /while 
    if($sessionId == ''){
        $sessionId = $_GET['newSessionId'];
    }
	echo '@!@'.$grandTotal.'@!@'.$sessionId;
}// if num_rows
$conn->close();

//echo json_encode($output);
?>