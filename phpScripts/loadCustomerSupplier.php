<?php
$conPrefix = '../';
include $conPrefix . 'includes/session.php';
$tblType = $_GET['tblType'];
if($tblType == "customersWithCreditLimit"){
    $sql = "SELECT tbl_party.id, tbl_party.tblCity, tbl_party.locationArea, tbl_party.partyName, ifnull(tbl_party.creditLimit,0)-IFNULL(dbt_dues.amount,0) AS creditLimit, ifnull(tbl_party.creditLimit,0) as initialLimit
            FROM tbl_party
            LEFT OUTER JOIN (SELECT Sum(CASE tbl_paymentVoucher.type 
                       WHEN 'partyPayable' THEN tbl_paymentVoucher.amount
                       WHEN 'paymentReceived' THEN -tbl_paymentVoucher.amount 
                       WHEN 'adjustment' THEN  -tbl_paymentVoucher.amount
                       WHEN 'payable' THEN -tbl_paymentVoucher.amount 
                       WHEN 'payment' THEN tbl_paymentVoucher.amount
                       WHEN 'paymentAdjustment' THEN tbl_paymentVoucher.amount 
                       WHEN 'discount' THEN -tbl_paymentVoucher.amount
                       END) AS amount, tbl_partyId 
            FROM tbl_paymentVoucher 
            WHERE deleted='No' AND status='Active' AND customerType='Party'
            GROUP BY tbl_partyId) AS dbt_dues ON dbt_dues.tbl_partyId = tbl_party.id
            WHERE tbl_party.deleted='No' AND tbl_party.status='Active' AND tbl_party.tblType <> 'Suppliers'
            ORDER BY tbl_party.id";
}else{
    $sql = "SELECT id,partyName,tblCity,locationArea FROM `tbl_party` WHERE status='Active' AND tblType<>'$tblType' ORDER BY `id`  DESC";
}
$result = $conn->query($sql);
$creditLimit = "";
$initialLimit = "";
$partyArray = array();
$partyArray[] = array("id" => "", "partyName" => "~~ Select Customer ~~", "creditLimit" => "~~ Select Customer to credit limit ~~");
while( $row = mysqli_fetch_array($result) ){
    $userid = $row['id'];
    $partyName = $row['partyName'].' ('.$row['tblCity'].' - '.$row['locationArea'].')';
    
    if($tblType == "customersWithCreditLimit"){
        //$partyName .= "__".$row['creditLimit'];
        $creditLimit = $row['creditLimit'];
        $initialLimit = $row['initialLimit'];
    }
    $partyArray[] = array("id" => $userid, "partyName" => $partyName, "creditLimit" => $creditLimit, "initialLimit" => $initialLimit);
}

// encoding array to json format
echo json_encode($partyArray);
?>