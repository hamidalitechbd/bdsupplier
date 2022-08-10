<?php
$conPrefix = '../';
include $conPrefix . 'includes/session.php';
include('resize_image_product.php');
date_default_timezone_set('Asia/Dhaka');
$toDay = (new DateTime($test))->format("Y-m-d H:i:s");

// add unit
if (isset($_POST['addUnit'])) {
    $loginID = $_SESSION['user'];
    $UnitName = $_POST['UnitName'];
    $UnitDescription = $_POST['UnitDescription'];
    $unitType = $_POST['type'];
    if ($unitType == "Unit") {
        $sql = "select id from tbl_units where unitName='$UnitName' AND deleted='No'";
        $result = $conn->query($sql);
        if($result->num_rows == 0){
            $sql = "INSERT INTO tbl_units (unitName,unitDesc,status,createdBy,unitType,createdDate) 
				VALUES ('$UnitName','$UnitDescription','Active','$loginID','$unitType','$toDay')";
		    if($conn->query($sql)){
	            echo json_encode('Success');	        
		    }else{
                echo json_encode($conn->error);		        
		    }
        }else{
            echo json_encode("This unit name is already exists");
        }
    } else if ($unitType == "Brand") {
        $sql = "select id from tbl_brands where brandName='$UnitName' AND deleted='No'";
        $result = $conn->query($sql);
        if($result->num_rows == 0){
            $sql = "INSERT INTO tbl_brands (brandName,brandDesc,status,createdBy,createdDate) 
				VALUES ('$UnitName','$UnitDescription','Active','$loginID','$toDay')";
			if($conn->query($sql)){
	            echo json_encode('Success');	        
		    }else{
                echo json_encode($conn->error);		        
		    }
        }else{
            echo json_encode("This brand name is already exists");
        }
    } else if ($unitType == "Warehouse") {
        $sql = "select id from tbl_warehouse where wareHouseName='$UnitName' AND deleted='No'";
        $result = $conn->query($sql);
        if($result->num_rows == 0){
            $sql = "INSERT INTO tbl_warehouse (wareHouseName,wareHouseAddress,status,createdBy,createdDate) 
				VALUES ('$UnitName','$UnitDescription','Active','$loginID','$toDay')";
			if($conn->query($sql)){
	            echo json_encode('Success');	        
		    }else{
                echo json_encode($conn->error);		        
		    }
        }else{
            echo json_encode("This warehouse name is already exists");
        }
    } else if ($unitType == "Category") {
        $sql = "select id from tbl_category where categoryName='$UnitName' AND deleted='No'";
        $result = $conn->query($sql);
        if($result->num_rows == 0){
            $sql = "INSERT INTO tbl_category (categoryName,categoryDesc,status,createdBy,createdDate) 
				VALUES ('$UnitName','$UnitDescription','Active','$loginID','$toDay')";
			if($conn->query($sql)){
	            echo json_encode('Success');	        
		    }else{
                echo json_encode($conn->error);		        
		    }
        }else{
            echo json_encode("This category name is already exists");
        }
    } else if ($unitType == "PaymentMethod") {
        $sql = "select id from tbl_paymentMethod where methodName='$UnitName' AND deleted='No'";
        $result = $conn->query($sql);
        if($result->num_rows == 0){
            $sql = "INSERT INTO tbl_paymentMethod (methodName,methodDesc,status,createdBy,createdDate) 
				VALUES ('$UnitName','$UnitDescription','Active','$loginID','$toDay')";
			if($conn->query($sql)){
	            echo json_encode('Success');	        
		    }else{
                echo json_encode($conn->error);		        
		    }
        }else{
            echo json_encode("This method name is already exists");
        }
    } else if ($unitType == "User Type") {
        $sql = "select id from tbl_accountType where accountType='$UnitName' AND deleted='No'";
        $result = $conn->query($sql);
        if($result->num_rows == 0){
            $sql = "INSERT INTO tbl_accountType (accountType,accountDesc,status,createdBy,createdDate) 
				VALUES ('$UnitName','$UnitDescription','Active','$loginID','$toDay')";
		    if($conn->query($sql)){
	            echo json_encode('Success');	        
		    }else{
                echo json_encode($conn->error);		        
		    }
        }else{
            echo json_encode("This user type is already exists");
        }
    }
    
    
    //header('location: manage-view.php?page='.$unitType);
}
 if (isset($_POST['saveEditImage']) ) {
        $id = $_POST['id'];
        $type = $_POST['bType'];
        //echo json_encode($type);
        $imageFileType = '';
    	$path = '';
    	$target_dir = "../images/brand/big_brand_img/";
    	if(isset($_FILES["file"]["name"])) 
    	{
    		if($_FILES["file"]["name"]!='')
    		{
    			$check = getimagesize($_FILES["file"]["tmp_name"]);
    			if($check) 
    			{
    				$uploadOk = 1;
    			} 
    			else 
    			{
    				$uploadOk = 1;
    			}
    			$target_file = $target_dir .$id.'_'.basename($_FILES["file"]["name"]);
    			//big size image//
    			$path_360 = $target_dir.str_replace(' ', '_',$id.'_'.$_FILES["file"]["name"]);
    			resize360(360,$path_360);
    			$path_100 = '../images/brand/thumb/'.str_replace(' ', '_',$id.'_'.$_FILES["file"]["name"]);			
    			resize(100,$path_100);
    			$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
    			$path =str_replace(' ', '_',$id.'_'.$_FILES["file"]["name"]);
    		}else{
    		    $target_file='';
    		    $path = '';
    		}
    	}
        
        
         $sql = "update tbl_brands set brand_logo='$path' where id='$id'";
        
        $result = $conn->query($sql);
        if($result->num_rows == 0){
            echo json_encode('Success');	        
		    
        }else{
            echo json_encode("This user type is already exists");
        }
    }
// Update unit
if (isset($_POST['editUnit'])) {
    $loginID = $_SESSION['user'];
    $id = $_POST['id'];
    $UnitName = $_POST['UnitName'];
    $UnitDescription = $_POST['UnitDescription'];
    $Ustatus = $_POST['Ustatus'];
    $unitType = $_POST['type'];
    if ($unitType == "Unit") {
        $sql = "SELECT id FROM tbl_units where deleted='No' and unitName='$UnitName' AND id <> '$id'";
        $result = $conn->query($sql);
        if($result->num_rows == 0){
            $sql = "UPDATE `tbl_units` SET unitName='$UnitName',unitDesc='$UnitDescription',status='$Ustatus',lastUpdatedDate='$toDay',lastUpdatedBy='$loginID' WHERE id = '$id'";
		    if($conn->query($sql)){
	            echo json_encode('Success');	        
		    }else{
                echo json_encode($conn->error);		        
		    }
        }else{
            echo json_encode("This user type is already exists");
        }
        
    } else if ($unitType == "Brand") {
        $sql = "SELECT id FROM tbl_brands where deleted='No' and brandName='$UnitName' AND id <> '$id'";
        $result = $conn->query($sql);
        if($result->num_rows == 0){
            $sql = "UPDATE `tbl_brands` SET brandName='$UnitName',brandDesc='$UnitDescription',status='$Ustatus',lastUpdatedDate='$toDay',lastUpdatedBy='$loginID' WHERE id = '$id'";
		    if($conn->query($sql)){
	            echo json_encode('Success');	        
		    }else{
                echo json_encode($conn->error);		        
		    }
        }else{
            echo json_encode("This user type is already exists");
        }
    } else if ($unitType == "Warehouse") {
        $sql = "SELECT id FROM tbl_warehouse where deleted='No' and wareHouseName='$UnitName' AND id <> '$id'";
        $result = $conn->query($sql);
        if($result->num_rows == 0){
            $sql = "UPDATE `tbl_warehouse` SET wareHouseName='$UnitName',wareHouseAddress='$UnitDescription',status='$Ustatus',lastUpdatedDate='$toDay',lastUpdatedBy='$loginID' WHERE id = '$id'";
		    if($conn->query($sql)){
	            echo json_encode('Success');	        
		    }else{
                echo json_encode($conn->error);		        
		    }
        }else{
            echo json_encode("This user type is already exists");
        }
        
        
    } else if ($unitType == "Category") {
        $sql = "SELECT id FROM tbl_category where deleted='No' and categoryName='$UnitName' AND id <> '$id'";
        $result = $conn->query($sql);
        if($result->num_rows == 0){
            $sql = "UPDATE `tbl_category` SET categoryName='$UnitName',categoryDesc='$UnitDescription',status='$Ustatus',lastUpdatedDate='$toDay',lastUpdatedBy='$loginID' WHERE id = '$id'";
		    if($conn->query($sql)){
	            echo json_encode('Success');	        
		    }else{
                echo json_encode($conn->error);		        
		    }
        }else{
            echo json_encode("This user type is already exists");
        }
    } else if ($unitType == "PaymentMethod") {
        $sql = "SELECT id FROM tbl_paymentMethod where deleted='No' and methodName='$UnitName' AND id <> '$id'";
        $result = $conn->query($sql);
        if($result->num_rows == 0){
            $sql = "UPDATE `tbl_paymentMethod` SET methodName='$UnitName',methodDesc='$UnitDescription',status='$Ustatus',lastUpdatedDate='$toDay',lastUpdatedBy='$loginID' WHERE id = '$id'";
		    if($conn->query($sql)){
	            echo json_encode('Success');	        
		    }else{
                echo json_encode($conn->error);		        
		    }
        }else{
            echo json_encode("This user type is already exists");
        }
        
        
    } else if ($unitType == "User Type") {
        $sql = "SELECT id FROM tbl_accountType where deleted='No' and accountType='$UnitName' AND id <> '$id'";
        $result = $conn->query($sql);
        if($result->num_rows == 0){
            $sql = "UPDATE `tbl_accountType` SET accountType='$UnitName',accountDesc='$UnitDescription',status='$Ustatus',lastUpdatedDate='$toDay',lastUpdatedBy='$loginID' WHERE id = '$id'";
		    if($conn->query($sql)){
	            echo json_encode('Success');	        
		    }else{
                echo json_encode($conn->error);		        
		    }
        }else{
            echo json_encode("This user type is already exists");
        }
        
    }
    /*if ($conn->query($sql)) {
        $_SESSION['success'] = $unitType . ' Updated successfully';
    } else {
        $_SESSION['error'] = $conn->error;
    }
    echo json_encode('Success');*/
    //header('location: manage-view.php?page='.$unitType);
}
?>