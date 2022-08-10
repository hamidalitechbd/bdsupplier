<?php
$conPrefix = '../';
include $conPrefix . 'includes/session.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $unitType = $_POST['type'];
    if ($unitType == "Unit") {
        $sql = "SELECT id,unitName,unitType,unitDesc,status FROM tbl_units WHERE id = '$id'";
    } else if ($unitType == "Brand") {
        $sql = "SELECT id,brandName as unitName,'".$unitType."' as unitType,brandDesc as unitDesc,status FROM tbl_brands WHERE id = '$id'";
    } else if ($unitType == "Warehouse") {
        $sql = "SELECT id,wareHouseName as unitName,'".$unitType."' as unitType,wareHouseAddress as unitDesc,status FROM tbl_warehouse WHERE id = '$id'";
    } else if ($unitType == "Category") {
        $sql = "SELECT id,categoryName as unitName,'".$unitType."' as unitType,categoryDesc as unitDesc,status FROM tbl_category WHERE id = '$id'";
    } else if ($unitType == "PaymentMethod") {
        $sql = "SELECT id,methodName as unitName,'".$unitType."' as unitType,methodDesc as unitDesc,status FROM tbl_paymentMethod WHERE id = '$id'";
    } else if ($unitType == "User Type") {
        $sql = "SELECT id,accountType as unitName,'".$unitType."' as unitType,accountDesc as unitDesc,status FROM tbl_accountType WHERE id = '$id'";
    }
    if ($conn->query($sql)) {
        $_SESSION['success'] = $unitType . ' Updated successfully';
    } else {
        $_SESSION['error'] = $conn->error;
    }
    
    $query = $conn->query($sql);
    $row = $query->fetch_assoc();

    echo json_encode($row);
}
?>