<?php
$conPrefix = '../';
include $conPrefix . 'includes/session.php';
date_default_timezone_set('Asia/Dhaka');
$toDay = (new DateTime())->format("Y-m-d H:i:s");
$toDate = (new DateTime())->format("Y-m-d");
$loginID = $_SESSION['user'];
ordersGoBackToPending();
if(isset($_POST['action'])){
    $action = $_POST['action'];
    if($action == 'confirmOrder'){
        //$confirmOrderDate = $_POST['confirmOrderDate'];  
        $detailsId = $_POST['detailsId'];  
        $availableQTY = $_POST['availableQTY'];  
        $updatedAmount = $_POST['updatedAmount'];  
        $sailedAmount = $_POST['sailedAmount'];  
        $offerQty = $_POST['offerQty'];  
        $wareHouseIds = $_POST['wareHouseIds'];  
        $offerDiscountAmount = $_POST['offerDiscountAmount'];
        $totalAfterDiscount = $_POST['totalAfterDiscount'];
        $orderId = $_POST['orderId'];
        $orderNo = $_POST['orderCode'];
        $ordersId = $orderId;
        $detailsIdArray = explode(",",$detailsId);
        $availableQTYArray = explode(",",$availableQTY);
        $updatedAmountArray = explode(",",$updatedAmount);
        $sailedAmountArray = explode(",",$sailedAmount);
        $offerQtyArray = explode(",",$offerQty);
        $warehouseIdArray = explode(",",$wareHouseIds);
        $totalAfterDiscountArray = explode(",",$totalAfterDiscount);
        $totalAmountOrder = 0;
        $totalDiscountOfferValue = 0;
        $orderCheckedTotal = 0;
        $orderCheckedTotalDiscount = 0;
        $orderCheckedGrandTotal = 0;
        for($i = 0; $i < count($detailsIdArray); $i++) {
            $salesAmount = $updatedAmountArray[$i];
            $sellAmount = $sailedAmountArray[$i];
            $total_quantity = $availableQTYArray[$i];
            $warehouseId = $warehouseIdArray[$i];
            $totalAmountOrder += ($sellAmount*$total_quantity);
            //echo json_encode("Qty = ".$total_quantity.' - '.$detailsIdArray[$i]."<br>");
            if($detailsIdArray[$i] != "" && $detailsIdArray[$i] != "0" && $total_quantity > 0){
                $discount_pc = 0;
                    $total_discount_pc = 0;
                    $discount_amount = 0;
                    $rest_pc = 0;
                    $rest_amount = 0;
                    $test = 0;
                    $stock_check = 'On';
                    $discountOfferValue = 0;
                    $remarks = '';
                /*Offer Back Calculation Start*/
                $sql_discountOffer = "SELECT tbl_discount_offer.id,offer_applicable,offer_for,unit_for,tbl_discount_offer.discount,discount_unit,discount_2,discount_unit_2, offer_name, tbl_order_details.remarks, tbl_order_details.salesAmount, tbl_order_details.checked_amount
                                        FROM tbl_discount_offer 
                                        LEFT OUTER JOIN tbl_order_details ON tbl_order_details.tbl_products_id = tbl_discount_offer.tbl_products_id AND  tbl_order_details.status<>'Cancel' AND tbl_order_details.deleted='No'
        								WHERE '$toDate' BETWEEN date_from AND date_to AND tbl_discount_offer.status = 'Active' AND tbl_discount_offer.deleted = 'No' AND tbl_order_details.id = '".$detailsIdArray[$i]."' AND offer_applicable='Party' AND priority > 0
                                        ORDER BY offer_for DESC, priority DESC";
                 //echo json_encode($sql_discountOffer.'+');                       
                $result_discountOffer = $conn->query($sql_discountOffer);
                if($result_discountOffer->num_rows > 0 ){
                    $discount_pc = 0;
                    $total_discount_pc = 0;
                    $discount_amount = 0;
                    $rest_pc = 0;
                    $rest_amount = 0;
                    $test = 0;
                    $stock_check = 'On';
                    $discountOfferValue = 0;
                    $remarks = '';
                    while($row_discountOffer = $result_discountOffer->fetch_assoc()){
                        $discountOfferid = $row_discountOffer['id'];
                        $remarks = $row_discountOffer['remarks'];
                        //$salesAmount = $updatedAmountArray[$i];
                        if($row_discountOffer['unit_for'] == 'PC'){
                            //echo json_encode("===PC");
    				        if($row_discountOffer['discount_unit'] == 'PC'){
    				            //echo json_encode(" to PC");
    				            while($total_quantity >= $row_discountOffer['offer_for']){
    				                if(($row_discountOffer['offer_for'] + $row_discountOffer['discount']) <= $total_quantity){
    				                    //echo json_encode(" Step 1");
    				                    $total_quantity -= ($row_discountOffer['offer_for'] + $row_discountOffer['discount']);
    				                    $total_discount_pc += $row_discountOffer['discount'];
    				                    //json_encode($total_discount_pc);
    				                    $discountOfferValue += ($row_discountOffer['discount']*$sellAmount);
    				                    $discountOfferValue += $row_discountOffer['offer_for']*($sellAmount - $salesAmount);
    				                    /*if(substr($row_discountOffer['remarks'], -1) == '%'){
    				                        //echo json_encode(" Step 1.1");
                    				        $discountOfferValue += $row_discountOffer['offer_for']*($sellAmount*(substr($row_discountOffer['remarks'],0,-1)/100));
                    				    }else{
                    				        //echo json_encode(" Step 1.2");
                    				        $discountOfferValue += $row_discountOffer['offer_for']*$row_discountOffer['remarks'];
                    				    }*/
                    				    if($row_discountOffer['discount_unit_2'] == 'TK'){
                    				        //echo json_encode(" Step 2");
                						    if($row_discountOffer['discount_2'] != 0){
                						        //echo json_encode(" Step 2.1");
                							    $discountOfferValue += $row_discountOffer['discount_2'];
                						    }
                						}
    				                }else{
    				                    //echo json_encode(" Step 3");
    				                    $total_quantity -= $row_discountOffer['offer_for'];
				                        $total_discount_pc += $total_quantity;
				                        //json_encode($total_discount_pc);
				                        $discountOfferValue += ($total_quantity*$sellAmount);
				                        $discountOfferValue += $row_discountOffer['offer_for']*($sellAmount - $salesAmount);
    				                    /*if(substr($row_discountOffer['remarks'], -1) == '%'){
    				                        //echo json_encode(" Step 3.1");
                    				        $discountOfferValue += $row_discountOffer['offer_for']*($sellAmount*(substr($row_discountOffer['remarks'],0,-1)/100));
                    				    }else{
                    				        //echo json_encode(" Step 3.2");
                    				        $discountOfferValue += $row_discountOffer['offer_for']*$row_discountOffer['remarks'];
                    				    }*/
				                        $total_quantity = 0;
    				                }
    				            }
    						}
        					else if($row_discountOffer['discount_unit'] == '%'){
        						if($total_quantity >= $row_discountOffer['offer_for']){
        							$discount_quantity = $row_discountOffer['offer_for'] * floor($total_quantity / $row_discountOffer['offer_for']);
        							$productTotal = $discount_quantity * $sellAmount;
        							$discountOfferValue += $productTotal * ($row_discountOffer['discount']/100);
        							//$discountOfferValueOutput .= $discountOfferValue.' 1-4 ';
        							$total_quantity -=$row_discountOffer['offer_for'] * floor($total_quantity / $row_discountOffer['offer_for']);
        						}
        					}
    						else if($row_discountOffer['discount_unit'] == 'TK'){
    							if($total_quantity >= $row_discountOffer['offer_for']){
    								$discount_quantity = $row_discountOffer['offer_for'] * floor($total_quantity / $row_discountOffer['offer_for']);
    								$discountOfferValue += $row_discountOffer['discount']* floor($total_quantity / $row_discountOffer['offer_for']);
    								//$discountOfferValueOutput .= $discountOfferValue.' 1-5 ';
    								//$productTotal = ($discount_quantity * $productPriceEntry) - $productDiscount;
    								$total_quantity -=$row_discountOffer['offer_for'] * floor($total_quantity / $row_discountOffer['offer_for']);
                                }
    						}
                        }
                    }
                    if($total_quantity > 0){
                        //echo json_encode(" Step 4");
                       // if(substr($remarks, -1) == '%'){
                            //echo json_encode(" Step 4.1");
        			        $discountOfferValue += $total_quantity*($sellAmount - $salesAmount);
        			        $remarks = 100 - (($salesAmount / $sellAmount) * 100);
        			        $remarks .= '%';
        			    //}else{
        			        //echo json_encode(" Step 4.2");
        			        //$discountOfferValue += $total_quantity*($sellAmount - $salesAmount);
        			    //}
        			    //$discountOfferValue += ($salesAmount - )
                        //$discountOfferValue += $total_quantity*($row['salesAmount'] * $row['remarks']);
                        //$discountOfferValueOutput .= $discountOfferValue.' 1-6 ';
                    }
                    $grandTotal = ($availableQTYArray[$i]*$sellAmount)-$discountOfferValue;
                    /*Offer Back Calculation End*/
                    $sql = "UPDATE tbl_order_details 
                                SET status = 'Checked', checked_quantity='".$availableQTYArray[$i]."',checked_amount='".$salesAmount."',checked_total_amount=$availableQTYArray[$i]*$sellAmount, 
                                offer_quantity='".$total_discount_pc."', offer_discount_amount='".$discountOfferValue."', total_after_discount='".$grandTotal."', lastUpdatedDate='$toDay', 
                                lastUpdatedBy='$loginID', remarks='$remarks', tbl_wareHouseId='$warehouseId' 
                            WHERE id='".$detailsIdArray[$i]."'";
                             //echo json_encode($sql);
                    $conn->query($sql);
                    $orderCheckedTotal += ($availableQTYArray[$i]*$salesAmount);
                    $orderCheckedTotalDiscount += $discountOfferValue;
                    $orderCheckedGrandTotal += $grandTotal;
                    $lastDetailsId = $detailsIdArray[$i];
                    if($updatedAmountArray[$i] != '0'){
                        $countNoOfOrder++;
                    }
                }else{
                    $sql = "Select checked_quantity,checked_amount,checked_total_amount, offer_quantity,discount, quantity, offer_discount_amount, total_after_discount 
                            FROM tbl_order_details 
                                WHERE id='".$detailsIdArray[$i]."'";
                    $result = $conn->query($sql);
                    while($row = $result->fetch_assoc()){
                        $discountOfferValue += ($sellAmount - $salesAmount)*$availableQTYArray[$i];
                    }
                    $grandTotal = ($availableQTYArray[$i]*$sellAmount)- $discountOfferValue;
                    $remarks = (($sellAmount - $salesAmount)/$sellAmount)*100;
                    $sql = "UPDATE tbl_order_details 
                                    SET status = 'Checked', checked_quantity='".$availableQTYArray[$i]."',checked_amount='".$salesAmount."',checked_total_amount=$availableQTYArray[$i]*$sellAmount, 
                                    offer_quantity='".$total_discount_pc."', offer_discount_amount=($sellAmount-$salesAmount)*$availableQTYArray[$i], total_after_discount=($availableQTYArray[$i]*$sellAmount)-(($sellAmount-$salesAmount)*$availableQTYArray[$i]), 
                                    lastUpdatedDate='$toDay', lastUpdatedBy='$loginID', tbl_wareHouseId='$warehouseId', remarks = '$remarks%'  
                                WHERE id='".$detailsIdArray[$i]."'";
                                //echo json_encode($sql);
                    $conn->query($sql);
                    $orderCheckedTotal += ($availableQTYArray[$i]*$salesAmount);
                    $orderCheckedTotalDiscount += $discountOfferValue;
                    $orderCheckedGrandTotal += $grandTotal;
                    $lastDetailsId = $detailsIdArray[$i];
                    if($updatedAmountArray[$i] != '0'){
                        $countNoOfOrder++;
                    }
                }
            }else{
                $sql = "UPDATE tbl_order_details 
                                SET checked_quantity='0', status = 'Cancel', lastUpdatedDate='$toDay', lastUpdatedBy='$loginID' 
                                WHERE id='".$detailsIdArray[$i]."'";
                                 //echo json_encode($sql);
                    $conn->query($sql);
            }
            $totalDiscountOfferValue += $discountOfferValue; 
        }
        //status='Checked',
        $sql = "UPDATE tbl_orders 
                SET  totalAmount = $totalAmountOrder, productDiscount=$totalDiscountOfferValue, grandTotal = $totalAmountOrder-$totalDiscountOfferValue, checked_total=$orderCheckedTotal, checked_discount=$orderCheckedTotalDiscount, total_after_discount=$orderCheckedGrandTotal, lastUpdatedDate='$toDay', lastUpdatedBy='$loginID' 
                WHERE id='$orderId'";
                 //echo json_encode($sql);
        $conn->query($sql);
        /*$countNoOfOrder = $conn->affected_rows;*/
        /*$sql = "INSERT INTO tbl_notification (notification_title, notification, created_by, created_time, notification_link, order_id, notify_for) 
                VALUES ('Confirmed order no# $orderNo','Order# $orderNo, Confirmed by ".$user['fname']." ".$user['lname']." With $countNoOfOrder Items','$loginID','$toDay','orderProcessList.php?page=1','$ordersId','confirmOrder')";
        $conn->query($sql);*/
        echo json_encode('Success');
    }
    else if($action == 'salesOrderConfirm'){
        $confirmOrderDate = $_POST['confirmOrderDate'];  
        $isChange = $_POST['isChange'];  
        $detailsId = $_POST['detailsId'];  
        $checkChangeQTY = $_POST['checkChangeQTY'];  
        $accountNo = $_POST['accountNo'];
        $BKashId = $_POST['BKashId'];
        $bankRferenceNumber = $_POST['bankRferenceNumber'];
        $advanceAmount = $_POST['advanceAmount'];
        $transportName = $_POST['transportName'];
        $orderId = $_POST['orderId'];
        $orderNo = $_POST['orderCode'];
        $ordersId = $orderId;
        $detailsIdArray = explode(",",$detailsId);
        $isChangeArray = explode(",",$isChange);
        $availableQTYArray = explode(",",$checkChangeQTY);
        $orderCheckedTotal = 0;
        $orderCheckedTotalDiscount = 0;
        $orderCheckedGrandTotal = 0;
        if($BKashId!="undefined" || $accountNo != ""){
            for($i = 0; $i < count($detailsIdArray); $i++) {
                $total_quantity = $availableQTYArray[$i];
                if($detailsIdArray[$i] != "" && $detailsIdArray[$i] != "0" && $isChangeArray[$i] == "1" && $total_quantity > 0){
                    /*Offer Back Calculation Start*/
                    $sql_discountOffer = "SELECT tbl_discount_offer.id,offer_applicable,offer_for,unit_for,tbl_discount_offer.discount,discount_unit,discount_2,discount_unit_2, offer_name, tbl_order_details.remarks, tbl_order_details.salesAmount, tbl_order_details.checked_amount
                                            FROM tbl_discount_offer 
                                            LEFT OUTER JOIN tbl_order_details ON tbl_order_details.tbl_products_id = tbl_discount_offer.tbl_products_id AND  tbl_order_details.status<>'Cancel' AND  tbl_order_details.deleted='No'
            								WHERE '$toDate' BETWEEN date_from AND date_to AND tbl_discount_offer.status = 'Active' AND tbl_discount_offer.deleted = 'No' AND tbl_order_details.id = '".$detailsIdArray[$i]."' AND offer_applicable='Party' AND priority > 0
                                            ORDER BY offer_for DESC, priority DESC";
                     //echo json_encode($sql_discountOffer.'+');                       
                    $result_discountOffer = $conn->query($sql_discountOffer);
                    $discount_pc = 0;
                    $total_discount_pc = 0;
                    $discount_amount = 0;
                    $rest_pc = 0;
                    $rest_amount = 0;
                    $test = 0;
                    $stock_check = 'On';
                    $discountOfferValue = 0;
                    $remarks = '';
                    $salesAmount = 0;
                    $sellAmount = 0;
                   
                    if($result_discountOffer->num_rows > 0 ){
                        while($row_discountOffer = $result_discountOffer->fetch_assoc()){
                            $discountOfferid = $row_discountOffer['id'];
                            $remarks = $row_discountOffer['remarks'];
                            $salesAmount = $row_discountOffer['checked_amount'];
                            $sellAmount = $row_discountOffer['salesAmount'];
                            if($row_discountOffer['unit_for'] == 'PC'){
        				        if($row_discountOffer['discount_unit'] == 'PC'){
        				            while($total_quantity >= $row_discountOffer['offer_for']){
        				                if(($row_discountOffer['offer_for'] + $row_discountOffer['discount']) <= $total_quantity){
        				                    $total_quantity -= ($row_discountOffer['offer_for'] + $row_discountOffer['discount']);
        				                    $total_discount_pc += $row_discountOffer['discount'];
        				                    $discountOfferValue += ($row_discountOffer['discount']*$sellAmount);
        				                    $discountOfferValue += $row_discountOffer['offer_for']*($sellAmount - $salesAmount);
        				                    /*if(substr($row_discountOffer['remarks'], -1) == '%'){
                        				        $discountOfferValue += $row_discountOffer['offer_for']*($salesAmount*(substr($row_discountOffer['remarks'],0,-1)/100));
                        				    }else{
                        				        $discountOfferValue += $row_discountOffer['offer_for']*$row_discountOffer['remarks'];
                        				    }*/
                        				    if($row_discountOffer['discount_unit_2'] == 'TK'){
                    						    if($row_discountOffer['discount_2'] != 0){
                    							    $discountOfferValue += $row_discountOffer['discount_2'];
                    						    }
                    						}
                    						//echo json_encode($discountOfferValue);
        				                }else{
        				                    $total_quantity -= $row_discountOffer['offer_for'];
    				                        $total_discount_pc += $total_quantity;
    				                        $discountOfferValue += ($total_quantity*$sellAmount);
    				                        $discountOfferValue += $row_discountOffer['offer_for']*($sellAmount - $salesAmount);
        				                    /*if(substr($row_discountOffer['remarks'], -1) == '%'){
                        				        $discountOfferValue += $row_discountOffer['offer_for']*($salesAmount*(substr($row_discountOffer['remarks'],0,-1)/100));
                        				    }else{
                        				        $discountOfferValue += $row_discountOffer['offer_for']*$row_discountOffer['remarks'];
                        				    }*/
    				                        $total_quantity = 0;
        				                }
        				            }
        						}
            					else if($row_discountOffer['discount_unit'] == '%'){
            						if($total_quantity >= $row_discountOffer['offer_for']){
            							$discount_quantity = $row_discountOffer['offer_for'] * floor($total_quantity / $row_discountOffer['offer_for']);
            							$productTotal = $discount_quantity * $sellAmount;
            							$discountOfferValue += $productTotal * ($row_discountOffer['discount']/100);
            							//$discountOfferValueOutput .= $discountOfferValue.' 1-4 ';
            							$total_quantity -=$row_discountOffer['offer_for'] * floor($total_quantity / $row_discountOffer['offer_for']);
            						}
            					}
        						else if($row_discountOffer['discount_unit'] == 'TK'){
        							if($total_quantity >= $row_discountOffer['offer_for']){
        								$discount_quantity = $row_discountOffer['offer_for'] * floor($total_quantity / $row_discountOffer['offer_for']);
        								$discountOfferValue += $row_discountOffer['discount']* floor($total_quantity / $row_discountOffer['offer_for']);
        								//$discountOfferValueOutput .= $discountOfferValue.' 1-5 ';
        								//$productTotal = ($discount_quantity * $productPriceEntry) - $productDiscount;
        								$total_quantity -=$row_discountOffer['offer_for'] * floor($total_quantity / $row_discountOffer['offer_for']);
                                    }
        						}
                            }
                            
                        }
                        if($total_quantity > 0){
                            $discountOfferValue += $total_quantity*($sellAmount - $salesAmount);
        			        $remarks = 100 - (($salesAmount / $sellAmount) * 100);
        			        $remarks .= '%';
                            /*if(substr($remarks, -1) == '%'){
            			        $discountOfferValue += $total_quantity*($salesAmount*(substr($remarks,0,-1)/100));
            			    }else{
            			        $discountOfferValue += $total_quantity*$remarks;
            			    }*/
                            //$discountOfferValue += $total_quantity*($row['salesAmount'] * $row['remarks']);
                            //$discountOfferValueOutput .= $discountOfferValue.' 1-6 ';
                        }
                        $grandTotal = ($availableQTYArray[$i]*$sellAmount)-$discountOfferValue;
                        
                            /*Offer Back Calculation End*/
                            $sql = "UPDATE tbl_order_details 
                                    SET status = 'Processing', checked_quantity='".$availableQTYArray[$i]."',checked_total_amount=$availableQTYArray[$i]*$sellAmount, offer_quantity='".$total_discount_pc."', offer_discount_amount='".$discountOfferValue."', total_after_discount='".$grandTotal."', lastUpdatedDate='$toDay', lastUpdatedBy='$loginID', remarks='$remarks' 
                                    WHERE id='".$detailsIdArray[$i]."'";
                            $conn->query($sql);
                            /*$orderCheckedTotal +=($availableQTYArray[$i]*$salesAmount);
                            $orderCheckedTotalDiscount += $discountOfferValue;
                            $orderCheckedGrandTotal += $grandTotal;*/
                            $lastDetailsId = $detailsIdArray[$i];
                            if($updatedAmountArray[$i] != '0'){
                                $countNoOfOrder++;
                            }
                    }else{
                        $sql = "Select checked_quantity,checked_amount,salesAmount,checked_total_amount, offer_quantity,discount, quantity, offer_discount_amount, total_after_discount 
                                FROM tbl_order_details 
                                    WHERE id='".$detailsIdArray[$i]."'";
                         
                        $result = $conn->query($sql);
                        while($row = $result->fetch_assoc()){
                            $discountOfferValue += ($row['salesAmount'] - $row['checked_amount'])*$availableQTYArray[$i];
                            $salesAmount = $row['salesAmount'];
                        }
                        $grandTotal = ($availableQTYArray[$i]*$salesAmount)- $discountOfferValue;
                        $sql = "UPDATE tbl_order_details 
                                    SET status = 'Processing', checked_quantity='".$availableQTYArray[$i]."',checked_total_amount=$availableQTYArray[$i]*$salesAmount, offer_quantity='".$total_discount_pc."', offer_discount_amount='".$discountOfferValue."', total_after_discount='".$grandTotal."', lastUpdatedDate='$toDay', lastUpdatedBy='$loginID'  
                                    WHERE id='".$detailsIdArray[$i]."'";
                        $conn->query($sql);
                        /*$orderCheckedTotal +=($availableQTYArray[$i]*$salesAmount);
                        $orderCheckedTotalDiscount += $discountOfferValue;
                        $orderCheckedGrandTotal += $grandTotal;*/
                        $lastDetailsId = $detailsIdArray[$i];
                        if($updatedAmountArray[$i] != '0'){
                            $countNoOfOrder++;
                        }
                    }
                    
                    
                    
                }else{
                    if($total_quantity != 0){
                        /*$sql = "Select checked_quantity,checked_amount,checked_total_amount, offer_quantity,discount, quantity, offer_discount_amount, total_after_discount 
                                FROM tbl_order_details 
                                    WHERE id='".$detailsIdArray[$i]."'";
                        $result = $conn->query($sql);
                        while($row = $result->fetch_assoc()){
                            $discountOfferValue += $row['offer_discount_amount'];
                            $salesAmount = $row['checked_amount'];
                        }
                        $grandTotal = ($availableQTYArray[$i]*$salesAmount)- $discountOfferValue;
                        $orderCheckedTotal +=($availableQTYArray[$i]*$salesAmount);
                        $orderCheckedTotalDiscount += $discountOfferValue;
                        $orderCheckedGrandTotal += $grandTotal;*/
                    }else{
                        $sql = "UPDATE tbl_order_details 
                                    SET status = 'Processing', checked_quantity='0',checked_total_amount=0, offer_quantity='0', offer_discount_amount=0, total_after_discount=0, lastUpdatedDate='$toDay', lastUpdatedBy='$loginID'  
                                    WHERE id='".$detailsIdArray[$i]."'";
                        $conn->query($sql);
                    }
                }
            }
            $sql = "SELECT SUM(checked_total_amount) as checked_total_amount, SUM(offer_discount_amount) as offer_discount_amount, SUM(total_after_discount) as total_after_discount, lastUpdatedDate='$toDay', lastUpdatedBy='$loginID'  
                    FROM `tbl_order_details`
                    WHERE tbl_orders_id='$orderId'";
            $result = $conn->query($sql);
            while($row = $result->fetch_assoc()){
                $orderCheckedTotal = $row['checked_total_amount'];
                $orderCheckedTotalDiscount = $row['offer_discount_amount'];
                $orderCheckedGrandTotal = $row['total_after_discount'];
            }        
            $sql = "UPDATE tbl_orders 
                    SET tbl_bank_id='$accountNo',tbl_paymentMethod_id='$BKashId', bank_reference='$bankRferenceNumber', paidAmount='$advanceAmount', tbl_transport_info='$transportName', confirm_date='$confirmOrderDate', status='Processing', checked_total=$orderCheckedTotal, checked_discount=$orderCheckedTotalDiscount, total_after_discount=$orderCheckedGrandTotal, lastUpdatedDate='$toDay', lastUpdatedBy='$loginID' 
                    WHERE id='$orderId'";
            $conn->query($sql);
            $countNoOfOrder = $conn->affected_rows;
            echo json_encode('Success');
        }else{
            echo json_encode("Any one payment system must be selected from Bkash and accountno");
        }
        /*$sql = "INSERT INTO tbl_notification (notification_title, notification, created_by, created_time, notification_link, order_id, notify_for) 
                VALUES ('Confirmed order no# $orderNo','Order# $orderNo, Confirmed by ".$user['fname']." ".$user['lname']." With $countNoOfOrder Items','$loginID','$toDay','orderProcessList.php?page=1','$ordersId','confirmOrder')";
        $conn->query($sql);*/
       
    }
    
    else if($action == 'recalculation'){
        $detailsId = $_POST['detailsId'];  
        $availableQTY = $_POST['availableQTY'];  
        $updatedAmount = $_POST['updatedAmount'];  
        $sailedAmount = $_POST['sailedAmount'];  
        $offerQty = $_POST['offerQty'];  
        $offerDiscountAmount = $_POST['offerDiscountAmount'];
        $totalAfterDiscount = $_POST['totalAfterDiscount'];
        $detailsIdArray = explode(",",$detailsId);
        $availableQTYArray = explode(",",$availableQTY);
        $updatedAmountArray = explode(",",$updatedAmount);
        $sailedAmountArray = explode(",",$sailedAmount);
        $offerQtyArray = explode(",",$offerQty);
        $totalAfterDiscountArray = explode(",",$totalAfterDiscount);
        $totalAmountOrder = 0;
        $totalDiscountOfferValue = 0;
        $orderCheckedTotal = 0;
        $orderCheckedTotalDiscount = 0;
        $orderCheckedGrandTotal = 0;
        $output = array('data' => array());
        for($i = 0; $i < count($detailsIdArray); $i++) {
            $salesAmount = $updatedAmountArray[$i];
            $sellAmount = $sailedAmountArray[$i];
            $total_quantity = $availableQTYArray[$i];
            $totalAmountOrder += ($sellAmount*$total_quantity);
            $orderDetailsId = $detailsIdArray[$i];
            if($detailsIdArray[$i] != "" && $detailsIdArray[$i] != "0" && $total_quantity > 0){
                $discount_pc = 0;
                $total_discount_pc = 0;
                $discount_amount = 0;
                $rest_pc = 0;
                $rest_amount = 0;
                $test = 0;
                $stock_check = 'On';
                $discountOfferValue = 0;
                $remarks = '';
                /*Offer Back Calculation Start*/
                $sql_discountOffer = "SELECT tbl_discount_offer.id,offer_applicable,offer_for,unit_for,tbl_discount_offer.discount,discount_unit,discount_2,discount_unit_2, offer_name, tbl_order_details.remarks, tbl_order_details.salesAmount, tbl_order_details.checked_amount
                                        FROM tbl_discount_offer 
                                        LEFT OUTER JOIN tbl_order_details ON tbl_order_details.tbl_products_id = tbl_discount_offer.tbl_products_id AND tbl_order_details.deleted='No'
        								WHERE '$toDate' BETWEEN date_from AND date_to AND tbl_discount_offer.status = 'Active' AND tbl_discount_offer.deleted = 'No' AND tbl_order_details.id = '".$detailsIdArray[$i]."' AND offer_applicable='Party' AND priority > 0
                                        ORDER BY offer_for DESC, priority DESC";
                 //echo json_encode($sql_discountOffer.'+');                       
                $result_discountOffer = $conn->query($sql_discountOffer);
                if($result_discountOffer->num_rows > 0 ){
                    $discount_pc = 0;
                    $total_discount_pc = 0;
                    $discount_amount = 0;
                    $rest_pc = 0;
                    $rest_amount = 0;
                    $test = 0;
                    $stock_check = 'On';
                    $discountOfferValue = 0;
                    $remarks = '';
                    while($row_discountOffer = $result_discountOffer->fetch_assoc()){
                        $discountOfferid = $row_discountOffer['id'];
                        $remarks = $row_discountOffer['remarks'];
                        if($row_discountOffer['unit_for'] == 'PC'){
    				        if($row_discountOffer['discount_unit'] == 'PC'){
    				            //echo json_encode(" to PC");
    				            while($total_quantity >= $row_discountOffer['offer_for']){
    				                if(($row_discountOffer['offer_for'] + $row_discountOffer['discount']) <= $total_quantity){
    				                    $total_quantity -= ($row_discountOffer['offer_for'] + $row_discountOffer['discount']);
    				                    $total_discount_pc += $row_discountOffer['discount'];
    				                    $discountOfferValue += ($row_discountOffer['discount']*$sellAmount);
    				                    $discountOfferValue += $row_discountOffer['offer_for']*($sellAmount - $salesAmount);
                    				    if($row_discountOffer['discount_unit_2'] == 'TK'){
                						    if($row_discountOffer['discount_2'] != 0){
                							    $discountOfferValue += $row_discountOffer['discount_2'];
                						    }
                						}
    				                }else{
    				                    $total_quantity -= $row_discountOffer['offer_for'];
				                        $total_discount_pc += $total_quantity;
				                        $discountOfferValue += ($total_quantity*$sellAmount);
				                        $discountOfferValue += $row_discountOffer['offer_for']*($sellAmount - $salesAmount);
				                        $total_quantity = 0;
    				                }
    				            }
    						}
        					else if($row_discountOffer['discount_unit'] == '%'){
        						if($total_quantity >= $row_discountOffer['offer_for']){
        							$discount_quantity = $row_discountOffer['offer_for'] * floor($total_quantity / $row_discountOffer['offer_for']);
        							$productTotal = $discount_quantity * $sellAmount;
        							$discountOfferValue += $productTotal * ($row_discountOffer['discount']/100);
        							$total_quantity -=$row_discountOffer['offer_for'] * floor($total_quantity / $row_discountOffer['offer_for']);
        						}
        					}
    						else if($row_discountOffer['discount_unit'] == 'TK'){
    							if($total_quantity >= $row_discountOffer['offer_for']){
    								$discount_quantity = $row_discountOffer['offer_for'] * floor($total_quantity / $row_discountOffer['offer_for']);
    								$discountOfferValue += $row_discountOffer['discount']* floor($total_quantity / $row_discountOffer['offer_for']);
    								$total_quantity -=$row_discountOffer['offer_for'] * floor($total_quantity / $row_discountOffer['offer_for']);
                                }
    						}
                        }
                    }
                    if($total_quantity > 0){
    			        $discountOfferValue += $total_quantity*($sellAmount - $salesAmount);
    			        $remarks = 100 - (($salesAmount / $sellAmount) * 100);
    			        $remarks .= '%';
                    }
                    $grandTotal = ($availableQTYArray[$i]*$sellAmount)-$discountOfferValue;
                    /*Offer Back Calculation End*/
                    /*$sql = "UPDATE tbl_order_details 
                            SET status = 'Checked', checked_quantity='".$availableQTYArray[$i]."',checked_amount='".$salesAmount."',checked_total_amount=$availableQTYArray[$i]*$sellAmount, offer_quantity='".$total_discount_pc."', offer_discount_amount='".$discountOfferValue."', total_after_discount='".$grandTotal."', lastUpdatedDate='$toDay', lastUpdatedBy='$loginID', remarks='$remarks' 
                            WHERE id='".$detailsIdArray[$i]."'";
                    $conn->query($sql);*/
                    $output['data'][] = array(
                        $orderDetailsId,
                        $total_discount_pc,
                        $discountOfferValue,
                        $grandTotal
                    );
                    $orderCheckedTotal += ($availableQTYArray[$i]*$salesAmount);
                    $orderCheckedTotalDiscount += $discountOfferValue;
                    $orderCheckedGrandTotal += $grandTotal;
                    $lastDetailsId = $detailsIdArray[$i];
                    if($updatedAmountArray[$i] != '0'){
                        $countNoOfOrder++;
                    }
                }else{
                    $sql = "Select checked_quantity,checked_amount,checked_total_amount, offer_quantity,discount, quantity, offer_discount_amount, total_after_discount 
                            FROM tbl_order_details 
                                WHERE id='".$detailsIdArray[$i]."'";
                    $result = $conn->query($sql);
                    while($row = $result->fetch_assoc()){
                        $discountOfferValue += ($sellAmount - $salesAmount)*$availableQTYArray[$i];
                    }
                    $grandTotal = ($availableQTYArray[$i]*$sellAmount)- $discountOfferValue;
                    /*$sql = "UPDATE tbl_order_details 
                                SET status = 'Checked', checked_quantity='".$availableQTYArray[$i]."',checked_amount='".$salesAmount."',checked_total_amount=$availableQTYArray[$i]*$sellAmount, offer_quantity='".$total_discount_pc."', offer_discount_amount=($sellAmount-$salesAmount)*$availableQTYArray[$i], total_after_discount=($availableQTYArray[$i]*$sellAmount)-(($sellAmount-$salesAmount)*$availableQTYArray[$i]), lastUpdatedDate='$toDay', lastUpdatedBy='$loginID' 
                                WHERE id='".$detailsIdArray[$i]."'";
                                //echo json_encode($sql);
                    $conn->query($sql);*/
                    $output['data'][] = array(
                        $orderDetailsId,
                        $total_discount_pc,
                        $discountOfferValue,
                        $grandTotal
                    );
                    $orderCheckedTotal += ($availableQTYArray[$i]*$salesAmount);
                    $orderCheckedTotalDiscount += $discountOfferValue;
                    $orderCheckedGrandTotal += $grandTotal;
                    $lastDetailsId = $detailsIdArray[$i];
                    if($updatedAmountArray[$i] != '0'){
                        $countNoOfOrder++;
                    }
                }
            }else{
                /*$sql = "UPDATE tbl_order_details 
                                SET status = 'Cancel', lastUpdatedDate='$toDay', lastUpdatedBy='$loginID' 
                                WHERE id='".$detailsIdArray[$i]."'";
                    $conn->query($sql);*/
            }
            $totalDiscountOfferValue += $discountOfferValue; 
        }
        /*$sql = "UPDATE tbl_orders 
                SET status='Checked', totalAmount = $totalAmountOrder, productDiscount=$totalDiscountOfferValue, grandTotal = $totalAmountOrder-$totalDiscountOfferValue, checked_total=$orderCheckedTotal, checked_discount=$orderCheckedTotalDiscount, total_after_discount=$orderCheckedGrandTotal, lastUpdatedDate='$toDay', lastUpdatedBy='$loginID' 
                WHERE id='$orderId'";
        $conn->query($sql);*/
        echo json_encode($output);
    } 
}
else if(isset($_GET[salesType])){
    $getType = $_GET[salesType];
    $orderId = $_GET['id'];
    if($getType == "OrderSales"){
        $sql = "SELECT tbl_order_details.quantity, tbl_order_details.salesAmount,tbl_order_details.discount, tbl_order_details.totalAmount, tbl_order_details.grandTotal, tbl_products.productName, 
                        tbl_products.productCode, tbl_products.modelNo, tbl_units.unitName, dbt.available, tbl_order_details.remarks, tbl_order_details.id, tbl_order_details.tbl_products_id, 
                        tbl_order_details.checked_quantity, tbl_order_details.checked_amount, tbl_order_details.status, tbl_order_details.offer_quantity, tbl_order_details.offer_discount_amount, 
                        tbl_order_details.total_after_discount, tbl_order_details.tbl_wareHouseId, tbl_orders.status as order_status
                FROM tbl_order_details
                LEFT OUTER JOIN tbl_orders ON tbl_order_details.tbl_orders_id = tbl_orders.id
                LEFT OUTER JOIN tbl_products ON tbl_products.id = tbl_order_details.tbl_products_id
                LEFT OUTER JOIN tbl_units ON tbl_products.units = tbl_units.id
                LEFT OUTER JOIN (SELECT SUM(currentStock) AS available, tbl_productsId
                FROM tbl_currentStock
                WHERE tbl_productsId IN (SELECT details1.tbl_products_id FROM tbl_order_details as details1 WHERE details1.tbl_orders_id='$orderId' AND details1.deleted='No')
                GROUP BY tbl_currentStock.tbl_productsId) AS dbt ON dbt.tbl_productsId = tbl_order_details.tbl_products_id
                WHERE tbl_order_details.deleted='No' AND  tbl_order_details.status<>'Cancel' AND tbl_order_details.tbl_orders_id='$orderId'";
        $result = $conn->query($sql);
        $i=1;
        $output = array('data' => array());
        //$output = '';
        while ($row = $result->fetch_array()) {
            $orderDetailsId = $row['id'];
            $button = '	<div class="btn-group">
					<button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
					<i class="fa fa-gear tiny-icon"></i>  <span class="caret"></span></button>
					<ul class="dropdown-menu dropdown-menu-right" style="border: 1px solid gray;" role="menu">';
    		if(strtolower($_SESSION['userType']) == "admin" || strtolower($_SESSION['userType']) == 'super admin'){					
    		    $button .=  '<li><a href="#" onclick="calcelOrder(' . $row['id'] . ')"><i class="fa fa-edit tiny-icon"></i>Delete</a></li>';
    		}
    		$button .=	'</ul>
    					</div>';
    					
            //Offer Start
            $productDiscountEntry = $row['discount'];
            $productQuantityEntry = $row['quantity'];
            $productPriceEntry = $row['salesAmount'];
            $productIdEntry = $row['tbl_products_id'];
            $sql_discountOffer = "SELECT id,offer_applicable,offer_for,unit_for,discount,discount_unit,discount_2,discount_unit_2, offer_name
                                    FROM tbl_discount_offer 
    								WHERE '$toDate' BETWEEN date_from AND date_to AND tbl_discount_offer.status = 'Active' AND deleted = 'No' AND tbl_products_id = '".$productIdEntry."' AND offer_applicable='Party' AND priority > 0
                                    ORDER BY offer_for DESC, priority DESC";
            $result_discountOffer = $conn->query($sql_discountOffer);
            $discount_pc = 0;
            $total_discount_pc = 0;
            $discount_amount = 0;
            $rest_pc = 0;
            $rest_amount = 0;
            $test = 0;
            $stock_check = 'On';
            $discountOfferValue = 0;
            //$discountOfferValueOutput = "";
            $total_quantity = $productQuantityEntry;
            if($result_discountOffer->num_rows > 0 ){
                while($row_discountOffer = $result_discountOffer->fetch_assoc()){
                    $discountOfferid = $row_discountOffer['id'];
                    if($row_discountOffer['unit_for'] == 'PC'){
    				    if($row_discountOffer['discount_unit'] == 'PC'){
    						$discount_pc = floor($total_quantity / $row_discountOffer['offer_for']);
    						if($discount_pc >= 1){
        						if($row_discountOffer['discount_unit_2'] == 'TK'){
        						    if($row_discountOffer['discount_2'] != 0){
        							    $discountOfferValue += ($row_discountOffer['discount_2']*$discount_pc);
        							    //$discountOfferValueOutput .= $discountOfferValue.' 1-1 ';
        						    }else{
        						        $discountOfferValue += $productDiscountEntry;
        						        //$discountOfferValueOutput .= $discountOfferValue.' 1-2 ';
        						    }
        						}else{
        						    if(substr($row['remarks'], -1) == '%'){
                				        $discountOfferValue += ($discount_pc*$row_discountOffer['offer_for'])*($row['salesAmount']*(substr($row['remarks'],0,-1)/100));
                				    }else{
                				        $discountOfferValue += ($discount_pc*$row_discountOffer['offer_for'])*$row['remarks'];
                				    }
        							//$discountOfferValue +=  $row['sale_amount']*($row_discountOffer['offer_for'] * floor($total_quantity / $row_discountOffer['offer_for']));
        							//$discountOfferValueOutput .= $discountOfferValue.' 1-3 ';
        						}
							
							    $discount_pc = $discount_pc * $row_discountOffer['discount'];
							    $total_discount_pc += $discount_pc;
							}
							$total_quantity -=$row_discountOffer['offer_for'] * floor($total_quantity / $row_discountOffer['offer_for']);
						}
    					else if($row_discountOffer['discount_unit'] == '%'){
    						if($total_quantity >= $row_discountOffer['offer_for']){
    							$discount_quantity = $row_discountOffer['offer_for'] * floor($total_quantity / $row_discountOffer['offer_for']);
    							$productTotal = $discount_quantity * $productPriceEntry;
    							$discountOfferValue += $productTotal * ($row_discountOffer['discount']/100);
    							//$discountOfferValueOutput .= $discountOfferValue.' 1-4 ';
    							$total_quantity -=$row_discountOffer['offer_for'] * floor($total_quantity / $row_discountOffer['offer_for']);
    						}
    					}
						else if($row_discountOffer['discount_unit'] == 'TK'){
							if($total_quantity >= $row_discountOffer['offer_for']){
								$discount_quantity = $row_discountOffer['offer_for'] * floor($total_quantity / $row_discountOffer['offer_for']);
								$discountOfferValue += $row_discountOffer['discount']* floor($total_quantity / $row_discountOffer['offer_for']);
								//$discountOfferValueOutput .= $discountOfferValue.' 1-5 ';
								//$productTotal = ($discount_quantity * $productPriceEntry) - $productDiscount;
								$total_quantity -=$row_discountOffer['offer_for'] * floor($total_quantity / $row_discountOffer['offer_for']);
                            }
						}
                    }
                }
            } 
           
            if($total_quantity > 0){
                if(substr($row['remarks'], -1) == '%'){
			        $discountOfferValue += $total_quantity*($row['salesAmount']*(substr($row['remarks'],0,-1)/100));
			        //$discountOfferValueOutput .= 'Calculation = '.$discountOfferValue.' + '.$total_quantity.'*('.$row['salesAmount'].'*('.$row['remarks'].')) ';
			    }else{
			        $discountOfferValue += $total_quantity*$row['remarks'];
			    }
                //$discountOfferValue += $total_quantity*($row['salesAmount'] * $row['remarks']);
                //$discountOfferValueOutput .= $discountOfferValue.' 1-6 ';
            }
            $grandTotal = ($row['quantity']*$row['salesAmount'])-$discountOfferValue;
            //Offer End
            if($row['status'] == 'Checked'){
                $textStatus = 'Readonly';
                if($row['order_status'] == 'Pending'){
                    $salesAmount = $row['salesAmount'];
                    $checkedmount = $row['checked_amount'];
                }else{
                    $salesAmount = $row['checked_amount'];
                    $checkedmount = $salesAmount;
                }
                $checkedQty = $row['checked_quantity'];
            }else{
                $textStatus = '';
                $salesAmount = $row['salesAmount'];
                $checkedmount = $row['checked_amount'];
                if($row['checked_quantity'] == ''){
                    $checkedQty = 0;
                }else{
                    $checkedQty = $row['checked_quantity'];
                }
            }
           
            $sqlWarehouse = "SELECT DISTINCT tbl_warehouse.id, tbl_warehouse.wareHouseName, tbl_currentStock.currentStock 
                                FROM `tbl_currentStock`
                                INNER JOIN tbl_warehouse ON tbl_currentStock.tbl_wareHouseId = tbl_warehouse.id
                                WHERE tbl_currentStock.tbl_productsId='$productIdEntry' AND tbl_currentStock.currentStock >= ".$productQuantityEntry." AND tbl_currentStock.deleted='No'";
           
             /*$sqlWarehouse = "SELECT DISTINCT tbl_warehouse.id, tbl_warehouse.wareHouseName, tbl_currentStock.currentStock 
                                FROM `tbl_currentStock`
                                INNER JOIN tbl_warehouse ON tbl_currentStock.tbl_wareHouseId = tbl_warehouse.id
                                WHERE tbl_currentStock.tbl_productsId='$productIdEntry' AND tbl_currentStock.deleted='No'"; */
            $resultWarehouse = $conn->query($sqlWarehouse);
            $warehouseddl = "<select class='form-control' id='warehouseId_".$orderDetailsId."'>";
            $selected = "";
            while ($rowWarehouse = $resultWarehouse->fetch_array()) {
                if($row['tbl_wareHouseId'] == $rowWarehouse['id']){
                    $selected = "Selected";
                }
                $warehouseddl .= "<option value='".$rowWarehouse['id']."' ".$selected.">".$rowWarehouse['wareHouseName']." - ".$rowWarehouse['currentStock']."</option>";
                $selected = "";
            }
            $warehouseddl .= "</select>";
            //$discountOfferValue = $row['offer_discount_amount'];
            //$grandTotal =  $row['total_after_discount'];
            $output['data'][] = array(
                $i++."<input type='hidden' id='detailsId_".$orderDetailsId."' name='detailsId' value='".$orderDetailsId."' />",
                $row['productName'].' - '.$row['productCode'].' ('.$row['modelNo'].')',
                $warehouseddl,
	        $row['available'],
		'Qty: '.$productQuantityEntry . ' '.$row['unitName']."<br>Dis.: <span id='offer_quantity_".$orderDetailsId."' name='offerQty'>".$total_discount_pc."</span>". ' '.$row['unitName']
		.'<br>Total: <b>'.($productQuantityEntry+$total_discount_pc). ' '.$row['unitName'].'</b>',
		"<input class='form-control' style='width:70px;' type='text' id='availableQTY_".$orderDetailsId."' name='availableQTY' value='".$checkedQty."' $textStatus />".$row['unitName'],
		"<div id='sales_amount_".$orderDetailsId."' name='salesAmount'>".$salesAmount."</div>",
		"<input class='form-control' style='width:85px;' type='text' id='updatedAmount_".$orderDetailsId."' name='updatedAmount' value='".$checkedmount."' $textStatus />",
		//"<div id='product_discount_amount_".$orderDetailsId."' name='offerDiscountAmount'>".$row['remarks']."</div>",
		"<div id='offer_discount_amount_".$orderDetailsId."' name='offerDiscountAmount'>".number_format($discountOfferValue,2)."</div>".' Tk',
		"<div id='total_after_discount_".$orderDetailsId."' name='totalAfterDiscount'>".$grandTotal."</div>"
	        );
        }// /while 
        echo json_encode($output);
        //echo $output;
    }
    else if($getType == "ConfirmSales"){
        $sql = "SELECT tbl_order_details.quantity, tbl_order_details.salesAmount, tbl_order_details.discount, tbl_order_details.totalAmount, tbl_order_details.grandTotal, tbl_products.productName, tbl_products.productCode, tbl_products.modelNo, tbl_units.unitName, dbt.available, tbl_order_details.remarks, tbl_order_details.id, tbl_order_details.tbl_products_id, tbl_order_details.checked_quantity, tbl_order_details.checked_amount, tbl_order_details.status, tbl_order_details.offer_quantity, tbl_order_details.offer_discount_amount, tbl_order_details.total_after_discount
                FROM tbl_order_details
                LEFT OUTER JOIN tbl_products ON tbl_products.id = tbl_order_details.tbl_products_id
                LEFT OUTER JOIN tbl_units ON tbl_products.units = tbl_units.id
                LEFT OUTER JOIN (SELECT SUM(currentStock) AS available, tbl_productsId
                FROM tbl_currentStock
                WHERE tbl_productsId IN (SELECT details1.tbl_products_id FROM tbl_order_details as details1 WHERE details1.tbl_orders_id='$orderId' AND details1.deleted='No')
                GROUP BY tbl_currentStock.tbl_productsId) AS dbt ON dbt.tbl_productsId = tbl_order_details.tbl_products_id
                WHERE tbl_order_details.deleted='No' AND  tbl_order_details.status<>'Cancel'  AND tbl_order_details.tbl_orders_id='$orderId'";
        $result = $conn->query($sql);
        $i=1;
        $output = array('data' => array());
        //$output = '';
        while ($row = $result->fetch_array()) {
            $orderDetailsId = $row['id'];
            $button = '	<div class="btn-group">
					<button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
					<i class="fa fa-gear tiny-icon"></i>  <span class="caret"></span></button>
					<ul class="dropdown-menu dropdown-menu-right" style="border: 1px solid gray;" role="menu">';
    		if(strtolower($_SESSION['userType']) == "admin" || strtolower($_SESSION['userType']) == 'super admin'){					
    		    $button .=  '<li><a href="#" onclick="calcelOrder(' . $row['id'] . ')"><i class="fa fa-edit tiny-icon"></i>Delete</a></li>';
    		}
    		$button .=	'</ul>
    					</div>';
    					
            //Offer Start
            $productDiscountEntry = $row['discount'];
            $productQuantityEntry = $row['quantity'];
            $productPriceEntry = $row['salesAmount'];
            $productIdEntry = $row['tbl_products_id'];
            /*$sql_discountOffer = "SELECT id,offer_applicable,offer_for,unit_for,discount,discount_unit,discount_2,discount_unit_2, offer_name
                                    FROM tbl_discount_offer 
    								WHERE '$toDate' BETWEEN date_from AND date_to AND deleted = 'No' AND tbl_products_id = '".$productIdEntry."' AND offer_applicable='Party' AND priority > 0
                                    ORDER BY priority DESC, offer_for DESC";
            $result_discountOffer = $conn->query($sql_discountOffer);
            $discount_pc = 0;
            $total_discount_pc = 0;
            $discount_amount = 0;
            $rest_pc = 0;
            $rest_amount = 0;
            $test = 0;
            $stock_check = 'On';
            $discountOfferValue = 0;
            //$discountOfferValueOutput = "";
            $total_quantity = $productQuantityEntry;
            if($result_discountOffer->num_rows > 0 ){
                while($row_discountOffer = $result_discountOffer->fetch_assoc()){
                    $discountOfferid = $row_discountOffer['id'];
                    if($row_discountOffer['unit_for'] == 'PC'){
    				    if($row_discountOffer['discount_unit'] == 'PC'){
    						$discount_pc = floor($total_quantity / $row_discountOffer['offer_for']);
    						if($discount_pc >= 1){
        						if($row_discountOffer['discount_unit_2'] == 'TK'){
        						    if($row_discountOffer['discount_2'] != 0){
        							    $discountOfferValue += ($row_discountOffer['discount_2']*$discount_pc);
        							    //$discountOfferValueOutput .= $discountOfferValue.' 1-1 ';
        						    }else{
        						        $discountOfferValue += $productDiscountEntry;
        						        //$discountOfferValueOutput .= $discountOfferValue.' 1-2 ';
        						    }
        						}else{
        						    if(substr($row['remarks'], -1) == '%'){
                				        $discountOfferValue += ($discount_pc*$row_discountOffer['offer_for'])*($row['salesAmount']*(substr($row['remarks'],0,-1)/100));
                				    }else{
                				        $discountOfferValue += ($discount_pc*$row_discountOffer['offer_for'])*$row['remarks'];
                				    }
        							//$discountOfferValue +=  $row['sale_amount']*($row_discountOffer['offer_for'] * floor($total_quantity / $row_discountOffer['offer_for']));
        							//$discountOfferValueOutput .= $discountOfferValue.' 1-3 ';
        						}
							
							    $discount_pc = $discount_pc * $row_discountOffer['discount'];
							}
							$total_quantity -=$row_discountOffer['offer_for'] * floor($total_quantity / $row_discountOffer['offer_for']);
						}
    					else if($row_discountOffer['discount_unit'] == '%'){
    						if($total_quantity >= $row_discountOffer['offer_for']){
    							$discount_quantity = $row_discountOffer['offer_for'] * floor($total_quantity / $row_discountOffer['offer_for']);
    							$productTotal = $discount_quantity * $productPriceEntry;
    							$discountOfferValue += $productTotal * ($row_discountOffer['discount']/100);
    							//$discountOfferValueOutput .= $discountOfferValue.' 1-4 ';
    							$total_quantity -=$row_discountOffer['offer_for'] * floor($total_quantity / $row_discountOffer['offer_for']);
    						}
    					}
						else if($row_discountOffer['discount_unit'] == 'TK'){
							if($total_quantity >= $row_discountOffer['offer_for']){
								$discount_quantity = $row_discountOffer['offer_for'] * floor($total_quantity / $row_discountOffer['offer_for']);
								$discountOfferValue += $row_discountOffer['discount']* floor($total_quantity / $row_discountOffer['offer_for']);
								//$discountOfferValueOutput .= $discountOfferValue.' 1-5 ';
								//$productTotal = ($discount_quantity * $productPriceEntry) - $productDiscount;
								$total_quantity -=$row_discountOffer['offer_for'] * floor($total_quantity / $row_discountOffer['offer_for']);
                            }
						}
                    }
                }
            } 
           
            if($total_quantity > 0){
                if(substr($row['remarks'], -1) == '%'){
			        $discountOfferValue += $total_quantity*($row['salesAmount']*(substr($row['remarks'],0,-1)/100));
			        //$discountOfferValueOutput .= 'Calculation = '.$discountOfferValue.' + '.$total_quantity.'*('.$row['salesAmount'].'*('.$row['remarks'].')) ';
			    }else{
			        $discountOfferValue += $total_quantity*$row['remarks'];
			    }
                //$discountOfferValue += $total_quantity*($row['salesAmount'] * $row['remarks']);
                //$discountOfferValueOutput .= $discountOfferValue.' 1-6 ';
            }
            $grandTotal = ($row['quantity']*$row['salesAmount'])-$discountOfferValue;*/
            //Offer End
            if($row['status'] == 'Checked'){
                $textStatus = 'Readonly';
                $salesAmount = $row['checked_amount'];
                $checkedQty = $row['checked_quantity'];
            }else{
                $textStatus = '';
                $salesAmount = $row['salesAmount'];
                if($row['checked_quantity'] == ''){
                    $checkedQty = 0;
                }else{
                    $checkedQty = $row['checked_quantity'];
                }
            }
            $discountOfferValue = $row['offer_discount_amount'];
            $grandTotal =  $row['total_after_discount'];
            $output['data'][] = array(
                    $i++."<input type='hidden' id='detailsId_".$orderDetailsId."' name='detailsId' value='".$row['id']."' />",
                    $row['productName'].' - '.$row['productCode'].' ('.$row['modelNo'].')',
                    $row['available'],
                    ($row['checked_quantity']-$row['offer_quantity']) . ' '.$row['unitName'],
                    "<div id='offer_quantity_".$orderDetailsId."' name='offerQty'>".$row['offer_quantity']."</div>". ' '.$row['unitName'],
                    "<input class='form-control' style='width:50px;' type='text' id='checkChangeQTY_".$orderDetailsId."' name='checkChangeQTY' value='".$checkedQty."'/><input class='form-control' style='width:50px;' type='hidden' id='checkQTY_".$orderDetailsId."' name='checkQTY' value='".$checkedQty."' Readonly/>",
                    //"<input class='form-control' style='width:50px;' type='text' id='availableQTY_".$orderDetailsId."' name='availableQTY' value='".$checkedQty."' $textStatus />".$row['unitName'],
                    //$row['salesAmount'],
                    $salesAmount,
                    "<div id='offer_discount_amount_".$orderDetailsId."' name='offerDiscountAmount'>".number_format($discountOfferValue,2)."</div>".' Tk',
                    "<div id='total_after_discount_".$orderDetailsId."' name='totalAfterDiscount'>".$grandTotal."</div>"
                );
        }// /while 
        echo json_encode($output);
        //echo $output;
    }
}
?>
