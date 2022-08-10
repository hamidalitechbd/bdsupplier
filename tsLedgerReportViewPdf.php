<?php include 'includes/session.php'; ?>
<?php
if(isset($_POST['startDate']))
//if(isset($_POST['EmpName']))
{
	$tsId=$_POST['cName'];
	//$sDate=$_POST['startDate'];
//	$eDate=$_POST['endtDate'];
	//$EmpName=$_POST['EmpName'];
?>
<style>
#customers {border-collapse: collapse;table-layout:auto;width: 100%;}
#customers td, #customers th {border: 1px solid #ddd;padding: 8px;}
#customers tr:nth-child(even){background-color: #f2f2f2;}
#customers tr:hover {background-color: #ddd;}
</style>
	<br><br>
	<div class="table-responsive"> 
        <table class="table table-bordered" id="customers">
        <thead>
		<tr style="background: #3f3e93;color: white;">
			<th class="hidden"></th>
			<th>Date</th>
			<th>TS#</th>
			<th>Products</th>
			<th>Specification</th>
			<th>Quantity</th>
			<th>Ret Qty</th>
			<th>Sold Qty</th>
			<th>Rem Qty</th>
		</tr>
        </thead>
        <tbody>
          <?php
			$sql = "SELECT tbl_temporary_sale.tsNo,tbl_tsalesproducts.quantity, tbl_tsalesproducts.returnedQuantity, tbl_tsalesproducts.soldQuantity, tbl_temporary_sale.tsNo, tbl_temporary_sale.tSalesDate, tbl_products.productName, tbl_products.productCode, tbl_products.modelNo, tbl_brands.brandName, tbl_units.unitName, CONCAT('<ul>', GROUP_CONCAT(CONCAT('<li>',tbl_productspecification.specificationName, ': ', tbl_productspecification.specificationValue) SEPARATOR '</li>') ,'</li></ul>') AS productSpeficiations, ifnull(tbl_tsalesproducts.quantity,0)-ifnull(tbl_tsalesproducts.returnedQuantity,0)-ifnull(tbl_tsalesproducts.soldQuantity, 0) as remainingQuantity  
                    FROM tbl_tsalesproducts 
                    LEFT OUTER JOIN tbl_temporary_sale ON tbl_temporary_sale.id = tbl_tsalesproducts.tbl_tSalesId AND tbl_temporary_sale.deleted = 'No'
                    LEFT OUTER JOIN tbl_products ON tbl_tsalesproducts.tbl_productsId = tbl_products.id
                    LEFT OUTER JOIN tbl_productspecification ON tbl_productspecification.tbl_productsId = tbl_products.id AND tbl_productspecification.deleted = 'No'
                    LEFT OUTER JOIN tbl_units ON tbl_products.units = tbl_units.id
                    LEFT OUTER JOIN tbl_brands ON tbl_products.tbl_brandsId = tbl_brands.id
                    WHERE tbl_tsalesproducts.deleted = 'No' AND tbl_tsalesproducts.status = 'Running' AND tbl_temporary_sale.tbl_customerId='".$tsId."'
                    GROUP BY tbl_tsalesproducts.id
                    ORDER BY tbl_temporary_sale.tbl_customerId, tbl_temporary_sale.tSalesDate";
            $query = $conn->query($sql);
			$idNo=1;
            while($row12 = $query->fetch_assoc()){
				//$fid=$row['firm_id'];
				//$pcnid=$row['pcn_no'];
				//$pcnid=$row['we_product_quantity']-$row['re_weQuantity'];
				//$pcnid12=$row['bu_product_quantity']-$row['re_buQuantity'];
			//	$image_name="<img src='images/products/thumb/".$row['productImage']."' width='30%'>";
				//$did12+=$did;
				
				$type=$row12['type'];
			
              echo "
                <tr>
					<td class='hidden'></td>
					<td>".$row12['tSalesDate']."</td>
					<td>".$row12['tsNo']."</td>
					<td style='text-align: left;'>".$row12['productName']." <br>Brand: ".$row12['brandName']."<br>Model: ".$row12['modelNo']."</td>
				    <td style='text-align: left;'>".$row12['productSpeficiations']."</td>
					<td>".$row12['quantity']." ".$row12['unitName']."</td>
					<td>".$row12['returnedQuantity']." ".$row12['unitName']."</td>
					<td>".$row12['soldQuantity']." ".$row12['unitName']."</td>
					<td>".$row12['remainingQuantity']." ".$row12['unitName']."</td>
					
                </tr>
				";
            }
			echo "
			<a href='tsSalesLedgerReportViewPdfPrint.php?tsId=".$tsId."' target='_blank' title='Issue Details' data-toggle='tooltip' class='btn btn-primary btn-sm btn-flat' style='margin-left: 1%; background: white;color: blue;margin-bottom: 1%;'><i class='fa fa-print'> Ts Sales ledger Reports Print </i></a>
			";
		  ?>
        </tbody>
    </table></div>
<?php } ?>