<?php
$conPrefix = '../';
include $conPrefix . 'includes/conn.php';
if (isset($_POST['id'])) {
    $printBookId = $_POST['id'];
    $sql = "SELECT id, book_name, book_date, status
            FROM tbl_printbook 
            WHERE id='$printBookId' AND deleted = 'No'";
    $result = $conn->query($sql);
    echo json_encode($result->fetch_array());
}
else{
    $sql = "SELECT id, book_name, book_date, status
            FROM tbl_printbook 
            WHERE deleted = 'No'
            ORDER BY id DESC";
    $result = $conn->query($sql);
    $i=1;
    $output = array('data' => array());
    while ($row = $result->fetch_array()) {
        $printBookId = $row['id'];
        // active 
        if ($row['status'] == 'Active') {
            // activate status
            $status = "<label class='label label-success'>" . $row['status'] . "</label>";
        } else {
            // deactivate status
            $status = "<label class='label label-danger'>" . $row['status'] . "</label>";
        }
        $button = '<a href="#" onclick="editPrintBook(' . $printBookId . ')"><button class="btn btn-warning btn-sm btn-flat"><i class="fa fa-edit"></i> Edit</button></a>';
        $output['data'][] = array(
            $i++,
            $row['book_name'],
            $row['book_date'],
            $status,
            $button
        );
    } // /while 
    echo json_encode($output);
}
$conn->close();
?>