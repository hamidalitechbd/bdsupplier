<?php
$conPrefix = '../';
include $conPrefix . 'includes/conn.php';
include('resize_image_product.php');
if (isset($_POST['savePrintBook'])) {
    //$loginID = $_SESSION['user'];
    $loginID = 1;
    $addPrintBookName = $_POST['addPrintBookName'];
    $addPrintBookDate = $_POST['addPrintBookDate'];
    $addPrintBookStatus = $_POST['addPrintBookStatus'];
    $now = new DateTime();
    $createdDate = $now->format('Y-m-d H:i:s');
    
    $sql = "SELECT id 
            FROM tbl_printbook
            WHERE book_name='$addPrintBookName'
            AND deleted='No'";

    $result = $conn->query($sql);
    if($result->num_rows == 0){
        $sql = "INSERT INTO tbl_printbook (book_name, book_date, status, created_by, created_date)
                VALUES ('$addPrintBookName','$addPrintBookDate','$addPrintBookStatus','$loginID','$createdDate')";
	    if($conn->query($sql)){
            echo json_encode('Success');	        
	    }else{
            echo json_encode($conn->error);		        
	    }
    }else{
        echo json_encode("This Book Name is already exists");
    }
}    
//Update Bank Account
if (isset($_POST['updatePrintBook'])) {
    $loginID =   1;
    $id = $_POST['id'];
    $editPrintBookName = $_POST['editPrintBookName'];
    $editPrintBookDate = $_POST['editPrintBookDate'];
    $editPrintBookStatus = $_POST['editPrintBookStatus'];
    $now = new DateTime();
    $updatedDate = $now->format('Y-m-d H:i:s');
    
    //$sql = "SELECT id FROM tbl_users where deleted='No' and username='$userName' AND id <> '$id'";
    //$result = $conn->query($sql);
    //if($result->num_rows == 0){
        $sql = "UPDATE tbl_printbook 
                SET book_name='$editPrintBookName',
                book_date='$editPrintBookDate',
                status='$editPrintBookStatus',
                lastUpdatedDate=NOW(),
                lastUpdatedBy='$loginID'";
                
       
		$sql .= " WHERE id = '$id'";
	    if($conn->query($sql)){
            echo json_encode('Success');	        
	    }else{
            echo json_encode($conn->error.$sql);		        
	    }
    //}else{
       // echo json_encode("This user type is already exists");
    //}
}

?>