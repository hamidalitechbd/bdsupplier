<?php
$conPrefix = '../';
include $conPrefix . 'includes/session.php';

date_default_timezone_set('Asia/Dhaka');
//$toDay = (new DateTime($test))->format("Y-m-d H:i:s");
$toDay = $toDate = (new DateTime())->format("Y-m-d");

if (isset($_POST['action'])) {
    if ($_POST['action'] == "loadSales") {
        $type = $_POST['type'];
        $id = $_POST['id'];
        $sql = '';
        if ($type == 'PartySale') {
            /*$sql = "SELECT  tbl_sales.salesOrderNo, tbl_sales.salesDate,tbl_sales.tbl_customerId, tbl_sales.requisitionNo, tbl_party.partyName, tbl_party.partyPhone, tbl_warehouse.wareHouseName,
                                tbl_sales_products.id as salesProductId, tbl_party.partyAddress, tbl_users.fname as soldBy, tbl_products.productName, tbl_products.productCode, 
                                tbl_brands.brandName, tbl_sales_products.quantity, tbl_sales_products.salesAmount, tbl_sales_products.totalAmount, tbl_sales_products.discount, 
                                tbl_sales_products.grandTotal, tbl_sales_products.tbl_wareHouseId, tbl_sales.totalAmount AS salesTotalAmount, tbl_sales.productDiscount, tbl_sales.salesDiscount, 
                                tbl_sales.grandTotal as salesGrandTotal, tbl_sales.vat, tbl_sales.ait, IFNULL(dbtProductsReturn.quantity,0) AS returnedQuantity, 
                                GROUP_CONCAT(CONCAT(tbl_productspecification.specificationName,': ',tbl_productspecification.specificationValue) SEPARATOR ', ') AS productSpecification
                        FROM tbl_sales 
                        LEFT OUTER JOIN tbl_party ON tbl_sales.tbl_customerId = tbl_party.id AND tbl_party.deleted='No'
                        LEFT OUTER JOIN tbl_users ON tbl_sales.tbl_userId=tbl_users.id AND tbl_users.deleted='No'
                        LEFT OUTER JOIN tbl_sales_products ON tbl_sales.id = tbl_sales_products.tbl_salesId AND tbl_sales_products.deleted='No'
                        LEFT OUTER JOIN tbl_warehouse ON tbl_sales_products.tbl_wareHouseId = tbl_warehouse.id AND tbl_warehouse.deleted='No'
                        LEFT OUTER JOIN tbl_products ON tbl_sales_products.tbl_productsId = tbl_products.id 
                        LEFT OUTER JOIN tbl_brands ON tbl_products.tbl_brandsId = tbl_brands.id
                        LEFT OUTER JOIN tbl_productspecification ON tbl_products.id = tbl_productspecification.tbl_productsId AND tbl_productspecification.deleted='No'
                        LEFT OUTER JOIN (SELECT SUM(tbl_sales_product_return.quantity) AS quantity, SUM(tbl_sales_product_return.totalAmount) AS totalAmount, tbl_sales_product_return.tbl_products_id
                        FROM tbl_sales_return
                        INNER JOIN tbl_sales_product_return ON tbl_sales_product_return.tbl_sales_return_id = tbl_sales_return.id AND tbl_sales_product_return.deleted = 'No'
                        WHERE tbl_sales_return.tbl_sales_id = '$id' AND tbl_sales_return.deleted = 'No'
                        GROUP BY tbl_sales_product_return.tbl_products_id) AS dbtProductsReturn ON tbl_sales_products.tbl_productsId = dbtProductsReturn.tbl_products_id
                        WHERE tbl_sales.type = '$type' AND tbl_sales.id='$id' AND tbl_sales.deleted = 'No'
                        GROUP BY tbl_sales_products.id";*/
            //After warehouse wise sale
            $sql = "SELECT  tbl_sales.salesOrderNo, tbl_sales.id as tbl_sales_id, tbl_sales.salesDate,tbl_sales.tbl_customerId, tbl_sales.requisitionNo, tbl_party.partyName, tbl_party.partyPhone, tbl_warehouse.wareHouseName,
                                tbl_sales_products.id as salesProductId, tbl_party.partyAddress, tbl_users.fname as soldBy, tbl_products.productName, tbl_products.type, tbl_products.productCode, 
                                tbl_brands.brandName, tbl_sales_products.quantity, tbl_sales_products.salesAmount, tbl_sales_products.totalAmount, tbl_sales_products.discount, tbl_sales_products.tbl_productsId,
                                tbl_sales_products.grandTotal, tbl_sales_products.tbl_wareHouseId, tbl_sales.totalAmount AS salesTotalAmount, tbl_sales.productDiscount, tbl_sales.salesDiscount, 
                                tbl_sales.grandTotal as salesGrandTotal, tbl_sales.vat, tbl_sales.ait, IFNULL(dbtProductsReturn.quantity,0) AS returnedQuantity, 
                                GROUP_CONCAT(CONCAT(tbl_productspecification.specificationName,': ',tbl_productspecification.specificationValue) SEPARATOR ', ') AS productSpecification
                        FROM tbl_sales 
                        LEFT OUTER JOIN tbl_party ON tbl_sales.tbl_customerId = tbl_party.id AND tbl_party.deleted='No'
                        LEFT OUTER JOIN tbl_users ON tbl_sales.tbl_userId=tbl_users.id AND tbl_users.deleted='No'
                        LEFT OUTER JOIN tbl_sales_products ON tbl_sales.id = tbl_sales_products.tbl_salesId AND tbl_sales_products.deleted='No'
                        LEFT OUTER JOIN tbl_warehouse ON tbl_sales_products.tbl_wareHouseId = tbl_warehouse.id AND tbl_warehouse.deleted='No'
                        LEFT OUTER JOIN tbl_products ON tbl_sales_products.tbl_productsId = tbl_products.id 
                        LEFT OUTER JOIN tbl_brands ON tbl_products.tbl_brandsId = tbl_brands.id
                        LEFT OUTER JOIN tbl_productspecification ON tbl_products.id = tbl_productspecification.tbl_productsId AND tbl_productspecification.deleted='No'
                        LEFT OUTER JOIN (SELECT SUM(tbl_sales_product_return.quantity) AS quantity, SUM(tbl_sales_product_return.totalAmount) AS totalAmount, tbl_sales_product_return.tbl_salesProductsId
                        FROM tbl_sales_return
                        INNER JOIN tbl_sales_product_return ON tbl_sales_product_return.tbl_sales_return_id = tbl_sales_return.id AND tbl_sales_product_return.deleted = 'No'
                        WHERE tbl_sales_return.tbl_sales_id = '$id' AND tbl_sales_return.deleted = 'No'
                        GROUP BY tbl_sales_product_return.tbl_products_id) AS dbtProductsReturn ON tbl_sales_products.id = dbtProductsReturn.tbl_salesProductsId
                        WHERE tbl_sales.type = '$type' AND tbl_sales.id='$id' AND tbl_sales.deleted = 'No'
                        GROUP BY tbl_sales_products.id";
        } else if ($type == 'WalkinSale') {

            /*$sql="SELECT  tbl_sales.salesOrderNo, tbl_sales.salesDate,tbl_sales.tbl_customerId, tbl_sales.requisitionNo, tbl_walkin_customer.customerName AS partyName, tbl_walkin_customer.phoneNo AS partyPhone, 
                                tbl_sales_products.id as salesProductId, tbl_walkin_customer.customerAddress AS partyAddress, tbl_users.fname as soldBy, tbl_products.productName, tbl_products.productCode, 
                                tbl_brands.brandName, tbl_sales_products.quantity, tbl_sales_products.salesAmount, tbl_sales_products.totalAmount, tbl_sales_products.discount, tbl_warehouse.wareHouseName,
                                tbl_sales_products.grandTotal, tbl_sales_products.tbl_wareHouseId, tbl_sales.totalAmount AS salesTotalAmount, tbl_sales.productDiscount, tbl_sales.salesDiscount, 
                                tbl_sales.grandTotal as salesGrandTotal, tbl_sales.vat, tbl_sales.ait, IFNULL(dbtProductsReturn.quantity,0) AS returnedQuantity
                        FROM tbl_sales 
                        LEFT OUTER JOIN tbl_walkin_customer ON tbl_sales.tbl_customerId = tbl_walkin_customer.id AND tbl_walkin_customer.deleted='No'
                        LEFT OUTER JOIN tbl_users ON tbl_sales.tbl_userId=tbl_users.id AND tbl_users.deleted='No'
                        LEFT OUTER JOIN tbl_sales_products ON tbl_sales.id = tbl_sales_products.tbl_salesId AND tbl_sales_products.deleted='No'
                        LEFT OUTER JOIN tbl_warehouse ON tbl_sales_products.tbl_wareHouseId = tbl_warehouse.id AND tbl_warehouse.deleted='No'
                        LEFT OUTER JOIN tbl_products ON tbl_sales_products.tbl_productsId = tbl_products.id 
                        LEFT OUTER JOIN tbl_brands ON tbl_products.tbl_brandsId = tbl_brands.id
                        LEFT OUTER JOIN tbl_productspecification ON tbl_products.id = tbl_productspecification.tbl_productsId AND tbl_productspecification.deleted='No'
                        LEFT OUTER JOIN (SELECT SUM(tbl_sales_product_return.quantity) AS quantity, SUM(tbl_sales_product_return.totalAmount) AS totalAmount, tbl_sales_product_return.tbl_products_id
                        FROM tbl_sales_return
                        INNER JOIN tbl_sales_product_return ON tbl_sales_product_return.tbl_sales_return_id = tbl_sales_return.id AND tbl_sales_product_return.deleted = 'No'
                        WHERE tbl_sales_return.tbl_sales_id = '".$id."' AND tbl_sales_return.deleted = 'No'
                        GROUP BY tbl_sales_product_return.tbl_products_id) AS dbtProductsReturn ON tbl_sales_products.tbl_productsId = dbtProductsReturn.tbl_products_id
                        WHERE tbl_sales.type = '".$type."' AND tbl_sales.id='".$id."' AND tbl_sales.deleted = 'No'
                        GROUP BY tbl_sales_products.id";*/
            //After warehouse wise sales 
            $sql = "SELECT  tbl_sales.salesOrderNo, tbl_sales.id as tbl_sales_id, tbl_sales.salesDate,tbl_sales.tbl_customerId, tbl_sales.requisitionNo, tbl_walkin_customer.customerName AS partyName, tbl_walkin_customer.phoneNo AS partyPhone, 
                                tbl_sales_products.id as salesProductId, tbl_walkin_customer.customerAddress AS partyAddress, tbl_users.fname as soldBy, tbl_products.productName, tbl_products.type, tbl_products.productCode, tbl_sales_products.tbl_productsId,
                                tbl_brands.brandName, tbl_sales_products.quantity, tbl_sales_products.salesAmount, tbl_sales_products.totalAmount, tbl_sales_products.discount, tbl_warehouse.wareHouseName,tbl_sales_products.tbl_wareHouseId,
                                tbl_sales_products.grandTotal, tbl_sales_products.tbl_wareHouseId, tbl_sales.totalAmount AS salesTotalAmount, tbl_sales.productDiscount, tbl_sales.salesDiscount, 
                                tbl_sales.grandTotal as salesGrandTotal, tbl_sales.vat, tbl_sales.ait, IFNULL(dbtProductsReturn.quantity,0) AS returnedQuantity
                        FROM tbl_sales 
                        LEFT OUTER JOIN tbl_walkin_customer ON tbl_sales.tbl_customerId = tbl_walkin_customer.id AND tbl_walkin_customer.deleted='No'
                        LEFT OUTER JOIN tbl_users ON tbl_sales.tbl_userId=tbl_users.id AND tbl_users.deleted='No'
                        LEFT OUTER JOIN tbl_sales_products ON tbl_sales.id = tbl_sales_products.tbl_salesId AND tbl_sales_products.deleted='No'
                        LEFT OUTER JOIN tbl_warehouse ON tbl_sales_products.tbl_wareHouseId = tbl_warehouse.id AND tbl_warehouse.deleted='No'
                        LEFT OUTER JOIN tbl_products ON tbl_sales_products.tbl_productsId = tbl_products.id 
                        LEFT OUTER JOIN tbl_brands ON tbl_products.tbl_brandsId = tbl_brands.id
                        LEFT OUTER JOIN tbl_productspecification ON tbl_products.id = tbl_productspecification.tbl_productsId AND tbl_productspecification.deleted='No'
                        LEFT OUTER JOIN (SELECT SUM(tbl_sales_product_return.quantity) AS quantity, SUM(tbl_sales_product_return.totalAmount) AS totalAmount, tbl_sales_product_return.tbl_salesProductsId
                        FROM tbl_sales_return
                        INNER JOIN tbl_sales_product_return ON tbl_sales_product_return.tbl_sales_return_id = tbl_sales_return.id AND tbl_sales_product_return.deleted = 'No'
                        WHERE tbl_sales_return.tbl_sales_id = '" . $id . "' AND tbl_sales_return.deleted = 'No'
                        GROUP BY tbl_sales_product_return.tbl_products_id) AS dbtProductsReturn ON tbl_sales_products.id = dbtProductsReturn.tbl_salesProductsId
                        WHERE tbl_sales.type = '" . $type . "' AND tbl_sales.id='" . $id . "' AND tbl_sales.deleted = 'No'
                        GROUP BY tbl_sales_products.id";
        } else if ($type == 'FS') {
            $sql = "SELECT  tbl_sales.salesOrderNo, tbl_sales.id as tbl_sales_id, tbl_sales.salesDate,tbl_sales.tbl_customerId, tbl_sales.requisitionNo, tbl_party.partyName, tbl_party.partyPhone, 
                                tbl_sales_products.id as salesProductId, tbl_party.partyAddress, tbl_users.fname as soldBy, tbl_products.productName, tbl_products.type, tbl_products.productCode, tbl_sales_products.tbl_productsId,
                                tbl_brands.brandName, tbl_sales_products.quantity, tbl_sales_products.salesAmount, tbl_sales_products.totalAmount, tbl_sales_products.discount,  tbl_warehouse.wareHouseName,tbl_sales_products.tbl_wareHouseId,
                                tbl_sales_products.grandTotal, tbl_sales_products.tbl_wareHouseId, tbl_sales.totalAmount AS salesTotalAmount, tbl_sales.productDiscount, tbl_sales.salesDiscount, 
                                tbl_sales.grandTotal as salesGrandTotal, tbl_sales.vat, tbl_sales.ait, IFNULL(dbtProductsReturn.quantity,0) AS returnedQuantity, 
                                GROUP_CONCAT(CONCAT(tbl_productspecification.specificationName,': ',tbl_productspecification.specificationValue) SEPARATOR ', ') AS productSpecification
                        FROM tbl_sales 
                        LEFT OUTER JOIN tbl_party ON tbl_sales.tbl_customerId = tbl_party.id AND tbl_party.deleted='No'
                        LEFT OUTER JOIN tbl_users ON tbl_sales.tbl_userId=tbl_users.id AND tbl_users.deleted='No'
                        LEFT OUTER JOIN tbl_sales_products ON tbl_sales.id = tbl_sales_products.tbl_salesId AND tbl_sales_products.deleted='No'
                        LEFT OUTER JOIN tbl_warehouse ON tbl_sales_products.tbl_wareHouseId = tbl_warehouse.id AND tbl_warehouse.deleted='No'
                        LEFT OUTER JOIN tbl_products ON tbl_sales_products.tbl_productsId = tbl_products.id 
                        LEFT OUTER JOIN tbl_brands ON tbl_products.tbl_brandsId = tbl_brands.id
                        LEFT OUTER JOIN tbl_productspecification ON tbl_products.id = tbl_productspecification.tbl_productsId AND tbl_productspecification.deleted='No'
                        LEFT OUTER JOIN (SELECT SUM(tbl_sales_product_return.quantity) AS quantity, SUM(tbl_sales_product_return.totalAmount) AS totalAmount, tbl_sales_product_return.tbl_salesProductsId
                        FROM tbl_sales_return
                        INNER JOIN tbl_sales_product_return ON tbl_sales_product_return.tbl_sales_return_id = tbl_sales_return.id AND tbl_sales_product_return.deleted = 'No'
                        WHERE tbl_sales_return.tbl_sales_id = '$id' AND tbl_sales_return.deleted = 'No'
                        GROUP BY tbl_sales_product_return.tbl_products_id) AS dbtProductsReturn ON tbl_sales_products.id = dbtProductsReturn.tbl_salesProductsId
                        WHERE tbl_sales.type = 'TS' AND tbl_sales.id='$id' AND tbl_sales.deleted = 'No'
                        GROUP BY tbl_sales_products.id";
        }
        $query = $conn->query($sql);
        while ($row = $query->fetch_assoc()) {
            $rows[] = $row;
        }
        echo json_encode($rows);
        //=========== Start Serialize Product Return ===========//
    } else if ($_POST['action'] == "showSerializTable") {
        $rows = '';
        $tbl_sales_id =  $_POST['tbl_sales_id'];
        $product_id =  $_POST['product_id'];
        $warehouse_id =  $_POST['warehouseId'];
        $totalQuantityForReturn = 0;
        $tbl_serialize_productsIdArray = [];
        $serializeProducts = [];
        $saleSerializeProducts = [];
        // Start Sale Serialize Products
        /* $sql_sale_serialize_products = "SELECT id, sale_id, warehouse_id, tbl_serialize_products_id, sale_quantity, returned_quantity
                                        FROM sale_serialize_products
                                        WHERE sale_id = '$tbl_sales_id' AND warehouse_id='$warehouse_id' AND product_id='$product_id' AND deleted='No'
                                        ORDER BY id";
        $result_SaleSerializeProducts = $conn->query($sql_sale_serialize_products);
        if ($result_SaleSerializeProducts->num_rows > 0) {
            while ($rowData = mysqli_fetch_assoc($result_SaleSerializeProducts)) {
                $saleSerializeProducts[] = $rowData;
            }
        } */
        // End Sale Serialize Products

        $sql_serializeProducts = "SELECT id, warehouse_id, quantity, used_quantity FROM tbl_serialize_products
        WHERE  tbl_productsId='$product_id' AND warehouse_id='$warehouse_id' AND deleted='No' AND quantity>used_quantity AND is_sold='ON'";
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
        echo json_encode(array('serializeProducts' => $serializeProducts, "tbl_serialize_productsIdArray" => $tbl_serialize_productsIdArray, "saleSerializeProducts" => $saleSerializeProducts));
        //=========== End Serialize Product Return ===========//
    } else if ($_POST['action'] == "saveSalesReturn") {
        $error = 0;
        $totalAmount = 0;
        $loginID = $_SESSION['user'];
        $returnDate = $_POST['returnDate'];
        $salesId = $_POST['salesId'];
        $salesType = $_POST['salesType'];
        $salesCode = $_POST['salesCode'];
        $customerId = $_POST['customerId'];
        $wareHouseId = $_POST['wareHouseId'];
        $quantity = $_POST['quantity'];
        $salesProductsId = $_POST['salesProductsId'];
        $quantityArray = explode("@!@,", $quantity);
        $salesProductsIdArray = explode("@!@,", $salesProductsId);

        // Serialize Product Return
        $stockQuantities = $_POST['stockQuantities'];
        $stockQuantityArray = explode(",", $stockQuantities);
        $tbl_serialize_productsIds = $_POST['tbl_serialize_productsIds'];
        $tbl_serialize_productsIdsArray = explode(",", $tbl_serialize_productsIds);
        $newStockQuantities = $_POST['newStockQuantities'];
        $newStockQuantityArray = explode(",", $newStockQuantities);
        // End Serialize Product Return

        try {
            $conn->begin_transaction();

            $sql = "SELECT LPAD(max(salesReturnOrderNo)+1, 6, 0) as salesReturnOrderNo 
        	            FROM tbl_sales_return 
                        WHERE salesType='$salesType'";
            $query = $conn->query($sql);
            $salesReturnOrderNo = '';
            while ($prow = $query->fetch_assoc()) {
                $salesReturnOrderNo = $prow['salesReturnOrderNo'];
            }
            if ($salesReturnOrderNo == '') {
                $salesReturnOrderNo = '000001';
            }
            $sql = "INSERT INTO tbl_sales_return(tbl_sales_id, tbl_customer_id, salesType, salesReturnOrderNo, returnDate, entryBy, remarks,entryDate) 
            	        VALUES ('$salesId','$customerId','$salesType','$salesReturnOrderNo','$returnDate','$loginID','Return of sales code: $salesCode','$toDay')";
            if ($conn->query($sql)) {
                $salesReturnId = $conn->insert_id;
                for ($i = 0; $i < count($salesProductsIdArray); $i++) {
                    $quantityEntry = $quantityArray[$i];
                    if ($quantityEntry > 0) {
                        $salesProductsIdEntry = $salesProductsIdArray[$i];
                        if ($i == count($quantityArray) - 1) {
                            $quantityEntry = substr($quantityEntry, 0, strlen($quantityEntry) - 3);
                            $salesProductsIdEntry = substr($salesProductsIdEntry, 0, strlen($salesProductsIdEntry) - 3);
                        }
                        if ($quantityEntry != '' && $quantityEntry > 0) {
                            $sql = "INSERT INTO tbl_sales_product_return (tbl_sales_return_id, tbl_products_id, quantity, units, salePrice, totalAmount, tbl_wareHouseId, entryBy, remarks, discount, grandTotal, tbl_salesProductsId,entryDate) 
            	                        SELECT '$salesReturnId', tbl_productsId, '$quantityEntry', units, salesAmount, totalAmount, tbl_wareHouseId, '$loginID', remarks, discount, grandTotal*($quantityEntry/quantity), id ,'$toDay'
                                        FROM tbl_sales_products 
                                        WHERE id='$salesProductsIdEntry' AND deleted = 'No'";
                            if ($conn->query($sql)) {
                                $returnProductsId = $conn->insert_id;
                                $sql = "SELECT tbl_products_id, salePrice, grandTotal, tbl_wareHouseId  
        						            FROM tbl_sales_product_return 
        						            WHERE id='$returnProductsId'";
                                $res = $conn->query($sql);
                                if ($res) {
                                    $productsId = '';
                                    $wareHouseId = '';
                                    while ($row = $res->fetch_assoc()) {
                                        $productsId = $row['tbl_products_id'];
                                        $wareHouseId = $row['tbl_wareHouseId'];
                                        $totalAmount = $totalAmount + $row['grandTotal'];
                                    }
                                    //If purchase products return entry then update the current stock
                                    $sql = "UPDATE tbl_currentStock 
                                                    set salesReturnStock=salesReturnStock+$quantityEntry, currentStock=currentStock+$quantityEntry,lastUpdatedDate='$toDay',lastUpdatedBy='$loginID'
                                                    where tbl_productsId = '$productsId' AND tbl_wareHouseId='$wareHouseId'";
                                    $res = $conn->query($sql);
                                    if (!$res) {
                                        $error = $error + 1;
                                        break;
                                    }
                                    if ($conn->affected_rows == 0) {
                                        $sql = "insert into tbl_currentStock (salesReturnStock, currentStock, tbl_productsId, tbl_wareHouseId, entryBy,enteryDate) 
                                			        values ('$quantityEntry', '$quantityEntry','$productsId','$wareHouseId', '$loginID','$toDay')";
                                        $res = $conn->query($sql);
                                        if (!$res) {
                                            $error = $error + 1;
                                            break;
                                        }
                                    }
                                    $sql = "UPDATE tbl_sales_products 
                                		            SET returnQuantity = returnQuantity+$quantityEntry 
                            		            WHERE id='$salesProductsIdEntry'";
                                    $conn->query($sql);
                                } else {
                                    $error = $error + 1;
                                    $conn->rollBack();
                                    echo json_encode('Error: ' . $conn->error . $sql);
                                    break;
                                }
                            } else {
                                $error = $error + 1;
                                $conn->rollBack();
                                echo json_encode('Error: ' . $conn->error . $sql);
                                break;
                            }
                        }
                    }
                }    //End for loop

                //====================== Start Serialize Product Sales Return ======================//
                //===== Update in Existing table =====//
                foreach ($tbl_serialize_productsIdsArray as $key => $tbl_serialize_productsId) {
                    if (strpos($tbl_serialize_productsId, '@') == true) {
                        $productIdAndWarahouseId = explode("@", $tbl_serialize_productsId);
                        continue;
                    }
                    $returnQuantity = $stockQuantityArray[$key];
                    // Update Quantity 
                    if ($returnQuantity > 0) {
                        $update_serialize = "UPDATE tbl_serialize_products set quantity=quantity+$returnQuantity, is_sold='ON' 
                                                   where id='$tbl_serialize_productsId'";
                        $updateResult = $conn->query($update_serialize);
                        if ($updateResult) {
                            $returnSql = "INSERT INTO tbl_sale_serialize_products_return (tbl_name, tbl_id, tbl_serialize_products_id, return_info, returned_quantity, salesType, created_by, created_date) 
                                		        values ('tbl_sales_return','$salesReturnId','$tbl_serialize_productsId','Old','$returnQuantity','$salesType','$loginID','$toDay')";
                            $result = $conn->query($returnSql);
                        }
                    }
                    // End
                }
                //===== End Update in Existing table =====//

                //===== Insert Newly =====//
                $tempProductId = 0;
                $tempWarehouseId = 0;
                $maxNumber = 0;
                foreach ($newStockQuantityArray as $key => $newStockQuantity) {
                    if (strpos($newStockQuantity, '@') == true) {
                        $productIdAndWarahouseId = explode("@", $newStockQuantity);
                        $tempProductId = $productIdAndWarahouseId[0];
                        $tempWarehouseId = $productIdAndWarahouseId[1];
                        // Generate Serial Number
                        $sql_serializeProduct = "select max(serial_no) as serial from tbl_serialize_products where tbl_productsId='$tempProductId'";
                        $query_serializeProduct = $conn->query($sql_serializeProduct);
                        $row_serializeProduct = $query_serializeProduct->fetch_assoc();
                        $maxNumber = $row_serializeProduct['serial'];
                        // End
                        continue;
                    }
                    $returnQuantity = $newStockQuantity;
                    // Insert 
                    if ($returnQuantity > 0) {
                        $maxNumber++;
                        $sql_insert_serialize = "INSERT INTO `tbl_serialize_products`(`tbl_productsId`, `warehouse_id`, `serial_no`, `quantity`, `created_by`, `created_date`) 
                                                  VALUES ('$tempProductId','$tempWarehouseId','$maxNumber','$returnQuantity','$loginID','$toDay')";
                        $insertResult = $conn->query($sql_insert_serialize);
                        if ($insertResult) {
                            $serializeProductId = $conn->insert_id;
                            $returnSql = "INSERT INTO tbl_sale_serialize_products_return (tbl_name, tbl_id, tbl_serialize_products_id, return_info, returned_quantity, salesType, created_by, created_date) 
                                		              values ('tbl_sales_return','$salesReturnId','$serializeProductId','New','$returnQuantity','$salesType','$loginID','$toDay')";
                            $result = $conn->query($returnSql);
                        }
                    }
                    // End
                }
                //===== End Insert Newly =====//
                //====================== End Serialize Product Sales Return ======================//

                if ($error == 0) {
                    if ($salesType == 'WalkinSale') {
                        $customerType = 'WalkinCustomer';
                    } else {
                        $customerType = 'Party';
                    }
                    $sql = "SELECT LPAD(IFNULL(max(voucherNo),0)+1, 6, 0) as voucherCode, LPAD(IFNULL(max(voucherNo),0)+2, 6, 0) as voucherReceiveCode FROM tbl_paymentVoucher WHERE tbl_partyId='$customerId' AND customerType = '$customerType'";
                    $query = $conn->query($sql);
                    while ($prow = $query->fetch_assoc()) {
                        $voucherNo = $prow['voucherCode'];
                        $voucherReceiveNo = $prow['voucherReceiveCode'];
                    }
                    if ($voucherNo == "") {
                        $voucherNo = "000001";
                        $voucherReceiveNo = "000002";
                    }
                    if ($totalAmount > 0) {
                        $sql = "INSERT INTO tbl_paymentVoucher (tbl_partyId, tbl_sales_return_id, amount, entryBy, paymentMethod, paymentDate, status, remarks, type, voucherType, voucherNo, customerType,entryDate) 
        							VALUES ('$customerId', '$salesReturnId', '$totalAmount', '$loginID', 'Cash', '$returnDate', 'Active', 'Sales return payment adjustment for sales code: $salesCode and sales return code: $salesReturnOrderNo', 'payable', 'SalesReturn', '$voucherNo', '$customerType','$toDay')";
                        $res = $conn->query($sql);

                        if (!$res) {

                            $error = $error + 1;
                            echo json_encode('Error: ' . $conn->error . $sql);
                        }
                    }
                    if ($error == 0) {
                        //echo json_encode('Success');
                        $data = array(
                            'msg' => 'Success',
                            'returnId' => $salesReturnId
                        );
                        echo json_encode($data);
                        $conn->commit();
                    }
                }
            } else {
                $conn->rollBack();
                echo json_encode('Error: ' . $conn->error . $sql);
            }
        } catch (Exception $e) {
            $conn->rollBack();
            echo json_encode('Error');
        }
    } else if ($_POST['action'] == "salesReturnDelete") {
        try {
            $id = $_POST['id'];
            $loginID = $_SESSION['user'];
            $tsFlag = 'No';
            $sql = "SELECT tbl_sales_product_return.id,tbl_sales_product_return.salePrice, tbl_sales_product_return.quantity, tbl_sales_product_return.tbl_products_id, 
                            tbl_sales_product_return.tbl_wareHouseId,tbl_sales_product_return.totalAmount,  tbl_sales_return.tbl_customer_id , tbl_sales_return.tbl_sales_id, tbl_sales_return.salesType, tbl_sales_product_return.tbl_salesProductsId
                        FROM tbl_sales_product_return
                        LEFT OUTER JOIN tbl_sales_return ON tbl_sales_product_return.tbl_sales_return_id = tbl_sales_return.id
                        WHERE tbl_sales_product_return.tbl_sales_return_id='$id' AND tbl_sales_product_return.deleted='No'";
            $res = $conn->query($sql);
            $conn->begin_transaction();
            $sql = "UPDATE tbl_sales_return 
                        SET deleted='Yes', deletedDate='$toDay', deletedBy='$loginID'
                        WHERE id='$id'";
            if ($conn->query($sql)) {
                $totalAmount = 0;
                $customerId = '';
                $productsId = '';
                $wareHouseId = '';
                $quantity = '';
                $purchaseId = '';
                while ($row = $res->fetch_assoc()) {
                    $totalAmount += $row['totalAmount'];
                    $customerId = $row['tbl_customer_id'];
                    $productsId = $row['tbl_products_id'];
                    $wareHouseId = $row['tbl_wareHouseId'];
                    $quantity = $row['quantity'];
                    $salesId = $row['tbl_sales_id'];
                    $sql = "UPDATE tbl_currentStock 
                                set salesReturnDelete=salesReturnDelete+$quantity, currentStock=currentStock-$quantity,lastUpdatedDate='$toDay',lastUpdatedBy='$loginID'
                                where tbl_productsId = '$productsId' AND tbl_wareHouseId='$wareHouseId'";
                    $conn->query($sql);
                    if ($row['salesType'] == 'TS') {
                        $tsFlag = 'Yes';
                        $TSSalesProductsId = $row['tbl_salesProductsId'];
                        $sql = "UPDATE tbl_tsalesproducts
                                    SET returnedQuantity = returnedQuantity - $quantity, status='Running'
                                    WHERE id = '$TSSalesProductsId'";
                        $conn->query($sql);
                    }
                }
                if ($tsFlag != 'Yes') {
                    $sql = "UPDATE tbl_paymentVoucher 
                                SET deleted='Yes', deletedBy='$loginID', deletedDate='$toDay'
                                WHERE tbl_sales_return_id='$id'";
                    $conn->query($sql);

                    $sql = "UPDATE tbl_sales_products 
                		            SET returnQuantity -= $quantity
            		            WHERE tbl_salesId='$salesId' AND
                                    tbl_productsId='$productsId'  AND
                                    tbl_wareHouseId='$wareHouseId'";
                    $conn->query($sql);
                }
            }
            $conn->commit();
            echo json_encode('Success');
        } catch (Exception $e) {
            $conn->rollBack();
            echo 'RollBack';
        }
        $conn->close();
    } else if ($_POST['action'] == "TSSalesReturn") {
        try {
            $id = $_POST['id'];
            $loginID = $_SESSION['user'];
            $tsFlag = 'No';
            $sql = "SELECT tbl_sales_product_return.id,tbl_sales_product_return.salePrice, tbl_sales_product_return.quantity, tbl_sales_product_return.tbl_products_id, 
                            tbl_sales_product_return.tbl_wareHouseId,tbl_sales_product_return.totalAmount,  tbl_sales_return.tbl_customer_id , tbl_sales_return.tbl_sales_id, tbl_sales_return.salesType, tbl_sales_product_return.tbl_salesProductsId
                        FROM tbl_sales_product_return
                        LEFT OUTER JOIN tbl_sales_return ON tbl_sales_product_return.tbl_sales_return_id = tbl_sales_return.id
                        WHERE tbl_sales_product_return.tbl_sales_return_id='$id' AND tbl_sales_product_return.deleted='No'";
            $res = $conn->query($sql);
            $conn->begin_transaction();
            $sql = "UPDATE tbl_sales_return 
                        SET deleted='Yes', deletedDate='$toDay', deletedBy='$loginID'
                        WHERE id='$id'";
            if ($conn->query($sql)) {

                $totalAmount = 0;
                $customerId = '';
                $productsId = '';
                $wareHouseId = '';
                $quantity = '';
                $purchaseId = '';
                while ($row = $res->fetch_assoc()) {
                    $totalAmount += $row['totalAmount'];
                    $customerId = $row['tbl_customer_id'];
                    $productsId = $row['tbl_products_id'];
                    $wareHouseId = $row['tbl_wareHouseId'];
                    $quantity = $row['quantity'];
                    $salesId = $row['tbl_sales_id'];
                    $sql = "UPDATE tbl_currentStock 
                                set salesReturnDelete=salesReturnDelete+$quantity, currentStock=currentStock-$quantity,lastUpdatedDate='$toDay',lastUpdatedBy='$loginID'
                                where tbl_productsId = '$productsId' AND tbl_wareHouseId='$wareHouseId'";
                    $conn->query($sql);
                    if ($row['salesType'] == 'TS') {
                        $tsFlag = 'Yes';
                        //===== Start Updated For TS Return Delete (Hamid) =====//
                        $tbl_salesProductsId = $row['tbl_salesProductsId'];
                        $sql = "UPDATE tbl_tsalesproducts 
                                set returnedQuantity=returnedQuantity-$quantity, status='Running', lastUpdatedDate='$toDay',lastUpdatedBy='$loginID'
                                where  id= '$tbl_salesProductsId'";
                        $conn->query($sql);
                        //===== End Updated For TS Return Delete (Hamid) =====//

                    }
                }
                if ($tsFlag != 'Yes') {
                    $sql = "UPDATE tbl_paymentVoucher 
                                SET deleted='Yes', deletedBy='$loginID', deletedDate='$toDay'
                                WHERE tbl_sales_return_id='$id'";
                    $conn->query($sql);

                    $sql = "UPDATE tbl_sales_products 
                		            SET returnQuantity -= $quantity
            		            WHERE tbl_salesId='$salesId' AND
                                    tbl_productsId='$productsId'  AND
                                    tbl_wareHouseId='$wareHouseId'";
                    $conn->query($sql);
                }
            }
            $sql = "UPDATE tbl_sales_product_return 
                        SET deleted='Yes', deletedDate='$toDay', deletedBy='$loginID'
                        WHERE tbl_sales_return_id='$id'";
            $conn->query($sql);




            //====================== Start Serialize Product Sales Return Delete ======================//
            $sql = "SELECT tbl_serialize_products_id, returned_quantity, return_info
                    FROM tbl_sale_serialize_products_return
                    WHERE tbl_name='tbl_sales_return' AND tbl_id='$id' AND deleted='No'";
            $returnResult = $conn->query($sql);

            while ($row = $returnResult->fetch_assoc()) {
                $return_info = $row['return_info'];
                $tbl_serialize_products_id = $row['tbl_serialize_products_id'];
                $returned_quantity = $row['returned_quantity'];
                if ($return_info == "Old") {
                    $sql = "UPDATE tbl_serialize_products set used_quantity=used_quantity+$returned_quantity, is_sold='ON'
                            where id ='$tbl_serialize_products_id' AND deleted='No'";
                    $conn->query($sql);
                } else {
                    $sql = "UPDATE tbl_serialize_products set deleted='Yes', deleted_by='$loginID', deleted_date='$toDay'
                            WHERE id='$tbl_serialize_products_id'";
                    $conn->query($sql);
                }
            }
            $deleteSql = "UPDATE tbl_sale_serialize_products_return set deleted='Yes', deleted_by='$loginID', deleted_date='$toDay'
                          WHERE tbl_name='tbl_sales_return' AND tbl_id='$id' AND deleted='No'";
            $conn->query($deleteSql);
            //====================== End Serialize Product Sales Return Delete ======================//

            $conn->commit();
            echo json_encode('Success');
        } catch (Exception $e) {
            $conn->rollBack();
            echo 'RollBack';
        }
        $conn->close();
    }
} else if (isset($_GET['salesType'])) {
    $getType = $_GET['salesType'];
    if ($getType == "PartySale") {
        if ($_GET['sortData'] == "0,0") {
            $sql = "SELECT tbl_sales_return.id, tbl_sales_return.returnDate, tbl_sales_return.salesReturnOrderNo,  tbl_sales.salesOrderNo, tbl_party.partyName, tbl_party.partyPhone, SUM(tbl_sales_product_return.grandTotal) AS totalAmount, tbl_sales_product_return.tbl_products_id, 
                            CONCAT('<ul>',GROUP_CONCAT(CONCAT('<li>', tbl_products.productName, ' - ', tbl_products.productCode, ' (', tbl_sales_product_return.quantity, ' ', tbl_units.unitName, ')') SEPARATOR '</li>'),'</li></ul>') AS productDetails
                        FROM tbl_sales_return
                        LEFT OUTER JOIN tbl_sales ON tbl_sales_return.tbl_sales_id=tbl_sales.id AND tbl_sales.deleted = 'No'
                        LEFT OUTER JOIN tbl_sales_product_return ON tbl_sales_product_return.tbl_sales_return_id = tbl_sales_return.id AND tbl_sales_product_return.deleted='No'
                        LEFT OUTER JOIN tbl_party ON tbl_sales_return.tbl_customer_id = tbl_party.id
                        LEFT OUTER JOIN tbl_products ON tbl_sales_product_return.tbl_products_id = tbl_products.id AND tbl_products.deleted='No'
                        LEFT OUTER JOIN tbl_units ON tbl_products.units = tbl_units.id
                        WHERE tbl_sales_return.salesType='PartySale' AND tbl_sales_return.deleted='No'
                        GROUP BY tbl_sales_return.id
                        ORDER BY tbl_sales_return.id DESC";
        } else {
            $dates = explode(",", $_GET['sortData']);
            $sql = "SELECT tbl_sales_return.id, tbl_sales_return.returnDate, tbl_sales_return.salesReturnOrderNo,  tbl_sales.salesOrderNo, tbl_party.partyName, tbl_party.partyPhone, SUM(tbl_sales_product_return.grandTotal) AS totalAmount, tbl_sales_product_return.tbl_products_id, 
                            CONCAT('<ul>',GROUP_CONCAT(CONCAT('<li>', tbl_products.productName, ' - ', tbl_products.productCode, ' (', tbl_sales_product_return.quantity, ' ', tbl_units.unitName, ')') SEPARATOR '</li>'),'</li></ul>') AS productDetails
                        FROM tbl_sales_return
                        LEFT OUTER JOIN tbl_sales ON tbl_sales_return.tbl_sales_id=tbl_sales.id AND tbl_sales.deleted = 'No'
                        LEFT OUTER JOIN tbl_sales_product_return ON tbl_sales_product_return.tbl_sales_return_id = tbl_sales_return.id AND tbl_sales_product_return.deleted='No'
                        LEFT OUTER JOIN tbl_party ON tbl_sales_return.tbl_customer_id = tbl_party.id
                        LEFT OUTER JOIN tbl_products ON tbl_sales_product_return.tbl_products_id = tbl_products.id AND tbl_products.deleted='No'
                        LEFT OUTER JOIN tbl_units ON tbl_products.units = tbl_units.id
                        WHERE tbl_sales_return.salesType='PartySale' AND tbl_sales_return.deleted='No' AND tbl_sales_return.returnDate BETWEEN '" . $dates[0] . "' AND '" . $dates[1] . "' 
                        GROUP BY tbl_sales_return.id
                        ORDER BY tbl_sales_return.id DESC";
        }
        $result = $conn->query($sql);
        $i = 1;
        $output = array('data' => array());
        while ($row = $result->fetch_array()) {
            $salesReturnId = $row['id'];
            $button = '	<div class="btn-group">
						<button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
						<i class="fa fa-gear tiny-icon"></i>  <span class="caret"></span></button>
						<ul class="dropdown-menu dropdown-menu-right" style="border: 1px solid gray;" role="menu">
						    <li><a href="#" onclick="salesReport(' . $salesReturnId . ',\'partySaleReturn\')"><i class="fa fa-print tiny-icon"></i> View Sales Return</a></li>';
            if (strtolower($_SESSION['userType']) == "admin" || strtolower($_SESSION['userType']) == 'super admin') {
                $button .=  '<li><a href="#" onclick="deleteSalesReturn(' . $salesReturnId . ')"><i class="fa fa-edit tiny-icon"></i>Delete</a></li>';
            }
            $button .=        '</ul>
        					</div>';
            $output['data'][] = array(
                $i++,
                $row['returnDate'],
                $row['salesReturnOrderNo'],
                $row['salesOrderNo'],
                $row['partyName'] . ' - ' . $row['partyPhone'],
                $row['productDetails'],
                $row['totalAmount'],
                $button
            );
        } // /while 
        echo json_encode($output);
    } else if ($getType == "WalkinSale") {
        if ($_GET['sortData'] == "0,0") {
            $sql = "SELECT tbl_sales_return.id, tbl_sales_return.returnDate, tbl_sales_return.salesReturnOrderNo,tbl_sales_return.salesType,  tbl_sales.salesOrderNo, tbl_walkin_customer.customerName, 
                            tbl_walkin_customer.phoneNo, SUM(tbl_sales_product_return.grandTotal) AS totalAmount, tbl_sales_product_return.tbl_products_id, 
                            CONCAT('<ul>',GROUP_CONCAT(CONCAT('<li>', tbl_products.productName, ' - ', tbl_products.productCode, ' (', tbl_sales_product_return.quantity, ' ', tbl_units.unitName, ')') SEPARATOR '</li>'),'</li></ul>') AS productDetails
                        FROM tbl_sales_return
                        LEFT OUTER JOIN tbl_sales ON tbl_sales_return.tbl_sales_id=tbl_sales.id AND tbl_sales.deleted = 'No'
                        LEFT OUTER JOIN tbl_sales_product_return ON tbl_sales_product_return.tbl_sales_return_id = tbl_sales_return.id AND tbl_sales_product_return.deleted='No'
                        LEFT OUTER JOIN tbl_walkin_customer ON tbl_sales_return.tbl_customer_id = tbl_walkin_customer.id
                        LEFT OUTER JOIN tbl_products ON tbl_sales_product_return.tbl_products_id = tbl_products.id AND tbl_products.deleted='No'
                        LEFT OUTER JOIN tbl_units ON tbl_products.units = tbl_units.id
                        WHERE tbl_sales_return.salesType='WalkinSale' AND tbl_sales_return.deleted='No'
                        GROUP BY tbl_sales_return.id
                        ORDER BY tbl_sales_return.id DESC";
        } else {
            $dates = explode(",", $_GET['sortData']);
            $sql = "SELECT tbl_sales_return.id, tbl_sales_return.returnDate, tbl_sales_return.salesReturnOrderNo,tbl_sales_return.salesType,  tbl_sales.salesOrderNo, tbl_walkin_customer.customerName, 
                            tbl_walkin_customer.phoneNo, SUM(tbl_sales_product_return.grandTotal) AS totalAmount, tbl_sales_product_return.tbl_products_id, 
                            CONCAT('<ul>',GROUP_CONCAT(CONCAT('<li>', tbl_products.productName, ' - ', tbl_products.productCode, ' (', tbl_sales_product_return.quantity, ' ', tbl_units.unitName, ')') SEPARATOR '</li>'),'</li></ul>') AS productDetails
                        FROM tbl_sales_return
                        LEFT OUTER JOIN tbl_sales ON tbl_sales_return.tbl_sales_id=tbl_sales.id AND tbl_sales.deleted = 'No'
                        LEFT OUTER JOIN tbl_sales_product_return ON tbl_sales_product_return.tbl_sales_return_id = tbl_sales_return.id AND tbl_sales_product_return.deleted='No'
                        LEFT OUTER JOIN tbl_walkin_customer ON tbl_sales_return.tbl_customer_id = tbl_walkin_customer.id
                        LEFT OUTER JOIN tbl_products ON tbl_sales_product_return.tbl_products_id = tbl_products.id AND tbl_products.deleted='No'
                        LEFT OUTER JOIN tbl_units ON tbl_products.units = tbl_units.id
                        WHERE tbl_sales_return.salesType='WalkinSale' AND tbl_sales_return.deleted='No' AND tbl_sales_return.returnDate BETWEEN '" . $dates[0] . "' AND '" . $dates[1] . "' 
                        GROUP BY tbl_sales_return.id
                        ORDER BY tbl_sales_return.id DESC";
        }
        $result = $conn->query($sql);
        $i = 1;
        $output = array('data' => array());
        while ($row = $result->fetch_array()) {
            $salesReturnId = $row['id'];
            $button = '	<div class="btn-group">
						<button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
						<i class="fa fa-gear tiny-icon"></i>  <span class="caret"></span></button>
						<ul class="dropdown-menu dropdown-menu-right" style="border: 1px solid gray;" role="menu">
						    <li><a href="salesReturnViewDetails.php?id=' . $row['id'] . '&rType=' . $row['salesType'] . '" target="_blank"><i class="fa fa-print tiny-icon"></i> View Sales Return</a></li>';
            if (strtolower($_SESSION['userType']) == "admin" || strtolower($_SESSION['userType']) == 'super admin') {
                $button .=  '<li><a href="#" onclick="deleteSalesReturn(' . $salesReturnId . ')"><i class="fa fa-edit tiny-icon"></i>Delete</a></li>';
            }
            $button .=    '</ul>
        					</div>';
            $output['data'][] = array(
                $i++,
                $row['returnDate'],
                $row['salesReturnOrderNo'],
                $row['salesOrderNo'],
                $row['customerName'] . ' - ' . $row['phoneNo'],
                $row['productDetails'],
                $row['totalAmount'],
                $button
            );
        } // /while 
        echo json_encode($output);
    } else if ($getType == "TS") {
        if ($_GET['sortData'] == "0,0") {
            $sql = "SELECT tbl_sales_return.id, tbl_sales_return.returnDate, tbl_sales_return.salesReturnOrderNo,  tbl_sales.salesOrderNo, tbl_party.partyName, tbl_party.partyPhone, SUM(tbl_sales_product_return.grandTotal) AS totalAmount, tbl_sales_product_return.tbl_products_id, CONCAT('<ul>',GROUP_CONCAT(CONCAT('<li>', tbl_products.productName, ' - ', tbl_products.productCode, ' (', tbl_sales_product_return.quantity, ' ', tbl_units.unitName, ')') SEPARATOR '</li>'),'</li></ul>') AS productDetails
                        FROM tbl_sales_return
                        LEFT OUTER JOIN tbl_sales ON tbl_sales_return.tbl_sales_id=tbl_sales.id AND tbl_sales.deleted = 'No'
                        LEFT OUTER JOIN tbl_sales_product_return ON tbl_sales_product_return.tbl_sales_return_id = tbl_sales_return.id AND tbl_sales_product_return.deleted='No'
                        LEFT OUTER JOIN tbl_party ON tbl_sales_return.tbl_customer_id = tbl_party.id
                        LEFT OUTER JOIN tbl_products ON tbl_sales_product_return.tbl_products_id = tbl_products.id AND tbl_products.deleted='No'
                        LEFT OUTER JOIN tbl_units ON tbl_products.units = tbl_units.id
                        WHERE tbl_sales_return.salesType='TS' AND tbl_sales_return.deleted='No'
                        GROUP BY tbl_sales_return.id
                        ORDER BY tbl_sales_return.id DESC";
        } else {
            $dates = explode(",", $_GET['sortData']);
            $sql = "SELECT tbl_sales_return.id, tbl_sales_return.returnDate, tbl_sales_return.salesReturnOrderNo,  tbl_sales.salesOrderNo, tbl_party.partyName, tbl_party.partyPhone, SUM(tbl_sales_product_return.grandTotal) AS totalAmount, tbl_sales_product_return.tbl_products_id, CONCAT('<ul>',GROUP_CONCAT(CONCAT('<li>', tbl_products.productName, ' - ', tbl_products.productCode, ' (', tbl_sales_product_return.quantity, ' ', tbl_units.unitName, ')') SEPARATOR '</li>'),'</li></ul>') AS productDetails
                        FROM tbl_sales_return
                        LEFT OUTER JOIN tbl_sales ON tbl_sales_return.tbl_sales_id=tbl_sales.id AND tbl_sales.deleted = 'No'
                        LEFT OUTER JOIN tbl_sales_product_return ON tbl_sales_product_return.tbl_sales_return_id = tbl_sales_return.id AND tbl_sales_product_return.deleted='No'
                        LEFT OUTER JOIN tbl_party ON tbl_sales_return.tbl_customer_id = tbl_party.id
                        LEFT OUTER JOIN tbl_products ON tbl_sales_product_return.tbl_products_id = tbl_products.id AND tbl_products.deleted='No'
                        LEFT OUTER JOIN tbl_units ON tbl_products.units = tbl_units.id
                        WHERE tbl_sales_return.salesType='TS' AND tbl_sales_return.deleted='No' AND tbl_sales_return.returnDate BETWEEN '" . $dates[0] . "' AND '" . $dates[1] . "'  
                        GROUP BY tbl_sales_return.id
                        ORDER BY tbl_sales_return.id DESC";
        }
        $result = $conn->query($sql);
        $i = 1;
        $output = array('data' => array());
        while ($row = $result->fetch_array()) {
            $salesReturnId = $row['id'];
            $button = '	<div class="btn-group">
						<button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
						<i class="fa fa-gear tiny-icon"></i>  <span class="caret"></span></button>
						<ul class="dropdown-menu dropdown-menu-right" style="border: 1px solid gray;" role="menu">
						    <li><a href="tsSalesReturnViewDetails.php?id=' . $row['id'] . '&rType=' . $getType . '" target="_blank"><i class="fa fa-print tiny-icon"></i> View Sales Return</a></li>';
            if (strtolower($_SESSION['userType']) == "admin" || strtolower($_SESSION['userType']) == 'super admin') {
                $button .=  '<li><a href="#" onclick="deleteSalesReturn(' . $salesReturnId . ')"><i class="fa fa-edit tiny-icon"></i>Delete</a></li>';
            }
            $button .=        '</ul>
        					</div>';
            $output['data'][] = array(
                $i++,
                $row['returnDate'],
                $row['salesReturnOrderNo'],
                $row['salesOrderNo'],
                $row['partyName'] . ' - ' . $row['partyPhone'],
                $row['productDetails'],
                $row['totalAmount'],
                $button
            );
        } // /while 
        echo json_encode($output);
    } else if ($getType == "FS") {
        if ($_GET['sortData'] == "0,0") {
            $sql = "SELECT tbl_sales_return.id, tbl_sales_return.returnDate, tbl_sales_return.salesReturnOrderNo,  tbl_sales.salesOrderNo, tbl_party.partyName, tbl_party.partyPhone, SUM(tbl_sales_product_return.grandTotal) AS totalAmount, tbl_sales_product_return.tbl_products_id, 
                            CONCAT('<ul>',GROUP_CONCAT(CONCAT('<li>', tbl_products.productName, ' - ', tbl_products.productCode, ' (', tbl_sales_product_return.quantity, ' ', tbl_units.unitName, ')') SEPARATOR '</li>'),'</li></ul>') AS productDetails
                        FROM tbl_sales_return
                        LEFT OUTER JOIN tbl_sales ON tbl_sales_return.tbl_sales_id=tbl_sales.id AND tbl_sales.deleted = 'No'
                        LEFT OUTER JOIN tbl_sales_product_return ON tbl_sales_product_return.tbl_sales_return_id = tbl_sales_return.id AND tbl_sales_product_return.deleted='No'
                        LEFT OUTER JOIN tbl_party ON tbl_sales_return.tbl_customer_id = tbl_party.id
                        LEFT OUTER JOIN tbl_products ON tbl_sales_product_return.tbl_products_id = tbl_products.id AND tbl_products.deleted='No'
                        LEFT OUTER JOIN tbl_units ON tbl_products.units = tbl_units.id
                        WHERE tbl_sales_return.salesType='FS' AND tbl_sales_return.deleted='No'
                        GROUP BY tbl_sales_return.id
                        ORDER BY tbl_sales_return.id DESC";
        } else {
            $dates = explode(",", $_GET['sortData']);
            $sql = "SELECT tbl_sales_return.id, tbl_sales_return.returnDate, tbl_sales_return.salesReturnOrderNo,  tbl_sales.salesOrderNo, tbl_party.partyName, tbl_party.partyPhone, SUM(tbl_sales_product_return.grandTotal) AS totalAmount, tbl_sales_product_return.tbl_products_id, 
                            CONCAT('<ul>',GROUP_CONCAT(CONCAT('<li>', tbl_products.productName, ' - ', tbl_products.productCode, ' (', tbl_sales_product_return.quantity, ' ', tbl_units.unitName, ')') SEPARATOR '</li>'),'</li></ul>') AS productDetails
                        FROM tbl_sales_return
                        LEFT OUTER JOIN tbl_sales ON tbl_sales_return.tbl_sales_id=tbl_sales.id AND tbl_sales.deleted = 'No'
                        LEFT OUTER JOIN tbl_sales_product_return ON tbl_sales_product_return.tbl_sales_return_id = tbl_sales_return.id AND tbl_sales_product_return.deleted='No'
                        LEFT OUTER JOIN tbl_party ON tbl_sales_return.tbl_customer_id = tbl_party.id
                        LEFT OUTER JOIN tbl_products ON tbl_sales_product_return.tbl_products_id = tbl_products.id AND tbl_products.deleted='No'
                        LEFT OUTER JOIN tbl_units ON tbl_products.units = tbl_units.id
                        WHERE tbl_sales_return.salesType='FS' AND tbl_sales_return.deleted='No' AND tbl_sales_return.returnDate BETWEEN '" . $dates[0] . "' AND '" . $dates[1] . "' 
                        GROUP BY tbl_sales_return.id
                        ORDER BY tbl_sales_return.id DESC";
        }
        $result = $conn->query($sql);
        $i = 1;
        $output = array('data' => array());
        while ($row = $result->fetch_array()) {
            $salesReturnId = $row['id'];
            $button = '	<div class="btn-group">
						<button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
						<i class="fa fa-gear tiny-icon"></i>  <span class="caret"></span></button>
						<ul class="dropdown-menu dropdown-menu-right" style="border: 1px solid gray;" role="menu">
						    <li><a href="salesReturnViewDetails.php?id=' . $row['id'] . '&rType=' . $getType . '" target="_blank"><i class="fa fa-print tiny-icon"></i> View Sales Return</a></li>';
            if (strtolower($_SESSION['userType']) == "admin" || strtolower($_SESSION['userType']) == 'super admin') {
                $button .=  '<li><a href="#" onclick="deleteSalesReturn(' . $salesReturnId . ')"><i class="fa fa-edit tiny-icon"></i>Delete</a></li>';
            }
            $button .=        '</ul>
        					</div>';
            $output['data'][] = array(
                $i++,
                $row['returnDate'],
                $row['salesReturnOrderNo'],
                $row['salesOrderNo'],
                $row['partyName'] . ' - ' . $row['partyPhone'],
                $row['productDetails'],
                $row['totalAmount'],
                $button
            );
        } // /while 
        echo json_encode($output);
    }
}
