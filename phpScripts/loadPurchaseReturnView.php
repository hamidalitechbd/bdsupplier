<?php
$conPrefix = '../';
include $conPrefix . 'includes/session.php';
if($_GET['sortData'] == "0,0")
{
    $sql = "SELECT purchaseReturnOrderNo, purchaseReturnDate, tbl_purchase_return.status, tbl_purchase.purchaseOrderNo, tbl_purchase_return.id, tbl_purchase_return.tbl_purchaseId, tbl_purchase_product_return.tbl_productsId, CONCAT('<ul>',GROUP_CONCAT(CONCAT('<li>',tbl_products.productName,' - ', tbl_products.productCode, ' (', tbl_purchase_product_return.quantity ,')') SEPARATOR '</li>'),'</ul>') AS purchaseReturnProducts 
            FROM tbl_purchase_return 
            LEFT OUTER JOIN tbl_purchase_product_return ON tbl_purchase_product_return.tbl_purchase_return_id = tbl_purchase_return.id AND tbl_purchase_product_return.deleted = 'No'
            LEFT OUTER JOIN tbl_purchase ON tbl_purchase_return.tbl_purchaseId = tbl_purchase.id AND tbl_purchase.deleted = 'No'
            LEFT OUTER JOIN tbl_products ON tbl_purchase_product_return.tbl_productsId = tbl_products.id
            WHERE tbl_purchase_return.deleted = 'No'
            GROUP BY tbl_purchase_return.id
            ORdER BY tbl_purchase_return.id DESC";
}else{
    $dates = explode(",",$_GET['sortData']);
    $sql = "SELECT purchaseReturnOrderNo, purchaseReturnDate, tbl_purchase_return.status, tbl_purchase.purchaseOrderNo, tbl_purchase_return.id, tbl_purchase_return.tbl_purchaseId, tbl_purchase_product_return.tbl_productsId, CONCAT('<ul>',GROUP_CONCAT(CONCAT('<li>',tbl_products.productName,' - ', tbl_products.productCode, ' (', tbl_purchase_product_return.quantity ,')') SEPARATOR '</li>'),'</ul>') AS purchaseReturnProducts 
            FROM tbl_purchase_return 
            LEFT OUTER JOIN tbl_purchase_product_return ON tbl_purchase_product_return.tbl_purchase_return_id = tbl_purchase_return.id AND tbl_purchase_product_return.deleted = 'No'
            LEFT OUTER JOIN tbl_purchase ON tbl_purchase_return.tbl_purchaseId = tbl_purchase.id AND tbl_purchase.deleted = 'No'
            LEFT OUTER JOIN tbl_products ON tbl_purchase_product_return.tbl_productsId = tbl_products.id
            WHERE tbl_purchase_return.deleted = 'No' AND purchaseReturnDate BETWEEN '".$dates[0]."' AND '".$dates[1]."' 
            GROUP BY tbl_purchase_return.id
            ORdER BY tbl_purchase_return.id DESC";
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
        $action = '	<div class="btn-group">
						<button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
						<i class="fa fa-gear tiny-icon"></i>  <span class="caret"></span></button>
						<ul class="dropdown-menu dropdown-menu-right" style="border: 1px solid gray;" role="menu">
							<li><a href="productReturnViewDetails.php?pid='.$row['tbl_purchaseId'].'&prid='.$row['id'].'" target="_blank"><i class="fa fa-print tiny-icon"></i>View Details</a></li>';
		if(strtolower($_SESSION['userType']) == 'super admin'){					
		    $action .=  '<li><a href="#" onclick="deletePurchaseReturn('.$row['id'].')"><i class="fa fa-edit tiny-icon"></i>Delete</a></li>';
		}
		$action .=	    '</ul>
					</div>';
        $output['data'][] = array(
            $i++,
            $row['purchaseReturnOrderNo'],
            $row['purchaseReturnDate'],
            $row['purchaseOrderNo'],
            $row['purchaseReturnProducts'],
            $unitStatus,
            $action
        );
    } // /while 
}// if num_rows

$conn->close();

echo json_encode($output);
?>