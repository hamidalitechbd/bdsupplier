<?php
$conPrefix = '../';
include $conPrefix . 'includes/session.php';
include('resize_image_product.php');
if (isset($_POST['saveUser'])) {
    $loginID = $_SESSION['user'];
    $userFullName = $_POST['userFullName'];
    $userPhone = $_POST['userPhone'];
    $userMail = $_POST['userMail'];
    $userGender = $_POST['userGender'];
    $userName = $_POST['userName'];
    $userType = $_POST['userType'];
    $userDesignation = $_POST['userDesignation'];
    $userStatus = $_POST['userStatus'];
    $userAddress = $_POST['userAddress'];
    $nidNumber = $_POST['nidNumber'];
    $printPhone = $_POST['printPhone'];
    $printMobile = $_POST['printMobile'];
    
    $imageFileType = '';
	$path = '';
	$target_dir = "../images/users/big_user_img/";
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
			$target_file = $target_dir .$userName.'_'.basename($_FILES["file"]["name"]);
			//big size image//
			$path_360 = '../images/products/big_user_img/'.str_replace(' ', '_',$userName.'_'.$_FILES["file"]["name"]);
			resize360(360,$path_360);
			$path_100 = '../images/products/thumb/'.str_replace(' ', '_',$userName.'_'.$_FILES["file"]["name"]);			
			resize(100,$path_100);
			$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
			$path =str_replace(' ', '_',$userName.'_'.$_FILES["file"]["name"]);
		}else{
		    $target_file='';
		}
	}
    
    $sql = "SELECT id 
            FROM tbl_users
            WHERE username='$userName'
            AND deleted='No'";
    $commonPassword = 'user123';
	$password = password_hash($commonPassword, PASSWORD_DEFAULT);
    $result = $conn->query($sql);
    if($result->num_rows == 0){
        $sql = "INSERT INTO tbl_users (fname, username, password, images, mobile, print_phone, print_mobile, email, address, gender, tbl_accountTypeId, priority, designation, accountStatus, createdBy, nid)
                VALUES ('$userFullName','$userName','$password','$path','$userPhone','$printPhone','$printMobile','$userMail','$userAddress','$userGender','$userType','0','$userDesignation','$userStatus','$loginID','$nidNumber')";
	    if($conn->query($sql)){
            echo json_encode('Success');	        
	    }else{
            echo json_encode($conn->error);		        
	    }
    }else{
        echo json_encode("This User Name is already exists");
    }
}
//Update User Account
if (isset($_POST['updateUser'])) {
    $loginID = $_SESSION['user'];
    $id = $_POST['id'];
    
    $userFullName = $_POST['userFullName'];
    $userPhone = $_POST['userPhone'];
    $userMail = $_POST['userMail'];
    $userGender = $_POST['userGender'];
    $userName = $_POST['userName'];
    $userType = $_POST['userType'];
    $userDesignation = $_POST['userDesignation'];
    $userStatus = $_POST['userStatus'];
    $userAddress = $_POST['userAddress'];
    $nidNumber = $_POST['nidNumber'];
    $printPhone = $_POST['printPhone'];
    $printMobile = $_POST['printMobile'];
    
    //$sql = "SELECT id FROM tbl_users where deleted='No' and username='$userName' AND id <> '$id'";
    //$result = $conn->query($sql);
    //if($result->num_rows == 0){
        $sql = "UPDATE tbl_users 
                SET fname='$userFullName',
                mobile='$userPhone',
                print_phone='$printPhone',
                print_mobile='$printMobile',
                nid='$nidNumber',
                email='$userMail',
                address='$userAddress',
                gender='$userGender',
                tbl_accountTypeId='$userType',
                designation='$userDesignation',
                accountStatus='$userStatus',
                lastUpdatedDate=NOW(),
                lastUpdatedBy='$loginID'";
                
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
					$uploadOk = 1;
				}
				else 
				{
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
				$sql .= ",images='$path'"; 
			}else{
			$target_file='';
			}
			
		}
		$sql .= " WHERE id = '$id'";
	    if($conn->query($sql)){
            echo json_encode('Success');	        
	    }else{
            echo json_encode($conn->error.$sql);		        
	    }
    //}else{
       // echo json_encode("This user type is already exists");
    //}
}
//Reset password
if (isset($_POST['resetPassword'])) {
    $loginID = $_SESSION['user'];
    $userName = $_POST['userName'];
    $userPassword = $_POST['userPassword'];
    $password = password_hash($userPassword, PASSWORD_DEFAULT);
    
    
        $sql = "UPDATE `tbl_users` SET `password` = '$password' WHERE `tbl_users`.`id` = '".$userName."'";
        if($conn->query($sql)){
            echo json_encode('Success');	        
	    }else{
            echo json_encode($conn->error.$sql);		        
	    }
    }else{
       
    }
?>