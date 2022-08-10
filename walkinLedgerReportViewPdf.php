<?php include 'includes/session.php'; ?>
<?php
if(isset($_POST['startDate']))
//if(isset($_POST['EmpName']))
{
	$partyId=$_POST['cName'];
	$sDate=$_POST['startDate'];
	$eDate=$_POST['endtDate'];
	//$EmpName=$_POST['EmpName'];
?>
<style>
#customers {border-collapse: collapse;table-layout:auto;width: 100%;}
#customers td, #customers th {border: 1px solid #ddd;padding: 8px;}
#customers tr:nth-child(even){background-color: #f2f2f2;}
#customers tr:hover {background-color: #ddd;}
</style>
	<br><br>
	<div class="table-responsive"> 
        <table class="table table-bordered" id="customers">
        <thead>
		<tr style="background: #3f3e93;color: white;">
			<th class="hidden"></th>
			<th>Date</th>
			<th>Particulars</th>
			<th>Inv No</th>
			<th>Vch Type</th>
			<th>Debit</th>
			<th>Credit</th>
			<th>Balance</th>
		</tr>
        </thead>
        <tbody>
          <?php
			$sql = "SELECT tbl_paymentVoucher.amount, voucherType, tbl_paymentVoucher.type, tbl_paymentVoucher.deleted, tbl_paymentVoucher.remarks, ifnull(ifnull(ifnull(tbl_sales.salesOrderNo, tbl_purchase.purchaseOrderNo), tbl_purchase_return.purchaseReturnOrderNo), tbl_sales_return.salesReturnOrderNo) as voucherNo, 
			        (CASE  WHEN tbl_paymentVoucher.paymentMethod = 'CHEQUE' THEN tbl_paymentVoucher.chequeIssueDate 
                           ELSE tbl_paymentVoucher.paymentDate
                           END) AS paymentDate,tbl_paymentVoucher.tbl_sales_id, tbl_paymentVoucher.voucherNo as paymentVoucherNo
                    FROM tbl_paymentVoucher 
                    LEFT OUTER JOIN tbl_purchase ON tbl_paymentVoucher.tbl_purchaseId = tbl_purchase.id AND tbl_purchase.deleted = 'No'
                    LEFT OUTER JOIN tbl_sales ON tbl_paymentVoucher.tbl_sales_id = tbl_sales.id AND tbl_sales.deleted = 'No'
                    LEFT OUTER JOIN tbl_purchase_return ON tbl_paymentVoucher.tbl_purchase_return_id = tbl_purchase_return.id AND tbl_purchase_return.deleted = 'No'
                    LEFT OUTER JOIN tbl_sales_return ON tbl_paymentVoucher.tbl_sales_return_id = tbl_sales_return.id AND tbl_sales_return.deleted = 'No'
                    WHERE tbl_partyId = '".$partyId."' AND tbl_paymentVoucher.customerType='WalkinCustomer' AND tbl_paymentVoucher.deleted='No' AND (CASE  WHEN tbl_paymentVoucher.paymentMethod = 'CHEQUE' THEN tbl_paymentVoucher.chequeIssueDate 
                                                               ELSE tbl_paymentVoucher.paymentDate
                                                               END) BETWEEN '".$sDate."' AND '".$eDate."'
                    ORDER BY CASE  WHEN tbl_paymentVoucher.paymentMethod = 'CHEQUE' THEN tbl_paymentVoucher.chequeIssueDate 
                                                               ELSE tbl_paymentVoucher.paymentDate
                                                               END, tbl_paymentVoucher.entryDate";
            /*$sql = "SELECT amount, voucherType, type, deleted, remarks, voucherNo, (CASE  WHEN paymentMethod = 'CHEQUE' THEN chequeIssueDate 
                                                                                   ELSE paymentDate
                                                                                   END) AS paymentDate
                                        FROM tbl_paymentVoucher 
                                        WHERE tbl_partyId = '".$partyId."' AND customerType = 'WalkinCustomer' AND (CASE  WHEN paymentMethod = 'CHEQUE' THEN chequeIssueDate 
                                                                                   ELSE paymentDate
                                                                                   END) BETWEEN '".$sDate."' AND '".$eDate."'
                                        ORDER BY CASE  WHEN paymentMethod = 'CHEQUE' THEN chequeIssueDate 
                                                                                   ELSE paymentDate
                                                                                   END, entryDate";*/
             $query = $conn->query($sql);
			$idNo=1;
            while($row12 = $query->fetch_assoc()){
				//$fid=$row['firm_id'];
				//$pcnid=$row['pcn_no'];
				//$pcnid=$row['we_product_quantity']-$row['re_weQuantity'];
				//$pcnid12=$row['bu_product_quantity']-$row['re_buQuantity'];
				$image_name="<img src='images/products/thumb/".$row['productImage']."' width='30%'>";
				//$did12+=$did;
				
				$type=$row12['type'];
			
    		    if($type=='paymentReceived'){
    		        $cr=$row12['amount'];
    		        $dr = '';
    		        $balance = $balance + $cr;
    		    }
    		    else if($type=='payable'){
    		        $cr=$row12['amount'];
    		        $dr = '';
    		        $balance = $balance + $cr;
    		    }
    		    else if($type=='adjustment'){
    		        $cr=$row12['amount'];
    		        $dr = '';
    		        $balance = $balance + $cr;
    		    }
    		    else if($type=='partyPayable'){
    		        $dr=$row12['amount'];
    		        $cr = '';
    		        $balance = $balance - $dr;
    		    }
    		    else if($type=='payment'){
    		        $dr=$row12['amount'];
    		        $cr = '';
    		        $balance = $balance - $dr;
    		    }
    		    else if($type=='paymentAdjustment'){
    		        $dr=$row12['amount'];
    		        $cr = '';
    		        $balance = $balance - $dr;
    		    }else if($type=='discount'){
    		        $cr=$row12['amount'];
    		        $dr= '';
    		        $balance = $balance + $cr;
    		    }
                echo "<tr>
                    	<td class='hidden'></td>
                    	<td>".$row12['paymentDate']."</td>
                    	<td>".$row12['voucherType']."</td>
                        <td><a href='salesViewDetails.php?id=".$row12['tbl_sales_id']."' target='_blank'>".$row12['voucherNo']."</a></td>
                    	<td>".$row12['type']."</td>
                    	<td>".$dr."</td>
                    	<td>".$cr."</td>
                    	<td>".$balance."</td>
                    </tr>";
            }
			echo "<a href='walkinSalesLedgerReportViewPdfPrint.php?spId=".$partyId."&sDate=".$sDate."&eDate=".$eDate."' target='_blank' title='Issue Details' data-toggle='tooltip' class='btn btn-primary btn-sm btn-flat' style='margin-left: 1%; background: white;color: blue;margin-bottom: 1%;'><i class='fa fa-print'> Walkin Sales ledger Reports Print </i></a>";
		  ?>
        </tbody>
    </table></div>
<?php } ?>