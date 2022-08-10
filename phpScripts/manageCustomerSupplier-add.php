<?php
$conPrefix = '../';
include $conPrefix . 'includes/session.php';

date_default_timezone_set('Asia/Dhaka');
$toDay = (new DateTime($test))->format("Y-m-d H:i:s");

// add customer
if (isset($_POST['saveCustomerSupplier'])) {
    $loginID = $_SESSION['user'];
	$AddTblType = $_POST['TblType'];
    $AddCustomer = $_POST['CustomerName'];
    $AddEmailAddress = $_POST['EmailAddress'];
    $AddContactPerson = $_POST['ContactPerson'];
    $AddPhoneNumber = $_POST['PhoneNumber'];
    $AddaltPhoneNumber = $_POST['altPhoneNumber'];
    $AddCountryName = $_POST['CountryName'];
    $AddCityName = $_POST['CityName'];
    $AddlocationArea = $_POST['LocationArea'];
    $AddCustomerType = $_POST['CustomerType'];
    $AddCustomerStatus = $_POST['CustomerStatus'];
    $AddCreditLimit = $_POST['CreditLimit'];
    $AddAddress = $_POST['Address'];
    $CustomerSalesType = $_POST['CustomerSalesType'];
    
    
	$partyCode=0;
    $sql = "INSERT INTO tbl_party (partyName,tblCountry,tblCity,locationArea,partyAddress,partyCode,partyType,contactPerson,partyPhone,partyAltPhone,partyEmail,remarks,status,creditLimit,currentCreditLimit,tblType,userType,createdDate,createdBy,customerSalesType) 
				VALUES ('$AddCustomer','$AddCountryName','$AddCityName','$AddlocationArea','$AddAddress','$partyCode','$AddCustomerType','$AddContactPerson','$AddPhoneNumber','$AddaltPhoneNumber','$AddEmailAddress','Test','Active','$AddCreditLimit','$AddCreditLimit','$AddTblType','','$toDay','$loginID','$CustomerSalesType')";
    
    if ($conn->query($sql)) {
        $_SESSION['success'] = 'Entry added successfully';
    } else {
        $_SESSION['error'] = $conn->error;
    }
    echo json_encode('Success');
    //header('location: manage-view.php?page='.$unitType);
}
// Update Customer or Supplier
if (isset($_POST['updateCustomerSupplier'])) {
    $loginID = $_SESSION['user'];
	$AddTblType = $_POST['TblType'];
	$TblUid = $_POST['TblUid'];
    $AddCustomer = $_POST['CustomerName'];
    $AddEmailAddress = $_POST['EmailAddress'];
    $AddContactPerson = $_POST['ContactPerson'];
    $AddPhoneNumber = $_POST['PhoneNumber'];
    $AddaltPhoneNumber = $_POST['altPhoneNumber'];
    $AddCountryName = $_POST['CountryName'];
    $AddCityName = $_POST['CityName'];
    $AddLocationArea = $_POST['LocationArea'];
    $AddCustomerType = $_POST['CustomerType'];
    $AddCustomerStatus = $_POST['CustomerStatus'];
    $AddCreditLimit = $_POST['CreditLimit'];
    $AddAddress = $_POST['Address'];
    $CustomerSalesType = $_POST['CustomerSalesType'];
    
    
	$partyCode=0;
	
        $sql = "UPDATE tbl_party set partyName='$AddCustomer',tblCountry='$AddCountryName',tblCity='$AddCityName',locationArea='$AddLocationArea',partyAddress='$AddAddress',partyCode='$partyCode',partyType='$AddCustomerType',contactPerson='$AddContactPerson',partyPhone='$AddPhoneNumber',partyAltPhone='$AddaltPhoneNumber',partyEmail='$AddEmailAddress',remarks='',status='$AddCustomerStatus',creditLimit='$AddCreditLimit',currentCreditLimit=(currentCreditLimit+$AddCreditLimit-creditLimit),tblType='$AddTblType',userType='',lastUpdatedDate='$toDay',lastUpdatedBy='$loginID',customerSalesType='$CustomerSalesType' where id='$TblUid'";
    if ($conn->query($sql)) {
        $_SESSION['success'] = 'Entry Updated successfully';
    } else {
        $_SESSION['error'] = $conn->error;
    }
    echo json_encode('Success');
    //header('location: manage-view.php?page='.$unitType);
}


// Update Customer or Supplier
if (isset($_POST['updateCustomerSupplierBangla'])) {
    $loginID = $_SESSION['user'];
	
	$TblUid = $_POST['TblUid'];
    $party_name_bangla = $_POST['edit_partyNameBangla'];
    $contact_person_bangla = $_POST['edit_contactPersonBangla'];
    $contact_number_bangla = $_POST['edit_partyPhoneBangla'];
    $location_bangla = $_POST['edit_locationAreaBangla'];
    $party_address_bangla = $_POST['edit_partyAddressBangla'];
   
   
    
    $sql = "UPDATE tbl_party set party_name_bangla='$party_name_bangla',contact_person_bangla='$contact_person_bangla',contact_number_bangla='$contact_number_bangla', 
    location_bangla='$location_bangla',party_address_bangla='$party_address_bangla'
    where id='$TblUid'";
    if ($conn->query($sql)) {
        $_SESSION['success'] = 'Entry Updated successfully';
    } else {
        $_SESSION['error'] = $conn->error;
    }
    echo json_encode('Success');
    //header('location: manage-view.php?page='.$unitType);
}

/*
if (isset($_POST['editUnit'])) {
    $loginID = $_SESSION['user'];
    $id = $_POST['id'];
    $UnitName = $_POST['UnitName'];
    $UnitDescription = $_POST['UnitDescription'];
    $Ustatus = $_POST['Ustatus'];
    $unitType = $_POST['type'];
    
        $sql = "UPDATE `tbl_units` SET unitName='$UnitName',unitDesc='$UnitDescription',status='$Ustatus',lastUpdatedDate='$toDay',lastUpdatedBy='$loginID' WHERE id = '$id'";
    if ($conn->query($sql)) {
        $_SESSION['success'] = $unitType . ' Updated successfully';
    } else {
        $_SESSION['error'] = $conn->error;
    }
    echo json_encode('Success');
    //header('location: manage-view.php?page='.$unitType);
}*/
?>