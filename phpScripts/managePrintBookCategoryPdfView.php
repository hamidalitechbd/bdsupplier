<?php
$conPrefix = '../';
include $conPrefix . 'includes/conn.php';

if (isset($_POST['action'])) {
    $action = $_POST['action'];
    if ($action == "savePdf") {
        $id = $_POST['id'];
        $tbl_printbookID = $_POST['tbl_printbookID'];
        $now = new DateTime();
        $date = $now->format('Y-m-d h:i:sa');
        $loginID = 2;

        //==== Start File Upload===// 
        $path = '';
        $target_dir = "../images/pdf/";
        $path = str_replace(' ', '_', $id . '_' . $_FILES["file"]["name"]);
        $fileName = $id . $tbl_printbookID . time() . $path;
        $target_file = $target_dir . $fileName;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);
        //==== End File Upload===// 

        $sql = "INSERT INTO `tbl_pdf`(`tbl_printbook_id`, `tbl_printbook_category_id`, `pdf_link`, `created_date`, `created_by`) VALUES ('$id','$tbl_printbookID','$fileName','$date','$loginID')";

        if ($conn->query($sql)) {
            echo json_encode('PDF Successfully Added');
        } else {
            echo json_encode('Error');
        }
    }

    if ($action == "changeStatus") {
        $id = $_POST['id'];
        $status = $_POST['status'];
        $now = new DateTime();
        $updatedDate = $now->format('Y-m-d h:i:s a');
        $loginID = 3;

         if ($status == 'Active') {
            $status = 'Inactive';
        } else {
            $status = 'Active';
        }

        $sql = "UPDATE `tbl_pdf` SET `status`='$status',`updated_date`='$updatedDate',`updatedby`='$loginID' WHERE id='$id'";
        if ($conn->query($sql)) {
            echo json_encode('Status ! Successfully Changed');
        } else {
            echo json_encode("Error");
        }
    }
} else {
    //$id = $_GET['id'];
    $sql = "SELECT tbl_pdf.id,tbl_pdf.tbl_printbook_id,tbl_printbook.id as tbl_printbookId,tbl_printbook.book_name,tbl_category.categoryName,tbl_brands.brandName,tbl_pdf.pdf_link,tbl_pdf.status 
    FROM `tbl_pdf`
    INNER JOIN tbl_printbook ON tbl_printbook.id=tbl_pdf.tbl_printbook_id
    INNER JOIN tbl_printbook_category ON tbl_printbook_category.id=tbl_pdf.tbl_printbook_category_id
    INNER JOIN tbl_brands ON tbl_brands.id=tbl_printbook_category.tbl_brand_id
    INNER JOIN tbl_category ON tbl_category.id=tbl_printbook_category.tbl_category_id";
    $result = $conn->query($sql);
    $i = 1;
    $output = array('data' => array());
    while ($row = $result->fetch_array()) {

        // active 
        if ($row['status'] == 'Active') {
            // activate status
            $status = "<label class='label label-success btn' onclick='changeStatus(".$row['id'].",".'"'.$row['status'].'"'.")'>" . $row['status'] . "</label>";
        } else {
            // deactivate status
            $status = "<label class='label label-danger btn ' onclick='changeStatus(".$row['id'].",".'"'.$row['status'].'"'.")'>" . $row['status'] . "</label>";
        }
        $sendMessage = "<a href='sendWhatsappMessage.php?mobile=01863982233&message=welcome'>Send Message</a>";
        $id = $row['id'];
        $tbl_printbookId = $row['tbl_printbook_id'];

        $downloadLink = '<a href="/images/pdf/'.$row['pdf_link'].'" target="_blank"><i class="fa fa-download"></i> Download CATELOGUE Pdf </a>';


        $output['data'][] = array(
            $i++,
            $row['book_name'] . '<br>Category : ' . $row['categoryName'] . '<br>Brand : ' . $row['brandName'],
            $downloadLink,
            $status.$sendMessage
        );
    } // /while 
    echo json_encode($output);
}

//----------Start load Brand---------------------------//

//----------End load Brand-----------------------------//
$conn->close();
