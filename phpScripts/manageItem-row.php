<?php
$conPrefix = '../';
include $conPrefix . 'includes/session.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $sql = "SELECT tbl_products.*, tbl_productspecification.specificationName, tbl_productspecification.specificationValue, tbl_productspecification.id as specificationId, tbl_brands.brandName 
            FROM tbl_products 
            LEFT OUTER JOIN tbl_productspecification ON tbl_productspecification.tbl_productsId = tbl_products.id AND tbl_productspecification.deleted='No' 
            left outer join tbl_brands on tbl_products.tbl_brandsId = tbl_brands.id and tbl_brands.deleted='No'
            WHERE tbl_products.id = '$id'";
    
	if ($conn->query($sql)) {
        $_SESSION['success'] = 'Product Updated successfully';
    } else {
        $_SESSION['error'] = $conn->error;
    }
    
    $query = $conn->query($sql);
    //$row = $query->fetch_assoc();
	while($row =  $query->fetch_assoc())
	{
		$rows[]= $row;
	}
    echo json_encode($rows);
} else if($_POST['action']){
    $action = $_POST['action'];
    if($action == 'updateOpeningStock'){
        $productId = $_POST['productId'];
        $warehouseId = $_POST['warehouseId'];
        $openingStock = $_POST['openingStock'];
        $editOSProductType = $_POST['editOSProductType'];
        $stockQuantities = $_POST['stockQuantities'];
        $serialNumbers = $_POST['serialNumbers'];
        $sql_currentStock= "Select * from tbl_currentStock where deleted='No' AND tbl_productsId='$productId' AND tbl_wareHouseId='$warehouseId'";
        $result_currentStock = $conn->query($sql_currentStock);
        if($result_currentStock->num_rows > 0){
            while($row_currentStock = $result_currentStock->fetch_assoc()){
                $currentStockId = $row_currentStock['id'];
                $currentDiff = $openingStock-$row_currentStock['initialStock'];
            }
            $sql_update = "update tbl_currentStock set currentStock='$currentDiff', initialStock='$openingStock' where id='$currentStockId'";
            $conn->query($sql_update);
            $sql_update = "UPDATE tbl_products SET current_stock = current_stock+$currentDiff, opening_stock=opening_stock+$currentDiff WHERE id='$productId'";
            $conn->query($sql_update);
            if($editOSProductType == 'serialize'){
                $stockQuantitiesArray = explode(',',$stockQuantities);
                $serialNumbersArray = explode(',',$serialNumbers);
                for($i = 0; $i < count($stockQuantitiesArray); $i++){
                    $stockQuantitiesEntry = $stockQuantitiesArray[$i];
                    $serialNumbersEntry = $serialNumbersArray[$i];
                    $sql_insert = "INSERT INTO tbl_serialize_products(tbl_productsId, warehouse_id, serial_no, quantity, created_by, created_date)
                                    VALUES ('$productId','$warehouseId','$serialNumbersEntry','$stockQuantitiesEntry','$loginID','$toDay')";
                    $conn->query($sql_insert);
                    
                }
            }
        }else{
            $sql_insert = "INSERT into tbl_currentStock (tbl_productsId,tbl_wareHouseId,currentStock,initialStock,entryBy,entryDate) values ('$productId','$warehouseId','$openingStock','$openingStock','$loginID','$toDay')";
            echo json_encode($sql_insert);
            $conn->query($sql_insert);
            $sql_update = "UPDATE tbl_products SET current_stock = current_stock+$openingStock, opening_stock=opening_stock+$openingStock WHERE id='$productId'";
            $conn->query($sql_update);
            if($editOSProductType == 'serialize'){
                $stockQuantitiesArray = explode(',',$stockQuantities);
                $serialNumbersArray = explode(',',$serialNumbers);
                for($i = 0; $i < count($stockQuantitiesArray); $i++){
                    $stockQuantitiesEntry = $stockQuantitiesArray[$i];
                    $serialNumbersEntry = $serialNumbersArray[$i];
                    $sql_insert = "INSERT INTO tbl_serialize_products(tbl_productsId, warehouse_id, serial_no, quantity, created_by, created_date)
                                    VALUES ('$productId','$warehouseId','$serialNumbersEntry','$stockQuantitiesEntry','$loginID','$toDay')";
                    $conn->query($sql_insert);
                    echo json_encode($sql_insert);
                }
            }
        }
        echo json_encode('Success');
        //$sql_product = "Select * from tbl_products where id='$productId'";
        /*$currentStock = Currentstock::where('deleted', 'No')->where('tbl_productsId', $request->productId)->where('tbl_wareHouseId', $request->warehouseId);
        $product = Product::find($request->productId);
        if($currentStock->first()){
            $currentStock=$currentStock->first();
            
            $currentStock->initialStock = $request->openingStock;
            $currentStock->save();
            $currentStock->increment('currentStock', $currentDiff);
            $product->increment('current_stock', $currentDiff);
            $product->increment('opening_stock', $currentDiff);
        }else{
            $currentstock_insert = new Currentstock();
			$currentstock_insert->tbl_productsId = $request->productId;
			$currentstock_insert->tbl_wareHouseId = $request->warehouseId;
			$currentstock_insert->currentStock = $request->openingStock;
			$currentstock_insert->initialStock = $request->openingStock;
			$currentstock_insert->entryBy = auth()->user()->id;
			$currentstock_insert->entryDate = date('Y-m-d H:i:s');
			$currentstock_insert->save();
			$product->increment('current_stock', $request->openingStock);
			$product->increment('opening_stock', $request->openingStock);
        }*/
    }
}
?>