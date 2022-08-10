<?php
$conPrefix = '../';
include $conPrefix . 'includes/session.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $sql = "SELECT tbl_purchase.id,DATE_FORMAT(tbl_purchase.purchaseDate, '%Y-%m-%d') AS purchaseDate,tbl_purchase.purchaseOrderNo,tbl_purchase.tbl_supplierId, tbl_purchase.chalanNo, tbl_purchaseProducts.quantity, tbl_purchaseProducts.units, 
            tbl_purchaseProducts.purchaseAmount, tbl_purchaseProducts.totalAmount, tbl_purchaseProducts.wholeSalePrice, tbl_purchaseProducts.walkinCustomerPrice, tbl_purchaseProducts.tbl_wareHouseId, tbl_purchaseProducts.manufacturingDate, 
            tbl_purchaseProducts.expiryDate, tbl_products.productName, tbl_products.productCode,tbl_purchaseProducts.id as purchaseProductsId, tbl_purchase.paidAmount, tbl_purchase.totalAmount as grandTotal, tbl_purchase.dueAmount 
            FROM tbl_purchase LEFT OUTER JOIN tbl_purchaseProducts ON tbl_purchase.id = tbl_purchaseProducts.tbl_purchaseId AND tbl_purchaseProducts.deleted='No' 
            LEFT OUTER JOIN tbl_products ON tbl_purchaseProducts.tbl_productsId = tbl_products.id AND tbl_products.deleted='No'
            WHERE tbl_purchase.id = '$id'";
    
    $query = $conn->query($sql);
	while($row =  $query->fetch_assoc())
	{
		$rows[]= $row;
	}
    echo json_encode($rows);
}
?>