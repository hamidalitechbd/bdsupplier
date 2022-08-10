<?php

$conPrefix = '../';
include $conPrefix . 'includes/session.php';
if (isset($_GET['page'])) {
    $type = $_GET['page'];
    if ($type == "Unit") {
        $sql = "SELECT id,unitName,unitDesc,status FROM tbl_units where deleted='No' order by id DESC";
    } else if ($type == "Brand") {
        $sql = "SELECT id,brandName,brandDesc,status,brand_logo FROM tbl_brands where deleted='No' order by id DESC";
    } else if ($type == "Warehouse") {
        $sql = "SELECT id,wareHouseName,wareHouseAddress,status FROM tbl_warehouse where deleted='No' order by id DESC";
    } else if ($type == "Category") {
        $sql = "SELECT id,categoryName,categoryDesc,status FROM tbl_category where deleted='No' order by id DESC";
    } else if ($type == "PaymentMethod") {
        $sql = "SELECT id,methodName,methodDesc,status FROM tbl_paymentMethod where deleted='No' order by id DESC";
    } else if ($type == "User Type") {
        $sql = "SELECT id,accountType,accountDesc,status FROM tbl_accountType where deleted='No' order by id DESC";
    }
    //$sql = "SELECT id,unitName,unitDesc,status FROM tbl_units where unitType='" . $type . "' order by id DESC";
    $result = $conn->query($sql);
    $output = array('data' => array());
    if ($result->num_rows > 0) {
        $unitStatus = "";
        $i = 1;
        while ($row = $result->fetch_array()) {
            $unitId = $row['id'];
            // active 
            if ($row['status'] == 'Active') {
                // activate status
                $unitStatus = "<label class='label label-success'>" . $row['status'] . "</label>";
            } else {
                // deactivate status
                $unitStatus = "<label class='label label-danger'>" . $row['status'] . "</label>";
            }
            $button = '<a href="#" onclick="editUnit(' . $row['id'] . ',\'' . $type . '\')"><button class="btn btn-warning btn-sm btn-flat"><i class="fa fa-edit"></i> Edit</button></a>';
            $image = '<a href="#" onclick="editImage(' . $row['id'] . ',\'' . $type . '\')"><button class="btn btn-warning btn-sm btn-flat"><i class="fa fa-edit"></i></button></a>';
            if ($type == "Brand") {
                $bannerPath2 = "images/brand/thumb/" . $row['brand_logo'];
                $brandLogo = "<img src='$bannerPath2' width='80' height='50'>";
                $output['data'][] = array(
                    $i++,
                    $row[1],
                    $row[2],
                    $brandLogo.' '.$image,
                    $unitStatus,
                    $button
                );
            }else{
                $output['data'][] = array(
                    $i++,
                    $row[1],
                    $row[2],
                    $unitStatus,
                    $button
                );
            }
        } // /while 
    }// if num_rows

    $conn->close();

    echo json_encode($output);
} else {
    echo json_encode('Illegal Operation');
}
?>