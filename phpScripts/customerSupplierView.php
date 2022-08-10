<?php
$conPrefix = '../';
include $conPrefix . 'includes/session.php';
if (isset($_GET['page'])) {
    $type = $_GET['page'];
    if($type == 'Customers'){
        $sql = "SELECT `id`,`partyName`,`partyAddress`,`partyCode`,`contactPerson`,tblCity,locationArea,`partyPhone`,partyAltPhone,`partyEmail`,`status`,`creditLimit`,`tblType`,customerSalesType,`userType`,createdDate,lastUpdatedDate,
        party_name_bangla,contact_person_bangla,contact_number_bangla,location_bangla,party_address_bangla FROM `tbl_party` 
        WHERE tblType<>'Suppliers' AND deleted='No' ORDER BY id DESC";
    }else if ($type == 'Suppliers'){
        $sql = "SELECT `id`,`partyName`,`partyAddress`,`partyCode`,`contactPerson`,tblCity,locationArea,partyPhone,partyAltPhone,`partyEmail`,`status`,`creditLimit`,`tblType`,`userType`,createdDate,lastUpdatedDate,
        party_name_bangla,contact_person_bangla,contact_number_bangla,location_bangla,party_address_bangla FROM `tbl_party`
        WHERE tblType<>'Customers' AND deleted='No' ORDER BY id DESC";
    }
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
            $action = '<div class="btn-group">
                <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-gear tiny-icon"></i>  <span class="caret"></span></button>
                <ul class="dropdown-menu dropdown-menu-right" style="border: 1px solid gray;" role="menu">
                    <li><a href="#" onclick="editCustomerSupplier(' . $row['id'] . ')"><i class="fa fa-edit"></i> Edit English</a>
                    <li><a href="#" onclick="editCustomerSupplierBangla(' .  $row['id'] . ')"><i class="fa fa-edit"></i> Edit Bangla</a>';
            $action .='</ul>
            </div>';
            $output['data'][] = array(
                $i++,
                $row['createdDate'].'<br>'.$row['lastUpdatedDate'],
                $row['partyName'].'<br>'.$row['party_name_bangla'],
                $row['contactPerson'].'<br>'.$row['contact_person_bangla'],
                '<div style="text-align:center;">'.$row['partyPhone'].'<br>'.$row['partyAltPhone'].'</div>',
                $row['tblCity'],
                $row['locationArea'].'<br>'.$row['location_bangla'],
                '<div style="text-align:right;">'.$row['creditLimit'].'</div>',
                $unitStatus.'<br><b style="color: blue;">'.$row['tblType'].'</b><br><b style="color: green;">'.$row['customerSalesType'].'</b>',
                $action
            );
        } // /while 
    }// if num_rows

    $conn->close();

    echo json_encode($output);
} else {
    echo json_encode('Illegal Operation');
}
?>