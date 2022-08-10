<?php 
$conPrefix = '';
include 'includes/session.php'; ?>
<?php 
  include 'timezone.php'; 
  $today = date('Y-m-d');
  $year = date('Y');
  if(isset($_GET['year'])){
    $year = $_GET['year'];
  }
  $totalProducts = 0;
  $totalSuppliers = 0;
  $totalCustomers = 0;
  $totalBrands = 0;
  $totalWareHouse = 0;
  $todayPurchase = 0;
  $localPurchase = 0;
  $foreignPurchase = 0;
  $walkinSale = 0;
  $partySale = 0;
  $finalSale = 0;
  $temporarySale = 0;
  $sql = "Select count(id) as totalProducts from tbl_products where status='Active' AND deleted='No'";
  $result = $conn->query($sql);
  while($row=$result->fetch_assoc()){
      $totalProducts=$row['totalProducts'];
  }
  $sql = "SELECT count(id) as totalCustomersSuppliers,tblType from tbl_party WHERE status='Active' AND deleted='No' group By tblType";
  $result = $conn->query($sql);
  while($row=$result->fetch_assoc()){
      if($row['tblType'] <> "Suppliers"){
          $totalCustomers+=$row['totalCustomersSuppliers'];
      } 
      if($row['tblType'] <> "Customers"){
          $totalSuppliers+=$row['totalCustomersSuppliers'];
      }
  }
  $sql = "Select count(id) as totalBrands from tbl_brands where status='Active' AND deleted='No'";
  $result = $conn->query($sql);
  while($row=$result->fetch_assoc()){
      $totalBrands=$row['totalBrands'];
  }
  $sql = "Select count(id) as totalWareHouse from tbl_warehouse where status='Active' AND deleted='No'";
  $result = $conn->query($sql);
  while($row=$result->fetch_assoc()){
      $totalWareHouse=$row['totalWareHouse'];
  }
    $sql = "SELECT COUNT(tbl_purchase.purchaseOrderNo) AS noOfPurchase, 'LocalPurchase' as type
            FROM tbl_purchase
            WHERE tbl_purchase.deleted = 'No' AND tbl_purchase.purchaseDate = '".$today."'
            UNION
            SELECT COUNT(tbl_purchaseForeign.purchaseOrderNo) AS noOfPurchase, 'ForeignPurchase' as type
            FROM tbl_purchaseForeign
            WHERE tbl_purchaseForeign.deleted = 'No' AND tbl_purchaseForeign.purchaseDate = '".$today."'";
    $result = $conn->query($sql);
    while($row=$result->fetch_assoc()){
        if($row['type'] == "LocalPurchase"){
            $localPurchase = $row['noOfPurchase'];
        }else if($row['type'] == "ForeignPurchase"){
            $foreignPurchase = $row['noOfPurchase'];
        }
    }
    $sql = "SELECT COUNT(tbl_sales.salesOrderNo) AS noOfSales, tbl_sales.type
                FROM tbl_sales
                WHERE tbl_sales.deleted = 'No' AND tbl_sales.salesDate='$today'
                GROUP BY tbl_sales.type";
    $result = $conn->query($sql);
    while($row=$result->fetch_assoc()){
        if($row['type'] == "WalkinSale"){
            $walkinSale = $row['noOfSales'];
        }else if($row['type'] == "PartySale"){
            $partySale = $row['noOfSales'];
        }else if($row['type'] == "TS"){
            $finalSale = $row['noOfSales'];
        }
    }
    $sql = "SELECT COUNT(tsNo) AS noOfSales
            FROM tbl_temporary_sale 
            WHERE deleted = 'No' AND tSalesDate='$today'";
    $result = $conn->query($sql);
    while($row=$result->fetch_assoc()){
        $temporarySale = $row['noOfSales'];
        
    }
    $sql = "SELECT ifnull(SUM(tbl_damageProducts.damageQuantity),0) AS damageQuantity
            FROM tbl_damageProducts
            WHERE tbl_damageProducts.deleted = 'No'";
    $result = $conn->query($sql);
    while($row=$result->fetch_assoc()){
      $totalDamage=$row['damageQuantity'];
    }
?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-blue sidebar-mini">
    <style>
        #customers {font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;border-collapse: collapse;width: 100%;}
        #customers td, #customers th {border: 1px solid #ddd;padding: 8.3px;color: white;font-weight: 800;}
        #customers tr:nth-child(odd){background-color: #a4a4a4;}
        #customers tr:nth-child(even){background-color: #868686;}
        #customers tr:hover {background-color: #c1ab32;}
        #customers th {padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #4CAF50;color: white;}
        .container-fluid { margin-top: 100px}
        .bsp_row-underline {content: "";display: block;border-bottom: 2px solid #3798db; margin-bottom: 20px}
        .bsp_deal-text {margin-left: -10px;font-size: 25px;margin-bottom: 10px;color: #000;font-weight: 700}
        .bsp_view-all {margin-right: -10px;font-size: 14px; margin-top: 10px}
        .bsp_image {width: 100% !important;height: 95px !important;border: 1px solid #d9d7d7;border-radius: 5px;}
        .bsp_big-image { box-shadow: 1px 1px 5px 1px rgba(0, 0, 0, 0);border-radius: 5px; margin-top: 0px;}
        .bsp_padding-0 { padding: 3px}
        .bsp_bbb_item { padding: 5px;background-color: #fff;box-shadow: 1px 2px 2px 0px #3798db ;border-radius: 5px;}
        .bsp_card-text { color: blue}
        .textBox {width: 116px;height: 30px;overflow: hidden;padding: 5px;position: relative;color: #3798db;font-weight: 800; }
        .textBox1 {width: 116px;   height: 28px; overflow: hidden; padding: 4px;   position: relative; color: #30751d;}
        .textBox span, .textBox1 span{position: absolute;white-space: nowrap;transform: translateX(0);transition: 2s;}
        .textBox:hover span {transform: translateX(calc(125px - 120%));}
        .textBox1:hover span { transform: translateX(calc(125px - 120%)); }
    </style>
<div class="wrapper">

  	<?php include 'includes/navbar.php'; ?>
  	<?php include 'includes/menubar.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard :<span style="color: gray;"> <?php echo $user['fname'].' '.$user['lname']; ?> - <?php echo $_SESSION['userType']; ?></span>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-2 col-xs-4">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
				<p>Total Products</p>
				<h2><center><?php echo $totalProducts;?></center></h2>
            </div>
            
            <a href="manageItem-view.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        
        <!-- ./col -->
        <div class="col-lg-2 col-xs-4">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <p>Total Suppliers</p>
              <h2><center><?php echo $totalSuppliers; ?></center></h2>
            </div>
            
            <a href="manageCustomerSupplier-view.php?page=Suppliers" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-2 col-xs-4">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <p>Total Customers</p>
              <h2><center><?php echo $totalCustomers; ?></center></h2>
            </div>
            
            <a href="manageCustomerSupplier-view.php?page=Customers" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        
        <!-- ./col -->
        <div class="col-lg-2 col-xs-4">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <p>Total Brands</p>
              <h2><center><?php echo $totalBrands; ?></center></h2>
            </div>
            
            <a href="manage-view.php?page=Brand" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        
        
		<div class="col-lg-2 col-xs-4">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              

              <p>Total Warehouse</p>
              <h2><center><?php echo $totalWareHouse; ?></center></h2>
            </div>
            
            <a href="manage-view.php?page=Warehouse" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
		
		
		<div class="col-lg-2 col-xs-4">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <p>Total Damage</p>
              <h2><center><?php echo $totalDamage;?></center></h2>
            </div>
            
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        
        <div class="col-lg-2 col-xs-4">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <p>Today WI Sale</p>
              <h2><center><?php echo $walkinSale; ?></center></h2>
            </div>
            
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
		<div class="col-lg-2 col-xs-4">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <p>Today Party Sale</p>
              <h2><center><?php echo $partySale;?></center></h2>
            </div>
            
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        
        <div class="col-lg-2 col-xs-4">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <p>Today TS</p>
              <h2><center><?php echo $temporarySale; ?></center></h2>
            </div>
            
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
		<div class="col-lg-2 col-xs-4">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <p>Today FS</p>
              <h2><center><?php echo $finalSale;?></center></h2>
            </div>
            
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        
        <div class="col-lg-4 col-xs-6"></div>    
            <div class="col-lg-2 col-xs-4">
                  <!-- small box -->
                <div class="small-box bg-red">
                    <div class="inner">
                      <p>Local Purchase</p>
                      <h2><center><?php echo $localPurchase; ?></center></h2>
                    </div>
                    
                    <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
        	<div class="col-lg-2 col-xs-4">
                  <!-- small box -->
                  <div class="small-box bg-aqua">
                    <div class="inner">
                      <p>Total Import</p>
                      <h2><center><?php echo $foreignPurchase;?></center></h2>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                  </div>
                </div>
                
      
      
      <!-- /.row -->
      <div class="row">
        <div class="col-xs-12">
            <div class="col-xs-6">
                <div class="col-md-12">
                    <div class="row bsp_row-underline">
                        <div class="col-md-12"> <span class="pull-left bsp_deal-text">Quick Links</span> </div>
                    </div>
                    <div class="row">
                    <table id="customers">
                        <?php 
                        $reorderProducts = 0;$cashOut = 0; $cashIn = 0;$walkinCustomerreceivable=0;$partyreceivable=0;$partyPayable=0;
                        $sql = "SELECT COUNT(dbt_products.productCode) AS noOfReorderProducts
                                FROM 
                                (SELECT tbl_products.minimumStock, tbl_products.productName, tbl_products.productCode, tbl_products.productImage, tbl_products.id, CONCAT('<ul>', GROUP_CONCAT(CONCAT('<li>',tbl_productspecification.specificationName,' : ',tbl_productspecification.specificationValue) SEPARATOR '</li>') ,'</li></ul>') AS productSpecification
                                FROM tbl_products 
                                LEFT OUTER JOIN tbl_productspecification ON tbl_productspecification.tbl_productsId = tbl_products.id AND tbl_productspecification.deleted = 'No'
                                WHERE tbl_products.deleted = 'No'
                                GROUP BY tbl_products.id) AS dbt_products
                                LEFT OUTER JOIN (SELECT SUM(tbl_currentStock.currentStock) as totalStock, tbl_currentStock.tbl_productsId
                                FROM tbl_currentStock
                                INNER JOIN tbl_products ON tbl_currentStock.tbl_productsId = tbl_products.id AND tbl_products.deleted = 'No'
                                WHERE tbl_currentStock.deleted = 'No'
                                GROUP BY tbl_currentStock.tbl_productsId) AS dbt_productsStock ON dbt_products.id = dbt_productsStock.tbl_productsId
                                WHERE ifnull(dbt_products.minimumStock,0) > ifnull(dbt_productsStock.totalStock,0)
                                ORDER BY totalStock ASC";
                        $result = $conn->query($sql);
                        while($row = $result->fetch_assoc()){
                            $reorderProducts = $row['noOfReorderProducts'];
                        }
                        ?>
                        <tr>
                            <td><i class="fa fa-check-square-o"></i><a href="belowReorderLevelProductsPdfPrint.php" target="_blank" style="color:white;"> Below reorder level products</a></td>
                            <td><?php echo $reorderProducts;?></td>
                        </tr>
                        <?php 
                        $sql = "SELECT SUM(amount) AS amount, type
                                FROM tbl_paymentVoucher
                                WHERE deleted = 'No' AND (type = 'payment' OR type = 'paymentReceived') AND paymentDate = '$today'
                                GROUP by type";
                        $result = $conn->query($sql);
                        while($row = $result->fetch_assoc()){
                            if($row['type'] == "payment"){
                                $cashOut = $row["amount"];    
                            }else if($row['type'] == "paymentReceived"){
                                $cashIn = $row["amount"];       
                            }
                        }
                        ?>
                        <tr>
                            <td><i class="fa fa-check-square-o"></i><a href="dailyCashSalesLedgerPdfViewPrint.php?sDate=<?php echo date("Y-m-d");?>" target="_blank" style="color:white;"> Today Cash Out</a></td>
                            <td>&#2547; <?php echo $cashOut;?></td>
                        </tr>
                        <tr>
                            <td><i class="fa fa-check-square-o"></i><a href="dailyCashSalesLedgerPdfViewPrint.php?sDate=<?php echo date("Y-m-d");?>&eDate=<?php echo date("Y-m-d");?>&paymentMethod=All" target="_blank" style="color:white;"> Today Cash In</a></td>
                            <td>&#2547; <?php echo $cashIn;?></td>
                        </tr>
                        <?php 
                        $sql = "SELECT COUNT(tbl_partyId) as noOfParty, customerType
                                FROM(SELECT IFNULL(Sum(CASE tbl_paymentVoucher.type
                                                           WHEN 'partyPayable' THEN tbl_paymentVoucher.amount
                                                           WHEN 'paymentReceived' THEN -tbl_paymentVoucher.amount
                                                           WHEN 'adjustment' THEN  -tbl_paymentVoucher.amount
                                                           WHEN 'payable' THEN -tbl_paymentVoucher.amount
                                                           WHEN 'payment' THEN tbl_paymentVoucher.amount
                                                           WHEN 'paymentAdjustment' THEN tbl_paymentVoucher.amount
                                                           WHEN 'discount' THEN -tbl_paymentVoucher.amount
                                               END),0) AS total, tbl_partyId, customerType
                                                FROM tbl_paymentVoucher
                                                WHERE  deleted = 'No'
                                                GROUP BY tbl_partyId, customerType) AS dbt 
                                                WHERE dbt.total > 0
                                                GROUP BY customerType";
                        $result = $conn->query($sql);
                        while($row = $result->fetch_assoc()){
                            if($row['customerType'] == "WalkinCustomer"){
                                $walkinCustomerreceivable = $row["noOfParty"];    
                            }else if($row['customerType'] == "Party"){
                                $partyreceivable = $row["noOfParty"];       
                            }
                        }
                        $sql = "SELECT COUNT(tbl_partyId) as noOfParty
                                FROM(SELECT IFNULL(Sum(CASE tbl_paymentVoucher.type
                                                           WHEN 'partyPayable' THEN tbl_paymentVoucher.amount
                                                           WHEN 'paymentReceived' THEN -tbl_paymentVoucher.amount
                                                           WHEN 'adjustment' THEN  -tbl_paymentVoucher.amount
                                                           WHEN 'payable' THEN -tbl_paymentVoucher.amount
                                                           WHEN 'payment' THEN tbl_paymentVoucher.amount
                                                           WHEN 'paymentAdjustment' THEN tbl_paymentVoucher.amount
                                                           WHEN 'discount' THEN -tbl_paymentVoucher.amount
                                               END),0) AS total, tbl_partyId, customerType
                                                FROM tbl_paymentVoucher
                                                WHERE  deleted = 'No' and customerType = 'Party'
                                                GROUP BY tbl_partyId, customerType) AS dbt 
                                                WHERE dbt.total < 0";
                        $result = $conn->query($sql);
                        while($row = $result->fetch_assoc()){
                            $partyPayable = $row["noOfParty"];    
                        }
                        ?>
                        <tr>
                            <td><i class="fa fa-check-square-o"></i><!--a href="partyReceivablePdfPrint.php" target="_blank" style="color:white;"> Party receivable</a--><a href="#" style="color:white;"> Party receivable</a></td>
                            <td><?php echo $partyreceivable;?></td>
                        </tr>
                        <tr>
                            <td><i class="fa fa-check-square-o"></i><!--a href="partyPayablePdfPrint.php" target="_blank" style="color:white;"> Party payable</a--><a href="#"  style="color:white;"> Party payable</a></td>
                            <td><?php echo $partyPayable;?></td>
                        </tr>
                        <tr>
                            <td><i class="fa fa-check-square-o"></i><a href="walkinCustomerReceivablePdfPrint.php" target="_blank" style="color:white;"> Walkin customer receivable</a></td>
                            <td><?php echo $walkinCustomerreceivable;?></td>
                        </tr>
                        
                    </table>
                    </div>
                </div>
            </div>
                <div class="col-xs-6">
                    <div class="col-md-12">
                        <div class="row bsp_row-underline">
                            <div class="col-md-12"> <span class="pull-left bsp_deal-text">Best Selling Products</span> </div>
                        </div>
                        <div class="row">
                            <?php 
                                $sql = "select * From(SELECT SUM(tbl_sales_products.quantity) AS noOfProducts, tbl_products.productName,tbl_products.productCode, tbl_units.unitName, tbl_brands.brandName, tbl_products.modelNo, tbl_products.productImage
                                        FROM tbl_sales_products
                                        INNER JOIN tbl_products ON tbl_sales_products.tbl_productsId = tbl_products.id AND tbl_products.deleted = 'No' AND tbl_products.status = 'Active'
                                        LEFT OUTER JOIN tbl_units ON tbl_products.units = tbl_units.id
                                        LEFT OUTER JOIN tbl_brands ON tbl_products.tbl_brandsId = tbl_brands.id
                                        WHERE tbl_sales_products.deleted = 'No'
                                        GROUP BY tbl_sales_products.tbl_productsId
                                        ORDER BY noOfProducts DESC LIMIT 100) as dbt
                                        ORDER BY RAND() limit 4";
                                $result = $conn->query($sql);
                                while($rows = $result->fetch_assoc()){
                                    $productName = $rows['productName'];
                                    $productCode = $rows['productCode'];
                                    if($rows['productImage'] == ""){
                                        $productImage = 'images/broken_image.png';
                                    }else{
                                        $productImage = 'images/products/thumb/'.$rows['productImage'];
                                    }
                                    $modelNo = $rows['productCode'];
                                    $brandName = $rows['brandName'];
                                    $noOfProducts = $rows['noOfProducts'];
                                    $units = $rows['unitName'];
                                    echo "<div class='col-md-3 bsp_padding-0'>
                                            <div class='bsp_bbb_item'> <img src='$productImage' class='bsp_image'>
                                                <ul class='list-group' style='margin-bottom: 1%;'>
                                                    <li class='list-group-item bsp_card-text textBox'><span>$productName - $productCode</span></li>
                                                    <li class='list-group-item textBox1'><span>Brand: $brandName</span></li>
                                                    <li class='list-group-item textBox1'><span>Model: $modelNo</span></li>
                                                    <li class='list-group-item textBox'><span>Sold : $noOfProducts $units</span></li>
                                                </ul>
                                            </div>
                                        </div>";
                                }
                            
                            ?>
                        </div>
                    </div>
                        
                </div>
        </div>
    </div>

      </section>
      <!-- right col -->
    </div>
  	<?php include 'includes/footer.php'; ?>

</div>
<!-- ./wrapper -->

<!-- Chart Data -->
<?php
  $and = 'AND YEAR(distribute_dateTime) = '.$year;
  $months = array();
  $ontime = array();
  $late = array();
  for( $m = 1; $m <= 12; $m++ ) {
    //$sql = "SELECT * FROM tbl_users WHERE MONTH(distribute_dateTime) = '$m' AND re_status !='' $and";
    //$oquery = $conn->query($sql);
    //array_push($ontime, $oquery->num_rows);

    //$sql = "SELECT * FROM tbl_users WHERE MONTH(distribute_dateTime) = '$m' AND re_status ='' $and";
    //$lquery = $conn->query($sql);
    //array_push($late, $lquery->num_rows);

    //$num = str_pad( $m, 2, 0, STR_PAD_LEFT );
   // $month =  date('M', mktime(0, 0, 0, $m, 1));
    //array_push($months, $month);
  }

  $months = json_encode($months);
  $late = json_encode($late);
  $ontime = json_encode($ontime);

?>
<!-- End Chart Data -->
<?php include 'includes/scripts.php'; include 'includes/productAdvanceSearch-modalMenue.php';?>
<script>
$(function(){
  var barChartCanvas = $('#barChart').get(0).getContext('2d')
  var barChart = new Chart(barChartCanvas)
  var barChartData = {
    labels  : <?php echo $months; ?>,
    datasets: [
      {
        label               : 'Distribute',
        fillColor           : 'rgba(210, 214, 222, 1)',
        strokeColor         : 'rgba(210, 214, 222, 1)',
        pointColor          : 'rgba(210, 214, 222, 1)',
        pointStrokeColor    : '#c1c7d1',
        pointHighlightFill  : '#fff',
        pointHighlightStroke: 'rgba(220,220,220,1)',
        data                : <?php echo $late; ?>
      },
      {
        label               : 'Adjustment',
        fillColor           : 'rgba(60,141,188,0.9)',
        strokeColor         : 'rgba(60,141,188,0.8)',
        pointColor          : '#3b8bba',
        pointStrokeColor    : 'rgba(60,141,188,1)',
        pointHighlightFill  : '#fff',
        pointHighlightStroke: 'rgba(60,141,188,1)',
        data                : <?php echo $ontime; ?>
      }
    ]
  }
  barChartData.datasets[1].fillColor   = '#00a65a'
  barChartData.datasets[1].strokeColor = '#00a65a'
  barChartData.datasets[1].pointColor  = '#00a65a'
  var barChartOptions                  = {
    //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
    scaleBeginAtZero        : true,
    //Boolean - Whether grid lines are shown across the chart
    scaleShowGridLines      : true,
    //String - Colour of the grid lines
    scaleGridLineColor      : 'rgba(0,0,0,.05)',
    //Number - Width of the grid lines
    scaleGridLineWidth      : 1,
    //Boolean - Whether to show horizontal lines (except X axis)
    scaleShowHorizontalLines: true,
    //Boolean - Whether to show vertical lines (except Y axis)
    scaleShowVerticalLines  : true,
    //Boolean - If there is a stroke on each bar
    barShowStroke           : true,
    //Number - Pixel width of the bar stroke
    barStrokeWidth          : 2,
    //Number - Spacing between each of the X value sets
    barValueSpacing         : 5,
    //Number - Spacing between data sets within X values
    barDatasetSpacing       : 1,
    //String - A legend template
    legendTemplate          : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].fillColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
    //Boolean - whether to make the chart responsive
    responsive              : true,
    maintainAspectRatio     : true
  }

  barChartOptions.datasetFill = false
  var myChart = barChart.Bar(barChartData, barChartOptions)
  document.getElementById('legend').innerHTML = myChart.generateLegend();
});
</script>
<script>
$(function(){
  $('#select_year').change(function(){
    window.location.href = 'home.php?year='+$(this).val();
  });
});
</script>
</body>
</html>
