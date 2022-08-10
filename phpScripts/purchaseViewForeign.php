<?php
$conPrefix = '../';
include $conPrefix . 'includes/session.php';
if(isset($_POST['id'])){
    
}else{
    if($_GET['sortData'] == "0,0")
    {
        $sql = "SELECT tbl_purchaseForeign.id, tbl_purchaseForeign.purchaseOrderNo,tbl_purchaseForeign.tbl_supplierId,tbl_purchaseForeign.purchaseDate, tbl_purchaseForeign.lcNo, tbl_party.partyName, tbl_purchaseForeign.grandTotal, tbl_purchaseForeign.status, CONCAT('<ul>', GROUP_CONCAT(CONCAT('<li>',tbl_products.productName,' - ',tbl_products.productCode, ' (', tbl_purchaseForeignProducts.quantity,')') SEPARATOR '</li>'),'</ul>') AS importedProducts
                FROM tbl_purchaseForeign INNER JOIN tbl_party ON tbl_purchaseForeign.tbl_supplierId = tbl_party.id
                LEFT OUTER JOIN tbl_purchaseForeignProducts ON tbl_purchaseForeignProducts.tbl_purchaseForeignId = tbl_purchaseForeign.id AND tbl_purchaseForeignProducts.deleted = 'No'
                LEFT OUTER JOIN tbl_products ON tbl_purchaseForeignProducts.tbl_productsId = tbl_products.id
                WHERE tbl_purchaseForeign.deleted='No'
                GROUP BY tbl_purchaseForeign.id
                ORDER BY id DESC";
    }else{
        $dates = explode(",",$_GET['sortData']);
        $sql = "SELECT tbl_purchaseForeign.id, tbl_purchaseForeign.purchaseOrderNo,tbl_purchaseForeign.tbl_supplierId,tbl_purchaseForeign.purchaseDate, tbl_purchaseForeign.lcNo, tbl_party.partyName, tbl_purchaseForeign.grandTotal, tbl_purchaseForeign.status, CONCAT('<ul>', GROUP_CONCAT(CONCAT('<li>',tbl_products.productName,' - ',tbl_products.productCode, ' (', tbl_purchaseForeignProducts.quantity,')') SEPARATOR '</li>'),'</ul>') AS importedProducts
                FROM tbl_purchaseForeign INNER JOIN tbl_party ON tbl_purchaseForeign.tbl_supplierId = tbl_party.id
                LEFT OUTER JOIN tbl_purchaseForeignProducts ON tbl_purchaseForeignProducts.tbl_purchaseForeignId = tbl_purchaseForeign.id AND tbl_purchaseForeignProducts.deleted = 'No'
                LEFT OUTER JOIN tbl_products ON tbl_purchaseForeignProducts.tbl_productsId = tbl_products.id
                WHERE tbl_purchaseForeign.deleted='No' AND tbl_purchaseForeign.purchaseDate BETWEEN '".$dates[0]."' AND '".$dates[1]."'  
                GROUP BY tbl_purchaseForeign.id
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
            $action = '<div class="btn-group">
                                <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-gear tiny-icon"></i>  <span class="caret"></span></button>
                                <ul class="dropdown-menu dropdown-menu-right" style="border: 1px solid gray;" role="menu">
                                        <li><a href="purchaseForeignViewDetails.php?id='.$row['id'].'&purid='.$row['purchaseOrderNo'].'&supId='.$row['tbl_supplierId'].'" target="_blank"><i class="fa fa-print tiny-icon"></i>View Details</a></li>';
    		if(strtolower($_SESSION['userType']) == 'super admin'){					
    		    $action .=  	'<li><a href="purchaseForeign-add.php?id='.$row['id'].'"><i class="fa fa-edit tiny-icon"></i>Individual Delete</a></li>';
    		    $action .=  	'<li><a href="#" onclick="deleteForeignPurchase('.$row['id'].')"><i class="fa fa-trash tiny-icon"></i>Delete</a></li>';
    		}
    		$action .='</ul>
                            </div>';
            $output['data'][] = array(
                $i++,
                $row['purchaseOrderNo'],
                $row['purchaseDate'],
                $row['partyName'],
                $row['lcNo'],
                $row['importedProducts'],
                $unitStatus,
                $action
            );
        } // /while 
    }// if num_rows
    $conn->close();
    echo json_encode($output);    
}

?>