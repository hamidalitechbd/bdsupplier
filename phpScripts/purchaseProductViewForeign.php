<?php
$conPrefix = '../';
include $conPrefix . 'includes/session.php';
if(isset($_GET['sessionId'])){
    $sessionId = $_GET['sessionId'];
    /*$sql = "select tbl_productsId,quantity,wholeSalePrice,walkinCustomerPrice,purchaseAmount,totalAmount,tbl_products.productCode, tbl_products.productName, tbl_tempForeignPurchaseProducts.id
            from tbl_tempForeignPurchaseProducts INNER JOIN tbl_products ON tbl_tempForeignPurchaseProducts.tbl_productsId = tbl_products.id
            where sessionId='$sessionId'
            order by id DESC";*/
    //added product brandname and model no
    $sql = "select tbl_productsId,quantity,wholeSalePrice,walkinCustomerPrice,purchaseAmount,totalAmount,tbl_products.productCode, tbl_products.productName, tbl_tempForeignPurchaseProducts.id, 
                    tbl_brands.brandName, tbl_products.modelNo, tbl_warehouse.wareHouseName
            from tbl_tempForeignPurchaseProducts 
            INNER JOIN tbl_products ON tbl_tempForeignPurchaseProducts.tbl_productsId = tbl_products.id
            LEFT OUTER JOIN tbl_brands ON tbl_products.tbl_brandsId = tbl_brands.id AND tbl_brands.deleted= 'No'
            LEFT OUTER JOIN tbl_warehouse ON tbl_tempForeignPurchaseProducts.tbl_wareHouseId = tbl_warehouse.id 
            where sessionId='$sessionId'
            order by id DESC";
}else if (isset($_GET['userId'])){
	$entryBy = $_SESSION['user'];
	/*$sql = "select tbl_productsId,quantity,wholeSalePrice,walkinCustomerPrice,purchaseAmount,totalAmount,tbl_products.productCode, tbl_products.productName, tbl_tempForeignPurchaseProducts.id,tbl_tempForeignPurchaseProducts.sessionId 
            from tbl_tempForeignPurchaseProducts INNER JOIN tbl_products ON tbl_tempForeignPurchaseProducts.tbl_productsId = tbl_products.id
            where entryBy='$entryBy'
            order by id DESC";*/
    $sql = "select tbl_productsId,quantity,wholeSalePrice,walkinCustomerPrice,purchaseAmount,totalAmount,tbl_products.productCode, tbl_products.productName, tbl_tempForeignPurchaseProducts.id,
                    tbl_tempForeignPurchaseProducts.sessionId, tbl_brands.brandName, tbl_products.modelNo, tbl_warehouse.wareHouseName 
            from tbl_tempForeignPurchaseProducts 
            INNER JOIN tbl_products ON tbl_tempForeignPurchaseProducts.tbl_productsId = tbl_products.id
            LEFT OUTER JOIN tbl_brands ON tbl_products.tbl_brandsId = tbl_brands.id AND tbl_brands.deleted= 'No'
            LEFT OUTER JOIN tbl_warehouse ON tbl_tempForeignPurchaseProducts.tbl_wareHouseId = tbl_warehouse.id 
            where entryBy='$entryBy'
            order by id DESC";
}else{
    $sessionId = $_GET['id'];
    /*$sql = "select tbl_productsId,quantity,wholeSalePrice,walkinCustomerPrice,purchaseAmount,totalAmount,tbl_products.productCode, tbl_products.productName, tbl_purchaseForeignProducts.id 
            from tbl_purchaseForeignProducts INNER JOIN tbl_products ON tbl_purchaseForeignProducts.tbl_productsId = tbl_products.id 
            where tbl_purchaseForeignProducts.tbl_purchaseId='$sessionId' AND tbl_purchaseForeignProducts.deleted='No'
        order by id DESC";*/
    $sql = "select tbl_productsId,quantity,wholeSalePrice,walkinCustomerPrice,purchaseAmount,totalAmount,tbl_products.productCode, tbl_products.productName, tbl_purchaseForeignProducts.id, 
                    tbl_brands.brandName, tbl_products.modelNo, tbl_warehouse.wareHouseName
            from tbl_purchaseForeignProducts 
            INNER JOIN tbl_products ON tbl_purchaseForeignProducts.tbl_productsId = tbl_products.id 
            LEFT OUTER JOIN tbl_brands ON tbl_products.tbl_brandsId = tbl_brands.id AND tbl_brands.deleted= 'No'
            LEFT OUTER JOIN tbl_warehouse ON tbl_purchaseForeignProducts.tbl_wareHouseId = tbl_warehouse.id 
            where tbl_purchaseForeignProducts.tbl_purchaseId='$sessionId' AND tbl_purchaseForeignProducts.deleted='No'
        order by id DESC";
}
$result = $conn->query($sql);
//$output = array('data' => array());
echo '<thead style="background-color: #e1e1e1;"><tr>
			  <th>SN</th>
			  <th>Item Name</th>
			  <th>Product Code</th>
			  <th>Model No</th>
			  <th>Brand Name</th>
			  <th>Warehouse</th>
			  <th>Unit Price</th>                  
			  <th>Quantity</th>
			  <th>Total</th>
			  <th style="width:6%;">Action</th>
			</tr></thead>';
if ($result->num_rows > 0) {
    echo '<tbody>';
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
            <td>'.$row['wareHouseName'].'</td>
            <td>'.$row['purchaseAmount'].'</td>
            <td>'.$row['quantity'].'</td>
            <td>'.$row['totalAmount'].'</td>
            <td>'.$button.'</td>
            </tr>';
            $grandTotal = $grandTotal + $row['totalAmount'];
    } // /while 
    if($sessionId == ''){
        $sessionId = $_GET['newSessionId'];
    }
	echo '</tbody>@!@'.$grandTotal.'@!@'.$sessionId;
}// if num_rows
$conn->close();

//echo json_encode($output);
?>