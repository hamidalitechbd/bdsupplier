<?php ob_start(); //$conPrefix = '';
include 'includes/session.php';
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

date_default_timezone_set('Asia/Dhaka');
$toDay = (new DateTime($test))->format("Y-m-d H:i:s");
$ToDayTime = date('Y-m-d H:i:s A',strtotime($toDay));
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
			.citiestd11 {text-align: left;font-size: 8px;}
			.citiestd20 {text-align: left;font-size: 7px;}
			.citiestd21 {font-size: 7px;}
			.citiestd22 {font-size: 7px;}
			ul {margin: 0;padding: 0;}
			span{font-size: 9px;}
			h2{font-size: 18px;text-align:center;}
		</style>';
	    $image = 'images/companylogo/'.$image;    
        $pdf->Image($image, 85, 3,40, 15);
		$content .='<br>
		<p> '.$address.' Tel:'.$phone.' Mobile: '.$mobile.'<br>E-mail:'.$email.'</p>
		<div class="citiestd13"> Total Financial Reports </div>';

		$content .= '<table border="1" cellspacing="0" cellpadding="3">
			<tr>
				<td width="100%" style="border: 1px solid gray;font-size: 8px;" >Printed By : <font color="gray" class="supAddressFont">'.$fname.'</font><br>Print Date : <font color="gray" class="supAddressFont">'.$ToDayTime.'</font></td>
			</tr>
			<tr>
				<td width="100%">Del. Date :<font color="gray"></font> Adj. Date :<font color="gray"></font> Ret. Date :<font color="gray"></td>
			</tr>
		</table>
		<br><br>
		<table border="1" cellspacing="0" cellpadding="3">
			<tr>
				<th class="citiestd11" width="10%">SL#</th>
				<th class="citiestd11" width="70%">Particular</th>
				<th class="citiestd11" width="20%">Amounts</th>
				
			</tr>';
			$blance=0;
			$sql12 = "SELECT CAST(SUM(tbl_products.minSalePrice * ifnull(dbt.totalStock,0)) AS DECIMAL(18,2)) as totalAmount
                    FROM tbl_products
                    LEFT OUTER JOIN (SELECT SUM(tbl_currentStock.currentStock) AS totalStock, tbl_currentStock.tbl_productsId
                    FROM tbl_currentStock
                    WHERE tbl_currentStock.deleted = 'No' AND tbl_currentStock.currentStock <> 0
                    GROUP BY tbl_currentStock.tbl_productsId) AS dbt ON tbl_products.id = dbt.tbl_productsId
                    WHERE tbl_products.deleted = 'No' AND tbl_products.status = 'Active'";
		
		$queryProducts = $conn->query($sql12);
		while($row123 = $queryProducts->fetch_assoc()){
		    $totalProductVAlue=$row123['totalAmount'];
		}
		
			$sql = "SELECT IFNULL(Sum(CASE tbl_paymentVoucher.type
                           WHEN 'partyPayable' THEN tbl_paymentVoucher.amount
                           WHEN 'paymentReceived' THEN -tbl_paymentVoucher.amount
                           WHEN 'adjustment' THEN  -tbl_paymentVoucher.amount
                           WHEN 'payable' THEN -tbl_paymentVoucher.amount
                           WHEN 'payment' THEN tbl_paymentVoucher.amount
                           WHEN 'paymentAdjustment' THEN tbl_paymentVoucher.amount
						   WHEN 'discount' THEN -tbl_paymentVoucher.amount
                END),0) AS totalDue, customerType
                FROM tbl_paymentVoucher
                WHERE  deleted = 'No'
                GROUP BY customerType";
		
		$queryProducts = $conn->query($sql);
		$weQuantity=0;
		$i=0;
		    $sql2 ="SELECT tbl_damageProducts.id,SUM(tbl_damageProducts.damageQuantity*tbl_products.minSalePrice) AS sumTotal
                    FROM `tbl_damageProducts`
                    LEFT JOIN tbl_products ON tbl_products.id=tbl_damageProducts.tbl_productsId
                    WHERE tbl_damageProducts.deleted='No'";
		    $queryDamage = $conn->query($sql2);
		    while($row123 = $queryDamage->fetch_assoc()){
		        $totalDAmageAmount=$row123['sumTotal'];
		    }
		    
		while($row12 = $queryProducts->fetch_assoc()){
			$i++;
			
			$totalAmount+=$row12['totalDue'];
			$content .= '<tr>
    						<td class="citiestd20">'.$i.'</td>
    						<td class="citiestd20">'.$row12['customerType'].' Due Value</td>
    						<td class="citiestd16">'.$row12['totalDue'].'</td>
        					
    					</tr>';
		}
		
		$totalValue=$totalAmount+$totalProductVAlue-$totalDAmageAmount;
		$content .= '
		<tr><td class="citiestd20">3</td><td class="citiestd11">Current Product value </td><td class="citiestd16">'.$totalProductVAlue.'</td></tr>
		<tr><td class="citiestd20">4</td><td class="citiestd11">Current Damage Product value </td><td class="citiestd16">'.$totalDAmageAmount.'</td></tr>
		<tr><td colspan="2" class="citiestd16"> Total Amount Value </td><td class="citiestd16">'.$totalValue.'</td></tr>
		</table><br /><br /><br /><br />';
		
		
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
		
		$content .= '
		
		<b>'.ucfirst(getIndianCurrency($totalValue)).'</b><br><br><br>
		
		    ** NB: (Party Received value -Party Payable value) = Party Due Value <br>
		    ** NB: (Due Value + Total product value - Damage product value) = Total Financial Value 
		';
	    
	
	$pdf->writeHTML($content);  
    ob_end_clean();
	$pdf->Output('schedule.pdf', 'I');
    ob_end_flush();
?>