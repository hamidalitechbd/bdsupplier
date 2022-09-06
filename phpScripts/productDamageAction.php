<?php
$conPrefix = '../';
include $conPrefix . 'includes/session.php';

date_default_timezone_set('Asia/Dhaka');
//$toDay = (new DateTime($test))->format("Y-m-d H:i:s");
$toDay = (new DateTime())->format("Y-m-d");

if (isset($_POST['action'])) {
    if ($_POST['action'] == "saveDamage") {
        $loginID = $_SESSION['user'];
        $damageDate = $_POST['damageDate'];
        $damageProducts = $_POST['damageProducts'];
        $damageWareHouse = $_POST['damageWareHouse'];
        $currentStock = $_POST['currentStock'];
        $damageQuantity = $_POST['damageQuantity'];
        $damageRemarks = $_POST['damageRemarks'];
        // Serialize Product
        $product_type = $_POST['product_type'];
        if ($product_type == "serialize") {
            $stockQuantities = $_POST['stockQuantities'];
            $stockQuantityArray = explode(",", $stockQuantities);
            $TemptblSerializeProductsIdArray = $_POST['tbl_serialize_productsIdArray'];
            $tbl_serialize_productsIdsArray = explode(",", $TemptblSerializeProductsIdArray);
        }
        $damageId = 0;
        // End Serialize Product
        try {
            $conn->begin_transaction();
            $sql = "SELECT LPAD(max(damageOrderNo)+1, 6, 0) as damageOrderNo from tbl_damageProducts";
            $query = $conn->query($sql);
            while ($prow = $query->fetch_assoc()) {
                $damageOrderNo = $prow['damageOrderNo'];
            }
            if ($damageOrderNo == "") {
                $damageOrderNo = "000001";
            }
            $sql = "INSERT INTO tbl_damageProducts(tbl_productsId, tbl_wareHouseId, damageQuantity, remarks, damageDate, damageOrderNo, createdBy,createdDate) 
                    VALUES ('$damageProducts','$damageWareHouse','$damageQuantity','$damageRemarks','$damageDate','$damageOrderNo','$loginID','$toDay')";
            if ($conn->query($sql)) {
                $damageId = $conn->insert_id;
                $sql = "UPDATE tbl_currentStock 
					    set damageProducts=damageProducts+$damageQuantity, currentStock=currentStock-$damageQuantity, lastUpdatedDate='$toDay', lastUpdatedBy='$loginID'
					    where tbl_productsId = '$damageProducts' AND tbl_wareHouseId='$damageWareHouse' and deleted='No'";
                if ($conn->query($sql)) {
                    if ($conn->affected_rows == 0) {
                        $sql = "insert into tbl_currentStock 
        				            (damageProducts, currentStock, tbl_productsId, tbl_wareHouseId, entryBy,entryDate) values 
        				            ('$damageQuantity', '-$damageQuantity','$damageProducts','$damageWareHouse','$loginID','$toDay')";
                        $conn->query($sql);
                    }

                    //====================== Start Serialize Product ======================//
                    if ($product_type == "serialize" && $damageQuantity > 0) {

                        foreach ($tbl_serialize_productsIdsArray as $key => $tbl_serialize_productsId) {
                            $damageQty = $stockQuantityArray[$key];
                            if ($damageQty > 0 && $tbl_serialize_productsId > 0) {
                                // Update Quantity
                                $update_serialize = "UPDATE tbl_serialize_products set used_quantity=used_quantity+$damageQty where id='$tbl_serialize_productsId'";
                                $updateResult = $conn->query($update_serialize);
                                // End Update Quantity
                                if ($updateResult) {
                                    $returnSql = "INSERT INTO tbl_sale_serialize_products_return (tbl_name,tbl_id,tbl_serialize_products_id,returned_quantity, salesType, created_by, created_date) 
                                                          values ('tbl_damageProducts','$damageId','$tbl_serialize_productsId','$damageQty','Damage','$loginID','$toDay')";
                                    $result = $conn->query($returnSql);
                                }
                            }
                        }
                    }
                    //====================== End Serialize Product ======================//

                    $conn->commit();
                    echo json_encode('Success');
                } else {
                    $conn->rollBack();
                    echo json_encode($conn->error . $sql);
                }
            } else {
                $conn->rollBack();
                echo json_encode($conn->error . $sql);
            }
        } catch (Exception $e) {
            $conn->rollBack();
            echo json_encode('RollBack');
        }
        $conn->close();
    } else if ($_POST['action'] == "deleteDamage") {
        $loginID = $_SESSION['user'];
        $id = $_POST['id'];
        try {
            $conn->begin_transaction();
            $sql = "SELECT tbl_productsId, tbl_wareHouseId, damageQuantity  
                    FROM tbl_damageProducts 
                    WHERE id = '$id' AND deleted = 'No'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $productsId = $row['tbl_productsId'];
            $wareHouseId = $row['tbl_wareHouseId'];
            $damageQuantity = $row['damageQuantity'];
            $sql = "UPDATE tbl_currentStock 
                    SET damageDelete=damageDelete+$damageQuantity,deletedBy='$loginID',deletedDate='$toDay', 
                    	currentStock = currentStock+$damageQuantity 
                    WHERE tbl_wareHouseId='$wareHouseId' AND tbl_productsId='$productsId' AND deleted='No'";
            if ($conn->query($sql)) {
                $sql = "UPDATE tbl_damageProducts
                        SET deleted = 'Yes'
                        WHERE id='$id'";
                if ($conn->query($sql)) {
                    $conn->commit();
                    echo json_encode('Success');
                } else {
                    $conn->rollBack();
                    echo json_encode("Error: " . $conn->error . $sql);
                }
            } else {
                $conn->rollBack();
                echo json_encode("Error: " . $conn->error . $sql);
            }
        } catch (Exception $e) {
            $conn->rollBack();
            //echo 'RollBack';
            echo json_encode("Error: RollBack");
        }
        $conn->close();
    }
} else {
    $sql = "SELECT tbl_damageProducts.id, tbl_products.productName, tbl_products.productCode, tbl_warehouse.wareHouseName, tbl_damageProducts.damageQuantity, tbl_damageProducts.remarks, tbl_damageProducts.damageDate, tbl_damageProducts.damageOrderNo, CONCAT('<ul>', GROUP_CONCAT(CONCAT('<li>',tbl_productspecification.specificationName, ': ', tbl_productspecification.specificationValue) SEPARATOR '</li>') ,'</li></ul>') AS productSpeficiations
            FROM tbl_damageProducts 
            LEFT OUTER JOIN tbl_products ON tbl_damageProducts.tbl_productsId = tbl_products.id
            LEFT OUTER JOIN tbl_productspecification ON tbl_productspecification.tbl_productsId = tbl_products.id
            LEFT OUTER JOIN tbl_warehouse ON tbl_damageProducts.tbl_wareHouseId = tbl_warehouse.id
            WHERE tbl_damageProducts.deleted = 'No'
            GROUP BY tbl_damageProducts.id
            order by id desc";
    $result = $conn->query($sql);
    $i = 1;
    $output = array('data' => array());
    while ($row = $result->fetch_array()) {
        $id = $row['id'];
        $button = '<a href="#" onclick="deleteDamage(' . $id . ')"><button class="btn btn-warning btn-sm btn-flat"><i class="fa fa-edit"></i> Delete</button></a>';
        $output['data'][] = array(
            $i++,
            $row['damageDate'],
            $row['damageOrderNo'],
            $row['productName'] . ' - ' . $row['productCode'] . '<br>Remarks : ' . $row['remarks'],
            $row['productSpeficiations'],
            $row['wareHouseName'],
            $row['damageQuantity'],
            $button
        );
    } // /while 
    echo json_encode($output);
}
