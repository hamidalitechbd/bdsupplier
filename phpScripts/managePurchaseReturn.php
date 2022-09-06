<?php
$conPrefix = '../';
include $conPrefix . 'includes/session.php';
if (isset($_POST['purchaseReturn'])) {
    $loginID = $_SESSION['user'];
    $returnDate = $_POST['returnDate'];
    $purchaseId = $_POST['purchaseId'];
    $supplierId = $_POST['supplierId'];
    $quantity = $_POST['quantity'];
    $purchaseProductsId = $_POST['purchaseProductsId'];
    $quantityArray = explode("@!@,", $quantity);
    $purchaseProductsIdArray = explode("@!@,", $purchaseProductsId);

    // Serialize Product Return From (Purchase)
    $stockQuantities = $_POST['stockQuantities'];
    $stockQuantityArray = explode(",", $stockQuantities);
    $tbl_serialize_productsIds = $_POST['tbl_serialize_productsIds'];
    $tbl_serialize_productsIdsArray = explode(",", $tbl_serialize_productsIds);
    // End Serialize Product Return (Purchase)

    try {
        $conn->begin_transaction();

        $sql = "SELECT LPAD(max(purchaseReturnOrderNo)+1, 6, 0) as purchaseReturnOrderNo from tbl_purchase_return";
        $query = $conn->query($sql);
        while ($prow = $query->fetch_assoc()) {
            $purchaseReturnOrderNo = $prow['purchaseReturnOrderNo'];
        }
        if ($purchaseReturnOrderNo == '') {
            $purchaseReturnOrderNo = '000001';
        }
        $sql = "INSERT INTO tbl_purchase_return (tbl_purchaseId,purchaseReturnOrderNo,purchaseReturnDate,deleted,entryBy, tbl_supplierId) "
            . "VALUES ('$purchaseId','$purchaseReturnOrderNo','$returnDate','No','$loginID','$supplierId')";
        if ($conn->query($sql)) {
            $k = 0;
            if (count($quantityArray) > 0) {
                $purchaseReturnId = $conn->insert_id;
                $totalAmount = 0;
                for ($i = 0; $i < count($quantityArray); $i++) {
                    $quantityEntry = $quantityArray[$i];
                    if ($quantityEntry > 0) {
                        $purchaseProductsIdEntry = $purchaseProductsIdArray[$i];
                        if ($i == count($quantityArray) - 1) {
                            $quantityEntry = substr($quantityEntry, 0, strlen($quantityEntry) - 3);
                            $purchaseProductsIdEntry = substr($purchaseProductsIdEntry, 0, strlen($purchaseProductsIdEntry) - 3);
                        }
                        if ($quantityEntry != '' && $quantityEntry > 0) {
                            //If quantity exists then insert into purchase products return
                            $sql = "INSERT INTO tbl_purchase_product_return (tbl_purchase_return_id,tbl_productsId,quantity,purchasePrice,wholeSalePrice,walkinCustomerPrice,totalAmount,tbl_wareHouseId,entryBy) 
    								Select '$purchaseReturnId' as purchaseReturnId,tbl_productsId,'$quantityEntry' as quantityEntry,purchaseAmount,wholeSalePrice,walkinCustomerPrice,purchaseAmount*$quantityEntry,tbl_wareHouseId,'$loginID' from tbl_purchaseProducts where id='" . $purchaseProductsIdEntry . "'";
                            if ($conn->query($sql)) {
                                $returnProductsId = $conn->insert_id;
                                $sql = "SELECT tbl_productsId, purchasePrice,totalAmount,tbl_wareHouseId  FROM tbl_purchase_product_return WHERE id='$returnProductsId'";
                                $res = $conn->query($sql);
                                $productsId = '';
                                $wareHouseId = '';

                                while ($row = $res->fetch_assoc()) {
                                    $productsId = $row['tbl_productsId'];
                                    $wareHouseId = $row['tbl_wareHouseId'];
                                    $totalAmount += $row['totalAmount'];
                                }
                                //If purchase products return entry then update the current stock
                                $sql = "UPDATE tbl_currentStock 
                                            set purchaseReturnStock=purchaseReturnStock+$quantityEntry, currentStock=currentStock-$quantityEntry
                                            where tbl_productsId = '$productsId' AND tbl_wareHouseId='$wareHouseId'";
                                $conn->query($sql);
                                if ($conn->affected_rows == 0) {
                                    $sql = "insert into tbl_currentStock (purchaseReturnStock, currentStock, tbl_productsId, tbl_wareHouseId, entryBy) values ('$quantityEntry', '-$quantityEntry','$productsId','$wareHouseId', '$loginID')";
                                    $conn->query($sql);
                                }

                                $sql = "UPDATE tbl_purchaseProducts 
                        		            SET returnQuantity += $quantityEntry 
                    		            WHERE id='$purchaseProductsIdEntry'";
                                $conn->query($sql);
                            }
                        }
                    }
                }
                if ($i == count($quantityArray)) {
                    //====================== Start Serialize Product Return (Purchase) ======================//
                    foreach ($tbl_serialize_productsIdsArray as $key => $tbl_serialize_productsId) {
                        if (strpos($tbl_serialize_productsId, '@') == true) {
                            $productIdAndWarahouseId = explode("@", $tbl_serialize_productsId);
                            continue;
                        }
                        $returnQuantity = $stockQuantityArray[$key];
                        if ($returnQuantity > 0) {
                            // Update Quantity 
                            $update_serialize = "UPDATE tbl_serialize_products set used_quantity=used_quantity+$returnQuantity where id='$tbl_serialize_productsId'";
                            $updateResult = $conn->query($update_serialize);
                            // End Update Quantity
                            if ($updateResult) {
                                $returnSql = "INSERT INTO tbl_sale_serialize_products_return (tbl_name, tbl_id, returned_quantity, salesType, created_by, created_date) 
                                		              values ('tbl_purchase_return','$purchaseReturnId','$returnQuantity','Purchase','$loginID','$toDay')";
                                $result = $conn->query($returnSql);
                            }
                        }
                    }
                    //====================== End Serialize Product Return (Purchase) ======================//

                    if ($totalAmount > 0) {
                        $customerType = 'Party';
                        $sql = "SELECT LPAD(IFNULL(max(voucherNo),0)+1, 6, 0) as voucherCode, LPAD(IFNULL(max(voucherNo),0)+2, 6, 0) as voucherReceiveCode FROM tbl_paymentVoucher WHERE tbl_partyId='$supplierId' AND customerType = '$customerType'";
                        $query = $conn->query($sql);
                        while ($prow = $query->fetch_assoc()) {
                            $voucherNo = $prow['voucherCode'];
                            $voucherReceiveNo = $prow['voucherReceiveCode'];
                        }
                        if ($voucherNo == "") {
                            $voucherNo = "000001";
                            $voucherReceiveNo = "000002";
                        }
                        $sql = "INSERT INTO tbl_paymentVoucher (tbl_partyId, tbl_purchase_return_id, amount, entryBy, paymentMethod, paymentDate, status, remarks, type, voucherType, voucherNo, customerType) 
    					        VALUES ('$supplierId', '$purchaseReturnId', '$totalAmount', '$loginID', 'Adjustment', '$returnDate', 'Active', 'Purchase return payable adjustment for purchase code: $purchaseCode and purchase return code: $purchaseReturnOrderNo', 'partyPayable', 'PurchaseReturn', '$voucherNo', '$customerType')";
                        $conn->query($sql);
                        $sql = "INSERT INTO tbl_paymentVoucher (tbl_partyId, tbl_purchase_return_id, amount, entryBy, paymentMethod, paymentDate, status, remarks, type, voucherType, voucherNo, customerType) 
    					        VALUES ('$supplierId', '$purchaseReturnId', '$totalAmount', '$loginID', 'Adjustment', '$returnDate', 'Active', 'Purchase return payment adjustment for purchase code: $purchaseCode and purchase return code: $purchaseReturnOrderNo', 'adjustment', 'PurchaseReturn', '$voucherReceiveNo', '$customerType')";
                        $conn->query($sql);
                    }
                    $conn->commit();
                    $data = array(
                        'msg' => 'Success',
                        'returnId' => $purchaseReturnId
                    );
                    echo json_encode($data);
                    //echo json_encode('Success');
                } else {
                    echo 'Without purchase return products, its not possible to save';
                    $conn->rollBack();
                }
            } else {
                echo 'Without purchase return products, its not possible to save';
                $conn->rollBack();
            }
        } else {
            echo json_encode($conn->error);
        }
    } catch (Exception $e) {
        $conn->rollBack();
        echo 'RollBack';
    }
    $conn->close();
}
if (isset($_POST['deletePurchaseReturn'])) {
    try {
        $id = $_POST['id'];
        $conn->begin_transaction();
        $sql = "UPDATE tbl_purchase_return 
                SET 
                deleted='Yes',
                deletedDate=NOW(),
                deletedBy='$loginID'
                WHERE id='$id'";
        if ($conn->query($sql)) {
            $sql = "SELECT tbl_purchase_product_return.id,purchasePrice, quantity, tbl_productsId, tbl_wareHouseId,totalAmount, walkinCustomerPrice, wholeSalePrice, tbl_purchase_return.tbl_supplierId , tbl_purchase_return.tbl_purchaseId
                    FROM tbl_purchase_product_return
                    INNER JOIN tbl_purchase_return ON tbl_purchase_product_return.tbl_purchase_return_id = tbl_purchase_return.id
                    WHERE tbl_purchase_return_id='$id' AND tbl_purchase_product_return.deleted='No'";
            $res = $conn->query($sql);
            $totalAmount = 0;
            $supplierId = '';
            $productsId = '';
            $wareHouseId = '';
            $quantity = '';
            $purchaseId = '';
            while ($row = $res->fetch_assoc()) {
                $totalAmount += $row['totalAmount'];
                $supplierId = $row['supplierId'];
                $productsId = $row['tbl_productsId'];
                $wareHouseId = $row['tbl_wareHouseId'];
                $quantity = $row['quantity'];
                $purchaseId = $row['tbl_purchaseId'];
                $sql = "UPDATE tbl_currentStock 
                        set purchaseReturnDelete=purchaseReturnDelete+$quantity, currentStock=currentStock+$quantity
                        where tbl_productsId = '$productsId' AND tbl_wareHouseId='$wareHouseId'";
                $conn->query($sql);

                $sql = "UPDATE tbl_purchaseProducts 
    		            SET returnQuantity -= $quantity
		            WHERE tbl_purchaseId='$purchaseId' AND
                        tbl_productsId='$productsId'  AND
                        tbl_wareHouseId='$wareHouseId'";
                $conn->query($sql);
            }
            $sql = "UPDATE tbl_paymentVoucher SET deleted='Yes', deletedBy='$loginID', deletedDate=NOW()
                    WHERE tbl_purchase_return_id='$id'";
            $conn->query($sql);
        }
        $conn->commit();
        echo json_encode('Success');
    } catch (Exception $e) {
        $conn->rollBack();
        echo 'RollBack';
    }
    $conn->close();
}
