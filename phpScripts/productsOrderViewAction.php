<?php
$conPrefix = '../';
include $conPrefix . 'includes/session.php';

$loginID = $_SESSION['user'];
$type = $_GET['page'];
    if(strtolower($_SESSION['userType']) == 'sales executive'){	
        $sql_executive = " where tbl_orders.tbl_userId = '$loginID' AND tbl_orders.status = '$type' ";    
    }else{
        $sql_executive = "where tbl_orders.status = '$type'";
    }
    if($type == 'Checked'){
        $sql = "SELECT tbl_orders.id,tbl_sales.id as saleID,tbl_sales.salesDate,tbl_orders.orderNo,tbl_orders.orderDate,tbl_orders.type,tbl_orders.tbl_customerId,tbl_party.partyName,tbl_party.partyAddress,tbl_party.locationArea,
            tbl_party.tblCity,tbl_party.contactPerson,tbl_party.partyPhone,tbl_users.fname,tbl_orders.remarks,tbl_orders.totalAmount,tbl_orders.grandTotal,tbl_orders.paidAmount,tbl_orders.received_amount,tbl_orders.status, tbl_orders.total_after_discount,
            tbl_orders.createdDate,tbl_order_details.tbl_products_id,CONCAT('<ul>', GROUP_CONCAT(CONCAT('<li>', tbl_products.productName,' - ',tbl_products.productCode, ' (',tbl_order_details.checked_quantity,' / ',tbl_order_details.quantity,')') SEPARATOR '</li>'), '</li></ul>') AS salesProducts
            FROM `tbl_orders`
            LEFT JOIN tbl_order_details ON tbl_order_details.tbl_orders_id=tbl_orders.id AND  tbl_order_details.deleted='No' AND  tbl_order_details.status<>'Cancel'
            LEFT JOIN tbl_party ON tbl_party.id=tbl_orders.tbl_customerId AND tbl_party.status='Active'
            LEFT JOIN tbl_products ON tbl_products.id=tbl_order_details.tbl_products_id
            LEFT JOIN tbl_users ON tbl_users.id=tbl_orders.tbl_userId AND tbl_users.accountStatus='approved'
            LEFT JOIN tbl_sales ON tbl_orders.id=tbl_sales.tbl_orders_id
            ".$sql_executive."
            GROUP BY tbl_orders.orderNo  ORDER BY tbl_sales.salesDate DESC";
    }else{
             if($type=='Completed'){
                 if(isset($_GET['sortData'])){
                    $dates = explode(",",$_GET['sortData']); 
                    $sql = "SELECT tbl_orders.id,tbl_sales.id as saleID,tbl_sales.salesDate,tbl_orders.orderNo,tbl_orders.orderDate,tbl_orders.type,tbl_orders.tbl_customerId,tbl_party.partyName,tbl_party.partyAddress,tbl_party.locationArea,
                    tbl_party.tblCity,tbl_party.contactPerson,tbl_party.partyPhone,tbl_users.fname,tbl_orders.remarks,tbl_orders.totalAmount,tbl_orders.grandTotal,tbl_orders.paidAmount,tbl_orders.received_amount,tbl_orders.bkash_amount,tbl_orders.status,tbl_orders.total_after_discount,
                    tbl_orders.createdDate,tbl_order_details.tbl_products_id,CONCAT('<ul>', GROUP_CONCAT(CONCAT('<li>', tbl_products.productName,' - ',tbl_products.productCode, ' (',tbl_order_details.checked_quantity,' / ',tbl_order_details.quantity,')') SEPARATOR '</li>'), '</li></ul>') AS salesProducts
                    FROM `tbl_orders`
                    LEFT JOIN tbl_order_details ON tbl_order_details.tbl_orders_id=tbl_orders.id AND  tbl_order_details.deleted='No' AND  tbl_order_details.status<>'Cancel'
                    LEFT JOIN tbl_party ON tbl_party.id=tbl_orders.tbl_customerId AND tbl_party.status='Active'
                    LEFT JOIN tbl_products ON tbl_products.id=tbl_order_details.tbl_products_id
                    LEFT JOIN tbl_users ON tbl_users.id=tbl_orders.tbl_userId AND tbl_users.accountStatus='approved'
                    LEFT JOIN tbl_sales ON tbl_orders.id=tbl_sales.tbl_orders_id
                    ".$sql_executive." and tbl_sales.salesDate BETWEEN '".$dates[0]."' AND '".$dates[1]."' 
                    GROUP BY tbl_orders.orderNo  ORDER BY tbl_sales.salesDate DESC";
                 }
                 else if(isset($_GET['customerId'])){
                    $customerId = $_GET['customerId'];
                      $sql = "SELECT tbl_orders.id,tbl_sales.id as saleID,tbl_orders.orderNo,tbl_orders.orderDate,tbl_orders.type,tbl_orders.tbl_customerId,tbl_party.partyName,tbl_party.partyAddress,tbl_party.locationArea,
                    tbl_party.tblCity,tbl_party.contactPerson,tbl_party.partyPhone,tbl_users.fname,tbl_orders.remarks,tbl_orders.totalAmount,tbl_orders.grandTotal,tbl_orders.paidAmount,tbl_orders.received_amount,tbl_orders.bkash_amount,tbl_orders.status,tbl_orders.total_after_discount,
                    tbl_orders.createdDate,tbl_order_details.tbl_products_id,CONCAT('<ul>', GROUP_CONCAT(CONCAT('<li>', tbl_products.productName,' - ',tbl_products.productCode, ' (',tbl_order_details.checked_quantity,' / ',tbl_order_details.quantity,')') SEPARATOR '</li>'), '</li></ul>') AS salesProducts
                    FROM `tbl_orders`
                    LEFT JOIN tbl_order_details ON tbl_order_details.tbl_orders_id=tbl_orders.id AND  tbl_order_details.deleted='No' AND  tbl_order_details.status<>'Cancel'
                    LEFT JOIN tbl_party ON tbl_party.id=tbl_orders.tbl_customerId AND tbl_party.status='Active'
                    LEFT JOIN tbl_products ON tbl_products.id=tbl_order_details.tbl_products_id
                    LEFT JOIN tbl_users ON tbl_users.id=tbl_orders.tbl_userId AND tbl_users.accountStatus='approved'
                    LEFT JOIN tbl_sales ON tbl_orders.id=tbl_sales.tbl_orders_id
                    ".$sql_executive." AND tbl_orders.tbl_customerId = '$customerId'
                    GROUP BY tbl_orders.orderNo  ORDER BY tbl_orders.id DESC";
                 }
                 else{
                    $sql = "SELECT tbl_orders.id,tbl_sales.id as saleID,tbl_orders.orderNo,tbl_orders.orderDate,tbl_orders.type,tbl_orders.tbl_customerId,tbl_party.partyName,tbl_party.partyAddress,tbl_party.locationArea,
                    tbl_party.tblCity,tbl_party.contactPerson,tbl_party.partyPhone,tbl_users.fname,tbl_orders.remarks,tbl_orders.totalAmount,tbl_orders.grandTotal,tbl_orders.paidAmount,tbl_orders.received_amount,tbl_orders.bkash_amount,tbl_orders.status,tbl_orders.total_after_discount,
                    tbl_orders.createdDate,tbl_order_details.tbl_products_id,CONCAT('<ul>', GROUP_CONCAT(CONCAT('<li>', tbl_products.productName,' - ',tbl_products.productCode, ' (',tbl_order_details.checked_quantity,' / ',tbl_order_details.quantity,')') SEPARATOR '</li>'), '</li></ul>') AS salesProducts
                    FROM `tbl_orders`
                    LEFT JOIN tbl_order_details ON tbl_order_details.tbl_orders_id=tbl_orders.id AND  tbl_order_details.deleted='No' AND  tbl_order_details.status<>'Cancel'
                    LEFT JOIN tbl_party ON tbl_party.id=tbl_orders.tbl_customerId AND tbl_party.status='Active'
                    LEFT JOIN tbl_products ON tbl_products.id=tbl_order_details.tbl_products_id
                    LEFT JOIN tbl_users ON tbl_users.id=tbl_orders.tbl_userId AND tbl_users.accountStatus='approved'
                    LEFT JOIN tbl_sales ON tbl_orders.id=tbl_sales.tbl_orders_id
                    ".$sql_executive."
                    GROUP BY tbl_orders.orderNo  ORDER BY tbl_orders.id DESC";
                }
            }else{
                $sql = "SELECT tbl_orders.id,tbl_sales.id as saleID,tbl_orders.orderNo,tbl_orders.orderDate,tbl_orders.type,tbl_orders.tbl_customerId,tbl_party.partyName,tbl_party.partyAddress,tbl_party.locationArea,
                tbl_party.tblCity,tbl_party.contactPerson,tbl_party.partyPhone,tbl_users.fname,tbl_orders.remarks,tbl_orders.totalAmount,tbl_orders.grandTotal,tbl_orders.paidAmount,tbl_orders.received_amount,tbl_orders.bkash_amount,tbl_orders.status,tbl_orders.total_after_discount,
                tbl_orders.createdDate,tbl_order_details.tbl_products_id,CONCAT('<ul>', GROUP_CONCAT(CONCAT('<li>', tbl_products.productName,' - ',tbl_products.productCode, ' (',tbl_order_details.checked_quantity,' / ',tbl_order_details.quantity,')') SEPARATOR '</li>'), '</li></ul>') AS salesProducts
                FROM `tbl_orders`
                LEFT JOIN tbl_order_details ON tbl_order_details.tbl_orders_id=tbl_orders.id AND  tbl_order_details.deleted='No' AND  tbl_order_details.status<>'Cancel'
                LEFT JOIN tbl_party ON tbl_party.id=tbl_orders.tbl_customerId AND tbl_party.status='Active'
                LEFT JOIN tbl_products ON tbl_products.id=tbl_order_details.tbl_products_id
                LEFT JOIN tbl_users ON tbl_users.id=tbl_orders.tbl_userId AND tbl_users.accountStatus='approved'
                LEFT JOIN tbl_sales ON tbl_orders.id=tbl_sales.tbl_orders_id
                ".$sql_executive."
                GROUP BY tbl_orders.orderNo  ORDER BY tbl_orders.id DESC";
            }
        }
    $result = $conn->query($sql);
    $output = array('data' => array());
    if ($result->num_rows > 0) {
        $unitStatus = "";
        $i = 1;
            while ($row = $result->fetch_array()) {
                if($row['status']=='Pending'){
                    $status= '<b style="color: blue;">Pending</b>';
                    $totalAmount= 'Total : '.$row['totalAmount'];
                    $advance='';
                }else if($row['status']=='Checked'){
                    $status= '<b style="color: green;">Checked</b>';
                    $totalAmount= 'Total : '.$row['totalAmount'].' ('.$row['total_after_discount'].')';
                    $advance='';
                }
                else if($row['status']=='Cancel'){
                    $status= '<b style="color: red;">Cancel</b>';
                    $totalAmount= 'Total : '.$row['totalAmount'];
                    $advance='Paid : '.$row['paidAmount'];
                }
                else if($row['status']=='Processing'){
                    $status= '<b style="color: #ff8d06;">Processing</b>';
                    $totalAmount= 'Total : '.$row['totalAmount'].' ('.$row['total_after_discount'].')';
                    $advance='Paid : '.$row['paidAmount'];
                    $received='Received  : '.$row['received_amount'];
                    $bkash='Bkash  : '.$row['bkash_amount'];
                }
                else if($row['status']=='Completed'){
                    $status= '<b style="color: blue;">Completed</b>';
                    $totalAmount= 'Total : '.$row['totalAmount'].' ('.$row['total_after_discount'].')';
                    $advance='Paid : '.$row['paidAmount'];
                    $received='Received  : '.$row['received_amount'];
                    $bkash='Bkash  : '.$row['bkash_amount'];
                    $sID=$row['saleID'];
                }
                
            $salesId = $row['id'];
            $button = '	<div class="btn-group">
    						<button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
    						<i class="fa fa-gear tiny-icon"></i>  <span class="caret"></span></button>
    						<ul class="dropdown-menu dropdown-menu-right" style="border: 1px solid gray;" role="menu">
    			
    			<li><a href="tempSalesOrderViewDetails.php?id='.$row['id'].'" target="_blank"><i class="fa fa-print tiny-icon"></i>View Details</a></li>';
				if((strtolower($_SESSION['userType']) == 'super admin' || strtolower($_SESSION['userType']) == 'admin coordinator') && $type=='Pending'){
					$button .= '<li><a href="checkOrderList.php?salesId='.$row['id'].'&salesType='.$row['type'].'"><i class="fa fa-mail-reply"></i> Check Order</a></li>';
				    $button .= '<li><a href="#" onclick="cancelOrder('.$row['id'].')"><i class="fa fa-trash"></i> Cancel Order</a></li>';
    		    }
    		    if(strtolower($_SESSION['userType']) == 'sales executive' && $type=='Pending'){
				    $button .= '<li><a href="#" onclick="cancelOrder('.$row['id'].')"><i class="fa fa-trash"></i> Cancel Order</a></li>';
    		    }
				if(strtolower($_SESSION['userType']) == 'sales executive' && $type=='Checked'){	
					$button .= '<li><a href="checkConfirmedOrderList.php?salesId='.$row['id'].'&salesType='.$row['type'].'"><i class="fa fa-mail-reply"></i> Confirmed Order</a></li>';
					$button .= '<li><a href="#" onclick="cancelOrder('.$row['id'].')"><i class="fa fa-trash"></i> Cancel Order</a></li>';
				    
				}
				if((strtolower($_SESSION['userType']) == 'super admin' || strtolower($_SESSION['userType']) == 'admin coordinator') && $type=='Checked'){
				    $button .= '<li><a href="#" onclick="changeCheckStatus('.$row['id'].')"><i class="fa fa-check-square-o"></i> Change Status</a></li>';
				    $button .= '<li><a href="#" onclick="cancelOrder('.$row['id'].')"><i class="fa fa-trash"></i> Cancel Order</a></li>';
				}
				if(strtolower($_SESSION['userType']) == 'sales executive' && $type=='Completed'){
					$button .= '<li><a href="#" onclick="order_regenerate('.$salesId.')"><i class="fa fa-mail-reply"></i> Order Regenerate</a></li>';
					$button .= '<li><a href="wholesalesViewDetails.php?id='.$sID.'"><i class="fa fa-print"></i> Order Invoice</a></li>';
    		    }
    		    if((strtolower($_SESSION['userType']) == 'super admin' || strtolower($_SESSION['userType']) == 'admin coordinator') && $type=='Completed'){
					$button .= '<li><a href="#" onclick="order_regenerate('.$salesId.')"><i class="fa fa-mail-reply"></i> Order Regenerate</a></li>';
					$button .= '<li><a href="wholesalesViewDetails.php?id='.$sID.'" target="_blank"><i class="fa fa-print tiny-icon"></i>Order Invoice</a></li>';
    		    }
				
    		$action .=	    '</ul>
    					</div>';
            $output['data'][] = array(
                $i++,
                $row['orderNo'].'<br>'.$row['orderDate'].'<br>'.$row['fname'].'<br>Fs : '.$row['salesDate'],
                $row['partyName'].'<br>'.$row['locationArea'].' - '.$row['tblCity'] .'<br>'.$row['contactPerson'] .' - '.$row['partyPhone'].'<br>NB: '.$row['remarks'],
                $row['salesProducts'],
                $totalAmount.'<br>'.$advance.'<br>'.$received.'<br>'.$bkash,
                $status,
                $button
            );
            } // /while 
    }// if num_rows
    
    $conn->close();
    
    echo json_encode($output);

?>
