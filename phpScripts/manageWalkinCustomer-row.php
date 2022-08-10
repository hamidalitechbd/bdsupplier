<?php
$conPrefix = '../';
include $conPrefix . 'includes/session.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];
   
    $sql = "SELECT * FROM `tbl_walkin_customer` WHERE id = '$id'";
    
	if ($conn->query($sql)) {
        $_SESSION['success'] = $id . ' Updated successfully';
    } else {
        $_SESSION['error'] = $conn->error;
    }
    
    $query = $conn->query($sql);
    $row = $query->fetch_assoc();

    echo json_encode($row);
}
?>