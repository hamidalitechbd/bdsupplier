<?php
$conPrefix = '../';
include $conPrefix . 'includes/session.php';

date_default_timezone_set('Asia/Dhaka');
$toDay = (new DateTime($test))->format("Y-m-d H:i:s");

// add bank Account
//"="+transportName+"&="+contactPerson+"&="+contactNo+"&="+contactEmail+"&address="+address+"&="+remarks+"&=1";
if (isset($_POST['addTransportInfo'])) {
    $loginID = $_SESSION['user'];
    $transportName = $_POST['transportName'];
    $contactPerson = $_POST['contactPerson'];
    $contactNo = $_POST['contactNo'];
    $contactEmail = $_POST['contactEmail'];
    $remarks = $_POST['remarks'];
    $address = $_POST['address'];
    $sql = "SELECT id 
            FROM tbl_transportInfo
            WHERE contactNo='$contactNo'
            AND deleted='No'";
    $result = $conn->query($sql);
    if($result->num_rows == 0){
        $sql = "INSERT INTO tbl_transportInfo (transportName, contactPerson, createdBy, contactNo, email, remarks, address,createdDate) 
                VALUES ('$transportName', '$contactPerson', '$loginID', '$contactNo', '$contactEmail', '$remarks', '$address','$toDay');";
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
if (isset($_POST['updateTransportInfo'])) {
    $loginID = $_SESSION['user'];
    $id = $_POST['id'];
    $transportName = $_POST['transportName'];
    $contactPerson = $_POST['contactPerson'];
    $contactNo = $_POST['contactNo'];
    $contactEmail = $_POST['contactEmail'];
    $remarks = $_POST['remarks'];
    $address = $_POST['address'];
    $status = $_POST['status'];
    $sql = "SELECT id 
            FROM tbl_transportInfo 
            where deleted='No' 
            and contactNo='$contactNo'
            AND id <> '$id'";
    $result = $conn->query($sql);
    if($result->num_rows == 0){
        $sql = "UPDATE tbl_transportInfo 
                    set transportName='$transportName', 
                    contactPerson='$contactPerson', 
                    lastUpdatedDate='$toDay',
                    lastUpdatedBy='$loginID',
                    contactNo='$contactNo', 
                    email='$contactEmail', 
                    remarks='$remarks',
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
//Update Transport bangla Info
if (isset($_POST['updateTransportInfoBangla'])) {
    $loginID = $_SESSION['user'];
    $id = $_POST['id'];
    $transportName = $_POST['transport_name_bangla'];
    $contactPerson = $_POST['contact_person_bangla'];
    $contactNo = $_POST['edit_contactNoBangla'];
    $address = $_POST['address_bangla'];
    
        $sql = "UPDATE tbl_transportInfo  set transport_name_bangla='$transportName', contact_person_bangla='$contactPerson', contact_number_bangla='$contactNo',address_bangla='$address' WHERE id = '$id'";
	    
	    if($conn->query($sql)){
            echo json_encode('Success');	        
	    }else{
            echo json_encode($conn->error);		        
	    }
    
}
?>