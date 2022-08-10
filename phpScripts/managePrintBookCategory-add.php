<?php
$conPrefix = '../';
include $conPrefix . 'includes/session.php';
include('resize_image_product.php');
//------------------  Start Save PrintBook Category  ------------------//
if (isset($_POST['savePrintBookCategory'])) {
    $loginID = $_SESSION['user'];
    $addPrintBookId = $_POST['addPrintBookId'];
    $addCategoryId = $_POST['addCategoryId'];
    $addBrandId = $_POST['addBrandId'];
    $addType = $_POST['addType'];
    $addViewType = $_POST['addViewType'];
    //$addReportFooter = $_POST['addReportFooter'];
    $addPageFooter = $_POST['addPageFooter'];
    $list_offer = $_POST['list_offer'];
    $productIdArray = $_POST['productIdArray'];
    $productIdFormattedArray = explode(",", $productIdArray);
    $arrayLength = count($productIdFormattedArray);

    //Start image upload
    $target_dir = "../images/categoryBanner/";
    // get the day, month, year,
    $date =  date("Y-m-d");
    // get time
    $time = time();
    $dateTime = $date . $time;
    if (!empty($_FILES["file"]["name"])) {
        $path = str_replace(' ', '_', $dateTime . '-' . $_FILES["file"]["name"]);
        //$target_file = $target_dir . basename($_FILES["file"]["name"]);
        $target_file = $target_dir . $path;
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $check = getimagesize($_FILES["file"]["tmp_name"]);

        if ($check !== false) {
            "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            "File is not an image.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                "The file " . htmlspecialchars(basename($_FILES["file"]["name"])) . " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        $path = "broken_image.png";
    }
    $time = time();
    $dateTime = $date . $time;
    if (!empty($_FILES["file2"]["name"])) {
        $path2 = str_replace(' ', '_', $dateTime . '-' . $_FILES["file2"]["name"]);
        //$target_file = $target_dir . basename($_FILES["file"]["name"]);
        $target_file = $target_dir . $path2;
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $check = getimagesize($_FILES["file2"]["tmp_name"]);

        if ($check !== false) {
            "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            "File is not an image.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["file2"]["tmp_name"], $target_file)) {
                "The file " . htmlspecialchars(basename($_FILES["file2"]["name"])) . " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        $path2 = "";
    }
    //End image upload
    //Start insert PrintBook Category
    $addPrintBookCategoryId = 0;
    $sql = "INSERT INTO tbl_printbook_category (tbl_printbook_id, banner, tbl_category_id, tbl_brand_id, type, viewtype, page_foooter, created_by, banner2, list_offer)
                VALUES ('$addPrintBookId','$path','$addCategoryId','$addBrandId','$addType','$addViewType','$addPageFooter','$loginID','$path2','$list_offer')";
    if ($conn->query($sql)) {
        //echo json_encode('Success');
        $addPrintBookCategoryId = $conn->insert_id; // last_id
    } else {
        json_encode($conn->error);
    }
    //End insert PrintBook Category

    //Start PrintBook Product
    $now = new DateTime();
    //Start PrintBook Category Last Inserted
    $sql = "Select * from tbl_printbook_category  where id = (Select max(id) from tbl_printbook_category) ";
    $result = $conn->query($sql);
    $data = $result->fetch_array();
    //Start PrintBook Category Last Inserted
    $printBookCategoryId = $data['id'];
    $viewGroup = $data['viewtype'];
    $count = 0;
    foreach ($productIdFormattedArray as $productId) {

        $sql = "INSERT INTO tbl_print_book_product (tbl_product_id, tbl_print_book_category_id, view_group, created_by, created_date)
                VALUES ('$productId','$printBookCategoryId','$viewGroup','$loginID','$toDay')";
        $conn->query($sql);
        $count++;

        /*Save printbook specification if not exists*/
        if ($addViewType  != 'List') {
            $sql_productSpec = "SELECT id 
                                FROM `tbl_print_book_spec_display` 
                                WHERE tbl_product_id='$productId' AND deleted = 'No'";
            $result_productSpec = $conn->query($sql_productSpec);
            if ($result_productSpec->num_rows == 0) {
                $sql_insertSpec = "INSERT INTO tbl_print_book_spec_display(tbl_product_id, spec_name, spec_value, spec_type, created_by, created_date) 
                SELECT tbl_productspecification.tbl_productsId, tbl_productspecification.specificationName, tbl_productspecification.specificationValue, 'Non-Price', '$loginID', '$toDay'
                FROM tbl_productspecification
                WHERE tbl_productspecification.tbl_productsId='$productId' AND tbl_productspecification.deleted='No'";
                $conn->query($sql_insertSpec);
            }
        }
    }
    if ($count == $arrayLength) {
        echo json_encode('Success');
    } else {
        echo json_encode($conn->error);
    }
    //End PrintBook Product


}
//------------------  End Save PrintBook Category  ------------------//


    if  (isset($_POST['saveDeleteCataloug'])){
        $loginID = $_SESSION['user'];
		$incentiveId = $_POST['id'];
		$now = new DateTime();
		$sql = "UPDATE tbl_printbook SET status='Inactive',deleted_by='$loginID',deleted_date='$toDay' WHERE id='$incentiveId'";
		$conn->query($sql);
		echo json_encode('Success');
	}

//------------------ Start Update PrintBook Category  ------------------//

if (isset($_POST['updatePrintBookCategory'])) {
    $loginID = $_SESSION['user'];
    $id = $_POST['id'];
    $editPrintBookId = $_POST['editPrintBookId'];
    $editCategoryId = $_POST['editCategoryId'];
    $editBrandId = $_POST['editBrandId'];
    $editType = $_POST['editType'];
    $editViewType = $_POST['editViewType'];
    $editListOffer = $_POST['editListOffer'];
    $editReportFooter = $_POST['editReportFooter'];
    $editApplicationSpecification = $_POST['editApplicationSpecification'];
    $editPageFooter = $_POST['editPageFooter'];
    $oldBannerImage = $_POST['oldBannerImage'];

    $productIdArray = $_POST['productIdArray'];
    $productIdFormattedArray = explode(",", $productIdArray);
    $arrayLength = count($productIdFormattedArray);

    $date =  date("Y-m-d");
    //----Start image upload
    if (!empty($_FILES["file"]["name"])) {
        $target_dir = "../images/categoryBanner/";
        // get the day, month, year,
        $date =  date("Y-m-d");
        // get time
        $time = time();
        $dateTime = $date . $time;
        $path = str_replace(' ', '_', $dateTime . '-' . $_FILES["file"]["name"]);
        //$target_file = $target_dir . basename($_FILES["file"]["name"]);
        $target_file = $target_dir . $path;
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $check = getimagesize($_FILES["file"]["tmp_name"]);

        if ($check !== false) {
            "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            "File is not an image.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
            $path = $oldBannerImage;
        } else {
            // if everything is ok, try to upload file
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                "The file " . htmlspecialchars(basename($_FILES["file"]["name"])) . " has been uploaded.";
                $oldImagePath =  $target_dir . $oldBannerImage;
                unlink($oldImagePath);
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        $path = $oldBannerImage;
    }
    
    
    if (!empty($_FILES["file2"]["name"])) {
        $target_dir = "../images/categoryBanner/";
        $time = time();
        $date =  date("Y-m-d");
        $dateTime = $date . $time;
        $path2 = str_replace(' ', '_', $dateTime . '-' . $_FILES["file2"]["name"]);
        //$target_file = $target_dir . basename($_FILES["file"]["name"]);
        $target_file = $target_dir . $path2;
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $check = getimagesize($_FILES["file2"]["tmp_name"]);

        if ($check !== false) {
            "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            "File is not an image.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["file2"]["tmp_name"], $target_file)) {
                "The file " . htmlspecialchars(basename($_FILES["file2"]["name"])) . " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        $path2 = "";
    }
    //----End image upload

    $sql = "UPDATE `tbl_printbook_category` SET `tbl_printbook_id`='$editPrintBookId',`banner`='$path',banner2='$path2',`tbl_category_id`='$editCategoryId',`tbl_brand_id`='$editBrandId',`report_footer`='$editReportFooter',`page_foooter`='$editPageFooter',application_specification='$editApplicationSpecification',`type`='$editType',`viewtype`='$editViewType',list_offer='$editListOffer',`updated_by`='$loginID',`updated_date`='$date' WHERE id='$id'";

    if ($conn->query($sql)) {
        $printBookCategoryId = $id;
        $viewGroup = $editViewType; // viewType as ViewGroup
        $count = 0;
        $now = new DateTime();
        //PrintBook Product
        //Delete PrintBookCategory Product
        /*$sql = "UPDATE `tbl_print_book_product` SET `deleted`='Yes',`deleted_by`='$loginID',`deleted_date`='$date' WHERE tbl_print_book_category_id='$printBookCategoryId'";
        $conn->query($sql);
        //End Delete PrintBookCategory Product
        foreach ($productIdFormattedArray as $productId) {

            $sql = "INSERT INTO tbl_print_book_product (tbl_product_id, tbl_print_book_category_id, view_group, created_by, created_date)
                VALUES ('$productId','$printBookCategoryId','$viewGroup','$loginID','$createdDate')";
            $conn->query($sql);
            $count++;
        }*/


        //if ($count == $arrayLength) {
            echo json_encode('Success');
        /*} else {
            echo json_encode($conn->error);
        }*/
        //End PrintBook Product
        //echo json_encode('Success');
    } else {
        echo json_encode($conn->error);
    }
}
//------------------ End Update PrintBook Category  ------------------//
