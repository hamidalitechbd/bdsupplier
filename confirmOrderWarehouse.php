<?php 
    $conPrefix = '';
    include 'includes/session.php'; 
    include 'includes/header.php'; 
	if(isset($_GET['salesId']) && isset($_GET['salesType'])){
		$getSalesId=$_GET['salesId'];
		$getType=$_GET['salesType'];
	}else{
		header("Location: user-home.php");
	}
	ordersGoBackToPending();
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
      <h1>
        <?php
            if($getType=='OrderSales'){
                $pageHeader = 'Order Sales Check';
            }
            echo $pageHeader;
        ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="manage-view.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><?php
            if($getType=='OrderSales'){
                $pageHeader = 'Order Sales Check';
            }
            echo $pageHeader;
            
            $sql12 = "SELECT tbl_orders.id,tbl_orders.orderNo,tbl_orders.orderDate,tbl_orders.tbl_customerId,tbl_party.partyName,tbl_party.locationArea,tbl_party.tblCity 
                        FROM tbl_orders 
                        LEFT JOIN tbl_party ON tbl_party.id=tbl_orders.id AND tbl_party.status='Active'
                        WHERE tbl_orders.id='".$getSalesId."'";
            $result = $conn->query($sql12);
            while( $row = mysqli_fetch_array($result) ){
                $tbl_customerId = $row['tbl_customerId'];
                $tbl_orders= $row['orderNo'];
                $tbl_orderDate= $row['orderDate'];
            }
            
        ?></li>
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
				<form class="" id="form_salesReturn" method="POST" action="#">
					<div class="form-group">
					    <div class="col-sm-3">
							<label for="returnDate">Check Order Date:</label> 
							<input type="date" style="line-height: 10px;" class="form-control" id="returnDate" value="<?php echo date('Y-m-d');?>" <?php echo $saleDateDisable;?> name="returnDate" placeholder=" Sale Return Date ">
							<input type="hidden" value="<?php echo $_GET['salesId'];?>" name='orderId' id='orderId' />
						</div>
				    	<div class="col-sm-2">
						    <label for="chalanNumber">Order Code:</label> 
							<input type="text" class="form-control" id="salesCode" name="salesCode" value="<?php echo $tbl_orders; ?>" placeholder=" Sales Code " readonly />
						</div>
						<div class="col-sm-3">
							<label for="purchaseDate">Order Date:</label> 
							<input type="date" style="line-height: 10px;" class="form-control" id="salesDate" name="salseDate" value="<?php echo $tbl_orderDate; ?>" placeholder=" sales Date " readonly>
						</div>
						<div class="col-sm-4">
							<label for="add_supplier">Customer Name: </label> 
							<?php
			                    if($getType=='OrderSales'){
                                    echo '<select class="form-control" id="customerId" name="customerId"  style="width:100%;" disabled>';
                                    $sql = "SELECT id,partyName,tblCity,locationArea FROM `tbl_party` WHERE id='".$tbl_customerId."' AND status='Active' ORDER BY `id`  DESC";
                                    $result = $conn->query($sql);
                                    while( $row = mysqli_fetch_array($result) ){
                                        $partyName = $row['partyName'].' ('.$row['tblCity'].' - '.$row['locationArea'].')';
                                        echo '<option value="'.$row['id'].'">'.$partyName.'</option>';
                                    }
                                    echo '</select>';
                                }				
							?>
                            
						</div>
							
					    <div class="col-sm-4">
					        <label for="removeProductId">Removed Products</label>
					        <select id="removeProductId" name="removeProductId" class="form-control">
					            
					        </select>
					    </div>
					    <div class="col-sm-3">
					        <label for="productwiseWarehouseId">Warehouse</label>
					        <select id="productwiseWarehouseId" name="productwiseWarehouseId" class="form-control">
					            
					        </select>
					    </div>
					    <div class="col-sm-2">
					        <label for="splitQty">Remaining Qty</label>
					        <input type="text" id="deletedUnallocatedQty" name="deletedUnallocatedQty" class="form-control" Readonly/> 
					    </div>
					    <div class="col-sm-2">
					        <label for="splitQty">Qty</label>
					        <input type="text" id="splitQty" name="splitQty" class="form-control" /> 
					    </div>
					    <div class="col-sm-1">
					        <label></label>
					        <a href="#" class="btn btn-primary btn-flat form-control" onclick="saveOrderDetails()"><i class="fa fa-plus"></i> Add </a>
					    </div>
						<input type="hidden" style="line-height: 10px;" class="form-control" id="requisitionNo" name="requisitionNo" placeholder=" Invoice Number " readonly>
						<input type="hidden" style="line-height: 10px;" class="form-control" id="purchaseId" name="purchaseId" placeholder=" Hidden Purchase Id ">
						
					</div>
					<div class="form-group">
						<div class="col-sm-12"><br>
							<table id="orderSalesCheckTable" class="table table-bordered">
							    <thead>
                                    <th>SL</th>
                                    <th>Product Info</th>
                                    <th>Warehouse</th>
                                    <th>Stock Qty</th>
                                    <!--th>Quantity</th>
                                    <<th>Discount PC</th>
                                    <th>Total Qty</th>-->
                                    <th>Available Qty.</th>
                                    <th>Sales Amount</th>
                                    <th>After Discount</th>
                                    <!--th>Total Discount</th>
                                    <th>Total Amount</th-->
                                    <th>Action</th>
                                </thead>
							</table>
						</div>
					</div>
				<div class="form-group">
					<div class="col-sm-12">
					<!--button type="submit" class="btn btn-primary btn-flat" name="btn_salesReturn" id="btn_purchaseReturn"><i class="fa fa-save"></i> Update Order </button-->
					<button type="button" class="btn btn-primary btn-flat" name="btn_salesReturn" id="btn_confirmOrder"><i class="fa fa-save"></i> Confirm Order </button>
					</div>
				</div>
          </div>
          </form>
        </div>
      </div>
    </section>   
  </div>
  <?php include 'includes/footer.php'; ?>
</div>
<?php include 'includes/scripts.php'; ?>
<script src="dist/js/select2.min.js"></script>
<script src="includes/js/confirmOrderWarehouse.js"></script> 
<script type="text/javascript">
	//$("#returnDate").val(new Date().toISOString().substring(0, 10));
	var salesId = "<?php echo $getSalesId;?>";
	var salesType  = "<?php echo $getType;?>";
	//loadSales(salesId, salesType);
</script>
</body>
</html>
