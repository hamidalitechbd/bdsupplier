<?php ob_start(); //$conPrefix = '';
include 'includes/session.php';

date_default_timezone_set('Asia/Dhaka');
$toDay = (new DateTime($test))->format("Y-m-d H:i:s");
$toDayDateTime = date('Y-m-d h:i:s: A', strtotime($toDay));

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
$id = $_GET['id'];
$purid = $_GET['purid'];
$supId = $_GET['supId'];
    //$type = htmlspecialchars($_GET["page"]);
    //if($type != "")
    //{
    //$sessionId = time().uniqid();

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
			$fname11=$row123[fname];
			}
	$sql = "SELECT tbl_purchaseForeign.id,tbl_purchaseForeign.purchaseOrderNo,tbl_purchaseForeign.purchaseDate,tbl_purchaseForeign.lcNo,tbl_purchaseForeign.lcOpeningDate,
            tbl_purchaseForeign.deliveryDate,tbl_purchaseForeign.fileNo,tbl_purchaseForeign.blNo,tbl_purchaseForeign.createdDate,tbl_bank_account_info.accountNo,tbl_bank_account_info.accountName,tbl_bank_account_info.bankName,tbl_bank_account_info.branchName,
            tbl_party.partyName,tbl_party.tblCountry,tbl_party.tblCity,tbl_party.locationArea,tbl_party.partyAddress,tbl_party.partyCode,tbl_party.partyType,tbl_party.contactPerson,tbl_party.partyPhone,tbl_party.partyEmail,tbl_purchaseForeign.purchaseType,tbl_purchaseForeign.chalanNo,tbl_users.fname
            FROM `tbl_purchaseForeign`
            LEFT JOIN tbl_party ON tbl_party.id=tbl_purchaseForeign.tbl_supplierId
            LEFT JOIN tbl_bank_account_info ON tbl_bank_account_info.id=tbl_purchaseForeign.tbl_bankInfoId
            LEFT JOIN tbl_users ON tbl_users.id=tbl_purchaseForeign.createdBy
            WHERE tbl_purchaseForeign.deleted!='Yes' AND tbl_purchaseForeign.id='".$id."'";
		
		$query = $conn->query($sql);
		while($row = $query->fetch_assoc()){
			$partyName=$row['partyName'];
			$tblCountry=$row['tblCountry'];
			$tblCity=$row['tblCity'];
			$locationArea=$row['locationArea'];
			$partyAddress=$row['partyAddress'];
			$contactPerson=$row['contactPerson'];
			$partyPhone=$row['partyPhone'];
			$partyEmail=$row['partyEmail'];
			
			$purchaseOrderNo=$row['purchaseOrderNo'];
			$purchaseDate=$row['purchaseDate'];
			$createdDate12=$row['createdDate'];
			$createdDate= date('Y-m-d h:i:s A',strtotime($createdDate12));
			$chalanNo=$row['chalanNo'];
			$lcNo=$row['lcNo'];
			$lcOpeningDate=$row['lcOpeningDate'];
			$deliveryDate=$row['deliveryDate'];
			$fileNo=$row['fileNo'];
			$blNo=$row['blNo'];
			$accountNo=$row['accountNo'];
			$accountName=$row['accountName'];
			$bankName=$row['bankName'];
			$branchName=$row['branchName'];
			$fname=$row['fname'];
			
		}
    $content .= '<style>p{color:black;font-size: 8px;text-align:center;}
			.cities {background-color: gray;color: white;text-align: center;padding: 30px;}
			.citiestd {background-color: yellow;color: black;text-align: center;}
			.citiestd12 {background-color: gray;color: white;text-align: center; font-size: 9px;}
			.citiestd13 {background-color: orange;color: white;text-align: center;}
			.citiestd11 {text-align: center;font-size: 7px;}
			span{font-size: 9px;}
			h2{font-size: 18px;text-align:center;}
		</style>';
		//$image = 'images/companylogo/'.$image;    
        //$pdf->Image($image, 85, 3,40, 15);
		$content .='<br>
		<div class="cities">Import Purchase Invoice : '.$purchaseOrderNo.'</div>
		
		<table border="" cellspacing="0" cellpadding="3">
			<tr>
				<td width="35%" class="supAddress">Supplier Name :<font color="gray" class="supAddressFont">'.$partyName.'</font><br>Address :<font color="gray" class="supAddressFont"> '.$partyAddress.' - '.$tblCity.','.$tblCountry.' '.$locationArea.'</font><br>Contact Person :<font color="gray" class="supAddressFont"> '.$contactPerson.'</font><br>Phone :<font color="gray" class="supAddressFont"> '.$partyPhone.'</font><br>Email :<font color="gray" class="supAddressFont"> '.$partyEmail.'</font></td>
				<td width="35%" style="border: 1px solid gray;font-size: 8px;" >LC Number :<font color="gray">'.$lcNo.'</font><br>LC Open Date :<font color="gray">'.$lcOpeningDate.'</font><br>LC Delivery Date :<font color="gray">'.$deliveryDate.'</font><br>File Number :<font color="gray">'.$fileNo.'</font><br>BL Number :<font color="gray">'.$blNo.'</font><br>Bank Info :<font color="gray">'.$bankName.' - '.$accountNo.' - '.$branchName.'</font></td>
				<td width="30%" style="border: 1px solid gray;font-size: 8px;" >Mamo No :<font color="gray">'.$chalanNo.'</font><br>Purchase Date :<font color="gray">'.$purchaseDate.'</font><br>Entry By :<font color="gray">'.$fname.'</font><br>Entry Date :<font color="gray">'.$createdDate.'</font><br>Printed By :<font color="gray">'.$fname11.'</font><br>Print Date :<font color="gray">'.$toDayDateTime.'</font></td>
			</tr>
			<tr>
				<td width="100%">Del. Date :<font color="gray"></font> Adj. Date :<font color="gray"></font> Ret. Date :<font color="gray"></td>
			</tr>
		</table> <br><br>
		Purchase product information :<br><br>
		<table border="1" cellspacing="0" cellpadding="3">
		
			<tr>
				<th class="citiestd11" width="5%">SL#</th>
				<th class="citiestd11" width="25%">Product Information</th>
				<th class="citiestd11" width="25%">Specification</th>
				<th class="citiestd11" width="16%">Category Name</th>
				<th class="citiestd11" width="10%">Purchase Price</th>
				<th class="citiestd11" width="9%">Qty</th>
				<th class="citiestd11" width="10%">Total</th>
				
			</tr>';
			$sql = "SELECT tbl_purchaseForeign.id,tbl_products.productName,tbl_products.productCode,tbl_units.unitName,tbl_brands.brandName,tbl_category.categoryName,tbl_products.lotNumber,tbl_products.modelNo,tbl_purchaseForeignProducts.quantity,tbl_purchaseForeignProducts.tbl_productsId,
                    tbl_purchaseForeignProducts.purchaseAmount,tbl_purchaseForeignProducts.totalAmount,tbl_purchaseForeignProducts.tbl_wareHouseId,tbl_purchaseForeignProducts.manufacturingDate,tbl_purchaseForeignProducts.expiryDate
                    FROM `tbl_purchaseForeign`
                    LEFT JOIN tbl_purchaseForeignProducts ON tbl_purchaseForeignProducts.tbl_purchaseForeignId=tbl_purchaseForeign.id
                    LEFT JOIN tbl_products ON tbl_products.id=tbl_purchaseForeignProducts.tbl_productsId
                    LEFT JOIN tbl_units ON tbl_units.id=tbl_products.units AND tbl_units.deleted!='Yes'
                    LEFT JOIN tbl_brands ON tbl_brands.id=tbl_products.tbl_brandsId AND tbl_brands.deleted!='Yes'
                    LEFT JOIN tbl_category ON tbl_category.id=tbl_products.categoryId AND tbl_category.deleted!='Yes'
                    WHERE tbl_purchaseForeign.id='".$id."'";
		
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
						<td class="citiestd11">'.$i.'</td>
						<td class="citiestd11">'.$row12['productName'].' - '.$row12['productCode'].'</td>
						<td class="citiestd11">';
			while($rowSpec = $querySpec->fetch_assoc()){
			    $content .= $rowSpec['specificationName'].' - '.$rowSpec['specificationValue'].'<br>';
			}
			 $content .='Model :'.$row12['modelNo'].'<br>';
			 $content .='Model :'.$row12['brandName']; 
			$content .= '</td>
		                <td class="citiestd11">'.$row12['categoryName'].'</td>
						<td class="citiestd11">'.$row12['purchaseAmount'].'</td>
						<td class="citiestd11">'.$row12['quantity'].' '.$row12['unitName'].'</td>
						<td class="citiestd11">'.number_format($total,2).'</td>
						
						
					</tr>
					
					';
					
			}	
			$content .= '
			<tr><td></td><td></td><td></td><td></td><td></td><td class="citiestd11" >Total = </td><td class="citiestd11">'.number_format($grandTotal,2).'</td></tr>
		</table><br><br>
		';
			
		
	$pdf->writeHTML($content);  
    ob_end_clean();
	$pdf->Output('schedule.pdf', 'I');
    ob_end_flush();
?>