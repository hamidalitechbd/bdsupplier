<?php
$conPrefix = '../';
include $conPrefix . 'includes/session.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $partyType = $_POST['type'];
    $sql = "SELECT id,partyName,tblCountry,tblCity,locationArea,partyAddress,partyType,contactPerson,partyPhone,partyAltPhone,partyEmail,remarks,status,creditLimit,tblType,customerSalesType,
    party_name_bangla,contact_person_bangla,contact_number_bangla,location_bangla,party_address_bangla
    FROM tbl_party WHERE id = '$id'";
    
	if ($conn->query($sql)) {
        $_SESSION['success'] = $partyType . ' Updated successfully';
    } else {
        $_SESSION['error'] = $conn->error;
    }
    
    $query = $conn->query($sql);
    $row = $query->fetch_assoc();

    echo json_encode($row);
}
?>