<?php
$conPrefix = '../';
include $conPrefix . 'includes/session.php';
date_default_timezone_set('Asia/Dhaka');
$toDay = (new DateTime($test))->format("Y-m-d H:i:s");
$toDate = date("Y-m-d") ;
$loginID = $_SESSION['user'];
if(isset($_POST['action'])){
    $action = $_POST['action'];
    if($action == 'orderFinal'){
         $orderId = $_POST['orderId'];
         /*$sqlPayment = "SELECT tbl_customerId, tbl_userId, received_amount, rcv_date, bkash_amount, bkash_rcv_date, orderNo, bkash_number, tbl_bank_id, bank_reference
                        FROM tbl_orders
                        WHERE id='$orderId'";*/
        $sqlPayment = "SELECT tbl_customerId, tbl_userId, received_amount, rcv_date, bkash_amount, bkash_rcv_date, orderNo, bkash_number, tbl_bank_id, bank_reference, tbl_paymentMethod_id, tbl_paymentMethod.methodName
                    FROM tbl_orders
                    LEFT OUTER JOIN tbl_paymentMethod ON tbl_orders.tbl_paymentMethod_id = tbl_paymentMethod.id
                    WHERE tbl_orders.id='$orderId'";
                $queryPayment = $conn->query($sqlPayment);
                while ($rowPayment = $queryPayment->fetch_array()) {
                    $orderNo = $rowPayment['orderNo'];
                    $customers = $rowPayment['tbl_customerId'];
                    $bankId = $rowPayment['tbl_bank_id'];
                    $bankReference = $rowPayment['bank_reference'];
                    $chequeIssueDate = $rowPayment['rcv_date'];
                    $paidAmount = round($rowPayment['received_amount']);
                    $BkashPaymentDate = $rowPayment['bkash_rcv_date'];
                    $bkashAmount = round($rowPayment['bkash_amount']);
                    $bkashNumber = $rowPayment['bkash_number'];
                    $paymentMethodId = $rowPayment['tbl_paymentMethod_id'];
                    $bkashPaymentNumber = $rowPayment['methodName'];
                    $paymentMethod = 'CHEQUE';
                    $customerType = 'Party';
         $sql = "SELECT Sum(CASE tbl_paymentVoucher.type
                           WHEN 'partyPayable' THEN tbl_paymentVoucher.amount
                           WHEN 'paymentReceived' THEN -tbl_paymentVoucher.amount
                           WHEN 'adjustment' THEN  -tbl_paymentVoucher.amount
                           WHEN 'payable' THEN -tbl_paymentVoucher.amount
                           WHEN 'payment' THEN tbl_paymentVoucher.amount
                           WHEN 'paymentAdjustment' THEN tbl_paymentVoucher.amount
                           WHEN 'discount' THEN -tbl_paymentVoucher.amount
                           END) AS total
                            FROM tbl_paymentVoucher
                            WHERE tbl_partyId = '".$customers."' AND customerType = 'Party' and deleted='No'";
                		$query = $conn->query($sql);
                		while ($prow = $query->fetch_assoc()) {
                			$previousDue = $prow['total'];
                		}
                		if($previousDue == ''){
                		    $previousDue = 0;
                		}
                }
         $status = 'Processing';
         $sql = "SELECT LPAD(max(salesOrderNo)+1, 6, 0) as salesCode from tbl_sales where type='PartySale'";
		$query = $conn->query($sql);
		while ($prow = $query->fetch_assoc()) {
			$salesOrderNo = $prow['salesCode'];
		}
		if($salesOrderNo == ""){
		    $salesOrderNo = "000001";
		}
		
        $sql = "INSERT INTO tbl_sales(salesOrderNo,salesDate,tbl_customerId, tbl_userId, remarks, totalAmount, productDiscount, salesDiscount, totalDiscount, grandTotal, discountNote, vat, ait, createdDate, createdBy, type, carringCost, requisitionNo, tbl_wareHouseId, tbl_transport_info, projectName, previousDue, paidAmount, totalDue, tbl_orders_id)
                SELECT '$salesOrderNo','$toDay',tbl_customerId, tbl_userId, remarks, checked_total, checked_discount, ordersDiscount, checked_discount+ordersDiscount, total_after_discount, discountNote, vat, ait, '$toDay', '$loginID', 'PartySale', carringCost, requisitionNo, '1', tbl_transport_info, projectName, $previousDue, (received_amount+bkash_amount), totalDue, id 
                FROM tbl_orders 
                WHERE status = '$status' AND id='$orderId'";
        if($conn->query($sql)){
            $salesId = $conn->insert_id;
            if($salesId > 0){
                $sql = "INSERT INTO tbl_sales_products(tbl_salesId, tbl_productsId, quantity, units, remarks, createdDate, createdBy, salesAmount, totalAmount, discount, grandTotal, tbl_wareHouseId,  tbl_discount_offer_id, tbl_order_details_id) 
                        SELECT '$salesId',tbl_products_id, checked_quantity-offer_quantity, units, offer_discount_amount-(offer_quantity*salesAmount), '$toDay', '$loginID', salesAmount, (checked_quantity-offer_quantity)*salesAmount, remarks, ((checked_quantity-offer_quantity)*salesAmount)-offer_discount_amount+(offer_quantity*salesAmount), tbl_wareHouseId, tbl_discount_offer_id, id
                        FROM tbl_order_details 
                        WHERE tbl_orders_id='$orderId' AND checked_quantity>0 AND status <> 'Cancel' AND deleted='No'";
                $conn->query($sql);
                /*$sql = "UPDATE tbl_sales set status='Completed' where id='$orderId'";
                $conn->query($sql);*/
                $sql = "UPDATE tbl_orders set status='Completed' where id='$orderId'";
                $conn->query($sql);
                
                $sql = "INSERT INTO tbl_sales_products(tbl_salesId, tbl_productsId, quantity, units, remarks, createdDate, createdBy, salesAmount, totalAmount, discount, grandTotal, tbl_wareHouseId, tbl_discount_offer_id, tbl_order_details_id) 
                        SELECT '$salesId',tbl_products_id, offer_quantity, units, offer_quantity*salesAmount, '$toDay', '$loginID', salesAmount, offer_quantity*salesAmount, remarks, 0, tbl_wareHouseId, tbl_discount_offer_id, id
                        FROM tbl_order_details 
                        WHERE tbl_orders_id='$orderId' AND checked_quantity>0 AND tbl_order_details.offer_quantity > 0 AND status <> 'Cancel' AND deleted='No'";
                $conn->query($sql);
                
                
                $sql = "SELECT tbl_products_id, (checked_quantity) as totalQty, units, remarks, total_after_discount, tbl_wareHouseId
                        FROM tbl_order_details 
                        WHERE tbl_orders_id='$orderId' AND status <> 'Cancel' AND deleted='No'";
                $query = $conn->query($sql);
        		while ($prow = $query->fetch_assoc()) {
        		    $totalAmount += $prow['total_after_discount'];
        		    $totalQty = $prow['totalQty'];
        		    $productId = $prow['tbl_products_id'];
        		    $wareHouse = $prow['tbl_wareHouseId'];
        		    //$wareHouse = '1';
        			$sql = "UPDATE tbl_currentStock 
						    set salesStock=salesStock+$totalQty, currentStock=currentStock-$totalQty, lastUpdatedDate='$toDay', lastUpdatedBy='$loginID'
						    where tbl_productsId = '$productId' AND tbl_wareHouseId='$wareHouse'";
    			    $query1 = $conn->query($sql);
    				if($conn->affected_rows == 0){
    					$sql = "insert into tbl_currentStock 
    					            (salesStock, currentStock, tbl_productsId, tbl_wareHouseId, entryBy,entryDate) values 
    					            ('$totalQty', '-$totalQty','$productId','$wareHouse','$loginID','$toDay')";
    					$query2 = $conn->query($sql);
    				}
    				if($query1 || $query2){
    				    $sql = "UPDATE tbl_products 
                                SET saleTime=saleTime+1
                                WHERE id='$productId' AND deleted='No' AND status='Active'";
                        $conn->query($sql);
    				    
    				}
        		}
        		
                    
					/*$sql = "UPDATE tbl_sales set previousDue='$previousDue' where tbl_orders_id='$orderId' AND deleted='No' AND type='PartySale'";
                    $conn->query($sql);*/
                    $sql = "SELECT LPAD(IFNULL(max(voucherNo),0)+1, 6, 0) as voucherCode, LPAD(IFNULL(max(voucherNo),0)+2, 6, 0) as voucherReceiveCode, LPAD(IFNULL(max(voucherNo),0)+3, 6, 0) as voucherReceiveCodeForBkash FROM tbl_paymentVoucher WHERE tbl_partyId='$customers' AND customerType = '$customerType'";
            		$query = $conn->query($sql);
            		while ($prow = $query->fetch_assoc()) {
            			$voucherNo = $prow['voucherCode'];
            			$voucherReceiveNo = $prow['voucherReceiveCode'];
            			$voucherRNForBkash = $prow['voucherReceiveCodeForBkash'];
            		}
            		if($chequeIssueDate == "0000-00-00"){
            		    $chequeIssueDate = $BkashPaymentDate;
            		}
            		
            		if($chequeIssueDate == "0000-00-00" || $chequeIssueDate == ""){
            		   $chequeIssueDate = $toDate;
            		}
            		 $sql = "INSERT INTO tbl_paymentVoucher (tbl_partyId, tbl_sales_id, amount, entryBy, paymentMethod, paymentDate, status, remarks, type, voucherType, voucherNo, customerType,entryDate) 
            							VALUES ('$customers', '$salesId', '$totalAmount', '$loginID', 'CASH', '$toDate', 'Active', 'Payable for Party Sales Code: $orderNo', 'partyPayable', 'PartySale', '$voucherNo', '$customerType','$toDay')";
					$conn->query($sql);
            		if($paymentMethodId != "" && $paymentMethodId != "0"){
            		    $paymentMethod = $bkashPaymentNumber;
					    $sql = "INSERT INTO tbl_paymentVoucher (tbl_partyId, tbl_sales_id, amount, entryBy, paymentMethod, paymentDate, status, remarks, type, voucherType, voucherNo, customerType,entryDate) 
        							VALUES ('$customers', '$salesId', '$paidAmount', '$loginID', '$paymentMethod', '$chequeIssueDate', 'Active', 'bkash payment for Party Order No: $orderNo', 'paymentReceived', 'PartySale', '$voucherReceiveNo', '$customerType','$toDay')";
					    $conn->query($sql);
            		}else{
            		    $sql = "INSERT INTO tbl_paymentVoucher (tbl_partyId, tbl_sales_id, amount, entryBy, paymentMethod, paymentDate,chequeIssueDate, status, remarks, type, voucherType, voucherNo, customerType,entryDate, tbl_bankInfoId, chequeNo) 
        							VALUES ('$customers', '$salesId', '$paidAmount', '$loginID', '$paymentMethod', '$chequeIssueDate','$chequeIssueDate', 'Active', 'payment for Party Order No: $orderNo', 'paymentReceived', 'PartySale', '$voucherReceiveNo', '$customerType','$toDay', '$bankId', '$bankReference')";    
            		    $conn->query($sql);
            		}
					
					if($bkashAmount > 0){
					    $paymentMethod = $bkashNumber;
					    $sql = "INSERT INTO tbl_paymentVoucher (tbl_partyId, tbl_sales_id, amount, entryBy, paymentMethod, paymentDate, status, remarks, type, voucherType, voucherNo, customerType,entryDate) 
        							VALUES ('$customers', '$salesId', '$bkashAmount', '$loginID', '$paymentMethod', '$BkashPaymentDate', 'Active', 'payment for Party Order No: $orderNo', 'paymentReceived', 'PartySale', '$voucherRNForBkash', '$customerType','$toDay')";
					    $conn->query($sql);
					}
					
                //}
                
                echo 'Success';
            }
        }
    }
}else{
    $sql = "SELECT tbl_orders.id,tbl_orders.orderNo,tbl_orders.orderDate,tbl_orders.type,tbl_orders.tbl_customerId,tbl_party.partyName,tbl_party.partyAddress,tbl_party.locationArea,
            tbl_party.tblCity,tbl_party.contactPerson,tbl_party.partyPhone,tbl_users.fname,tbl_orders.remarks,tbl_orders.totalAmount,tbl_orders.paidAmount,tbl_orders.received_amount,tbl_orders.bkash_amount,tbl_orders.status,tbl_orders.total_after_discount,
            tbl_orders.createdDate,tbl_order_details.tbl_products_id,CONCAT('<ul>', GROUP_CONCAT(CONCAT('<li>', tbl_products.productName,' - ',tbl_products.productCode, ' (',tbl_order_details.checked_quantity,' / ',tbl_order_details.quantity,')') SEPARATOR '</li>'), '</li></ul>') AS salesProducts
            FROM tbl_orders
            inner JOIN tbl_order_details ON tbl_order_details.tbl_orders_id=tbl_orders.id AND  tbl_order_details.deleted='No'
            inner JOIN tbl_party ON tbl_party.id=tbl_orders.tbl_customerId AND tbl_party.status='Active'
            inner JOIN tbl_products ON tbl_products.id=tbl_order_details.tbl_products_id
            Inner JOIN tbl_users ON tbl_users.id=tbl_orders.tbl_userId AND tbl_users.accountStatus='approved'
            WHERE tbl_orders.status='Processing' 
            GROUP BY tbl_orders.orderNo  ORDER BY tbl_orders.id DESC";
    $result = $conn->query($sql);
    $output = array('data' => array());
    if ($result->num_rows > 0) {
        $unitStatus = "";
        $i = 1;
            while ($row = $result->fetch_array()) {
                $statuss="";
                if($row['status']=='Processing'){
                    $totalAdvance=$row['received_amount']+floatval($row['bkash_amount']);
                    $totalReceived=floatval($row['paidAmount']);
                    if($totalReceived > $totalAdvance){
                        $statuss= '<b style="color: red;">Processing Hold</b>';
                        //$statuss .= floatval($row['paidAmount']) .">=". floatval($row['received_amount']);
                        $totalAmount= 'Total : '.$row['totalAmount'].' ('.$row['total_after_discount'].')';
                        $advance='Paid : '.$row['paidAmount'];
                        $received='Received  : '.$row['received_amount'];
                        $bkash='Bkash  : '.$row['bkash_amount'];
                    }else{
                        $statuss= '<b style="color: green;">Processing</b>';
                        //$statuss .= floatval($row['paidAmount']) ."<". floatval($row['received_amount']);
                        $totalAmount= 'Total : '.$row['totalAmount'].' ('.$row['total_after_discount'].')';
                        $advance='Paid : '.$row['paidAmount'];
                        $received='Received  : '.$row['received_amount'];
                        $bkash='Bkash  : '.$row['bkash_amount'];
                    }
                    
                }
                $salesId = $row['id'];
                $button = '	<div class="btn-group">
        						<button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
        						<i class="fa fa-gear tiny-icon"></i>  <span class="caret"></span></button>
        						<ul class="dropdown-menu dropdown-menu-right" style="border: 1px solid gray;" role="menu">
        							<li><a href="tempSalesOrderViewDetails.php?id='.$row['id'].'" target="_blank"><i class="fa fa-print tiny-icon"></i>View Details</a></li>';
        							if(strtolower($_SESSION['userType']) == 'sales executive' && $status = 'Processing'){
        							   //$button .= '<li><a href="#" onclick="viewOrderFinal('.$row['id'].')"><i class="fa fa-eye"></i> Amount Check </a></li>'; 
        							   $button .= '<li><a href="#" onclick="paymentBkash('.$row['id'].')"><i class="fa fa-try"></i> Payment Bkash </a></li>'; 
        							}
        							if((strtolower($_SESSION['userType']) == 'super admin' || strtolower($_SESSION['userType']) == 'admin coordinator')  && $status = 'Processing'){
        							    $button .= '<li><a href="#" onclick="changeProcessStatus('.$row['id'].')"><i class="fa fa-check-square-o"></i> Change Status</a></li>';
        							    $button .= '<li><a href="#" onclick="viewOrderFinal('.$row['id'].')"><i class="fa fa-eye"></i> Amount Check </a></li>';
    				                    $button .= '<li><a href="#" onclick="orderFinal('.$row['id'].')"><i class="fa fa-mail-reply"></i> Order Final</a></li>';
        							}
        							
    				if($row['status']=='Checked'){	
    					$button .= '<li><a href="checkConfirmedOrderList.php?salesId='.$row['id'].'&salesType='.$row['type'].'"><i class="fa fa-mail-reply"></i> Confirmed Order</a></li>';
    					
    				    
    				}
    				/*if(strtolower($_SESSION['userType']) != 'sales executive'){	
    					$button .= '<li><a href="checkOrderList.php?salesId='.$row['id'].'&salesType='.$row['type'].'"><i class="fa fa-mail-reply"></i> Check Order</a></li>';
    				    
    				}	
        		    if(strtolower($_SESSION['userType']) == 'super admin'){					
        		        $button .=  '<li><a href="#" onclick="deleteSales(' . $salesId . ')"><i class="fa fa-edit tiny-icon"></i>Delete</a></li>';
        		    }*/
        		$action .= '</ul>
        					</div>';
                $output['data'][] = array(
                    $i++,
                    $row['orderNo'].'<br>'.$row['fname'],
                    $row['partyName'].'<br>'.$row['locationArea'].' - '.$row['tblCity'] .'<br>'.$row['contactPerson'] .' - '.$row['partyPhone'],
                    $row['salesProducts'],
                    $totalAmount.'<br>'.$advance.'<br>'.$received.'<br>'.$bkash,
                    $statuss,
                    $button
                );
            } // /while 
    }// if num_rows
    
    $conn->close();
    
    echo json_encode($output);
}


?>
