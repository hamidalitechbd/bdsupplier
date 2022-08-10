<?php
$conPrefix = '../';
include $conPrefix . 'includes/session.php';

date_default_timezone_set('Asia/Dhaka');
$toDay = (new DateTime($test))->format("Y-m-d H:i:s");

if(isset($_POST['action'])){
    if ($_POST['action'] == "saveDamage"){
        $loginID = $_SESSION['user'];
        $damageDate = $_POST['damageDate'];
        $damageProducts = $_POST['damageProducts'];
        $damageWareHouse=$_POST['damageWareHouse'];
        $currentStock=$_POST['currentStock'];
        $damageQuantity=$_POST['damageQuantity'];
        $damageRemarks=$_POST['damageRemarks'];
        try{    
            $conn->begin_transaction();
            $sql = "SELECT LPAD(max(damageOrderNo)+1, 6, 0) as damageOrderNo from tbl_damageProducts";
    		$query = $conn->query($sql);
    		while ($prow = $query->fetch_assoc()) {
    			$damageOrderNo = $prow['damageOrderNo'];
    		}
    		if($damageOrderNo == ""){
    		    $damageOrderNo = "000001";
    		}
            $sql = "INSERT INTO tbl_damageProducts(tbl_productsId, tbl_wareHouseId, damageQuantity, remarks, damageDate, damageOrderNo, createdBy,createdDate) 
                    VALUES ('$damageProducts','$damageWareHouse','$damageQuantity','$damageRemarks','$damageDate','$damageOrderNo','$loginID','$toDay')";
            if($conn->query($sql)){
                $sql = "UPDATE tbl_currentStock 
					    set damageProducts=damageProducts+$damageQuantity, currentStock=currentStock-$damageQuantity, lastUpdatedDate='$toDay', lastUpdatedBy='$loginID'
					    where tbl_productsId = '$damageProducts' AND tbl_wareHouseId='$damageWareHouse' and deleted='No'";
    		    if($conn->query($sql)){
        			if($conn->affected_rows == 0){
        				$sql = "insert into tbl_currentStock 
        				            (damageProducts, currentStock, tbl_productsId, tbl_wareHouseId, entryBy,entryDate) values 
        				            ('$damageQuantity', '-$damageQuantity','$damageProducts','$damageWareHouse','$loginID','$toDay')";
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
        }catch(Exception $e){
    		$conn->rollBack();
    		echo json_encode('RollBack');
    	}
        $conn->close();
    }
    else if ($_POST['action'] == "deleteDamage"){
        $loginID = $_SESSION['user'];
        $id = $_POST['id'];
        try{    
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
            if($conn->query($sql)){
                $sql = "UPDATE tbl_damageProducts
                        SET deleted = 'Yes'
                        WHERE id='$id'";
                if($conn->query($sql)){        
                    $conn->commit();
                    echo json_encode('Success');
                }else{
                    $conn->rollBack();    
                    echo json_encode("Error: ".$conn->error.$sql);
                }
            }else{
                $conn->rollBack();
                echo json_encode("Error: ".$conn->error.$sql);
            }    
        }catch(Exception $e){
    		$conn->rollBack();
    		//echo 'RollBack';
    		echo json_encode("Error: RollBack");
    	}
        $conn->close();
    }
}else{
    $sql = "SELECT tbl_damageProducts.id, tbl_products.productName, tbl_products.productCode, tbl_warehouse.wareHouseName, tbl_damageProducts.damageQuantity, tbl_damageProducts.remarks, tbl_damageProducts.damageDate, tbl_damageProducts.damageOrderNo, CONCAT('<ul>', GROUP_CONCAT(CONCAT('<li>',tbl_productspecification.specificationName, ': ', tbl_productspecification.specificationValue) SEPARATOR '</li>') ,'</li></ul>') AS productSpeficiations
            FROM tbl_damageProducts 
            LEFT OUTER JOIN tbl_products ON tbl_damageProducts.tbl_productsId = tbl_products.id
            LEFT OUTER JOIN tbl_productspecification ON tbl_productspecification.tbl_productsId = tbl_products.id
            LEFT OUTER JOIN tbl_warehouse ON tbl_damageProducts.tbl_wareHouseId = tbl_warehouse.id
            WHERE tbl_damageProducts.deleted = 'No'
            GROUP BY tbl_damageProducts.id
            order by id desc";
    $result = $conn->query($sql);
    $i=1;
    $output = array('data' => array());
    while ($row = $result->fetch_array()) {
        $id = $row['id'];
        $button = '<a href="#" onclick="deleteDamage('.$id.')"><button class="btn btn-warning btn-sm btn-flat"><i class="fa fa-edit"></i> Delete</button></a>';
        $output['data'][] = array(
            $i++,
            $row['damageDate'],
            $row['damageOrderNo'],
            $row['productName'].' - '.$row['productCode'].'<br>Remarks : '.$row['remarks'],
            $row['productSpeficiations'],
            $row['wareHouseName'],
            $row['damageQuantity'],
            $button
        );
    } // /while 
    echo json_encode($output);
}
?>