<?php
$conPrefix = '../';
include $conPrefix . 'includes/session.php';
if($_GET['sortData'] == "0,0"){
    $sql = "SELECT tbl_purchase.id, tbl_purchase.purchaseOrderNo, tbl_purchase.tbl_supplierId, tbl_purchase.purchaseDate, tbl_purchase.chalanNo, tbl_party.partyName, tbl_purchase.grandTotal, tbl_purchase.status, CONCAT('<ul>',GROUP_CONCAT(CONCAT('<li>',tbl_products.productName,' - ', tbl_products.productCode,' (',tbl_purchaseProducts.quantity,')') SEPARATOR '</li>'),'</ul>') AS purchaseProducts
            FROM tbl_purchase INNER JOIN tbl_party ON tbl_purchase.tbl_supplierId=tbl_party.id
            LEFT OUTER JOIN tbl_purchaseProducts ON tbl_purchaseProducts.tbl_purchaseId = tbl_purchase.id AND tbl_purchaseProducts.deleted='No'
            LEFT OUTER JOIN tbl_products ON tbl_purchaseProducts.tbl_productsId = tbl_products.id
            WHERE tbl_purchase.deleted='No'
            GROUP BY tbl_purchase.id
            ORDER BY id DESC";
}else{
    $dates = explode(",",$_GET['sortData']);
    $sql = "SELECT tbl_purchase.id, tbl_purchase.purchaseOrderNo, tbl_purchase.tbl_supplierId, tbl_purchase.purchaseDate, tbl_purchase.chalanNo, tbl_party.partyName, tbl_purchase.grandTotal, tbl_purchase.status, CONCAT('<ul>',GROUP_CONCAT(CONCAT('<li>',tbl_products.productName,' - ', tbl_products.productCode,' (',tbl_purchaseProducts.quantity,')') SEPARATOR '</li>'),'</ul>') AS purchaseProducts
            FROM tbl_purchase INNER JOIN tbl_party ON tbl_purchase.tbl_supplierId=tbl_party.id
            LEFT OUTER JOIN tbl_purchaseProducts ON tbl_purchaseProducts.tbl_purchaseId = tbl_purchase.id AND tbl_purchaseProducts.deleted='No'
            LEFT OUTER JOIN tbl_products ON tbl_purchaseProducts.tbl_productsId = tbl_products.id
            WHERE tbl_purchase.deleted='No' AND tbl_purchase.purchaseDate BETWEEN '".$dates[0]."' AND '".$dates[1]."'
            GROUP BY tbl_purchase.id
            ORDER BY id DESC";
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
							<li><a href="productViewDetails.php?id='.$row['id'].'" target="_blank"><i class="fa fa-print tiny-icon"></i>View Details</a></li>
							<li><a href="purchaseLocal-return.php?purid='.$row['purchaseOrderNo'].'"><i class="fa fa-mail-reply"></i>Purchase Return</a></li>';
		if(strtolower($_SESSION['userType']) == 'super admin'){					
		    //$action .=  '<li><a href="productEditDetails.php?id='.$row['id'].'"><i class="fa fa-edit tiny-icon"></i>Edit</a></li>';
			$action .=  '<li><a href="#" onclick="deletePurchase('.$row['id'].')"><i class="fa fa-edit tiny-icon"></i>Delete</a></li>';
		}
		$action .=	    '</ul>
					</div>';
        $output['data'][] = array(
            $i++,
            $row['purchaseOrderNo'],
            $row['purchaseDate'],
            $row['partyName'],
            $row['chalanNo'],
            $row['purchaseProducts'],
            $unitStatus,
            $action
        );
    } // /while 
}// if num_rows

$conn->close();

echo json_encode($output);
?>