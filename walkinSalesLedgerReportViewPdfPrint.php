<?php ob_start(); //$conPrefix = '';
include 'includes/session.php';
$partyId = $_GET['spId'];
$sDate = $_GET['sDate'];
$eDate = $_GET['eDate'];
$sql = "SELECT * FROM `shopSettings`"; 
$query = $conn->query($sql);
while($row = $query->fetch_assoc()){
	$address=$row['address'];
	$phone=$row['phone'];
	$mobile=$row['mobile'];
	$email=$row['email'];
	$website=$row['website'];
	$image=$row['image'];
	$imageWatermark=$row['imageWatermark'];
	$addType=$row['address_type'];
}
	require_once('tcpdf/tcpdf.php');
		
		// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

    //Page header
    public function Header() {
        $image_file = "images/companylogo/watermarkJafree.png";
         $this->Image($image_file,10, 10,189, '', 'JPG', '', 'T', false, 100, '', false, false, 0, false, false, false);
    }

    // Page footer
    public function Footer() {
        
        // Position at 15 mm from bottom
        $this->SetY(-12);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Powered By Alitech. Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        
    }
}
		
    //$pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false); 
    $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->SetCreator(PDF_CREATOR);  
    $pdf->SetTitle('Duronto Shop Management System');  
    $pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);  
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));  
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));  
    $pdf->SetDefaultMonospacedFont('helvetica');  
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);  
    $pdf->SetMargins(PDF_MARGIN_LEFT, '10', PDF_MARGIN_RIGHT);  
    $pdf->setPrintHeader(TRUE);  
    $pdf->setPrintFooter(TRUE);  
    $pdf->SetAutoPageBreak(TRUE, 10);  
    $pdf->SetFont('helvetica', '', 11);  
    $pdf->AddPage();
    $content = '<style>
				.supAddress {font-size: 8px;}
				.supAddressFont {font-size: 8px;}
				</style>'; 
	$sql11 = "SELECT fname FROM `tbl_users` WHERE id='".$_SESSION['user']."'";
			$query = $conn->query($sql11);
			while($row123 = $query->fetch_assoc()){
			$fname=$row123[fname];
			}
	$sql = "SELECT * FROM `tbl_walkin_customer` WHERE id='".$partyId."'";
		
		$query = $conn->query($sql);
		while($row = $query->fetch_assoc()){
			$partyName=$row['customerName'];
			$partyAddress=$row['customerAddress'];
			$partyPhone=$row['phoneNo'];
			$partyEmail=$row['contactEmail']; 
			
			
			  
		}
    $content .= '<style>
            p{color:black;font-size: 8px;text-align:center;}
			.cities {background-color: gray;color: white;text-align: center;padding: 30px;}
			.citiestd {background-color: yellow;color: black;text-align: center;}
			.citiestd12 {background-color: gray;color: white;text-align: center; font-size: 9px;}
			.citiestd13 {background-color: gray;color: white;text-align: center;font-size: 10px;padding: 30px;}
			.citiestd14 {text-align: right;font-size: 8px;}
			.citiestd15 {font-size: 8px;}
			.citiestd16 {text-align: right;font-size: 8px;}
			.citiestd17 {text-align: center;font-size: 8px;}
			.citiestd18 {text-align: left;font-size: 8px;}
			.citiestd11 {text-align: center;font-size: 8px;}
			span{font-size: 9px;}
			h2{font-size: 18px;text-align:center;}
		</style>';
		$image = 'images/companylogo/'.$image;    
        $pdf->Image($image, 85, 3,40, 15);
		$content .='<br>
		<p> '.$address.' Tel:'.$phone.' Mobile: '.$mobile.'<br>E-mail:'.$email.'</p>
		<div class="citiestd13"> Walkin Customer Ledger Information </div>
		<table border="1" cellspacing="0" cellpadding="3">
			<tr>
				<td width="75%" class="supAddress">Customer Name :<font color="gray" class="supAddressFont">'.$partyName.'</font><br>Customer Address :<font color="gray" class="supAddressFont">'.$partyAddress.'</font><br>Phone :<font color="gray" class="supAddressFont">'.$partyPhone.'</font><br>Email :<font color="gray" class="supAddressFont">'.$partyEmail.'</font></td>
				<td width="25%" style="border: 1px solid gray;font-size: 8px;" ><span class="citiestd11">Start Date: '.$sDate.'</span><br><span class="citiestd11">End Date: '.$eDate.'</span><br><span class="citiestd11">Print Date: ' . date("Y-m-d") .'</span><br><span class="citiestd11">Printed By: ' . $fname .'</span></td>
			</tr>
			<tr>
				<td width="100%">Del. Date :<font color="gray"></font> Adj. Date :<font color="gray"></font> Ret. Date :<font color="gray"></td>
			</tr>
		</table>
		<br><br>
		<table border="1" cellspacing="0" cellpadding="3">
		
			<tr>
				<th class="citiestd11" width="5%">SL#</th>
				<th class="citiestd11" width="10%">Date</th>
				<th class="citiestd11" width="15%">Particulars</th>
				<th class="citiestd11" width="10%">Inv. No</th>
				<th class="citiestd11" width="15%">Vch Type</th>
				<th class="citiestd11" width="10%">Vch No</th>
				<th class="citiestd11" width="12%">Debit</th>
				<th class="citiestd11" width="12%">Credit</th>
				<th class="citiestd11" width="11%">Balance</th>
			</tr>';
			
			$blance=0;
			
            $sql="SELECT Sum(CASE tbl_paymentVoucher.type 
                                       WHEN 'partyPayable' THEN tbl_paymentVoucher.amount
                                       WHEN 'paymentReceived' THEN -tbl_paymentVoucher.amount 
                                       WHEN 'adjustment' THEN -tbl_paymentVoucher.amount
                                       WHEN 'payable' THEN -tbl_paymentVoucher.amount 
                                       WHEN 'payment' THEN tbl_paymentVoucher.amount
                                       WHEN 'paymentAdjustment' THEN tbl_paymentVoucher.amount 
                                       WHEN 'discount' THEN -tbl_paymentVoucher.amount
                                       END) AS total, 'Opening Balance' AS type, deleted, 'Before' AS paymentDate
                FROM tbl_paymentVoucher 
                WHERE tbl_partyId = '$partyId' AND customerType = 'WalkinCustomer' AND deleted = 'No' AND  paymentDate < '$sDate'";    
                
                
			$query = $conn->query($sql);
		    while($row = $query->fetch_assoc()){
			$openingBalance=$row['total']; 
			}
			/*Eikhane sql theke loop diye opening balance ta ekta variable er modhe dhore niba*/
			//$openingBalance = "0";
			
			if($openingBalance >= 0){
			    $dr = $openingBalance;
			    $balance = $balance - $dr; 
			    $cr = '';
			}else{
			    $dr = '';
			    $cr = $openingBalance*(-1);
			    $balance = $balance + $cr;
			}
			
			
			$content .='<tr>
                        <td class="citiestd11">1</td>			
                        <th colspan="4" class="citiestd11">Opening Balance Before '.$sDate.'</th>
                        <td class="citiestd11">'.$dr.'</td>
                        <td class="citiestd11">'.$cr.'</td>
                    </tr>';
		
			$sql = "SELECT tbl_paymentVoucher.amount, voucherType, tbl_paymentVoucher.type, tbl_paymentVoucher.deleted, tbl_paymentVoucher.remarks, ifnull(ifnull(ifnull(tbl_sales.salesOrderNo, tbl_purchase.purchaseOrderNo), tbl_purchase_return.purchaseReturnOrderNo), tbl_sales_return.salesReturnOrderNo) as voucherNo, 
			        (CASE  WHEN tbl_paymentVoucher.paymentMethod = 'CHEQUE' THEN tbl_paymentVoucher.chequeIssueDate 
                           ELSE tbl_paymentVoucher.paymentDate
                           END) AS paymentDate, tbl_paymentVoucher.voucherNo as paymentVoucherNo
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
            /*$sql="SELECT amount, voucherType, type, deleted, remarks, voucherNo, (CASE  WHEN paymentMethod = 'CHEQUE' THEN chequeIssueDate 
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
		$weQuantity=0;
		$i=1;
		$weQuantitybu=0;
		$weQuantitybu12=0;
		$balance = 0;
		while($row12 = $query->fetch_assoc()){
			$i++;
			$dr='';
			$cr='';
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
		        $drTotal+=$dr;
		        $crTotal+=$cr;
		        //$closingBlance=$drTotal-$crTotal;
		        //$trialBalance=$closingBlance+$crTotal;
                $drClosingBalance = 0;
		        $crClosingBalance = 0;
		        $drGrandTotal = 0;
		        $crGrandTotal = 0;
		        if($balance >= 0){
		           $drClosingBalance = $balance;
		           $trialBalance=$drTotal+$balance;
		           $drGrandTotal = $drTotal+$drClosingBalance;
		           $crGrandTotal = $crTotal+$crClosingBalance;
		        }else{
		            $crClosingBalance = $balance*(-1);
		            $trialBalance=$crTotal+$balance;
		            $drGrandTotal = $drTotal+$drClosingBalance;
		            $crGrandTotal = $crTotal+$crClosingBalance;
		        }
		        
			$content .= '<tr>
						<td class="citiestd11">'.$i.'</td>
						<td class="citiestd11">'.$row12['paymentDate'].'</td>
    					<td class="citiestd11">'.$row12['voucherType'].'</td>
    					<td class="citiestd11">'.$row12['voucherNo'].'</td>
    					<td class="citiestd11">'.$row12['type'].'</td>
    					<td class="citiestd11">'.$row12['paymentVoucherNo'].'</td>
    					<td class="citiestd11">'.$dr.'</td>
    					<td class="citiestd11">'.$cr.'</td>
    					<td class="citiestd11">'.$balance.'</td>
					</tr>
					
					';
					
			}
		$content .= '<tr><td></td><th colspan="5" class="citiestd11"> Total Blance </th><td class="citiestd11">'.number_format($drTotal,2).'</td><td class="citiestd11">'.number_format($crTotal,2).'</td></tr>
            			<tr><td></td><th colspan="5" class="citiestd11"><b> Closing Balance </b></th><td class="citiestd11"><b>'.number_format($drClosingBalance,2).'</b></td><td class="citiestd11">'.number_format($crClosingBalance,2).'</td><td class="citiestd11"></td></tr>
            			<tr><td></td><th colspan="5" class="citiestd11">  </th><td class="citiestd11">'.number_format($drGrandTotal,2).'</td><td class="citiestd11">'.number_format($crGrandTotal,2).'</td></tr>
            		    </table><br><br><br><br>';
		
			$content .='
			
				
				<table>
					
					<tr>
						<th class="citiestd15">--------------------------------------</th><th class="citiestd17">----------------</th><th class="citiestd16">----------------------------</th>
						
					</tr>
					<tr>
						<td class="citiestd15" > Walkin Coustomer Signature </td><td class="citiestd17"> Checked By </td><td class="citiestd16"> Authorized Signature </td>
						
					</tr>
					
				</table>
			';
		
	$pdf->writeHTML($content);  
    ob_end_clean();
	$pdf->Output('schedule.pdf', 'I');
    ob_end_flush();
?>