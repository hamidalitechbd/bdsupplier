<?php ob_start(); //$conPrefix = '';
include 'includes/session.php';
$id = $_GET['id'];
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
    $sql11 = "SELECT fname FROM `tbl_users` WHERE id='".$_SESSION['user']."'";
			$query = $conn->query($sql11);
			while($row123 = $query->fetch_assoc()){
			$fname11=$row123[fname];
			}
    $content = '<style>
				.supAddress {font-size: 8px;}
				.supAddressFont {font-size: 8px;}
				</style>'; 
	$sql = "SELECT tbl_purchase.id,tbl_purchase.tbl_supplierId,tbl_purchase.purchaseOrderNo,tbl_purchase.createdDate,tbl_purchase.purchaseDate,tbl_purchase.chalanNo,tbl_party.id,tbl_party.partyName,tbl_party.tblCountry,tbl_party.tblCity,tbl_party.partyAddress,
		tbl_party.contactPerson,tbl_party.partyPhone,tbl_party.partyAltPhone,tbl_party.creditLimit,tbl_party.tblType,tbl_users.fname
		FROM `tbl_purchase`
		LEFT JOIN tbl_party ON tbl_party.id=tbl_purchase.tbl_supplierId 
        LEFT JOIN tbl_users ON tbl_users.id=tbl_purchase.createdBy
        WHERE tbl_purchase.id='".$id."' AND (tbl_party.tblType='Suppliers' || tbl_party.tblType='Both')";
		
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
			$purchaseOrderNo=$row['purchaseOrderNo'];
			$purchaseDate=$row['purchaseDate'];
			$createdDate12=$row['createdDate'];
			$createdDate = date('Y-m-d h:i:s A', strtotime($createdDate12));
			$chalanNo=$row['chalanNo'];
			$fname=$row['fname'];
			$tblType=$row['tblType'];
			
		}
    $content .= '<style>p{color:black;font-size: 8px;text-align:center;}
			.cities {background-color: gray;color: white;text-align: center;padding: 30px;}
			.citiestd {background-color: yellow;color: black;text-align: center;}
			.citiestd12 {background-color: gray;color: white;text-align: center; font-size: 9px;}
			.citiestd13 {background-color: orange;color: white;text-align: center;}
			.citiestd11 {text-align: center;font-size: 8px;}
			.citiestd14 {font-size: 7px;}
			.citiestd15 {text-align: center;font-size: 7px;}
			span{font-size: 9px;}
			h2{font-size: 18px;text-align:center;}
		</style>';
		//$image = 'images/companylogo/'.$image;    
        //$pdf->Image($image, 85, 3,40, 15);
		$content .='<br>
		<div class="cities"> Purchase Invoice : '.$purchaseOrderNo.'</div>
		
		<table border="1" cellspacing="0" cellpadding="3">
			<tr>
				<td width="73%" class="supAddress">Status :<font color="gray" class="supAddressFont">'.$tblType.'</font><br>Supplier Name :<font color="gray" class="supAddressFont">'.$partyName.'</font><br>Address :<font color="gray" class="supAddressFont"> '.$partyAddress.' - '.$tblCity.','.$tblCountry.'</font><br>Contact Person :<font color="gray" class="supAddressFont"> '.$contactPerson.'</font> / Phone :<font color="gray" class="supAddressFont"> '.$partyPhone.' , '.$partyAltPhone.'</font></td>
				<td width="27%" style="border: 1px solid gray;font-size: 8px;" >Memo No :<font color="gray">'.$chalanNo.'</font><br>Purchase Date :<font color="gray">'.$purchaseDate.'</font><br>Entry Date :<font color="gray">'.$createdDate.'</font><br>Entry By :<font color="gray">'.$fname.'</font><br>Printed By :<font color="gray">'.$fname11.'</font></td>
			</tr>
			<tr>
				<td width="100%">Del. Date :<font color="gray"></font> Adj. Date :<font color="gray"></font> Ret. Date :<font color="gray"></td>
			</tr>
		</table>
		Purchase product information :<br><br>
		<table border="1" cellspacing="0" cellpadding="3">
		
			<tr>
				<th class="citiestd11" width="5%">SL#</th>
				<th class="citiestd11" width="25%">Product Information</th>
				<th class="citiestd11" width="22%">Specification</th>
				<th class="citiestd11" width="10%">MFG Date</th>
				<th class="citiestd11" width="10%">EXP Date</th>
				<th class="citiestd11" width="9%">Purchase Price</th>
				<th class="citiestd11" width="9%">Qty</th>
				<th class="citiestd11" width="10%">Total</th>
				
			</tr>';
			$sql = "SELECT tbl_purchase.purchaseOrderNo,tbl_purchase.purchaseDate,tbl_purchase.purchaseType,tbl_purchaseProducts.manufacturingDate,tbl_purchaseProducts.expiryDate,tbl_warehouse.wareHouseName,
					tbl_purchaseProducts.id,tbl_purchaseProducts.quantity,tbl_products.tbl_unitsId,tbl_products.minSalePrice,tbl_products.maxSalePrice,
                    tbl_purchaseProducts.purchaseAmount,tbl_purchaseProducts.tbl_productsId,tbl_products.productName,tbl_products.productCode,tbl_products.modelNo 
					FROM `tbl_purchase`
					LEFT JOIN tbl_purchaseProducts ON tbl_purchaseProducts.tbl_purchaseId=tbl_purchase.id
					LEFT JOIN tbl_products ON tbl_products.id=tbl_purchaseProducts.tbl_productsId
                    LEFT JOIN tbl_units ON tbl_units.id=tbl_products.tbl_unitsId
                    LEFT JOIN tbl_warehouse ON tbl_warehouse.id=tbl_purchaseProducts.tbl_wareHouseId
					WHERE tbl_purchase.id='".$id."'";
		
		$query = $conn->query($sql);
		$weQuantity=0;
		$i=0;
		$weQuantitybu=0;
		$weQuantitybu12=0;
		while($row12 = $query->fetch_assoc()){
			$sqlspec ="SELECT specificationName,specificationValue FROM `tbl_productspecification` WHERE tbl_productsId='".$row12['tbl_productsId']."'";
			$querySpec = $conn->query($sqlspec);
			
			$i++;
			$total=$row12['purchaseAmount']*$row12['quantity'];
			$grandTotal+=$total;
			$content .= '<tr>
						<td class="citiestd15">'.$i.'</td>
						<td class="citiestd14">'.$row12['productName'].' <br>PCode: '.$row12['productCode'].'<br>Model: '.$row12['modelNo'].'<br>WhName : '.$row12['wareHouseName'].'</td>
						<td class="citiestd14">';
			while($rowSpec = $querySpec->fetch_assoc()){
			    $content .= $rowSpec['specificationName'].' - '.$rowSpec['specificationValue'].'<br>';
			}
			$content .= '</td>
		                <td class="citiestd15">'.$row12['manufacturingDate'].'</td>
						<td class="citiestd15">'.$row12['expiryDate'].'</td>
						<td class="citiestd15">'.$row12['purchaseAmount'].'</td>
						<td class="citiestd15">'.$row12['quantity'].' '.$row12['tbl_unitsId'].'</td>
						<td class="citiestd15">'.number_format($total,2).'</td>
						
						
					</tr>
					
					';
					
			}	
			$content .= '
			<tr><td></td><td></td><td></td><td></td><td></td><td></td><td class="citiestd11" >Total = </td><td class="citiestd11">'.number_format($grandTotal,2).'</td></tr>
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