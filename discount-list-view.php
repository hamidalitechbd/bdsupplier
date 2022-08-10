<?php 
	$conPrefix = '';
    include 'includes/session.php'; 
    include 'includes/header.php'; 
    include 'timezone.php'; 
    $today = date('Y-m-d');
?>
<body class="hold-transition skin-blue sidebar-mini">
<link rel="stylesheet" href="dist/css/select2.min.css" />
<div class="wrapper">

  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <?php
        
    ?>
    <section class="content-header">
      <h1>Discount Offer List</h1>
      <ol class="breadcrumb">
        <li><a href="manageUser-view.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Discount Offer List</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
	  <link rel="stylesheet" href="css/buttons.dataTables.min.css"/>
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border">
    			<div class="col-xs-6">
    				<div id='divMsg' class='alert alert-success alert-dismissible successMessage'></div>
    			</div>
			</div>
            <div class="box-body">
              <table id="manageUserTable" class="table table-bordered">
                <tr>
                    <th>SL#</th>
                    <th>Date</th>
                    <th>Offer Name</th>
                    <th>Offer For</th>
                    <th>Product Informatin</th>
                    <th>Offer Details</th>
                </tr>
                <?php 
                        $reorderProducts = 0;
                        $sql = "SELECT tbl_discount_offer.*,tbl_products.productName,tbl_products.modelNo,tbl_products.productCode,tbl_discount_offer.status,SUM(tbl_currentStock.currentStock) AS totalStock
                                FROM `tbl_discount_offer` 
                                INNER JOIN tbl_products ON tbl_products.id=tbl_discount_offer.tbl_products_id
                                INNER JOIN tbl_currentStock ON tbl_currentStock.tbl_productsId=tbl_products.id
                                WHERE tbl_discount_offer.deleted='No' AND tbl_discount_offer.status='Active' AND tbl_discount_offer.date_to >= '$today'
                                GROUP BY tbl_discount_offer.id ORDER BY `tbl_discount_offer`.`offer_applicable`  DESC";
                        $result = $conn->query($sql);
                        $i=1;
                        while($row = $result->fetch_assoc()){
                           
                            echo '<tr>
                                    <td>'.$i++.'</td>
                                    <td class="" style="width: 11%;">Start :'.$row['date_from'].'<br>End :'.$row['date_to'].'</td>
                                    <td class="">'.$row['offer_name'].'</td>
                                    <td class="">'.$row['offer_applicable'].'</td>
                                    <td class="">'.$row['productName'].' '.$row['productCode'].'<br>Model :'.$row['modelNo'].'<br>Total Stock : '.$row['totalStock'].'</td>
                                    <td class="">'.$row['offer_for'].'  '.$row['unit_for'].' & '.$row['discount'].'  '.$row['discount_unit'].'</td>
                                </tr>';
                        }
                        ?>
                        
                </table>
            </div>
          </div>
        </div>
      </div>
    </section>   
    
  </div>
   
  <?php include 'includes/footer.php'; ?>
</div>
<script src="notify.js"></script>
<?php include 'includes/scripts.php'; ?>
</body>
</html>
