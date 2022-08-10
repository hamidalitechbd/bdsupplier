<?php
$conPrefix = '../';
include $conPrefix . 'includes/session.php';

/*date_default_timezone_set('Asia/Dhaka');
$toDay = date('Y-m-d h:i:s', time());*/

date_default_timezone_set('Asia/Dhaka');
$toDay = (new DateTime($test))->format("Y-m-d H:i:s");

include('resize_image_product.php');
if(isset($_GET['page'])){
    $page = $_GET['page'];
    if($page == 'editOpeningStock'){
        $id = $_GET['id'];
        $sql_product = "select * from tbl_products where id='$id' AND deleted='No'";
        $query_product = $conn->query($sql_product);
    	$row_product =  $query_product->fetch_assoc();
    	
    	$sql_productSpecs = "select * from tbl_productspecification where tbl_productsId='$id' AND deleted='No'";
    	$query_productSpecs = $conn->query($sql_productSpecs);
    	$i = 0;
    	$productSpecs[] = null;
    	while($row_productSpecs =  $query_productSpecs->fetch_assoc())
    	{
    	    $productSpecs[$i]= $row_productSpecs;
    			$i++;
    	}
    	
    	$sql_currentStocks = "select tbl_warehouse.wareHouseName, tbl_currentStock.initialStock, tbl_currentStock.currentStock, tbl_products.productName, tbl_products.productCode 
    	                        from tbl_currentStock 
    	                        INNER JOIN tbl_warehouse ON tbl_currentStock.tbl_wareHouseId=tbl_warehouse.id
    	                        INNER JOIN tbl_products ON tbl_currentStock.tbl_productsId=tbl_products.id
    	                        where tbl_productsId='$id' AND tbl_currentStock.deleted='No' AND tbl_currentStock.initialStock > 0
    	                        ORDER BY tbl_warehouse.id DESC";
    	$query_currentStocks = $conn->query($sql_currentStocks);
    	$initialStockData = '';
    	while($row_currentStocks =  $query_currentStocks->fetch_assoc())
    	{
    	     $initialStockData .= '<tr>
                                    <td>' . $row_currentStocks['wareHouseName'] . '</td>
                                    <td>' . $row_currentStocks['initialStock'] . '</td>
                                    <td>' . $row_currentStocks['currentStock'] . '</td>
                                </tr>';
    	}
        $data = array(
            'row_product'=>$row_product, 
            'productSpecs'=>$productSpecs, 
            'initialStockData'=>$initialStockData
            );
        echo json_encode($data);
    }
}else{
    if (isset($_POST['saveProduct'])) {
        $loginID = $_SESSION['user'];
    	//$wareHouseId = $_POST['wareHouseId'];
        $productName = $_POST['productName'];
        $productCode = $_POST['productCode'];
        //$openStock = 0;
        $minimumStock = $_POST['minimumStock'];
        $brandId = $_POST['brandId'];
        $categoryId = $_POST['categoryId'];
        $units = $_POST['units'];
        $cartoon_unit = $_POST['cartoon_unit'];
        $carton_type = $_POST['carton_type'];
        $package_name = $_POST['package_name'];
        $carton_name = $_POST['carton_name'];
        $productType = $_POST['type'];
        $stockCheck = $_POST['stockCheck'];
        $itemsInBox = $_POST['itemsInBox'];
        //$manufacturingDate = $_POST['manufacturingDate'];
        //$expiryDate = $_POST['expiryDate'];
        $standardSalesUnit = $_POST['standardSalesUnit'];
        $purchasePrice = $_POST['purchasePrice'];
        $minimumSalePrice = $_POST['minimumSalePrice'];
        $maximumSalePrice = $_POST['maximumSalePrice'];
        $productNote = $_POST['productNote'];
        //$lotNo = $_POST['lotNo'];
        $modelNo = $_POST['modelNo'];
        $spacName = $_POST['spacName'];
        $spacValue = $_POST['spacValue'];
        $spacNameArray = explode("@!@,",$spacName);
        $spacValueArray = explode("@!@,",$spacValue);
       
    	//Below Part for image upload
    	$imageFileType = '';
    	$path = '';
    	$target_dir = "../images/products/big_product_img/";
    	if(isset($_FILES["file"]["name"])) 
    	{
    		if($_FILES["file"]["name"]!='')
    		{
    			$check = getimagesize($_FILES["file"]["tmp_name"]);
    			if($check) 
    			{
    				//echo "File is an image - " . $check["mime"] . ".";
    				$uploadOk = 1;
    			}
    			else 
    			{
    				//echo "File is not an image.";
    				//echo "<script type='text/javascript'>alert('Sorry, File is not an image.');</script>";
    				$uploadOk = 1;
    			}
    			$target_file = $target_dir .$productCode.'_'.basename($_FILES["file"]["name"]);
    			//big size image//
    			$path_360 = '../images/products/big_product_img/'.str_replace(' ', '_',$productCode.'_'.$_FILES["file"]["name"]);
    			resize360(360,$path_360);
    			$path_100 = '../images/products/thumb/'.str_replace(' ', '_',$productCode.'_'.$_FILES["file"]["name"]);			
    			resize(100,$path_100);
    			$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
    			$path =str_replace(' ', '_',$productCode.'_'.$_FILES["file"]["name"]);
    		}else{
    		$target_file='';
    		}
    	}
    		//$uploadOk = 1;
        try{    
            $conn->begin_transaction();
            $sql = "INSERT INTO tbl_products (tbl_brandsId, productCode, productName,modelNo,categoryId, units, productDescriptions,minSalePrice,maxSalePrice,currentStock, minimumStock, standardSalesUnit,purchasePrice,productImage, status, deleted, createdBy, createdDate, carton_unit, carton_type, package_unit, carton_name, type, stock_check, items_in_box) "
                        . "VALUES ('$brandId', '$productCode', '$productName','$modelNo','$categoryId', '$units', '$productNote','$minimumSalePrice','$maximumSalePrice','', '$minimumStock', '$standardSalesUnit','$purchasePrice','$path', 'Active', 'No', '$loginID', '$toDay', '$cartoon_unit', '$carton_type', '$package_name', '$carton_name', '$productType', '$stockCheck', '$itemsInBox')";
            if ($conn->query($sql)) {
    			if(count($spacNameArray) > 0){
    				$productId = $conn->insert_id;
    				for($i = 0; $i < count($spacNameArray); $i++) {
    					$spacNameEntry = $spacNameArray[$i];
    					$spacValueEntry =$spacValueArray[$i]; 
    					if($i == count($spacNameArray)-1){
    						$spacNameEntry = substr($spacNameEntry, 0, strlen($spacNameEntry)-3);
    						$spacValueEntry = substr($spacValueEntry, 0,strlen($spacValueEntry)-3);
    					}
    					if($spacNameEntry != ''){
    						$sql = "INSERT INTO tbl_productspecification (tbl_productsId, specificationName, specificationValue, deleted, lastInsertedBy, insertDate) "
    							. "VALUES ('$productId', '$spacNameEntry', '$spacValueEntry', 'No',  '$loginID', '$toDay')";
    						$conn->query($sql);
    						//echo $sql;
    					}
    				}
    			}
    			//echo json_encode('Success');
                /*$sql = "INSERT INTO tbl_currentStock (tbl_productsId, tbl_wareHouseId, currentStock, lastUpdateDate, lastUpdatedBy, deleted, initialStock) 
                        VALUES ('$productId', '$wareHouseId', currentStock+$openStock, NOW(), '$loginID', 'No', '$openStock')";
                if($conn->query($sql)){
                    
                }*/
    			$conn->commit();
    			echo json_encode('Success');
            } else {
                echo json_encode($conn->error);
            }
    		
        }catch(Exception $e){
    		$conn->rollBack();
    		echo 'RollBack';
    	}
        $conn->close();
    }
    // Update Customer or Supplier
    if (isset($_POST['updateProduct'])) {
        $loginID = $_SESSION['user'];
        $productId = $_POST['productId'];
    	//$wareHouseId = $_POST['wareHouseId'];
        $productName = $_POST['productName'];
        $productCode = $_POST['productCode'];
        //$openStock = 0;
        $minimumStock = $_POST['minimumStock'];
        $brandId = $_POST['brandId'];
        $categoryId = $_POST['categoryId'];
        $units = $_POST['units'];
        $carton_unit = $_POST['carton_unit'];
        $carton_type = $_POST['carton_type'];
        $package_name = $_POST['package_name'];
        $carton_name = $_POST['carton_name'];
        //$manufacturingDate = $_POST['manufacturingDate'];
        //$expiryDate = $_POST['expiryDate'];
        $standardSalesUnit = $_POST['standardSalesUnit'];
        $purchasePrice = $_POST['purchasePrice'];
        $minimumSalePrice = $_POST['minimumSalePrice'];
        $maximumSalePrice = $_POST['maximumSalePrice'];
        $productNote = $_POST['productNote'];
        //$lotNo = $_POST['lotNo'];
        $modelNo = $_POST['modelNo'];
        $status = $_POST['status'];
        $spacId = $_POST['spacId'];
        $spacName = $_POST['spacName'];
        $spacValue = $_POST['spacValue'];
        $spacIdArray = explode("@!@,",$spacId);
        $spacNameArray = explode("@!@,",$spacName);
        $spacValueArray = explode("@!@,",$spacValue);
    	
    	
    	
    	try{
    	    
        	/*$sql = "select openStock from tbl_products where id='$productId'";
        	$result = $conn->query($sql);
        	$previousOpenStock = 0;
        	while($row =  $result->fetch_assoc())
        	{
        		$previousOpenStock = $row['openStock'];
        	}*/
        	// purchasePrice
        	$conn->begin_transaction();
            $sql = "UPDATE tbl_products set tbl_brandsId='$brandId',productCode='$productCode',productName='$productName',
                    modelNo='$modelNo',categoryId='$categoryId',units='$units',productDescriptions='$productNote',minSalePrice='$minimumSalePrice',maxSalePrice='$maximumSalePrice',
                    minimumStock='$minimumStock',standardSalesUnit='$standardSalesUnit',purchasePrice='$purchasePrice',status='$status', carton_unit='$carton_unit', carton_type='$carton_type', 
                    package_unit='$package_name', carton_name='$carton_name'";
    		/*if(isset($_FILES['file']['name'])){
            $filename = $_FILES['file']['name']; 
        	if($filename != ''){*/
    			$imageFileType = '';
    			$path = '';
    			$target_dir = "../images/products/big_product_img/";
    			if(isset($_FILES["file"]["name"])) 
    			{
    				if($_FILES["file"]["name"]!='')
    				{
    					$check = getimagesize($_FILES["file"]["tmp_name"]);
    					if($check) 
    					{
    						//echo "File is an image - " . $check["mime"] . ".";
    						$uploadOk = 1;
    					}
    					else 
    					{
    						//echo "File is not an image.";
    						//echo "<script type='text/javascript'>alert('Sorry, File is not an image.');</script>";
    						
    						
    						$uploadOk = 1;
    					}
    					$target_file = $target_dir .$productCode.'_'.basename($_FILES["file"]["name"]);
    					//big size image//
    					$path_360 = '../images/products/big_product_img/'.str_replace(' ', '_',$productCode.'_'.$_FILES["file"]["name"]);
    					resize360(360,$path_360);
    					$path_100 = '../images/products/thumb/'.str_replace(' ', '_',$productCode.'_'.$_FILES["file"]["name"]);			
    					resize(100,$path_100);
    					$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
    					$path =str_replace(' ', '_',$productCode.'_'.$_FILES["file"]["name"]);
    					$sql .= ",productImage='$path'"; 
    				}else{
    				$target_file='';
    				}
    				
    			}
                /*$location = "../images/products/".$filename;
                $uploadOk = 1; 
                if($uploadOk == 0){ 
                   $sql .= ",productImage='$filename'"; 
                }else{ 
                   // Upload file
                   if(move_uploaded_file($_FILES['file']['tmp_name'], $location)){ 
                      
                   }else{ 
                      echo 'Error in files';
                   } 
                } */
        	/*}
    		}*/
        	
        	$sql .= ",lastUpdatedDate='$toDay',lastUpdatedBy='$loginID' where id='$productId'";
            
            if ($conn->query($sql)) {
                for($i = 0; $i < count($spacNameArray); $i++) {
                    $spacNameEntry = $spacNameArray[$i];
                    $spacValueEntry =$spacValueArray[$i];
                    $spacIdEntry =$spacIdArray[$i];
                    if($i == count($spacNameArray)-1){
                        $spacNameEntry = substr($spacNameEntry, 0, strlen($spacNameEntry)-3);
                        $spacValueEntry = substr($spacValueEntry, 0,strlen($spacValueEntry)-3);
                        $spacIdEntry = substr($spacIdEntry, 0,strlen($spacIdEntry)-3);
                    }
                    if($spacIdEntry == "0"){
    					if($spacNameEntry != ''){
    						$sql = "INSERT INTO tbl_productspecification (tbl_productsId, specificationName, specificationValue, deleted, lastInsertedBy, insertDate)
    						 VALUES ('$productId', '$spacNameEntry', '$spacValueEntry', 'No',  '$loginID', '$toDay' )";    
    						 $conn->query($sql);
    					}
                    }else{
    					if($spacNameEntry != ''){
    						//$sql = "UPDATE tbl_productspecification SET  specificationName='$spacNameEntry', specificationValue='$spacValueEntry', deleted='No', lastUpdatedDate=Now(), lastUpdateddBy='$loginID') 
    						//where id='$spacIdEntry'";
    						$sql = "UPDATE tbl_productspecification SET  specificationName='$spacNameEntry', specificationValue='$spacValueEntry', deleted='No', lastUpdatedDate='$toDay', lastUpdatedBy='$loginID' 
    						where id='$spacIdEntry'";
    						$conn->query($sql);
    					}
                    }
                    
                }
                //$stock = $openStock - $previousOpenStock;
                //$sql = "SELECT id from tbl_currentStock where tbl_productsId='$productId' and tbl_wareHouseId='$wareHouseId'";
                //$result = $conn->query($sql);
                /*if ($result->num_rows > 0) {
                    $sql = "update tbl_currentStock set currentStock = currentStock+$stock, lastUpdateDate=NOW(),lastUpdatedBy='$loginID',initialStock='$openStock'  where tbl_productsId='$productId' and tbl_wareHouseId='$wareHouseId'";
            	}else{
            	    $sql = "INSERT INTO tbl_currentStock (tbl_productsId, tbl_wareHouseId, currentStock, lastUpdateDate, lastUpdatedBy,initialStock, deleted) 
                        VALUES ('$productId', '$wareHouseId', currentStock+$openStock, NOW(), '$loginID','$openStock', 'No')";
                
            	}*/
            	/*if($conn->query($sql)){
            	    
                }else{
                    echo json_encode($conn->error);
                }*/
    			$conn->commit();
    			echo json_encode('Success');
            } else {
                echo json_encode($conn->error);
            }
    	}catch(Exception $e){
    		$conn->rollBack();
    		echo 'RollBack';
    	}
        //header('location: manage-view.php?page='.$unitType);
    }
    if (isset($_POST['deleteSpecification'])) {
        $loginID = $_SESSION['user'];
        $id = $_POST['specificationId'];
    	$rows[] = null;
        $sql = "UPDATE tbl_productspecification SET deleted='Yes', deletedDate='$toDay', deletedBy = '$loginID' WHERE id = '$id'";
        if ($conn->query($sql)) {
            $sql = "SELECT * FROM tbl_productspecification WHERE deleted='No' AND tbl_productsId=(SELECT tbl_productsId FROM tbl_productspecification WHERE id='$id')";
    		//echo json_encode($sql);
            $query = $conn->query($sql);
    		$i = 0;
        	while($row =  $query->fetch_assoc())
        	{
        		$rows[$i]= $row;
    			$i++;
        	}
            //$_SESSION['success'] = $unitType . ' Deleted successfully';
        } else {
            json_encode($conn->error);;
        }
        echo json_encode($rows);
        //header('location: manage-view.php?page='.$unitType);
    }
}
?>