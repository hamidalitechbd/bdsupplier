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
	$sql = "SELECT tbl_sales_return.id,tbl_sales_return.tbl_sales_id,tbl_sales.salesDate,tbl_party.partyName AS customerName,tbl_party.tblCity,tbl_party.tblCountry,tbl_party.locationArea,tbl_party.tblCountry,tbl_party.partyAddress AS customerAddress,tbl_party.contactPerson,tbl_party.partyPhone AS phoneNo,tbl_party.partyAltPhone,tbl_sales_return.salesType,tbl_sales_return.salesReturnOrderNo,tbl_sales_return.returnDate,tbl_sales_return.entryBy,tbl_users.fname 
            FROM `tbl_sales_return`
            LEFT JOIN tbl_party ON tbl_party.id=tbl_sales_return.tbl_customer_id
            LEFT JOIN tbl_users ON tbl_users.id=tbl_sales_return.entryBy
            LEFT JOIN tbl_sales ON tbl_sales.id=tbl_sales_return.tbl_sales_id
            WHERE tbl_sales_return.salesType='".$rType."' AND tbl_sales_return.id='".$id."' AND tbl_sales_return.deleted='No'";
		
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
			$returnDate=$row['returnDate'];
			$salesReturnOrderNo=$row['salesReturnOrderNo'];
			$fname=$row['fname'];
			$salesType=$row['salesType'];
			$salesOrderNo=$row['salesOrderNo'];
			$salesDate=$row['salesDate'];
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
		<div class="citiestd13">TS Return Invoice Number: '.$salesReturnOrderNo.'</div>
		
		<table border="1" cellspacing="0" cellpadding="3">
			<tr>
				<td width="50%" class="supAddress">Cusotmer Name :<font class="supAddressFont"><b>'.$customerName.'</b></font><br>Address :<font class="supAddressFont"> <b>'.$customerAddress.' '.$locationArea.' '.$tblCity.' '.$tblCountry.' '.$phoneNo.'</b></font><br>Phone :<font class="supAddressFont"><b> '.$phoneNo.' '.$partyAltPhone.'</b></font></td>
				<td width="25%" style="border: 1px solid gray;font-size: 8px;" >Sales Date :<font><b>'.$salesDate.'</b></font><br>Payment Mode :<font color="gray"><b>'.$paymentType.'</b></font></td>
				<td width="25%" style="border: 1px solid gray;font-size: 8px;" >Entry Date :<font><b>'.$returnDate.'</b></font><br>Entry By :<font><b>'.$fname.'</b></font><br>Printed By :<font><b>'.$fname11.'</b></font></td>
			</tr>
			<tr>
				<td width="100%">Del. Date :<font color="gray"></font> Adj. Date :<font color="gray"></font> Ret. Date :<font color="gray"></td>
			</tr>
		</table> <br><br>
		<table border="1" cellspacing="0" cellpadding="3">
		
			<tr>
				<th class="citiestd11" width="5%">SL#</th>
				<th class="citiestd11" width="25%">Reference</th>
				<th class="citiestd11" width="40%">ProductDetails</th>
				<th class="citiestd11" width="10%">Quantity</th>
				<th class="citiestd11" width="10%">Amount</th>
				<th class="citiestd11" width="10%">Total</th>
				
			</tr>';
			$sql = "SELECT tbl_sales_return.id,tbl_sales_return.returnDate,tbl_sales_return.salesReturnOrderNo,tbl_sales.salesOrderNo,tbl_sales.salesDate,tbl_sales.paymentType,tbl_users.fname,tbl_sales_return.tbl_sales_id,
                    tbl_walkin_customer.customerName,tbl_walkin_customer.phoneNo,tbl_sales_return.salesType,tbl_units.unitName,
                    tbl_sales_product_return.tbl_sales_return_id,tbl_products.productName,tbl_products.productCode,tbl_sales_product_return.quantity,
                    tbl_sales_product_return.salePrice,tbl_sales_product_return.totalAmount,tbl_sales_product_return.grandTotal
                    FROM `tbl_sales_return`
                    LEFT JOIN tbl_sales_product_return ON tbl_sales_product_return.tbl_sales_return_id=tbl_sales_return.id
                    LEFT JOIN tbl_walkin_customer ON tbl_sales_return.tbl_customer_id = tbl_walkin_customer.id
                    LEFT JOIN tbl_users ON tbl_users.id=tbl_sales_return.entryBy
                    LEFT OUTER JOIN tbl_sales ON tbl_sales_return.tbl_sales_id=tbl_sales.id AND tbl_sales.deleted = 'No'
                    LEFT OUTER JOIN tbl_products ON tbl_sales_product_return.tbl_products_id = tbl_products.id AND tbl_products.deleted='No'
                    LEFT OUTER JOIN tbl_units ON tbl_units.id=tbl_products.units
                    WHERE tbl_sales_return.id='".$id."' AND tbl_sales_return.salesType='".$rType."' AND tbl_sales_return.deleted='No'";
		
		$query = $conn->query($sql);
		$weQuantity=0;
		$i=0;
		$weQuantitybu=0;
		$weQuantitybu12=0;
		while($row12 = $query->fetch_assoc()){
			$i++;
			$totalAmount=$row12['salesTotalAmount'];
		    $totalDiscount=$row12['salesDiscount'];
		    $vat=$row12['vat'];
		    $ait=$row12['ait'];
		    $carringCost=$row12['grandTotal'];
		    $GrandTotalBlance+=$carringCost;
			$content .= '<tr>
						<td class="">'.$i.'</td>
						<td class="citiestd11">Sale Ref ID# '.$row12['salesOrderNo'].'<br>Retrun ID# '.$row12['salesReturnOrderNo'].'</td>
		                <td class="citiestd11">'.$row12['productName'].' - '.$row12['productName'].'</td>
		                <td class="citiestd11">'.$row12['quantity'].'   '.$row12['unitName'].'</td>
		                <td class="citiestd11">'.$row12['salePrice'].'</td>
		                <td class="citiestd11">'.$row12['grandTotal'].'</td>
					</tr>
					
					';
					
			}	
			$content .= '
			<tr><td></td><td></td><td></td><td></td><td class="citiestd11" >Total = </td><td class="citiestd11">'.number_format($GrandTotalBlance,2).'</td></tr>
		</table><br><br><br><br>
		';
		
			$content .='
			
				
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