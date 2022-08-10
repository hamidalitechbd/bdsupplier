<?php
$conPrefix = '../';
include $conPrefix . 'includes/conn.php';
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
        $id = $_POST['id'];
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
                <td><a href="#" class="btn btn-danger btn-sm btn-flat" onclick="deleProducSpec(' . $row['id'] . ')"><i class="fa fa-trash"></i> Delete</a></td>
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
        $loginID = 1;
        $addProductId = $_POST['addProductId'];
        $addSpecName = $_POST['addSpecName'];
        $addSpecValue = $_POST['addSpecValue'];
        $addSpecType = $_POST['addSpecType'];
        $now = new DateTime();
        $createdDate = $now->format('Y-m-d h:i:sa');

        $sql = "INSERT INTO tbl_print_book_spec_display (tbl_product_id, spec_name, spec_value, spec_type, created_by, created_date)
                    VALUES ('$addProductId','$addSpecName','$addSpecValue','$addSpecType','$loginID','$createdDate')";
        if ($conn->query($sql)) {
            echo json_encode('Success');
        } else {
            echo json_encode($conn->error);
        }
    } //addPrintBookProductSpec
    else if ($action == "deletePrintBookProductSpec") {
        $loginID = 1;
        $productId = $_POST['productId'];
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
    } //deletePrintBookProductSpec
} //action
else if (isset($_POST['id'])) {

    $tblprintbookCategoryId = $_POST['id'];

    $sql = "SELECT tbl_printbook_category.*, tbl_printbook.id as tbl_printbookId, tbl_printbook.book_name, tbl_category.id as tbl_categoryId, tbl_category.categoryName, tbl_brands.id as tbl_brandsId, tbl_brands.brandName FROM ((tbl_printbook_category INNER JOIN tbl_printbook ON tbl_printbook_category.tbl_printbook_id = tbl_printbook.id) INNER JOIN tbl_category ON tbl_printbook_category.tbl_category_id = tbl_category.id INNER JOIN tbl_brands ON tbl_printbook_category.tbl_brand_id = tbl_brands.id) WHERE tbl_printbook_category.deleted='No' AND tbl_printbook_category.id='$tblprintbookCategoryId'";
    $result = $conn->query($sql);
    $data = $result->fetch_array();
    //fetch products
    $categoryId = $data['tbl_categoryId'];
    $brandId = $data['tbl_brandsId'];

    $sql = "SELECT * FROM `tbl_products` WHERE categoryId='$categoryId' AND tbl_brandsId='$brandId' AND deleted='no' AND status='Active'";
    $result = $conn->query($sql);
    $i = 1;
    $tr = '';
    $productIds = [];
    while ($row = $result->fetch_array()) {
        $tblProductId = $row['id'];
        $productIds[$i] = $row['id'];
        $button = '<a href="#" class="btn btn-warning btn-sm btn-flat" onclick="removeProduct(' . $tblProductId . ')"><i class="fa fa-trash"></i></a>';
        $tr .= '<tr id="' . $tblProductId . '"><td>' . $i++ . '</td><td>' . $row['productName'] . '</td><td>' . $row['productCode'] . '</td><td>' . $button . '</td></tr>';
    } // /while 
    //end fetch product

    echo json_encode(["data" => $data, "productData" => $tr]);
} elseif (isset($_POST['products'])) {

    $categoryId = $_POST['categoryId'];
    $brandId = $_POST['brandId'];

    // $sql = "SELECT * FROM `tbl_brands` WHERE id='$categoryId' AND deleted='no' AND status='Active'";
    $sql = "SELECT * FROM `tbl_products` WHERE categoryId='$categoryId' AND tbl_brandsId='$brandId' AND deleted='no' AND status='Active'";
    $result = $conn->query($sql);
    $i = 1;
    $tr = '';
    $productIds = [];
    while ($row = $result->fetch_array()) {
        $tblProductId = $row['id'];
        $productIds[$i] = $row['id'];
        $button = '<a href="#" class="btn btn-warning btn-sm btn-flat" onclick="removeProduct(' . $tblProductId . ')"><i class="fa fa-trash"></i></a>';
        $tr .= '<tr id="' . $tblProductId . '"><td>' . $i++ . '</td><td>' . $row['productName'] . '</td><td>' . $row['productCode'] . '</td><td>' . $button . '</td></tr>';
    } // /while 
    echo json_encode(["data" => $tr, "productIds" => $productIds]);
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

        // active 
        if ($row['status'] == 'Active') {
            // activate status
            $status = "<label class='label label-success'>" . $row['status'] . "</label>";
        } else {
            // deactivate status
            $status = "<label class='label label-danger'>" . $row['status'] . "</label>";
        }
        $url = "dataGridViewTest.php?id=$tblPrintBookCategoryId";
        
        $button = '	<div class="btn-group">
						<button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
						<i class="fa fa-gear tiny-icon"></i>  <span class="caret"></span></button>
						<ul class="dropdown-menu dropdown-menu-right" style="border: 1px solid gray;" role="menu">
							<li><a href="#" onclick="editPrintBookCategory(' . $tblPrintBookCategoryId . ')"><i class="fa fa-edit"></i> Edit</a></li>
							<li><a href="' . $url . '" target="_blank"><i class="fa fa-eye"></i> View Price Catalogue</a></li>';
		$button .= '</ul></div>';
        
        $output['data'][] = array(
            $i++,
            $row['book_name'],
            $row['categoryName'],
            $row['brandName'],
            $row['type'],
            $row['viewtype'],
            $image,
            $status,
            $button
        );
    } // /while 
    echo json_encode($output);
}

//----------Start load Brand---------------------------//

//----------End load Brand-----------------------------//
$conn->close();
