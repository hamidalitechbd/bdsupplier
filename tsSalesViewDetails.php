<?php ob_start(); //$conPrefix = '';
include 'includes/session.php';
$id = $_GET['id'];

$sql = "SELECT * FROM `shopSettings`"; 
$query = $conn->query($sql);
while($row = $query->fetch_assoc()){
	$addressT=$row['address'];
	$phoneT=$row['phone'];
	$mobileT=$row['mobile'];
	$emailT=$row['email'];
	$websiteT=$row['website'];
	$imageT=$row['image'];
	$imageWatermarkT=$row['imageWatermark'];
	$addTypeT=$row['address_type'];
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
	
	
	$sql = "SELECT tbl_temporary_sale.id, tbl_temporary_sale.tsNo, tbl_temporary_sale.inv_remarks, tbl_temporary_sale.tSalesDate,tbl_temporary_sale.createdDate,tbl_party.partyName,tbl_party.tblCountry,tbl_party.tblCity,tbl_party.locationArea,tbl_party.partyAddress,tbl_party.partyCode,tbl_party.contactPerson,tbl_party.partyPhone,tbl_users.fname,tblRef.fname as refBy,
            tbl_temporary_sale.tbl_wareHouseId,  tbl_temporary_sale.referenceInfo
            FROM tbl_temporary_sale
            LEFT OUTER JOIN tbl_party ON tbl_temporary_sale.tbl_customerId = tbl_party.id
            LEFT OUTER JOIN tbl_users ON tbl_temporary_sale.createdBy = tbl_users.id
            LEFT JOIN tbl_users tblRef ON tblRef.id=tbl_temporary_sale.tbl_userId
            WHERE tbl_temporary_sale.id='".$id."'";
		
		$query = $conn->query($sql);
		while($row = $query->fetch_assoc()){
			$partyName=$row['partyName'];
			$tblCountry=$row['tblCountry'];
			$tblCity=$row['tblCity'];
			$locationArea=$row['locationArea'];
			$partyAddress=$row['partyAddress'];
			$partyCode=$row['partyCode'];
			$contactPerson=$row['contactPerson'];
			$partyPhone=$row['partyPhone']; 
			$fname=$row['fname'];
			$refBy=$row['refBy'];
			$inv_remarks = $row['inv_remarks'];
			$referenceInfo = $row['referenceInfo'];
			$tsNo=$row['tsNo'];
			$salesDate=$row['tSalesDate'];
			$paymentType=$row['paymentType'];
			$createdDate12=$row['createdDate'];
			$createdDate = date('Y-m-d h:i:s A', strtotime($createdDate12));
			
		}
    $content .= '<style>
            p{color:black;font-size: 8px;text-align:center;}
			.cities {background-color: gray;color: white;text-align: center;padding: 30px;}
			.citiestd {background-color: yellow;color: black;text-align: center;}
			.citiestd12 {font-size: 10px;}
			.citiestd13 {background-color: gray;color: white;text-align: center;font-size: 10px;padding: 30px;}
			.citiestd14 {text-align: right;font-size: 8px;}
			.citiestd15 {font-size: 8px;}
			.citiestd16 {text-align: right;font-size: 8px;}
			.citiestd17 {text-align: center;font-size: 8px;}
			.citiestd18 {text-align: center;font-size: 8px;}
			.citiestd19 {text-align: left;font-size: 8px;}
			.citiestd11 {text-align: center;font-size: 8px;}
			.citiestd20 {font-size: 7px;}
			.citiestd21 {text-align: center;font-size: 7px;}
			.citiestd22 {text-align: right;font-size: 7px;}
			span{font-size: 9px;}
			h2{font-size: 18px;text-align:center;}
		</style>';
		$image = 'images/companylogo/'.$imageT;    
        $pdf->Image($image, 85, 3,40, 15);
		$content .='<br>
		<p> '.$addressT.' Tel:'.$phoneT.' Mobile: '.$mobileT.'<br>E-mail:'.$emailT.'</p>
		<div class="citiestd13">TS Invoice Number: '.$tsNo.'</div>
		
		<table border="1" cellspacing="0" cellpadding="3">
			<tr>
				<td width="70%" class="supAddress">Customer Name :<font class="supAddressFont"><b>'.$partyName.'</b></font><br>Address :<font class="supAddressFont"> <b>'.$partyAddress.' '.$locationArea.' '.$tblCity.' '.$tblCountry.'</b></font><br>Contact Person :<font class="supAddressFont"> <b>'.$contactPerson.'</b></font> Phone :<font class="supAddressFont"> <b>'.$partyPhone.'</b></font></td>
				<td width="30%" style="border: 1px solid gray;font-size: 8px;" >Sales Date :<font><b>'.$salesDate.'</b></font><br>Entry Date :<font><b>'.$createdDate.'</b></font><br>Entry by :<font><b>'.$fname.'</b></font><br>Printed by :<font><b>'.$fname11.'</b></font></td>
			</tr>
			<tr><td colspan="2" class="supAddress">Reference : <font><b>'.$referenceInfo.'</b></font></td></tr>
			<tr>
				<td width="100%">Del. Date :<font color="gray"></font> Adj. Date :<font color="gray"></font> Ret. Date :<font color="gray"></td>
			</tr>
		</table> <br><br>
		<table border="1" cellspacing="0" cellpadding="3">
		
			<tr>
				<th class="citiestd11" width="5%">SL#</th>
				<th class="citiestd11" width="25%">Product Information</th>
				<th class="citiestd11" width="26%">Specification</th>
				<th class="citiestd11" width="10%">Quantity</th>
				<th class="citiestd11" width="8%">Unit Price</th>
				<th class="citiestd11" width="8%">Discount</th>
				<th class="citiestd11" width="8%">Dis. Price</th>
				<th class="citiestd11" width="10%">Total</th>
			</tr>';
			/*$sql = "SELECT  tbl_sales.salesOrderNo,tbl_transportInfo.transportName,tbl_transportInfo.contactPerson,tbl_transportInfo.contactNo,tbl_transportInfo.address,tbl_transportInfo.email, tbl_sales.salesDate, tbl_party.partyName, tbl_party.partyPhone, tbl_party.partyAddress, tbl_users.fname as soldBy, tbl_products.productName, tbl_products.modelNo,tbl_units.unitName, tbl_brands.brandName, SUM(tbl_sales_products.quantity) as quantity,
			tbl_sales_products.salesAmount, SUM(tbl_sales_products.totalAmount) as totalAmount, SUM(tbl_sales_products.remarks) as product_discount, SUM(tbl_sales_products.grandTotal) as grandTotal, tbl_sales_products.tbl_wareHouseId, tbl_sales.totalAmount AS salesTotalAmount, tbl_sales.productDiscount, tbl_sales.salesDiscount, 
			tbl_sales.grandTotal as salesGrandTotal, tbl_sales.vat, tbl_sales.ait,tbl_sales.carringCost, '' AS productSpecification, tbl_sales_products.tbl_productsId, tbl_discount_offer.offer_name,tbl_discount_offer.offer_for, tbl_discount_offer.unit_for,tbl_discount_offer.discount,tbl_discount_offer.discount_unit, tbl_discount_offer.discount_2, tbl_discount_offer.discount_unit_2
                    FROM tbl_sales 
                    LEFT OUTER JOIN tbl_party ON tbl_sales.tbl_customerId = tbl_party.id AND tbl_party.deleted='No'
                    LEFT OUTER JOIN tbl_users ON tbl_sales.tbl_userId=tbl_users.id AND tbl_users.deleted='No'
                    LEFT OUTER JOIN tbl_sales_products ON tbl_sales.id = tbl_sales_products.tbl_salesId AND tbl_sales_products.deleted='No'
                    LEFT OUTER JOIN tbl_discount_offer ON tbl_sales_products.tbl_discount_offer_id = tbl_discount_offer.id AND tbl_discount_offer.deleted='No'
                    LEFT OUTER JOIN tbl_products ON tbl_sales_products.tbl_productsId = tbl_products.id 
                    LEFT OUTER JOIN tbl_units ON tbl_units.id = tbl_products.units 
                    LEFT OUTER JOIN tbl_brands ON tbl_products.tbl_brandsId = tbl_brands.id
                    LEFT OUTER JOIN tbl_transportInfo ON tbl_transportInfo.id=tbl_sales.tbl_transport_info
                    WHERE tbl_sales.type = 'PartySale' AND tbl_sales.id='".$id."' AND tbl_sales.deleted = 'No'
                    group by tbl_sales_products.tbl_productsId,tbl_sales_products.salesAmount,floor(tbl_sales_products.remarks/tbl_sales_products.quantity)";*/			
			$sql = "SELECT tbl_temporary_sale.id, tbl_temporary_sale.tsNo,tbl_tsalesproducts.amount,tbl_tsalesproducts.saleAmount,tbl_temporary_sale.tSalesDate, tbl_party.partyName, 
			            tbl_party.tblCountry, tbl_party.tblCity, tbl_party.locationArea, tbl_party.partyAddress, tbl_party.partyCode, tbl_party.contactPerson, tbl_party.partyPhone, 
			            tbl_users.fname, tbl_temporary_sale.tbl_wareHouseId, dbt_partyDues.amount AS dues, SUM(tbl_tsalesproducts.quantity) as quantity, tbl_products.productName, tbl_products.productCode, 
			            tbl_products.modelNo, tbl_units.unitName, tbl_brands.brandName,tbl_discount_offer.offer_name,tbl_discount_offer.offer_for, tbl_discount_offer.unit_for,
			            tbl_discount_offer.discount,tbl_discount_offer.discount_unit, tbl_discount_offer.discount_2, tbl_discount_offer.discount_unit_2,tbl_tsalesproducts.discount as product_discount,
			            (tbl_tsalesproducts.remarks/tbl_tsalesproducts.quantity) as discountAmount
                    FROM tbl_temporary_sale
                    LEFT OUTER JOIN tbl_tsalesproducts ON tbl_tsalesproducts.tbl_tSalesId = tbl_temporary_sale.id AND tbl_tsalesproducts.deleted='No'
                    LEFT OUTER JOIN tbl_discount_offer ON tbl_tsalesproducts.tbl_discount_offer_id = tbl_discount_offer.id AND tbl_discount_offer.deleted='No'
                    LEFT OUTER JOIN tbl_products ON tbl_tsalesproducts.tbl_productsId = tbl_products.id
                    LEFT OUTER JOIN tbl_units ON tbl_products.units = tbl_units.id
                    LEFT OUTER JOIN tbl_party ON tbl_temporary_sale.tbl_customerId = tbl_party.id
                    LEFT OUTER JOIN tbl_users ON tbl_temporary_sale.tbl_userId = tbl_users.id
                    LEFT OUTER JOIN tbl_brands ON tbl_products.tbl_brandsId = tbl_brands.id
                    LEFT OUTER JOIN (SELECT Sum(CASE tbl_paymentVoucher.type 
                        WHEN 'partyPayable' THEN tbl_paymentVoucher.amount
                        WHEN 'paymentReceived' THEN -tbl_paymentVoucher.amount 
                        WHEN 'adjustment' THEN  tbl_paymentVoucher.amount
                        WHEN 'payable' THEN -tbl_paymentVoucher.amount 
                        WHEN 'payment' THEN tbl_paymentVoucher.amount
                        WHEN 'paymentAdjustment' THEN -tbl_paymentVoucher.amount 
            		END) AS amount, tbl_partyId 
            		FROM tbl_paymentVoucher 
            		WHERE deleted='No' AND status='Active' AND paymentDate < (SELECT tSalesDate FROM tbl_temporary_sale WHERE id='".$id."')) AS dbt_partyDues ON dbt_partyDues.tbl_partyId = tbl_party.id
                    WHERE tbl_temporary_sale.id='".$id."' AND tbl_temporary_sale.deleted='No'
                    group by tbl_tsalesproducts.tbl_productsId,tbl_tsalesproducts.amount,floor(tbl_tsalesproducts.remarks/tbl_tsalesproducts.quantity)";
		
		$query = $conn->query($sql);
		$weQuantity=0;
		$i=0;
		$weQuantitybu=0;
		$weQuantitybu12=0;
		$totalAmount = "";
		while($row12 = $query->fetch_assoc()){
		    $discount_2='';
			$productSpac = '';
		    $sql = "SELECT tbl_productspecification.specificationName, tbl_productspecification.specificationValue 
                    FROM tbl_productspecification
                    WHERE tbl_productspecification.tbl_productsId = '".$row12["tbl_productsId"]."' AND tbl_productspecification.deleted='No'";
            $query_spec = $conn->query($sql);
            while($row_spec = $query_spec->fetch_assoc()){
                $productSpac .= $row_spec['specificationName'].' : '.$row_spec['specificationValue'].',';
            }
			
			$i++;
			$unit=$row12['unitName'];
			$qty=$row12['quantity'];
			$totalQty+=$qty;
			$totalAmount+=$row12['amount'];
			$GrandTotalAmount=$qty*$row12['amount'];
			$GrandAmount+=$qty*$row12['amount'];
			
			$disPrice=($GrandTotalAmount/$row12['quantity']);
			
			if($row12['discount'] > 0 && $row12['discount'] != ""){
                $discount_2 = 'OFFER : <b>Buy</b> '.$row12['offer_for'].' '.$row12['unit_for'].' <b>Get</b> '.$row12['discount'].' '.$row12['discount_unit'];
                if($row12['discount_2'] > 0 && $row12['discount_2'] != ""){
                    $discount_2 .= ' and '.$row12['discount_2'].' '.$row12['discount_unit_2'];
                }
            }
			
			
			$content .= '<tr>
						<td class="citiestd21">'.$i.'</td>
						<td class="citiestd20">'.$row12['productName'].' - '.$row12['productCode'].'<br><span style="font-size:6px;">'.$discount_2.'</span></td>
						<td class="citiestd20">Brand : '.$row12['brandName'].'<br>Model : '.$row12['modelNo'].'<br>'.$productSpac.'</td>
		                <td class="citiestd19">'.$row12['quantity'].' '.$row12['unitName'].'</td>
		                <td class="citiestd22">'.$row12['saleAmount'].'</td>
		                <td class="citiestd22">'.number_format($row12['discountAmount'],2).'</td>
		                <td class="citiestd22">'.$disPrice.'</td>
		                <td class="citiestd22">'.number_format($GrandTotalAmount,2).'</td>
					</tr>
					';
					
			}	
			$content .= '
			<tr><td></td><td></td><td class="citiestd11" >Total = </td><td class="citiestd11">'.$totalQty.' </td><td></td><td></td><td class="citiestd22"></td><td class="citiestd22">'.number_format($GrandAmount,2).'</td></tr>
		</table><br><br>
		';
        
			$content .='
			
				<table>
					
					
				</table><br><br><br><br>
				<table>
					<tr>
						<th class="citiestd15"></th><td class="citiestd18"> '.$refBy.'</td><th class="citiestd17"></th><th class="citiestd16"></th>
						
					</tr>
					<tr>
						<th class="citiestd15">---------------------------</th><td class="citiestd18">---------------------------</td><th class="citiestd17">----------------</th><th class="citiestd16">----------------------------</th>
						
					</tr>
					<tr>
						<td class="citiestd15" > Customer Signature </td><td class="citiestd18">  Referecne By </td><td class="citiestd17"> Checked By </td><td class="citiestd16"> Authorized Signature </td>
						
					</tr>
					
				</table>';
		        if($inv_remarks!=''){
				    $content .='<br><b class="citiestd12" style="text-align:left; width:100%;">Remarks: '.$inv_remarks.' </b>';
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