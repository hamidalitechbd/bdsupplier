<?php
$conPrefix = '../';
include $conPrefix . 'includes/session.php';
//session_start();
if(isset($_POST["action"])){
}else{
    $sql = "SELECT id, customerCode, customerName, phoneNo, contactEmail, status, customerAddress
            FROM tbl_walkin_customer
            WHERE deleted = 'No'";
    $result = $conn->query($sql);
    $i=1;
    $output = array('data' => array());
    while ($row = $result->fetch_array()) {
        $walkInCustomerId = $row['id'];
        // active 
        if ($row['status'] == 'Active') {
            // activate status
            $status = "<label class='label label-success'>" . $row['status'] . "</label>";
        } else {
            // deactivate status
            $status = "<label class='label label-danger'>" . $row['status'] . "</label>";
        }
        $button = '<a href="#" onclick="editWalkInCustomer(' . $walkInCustomerId . ')"><button class="btn btn-warning btn-sm btn-flat"><i class="fa fa-edit"></i> Edit</button></a>';
        $output['data'][] = array(
            $i++,
            $row['customerName'],
            $row['phoneNo'],
            $row['contactEmail'],
            $row['customerAddress'],
            $status,
            $button
        );
    } // /while 
    echo json_encode($output);  
}
?>