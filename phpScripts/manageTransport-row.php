<?php
$conPrefix = '../';
include $conPrefix . 'includes/session.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    
    $sql = "SELECT id,tbl_transportinfo_id,transport_challan_no,challan_date FROM `tbl_challan` WHERE id='$id'";
    
    $query = $conn->query($sql);
    $row = $query->fetch_assoc();

    echo json_encode($row);
}
?>