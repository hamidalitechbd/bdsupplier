<?php
$conPrefix = '../';
include $conPrefix . 'includes/session.php';
date_default_timezone_set('Asia/Dhaka');
$toDay = (new DateTime())->format("Y-m-d H:i:s");
if(isset($_POST["action"]))
{
	$action = $_POST["action"];
	if($action == "warehouseWiseProducts")
	{
		$salesId = $_POST['sales_id'];
		$warehouseId = $_POST['warehouse_id'];
		$sql = "SELECT tbl_sales_products.id, tbl_sales.salesDate, tbl_sales.salesOrderNo, tbl_sales.tbl_transport_info, tbl_products.id as pId,tbl_products.productName, 
		            tbl_products.productCode, tbl_brands.brandName, tbl_category.categoryName, tbl_units.unitName, SUM(tbl_sales_products.quantity) as quantity, 
		            tbl_products.modelNo,tbl_products.carton_unit, tbl_sales_products.tbl_productsId, tbl_products.carton_type, tbl_products.package_unit, tbl_products.carton_name  
				FROM tbl_sales
				INNER JOIN tbl_sales_products ON tbl_sales_products.tbl_salesId = tbl_sales.id
				INNER JOIN tbl_products ON tbl_sales_products.tbl_productsId = tbl_products.id
				LEFT OUTER JOIN tbl_brands ON tbl_products.tbl_brandsId = tbl_brands.id
				LEFT OUTER JOIN tbl_category ON tbl_products.categoryId = tbl_category.id
				LEFT OUTER JOIN tbl_units ON tbl_products.units = tbl_units.id
				WHERE tbl_sales.id='$salesId' AND tbl_sales_products.tbl_wareHouseId='$warehouseId' AND tbl_sales.deleted='No' AND tbl_sales_products.deleted='No'
				GROUP BY tbl_sales_products.tbl_productsId";
		$result = $conn->query($sql);
		$cartoonTable = '';
		$cartonName = '';
		while ($row = $result->fetch_array()) 
		{
		    
		    $cartonName =$row['carton_name'];
		    
		    $Toltip='<a href="#" data-toggle="tooltip" data-placement="bottom" data-html="true" title="'.$cartonName.'">See Carton</a>';
		    $sqlspec = "SELECT specificationName,specificationValue FROM `tbl_productspecification`
                        WHERE tbl_productsId='".$row['pId']."' AND deleted='No'";
		    $resultspec = $conn->query($sqlspec);
		    $spec='';
		    while ($rowspec = $resultspec->fetch_array()) 
        		{
        		    $spec.=$rowspec['specificationName'].' - '.$rowspec['specificationValue'].'<br>';
        		}
		    
			$quantity = $row['quantity'];
			$cartoon_unit = $row['carton_unit'];
			if($row['package_unit'] == ""){
			    $package_name = "Carton";
			}else{
			    $package_name = $row['package_unit'];
			}
			if($cartoon_unit > 0)
			{
			    if($row['carton_type'] == "Unit"){
			        if($quantity >= $cartoon_unit)
    				{
    					$noOfCartoon = floor($quantity/$cartoon_unit);
    					$restQuantity = $quantity - ($cartoon_unit * $noOfCartoon);
    				}
    				else
    				{
    					$noOfCartoon = 0;
    					$restQuantity = $quantity;
    				}    
    				
    				$cartonData = "<input type='text' onkeyup='cartoonChange(".$row['id'].")' style='width:100px;' id='noOfCartoon_".$row['id']."' name='noOfCartoon[]' value='".$noOfCartoon."' readonly /> ".$package_name;
    				$qtyData = "<input type='text' onkeyup='cartoonCalculation(".$row['id'].")' style='width:100px;' id='cartoon_unit_".$row['id']."' name='cartoon_unit[]' value='".$cartoon_unit."' /> ".$row['unitName'];
			    }else if($row['carton_type'] == "Carton"){
			        $noOfCartoon = $quantity*$cartoon_unit;
			        $restQuantity = 0;
			        $cartonData = "<input type='text' Disabled id='noOfCartoon_".$row['id']."' style='width:100px;' name='noOfCartoon[]' value='".$noOfCartoon."' readonly /> ".$package_name."<br>$Toltip";
			        $qtyData = "<input type='text' Disabled id='cartoon_unit_".$row['id']."' style='width:100px;' name='cartoon_unit[]' value='".$cartoon_unit."' /> ".$row['unitName'];
			    }
			}
			else
			{
				$noOfCartoon = 0;
				$restQuantity = $quantity;
				$cartonData = "<input type='text' onkeyup='cartoonChange(".$row['id'].")' style='width:100px;' id='noOfCartoon_".$row['id']."' name='noOfCartoon[]' value='".$noOfCartoon."' readonly /> ".$package_name;
    			$qtyData = "<input type='text' onkeyup='cartoonCalculation(".$row['id'].")' style='width:100px;' id='cartoon_unit_".$row['id']."' name='cartoon_unit[]' value='".$cartoon_unit."' /> ".$row['unitName'];
			}
			$cartoonTable .= "<tr>
								<td>".$row['productName']."<br>Model : ".$row['modelNo']." Brand : ".$row['brandName']."<br>Spec: ".$spec."
										<input type='hidden' id='sale_products_id_".$row['id']."' name='sale_products_id[]' value='".$row['id']."' />
										<input type='hidden' id='productId_".$row['id']."' name='product_id[]' value='".$row['tbl_productsId']."' /></td>
								<td>".$quantity." ".$row['unitName']."<input type='hidden' id='total_qty_".$row['id']."' name='total_qty[]' value='".$quantity."' /></td>
								<td>".$qtyData."</td>
								<td>".$cartonData."</td>
								<td><input type='text' id='restQuantity_".$row['id']."' name='restQuantity[]' style='width:100px;' value='".$restQuantity."' Readonly /> ".$row['unitName']."</td>
							</tr>";
		}
		echo $cartoonTable;
	}else if ($action == "createChallanAccess"){
		$salesId = $_POST['salesId'];
		$saleType = $_POST['salesType'];
		$sql = "SELECT DISTINCT tbl_sales_products.tbl_wareHouseId
				FROM tbl_sales
				INNER JOIN tbl_sales_products ON tbl_sales.id = tbl_sales_products.tbl_salesId
				WHERE tbl_sales.id = '$salesId' AND tbl_sales.type='$saleType' AND tbl_sales.deleted='No' AND tbl_sales_products.deleted='No' AND tbl_sales_products.tbl_wareHouseId NOT IN (SELECT tbl_challan.tbl_warehouse_id 
				FROM tbl_challan 
				WHERE tbl_sales_id = '$salesId' AND tbl_challan.type='$saleType' AND deleted='No')";
		$result = $conn->query($sql);
		echo mysqli_num_rows($result);
	}else if ($action == "createChallan"){
		//Submit Create Challan
		$totalremainingEntry = 0;
		$warehouseId = $_POST['warehouseId'];
		$transportId = $_POST['transportId'];
		$salesId = $_POST['salesId'];
		$type = $_POST['type'];
		$partyId = $_POST['partyId'];
		$challanDate = $_POST['challanDate'];
		$sql = "SELECT LPAD(max(challan_no)+1, 6, 0) as challan_no from tbl_challan where type='PartySale'";
		$query = $conn->query($sql);
		while ($prow = $query->fetch_assoc()) {
			if($prow['challan_no'] != ''){
				$challan_no = $prow['challan_no'];
			}else{
				$challan_no = '000001';
			}
		}
		$sql = "INSERT INTO tbl_challan(challan_date, challan_no, tbl_sales_id, tbl_warehouse_id, createdBy, createdDate, type, tbl_transportinfo_id, tbl_party_id) 
				VALUES ('$challanDate','$challan_no','$salesId','$warehouseId','$loginID','$toDay','$type','$transportId','$partyId')";
		if($conn->query($sql)){
			$challanId = $conn->insert_id;
			$salesProductId = $_POST['salesProductId'];
			$salesProductIdArray = explode("@!@,",$salesProductId);
			$totalQuantity = $_POST['totalQuantity'];
			$totalQuantityArray = explode("@!@,",$totalQuantity);
			$cartoonQuantity = $_POST['cartoonQuantity'];
			$cartoonQuantityArray = explode("@!@,",$cartoonQuantity);
			$noofCartoon = $_POST['noofCartoon'];
			$noofCartoonArray = explode("@!@,",$noofCartoon);
			$remainingProducts = $_POST['remainingProducts'];
			$remainingProductsArray = explode("@!@,",$remainingProducts);
			$productId = $_POST['productIds'];
			$productIdArray = explode("@!@,",$productId);
			$cartoonSerialNo = 1;
			for($i = 0; $i < count($salesProductIdArray); $i++) {
				$salesProductIdEntry = $salesProductIdArray[$i];
				$totalQuantityEntry = $totalQuantityArray[$i];
				$cartoonQuantityEntry = $cartoonQuantityArray[$i];
				$noofCartoonEntry = $noofCartoonArray[$i];
				$remainingProductsEntry = $remainingProductsArray[$i];
				$productIdEntry = $productIdArray[$i];
				if($i == count($salesProductIdArray)-1){
					$salesProductIdEntry = substr($salesProductIdEntry, 0, strlen($salesProductIdEntry)-3);
					$totalQuantityEntry = substr($totalQuantityEntry, 0, strlen($totalQuantityEntry)-3);
					$cartoonQuantityEntry = substr($cartoonQuantityEntry, 0, strlen($cartoonQuantityEntry)-3);
					$noofCartoonEntry = substr($noofCartoonEntry, 0, strlen($noofCartoonEntry)-3);
					$remainingProductsEntry = substr($remainingProductsEntry, 0, strlen($remainingProductsEntry)-3);
					$productIdEntry = substr($productIdEntry, 0, strlen($productIdEntry)-3);
				}
				$totalremainingEntry += $remainingProductsEntry;
				if($cartoonQuantityEntry > 0 && $noofCartoonEntry > 0){
    				$sql = "INSERT INTO tbl_challan_details(tbl_challan_id, tbl_sales_products_id, tbl_products_id, quantity, cartoon_quantity, no_of_cartoon, remaining_qty, createdBy, createdDate) 
    							VALUES ('$challanId','$salesProductIdEntry','$productIdEntry','$totalQuantityEntry','$cartoonQuantityEntry','$noofCartoonEntry','$remainingProductsEntry','$loginID','$toDay')";
    				if($conn->query($sql)){
    				    //if($cartoonQuantityEntry > 0 && $noofCartoonEntry > 0){
        					for($j = 0; $j < $noofCartoonEntry; $j++){
        						if($cartoonSerialNo < 10){
        							$cartoonNo = $challan_no.'00'.$cartoonSerialNo;
        						}else if($cartoonSerialNo < 100){
        							$cartoonNo = $challan_no.'0'.$cartoonSerialNo;
        						}else{
        							$cartoonNo = $challan_no.$cartoonSerialNo;
        						}
        						$cartoonSerialNo++;
        						$sql = "INSERT INTO tbl_challan_cartoon(tbl_challan_id, cartoon_no, createdBy, createdDate) 
        									VALUES ('$challanId','$cartoonNo','$loginID','$toDay')";
        						$conn->query($sql);
        						$cartoonId = $conn->insert_id;
        						$sql_cartoonProducts = "INSERT INTO tbl_cartoon_products(tbl_cartoon_id, tbl_products_id, tbl_sales_products_id, no_of_products, tbl_warehouse_id, createdBy, createdDate) 
        									VALUES ('$cartoonId','$productIdEntry','$salesProductIdEntry','$cartoonQuantityEntry','$warehouseId','$loginID','$toDay')";
        						$conn->query($sql_cartoonProducts);
        					}
    				    //}
    				}
				}
			}
			if($totalremainingEntry == 0){
    		    $sql_updateChallan = "UPDATE tbl_challan set status='Completed' WHERE id='$challanId'";
    		    $conn->query($sql_updateChallan);
    		}
			$sql_salesId = "SELECT tbl_challan.tbl_sales_id
                    FROM tbl_challan
                    WHERE tbl_challan.deleted='No' and tbl_challan.id = '$challanId'";
            $query_salesId = $conn->query($sql_salesId);
            $salesId = '';
    		while ($prow_salesId = $query_salesId->fetch_assoc()) {
    			$salesId = $prow_salesId['tbl_sales_id'];
    		}
    		if($salesId != ''){
    		    $sql_challanExists = "SELECT DISTINCT count(tbl_sales_products.tbl_wareHouseId) as warehouse
								FROM tbl_sales
								INNER JOIN tbl_sales_products ON tbl_sales_products.tbl_salesId = tbl_sales.id
								left outer JOIN tbl_warehouse ON tbl_sales_products.tbl_wareHouseId = tbl_warehouse.id
								WHERE tbl_sales.id='$salesId' AND tbl_sales.deleted='No' AND tbl_sales_products.deleted='No' AND tbl_sales_products.tbl_wareHouseId NOT IN (SELECT tbl_warehouse_id 
								FROM tbl_challan 
								WHERE deleted='No' AND tbl_sales_id='$salesId')";
				$query_challanExists = $conn->query($sql_challanExists);
				$challanExists = 'No';
				while ($prow_challanExists = $query_challanExists->fetch_assoc()) {
				    if($prow_challanExists['warehouse'] > 0){
    			        $challanExists = 'Yes';
				    }
    	        }
								
    		}
    		
			$data = array( 
                            'msg'=>'Success', 
                            'challanId'=>$challanId,
                            'type'=>$type,
                            'challanExists'=>$challanExists,
                            'salesId'=>$salesId);
            echo json_encode($data);
			//echo json_encode("Success");
		}
	}
	else if ($action == "deleteChallan"){
		$id = $_POST['id'];
		$sql = "UPDATE tbl_challan set deleted='Yes', deletedBy='$loginID', deletedDate='$toDay' WHERE id='$id'";
		$conn->query($sql);
		$sql = "UPDATE tbl_challan_details set deleted='Yes', deletedBy='$loginID', deletedDate='$toDay' WHERE tbl_challan_id='$id'";
		$conn->query($sql);
		$sql = "UPDATE tbl_challan_cartoon set deleted='Yes', deletedBy='$loginID', deletedDate='$toDay' WHERE tbl_challan_id='$id'";
		$conn->query($sql);
		$sql = "UPDATE tbl_cartoon_products 
				INNER JOIN tbl_challan_cartoon ON tbl_cartoon_products.tbl_cartoon_id=tbl_challan_cartoon.id
				set tbl_cartoon_products.deleted='Yes', tbl_cartoon_products.deletedBy='$loginID', tbl_cartoon_products.deletedDate='$toDay' 
				WHERE tbl_challan_cartoon.tbl_challan_id='$id'";
		$conn->query($sql);
		echo 'Success';
	}
	else if ($action == "adjustRemainingCheck"){
		$id = $_POST['id'];
		$sql = "SELECT * FROM(SELECT SUM(tbl_sales_products.quantity) - IFNULL(dbt.no_of_products, 0) AS no_of_products, tbl_sales_products.tbl_productsId
                FROM `tbl_challan` 
                INNER JOIN tbl_sales ON tbl_challan.tbl_sales_id = tbl_sales.id
                INNER JOIN tbl_sales_products ON tbl_sales_products.tbl_salesId = tbl_sales.id AND tbl_sales_products.tbl_wareHouseId = tbl_challan.tbl_warehouse_id
                LEFT OUTER JOIN (SELECT SUM(tbl_cartoon_products.no_of_products) AS no_of_products, tbl_cartoon_products.tbl_products_id
                				FROM tbl_challan
                				INNER JOIN tbl_challan_cartoon ON tbl_challan_cartoon.tbl_challan_id = tbl_challan.id
                				INNER JOIN tbl_cartoon_products ON tbl_cartoon_products.tbl_cartoon_id = tbl_challan_cartoon.id
                				WHERE tbl_challan.id='$id' AND tbl_challan.deleted='No'
                				GROUP BY tbl_cartoon_products.tbl_products_id) AS dbt ON dbt.tbl_products_id = tbl_sales_products.tbl_productsId
                WHERE tbl_challan.id = '$id' AND tbl_sales.deleted= 'No'
                GROUP BY tbl_sales_products.tbl_productsId) AS dbt1
                WHERE dbt1.no_of_products > 0";
		/*$sql = "SELECT SUM(tbl_cartoon_products.no_of_products) AS no_of_products, tbl_cartoon_products.tbl_sales_products_id, tbl_sales_products.quantity
				FROM tbl_challan
				INNER JOIN tbl_challan_cartoon ON tbl_challan_cartoon.tbl_challan_id = tbl_challan.id
				INNER JOIN tbl_cartoon_products ON tbl_cartoon_products.tbl_cartoon_id = tbl_challan_cartoon.id
				LEFT OUTER JOIN tbl_sales_products ON tbl_cartoon_products.tbl_sales_products_id = tbl_sales_products.id AND tbl_cartoon_products.tbl_warehouse_id = tbl_sales_products.tbl_wareHouseId AND tbl_sales_products.deleted='No'
				WHERE tbl_challan.id='$id' AND tbl_challan.deleted='No'
				GROUP BY tbl_cartoon_products.tbl_sales_products_id";*/
		$result = $conn->query($sql);
		$remainingQty = 0;
		while ($row = $result->fetch_array()) {
			$remainingQty += $row['no_of_products'];
		}
		echo $remainingQty;
	}else if ($action=="openSalesChallan"){
	    $id = $_POST['id'];
	    /*$sql = "SELECT tbl_challan.id, tbl_challan.challan_date, tbl_challan.challan_no 
	            FROM tbl_challan 
	            where tbl_challan.tbl_sales_id=(SELECT tbl_challan.tbl_sales_id 
	            FROM tbl_challan WHERE id='$id' AND deleted='No' LIMIT 1) AND tbl_challan.deleted='No' ORDER BY id ASC";*/
	    $sql="SELECT tbl_challan.id, tbl_challan.challan_date, tbl_challan.challan_no ,tbl_sales.salesOrderNo,tbl_transportInfo.transportName,tbl_transportInfo.address,
	       tbl_warehouse.wareHouseName,tbl_party.partyName,tbl_party.locationArea,tbl_party.partyPhone,tbl_party.partyAddress, tbl_challan.print_set, tbl_challan.print_url
	            FROM tbl_challan 
                LEFT JOIN tbl_sales ON tbl_sales.id = tbl_challan.tbl_sales_id
                LEFT JOIN tbl_warehouse ON tbl_warehouse.id = tbl_challan.tbl_warehouse_id
                LEFT JOIN tbl_party ON tbl_party.id=tbl_challan.tbl_party_id
                LEFT JOIN tbl_transportInfo ON tbl_transportInfo.id=tbl_challan.tbl_transportinfo_id
	            where tbl_challan.tbl_sales_id=(SELECT tbl_challan.tbl_sales_id 
	            FROM tbl_challan WHERE id='$id' AND deleted='No' LIMIT 1) AND tbl_challan.deleted='No' ORDER BY print_set, id ASC";
	    $result = $conn->query($sql);
	    $data = '<tr>
			    <th>Check</th>
			    <th>Warehouse</th>
			    <th>Challan No</th>
			    <th>Transport</th>
			    <th>Print Set</th>
			    </tr>';
			    $print=0;
	    while ($row = $result->fetch_array()) {
            if($row['print_set'] != ''){
                $stateStatus = 'Disabled';
                $printUrl = '<a href="htmlMultiChallanViewDetails.php?'.$row['print_url'].'" target="_blank" class="btn btn-success"><i class="fa fa-file-pdf-o"></i> Print</a>';
            }else{
                $stateStatus = 'checked';
                $printUrl ='';
                $print++;
            }
			$data .= '<tr>
			    <td><input type="checkbox" name="challan[]" id="challan_'.$row['id'].'" value="'.$row['id'].'" '.$stateStatus.'/></td>
			    <td>'.$row['wareHouseName'].'</td>
			    <td>'.$row['challan_no'].' - '.$row['challan_date'].'</td>
			    <td>'.$row['transportName'].' - '.$row['transportName'].'</td>
			    <td>'.$row['print_set'].' '.$printUrl.'</td>
			</tr>';
			$tbl_sales_id = $row['salesOrderNo'];
			$partyName = $row['partyName'];
			$partyPhone = $row['partyPhone'];
			$partyAddress = $row['partyAddress'];
		}
		$data = array( 
                'msg'=>'Success', 
                'data'=>$data,
                'sales'=>$tbl_sales_id,
                'partyName'=>$partyName,
                'partyPhone'=>$partyPhone,
                'partyAddress'=>$partyAddress,
                'print'=>$print
                ); 
        echo json_encode($data);
		//echo $data;
	}
}else{
    if(isset($_GET['sortData'])){
        $dates = explode(",",$_GET['sortData']);  
	 $sql = "SELECT tbl_challan.id,tbl_challan.tbl_party_id, tbl_challan.challan_date, tbl_challan.challan_no, tbl_sales.salesOrderNo, tbl_sales.salesDate, tbl_warehouse.wareHouseName, tbl_transportInfo.transportName, tbl_transportInfo.transport_name_bangla, tbl_transportInfo.contact_number_bangla, tbl_challan.status,tbl_challan.transport_challan_no,tbl_challan.transport_date, tbl_challan.type, tbl_transportInfo.contactNo, tbl_challan.total_carton, tbl_challan.total_pcs
			FROM tbl_challan
			INNER JOIN tbl_sales ON tbl_challan.tbl_sales_id = tbl_sales.id
			INNER JOIN tbl_warehouse ON tbl_challan.tbl_warehouse_id = tbl_warehouse.id
			LEFT OUTER JOIN tbl_transportInfo ON tbl_challan.tbl_transportinfo_id = tbl_transportInfo.id
			WHERE tbl_challan.deleted='No' AND tbl_challan.challan_date BETWEEN '".$dates[0]."' AND '".$dates[1]."'
			order by tbl_challan.id desc";
    }
    else if(isset($_GET['customerId'])){
            $customerId = $_GET['customerId'];
        $sql = "SELECT tbl_challan.id, tbl_challan.tbl_party_id,tbl_challan.challan_date, tbl_challan.challan_no, tbl_sales.salesOrderNo, tbl_sales.salesDate, tbl_warehouse.wareHouseName, tbl_transportInfo.transportName, tbl_transportInfo.transport_name_bangla, tbl_transportInfo.contact_number_bangla, tbl_challan.status,tbl_challan.transport_challan_no,tbl_challan.transport_date, tbl_challan.type, tbl_transportInfo.contactNo, tbl_challan.total_carton, tbl_challan.total_pcs
			FROM tbl_challan
			INNER JOIN tbl_sales ON tbl_challan.tbl_sales_id = tbl_sales.id
			INNER JOIN tbl_warehouse ON tbl_challan.tbl_warehouse_id = tbl_warehouse.id
			LEFT OUTER JOIN tbl_transportInfo ON tbl_challan.tbl_transportinfo_id = tbl_transportInfo.id
			WHERE tbl_challan.deleted='No' AND tbl_challan.tbl_party_id='$customerId' order by tbl_challan.id desc";
    }
    else {
        $sql = "SELECT tbl_challan.id, tbl_challan.tbl_party_id,tbl_challan.challan_date, tbl_challan.challan_no, tbl_sales.salesOrderNo, tbl_sales.salesDate, tbl_warehouse.wareHouseName, tbl_transportInfo.transportName, tbl_transportInfo.transport_name_bangla, tbl_transportInfo.contact_number_bangla, tbl_challan.status,tbl_challan.transport_challan_no,tbl_challan.transport_date, tbl_challan.type, tbl_transportInfo.contactNo, tbl_challan.total_carton, tbl_challan.total_pcs
			FROM tbl_challan
			INNER JOIN tbl_sales ON tbl_challan.tbl_sales_id = tbl_sales.id
			INNER JOIN tbl_warehouse ON tbl_challan.tbl_warehouse_id = tbl_warehouse.id
			LEFT OUTER JOIN tbl_transportInfo ON tbl_challan.tbl_transportinfo_id = tbl_transportInfo.id
			WHERE tbl_challan.deleted='No' order by tbl_challan.id desc";
    }
    
	/*$sql = "SELECT tbl_challan.id, challan_date, challan_no, tbl_sales.salesOrderNo, tbl_sales.salesDate, tbl_warehouse.wareHouseName, tbl_transportInfo.transportName, tbl_challan.status,tbl_challan.transport_challan_no,tbl_challan.transport_date, tbl_challan.type
			FROM tbl_challan
			INNER JOIN tbl_sales ON tbl_challan.tbl_sales_id = tbl_sales.id
			INNER JOIN tbl_warehouse ON tbl_challan.tbl_warehouse_id = tbl_warehouse.id
			LEFT OUTER JOIN tbl_transportInfo ON tbl_challan.tbl_transportinfo_id = tbl_transportInfo.id
			WHERE tbl_challan.deleted='No'
			order by tbl_challan.id desc";
    */
			
	$result = $conn->query($sql);
    $i=1;
    $output = array('data' => array());
    while ($row = $result->fetch_array()) {
        
        $sqlParty="SELECT tbl_challan.id,tbl_party.partyName,tbl_walkin_customer.customerName ,tbl_party.partyAddress,tbl_walkin_customer.customerAddress,tbl_party.tblCity,tbl_party.tblCountry,tbl_party.locationArea,tbl_party.contactPerson,tbl_party.partyPhone, tbl_walkin_customer.phoneNo,tbl_party.partyAltPhone, tbl_challan.type
            FROM `tbl_challan` 
            LEFT OUTER JOIN tbl_party ON tbl_challan.tbl_party_id = tbl_party.id 
            LEFT OUTER JOIN tbl_walkin_customer ON tbl_challan.tbl_party_id = tbl_walkin_customer.id 
            WHERE tbl_challan.id = '".$row['id']."' AND tbl_challan.deleted = 'No'";
        $queryParty = $conn->query($sqlParty);
        while($rowParty = $queryParty->fetch_assoc()){
            if($rowParty['type']=='PartySale'){
                $partyName = $rowParty['partyName'].'<br>'.$rowParty['partyAddress'].' - '.$rowParty['tblCity'].'<br>'.$rowParty['contactPerson'].' - '.$rowParty['partyPhone'];
                $partyPhoneWA = $rowParty['partyPhone'];
                $partyNameWA = $rowParty['partyName'];
            }else{
                $partyName = $rowParty['customerName'].'<br>Address : '.$rowParty['customerAddress'].'<br>Contact : '.$rowParty['phoneNo'];
                $partyNameWA = $rowParty['customerName'];
                $partyPhoneWA = $rowParty['phoneNo'];
            }
        }
    
		$transport_challan_no = $row['transport_challan_no'];
		$transportName = $row['transportName'];
		$transportBangla = $row['transport_name_bangla'];
		$transportBanglaPhone = $row['contact_number_bangla'];
		$contactNo = $row['contactNo'];
		if($row['transport_date'] != ""){
		    $transport_date = date("m-d-Y", strtotime($row['transport_date']));
		}else{
		    $transport_date = date("m-d-Y", strtotime($row['challan_date']));
		}
    
        
        
        
        
        //$message = 'Dear '.$partyNameWA.', goods from Jafree Traders dispatches on '.$transport_date.' through '.$transportName.', '.$contactNo.'  - Challan# '.$transport_challan_no.' No of Carton '.'('.$row['total_carton'].')'.'. All goods have been checked and packaged with atmost care.'.'Please first contact transport agency for any damages. Thank You.';	
        $message = 'Dear '.$partyNameWA.',%0a%0aGoods from Jafree Traders dispatches on '.$transport_date.'.%0a%0aBooking though: '.$transportBangla.', Phone : '.$transportBanglaPhone.'%0a%0aTransport Chalan # '.$transport_challan_no.'.%0a%0aNo of Carton - '.$row['total_carton'].'.%0a%0a মন্তব্য : %0a%0a১. সকল পন‍্য পর্যবেক্ষণ করে অধিকতর যত্ন সহকারে প‍্যাকেট করা হয়েছে .%0a২. পন‍্যের কোনরকম ক্ষয়ক্ষতি হলে প্রথমেই পরিবহন সংস্থার সাথে যোগাযোগ করুন   .%0a৩. জাফরী ট্রেডার্স থেকে দেয়া চালানের সাথে পরিবহন সংস্থা থেকে সরবরাহকৃত পন‍্য মিলিয়ে নিন . %0a%0aThanking you.%0aJafree Traders.';
        $challanId = $row['id'];
        if($row['status']=='Completed'){
            $status="<span style='color: green;font-weight: 600;'>Completed</span>";
            if($row['total_carton'] > 0)
                $whatsAppMessage = '<li><a href="https://web.whatsapp.com/send?phone=+88'.$partyPhoneWA.'&text='.$message.'" target="_blank"><i class="fa fa-whatsapp" aria-hidden="true"></i>Send WhatsApp</a></li>';
            else
                $whatsAppMessage = '';
        }else{
           $status="<span style='color: red;font-weight: 600;'>Pending</span>"; 
            $whatsAppMessage = '';
        }
        $button = '	<div class="btn-group">
						<button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
						<i class="fa fa-gear tiny-icon"></i> <span class="caret"></span></button>
						<ul class="dropdown-menu dropdown-menu-right" style="border: 1px solid gray;" role="menu">
							<li><a href="challanPrintViewDetails.php?id='.$row['id'].'" target="_blank"><i class="fa fa-print tiny-icon"></i> Eng.Challan PDF</a></li>
							<li><a href="challanHtmlViewDetails.php?id='.$row['id'].'" target="_blank"><i class="fa fa-print tiny-icon"></i> Ban.Challan PDF</a></li>
							<li><a href="#" onclick="updateChallan('.$row['id'].')"><i class="fa fa-truck tiny-icon"></i>Update Transport</a></li-->
							<li><a href="#" onclick="transportChallan('.$row['id'].')"><i class="fa fa-asl-interpreting"></i>Group Challan</a></li>
							<li><a href="#" onclick="adjustRemaining('.$challanId.',\''.$row['type'].'\')"><i class="fa fa-plus" aria-hidden="true"></i>Adjust Remaining</a></li>'.$whatsAppMessage;
		if(strtolower($_SESSION['userType']) == 'super admin' || strtolower($_SESSION['userType']) == 'admin support plus'){
		    $button .=  '<li><a href="#" onclick="deleteChallan(' . $challanId . ')"><i class="fa fa-edit tiny-icon"></i>Delete</a></li>';
		}
		$button .= '</ul></div>';
        $output['data'][] = array(
            $i++,
            $row['challan_date'],
            $row['challan_no'],
            $row['salesOrderNo'].'<br>'.$row['salesDate'].'<br>'.$row['type'],
            $row['wareHouseName'],
            $partyName,
            $row['transportName'].'<br>'.$row['transport_challan_no'].'<br>'.$row['transport_date'],
            $status,
            $button
        );
    } // /while 
    echo json_encode($output);
}
?>