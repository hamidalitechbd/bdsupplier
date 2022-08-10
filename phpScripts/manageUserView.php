<?php
$conPrefix = '../';
include $conPrefix . 'includes/session.php';
if (isset($_POST['id'])) {
    $userId=$_POST['id'];
    $sql = "SELECT id, fname, username, images, mobile, print_phone, print_mobile,email, address, gender, tbl_accountTypeId, priority, designation, accountStatus, nid 
            FROM tbl_users 
            WHERE id='$userId' AND deleted = 'No'";
    $result = $conn->query($sql);
    echo json_encode($result->fetch_array());
}
else{
    $sql = "SELECT tbl_users.id, fname, username, images, mobile, print_phone, print_mobile,email, address, gender, tbl_accountTypeId, priority, designation, accountStatus, tbl_accountType.accountType
            FROM tbl_users 
            LEFT OUTER JOIN tbl_accountType ON tbl_users.tbl_accountTypeId=tbl_accountType.id AND tbl_accountType.deleted='No'
            WHERE tbl_users.deleted = 'No'
            ORDER BY priority DESC";
    $result = $conn->query($sql);
    $i=1;
    $output = array('data' => array());
    while ($row = $result->fetch_array()) {
        $userId = $row['id'];
        // active 
        if ($row['accountStatus'] == 'approved') {
            // activate status
            $status = "<label class='label label-success'>" . $row['accountStatus'] . "</label>";
        } else {
            // deactivate status
            $status = "<label class='label label-danger'>" . $row['accountStatus'] . "</label>";
        }
        $button = '<a href="#" onclick="editUser(' . $userId . ')"><button class="btn btn-warning btn-sm btn-flat"><i class="fa fa-edit"></i> Edit</button></a>';
        $output['data'][] = array(
            $i++,
            $row['fname'],
            $row['username'],
            $row['email'],
            $row['mobile'].'<br>'.$row['print_phone'].'<br>'.$row['print_mobile'],
            $row['accountType'],
            $status,
            $button
        );
    } // /while 
    echo json_encode($output);
}
$conn->close();
?>