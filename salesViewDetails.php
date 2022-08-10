<?php ob_start(); //$conPrefix = '';
include 'includes/session.php';
$id = $_GET['id'];
$loginId = $_SESSION['user'];
//$purid = $_GET['purid'];
//$supId = $_GET['supId'];
    //$type = htmlspecialchars($_GET["page"]);
    //if($type != "")
    //{
    //$sessionId = time().uniqid();
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
			$fname11=$row123[fname];
			}			
	$sql = "SELECT tbl_sales.*,tbl_walkin_customer.customerName,tbl_walkin_customer.customerAddress,tbl_walkin_customer.phoneNo,tbl_users.fname,tblRef.fname as refBy
            FROM `tbl_sales` 
            LEFT JOIN tbl_walkin_customer ON tbl_walkin_customer.id=tbl_sales.tbl_customerId
            LEFT JOIN tbl_users ON tbl_users.id=tbl_sales.createdBy
            LEFT JOIN tbl_users tblRef ON tblRef.id=tbl_sales.tbl_userId
            WHERE tbl_sales.id='".$id."'";
		
		$query = $conn->query($sql);
		while($row = $query->fetch_assoc()){
			$customerName=$row['customerName'];
			$customerAddress=$row['customerAddress'];
			$remarks=$row['remarks'];
			$phoneNo=$row['phoneNo'];
			$fname=$row['fname'];
			$refBy=$row['refBy'];
			$createdDate12=$row['createdDate'];
			$createdDate = date('Y-m-d h:i:s A', strtotime($createdDate12));
			
            $salesOrderNo=$row['salesOrderNo'];
			$salesDate=$row['salesDate'];
			$paymentType=$row['paymentType'];
			
			
		}
    $content .= '<style>
            p{color:black;font-size: 8px;text-align:center;}
			.cities {background-color: gray;color: white;text-align: center;padding: 30px;}
			.citiestd {background-color: yellow;color: black;text-align: center;}
			.citiestd5 {color: gray;text-align: center;font-size: 8px;}
			.citiestd12 {font-size: 10px;}
			.citiestd13 {background-color: gray;color: white;text-align: center;font-size: 10px;padding: 30px;}
			.citiestd14 {text-align: right;font-size: 8px;}
			.citiestd15 {font-size: 8px;}
			.citiestd16 {text-align: right;font-size: 8px;}
			.citiestd17 {text-align: center;font-size: 8px;}
			.citiestd18 {text-align: center;font-size: 8px;}
			.citiestd11 {text-align: center;font-size: 8px;}
			.citiestd19 {font-size: 7px;}
			.citiestd20 {text-align: center;font-size: 7px;}
			span{font-size: 9px;}
			h2{font-size: 18px;text-align:center;}
		</style>';
		$image = 'images/companylogo/'.$image;    
        $pdf->Image($image, 85, 3,40, 15);
		$content .='<br>
		<p> '.$address.' Tel:'.$phone.' Mobile: '.$mobile.'<br>E-mail:'.$email.'</p>
		<div class="citiestd13">Invoice Number: WI-'.$salesOrderNo.'</div>
		
		<table border="1" cellspacing="0" cellpadding="3">
			<tr>
				<td width="40%" class="supAddress">Customer Name :<font class="supAddressFont"><b> '.ucfirst($customerName).'</b></font><br>Phone :<font class="supAddressFont"> <b>'.$phoneNo.'</b></font><br>Address :<font class="supAddressFont"> <b>'.$customerAddress.'</b></font></td>
				<td width="30%" style="border: 1px solid gray;font-size: 8px;" >Sales Date :<font><b>'.$salesDate.'</b></font><br>Payment Mode :<font><b>'.$paymentType.'</b></font></td>
				<td width="30%" style="border: 1px solid gray;font-size: 8px;" >Entry Date :<font><b>'.$createdDate.'</b></font><br>Entry By :<font><b>'.$fname.'</b></font><br>Printed By :<font><b>'.$fname11.'</b></font></td>
			</tr>
			<tr>
				<td width="100%">Del. Date :<font color="gray"></font> Adj. Date :<font color="gray"></font> Ret. Date :<font color="gray"></td>
			</tr>
		</table> <br><br>
		<table border="1" cellspacing="0" cellpadding="3">
		
			<tr>
				<th class="citiestd11" width="5%">SL#</th>
				<th class="citiestd11" width="25%">Items</th>
				<th class="citiestd11" width="28%">Specification</th>
				<th class="citiestd11" width="8%">Brand</th>
				<th class="citiestd11" width="8%">Quantity</th>
				<th class="citiestd11" width="8%">Unit Price</th>
				<th class="citiestd11" width="8%">Discount</th>
				<th class="citiestd11" width="10%">Total</th>
				
			</tr>';
			/*
			SELECT  tbl_sales.salesOrderNo, tbl_sales.salesDate, tbl_walkin_customer.customerName, tbl_walkin_customer.phoneNo, tbl_walkin_customer.customerAddress, tbl_users.fname as soldBy, tbl_products.productName,tbl_products.modelNo, tbl_products.productCode,tbl_units.unitName, 
			tbl_brands.brandName, tbl_sales_products.quantity as quantity, tbl_sales_products.salesAmount as salesAmount, tbl_sales_products.totalAmount as totalAmount, tbl_sales_products.discount as discount, tbl_sales_products.grandTotal as grandTotal, tbl_sales_products.tbl_wareHouseId, tbl_sales.totalAmount AS salesTotalAmount,
			tbl_sales.productDiscount, tbl_sales.salesDiscount, tbl_sales.grandTotal as salesGrandTotal, tbl_sales.vat, tbl_sales.ait,tbl_sales.carringCost, ifnull(tbl_paymentVoucher.amount,0) AS paidAmount, GROUP_CONCAT(CONCAT(tbl_productspecification.specificationName,': ',tbl_productspecification.specificationValue) SEPARATOR ', ') AS productSpecification
                FROM tbl_sales 
                LEFT OUTER JOIN tbl_walkin_customer ON tbl_sales.tbl_customerId = tbl_walkin_customer.id AND tbl_walkin_customer.deleted='No'
                LEFT OUTER JOIN tbl_users ON tbl_sales.tbl_userId=tbl_users.id AND tbl_users.deleted='No'
                LEFT OUTER JOIN tbl_sales_products ON tbl_sales.id = tbl_sales_products.tbl_salesId AND tbl_sales_products.deleted='No'
                LEFT OUTER JOIN tbl_products ON tbl_sales_products.tbl_productsId = tbl_products.id 
                LEFT OUTER JOIN tbl_brands ON tbl_products.tbl_brandsId = tbl_brands.id
                LEFT OUTER JOIN tbl_units ON tbl_units.id=tbl_products.units
                LEFT OUTER JOIN tbl_productspecification ON tbl_products.id = tbl_productspecification.tbl_productsId AND tbl_productspecification.deleted='No'
                LEFT OUTER JOIN tbl_paymentVoucher ON tbl_paymentVoucher.tbl_sales_id = tbl_sales.id AND tbl_paymentVoucher.type = 'paymentReceived'
                WHERE tbl_sales.type = 'WalkinSale' AND tbl_sales.id='".$id."' AND tbl_sales.deleted = 'No'
                GROUP BY tbl_sales_products.tbl_productsId
			*/
                
            $sql = "SELECT  tbl_sales.salesOrderNo, tbl_sales.salesDate, tbl_walkin_customer.customerName, tbl_walkin_customer.phoneNo, tbl_walkin_customer.customerAddress, tbl_users.fname as soldBy, tbl_products.productName,tbl_products.modelNo, tbl_products.productCode,tbl_units.unitName, 
			tbl_brands.brandName, SUM(tbl_sales_products.quantity) as quantity, tbl_sales_products.salesAmount as salesAmount, SUM(tbl_sales_products.totalAmount) as totalAmount, SUM(tbl_sales_products.discount) as discount, SUM(tbl_sales_products.grandTotal) as grandTotal, tbl_sales_products.tbl_wareHouseId, tbl_sales.totalAmount AS salesTotalAmount,
			tbl_sales.productDiscount, tbl_sales.salesDiscount, tbl_sales.grandTotal as salesGrandTotal, tbl_sales.vat, tbl_sales.ait,tbl_sales.carringCost, ifnull(tbl_paymentVoucher.amount,0) AS paidAmount, tbl_sales_products.tbl_productsId
                FROM tbl_sales 
                LEFT OUTER JOIN tbl_walkin_customer ON tbl_sales.tbl_customerId = tbl_walkin_customer.id AND tbl_walkin_customer.deleted='No'
                LEFT OUTER JOIN tbl_users ON tbl_sales.tbl_userId=tbl_users.id AND tbl_users.deleted='No'
                LEFT OUTER JOIN tbl_sales_products ON tbl_sales.id = tbl_sales_products.tbl_salesId AND tbl_sales_products.deleted='No'
                LEFT OUTER JOIN tbl_products ON tbl_sales_products.tbl_productsId = tbl_products.id 
                LEFT OUTER JOIN tbl_brands ON tbl_products.tbl_brandsId = tbl_brands.id
                LEFT OUTER JOIN tbl_units ON tbl_units.id=tbl_products.units
                LEFT OUTER JOIN tbl_paymentVoucher ON tbl_paymentVoucher.tbl_sales_id = tbl_sales.id AND tbl_paymentVoucher.type = 'paymentReceived'
                WHERE tbl_sales.type = 'WalkinSale' AND tbl_sales.id='".$id."' AND tbl_sales.deleted = 'No'
                GROUP BY tbl_sales_products.tbl_productsId,tbl_sales_products.salesAmount,tbl_sales_products.discount";
		$query = $conn->query($sql);
		$weQuantity=0;
		$i=0;
		$weQuantitybu=0;
		$weQuantitybu12=0;
		$paidAmount = 0;
		while($row12 = $query->fetch_assoc()){
		    $productSpac = '';
		    $sql_spec = "SELECT tbl_productspecification.specificationName, tbl_productspecification.specificationValue 
                    FROM tbl_productspecification
                    WHERE tbl_productspecification.tbl_productsId = '".$row12["tbl_productsId"]."' AND tbl_productspecification.deleted='No'";
            $query_spec = $conn->query($sql_spec);
            while($row_spec = $query_spec->fetch_assoc()){
                $productSpac .= $row_spec['specificationName'].' : '.$row_spec['specificationValue'].',';
            }
			$i++;
			$paidAmount = $row12['paidAmount'];
			$totalAmount=$row12['salesTotalAmount'];
		    $totalDiscount=$row12['salesDiscount'];
		    $vat=$row12['vat'];
		    $ait=$row12['ait'];
		    $carringCost=$row12['carringCost'];
		    $GrandTotalBlance=$totalAmount-$totalDiscount+$vat+$ait+$carringCost;
			$content .= '<tr>
						<td class="citiestd20">'.$i.'</td>
						<td class="citiestd19">'.$row12['productName'].'</td>
						<td class="citiestd19">Model: '.$row12['modelNo'].'<br>'.$productSpac.'</td>
		                <td class="citiestd20">'.$row12['brandName'].'</td>
						<td class="citiestd20">'.$row12['quantity'].' '.$row12['unitName'].'</td>
						<td class="citiestd20">'.$row12['salesAmount'].'</td>
						<td class="citiestd20">'.$row12['discount'].'</td>
						<td class="citiestd20">'.$row12['grandTotal'].'</td>
						
						
					</tr>
					
					';
					
			}	
			$content .= '
			<tr><td></td><td></td><td></td><td></td><td></td><td></td><td class="citiestd11" >Total = </td><td class="citiestd11">'.$totalAmount.'</td></tr>
		</table><br><br>
		';
		function getIndianCurrency(float $number)
        {
            $decimal = round($number - ($no = floor($number)), 2) * 100;
            $decimal_part = $decimal;
            $hundred = null;
            $hundreds = null;
            $digits_length = strlen($no);
            $decimal_length = strlen($decimal);
            $i = 0;
            $str = array();
            $str2 = array();
            $words = array(0 => '', 1 => 'one', 2 => 'two',
                3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six',
                7 => 'seven', 8 => 'eight', 9 => 'nine',
                10 => 'ten', 11 => 'eleven', 12 => 'twelve',
                13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen',
                16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',
                19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
                40 => 'forty', 50 => 'fifty', 60 => 'sixty',
                70 => 'seventy', 80 => 'eighty', 90 => 'ninety');
            $digits = array('', 'hundred','thousand','lakh', 'crore');
        
            while( $i < $digits_length ) {
                $divider = ($i == 2) ? 10 : 100;
                $number = floor($no % $divider);
                $no = floor($no / $divider);
                $i += $divider == 10 ? 1 : 2;
                if ($number) {
                    $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                    $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                    $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
                } else $str[] = null;
            }
        
            $d = 0;
            while( $d < $decimal_length ) {
                $divider = ($d == 2) ? 10 : 100;
                $decimal_number = floor($decimal % $divider);
                $decimal = floor($decimal / $divider);
                $d += $divider == 10 ? 1 : 2;
                if ($decimal_number) {
                    $plurals = (($counter = count($str2)) && $decimal_number > 9) ? 's' : null;
                    $hundreds = ($counter == 1 && $str2[0]) ? ' and ' : null;
                    @$str2 [] = ($decimal_number < 21) ? $words[$decimal_number].' '. $digits[$decimal_number]. $plural.' '.$hundred:$words[floor($decimal_number / 10) * 10].' '.$words[$decimal_number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
                } else $str2[] = null;
            }
        
            $Rupees = implode('', array_reverse($str));
            $paise = implode('', array_reverse($str2));
            $paise = ($decimal_part > 0) ? $paise . ' Paise' : '';
            return ($Rupees ? $Rupees . 'taka only' : '') . $paise;
        }
			$content .='
			
				<table>
					
					<tr>
						<td width="70%" class="citiestd18">Amount In Words : <b>'.ucfirst(getIndianCurrency($GrandTotalBlance)).'</b></td>
						<td width="20%" class="citiestd14">Sub-Total amount :</td><td width="10%" class="citiestd14">'.$totalAmount.'</td>
					</tr>
					<tr>
						<td width="70%"></td>
						<td width="20%" class="citiestd14">Discount :</td><td width="10%" class="citiestd14" >'.$totalDiscount.'</td>
					</tr>
					<tr>
						<td width="70%"></td>
						<td width="20%" class="citiestd14">VAT :</td><td width="10%" class="citiestd14">'.$vat.'</td>
					</tr>
					<tr>
						<td width="70%"></td>
						<td width="20%" class="citiestd14">AIT :</td><td width="10%" class="citiestd14">'.$ait.'</td>
					</tr>
					<tr>
						<td width="70%"></td>
						<td width="20%" class="citiestd14">Carrying Cost :</td><td width="10%" class="citiestd14">'.$carringCost.'</td>
					</tr>
					<tr>
						<td width="70%"></td>
						<td width="20%" class="citiestd14">Grand Total :</td><td width="10%" class="citiestd14">'.number_format($GrandTotalBlance, 2).'</td>
					</tr>
					<tr>
						<td width="70%"></td>
						<td width="20%" class="citiestd14">Net Payable (Round) :</td><td width="10%" class="citiestd14">'.number_format(round($GrandTotalBlance), 2).'</td>
					</tr>';
				if($paidAmount > 0){
				    $content .='<tr>
						<td width="70%"></td>
						<td width="20%" class="citiestd14">Paid Amount :</td><td width="10%" class="citiestd14">'.number_format($paidAmount, 2).'</td>
					</tr>';
				}
				$content .='</table><br><br><br><br>
				<table>
					<tr>
						<th class="citiestd15"></th><th class="citiestd18">'.$refBy.'</th><th class="citiestd17"></th><th class="citiestd16"></th>
						
					</tr>
					<tr>
						<th class="citiestd15">---------------------------</th><th class="citiestd17">----------------</th><th class="citiestd17">----------------</th><th class="citiestd16">----------------------------</th>
						
					</tr>
					<tr>
						<td class="citiestd15" > Customer Signature </td><td class="citiestd18"> Reference By </td><td class="citiestd17"> Checked By </td><td class="citiestd16"> Authorized Signature </td>
						
					</tr>
					
				</table> ';
			if($remarks!=''){
				$content .='<br><b class="citiestd12" style="text-align:left; width:100%;">Remarks: '.$remarks.' </b>';
				}
			
    			else{
    			    
    			}		
			$content .='	
				<br><br>
				<b class="citiestd12">Note: ***goods sold are not returnable*** </b>
			';
			
		
	$pdf->writeHTML($content);  
    ob_end_clean();
	$pdf->Output('schedule.pdf', 'I');
    ob_end_flush();
?>