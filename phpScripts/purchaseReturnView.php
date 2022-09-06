<?php
$conPrefix = '../';
include $conPrefix . 'includes/session.php';

if(isset($_GET['loadPurchaseByPurchaseCode'])) {
	$purchaseCode = $_GET['purchaseCode'];
	/*$sql = "SELECT tbl_purchase.purchaseOrderNo, tbl_purchase.purchaseDate, tbl_purchase.tbl_supplierId, tbl_purchase.chalanNo, 
			tbl_purchase.totalAmount, tbl_purchase.grandTotal, tbl_purchase.paidAmount, tbl_purchase.dueAmount, tbl_purchase.id,
			tbl_purchaseProducts.quantity, tbl_purchaseProducts.units, tbl_purchaseProducts.purchaseAmount, 
			tbl_purchaseProducts.totalAmount, tbl_purchaseProducts.wholeSalePrice, tbl_purchaseProducts.walkinCustomerPrice, 
			tbl_purchaseProducts.tbl_wareHouseId, tbl_purchaseProducts.manufacturingDate, tbl_purchaseProducts.expiryDate,
			CONCAT(tbl_products.productName,' (',tbl_products.productCode,')') AS productName, tbl_warehouse.wareHouseName, tbl_purchaseProducts.id as purchaseProductsId  
			FROM tbl_purchase 
			INNER JOIN tbl_purchaseProducts ON tbl_purchase.id = tbl_purchaseProducts.tbl_purchaseId
			INNER JOIN tbl_products ON tbl_purchaseProducts.tbl_productsId = tbl_products.id
            INNER JOIN tbl_warehouse ON tbl_purchaseProducts.tbl_wareHouseId = tbl_warehouse.id
			WHERE tbl_purchase.status='Active' and tbl_purchase.deleted='No' AND tbl_purchaseProducts.deleted='No' 
					AND tbl_purchase.purchaseOrderNo='$purchaseCode'";*/
	$sql = "select id from tbl_purchase where purchaseOrderNo='$purchaseCode'";
	$res = $conn->query($sql);
	$purchaseId = '';
	while($row = $res->fetch_assoc()){
		$purchaseId = $row['id'];
	}
	$sql = "SELECT tbl_purchase.purchaseOrderNo, tbl_purchase.purchaseDate, tbl_purchase.tbl_supplierId, tbl_purchase.chalanNo, 
			tbl_purchase.totalAmount-IFNULL(dbt.totalAmount, 0) as totalAmount, tbl_purchase.grandTotal, tbl_purchase.paidAmount, tbl_purchase.dueAmount, tbl_purchase.id as purchaseId,
			tbl_purchaseProducts.quantity,IFNULL(dbt.returnQuantity,0) as returnQuantity, tbl_purchaseProducts.units, tbl_purchaseProducts.purchaseAmount, 
			tbl_purchaseProducts.totalAmount, tbl_purchaseProducts.wholeSalePrice, tbl_purchaseProducts.walkinCustomerPrice, 
			tbl_purchaseProducts.tbl_wareHouseId, tbl_purchaseProducts.manufacturingDate, tbl_purchaseProducts.expiryDate, tbl_products.type,
			CONCAT(tbl_products.productName,' (',tbl_products.productCode,')') AS productName, tbl_warehouse.wareHouseName, tbl_purchaseProducts.id as purchaseProductsId, IFNULL(dbt.returnQuantity,0) as returnQuantity, 
			IFNULL(dbt.totalAmount, 0) as totalAmount, tbl_products.id as tbl_productsId  
			FROM tbl_purchase 
			INNER JOIN tbl_purchaseProducts ON tbl_purchase.id = tbl_purchaseProducts.tbl_purchaseId
			INNER JOIN tbl_products ON tbl_purchaseProducts.tbl_productsId = tbl_products.id
            INNER JOIN tbl_warehouse ON tbl_purchaseProducts.tbl_wareHouseId = tbl_warehouse.id
            LEFT OUTER JOIN (SELECT SUM(quantity) as returnQuantity, SUM(totalAmount) as  totalAmount, tbl_productsId, tbl_wareHouseId
			FROM tbl_purchase_product_return
			INNER JOIN tbl_purchase_return ON tbl_purchase_return.id=tbl_purchase_product_return.tbl_purchase_return_id
			WHERE tbl_purchase_return.tbl_purchaseId='$purchaseId'
			GROUP BY tbl_productsId, tbl_wareHouseId) as dbt ON dbt.tbl_productsId=tbl_purchaseProducts.tbl_productsId AND dbt.tbl_wareHouseId=tbl_purchaseProducts.tbl_wareHouseId
						WHERE tbl_purchase.status='Active' and tbl_purchase.deleted='No' AND tbl_purchaseProducts.deleted='No' 
								AND tbl_purchase.purchaseOrderNo='$purchaseCode'";
							
	$query = $conn->query($sql);
    while($row = $query->fetch_assoc()){
		$rows[] = $row;
	}
    echo json_encode($rows);
 //=========== Start Serialize Product Return ===========//
} else if ($_POST['action'] == "showSerializTable") {
	
	$rows = '';
	$tbl_purchase_id =  $_POST['tbl_purchase_id'];
	$product_id =  $_POST['product_id'];
	$warehouse_id =  $_POST['warehouseId'];
	$totalQuantityForReturn = 0;
	$tbl_serialize_productsIdArray = [];
	$serializeProducts = [];
	$sql_serializeProducts = "SELECT id, warehouse_id, quantity, used_quantity FROM tbl_serialize_products
	WHERE  tbl_productsId='$product_id' AND warehouse_id='$warehouse_id' AND purchase_id='$tbl_purchase_id' AND deleted='No' AND quantity>used_quantity AND is_sold='ON'";
	$result_serializeProducts = $conn->query($sql_serializeProducts);
	if ($result_serializeProducts->num_rows > 0) {
		$key = 0;
		while ($row_serializeProducts = $result_serializeProducts->fetch_array()) {
			$serializeProducts[$key] = $row_serializeProducts;
			$key++;
		}
	} else {
		$rows .= '<tr class="bg-warning"><td colspan="4">Stock Not Avaialable For Sale...</td></tr>';
	}
	echo json_encode(array('serializeProducts' => $serializeProducts, "tbl_serialize_productsIdArray" => $tbl_serialize_productsIdArray));
	//=========== End Serialize Product Return ===========//
}
?>