<?php 
    $conPrefix = '';
    include 'includes/session.php'; 
    include 'includes/header.php'; 
	if(isset($_GET['purid'])){
		$getPurchaseCode=$_GET['purid'];
	}else{
		$getPurchaseCode = '';
	}
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
      <h1>
        Purchase Return
      </h1>
      <ol class="breadcrumb">
        <li><a href="manage-view.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Purchase Return</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
      
	  <link rel="stylesheet" href="css/buttons.dataTables.min.css"/>
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border">
				<!--<div class="col-xs-6"><h3><input type="checkbox" value="" checked> Search by purchase code</h3></div>-->
				<div class="col-xs-6"></div>
				<div class="col-xs-6">
					<div id='divMsg' class='alert alert-success alert-dismissible successMessage'></div>
					<div id='divErrorMsg' class='alert alert-danger alert-dismissible errorMessage'></div>
				</div>
            </div>
            <div class="box-body">
				<form class="form-horizontal" id="form_purchaseReturn" method="POST" action="#">
					<div class="form-group">
						<div class="col-sm-3">
							<label for="purchaseDate">Return Date:</label> 
							<input type="date" style="line-height: 10px;" class="form-control" id="returnDate" name="returnDate" placeholder=" Purchase Date " readonly>
						</div>
						<div class="col-sm-3">
							<label for="add_supplier">Supplier Name: </label> 
                            <select class="form-control" id="supplierId" name="supplier"  style="width:100%;" disabled>
                                
							</select>
						</div>
						<div class="col-sm-3">
						<label for="chalanNumber">Purchase Code:</label> 
							<input type="text" class="form-control" id="purchaseCode" name="purchaseCode" placeholder=" Purchase Code " onblur="loadPurchase()" readonly>
						</div>
						<div class="col-sm-3">
							<label for="purchaseDate">Purchase Date:</label> 
							<input type="date" style="line-height: 10px;" class="form-control" id="purchaseDate" name="purchaseDate" placeholder=" Purchase Date " readonly>
						</div>
						
					</div>
					
					<div class="form-group">
						<div class="col-sm-3">
							<label for="chalanNo">Memo No </label> 
                            <input type="text" style="line-height: 10px;" class="form-control" id="chalanNo" name="chalanNo" placeholder=" Memo Number " readonly>
						</div>
						<div class="col-sm-3">
							<label for="purchaseId"></label> 
                            <input type="hidden" style="line-height: 10px;" class="form-control" id="purchaseId" name="purchaseId" placeholder=" Hidden Purchase Id " >
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-12">
							<table id="managePurchaseProductTable" class="table table-bordered">
								
							</table>
						</div>
					</div>
					<!--<div class="form-group">
						<div class="col-sm-7"></div>
						<div class="col-sm-5">
						<label for="add_grandTotal">Grand Total</label> 
							<input type="text" class="form-control" id="grandTotal" name="grandTotal" readonly>
						
						<label for="add_paid">Paid </label> 
							<input type="text" class="form-control" id="paidAmount" name="paid" required>
						
						<label for="add_due">Due </label> 
							<input type="text" class="form-control" id="dueAmount" name="due" required>
					    </div>
					</div>-->
					<div class="form-group">
						<div class="col-sm-12">
						<button type="submit" class="btn btn-primary btn-flat" name="btn_purchaseReturn" id="btn_purchaseReturn"><i class="fa fa-save"></i> Return Purchase </button>
						<a href="purchaseLocalViewreturn.php" class="btn btn-primary btn-flat"><i class="fa fa-mail-reply"></i> Back </a>
						</div>
					</div>
				</form>
          </div>
        </div>
      </div>
    </section>   
    
  </div>
   
  <?php 
		include 'includes/footer.php'; 
		include 'includes/saleSerializeProductReturn-modal.php';
  ?>
</div>
<?php include 'includes/scripts.php'; ?>


<script src="dist/js/select2.min.js"></script>
<script src="includes/js/managePurchaseLocalReturn.js"></script> 
<script type="text/javascript">
	$("#returnDate").val(new Date().toISOString().substring(0, 10));
	var purchaseCode = "<?php echo $getPurchaseCode;?>";
	if(purchaseCode.length > 5){
		$("#purchaseCode").val(purchaseCode);
		loadPurchase();
	}
</script>
</body>
</html>