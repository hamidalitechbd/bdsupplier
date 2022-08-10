<?php ob_start(); //$conPrefix = '';
include 'includes/session.php';
$id = $_GET['id'];
$rType = $_GET['rType'];
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
			$customerType='';
	if($rType=='WalkinSale'){
	$sql = "SELECT tbl_sales_return.id,tbl_sales_return.tbl_sales_id,tbl_sales.salesOrderNo,tbl_sales.salesDate,tbl_walkin_customer.id as pId,tbl_walkin_customer.customerName,tbl_walkin_customer.customerAddress,tbl_walkin_customer.phoneNo,tbl_sales_return.salesType,tbl_sales_return.salesReturnOrderNo,tbl_sales_return.entryDate,tbl_sales_return.entryBy,tbl_users.fname 
            FROM `tbl_sales_return`
            LEFT JOIN tbl_walkin_customer on tbl_walkin_customer.id=tbl_sales_return.tbl_customer_id
            LEFT JOIN tbl_users ON tbl_users.id=tbl_sales_return.entryBy
            LEFT JOIN tbl_sales ON tbl_sales.id=tbl_sales_return.tbl_sales_id
            WHERE tbl_sales_return.salesType='".$rType."' AND tbl_sales_return.id='".$id."' AND tbl_sales_return.deleted='No'";
	    $customerType='WalkinCustomer';
	}   
	else{
    $sql = "SELECT tbl_sales_return.id,tbl_sales_return.tbl_sales_id,tbl_sales.salesOrderNo,tbl_sales.salesDate,tbl_sales.totalDue,tbl_party.id as pId,tbl_party.partyName AS customerName,tbl_party.tblCity,tbl_party.tblCountry,tbl_party.locationArea,tbl_party.tblCountry,tbl_party.partyAddress AS customerAddress,tbl_party.contactPerson,tbl_party.partyPhone AS phoneNo,tbl_party.partyAltPhone,tbl_sales_return.salesType,tbl_sales_return.salesReturnOrderNo,tbl_sales_return.entryDate,tbl_sales_return.entryBy,tbl_users.fname 
            FROM `tbl_sales_return`
            LEFT JOIN tbl_party ON tbl_party.id=tbl_sales_return.tbl_customer_id
            LEFT JOIN tbl_users ON tbl_users.id=tbl_sales_return.entryBy
            LEFT JOIN tbl_sales ON tbl_sales.id=tbl_sales_return.tbl_sales_id
            WHERE tbl_sales_return.salesType='".$rType."' AND tbl_sales_return.id='".$id."' AND tbl_sales_return.deleted='No'";
            $customerType='Party';
	}	
		$query = $conn->query($sql);
		while($row = $query->fetch_assoc()){
			$customerName=$row['customerName'];
			$tblCity=$row['tblCity'];
			$tblCountry=$row['tblCountry'];
			$locationArea=$row['locationArea'];
			$customerAddress=$row['customerAddress'];
			$contactPerson=$row['contactPerson'];
			$phoneNo=$row['phoneNo'];
			$partyAltPhone=$row['partyAltPhone'];
			$returnDate=$row['entryDate'];
			$salesReturnOrderNo=$row['salesReturnOrderNo'];
			$fname=$row['fname'];
			$salesType=$row['salesType'];
			$paymentType=$row['paymentType'];
			$salesOrderNo=$row['salesOrderNo'];
			$salesDate=$row['salesDate'];
			$paymentType=$row['paymentType'];
			$pId=$row['pId'];
		}
    $content .= '<style>
            p{color:black;font-size: 8px;text-align:center;}
			.cities {background-color: gray;color: white;text-align: center;padding: 30px;}
			.citiestd {background-color: yellow;color: black;text-align: center;}
			.citiestd12 {text-align: left;font-size: 7px;}
			.citiestd13 {background-color: gray;color: white;text-align: center;font-size: 10px;padding: 30px;}
			.citiestd14 {text-align: right;font-size: 8px;}
			.citiestd15 {font-size: 8px;}
			.citiestd16 {text-align: right;font-size: 8px;}
			.citiestd17 {text-align: center;font-size: 8px;}
			.citiestd18 {text-align: left;font-size: 8px;}
			.citiestd11 {text-align: center;font-size: 7px;}
			.citiestd19 {font-size: 7px;}
			span{font-size: 9px;}
			h2{font-size: 18px;text-align:center;}
		</style>';
		$image = 'images/companylogo/'.$image;    
        $pdf->Image($image, 85, 3,40, 15);
		$content .='<br>
		<p> '.$address.' Tel:'.$phone.' Mobile: '.$mobile.'<br>E-mail:'.$email.'</p>
		<div class="citiestd13">Return Invoice Number: '.$salesReturnOrderNo.'</div>
		
		<table border="1" cellspacing="0" cellpadding="3">
			<tr>
				<td width="50%" class="supAddress">Cusotmer Name :<font class="supAddressFont"><b>'.$customerName.'</b></font><br>Address :<font class="supAddressFont"><b> '.$customerAddress.' '.$locationArea.' '.$tblCity.' '.$tblCountry.'</b></font><br>Phone :<font class="supAddressFont"><b> '.$phoneNo.' '.$partyAltPhone.'</b></font></td>
				<td width="25%" style="border: 1px solid gray;font-size: 8px;" >Sales Order No:<font><b>'.$salesOrderNo.'</b></font><br>Sales Date :<font><b>'.$salesDate.'</b></font></td>
				<td width="25%" style="border: 1px solid gray;font-size: 8px;" >Entry Date :<font><b>'.$returnDate.'</b></font><br>Entry By :<font><b>'.$fname.'</b></font><br>Printed By :<font><b>'.$fname11.'</b></font></td>
			</tr>
			<tr>
				<td width="100%">Del. Date :<font color="gray"></font> Adj. Date :<font color="gray"></font> Ret. Date :<font color="gray"></td>
			</tr>
		</table> <br><br>
		<table border="1" cellspacing="0" cellpadding="3">
		
			<tr>
				<th class="citiestd11" width="5%">SL#</th>
				<th class="citiestd11" width="25%">ProductDetails</th>
				<th class="citiestd11" width="20%">Specification</th>
				<th class="citiestd11" width="10%">Brand</th>
				<th class="citiestd11" width="10%">Quantity</th>
				<th class="citiestd11" width="10%">Amount</th>
				<th class="citiestd11" width="10%">Discount</th>
				<th class="citiestd11" width="10%">Total</th>
				
			</tr>';
			$sql = "SELECT tbl_sales_return.id,tbl_sales_return.returnDate,tbl_sales_return.salesReturnOrderNo,tbl_sales.salesOrderNo,tbl_sales.salesDate,tbl_sales.paymentType,tbl_users.fname,tbl_sales_return.tbl_sales_id,
                    tbl_walkin_customer.customerName,tbl_walkin_customer.phoneNo,tbl_sales_return.salesType,tbl_units.unitName,
                    tbl_sales_product_return.tbl_sales_return_id,tbl_products.id as proID,tbl_products.productName,tbl_products.productCode,tbl_sales_product_return.quantity,
                    tbl_sales_product_return.salePrice,tbl_sales_product_return.remarks,tbl_sales_product_return.totalAmount,tbl_sales_product_return.grandTotal, tbl_products.modelNo, tbl_brands.brandName
                    FROM tbl_sales_return
                    LEFT JOIN tbl_sales_product_return ON tbl_sales_product_return.tbl_sales_return_id=tbl_sales_return.id
                    LEFT JOIN tbl_walkin_customer ON tbl_sales_return.tbl_customer_id = tbl_walkin_customer.id
                    LEFT JOIN tbl_users ON tbl_users.id=tbl_sales_return.entryBy
                    LEFT OUTER JOIN tbl_sales ON tbl_sales_return.tbl_sales_id=tbl_sales.id AND tbl_sales.deleted = 'No'
                    LEFT OUTER JOIN tbl_products ON tbl_sales_product_return.tbl_products_id = tbl_products.id AND tbl_products.deleted='No'
                    LEFT OUTER JOIN tbl_units ON tbl_units.id=tbl_products.units
                    LEFT OUTER JOIN tbl_brands ON tbl_products.tbl_brandsId = tbl_brands.id
                    WHERE tbl_sales_return.id='".$id."' AND tbl_sales_return.salesType='".$rType."' AND tbl_sales_return.deleted='No'";
		
		$query = $conn->query($sql);
		$weQuantity=0;
		$i=0;
		$weQuantitybu=0;
		$weQuantitybu12=0;
		while($row12 = $query->fetch_assoc()){
		    
		    $sqlspec ="SELECT specificationName,specificationValue FROM `tbl_productspecification` WHERE tbl_productsId='".$row12['proID']."'";
			$querySpec = $conn->query($sqlspec);
			
			$i++;
			$totalAmount=$row12['salesTotalAmount'];
		    $totalDiscount=$row12['salesDiscount'];
		    $vat=$row12['vat'];
		    $ait=$row12['ait'];
		    $carringCost=$row12['grandTotal'];
		    $GrandTotalBlance+=$carringCost;
			$content .= '<tr>
						<td class="citiestd11">'.$i.'</td>
						<td class="citiestd11">'.$row12['productName'].'</td>
						<td class="citiestd12">';
			while($rowSpec = $querySpec->fetch_assoc()){
			    $content .= $rowSpec['specificationName'].' - '.$rowSpec['specificationValue'].'<br>'.$row12['modelNo'];
			}
			$content .= '</td>
		                <td class="citiestd11">'.$row12['brandName'].'</td>
		                <td class="citiestd11">'.$row12['quantity'].'   '.$row12['unitName'].'</td>
		                <td class="citiestd11">'.$row12['salePrice'].'</td>
		                <td class="citiestd11">'.$row12['remarks'].'</td>
		                <td class="citiestd11">'.$row12['grandTotal'].'</td>
					</tr>
					
					';
					
			}	
			$content .= '
			<tr><td></td><td></td><td></td><td></td><td></td><td></td><td class="citiestd11" >Total = </td><td class="citiestd11">'.number_format($GrandTotalBlance,2).'</td></tr>
		</table><br><br><br>
		';
		
		$sql="SELECT Sum(CASE tbl_paymentVoucher.type
                   WHEN 'partyPayable' THEN tbl_paymentVoucher.amount
                   WHEN 'paymentReceived' THEN -tbl_paymentVoucher.amount
                   WHEN 'adjustment' THEN  -tbl_paymentVoucher.amount
                   WHEN 'payable' THEN -tbl_paymentVoucher.amount
                   WHEN 'payment' THEN tbl_paymentVoucher.amount
                   WHEN 'paymentAdjustment' THEN tbl_paymentVoucher.amount
                   WHEN 'discount' THEN -tbl_paymentVoucher.amount
                   END) AS totalDue
                    FROM tbl_paymentVoucher
                    WHERE tbl_partyId = '".$pId."' AND customerType = '".$customerType."' and deleted='No' AND tbl_paymentVoucher.entryDate<'".$returnDate."'";       
        $query = $conn->query($sql);
        while($row13 = $query->fetch_assoc()){
            $totalDue=$row13['totalDue'];
        }
        $totalBlance =$totalDue-$GrandTotalBlance;
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
						<td width="15%" class="citiestd14">Amount In Words : </td><td width="45%" class="citiestd19"> <b>'.ucfirst(getIndianCurrency($GrandTotalBlance)).'</b></td>
						<td width="30%" class="citiestd14">Total amount :</td><td width="10%" class="citiestd14">'.number_format($GrandTotalBlance,2).'</td>
					</tr>
					<tr>
						<td width="15%" class="citiestd14">Previous Due :</td><td width="12%" class="citiestd14"><b> '.number_format($totalDue,2).'</b></td><td width="33%" class="citiestd19"></td>
					</tr>
					<tr>
					    <td width="15%" class="citiestd14">Current Return Bill :</td><td width="12%" class="citiestd14"><b> '.number_format($GrandTotalBlance,2).'</b></td><td width="33%" class="citiestd19"></td>
					</tr>
					 <tr>
						<td width="15%" class="citiestd14">Total Due :</td><td width="12%" class="citiestd14"><b> '.number_format($totalBlance,2).'</b></td><td width="33%" class="citiestd19"></td>
					</tr>
					<tr>
						<td width="60%" class="citiestd17"></td>
					</tr>
					
				</table><br><br><br><br>
				
				<table>
					
					<tr>
						<th class="citiestd15">---------------------------</th><th class="citiestd17">----------------</th><th class="citiestd16">----------------------------</th>
						
					</tr>
					<tr>
						<td class="citiestd15" > Customer Signature </td><td class="citiestd17"> Checked By </td><td class="citiestd16"> Authorized Signature </td>
						
					</tr>
					
				</table>
			';
		
	$pdf->writeHTML($content);  
    ob_end_clean();
	$pdf->Output('schedule.pdf', 'I');
    ob_end_flush();
?>