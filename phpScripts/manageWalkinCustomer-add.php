<?php
$conPrefix = '../';
include $conPrefix . 'includes/session.php';
date_default_timezone_set('Asia/Dhaka');
$toDay = (new DateTime($test))->format("Y-m-d H:i:s");
// Update Customer or Supplier
if (isset($_POST['updateWalkinCustomer'])) {
    $loginID = $_SESSION['user'];
	$TblUid = $_POST['TblUid'];
    $AddCustomer = $_POST['CustomerName'];
    $AddPhoneNumber = $_POST['PhoneNumber'];
    $AddEmailAddress = $_POST['EmailAddress'];
    $AddCustomerStatus = $_POST['CustomerStatus'];
    $AddAddress = $_POST['Address'];
    
    
	$sql = "UPDATE tbl_walkin_customer set customerName='$AddCustomer',customerAddress='$AddAddress',phoneNo='$AddPhoneNumber',contactEmail='$AddEmailAddress',status='$AddCustomerStatus',lastUpdatedDate='$toDay',lastUpdatedBy='$loginID' where id='$TblUid'";
    
    if ($conn->query($sql)) {
        $_SESSION['success'] = 'Entry Updated successfully';
    } else {
        $_SESSION['error'] = $conn->error;
    }
    echo json_encode('Success');
    //header('location: manage-view.php?page='.$unitType);
}

?>