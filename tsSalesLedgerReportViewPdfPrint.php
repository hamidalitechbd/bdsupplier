<?php ob_start(); //$conPrefix = '';
include 'includes/session.php';
$spId = $_GET['tsId'];

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
    // water marks 
   // $pdf->Image('images/companylogo/watermarkJafree.png', 10, 10,189);	
    $content = '<style>
				.supAddress {font-size: 8px;}
				.supAddressFont {font-size: 8px;}
				</style>'; 
	$sql = "SELECT DISTINCT tbl_party.id, tbl_party.partyName, tbl_party.contactPerson, tbl_party.partyPhone, tbl_party.partyAddress
            FROM tbl_tsalesproducts 
            LEFT OUTER JOIN tbl_temporary_sale ON tbl_temporary_sale.id = tbl_tsalesproducts.tbl_tSalesId AND tbl_temporary_sale.deleted = 'No'
            LEFT OUTER JOIN tbl_party ON tbl_temporary_sale.tbl_customerId = tbl_party.id
            WHERE tbl_temporary_sale.tbl_customerId = '".$spId."' AND tbl_tsalesproducts.status = 'Running'
            ORDER BY tbl_temporary_sale.tSalesDate DESC ";
		
		$query = $conn->query($sql);
		while($row = $query->fetch_assoc()){
			$partyName=$row['partyName'];
			$contactPerson=$row['contactPerson'];
			$partyPhone=$row['partyPhone'];
			$partyAddress=$row['partyAddress'];
			
		}
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
			.citiestd11 {text-align: center;font-size: 8px;}
			.citiestd20 {text-align: center;font-size: 7px;}
			.citiestd21 {font-size: 7px;}
			.citiestd22 {font-size: 7px;}
			span{font-size: 9px;}
			h2{font-size: 18px;text-align:center;}
		</style>';
		$image = 'images/companylogo/'.$image;    
        $pdf->Image($image, 85, 3,40, 15);
		$content .='<br>
		<p> '.$address.' Tel:'.$phone.' Mobile: '.$mobile.'<br>E-mail:'.$email.'</p>
		<div class="citiestd13">TS Sales Report </div>
		<span class="citiestd11"></span>
		<table border="" cellspacing="0" cellpadding="3">
			<tr>
				<td width="73%" class="supAddress">Customer Name :<font color="gray" class="supAddressFont">'.$partyName.'</font><br>Contact Person :<font color="gray" class="supAddressFont">'.$contactPerson.'</font> Phone :<font color="gray" class="supAddressFont"> '.$partyPhone.' </font><br>Address :<font color="gray" class="supAddressFont"> '.$partyAddress.' </font></td>
				<td width="27%" style="border: 1px solid gray;font-size: 8px;" >Print Date :<font color="gray">' . date("Y-m-d") .'</font><br>Printed By :<font color="gray">'.$fname.'</font></td>
			</tr>
			<tr>
				<td width="100%">Del. Date :<font color="gray"></font> Adj. Date :<font color="gray"></font> Ret. Date :<font color="gray"></td>
			</tr>
		</table> 
		<table border="1" cellspacing="0" cellpadding="3">
		
			<tr>
				<th class="citiestd11" width="4%">SL#</th>
				<th class="citiestd11" width="10%">Date</th>
				<th class="citiestd11" width="7%">TS#</th>
				<th class="citiestd11" width="18%">Product Name</th>
				<th class="citiestd11" width="12%">Specification</th>
				<th class="citiestd11" width="10%">Quantity</th>
				<th class="citiestd11" width="10%">Ret Qty</th>
				<th class="citiestd11" width="10%">SoldQty</th>
				<th class="citiestd11" width="10%">RemainQty</th>
				<th class="citiestd11" width="10%">Price</th>
			</tr>';
			$sql = "SELECT tbl_temporary_sale.tsNo,tbl_tsalesproducts.quantity, tbl_tsalesproducts.returnedQuantity,tbl_tsalesproducts.amount, tbl_tsalesproducts.soldQuantity, tbl_temporary_sale.tsNo, tbl_temporary_sale.tSalesDate, tbl_products.productName, tbl_products.productCode, tbl_products.modelNo, tbl_brands.brandName, tbl_units.unitName, CONCAT('<ul>', GROUP_CONCAT(CONCAT('<li>',tbl_productspecification.specificationName, ': ', tbl_productspecification.specificationValue) SEPARATOR '</li>') ,'</li></ul>') AS productSpeficiations, ifnull(tbl_tsalesproducts.quantity,0)-ifnull(tbl_tsalesproducts.returnedQuantity,0)-ifnull(tbl_tsalesproducts.soldQuantity, 0) as remainingQuantity  
                    FROM tbl_tsalesproducts 
                    LEFT OUTER JOIN tbl_temporary_sale ON tbl_temporary_sale.id = tbl_tsalesproducts.tbl_tSalesId AND tbl_temporary_sale.deleted = 'No'
                    LEFT OUTER JOIN tbl_products ON tbl_tsalesproducts.tbl_productsId = tbl_products.id
                    LEFT OUTER JOIN tbl_productspecification ON tbl_productspecification.tbl_productsId = tbl_products.id AND tbl_productspecification.deleted = 'No'
                    LEFT OUTER JOIN tbl_units ON tbl_products.units = tbl_units.id
                    LEFT OUTER JOIN tbl_brands ON tbl_products.tbl_brandsId = tbl_brands.id
                    WHERE tbl_tsalesproducts.deleted = 'No' AND tbl_tsalesproducts.status = 'Running' AND tbl_temporary_sale.tbl_customerId='".$spId."'
                    GROUP BY tbl_tsalesproducts.id
                    ORDER BY tbl_temporary_sale.tbl_customerId, tbl_temporary_sale.tSalesDate";
		
		$query = $conn->query($sql);
		$weQuantity=0;
		$i=0;
		$weQuantitybu=0;
		$weQuantitybu12=0;
		while($row12 = $query->fetch_assoc()){
			$i++;
			$carringCost=$row12['amount'];
		    $GrandTotalBlance+=$carringCost;
			$content .= '<tr>
						    <td class="citiestd20">'.$i.'</td>
    						<td class="citiestd20">'.$row12['tSalesDate'].'</td>
    						<td class="citiestd20">'.$row12['tsNo'].'</td>
        					<td class="citiestd21">'.$row12['productName'].'<br>Brand: '.$row12['brandName'].'<br>Model: '.$row12['modelNo'].'</td>
        					<td class="citiestd22">'.$row12['productSpeficiations'].'</td>
        					<td class="citiestd20">'.$row12['quantity'].' '.$row12['unitName'].'</td>
        					<td class="citiestd20">'.$row12['returnedQuantity'].' '.$row12['unitName'].'</td>
        					<td class="citiestd20">'.$row12['soldQuantity'].' '.$row12['unitName'].'</td>
        					<td class="citiestd20">'.$row12['remainingQuantity'].' '.$row12['unitName'].'</td>
        					<td class="citiestd20">'.$row12['amount'].'</td>
					</tr>
					
					';
					
			}	
			$content .= '
			
		</table><br><br><br><br>
		';
		
			/*$content .='
			
				
				<table>
					
					<tr>
						<th class="citiestd15">---------------------------</th><th class="citiestd17">----------------</th><th class="citiestd16">----------------------------</th>
						
					</tr>
					<tr>
						<td class="citiestd15" > Warehouse Authorized </td><td class="citiestd17"> Checked By </td><td class="citiestd16"> Authorized Signature </td>
						
					</tr>
					
				</table>
			';*/


	$pdf->writeHTML($content);  
    ob_end_clean();
	$pdf->Output('schedule.pdf', 'I');
    ob_end_flush();
?>