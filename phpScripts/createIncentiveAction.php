<?php
$conPrefix = '../';
include $conPrefix . 'includes/session.php';
date_default_timezone_set('Asia/Dhaka');
$toDay = (new DateTime())->format("Y-m-d H:i:s");
if(isset($_POST["action"]))
{
	$action = $_POST["action"];
	if($action == "saveIncentive")
	{
		$name = $_POST['name'];
		$dateFrom = $_POST['dateFrom'];
		$dateTo = $_POST['dateTo'];
		$buyAmount = $_POST['buyAmount'];
		$restAmount = $_POST['restAmount'];
		$ownerIncentive = $_POST['ownerIncentive'];
		$employeeIcentive = $_POST['employeeIcentive'];
		$applayDate = $_POST['applayDate'];
		$customerSalesType = $_POST['customerSalesType'];
		$sql = "INSERT INTO tbl_incentive(incentive_name, date_from, date_to, buy_amount, rest_amount, owners_incentive, employee_incentive, customer_sales_type, applay_date) 
		        VALUES ('".$name."','".$dateFrom."','".$dateTo."','".$buyAmount."','".$restAmount."','".$ownerIncentive."','".$employeeIcentive."','".$customerSalesType."','".$applayDate."')";
		$conn->query($sql);
		echo json_encode('Success');
	}else if ($action == "deleteIncentive"){
		$incentiveId = $_POST['id'];
		$sql = "UPDATE tbl_incentive 
                SET deleted='Yes'
                WHERE id='$incentiveId'";
		$conn->query($sql);
		echo json_encode('Success');
	}
}else{
    $sql="SELECT * FROM `tbl_incentive` WHERE deleted='No'";
	$result = $conn->query($sql);
    $i=1;
    $output = array('data' => array());
    while ($row = $result->fetch_array()) {
        $button = '	<div class="btn-group">
						<button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
						<i class="fa fa-gear tiny-icon"></i> <span class="caret"></span></button>
						<ul class="dropdown-menu dropdown-menu-right" style="border: 1px solid gray;" role="menu">
							<li><a href="incentiveParty.php?id='.$row['id'].'"><i class="fa fa-print tiny-icon"></i> Incentive Party View</a></li>
							';
		if(strtolower($_SESSION['userType']) == 'super admin' || strtolower($_SESSION['userType']) == 'admin support plus'){
		    $button .=  '<li><a href="#" onclick="deleteIncentive(' . $row['id'] . ')"><i class="fa fa-edit tiny-icon"></i>Delete</a></li>';
		}
		$button .= '</ul></div>';
        $output['data'][] = array(
            $i++,
            $row['incentive_name'].'<br>Applied for '.$row['customer_sales_type'],
            'From: '.$row['date_from'].'<br />To: '. $row['date_to'],
            'Buy: '.$row['buy_amount'].'<br>Rest: '.$row['rest_amount'],
            'Owner: '.$row['owners_incentive'].'<br>Employee: '.$row['employee_incentive'],
            $row['applay_date'],
            $button
        );
    } // /while 
    echo json_encode($output);
}
?>