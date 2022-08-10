<?php
$conPrefix = '../';
include $conPrefix . 'includes/session.php';
if (isset($_POST['id'])) {
    $transportInfoId=$_POST['id'];
    $sql = "SELECT id, transportName, contactPerson, contactNo, status, email,address,remarks,transport_name_bangla,contact_person_bangla,contact_number_bangla,address_bangla
            FROM tbl_transportInfo
            WHERE deleted='No' AND id='$transportInfoId'";
    $result = $conn->query($sql);
    echo json_encode($result->fetch_array());
}
else{
    $sql = "SELECT id, transportName, contactPerson, contactNo,address, status,transport_name_bangla,contact_person_bangla,contact_number_bangla,address_bangla
            FROM tbl_transportInfo
            WHERE deleted='No'
            ORDER BY id DESC";
    $result = $conn->query($sql);
    $i=1;
    $output = array('data' => array());
    while ($row = $result->fetch_array()) {
        $transportInfoId = $row['id'];
        // active 
        if ($row['status'] == 'Active') {
            // activate status
            $status = "<label class='label label-success'>" . $row['status'] . "</label>";
        } else {
            // deactivate status
            $status = "<label class='label label-danger'>" . $row['status'] . "</label>";
        }
        $action = '<div class="btn-group">
                <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-gear tiny-icon"></i>  <span class="caret"></span></button>
                <ul class="dropdown-menu dropdown-menu-right" style="border: 1px solid gray;" role="menu">
                    <li><a href="#" onclick="editTransportInfo(' . $transportInfoId . ')"><i class="fa fa-edit"></i> Edit English</a>
                    <li><a href="#" onclick="editTransportInfoBangla(' . $transportInfoId . ')"><i class="fa fa-edit"></i> Edit Bangla</a>';
        $action .='</ul>
            </div>';
        
        $output['data'][] = array(
            $i++,
            $row['transportName'].'<br>'.$row['transport_name_bangla'],
            $row['address'].'<br>'.$row['address_bangla'],
            $row['contactPerson'].'<br>'.$row['contact_person_bangla'],
            $row['contactNo'],
            $status,
            $action
        );
    } // /while 
    echo json_encode($output);
}
$conn->close();
?>