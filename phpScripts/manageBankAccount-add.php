<?php
$conPrefix = '../';
include $conPrefix . 'includes/session.php';

date_default_timezone_set('Asia/Dhaka');
$toDay = (new DateTime($test))->format("Y-m-d H:i:s");

// add bank Account
if (isset($_POST['addBankAccount'])) {
    $loginID = $_SESSION['user'];
    $accountNo = $_POST['accountNo'];
    $accountName = $_POST['accountName'];
    $bankName = $_POST['bankName'];
    $branchName = $_POST['branchName'];
    $swiftCode = $_POST['swiftCode'];
    $address = $_POST['address'];
    $sql = "SELECT id 
            FROM tbl_bank_account_info
            WHERE accountNo='$accountNo'
            AND deleted='No'";
    $result = $conn->query($sql);
    if($result->num_rows == 0){
        $sql = "INSERT INTO tbl_bank_account_info (accountNo, accountName, createdBy, bankName, branchName, swiftCode, address,createdDate) 
                VALUES ('$accountNo', '$accountName', '$loginID', '$bankName', '$branchName', '$swiftCode', '$address','$toDay')";
	    if($conn->query($sql)){
            echo json_encode('Success');	        
	    }else{
            echo json_encode($conn->error);		        
	    }
    }else{
        echo json_encode("This Account No is already exists");
    }
}
//Update Bank Account
if (isset($_POST['updateBankAccount'])) {
    $loginID = $_SESSION['user'];
    $id = $_POST['id'];
    $accountNo = $_POST['accountNo'];
    $accountName = $_POST['accountName'];
    $bankName = $_POST['bankName'];
    $branchName = $_POST['branchName'];
    $swiftCode = $_POST['swiftCode'];
    $address = $_POST['address'];
    $status = $_POST['status'];
    $sql = "SELECT id 
            FROM tbl_bank_account_info 
            where deleted='No' 
            and accountNo='$accountNo' 
            AND id <> '$id'";
    $result = $conn->query($sql);
    if($result->num_rows == 0){
        $sql = "UPDATE tbl_bank_account_info 
                    set accountNo='$accountNo', 
                    accountName='$accountName', 
                    lastUpdatedDate='$toDay',
                    lastUpdatedBy='$loginID',
                    bankName='$bankName', 
                    branchName='$branchName', 
                    swiftCode='$swiftCode',
                    status='$status',
                    address='$address'
                WHERE id = '$id'";
	    if($conn->query($sql)){
            echo json_encode('Success');	        
	    }else{
            echo json_encode($conn->error);		        
	    }
    }else{
        echo json_encode("This user type is already exists");
    }
}
?>