<?php ob_start(); //$conPrefix = '';

include 'includes/session.php';
/*$original_mem = ini_get('memory_limit');
ini_set('memory_limit','640M');
ini_set('max_execution_time', 300);*/

//$spId = $_GET['spId'];

set_time_limit(0);
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
    $pdf->SetTitle('Duronto Reporting System');  
    $pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);  
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));  
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));  
    $pdf->SetDefaultMonospacedFont('helvetica');  
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);  
    $pdf->SetMargins(PDF_MARGIN_LEFT, '10', PDF_MARGIN_RIGHT);  
    /*$pdf->setPrintHeader(TRUE);  
    $pdf->setPrintFooter(TRUE);  
    $pdf->SetAutoPageBreak(TRUE, 10);  
    $pdf->SetFont('helvetica', '', 11);  */
    $pdf->setPrintHeader(FALSE);  
    $pdf->setPrintFooter(FALSE);  
    $pdf->SetAutoPageBreak(FALSE);  
    $pdf->SetFontSubsetting(false);
    $pdf->AddPage();
    // water marks 
   // $pdf->Image('images/companylogo/watermarkJafree.png', 10, 10,189);	
    $content = '<style>
				.supAddress {font-size: 8px;}
				.supAddressFont {font-size: 8px;}
				</style>'; 
	
		$sql = "SELECT fname FROM `tbl_users` WHERE id='".$_SESSION['user']."'";
			$query = $conn->query($sql);
			while($row123 = $query->fetch_assoc()){
			$fname=$row123[fname];
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
			.citiestd11 {text-align: center;font-size: 7px;}
			.citiestd20 {font-size: 7px;}
			span{font-size: 9px;}
			h2{font-size: 18px;text-align:center;}
		</style>';
		$pdf->Image('images/companylogo/Jafree.jpg', 85, 3,40, 15);
		$content .='<br>
		<p> 212, Jubilee Road, Chittagong-4000, Bangladesh.Tel:031-617505,615062 Mobile: 01973105100,01711-325119<br>E-mail:info@jafreetraders.com</p>
		<div class="citiestd13">Party Payable Information</div>
		<span class="citiestd11"></span>
		    <span style="border: 1px solid gray;font-size: 8px;" >Print Date :<font color="gray">' . date("Y-m-d") .'</font> Printed By :<font color="gray">'.$fname.'</font></span>
			<br><br>
		<table border="1" cellspacing="0" cellpadding="3">
		
			<tr>
				<th class="citiestd11" width="6%">SL#</th>
				<th class="citiestd11" width="25%">Party Name</th>
				<th class="citiestd11" width="20%">Contact Info</th>
				<th class="citiestd11" width="25%">Address</th>
				<th class="citiestd11" width="12%">Customer Type</th>
				<th class="citiestd11" width="12%">Amount</th>
			</tr>';
			$sql = "SELECT total as partyReceivable, dbt.customerType, tbl_partyId, tbl_walkin_customer.customerName, tbl_walkin_customer.customerAddress, tbl_walkin_customer.phoneNo
                FROM(SELECT IFNULL(Sum(CASE tbl_paymentVoucher.type
                					   WHEN 'partyPayable' THEN tbl_paymentVoucher.amount
                					   WHEN 'paymentReceived' THEN -tbl_paymentVoucher.amount
                					   WHEN 'adjustment' THEN  -tbl_paymentVoucher.amount
                					   WHEN 'payable' THEN -tbl_paymentVoucher.amount
                					   WHEN 'payment' THEN tbl_paymentVoucher.amount
                					   WHEN 'paymentAdjustment' THEN tbl_paymentVoucher.amount
                					   END),0) AS total, tbl_partyId, customerType
                	 FROM tbl_paymentVoucher
                	 WHERE  deleted = 'No' and customerType = 'WalkinCustomer'
                	 GROUP BY tbl_partyId) AS dbt 
                	 LEFT OUTER JOIN tbl_walkin_customer ON dbt.tbl_partyId = tbl_walkin_customer.id
                				WHERE dbt.total > 0";
		
		$query = $conn->query($sql);
		$i=0;
		while($row12 = $query->fetch_assoc()){
			$i++;
			$totalAmount+=$row12['partyReceivable'];
			$content .= '<tr>
						<td class="citiestd11">'.$i.'</td>
						<td class="citiestd20">'.$row12['customerName'].'</td>
						<td class="citiestd20">'.$row12['phoneNo'].'</td>
						<td class="citiestd20">'.$row12['customerAddress'].'</td>
						<td class="citiestd11">'.$row12['customerType'].'</td>
						<td class="citiestd11">'.$row12['partyReceivable'].'</td>
					</tr>';
		}	
		    
		$content .= '<tr><td></td><td></td><td></td><td></td><td class="citiestd11">Total</td><td class="citiestd11">'.$totalAmount.'</td></tr>
		</table><br><br><br><br>';

	$pdf->writeHTML($content);  
    ob_end_clean();
	$pdf->Output('BelowReorderLevelProducts.pdf', 'I');
    ob_end_flush();
?>