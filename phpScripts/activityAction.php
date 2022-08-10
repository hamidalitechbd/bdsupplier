<?php
$conPrefix = '../';
include $conPrefix . 'includes/session.php';
//action.php
//session_start();
if(isset($_POST["action"]))
{
	if($_POST["action"] == "findSalesReportPrint")
	{
	    $salesId = $_POST['id'];
	    $sql = "SELECT tbl_activity_log.printed_date, tbl_users.fname, ifnull(tbl_sales.print_count, 0) AS print_count
                FROM tbl_sales
                LEFT OUTER JOIN tbl_activity_log ON tbl_activity_log.tbl_sales_id = tbl_sales.id AND tbl_activity_log.activity_type='sales_print'
                LEFT OUTER JOIN tbl_users ON tbl_activity_log.printed_by = tbl_users.id
                WHERE  tbl_sales.id='$salesId'
                ORDER BY tbl_activity_log.printed_date DESC";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        echo json_encode($row);
	}else if($_POST['action'] == "findTSReportPrint"){
	    $salesId = $_POST['id'];
	    $sql = "SELECT tbl_activity_log.printed_date, tbl_users.fname, ifnull(tbl_temporary_sale.print_count, 0) AS print_count
                FROM tbl_temporary_sale
                LEFT OUTER JOIN tbl_activity_log ON tbl_activity_log.tbl_sales_id = tbl_temporary_sale.id AND tbl_activity_log.activity_type='TS_print'
                LEFT OUTER JOIN tbl_users ON tbl_activity_log.printed_by = tbl_users.id
                WHERE  tbl_temporary_sale.id='$salesId'
                ORDER BY tbl_activity_log.printed_date DESC";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        echo json_encode($row);
	}else if($_POST['action'] == "findSaleReturnReportPrint"){
	    $salesId = $_POST['id'];
	    $sql = "SELECT tbl_activity_log.printed_date, tbl_users.fname, ifnull(tbl_sales_return.print_count, 0) AS print_count
                FROM tbl_sales_return
                LEFT OUTER JOIN tbl_activity_log ON tbl_activity_log.tbl_sales_id = tbl_sales_return.id AND tbl_activity_log.activity_type='return_print'
                LEFT OUTER JOIN tbl_users ON tbl_activity_log.printed_by = tbl_users.id
                WHERE  tbl_sales_return.id='$salesId'
                ORDER BY tbl_activity_log.printed_date DESC";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        echo json_encode($row);
	}else if ($_POST['action']== "countTSPrint"){
	    $id = $_POST['id'];
	    $sql_pritCount = "UPDATE tbl_temporary_sale SET print_count=(print_count+1) WHERE id='$id'";
    	$conn->query($sql_pritCount);
    	$sql_printLog = "INSERT INTO tbl_activity_log(printed_by, printed_date, tbl_sales_id, activity_type) VALUES ('$loginID','$toDay','$id','TS_print')";
    	$conn->query($sql_printLog);
	}else if ($_POST['action']== "countPrint"){
	    $id = $_POST['id'];
	    $sql_pritCount = "UPDATE tbl_sales SET print_count=(print_count+1) WHERE id='$id'";
    	$conn->query($sql_pritCount);
    	$sql_printLog = "INSERT INTO tbl_activity_log(printed_by, printed_date, tbl_sales_id, activity_type) VALUES ('$loginID','$toDay','$id','sales_print')";
    	$conn->query($sql_printLog);
	}
	else if ($_POST['action']== "countReturnPrint"){
	    $id = $_POST['id'];
	    $sql_pritCount = "UPDATE tbl_sales_return SET print_count=(print_count+1) WHERE id='$id'";
    	$conn->query($sql_pritCount);
    	$sql_printLog = "INSERT INTO tbl_activity_log(printed_by, printed_date, tbl_sales_id, activity_type) VALUES ('$loginID','$toDay','$id','return_print')";
    	$conn->query($sql_printLog);
	}
}