<?php 
    $conPrefix = '';
    include 'includes/session.php'; 
    include 'includes/header.php';
?>
<body class="hold-transition skin-blue sidebar-mini">
<link rel="stylesheet" href="dist/css/select2.min.css" />
<div class="wrapper">
  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Check which stock is not correct</h1>
      <ol class="breadcrumb">
        <li><a href="manage-view.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><?php echo $pageHeader;?></li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
	  <link rel="stylesheet" href="css/buttons.dataTables.min.css"/>
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="">
				<div class="col-xs-6"><!--<h3><?php echo $pageHeader;?></h3>--></div>
				<div class="col-xs-6">
					<div id='divMsg' class='alert alert-success alert-dismissible successMessage'></div>
					<div id='divErrorMsg' class='alert alert-danger alert-dismissible errorMessage'></div>
				</div>
            </div>
            <div class="box-body">
					<div class="form-group">
						<div class="col-sm-12"><br>
						
							<table id="orderSalesCheckTable" class="table table-bordered" width="100%">
							    <thead>
                                    <th>SL</th>
                                    <th>Product Info</th>
                                    <th>Product Ledger Stock</th>
                                    <th>Current Stock</th>
                                </thead>
                                <tbody>
                                    <?php
            						    $sql12 = "SELECT SUM(tbl_currentStock.currentStock) AS currentStock, tbl_productsId 
                                                    FROM `tbl_currentStock` 
                                                    INNER JOIN tbl_products ON tbl_currentStock.tbl_productsId = tbl_products.id
                                                    WHERE tbl_currentStock.deleted='No' AND tbl_productsId > 4000   AND tbl_productsId <= 6000  AND tbl_products.deleted='No' AND tbl_products.status='Active'
                                                    GROUP BY tbl_productsId
                                                    ORDER BY tbl_productsId";
                                        $result = $conn->query($sql12);
                                        $sl = 1;
                                        while( $row = mysqli_fetch_array($result) ){
                                            $currentStock=$row['currentStock'];
                                            $spId = $row['tbl_productsId'];
                                            $sDate = '2022-05-27';
                                            $openingBalance = 0;
                                            $sql_itemStockLedger = "SELECT SUM(dbt.stockInQuantity)-SUM(dbt.stockOutQuantity) AS openingBalance, tbl_products.openStock    
                                                    FROM (SELECT SUM(tbl_purchaseProducts.quantity) as stockInQuantity, 0 as stockOutQuantity 
                                                    FROM tbl_purchaseProducts
                                                    LEFT OUTER JOIN tbl_purchase ON tbl_purchaseProducts.tbl_purchaseId = tbl_purchase.id
                                                    WHERE tbl_purchaseProducts.tbl_productsId='".$spId."' AND tbl_purchase.purchaseDate < '".$sDate."' AND tbl_purchaseProducts.deleted='No'
                                                    UNION ALL
                                                    SELECT 0 as stockInQuantity, SUM(tbl_purchase_product_return.quantity) as stockOutQuantity 
                                                    FROM tbl_purchase_product_return
                                                    LEFT OUTER JOIN tbl_purchase_return ON tbl_purchase_product_return.tbl_purchase_return_id = tbl_purchase_return.id
                                                    WHERE tbl_purchase_product_return.tbl_productsId='".$spId."' AND tbl_purchase_return.purchaseReturnDate < '".$sDate."' AND tbl_purchase_product_return.deleted = 'No'
                                                    UNION ALL
                                                    SELECT SUM(tbl_purchaseForeignProducts.quantity) as stockInQuantity, 0 as stockOutQuantity 
                                                    FROM tbl_purchaseForeignProducts
                                                    LEFT OUTER JOIN tbl_purchaseForeign ON tbl_purchaseForeignProducts.tbl_purchaseForeignId = tbl_purchaseForeign.id
                                                    WHERE tbl_purchaseForeignProducts.tbl_productsId='".$spId."' AND tbl_purchaseForeign.purchaseDate < '".$sDate."' AND tbl_purchaseForeignProducts.deleted='No'
                                                    UNION ALL
                                                    SELECT 0 as stockInQuantity, SUM(tbl_sales_products.quantity) as stockOutQuantity
                                                    FROM tbl_sales_products
                                                    LEFT OUTER JOIN tbl_sales ON tbl_sales_products.tbl_salesId = tbl_sales.id
                                                    WHERE tbl_sales_products.tbl_productsId='".$spId."' AND tbl_sales.type <> 'TS' AND tbl_sales.salesDate < '".$sDate."' AND tbl_sales_products.deleted = 'No'
                                                    UNION ALL
                                                    SELECT 0 as stockInQuantity, SUM(tbl_tsalesproducts.quantity) as stockOutQuantity
                                                    FROM tbl_tsalesproducts
                                                    LEFT OUTER JOIN tbl_temporary_sale ON tbl_tsalesproducts.tbl_tSalesId = tbl_temporary_sale.id
                                                    WHERE tbl_tsalesproducts.tbl_productsId='".$spId."' AND tbl_temporary_sale.tSalesDate < '".$sDate."' AND tbl_tsalesproducts.deleted='No'
                                                    UNION ALL
                                                    SELECT SUM(tbl_sales_product_return.quantity) as stockInQuantity, 0 as stockOutQuantity 
                                                    FROM tbl_sales_product_return
                                                    LEFT OUTER JOIN tbl_sales_return ON tbl_sales_product_return.tbl_sales_return_id = tbl_sales_return.id
                                                    WHERE tbl_sales_product_return.tbl_products_id='".$spId."' AND tbl_sales_return.returnDate < '".$sDate."' AND tbl_sales_product_return.deleted='No'
                                                    UNION ALL
                                                    SELECT 0 AS stockInQuantity, SUM(damageQuantity) AS stockOutQuantity 
                                                    FROM tbl_damageProducts 
                                                    WHERE deleted = 'No' AND tbl_productsId = '".$spId."' AND damageDate < '".$sDate."'
                                                    ) AS dbt
                                                    inner join tbl_products on tbl_products.id = '".$spId."'
                                                    where tbl_products.deleted = 'No' and tbl_products.status='Active'";
                                    			$query_itemStockLedger = $conn->query($sql_itemStockLedger);
                                    			$openingBalance = 0;
                                    		    while($row_itemStockLedger = $query_itemStockLedger->fetch_assoc()){
                                    			    $openingBalance=$row_itemStockLedger['openingBalance']+$row_itemStockLedger['openStock']; 
                                    			}
                                    			if($openingBalance != $currentStock){
                                        			echo '<tr>
                                                            <td>'.$sl++.'</td>
                                                            <td>'.$spId.'</td>
                                                            <td>'.$openingBalance.'</td>
                                                            <td>'.$currentStock.'</td>
                                                        </tr>';
                                                    if($sl == 51)
                                                        break;
                                    			}
                                        }
            						?>
                                    
                                </tbody>
							</table>
						</div>
					</div>
        </div>
      </div>
    </section>   
  </div>
  <?php include 'includes/footer.php'; ?>
</div>
<?php include 'includes/scripts.php'; ?>

</body>
</html>