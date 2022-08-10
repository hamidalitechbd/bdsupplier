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
            
            $sql12 = "SELECT tbl_orders.id,tbl_orders.orderNo,tbl_orders.tbl_customerId,tbl_party.partyName,tbl_party.locationArea,tbl_party.tblCity, tbl_orders.orderDate, tbl_orders.grandTotal 
                        FROM tbl_orders 
                        LEFT JOIN tbl_party ON tbl_party.id=tbl_orders.id AND tbl_party.status='Active'
                        WHERE tbl_orders.id='".$getSalesId."'";
            $result = $conn->query($sql12);
            while( $row = mysqli_fetch_array($result) ){
                $tbl_customerId = $row['tbl_customerId'];
                $tbl_orders= $row['orderNo'];
                $orderDate = $row['orderDate'];
                $grandTotal = $row['grandTotal'];
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
				<form class="" id="form_orderConfirm" method="POST" action="#">
					<div class="form-group">
					    <div class="col-sm-4">
							<label for="returnDate">Confirm Order Date:</label> 
							<input type="date" style="line-height: 10px;" class="form-control" id="confirmOrderDate" value="<?php echo date('Y-m-d');?>" <?php echo $saleDateDisable;?> name="returnDate" placeholder=" Sale Return Date ">
							<input type="hidden" value="<?php echo $_GET['salesId'];?>" name='orderId' id='orderId' />
						</div>
				    	<div class="col-sm-4">
						    <label for="chalanNumber">Order Code:</label> 
							<input type="text" class="form-control" id="salesCode" name="salesCode" value="<?php echo $tbl_orders; ?>" placeholder=" Sales Code " readonly />
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
					</div>
					<div class="form-group">
						<!--<div class="col-sm-4">
							<label for="chalanNo">Requisition No </label> 
                            <input type="text" style="line-height: 10px;" class="form-control" id="requisitionNo" name="requisitionNo" placeholder=" Invoice Number " readonly>
						</div>-->
						<div class="col-sm-4">
							<label for="purchaseDate">Order Date:</label> 
							<input type="date" style="line-height: 10px;" class="form-control" id="salesDate" name="salseDate" value=<?php echo $orderDate;?> placeholder=" sales Date " readonly>
						</div>
						<input type="hidden" style="line-height: 10px;" class="form-control" id="purchaseId" name="purchaseId" placeholder=" Hidden Purchase Id ">
					</div>
					<div class="form-group">
					    <div class="col-sm-4">
							<label for="chalanNo">Total Amount </label> 
                            <input type="text" style="line-height: 10px;" class="form-control" id="totalAmount" name="totalAmount" value=<?php echo $grandTotal;?> placeholder=" Total Amount " readonly>
						</div>
						<div class="col-sm-4">
							<label for="purchaseDate">Account Number:</label> 
							<select class="form-control accountNo" name="accountNo" id="accountNo" onchange="updateCheckBox(this)">
    							<option value="" selected>~~ Account Number ~~</option>
    							<?php
    							$sql = "SELECT id, accountNo, accountName, bankName, branchName 
                                        FROM tbl_bank_account_info 
                                        WHERE status = 'Active' AND deleted = 'No'
                                        ORDER BY id DESC";
                                $query = $conn->query($sql);
    							while ($prow = $query->fetch_assoc()) {
    								echo "<option value='" . $prow['id'] . "'>".$prow['bankName']." - ".$prow['accountName']."</option>";
    							}
    							?>
    						</select>
						</div>
						<div class="col-sm-1">
						    <label></label>
							<div class="checkbox">
                                <label><input type="checkbox" value="2" name="BKashId" id="BKashId" > BKash </label>
                            </div>
						</div>	
						<div class="col-sm-3">
							<label for="purchaseDate">Reference Number:</label> 
							<input type="text" style="line-height: 10px;" class="form-control" id="bankRferenceNumber" name="bankRferenceNumber" placeholder=" Reference Number ">
						</div>	
						<div class="col-sm-4">
							<label for="purchaseDate">Advance Amount:</label> 
							<input type="text" style="line-height: 10px;" class="form-control" id="advanceAmount" name="advanceAmount" placeholder=" Advance Amount ">
						</div>	
						<div class="col-sm-4">
							<label for="purchaseDate">Transport:</label> 
						        <select class="form-control transportName" id="transportName" style="width:100%;">
								    <option value="" selected>~~ Select Transport ~~</option>
									<?php
									    $sql = "SELECT id, transportName, contactPerson, contactNo 
                                                FROM tbl_transportInfo 
                                                WHERE deleted = 'No' AND status='Active'";
                                        $res = $conn->query($sql);
                                        while($row = $res->fetch_assoc()){
                                            echo '<option value="'.$row['id'].'">'.$row['transportName'].' ('.$row['contactPerson'].'-'.$row['contactNo'].')</option>';
                                        }
									?>
								</select>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-12"><br>
							<table id="orderSalesCheckTable" class="table table-bordered" width="100%">
							    <thead>
                                    <th>SL</th>
                                    <th>Product Info</th>
                                    <th>Available</th>
                                    <th>QTY</th>
                                    <th>Discount PC</th>
                                    <th>Total Qty</th>
                                    <!--<th>Available Qty</th>
                                    <th>Sales Amount</th>-->
                                    <th>Updated Amount</th>
                                    <th>Offer Discount</th>
                                    <th>Total Amount</th>
                                </thead>
							</table>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-12">
						    <a href="orderList.php?page=Checked" class="btn btn-primary btn-flat"><i class="fa fa-mail-reply"></i> Back </a>
						    <button type="button" class="btn btn-primary btn-flat" name="btn_salesReturn" id="btn_salesOrderConfirm"><i class="fa fa-save"></i> Confirm Order </button>
						    <a  style="width: 20%;box-shadow: 1px 1px 1px 0px #909090;" id="discountPreview" href="#" class="btn btn-default" onclick=discountOfferPreview()>
							    <span class="glyphicon glyphicon-th-list" style="color: #000cbd;"></span> Preview
							</a>
						
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
<?php include 'includes/scripts.php'; 
include 'includes/previousProducts-modal.php';?>
<script src="dist/js/select2.min.js"></script>
<script src="includes/js/checkConfirmList.js"></script> 
<script type="text/javascript">
	//$("#returnDate").val(new Date().toISOString().substring(0, 10));
	var salesId = "<?php echo $getSalesId;?>";
	var salesType  = "<?php echo $getType;?>";
	//loadSales(salesId, salesType);
	
	$("#BKashId").click(function () {
	    if (jQuery(this).prop("checked")) {
            jQuery("#accountNo")
                .prop("disabled", this)
                .val('', this)
            ;
        } else {
            jQuery("#accountNo")
                .prop("disabled", false)
                .val('')
            ;
        }
    });

    function updateCheckBox(opts) {
        var chks = document.getElementsByName("BKashId");
         if (opts.value == '') {
            for (var i = 0; i <= chks.length - 1; i++) {
                chks[i].disabled = false;
            }
        }
        else {
        for (var i = 0; i <= chks.length - 1; i++) {
                chks[i].disabled = true;
                chks[i].checked = false;
            }
        }
    }
	
	
</script>
</body>
</html>