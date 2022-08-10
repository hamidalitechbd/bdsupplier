<?php
$conPrefix = '../';
include $conPrefix . 'includes/session.php';
if (isset($_POST['action'])) {
    $action = $_POST['action'];
    if ($action == "loadBrandByCategory") {
        $categoryId = $_POST['categoryId'];
        $sql = "SELECT tbl_brands.id, tbl_brands.brandName FROM tbl_brands WHERE tbl_brands.id in (SELECT DISTINCT tbl_brandsId FROM `tbl_products` WHERE categoryId='$categoryId' AND tbl_products.deleted='No') AND tbl_brands.deleted='No'";
        $result = $conn->query($sql);
        $rowData = '<option value=""> Select Brand </option>';
        while ($row = $result->fetch_array()) {
            $rowData .= '<option value="' . $row['id'] . '">' . $row['brandName'] . '</option>';
        } // /while 
        echo $rowData;
    } //loadBrandByCategory
    else if ($action == "loadPrintBookSpec") {
        $bookProductId = $_POST['id'];
        $sql = "SELECT * 
                FROM tbl_print_book_product 
                WHERE deleted='No' AND id='$bookProductId'";
        $result = $conn->query($sql);
        while ($row = $result->fetch_array()) {
            $id = $row['tbl_product_id'];
        }
        
        $sql = "SELECT * 
                FROM tbl_print_book_spec_display 
                WHERE deleted='No' AND tbl_product_id='$id'";
        $result = $conn->query($sql);
        $i = 1;
        $modalTable = '';
        $displayTable = '';
        while ($row = $result->fetch_array()) {
            $modalTable .= '<tr>
                <td>' . $row['spec_name'] . '</td>
                <td>' . $row['spec_value'] . '</td>
                <td>' . $row['spec_type'] . '</td>
                <td>
                    <a href="#" class="btn btn-warning btn-sm btn-flat" onclick="editProducSpec(' . $row['id'] . ')"><i class="fa fa-pencil"></i></a>
                    <a href="#" class="btn btn-danger btn-sm btn-flat" onclick="deleProducSpec(' . $row['id'] . ')"><i class="fa fa-trash"></i> </a>
                </td>
            </tr>';
            $displayTable .= '<tr>
                <td>' . $row['spec_name'] . '</td>
                <td>' . $row['spec_value'] . '</td>
            </tr>';
        }
        $tableData = array(
            'modalTable' => $modalTable,
            'displayTable' => $displayTable
        );

        echo json_encode($tableData);
    } //loadPrintBookSpec
    else if ($action == "addPrintBookProductSpec") {
        
        $addProductId = $_POST['addProductId'];
        $addSpecName = $_POST['addSpecName'];
        $specId = $_POST['specId'];
        $addSpecValue = $_POST['addSpecValue'];
        $addSpecType = $_POST['addSpecType'];
        $now = new DateTime();
        
        $sql = "SELECT * 
                FROM tbl_print_book_product 
                WHERE deleted='No' AND id='$addProductId'";
        $result = $conn->query($sql);
        while ($row = $result->fetch_array()) {
            $tbl_product_id = $row['tbl_product_id'];
        }
        if($specId==''){
             $sql = "INSERT INTO tbl_print_book_spec_display (tbl_product_id, spec_name, spec_value, spec_type, created_by, created_date)
                    VALUES ('$tbl_product_id','$addSpecName','$addSpecValue','$addSpecType','$loginID','$toDay')";
        }else{
            $sql = "update tbl_print_book_spec_display set spec_name='$addSpecName',spec_value='$addSpecValue', spec_type='$addSpecType', updated_by='$loginID', updated_date='$toDay' where id='$specId' ";
        }
        
        if ($conn->query($sql)) {
            echo json_encode('Success');
        } else {
            echo json_encode($conn->error);
        }
    } //addPrintBookProductSpec
    else if ($action == "editPrintBookProductSpec") {
        
        $id = $_POST['id'];
        $sql = "select * from tbl_print_book_spec_display WHERE id='$id' limit 0,1";
        $result= $conn->query($sql);
        
        echo json_encode($result->fetch_assoc());
        
    } //deletePrintBookProductSpec
    else if ($action == "deletePrintBookProductSpec") {
        
        //$productId = $_POST['productId'];
        $id = $_POST['id'];
        $sql = "UPDATE tbl_print_book_spec_display 
                SET deleted='Yes',
                deleted_date=NOW(),
                deleted_by='$loginID'
                 WHERE id='$id'";
        if ($conn->query($sql)) {
            echo json_encode('Success');
        } else {
            echo json_encode($conn->error);
        }
    } 
    else if ($action == "saveSpecList") {

        $productID = $_POST['productID'];
        $specValueList = $_POST['specValueLists'];
        $specHeadNames = $_POST['specHeadNames'];
        $specValueListArray = explode(",", $specValueList);
        $specHeadNamesArray = explode(",", $specHeadNames);

        $now = new DateTime();
        $len = count($specValueListArray);
        $loginID = $_SESSION['user'];
        $numberOfSpecValue = 0;

        for ($i = 0; $i < $len; $i++) {
            // $sql = "INSERT INTO tbl_print_book_spec_display (tbl_product_id, spec_value, created_by, created_date)
            //             VALUES ('$productID','$specValueListArray[$i]','$loginID','$createdDate')";
            $sql = "UPDATE `tbl_print_book_spec_display` SET `spec_value`='$specValueListArray[$i]',`updated_by`='$loginID',`updated_date`='$toDay' WHERE tbl_product_id ='$productID' AND spec_name ='$specHeadNamesArray[$i]'  AND deleted ='No'";
            if ($conn->query($sql)) {
                $numberOfSpecValue++;
            }
        }
        echo json_encode(' Successfully Spec value Inserted ');
    } //saveSpecList
    else if($action == 'updateCategoryProductsOrdering'){
        $categoryProductsIds = $_POST['categoryProductsIds'];
        $categoryProductsIdsArray = explode(",",$categoryProductsIds);
        $queryData = "";
        for($i = 0; $i < count($categoryProductsIdsArray); $i++){
            if($categoryProductsIdsArray[$i] != ""){
                $ordering = $i+1;
                $sql = "UPDATE tbl_print_book_product SET ordering='$ordering' WHERE id='$categoryProductsIdsArray[$i]' AND deleted='No'";
                $conn->query($sql);
            }
        }
        echo json_encode('Success');
    }
    else if($action == 'updateListOrderingCalculation'){
        $categoryProductsIds = $_POST['productId'];
        $categoryId = $_POST['categoryId'];
        $ordering = $_POST['serial'];
        if($ordering == 0){
            $ordering = 1;
            $sql = "SELECT tbl_printbook_category.*, tbl_printbook.id as tbl_printbookId, tbl_printbook.book_name, tbl_category.id as tbl_categoryId, tbl_category.categoryName, tbl_brands.id as tbl_brandsId, tbl_brands.brandName, tbl_print_book_product.id as tbl_print_book_productId,
                    tbl_products.id as tbl_productsId, tbl_products.productName, tbl_products.productImage, tbl_products.productCode,tbl_products.modelNo,tbl_products.minSalePrice,tbl_products.maxSalePrice, tbl_print_book_product.type as product_type, tbl_print_book_product.ad_url,tbl_print_book_product.ordering
                    FROM tbl_printbook_category 
                    INNER JOIN tbl_printbook ON tbl_printbook_category.tbl_printbook_id = tbl_printbook.id 
                    LEFT JOIN tbl_category ON tbl_printbook_category.tbl_category_id = tbl_category.id 
                    INNER JOIN tbl_brands ON tbl_printbook_category.tbl_brand_id = tbl_brands.id 
                    INNER JOIN tbl_print_book_product ON tbl_printbook_category.id = tbl_print_book_product.tbl_print_book_category_id 
                    INNER JOIN tbl_products ON tbl_print_book_product.tbl_product_id = tbl_products.id 
                    WHERE tbl_print_book_product.deleted='No' AND tbl_printbook_category.id='$categoryId'
                    ORDER BY tbl_print_book_product.ordering";
        }else{
            $sql = "SELECT tbl_printbook_category.*, tbl_printbook.id as tbl_printbookId, tbl_printbook.book_name, tbl_category.id as tbl_categoryId, tbl_category.categoryName, tbl_brands.id as tbl_brandsId, tbl_brands.brandName, tbl_print_book_product.id as tbl_print_book_productId,
                    tbl_products.id as tbl_productsId, tbl_products.productName, tbl_products.productImage, tbl_products.productCode,tbl_products.modelNo,tbl_products.minSalePrice,tbl_products.maxSalePrice, tbl_print_book_product.type as product_type, tbl_print_book_product.ad_url,tbl_print_book_product.ordering
                    FROM tbl_printbook_category 
                    INNER JOIN tbl_printbook ON tbl_printbook_category.tbl_printbook_id = tbl_printbook.id 
                    LEFT JOIN tbl_category ON tbl_printbook_category.tbl_category_id = tbl_category.id 
                    INNER JOIN tbl_brands ON tbl_printbook_category.tbl_brand_id = tbl_brands.id 
                    INNER JOIN tbl_print_book_product ON tbl_printbook_category.id = tbl_print_book_product.tbl_print_book_category_id 
                    INNER JOIN tbl_products ON tbl_print_book_product.tbl_product_id = tbl_products.id 
                    WHERE tbl_print_book_product.deleted='No' AND tbl_print_book_product.id='$categoryProductsIds'
                    ORDER BY tbl_print_book_product.ordering";
            $result = $conn->query($sql);
            while ($row = $result->fetch_array()) {
                $printBookProductId = $row['tbl_print_book_productId'];
                $sql = "UPDATE tbl_print_book_product SET ordering='$ordering' WHERE id='$printBookProductId' AND deleted='No'";
                $conn->query($sql);
                
            }
            $sql = "SELECT tbl_printbook_category.*, tbl_printbook.id as tbl_printbookId, tbl_printbook.book_name, tbl_category.id as tbl_categoryId, tbl_category.categoryName, tbl_brands.id as tbl_brandsId, tbl_brands.brandName, tbl_print_book_product.id as tbl_print_book_productId,
                    tbl_products.id as tbl_productsId, tbl_products.productName, tbl_products.productImage, tbl_products.productCode,tbl_products.modelNo,tbl_products.minSalePrice,tbl_products.maxSalePrice, tbl_print_book_product.type as product_type, tbl_print_book_product.ad_url,tbl_print_book_product.ordering
                    FROM tbl_printbook_category 
                    INNER JOIN tbl_printbook ON tbl_printbook_category.tbl_printbook_id = tbl_printbook.id 
                    LEFT JOIN tbl_category ON tbl_printbook_category.tbl_category_id = tbl_category.id 
                    INNER JOIN tbl_brands ON tbl_printbook_category.tbl_brand_id = tbl_brands.id 
                    INNER JOIN tbl_print_book_product ON tbl_printbook_category.id = tbl_print_book_product.tbl_print_book_category_id 
                    INNER JOIN tbl_products ON tbl_print_book_product.tbl_product_id = tbl_products.id 
                    WHERE tbl_print_book_product.deleted='No' AND tbl_printbook_category.id='$categoryId' AND tbl_print_book_product.ordering >= '$ordering' AND tbl_print_book_product.id<>'$categoryProductsIds'
                    ORDER BY tbl_print_book_product.ordering";
        }
        $ordering = $ordering+1;
        $result = $conn->query($sql);
        while ($row = $result->fetch_array()) {
            $printBookProductId = $row['tbl_print_book_productId'];
                $sql = "UPDATE tbl_print_book_product SET ordering='$ordering' WHERE id='$printBookProductId' AND deleted='No'";
                $conn->query($sql);
                $ordering = $ordering+1;
        }
        echo json_encode('Success');
    }
    else if ($action == 'removeProductFromBook'){
        $id = $_POST['id'];
        $sql = "UPDATE tbl_print_book_product SET deleted='Yes', deleted_date='$toDay',deleted_by='$loginID'  WHERE id='$id'";
        $conn->query($sql);
        echo json_encode('Success');
    }
    else if ($action == 'restoreProductFromBook'){
        $id = $_POST['id'];
        $sql = "UPDATE tbl_print_book_product SET deleted='No'  WHERE id='$id'";
        $conn->query($sql);
        echo json_encode('Success');
    }
    
    else if ($action == "addSpecHead") {
        $headName = $_POST['headName'];
        $editSpecHead = $_POST['editSpecHead'];
        $allProductId = $_POST['productIds'];
        $productIds = (explode(",", $allProductId));
        $numberOfInsertedRow = 1;
        $now = new DateTime();
        $loginID = $_SESSION['user'];
        if ($editSpecHead) {
            $editql = "UPDATE `tbl_print_book_spec_display` SET `spec_name`='$headName' WHERE spec_name='$editSpecHead' AND deleted='No'";
            if ($conn->query($editql)) {
                echo json_encode('SuccessUpdate');
            } else {
                echo json_encode($conn->error);
            }
        } else {

            foreach ($productIds as $productId) {
                $sql = "INSERT INTO tbl_print_book_spec_display (tbl_product_id, spec_name, created_by, created_date)
                       VALUES ('$productId','$headName','$loginID','$toDay')";
                if ($conn->query($sql)) {
                    $numberOfInsertedRow++;
                }
            }
            if ($numberOfInsertedRow > 1) {
                echo json_encode('Success');
            } else {
                echo json_encode($conn->error);
            }
        }
        //addHeadSpec
    } else if ($action == "deleteSpecHead") {
        $headName = $_POST['headName'];
        $now = new DateTime();
        $deletedDate = $now->format('Y-m-d h:i:sa');
        $loginID = $_SESSION['user'];
        $editql = "UPDATE `tbl_print_book_spec_display` SET deleted='Yes', deleted_by='$loginID', deleted_date='$deletedDate' WHERE spec_name='$headName'";
        if ($conn->query($editql)) {
            echo json_encode('SuccessDeleted');
        } else {
            echo json_encode($conn->error);
        }
        //deleteHeadSpec
    } else if ($action == "getSpecHead") {
        $id = $_POST['id'];

        $sqlSpecHead = "SELECT DISTINCT tbl_print_book_spec_display.spec_name 
        FROM `tbl_print_book_spec_display` INNER JOIN tbl_print_book_product ON tbl_print_book_spec_display.tbl_product_id = tbl_print_book_product.tbl_product_id 
        AND tbl_print_book_product.tbl_print_book_category_id = $id WHERE tbl_print_book_product.deleted='No' AND tbl_print_book_spec_display.spec_name !='' AND tbl_print_book_spec_display.deleted='No'";
        $productSpecHead = $conn->query($sqlSpecHead);
        $specHeads = '';
        $i = 1;
        while ($row_spec = $productSpecHead->fetch_array()) {
            $headName = "'" . $row_spec['spec_name'] . "'";
            $specHeads .= '<tr>
            <td>' . ($i++) . '</td>
            <td>' . $row_spec['spec_name'] . '</td>
            <td>
            <a  class="btn btn-warning btn-sm btn-flat" onclick="editSpecHead(' . $headName . ')"><i class="fa fa-pencil"></i></a>
            <a href="#" class="btn btn-danger btn-sm btn-flat" onclick="deleteSpecHead(' . $headName . ')"><i class="fa fa-trash"></i> </a>
        </td>
            </tr>';
        }

        echo json_encode(['specHeads' => $specHeads]); //getSpecHead

    } else if ($action == "getSpecList") {
        $productId = $_POST['productID'];
        $specHeadNames = $_POST['specHeadNames'];
        $specHeadNamesArray = explode(",", $specHeadNames);
        $len = count($specHeadNamesArray);

        $specValuefields = "";

        for ($i = 0; $i < $len; $i++) {

            $sqlSpecValueList = "SELECT spec_value FROM `tbl_print_book_spec_display` WHERE tbl_product_id=$productId AND spec_name='$specHeadNamesArray[$i]' AND deleted='No'";
            $result = $conn->query($sqlSpecValueList);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $specValuefields .= "<div class='col-sm-6 specListDiv'><label for='specValueList' class='control-label'>".$specHeadNamesArray[$i]." :</label><input type='text' class='form-control specValueList' id='specValueList' name='specValueList' value='".$row['spec_value']."' required></div>";
                }
                
            }

        }

        echo json_encode($specValuefields); //getSpecList
    }
    
} //action
else if (isset($_POST['saveADImage']) ) {
        $loginID = $_SESSION['user'];
        $id = $_POST['id'];
        $now = new DateTime();
    	$path = '';

    	
        $target_dir = "../images/ads/";
        $target_file = $target_dir .$id.'_'. basename($_FILES["file"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        
        
        move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);
        $path =str_replace(' ', '_',$id.'_'.$_FILES["file"]["name"]);
        
        
        $sql = "Insert into tbl_print_book_product (ad_url,type,tbl_product_id,tbl_print_book_category_id,updated_by,updated_date) VALUES('$path','Ad',0,'$id','$loginID','$toDay')";
        
        $result = $conn->query($sql);
        if($result->num_rows == 0){
            echo json_encode('Success');	        
		    
        }else{
            echo json_encode("This user type is already exists");
        }
    }else if (isset($_POST['saveImage'])) { // Start Spec Image
        $loginID = 3;
        $id = $_POST['id'];
        $now = new DateTime();
        $createdDate = $now->format('Y-m-d h:i:sa');
        $path = '';
    
        $target_dir = "../images/specImages/";
        $target_file = $target_dir . $id . '_' . basename($_FILES["file"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
    
        move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);
        $path = str_replace(' ', '_', $id . '_' . $_FILES["file"]["name"]);
    
        $sql = "INSERT INTO `tbl_print_book_product`(`tbl_product_id`,`ordering`,`tbl_print_book_category_id`, `created_by`, `created_date`,`type`,`ad_url`) 
        VALUES ('0','$id','$id','$loginID','$createdDate','List_Img','$path')";
    
        if ($conn->query($sql)) {
            echo json_encode('Success');
        } else {
            echo json_encode($conn->error);
        }
        // End Spec Image
    }

else if (isset($_POST['id'])) {

    $tblprintbookCategoryId = $_POST['id'];

    $sql = "SELECT tbl_printbook_category.*, tbl_printbook.id as tbl_printbookId, tbl_printbook.book_name, tbl_category.id as tbl_categoryId, tbl_category.categoryName, tbl_brands.id as tbl_brandsId, tbl_brands.brandName 
                FROM ((tbl_printbook_category 
                INNER JOIN tbl_printbook ON tbl_printbook_category.tbl_printbook_id = tbl_printbook.id) 
                INNER JOIN tbl_category ON tbl_printbook_category.tbl_category_id = tbl_category.id 
                INNER JOIN tbl_brands ON tbl_printbook_category.tbl_brand_id = tbl_brands.id) 
            WHERE tbl_printbook_category.deleted='No' AND tbl_printbook_category.id='$tblprintbookCategoryId'";
    $result = $conn->query($sql);
    $data = $result->fetch_array();
    //fetch products
    $categoryId = $data['tbl_categoryId'];
    $brandId = $data['tbl_brandsId'];

    //$sql = "SELECT * FROM `tbl_products` WHERE categoryId='$categoryId' AND tbl_brandsId='$brandId' AND deleted='no' AND status='Active'";
    $sql = "SELECT tbl_print_book_product.*,tbl_products.productName,tbl_products.productCode, tbl_products.id as product_id
            FROM `tbl_print_book_product`
            INNER JOIN tbl_products ON tbl_products.id=tbl_print_book_product.tbl_product_id
            WHERE tbl_print_book_product.tbl_print_book_category_id='$tblprintbookCategoryId' AND tbl_print_book_product.deleted='No'
            ORDER BY `tbl_print_book_product`.`ordering` ASC";
    $result = $conn->query($sql);
    $i = 1;
    $tr = '';
    $productIds = [];
    while ($row = $result->fetch_array()) {
        $printbokproductId = $row['id'];
        $tblProductId = $row['product_id'];
        $productIds[$i] = $row['product_id'];
        $button = '<a href="#" class="btn btn-warning btn-sm btn-flat" onclick="removeProduct(' . $printbokproductId . ',1)"><i class="fa fa-trash"></i></a>';
        $tr .= '<tr id="' . $tblProductId . '"><td>' . $i++ . '</td><td>' . $row['productName'] . '</td><td>' . $row['productCode'] . '</td><td>' . $button . '</td></tr>';
    } // /while 
    //end fetch product
    
    $sql = "SELECT tbl_print_book_product.*,tbl_products.productName,tbl_products.productCode, tbl_products.id as product_id
            FROM `tbl_print_book_product`
            INNER JOIN tbl_products ON tbl_products.id=tbl_print_book_product.tbl_product_id
            WHERE tbl_print_book_product.tbl_print_book_category_id='$tblprintbookCategoryId' AND tbl_print_book_product.deleted='Yes'";
    $result = $conn->query($sql);
    $i = 1;
    $tr_removed = '';
    $productIds = [];
    while ($row = $result->fetch_array()) {
        $printbokproductId = $row['id'];
        $tblProductId = $row['product_id'];
        //$productIds[$i] = $row['product_id'];
        $button = '<a href="#" class="btn btn-warning btn-sm btn-flat" onclick="restoreProduct(' . $printbokproductId . ')"><i class="fa fa-undo"></i></a>';
        $tr_removed .= '<tr id="removed_' . $printbokproductId . '"><td>' . $i++ . '</td><td>' . $row['productName'] . '</td><td>' . $row['productCode'] . '</td><td>' . $button . '</td></tr>';
    } // /while 
    //end fetch product
    
    echo json_encode(["data" => $data, "productData" => $tr, "removedProductData" => $tr_removed]);
} elseif (isset($_POST['products'])) {

    $categoryId = $_POST['categoryId'];
    $brandId = $_POST['brandId'];
    $flag = $_POST['flag'];
    $sql = '';
    // $sql = "SELECT * FROM `tbl_brands` WHERE id='$categoryId' AND deleted='no' AND status='Active'";
    if($flag == '1'){
        $sql = "SELECT * FROM `tbl_products` WHERE categoryId='$categoryId' AND tbl_brandsId='$brandId' AND deleted='no' AND status='Active'";
    }else{
        $tblprintbookCategoryId = $_POST['PrintBookCategoryId'];
        /*$sql = "SELECT tbl_products.*, tbl_print_book_product.id as bookProductId
                                FROM tbl_printbook_category 
                                INNER JOIN tbl_printbook ON tbl_printbook_category.tbl_printbook_id = tbl_printbook.id 
                                INNER JOIN tbl_category ON tbl_printbook_category.tbl_category_id = tbl_category.id 
                                INNER JOIN tbl_brands ON tbl_printbook_category.tbl_brand_id = tbl_brands.id 
                                INNER JOIN tbl_print_book_product ON tbl_printbook_category.id = tbl_print_book_product.tbl_print_book_category_id 
                                INNER JOIN tbl_products ON tbl_print_book_product.tbl_product_id = tbl_products.id 
                                WHERE tbl_print_book_product.deleted='No' AND tbl_products.categoryId='$categoryId' AND tbl_products.tbl_brandsId='$brandId' AND tbl_products.status='Active'
                                ORDER BY tbl_print_book_product.ordering";*/
        $sql = "SELECT tbl_print_book_product.id as bookProductId,tbl_products.*
                    FROM `tbl_print_book_product`
                    INNER JOIN tbl_products ON tbl_products.id=tbl_print_book_product.tbl_product_id
                    WHERE tbl_print_book_product.tbl_print_book_category_id='$tblprintbookCategoryId' AND tbl_print_book_product.deleted='No'";
                    
        $sql_removed = "SELECT tbl_print_book_product.id as bookProductId,tbl_products.*
                    FROM `tbl_print_book_product`
                    INNER JOIN tbl_products ON tbl_products.id=tbl_print_book_product.tbl_product_id
                    WHERE tbl_print_book_product.tbl_print_book_category_id='$tblprintbookCategoryId' AND tbl_print_book_product.deleted='Yes'";
    }
    $result = $conn->query($sql);
    $i = 1;
    $tr = '';
    $productIds = [];
    while ($row = $result->fetch_array()) {
        $tblProductId = $row['id'];
        $productIds[$i] = $row['id'];
        $bookProductId = $row['bookProductId'];
        $button = '<a href="#" class="btn btn-warning btn-sm btn-flat" onclick="removeProduct(' . $tblProductId . ',0)"><i class="fa fa-trash"></i></a>';
        $tr .= '<tr id="' . $tblProductId . '"><td>' . $i++ . '</td><td>' . $row['productName'] . '1</td><td>' . $row['productCode'] . '</td><td>' . $button . '</td></tr>';
    } // /while 
    $tr_removed = '';
    if($flag != '1'){
        $result_removed = $conn->query($sql_removed);
        $i = 1;
        
        $productIds = [];
        while ($row_removed = $result_removed->fetch_array()) {
            $tblProductId = $row_removed['id'];
            //$productIds[$i] = $row_removed['id'];
            $bookProductId = $row_removed['bookProductId'];
            $button = '<a href="#" class="btn btn-warning btn-sm btn-flat" onclick="restoreProduct(' . $bookProductId . ')"><i class="fa fa-undo"></i></a>';
            $tr_removed .= '<tr id="' . $tblProductId . '"><td>' . $i++ . '</td><td>' . $row_removed['productName'] . '1</td><td>' . $row_removed['productCode'] . '</td><td>' . $button . '</td></tr>';
        } // /while 
    }
    echo json_encode(["data" => $tr, "productIds" => $productIds, "removed_data" => $tr_removed]);
} else {
    $id = $_GET['id'];
    $sql = "SELECT tbl_printbook_category.*, tbl_printbook.id as tbl_printbookId, tbl_printbook.book_name, tbl_category.id as tbl_categoryId, tbl_category.categoryName, tbl_brands.id as tbl_brandsId, tbl_brands.brandName 
            FROM ((tbl_printbook_category
            INNER JOIN tbl_printbook ON tbl_printbook_category.tbl_printbook_id = tbl_printbook.id)
            INNER JOIN tbl_category ON tbl_printbook_category.tbl_category_id = tbl_category.id
            INNER JOIN tbl_brands ON tbl_printbook_category.tbl_brand_id = tbl_brands.id) 
            WHERE tbl_printbook_category.deleted='No' AND tbl_printbook.id='$id'
            ORDER BY id DESC";
    $result = $conn->query($sql);
    $i = 1;
    $output = array('data' => array());
    while ($row = $result->fetch_array()) {
        $tblPrintBookCategoryId = $row['id'];
        $bannerPath = "images/categoryBanner/" . $row['banner'];
        $image =  "<img src='$bannerPath' width='80' height='50'>";
        
        $bannerPath2 = "images/categoryBanner/" . $row['banner2'];
        $image2 =  "<img src='$bannerPath2' width='80' height='50'>";

        // active 
        if ($row['status'] == 'Active') {
            // activate status
            $status = "<label class='label label-success'>" . $row['status'] . "</label>";
        } else {
            // deactivate status
            $status = "<label class='label label-danger'>" . $row['status'] . "</label>";
        }
        // active 
        if ($row['viewtype'] == 'List') {
            // activate status
            $column_status ='List View';
        } else if ($row['viewtype'] == 'Column') {
            // deactivate status
            $column_status ='Grid View';
        }
        else if ($row['viewtype'] == 'singleProducts') {
            // deactivate status
            $column_status ='Single Product';
        }
        
        
        $url = "dataGridViewTest.php?id=$tblPrintBookCategoryId&type=".$row['viewtype']."";
        $url_printList = "printGridCatelogueList.php?id=$tblPrintBookCategoryId";
        $url_SingleProductsprintList = "printGridCatelogueSingleProdcuts.php?id=$tblPrintBookCategoryId";
        $url_print = "printGridCatelogue.php?id=$tblPrintBookCategoryId";
        
		
		if ($row['viewtype'] == 'List') {
		    $button = '	<div class="btn-group">
						<button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
						<i class="fa fa-gear tiny-icon"></i>  <span class="caret"></span></button>
						<ul class="dropdown-menu dropdown-menu-right" style="border: 1px solid gray;" role="menu">
							<li><a href="#" onclick="editPrintBookCategory(' . $tblPrintBookCategoryId . ')"><i class="fa fa-edit"></i> Edit Panel</a></li>
							<li><a href="' . $url . '" target="_blank"><i class="fa fa-eye"></i> Edit Catalogue View</a></li>
							<li><a href="' . $url_printList . '&type=list" target="_blank"><i class="fa fa-print"></i> List View PDF With Price</a></li>
							<li><a href="' . $url_printList . '&type=listLp" target="_blank"><i class="fa fa-print"></i> List View PDF Without Price</a></li>
							<li><a href="#" onclick="addPdf(' . $row['tbl_printbookId'] . ',' . $tblPrintBookCategoryId . ')"><i class="fa fa-plus"></i> Add Pdf </a></li>
				    ';
		    $button .= '</ul></div>';
		    
		}else if ($row['viewtype'] == 'singleProducts') {
		    $button = '	<div class="btn-group">
						<button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
						<i class="fa fa-gear tiny-icon"></i>  <span class="caret"></span></button>
						<ul class="dropdown-menu dropdown-menu-right" style="border: 1px solid gray;" role="menu">
							<li><a href="#" onclick="editPrintBookCategory(' . $tblPrintBookCategoryId . ')"><i class="fa fa-edit"></i> Edit Panel</a></li>
							<li><a href="' . $url . '" target="_blank"><i class="fa fa-eye"></i> Edit Catalogue View</a></li>
							<li><a href="' . $url_SingleProductsprintList . '&type=singleProducts" target="_blank"><i class="fa fa-print"></i> Single Products PDF With Price</a></li>
							<li><a href="' . $url_SingleProductsprintList . '&type=singleProductsLp" target="_blank"><i class="fa fa-print"></i> Single Products PDF Without Price</a></li>
							<li><a href="#" onclick="addPdf(' . $row['tbl_printbookId'] . ',' . $tblPrintBookCategoryId . ')"><i class="fa fa-plus"></i> Add Pdf </a></li>
				    ';
		    $button .= '</ul></div>';
		    
		}
		else{
		    $button = '	<div class="btn-group">
						<button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
						<i class="fa fa-gear tiny-icon"></i>  <span class="caret"></span></button>
						<ul class="dropdown-menu dropdown-menu-right" style="border: 1px solid gray;" role="menu">
							<li><a href="#" onclick="editPrintBookCategory(' . $tblPrintBookCategoryId . ')"><i class="fa fa-edit"></i> Edit Panel</a></li>
							<li><a href="' . $url . '" target="_blank"><i class="fa fa-eye"></i> Edit Catalogue View</a></li>
							<li><a href="' . $url_print . '&type=grid2" target="_blank"><i class="fa fa-print"></i> PDF 2-Grid With Price</a></li>
							<li><a href="' . $url_print . '&type=grid4" target="_blank"><i class="fa fa-print"></i> PDF 4-Grid With Price</a></li>
							<li><a href="' . $url_print . '&type=grid2Lp" target="_blank"><i class="fa fa-print"></i> PDF 2-Grid Without Price</a></li>
							<li><a href="' . $url_print . '&type=grid4Lp" target="_blank"><i class="fa fa-print"></i> PDF 4-Grid Without Price</a></li>
							<li><a href="#" onclick="addPdf(' . $row['tbl_printbookId'] . ',' . $tblPrintBookCategoryId . ')"><i class="fa fa-plus"></i> Add Pdf </a></li>
				    ';
		    $button .= '</ul></div>';
		}
        
        $output['data'][] = array(
            $i++,
            $row['book_name'],
            'Category : '.$row['categoryName'].'<br>Brand : '.$row['brandName'],
            $image,
            $image2,
            $status.'<br>'.$column_status,
            $button
        );
    } // /while 
    echo json_encode($output);
}

//----------Start load Brand---------------------------//

//----------End load Brand-----------------------------//
$conn->close();
