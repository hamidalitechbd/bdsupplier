<?php ob_start(); //$conPrefix = '';
include 'includes/session.php';
//$id = $_GET['pid'];
$prid = $_GET['prid'];
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
	$page_header = '<div>
    <table width="100%"><br><br><br><br><br><br>
        <tr>
            <td style="text-align:center;font-size: 25px;">'.strtoupper('Bangladesh Suppliers').'</td>
        </tr><tr>    
            <td style="padding-top:1px;text-align:center;"> '.strtoupper($address).' <br>Tel:'.$phone.' Mobile: '.$mobile.'<br>E-mail:'.$email.'</td>
        </tr>
    </table>
    </div>';
     $page_banner = $image;	
		
		// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

    //Page header
    public function Header() {
        
        global $imageWatermark;
        global $page_header;
        global $page_banner;
        //$this->SetFont('helvetica', '', 7);
        //Page number
        $image_file = "images/companylogo/$page_banner";
        $this->Image($image_file, 90, 4,27, 20, 'JPG', '', 'T', false, 100, '', false, false, 0, false, false, false);
        $this->writeHTML($page_header);
        
        
        $image_file = 'images/companylogo/'.$imageWatermark;
        $this->Image($image_file,10, 60,189, '', 'JPG', '', 'T', false, 100, '', false, false, 0, false, false, false);
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
    $pdf->SetMargins(PDF_MARGIN_LEFT, '', PDF_MARGIN_RIGHT);  
    $pdf->SetMargins('8', '47', '8');
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
			$fname12=$row123[fname];
			}
	$sql = "SELECT tbl_purchase_return.tbl_purchaseId,tbl_purchase_return.purchaseReturnOrderNo,tbl_purchase_return.purchaseReturnDate,tbl_purchase_return.entryDate,tbl_purchase.purchaseOrderNo,tbl_purchase.tbl_supplierId,tbl_party.partyName,tbl_party.tblCountry,tbl_party.tblCity,
			tbl_party.partyAddress,tbl_party.contactPerson,tbl_party.partyPhone,tbl_party.partyAltPhone,tbl_users.fname
			FROM `tbl_purchase_return`
			LEFT JOIN tbl_purchase ON tbl_purchase.id=tbl_purchase_return.tbl_purchaseId
			LEFT JOIN tbl_party ON tbl_party.id=tbl_purchase.tbl_supplierId
			LEFT JOIN tbl_users ON tbl_users.id=tbl_purchase_return.entryBy
			WHERE tbl_purchase_return.id='".$prid."'";
		
		$query = $conn->query($sql);
		while($row = $query->fetch_assoc()){
			$partyName=$row['partyName'];
			$tblCountry=$row['tblCountry'];
			$tblCity=$row['tblCity'];
			$partyAddress=$row['partyAddress'];
			$contactPerson=$row['contactPerson'];
			$partyPhone=$row['partyPhone'];
			$partyAltPhone=$row['partyAltPhone'];
			$creditLimit=$row['creditLimit'];
			$purchaseReturnOrderNo=$row['purchaseReturnOrderNo'];
			$purchaseOrderNo=$row['purchaseOrderNo'];
			$purchaseDate=$row['purchaseReturnDate'];
			$fname=$row['fname'];
			$entryDate=$row['entryDate'];
		}
    $content .= '<style>p{color:black;font-size: 8px;text-align:center;}
			.cities {background-color: gray;color: white;text-align: center;padding: 30px;}
			.citiestd {background-color: yellow;color: black;text-align: center;}
			.citiestd12 {background-color: gray;color: white;text-align: center; font-size: 9px;}
			.citiestd13 {background-color: orange;color: white;text-align: center;}
			.citiestd11 {text-align: center;font-size: 8px;}
			.citiestd14 {text-align: center;font-size: 7px;}
			.citiestd15 {font-size: 7px;}
			span{font-size: 9px;}
			h2{font-size: 18px;text-align:center;}
		</style>';
		//$image = 'images/companylogo/'.$image;    
        //$pdf->Image($image, 85, 3,40, 15);
		$content .='<br>
		<div class="cities"> Purchase Return Invoice : '.$purchaseReturnOrderNo.' </div> <br>
		
		<table border="" cellspacing="0" cellpadding="3">
			<tr>
				<td width="73%" class="supAddress">Supplier Name :<font color="gray" class="supAddressFont">'.$partyName.'</font><br>Address :<font color="gray" class="supAddressFont"> '.$partyAddress.' - '.$tblCity.','.$tblCountry.'</font><br>Contact Person :<font color="gray" class="supAddressFont"> '.$contactPerson.'</font> / Phone :<font color="gray" class="supAddressFont"> '.$partyPhone.' , '.$partyAltPhone.'</font></td>
				<td width="27%" style="border: 1px solid gray;font-size: 8px;" >Purchase Order No :<font color="gray">'.$purchaseOrderNo.'</font><br>Purchase Date :<font color="gray">'.$purchaseDate.'</font><br>Entry Date :<font color="gray">'.$entryDate.'</font><br>Entry By :<font color="gray">'.$fname.'</font><br>Printed By :<font color="gray">'.$fname12.'</font></td>
			</tr>
			<tr>
				<td width="100%">Del. Date :<font color="gray"></font> Adj. Date :<font color="gray"></font> Ret. Date :<font color="gray"></td>
			</tr>
		</table> <br><br>
		<u>Items Return Details Information :</u><br><br>
		<table border="1" cellspacing="0" cellpadding="3">
		
			<tr>
				<th class="citiestd11" width="5%">SL#</th>
				<th class="citiestd11" width="15%">WareHouse</th>
				<th class="citiestd11" width="25%">Product Name</th>
				<th class="citiestd11" width="25%">Specification</th>
				<th class="citiestd11" width="10%">Purchase Price</th>
				<th class="citiestd11" width="10%">Qty</th>
				<th class="citiestd11" width="10%">Total</th>
				
			</tr>';
			$sql = "SELECT tbl_purchase_product_return.tbl_purchase_return_id,tbl_warehouse.wareHouseName,tbl_warehouse.wareHouseAddress,tbl_purchase_product_return.tbl_productsId,tbl_products.productName,tbl_purchase_product_return.quantity,tbl_units.unitName,
					tbl_purchase_product_return.purchasePrice,tbl_purchase_product_return.totalAmount
					FROM `tbl_purchase_product_return`
					LEFT JOIN tbl_products ON tbl_products.id=tbl_purchase_product_return.tbl_productsId
					LEFT JOIN tbl_warehouse ON tbl_warehouse.id=tbl_purchase_product_return.tbl_wareHouseId
                    LEFT JOIN tbl_units ON tbl_units.id=tbl_products.units
					WHERE tbl_purchase_product_return.tbl_purchase_return_id='".$prid."'";
		
		$query = $conn->query($sql);
		$weQuantity=0;
		$i=0;
		$weQuantitybu=0;
		$weQuantitybu12=0;
		while($row12 = $query->fetch_assoc()){
			$sqlspec ="SELECT specificationName,specificationValue FROM `tbl_productspecification` WHERE tbl_productsId='".$row12['tbl_productsId']."'";
			$querySpec = $conn->query($sqlspec);
			
			$i++;
			//$total=$row12['purchasePrice']*$row12['quantity'];
			$grandTotal+=$row12['totalAmount'];
			$content .= '<tr>
						<td class="citiestd14">'.$i.'</td>
						<td class="citiestd14">'.$row12['wareHouseName'].'<br>'.$row12['wareHouseAddress'].'</td>
						<td class="citiestd15">'.$row12['productName'].'</td>
						<td class="citiestd15">';
			while($rowSpec = $querySpec->fetch_assoc()){
			    $content .= $rowSpec['specificationName'].' - '.$rowSpec['specificationValue'].'<br>';
			}
			$content .= '</td>
		                <td class="citiestd14">'.$row12['purchasePrice'].'</td>
						<td class="citiestd14">'.$row12['quantity'].' '.$row12['unitName'].'</td>
						<td class="citiestd14">'.$row12['totalAmount'].'</td>
						
						
					</tr>
					
					';
					
			}	
			$content .= '
			<tr><td></td><td></td><td></td><td></td><td></td><td class="citiestd11" >Total = </td><td class="citiestd11">'.number_format($grandTotal,2).'</td></tr>
		</table><br><br>
		';
			
			$content .='
				<!--<table>
					<tr><br>
						<td width="60%"> </td>
						<td width="30%">Delivery Weapons : </td>
					</tr>
					<tr>
						<td width="71%"></td>
						<td width="30%">Bullets : </td>
					</tr>
					<tr>
						<td width="61.4%"></td>
						<td width="35%">Return Weapons : </td>
					</tr>
					<tr>
						<td width="71%"></td>
						<td width="35%">Bullets : </td>
					</tr>
					<tr>
						<td width="58.5%"></td>
						<td width="25%">Total Weapons Use : </td>
					</tr>
					<tr>
						<td width="61%"></td>
						<td width="25%">Total Bullets Use : </td>
					</tr>
					
				</table-->
			';
		
	$pdf->writeHTML($content);  
    ob_end_clean();
	$pdf->Output('schedule.pdf', 'I');
    ob_end_flush();
?>