<?php
$conPrefix = '../';
include $conPrefix . 'includes/session.php';

date_default_timezone_set('Asia/Dhaka');
$toDay = (new DateTime($test))->format("Y-m-d H:i:s");

if(isset($_POST['action'])){
    if($_POST['action'] == "fetchWareHouse"){
        $productsId = $_POST['productsId'];
        $sql = "SELECT tbl_warehouse.id, tbl_warehouse.wareHouseName, tbl_currentStock.currentStock
                FROM tbl_currentStock
                INNER JOIN tbl_warehouse ON tbl_warehouse.id = tbl_currentStock.tbl_wareHouseId
                WHERE tbl_currentStock.tbl_productsId='$productsId' AND tbl_currentStock.deleted='No' AND tbl_warehouse.status='Active' AND tbl_warehouse.deleted='No'";
        $result = $conn->query($sql);
        
        while ($row = $result->fetch_array()) {
            $rows[] = $row;    
        }
        echo json_encode($rows);
    }else if ($_POST['action'] == "saveTransfer"){
        $loginID = $_SESSION['user'];
        $transferDate = $_POST['transferDate'];
        $products = $_POST['products'];
        $wareHouseId=$_POST['wareHouseID'];
        $currentStock=$_POST['currentStock'];
        $remainingStock=$_POST['remainingStock'];
        $transferWareHouse=$_POST['transferWareHouse'];
        $transferStock=$_POST['transferStock'];
        try{    
            $conn->begin_transaction();
            $sql = "INSERT INTO tbl_warehouse_transfer(transferDate, tbl_products_id, tbl_current_warehouse_id, current_stock, tbl_transfer_warehouse_id, transfer_stock, entryBy, entryDate) 
                VALUES ('$transferDate','$products','$wareHouseId','$currentStock','$transferWareHouse','$transferStock','$loginID','$toDay')";
            if($conn->query($sql)){
                $sql = "UPDATE tbl_currentStock 
                        SET transferFrom=transferFrom+$transferStock,lastUpdatedDate='$toDay',lastUpdatedBy='$loginID', 
                        	currentStock = currentStock - $transferStock 
                        WHERE tbl_wareHouseId='$wareHouseId' AND tbl_productsId='$products' AND deleted='No'";
                if($conn->query($sql)){
                    $sql = "UPDATE tbl_currentStock 
                            SET transferTo=transferTo+$transferStock,lastUpdatedDate='$toDay',lastUpdatedBy='$loginID',
                            	currentStock = currentStock + $transferStock 
                            WHERE tbl_wareHouseId='$transferWareHouse' AND tbl_productsId='$products' AND deleted='No'";
                    if($conn->query($sql)){
                        if($conn->affected_rows == 0){
                            $sql = "INSERT INTO tbl_currentStock(tbl_productsId, tbl_wareHouseId, currentStock, transferTo, entryBy,entryDate) 
                                    VALUES ('$products', '$transferWareHouse', '$transferStock', '$transferStock', '$loginID','$toDay')";
                            $conn->query($sql);
                        }
                        $conn->commit();
                        echo json_encode('Success');
                    }else{
                        $conn->rollBack();    
                        echo json_encode($conn->error.$sql);
                    }
                }else{
                    $conn->rollBack();
                    echo json_encode($conn->error.$sql);
                }    
            }else{
                $conn->rollBack();
                echo json_encode($conn->error.$sql);
            }
        }catch(Exception $e){
    		$conn->rollBack();
    		echo json_encode('RollBack');
    	}
        $conn->close();
            
                
        
    }
    else if ($_POST['action'] == "deleteTransfer"){
        $loginID = $_SESSION['user'];
        $id = $_POST['id'];
        try{    
            $conn->begin_transaction();
            $sql = "select transferDate, tbl_products_id, tbl_current_warehouse_id, current_stock, tbl_transfer_warehouse_id, transfer_stock 
                from tbl_warehouse_transfer
                where id='$id' and deleted='No'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $transferStock = $row['transfer_stock'];
            $wareHouseId = $row['tbl_current_warehouse_id'];
            $productsId = $row['tbl_products_id']; 
            $transferWareHouseId = $row['tbl_transfer_warehouse_id'];
            $sql = "UPDATE tbl_currentStock 
                    SET transferFromDelete=transferFromDelete+$transferStock,deletedBy='$loginID',deletedDate='$toDay', 
                    	currentStock = currentStock + $transferStock 
                    WHERE tbl_wareHouseId='$wareHouseId' AND tbl_productsId='$productsId' AND deleted='No'";
            if($conn->query($sql)){
                $sql = "UPDATE tbl_currentStock 
                        SET transferToDelete=transferToDelete+$transferStock,deletedBy='$loginID',deletedDate='$toDay', 
                        	currentStock = currentStock - $transferStock 
                        WHERE tbl_wareHouseId='$transferWareHouseId' AND tbl_productsId='$productsId' AND deleted='No'";
                if($conn->query($sql)){
                    $sql = "UPDATE tbl_warehouse_transfer
                            SET deleted = 'Yes'
                            WHERE id='$id'";
                    if($conn->query($sql)){        
                        $conn->commit();
                        echo 'Success';
                    }else{
                        $conn->rollBack();    
                        echo $conn->error.$sql;
                    }
                }else{
                    $conn->rollBack();    
                    echo $conn->error.$sql;
                }
            }else{
                $conn->rollBack();
                echo $conn->error.$sql;
            }    
            
        }catch(Exception $e){
    		$conn->rollBack();
    		echo 'RollBack';
    	}
        $conn->close();
            
                
        
    }
}else{
    $sql = "SELECT tbl_warehouse_transfer.id, tbl_warehouse_transfer.transferDate, tbl_warehouse.wareHouseName AS currentWareHouseName, current_stock, w1.wareHouseName AS transferWareHouseName, transfer_stock, tbl_products.productName, tbl_products.productCode
            FROM tbl_warehouse_transfer
            LEFT OUTER JOIN tbl_warehouse ON tbl_warehouse_transfer.tbl_current_warehouse_id = tbl_warehouse.id AND tbl_warehouse.deleted = 'No' AND tbl_warehouse.status = 'Active'
            LEFT OUTER JOIN tbl_warehouse as w1 ON tbl_warehouse_transfer.tbl_transfer_warehouse_id = w1.id AND w1.deleted = 'No' AND w1.status = 'Active'
            LEFT OUTER JOIN tbl_products ON tbl_warehouse_transfer.tbl_products_id = tbl_products.id
            WHERE tbl_warehouse_transfer.deleted='No'
            order by tbl_warehouse_transfer.id DESC";
    $result = $conn->query($sql);
    $i=1;
    $output = array('data' => array());
    while ($row = $result->fetch_array()) {
        $transferId = $row['id'];
        $button = '<a href="#" onclick="deleteTransfer(' . $transferId . ')"><button class="btn btn-warning btn-sm btn-flat"><i class="fa fa-edit"></i> Delete</button></a>';
        $output['data'][] = array(
            $i++,
            $row['transferDate'],
            $row['productName'].' - '.$row['productCode'],
            $row['currentWareHouseName'],
            $row['transferWareHouseName'],
            $row['transfer_stock'],
            $button
        );
    } // /while 
    echo json_encode($output);
}
?>