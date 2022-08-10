<?php
$conPrefix = '../';
include $conPrefix . 'includes/session.php';

$sql = "SELECT id,customerName,customerAddress,phoneNo FROM `tbl_walkin_customer` WHERE deleted='No' ORDER BY `tbl_walkin_customer`.`customerName`  ASC";

$result = $conn->query($sql);
$partyArray = array();
$partyArray[] = array("id" => "", "partyName" => "~~ Select Customer ~~");
while( $row = mysqli_fetch_array($result) ){
    $userid = $row['id'];
    $partyName = $row['customerName'].' ('.$row['phoneNo'].')';
    
    
    $partyArray[] = array("id" => $userid, "partyName" => $partyName);
}

// encoding array to json format
echo json_encode($partyArray);
?>