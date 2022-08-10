<?php 
    $conPrefix = '';
    include 'includes/session.php'; 
    include 'includes/header.php'; 
    $sessionId = time().uniqid();
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
        Purchase Local
      </h1>
      <ol class="breadcrumb">
        <li><a href="manage-view.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Purchase Local</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
	  <link rel="stylesheet" href="css/buttons.dataTables.min.css"/>
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border">
				<div class="col-xs-6"></div>
				<div class="col-xs-6">
					<div id='divMsg' class='alert alert-success alert-dismissible successMessage'></div>
					<div id='divErrorMsg' class='alert alert-danger alert-dismissible errorMessage'></div>
				</div>
            </div>
            <div class="box-body">
				<form class="form-horizontal" id="form_addPurchase" method="POST" action="#">
				    <div class="row">
					<div class="col-md-12">
    					<div class="form-group">
    						<div class="col-md-4">
        					    <?php
        				            $maxDate = date("Y-m-d");
        				            if(strtolower($_SESSION['userType']) == 'admin' || strtolower($_SESSION['userType']) == 'super admin'){
        				                $minDate = date('Y-m-d',strtotime("-60 days"));    
        				            }else{
        				                $minDate = $maxDate; 
        				            }
        				        ?>
    						    <label for="purchaseDate">Purchase Date</label> 
    							<input type="date" style="line-height: 10px;" class="form-control" value="<?php echo $maxDate;?>" min="<?php echo $minDate;?>" max="<?php echo $maxDate;?>" id="add_purchaseDate" name="purchaseDate" placeholder=" Purchase Date ">
    							<input type="hidden" class="form-control" id="edit_purchaseId"  name="purchaseId">
    						</div>
    						<div class="col-md-4">
    							<label for="add_supplier">Supplier </label> 
                                <!--div class="input-group"-->
    							<select class="form-control" id="add_supplier" name="supplier"  style="width:100%;" required>
    							</select>
    							<!--data-toggle="modal" data-target="#supplierModal"
    								<a class="input-group-addon" onclick="addSupplier('Suppliers')" style="background-color:#5307ae;color:#fff;border-radius: 0px 8px 8px 0px;box-shadow: -5px 0px 0px 0px #5307ae;border: 0px solid #5307ae;"><i class="fa fa-plus"></i></a> 
    							 </div>-->
    						</div>
    						<div class="col-md-4">
    						<label for="chalanNumber">Memo Number</label> 
    							<input type="text" class="form-control" id="add_chalanNumber" name="chalanNumber" placeholder=" Chalan Number" maxlength="13">
    						</div>
    					</div>
    					<div class="form-group">
    						<div class="col-md-6">
    							<label for="products">Product Name </label> 
    							<div class="input-group">
                                <select class="form-control" id="add_products" name="products"  style="width:100%;">
                                    <option value="" selected>~~ Select Product Name ~~</option>
                                    <?php
                                    $sql = "SELECT tbl_products.id,tbl_products.productName,tbl_products.productCode,tbl_brands.brandName, tbl_products.modelNo
    										FROM tbl_products 
    										LEFT OUTER JOIN tbl_brands ON tbl_brands.id=tbl_products.tbl_brandsId
    										WHERE tbl_products.status='Active' AND tbl_products.deleted='No'
    										ORDER BY tbl_products.id  DESC";
                                    $query = $conn->query($sql);
                                    while ($prow = $query->fetch_assoc()) {
                                        echo "<option value='" . $prow['id'] . "'>" . $prow['productName'] . " - " . $prow['productCode'] . " (" . $prow['brandName'] . " - " . $prow['modelNo'] . ")</option>";
                                    }
                                    ?>
                                </select>
                                <a href="#" onclick="advanceSearch('purchase')" class="input-group-addon" style="background-color:#01006c;color:#fff;border-radius: 0px 8px 8px 0px;box-shadow: -5px 0px 0px 0px #01006c;border: 0px solid #01006c;"><i class="fa fa-search"></i></a>
                                </div>
    						</div>
    						
    					</div>
    					<div class="form-group">
    						<div class="col-md-12">
    							<table id="managePurchaseProductTable" class="table table-bordered">
    								
    							</table>
    						</div>
    					</div>
    					<div class="form-group">
    						<div class="col-md-7"></div>
    						<div class="col-md-5">
    						<label for="add_grandTotal">Grand Total</label> 
    							<input type="text" class="form-control" id="add_grandTotal" name="grandTotal" readonly>
    						
    						<label for="add_paid">Paid </label> 
    							<input type="text" class="form-control" id="add_paid" name="paid" required>
    						
    						<label for="add_due">Due </label> 
    							<input type="text" class="form-control" id="add_due" name="due" required>
    					    </div>
    					</div>
    					<div class="form-group">
    						<div class="col-md-12">
    						<button type="submit" class="btn btn-primary btn-flat" name="btn_savePurchase" id="btn_saePurchase"><i class="fa fa-save"></i> Save Purchase </button>
    						<a href="purchaseLocal-view.php" class="btn btn-primary btn-flat"><i class="fa fa-mail-reply"></i> Back </a>
    						</div>
    					</div>
					</div>
					</div>
				</form>
          </div>
        </div>
      </div>
    </section>   
    
  </div>
   
  <?php include 'includes/footer.php'; ?>
  <?php 
    include 'includes/manageCustomerSupplier-modal.php';
    include 'includes/purchaseSerialize-modal.php';
    include 'includes/productAdvanceSearch-modal.php';
  ?>
</div>
</div>
<?php include 'includes/scripts.php'; ?>
<script src="dist/js/select2.min.js"></script>
<script src="includes/js/managePurchaseLocal.js"></script> 
<script src="includes/js/manageCustomerSupplier.js"></script> 
<script type="text/javascript">
	//$("#add_purchaseDate").val(new Date().toISOString().substring(0, 10));
	$("#add_products").on("change", function (){        
      $modal = $('#myModal');
      if($(this).val()){
          var dataString = "page=editOpeningStock&id="+$(this).val();
            $.ajax({
                method: "GET",
                url: 'phpScripts/manageProduct-add.php',
                data: dataString,
                dataType: "json",
                success: function(result) {
                    $("#add_productType").val(result.row_product.type);
                    $("#add_items_in_box").val(result.row_product.items_in_box);
                        $("#serializeProductTable").html('');
                    $modal.modal('show');
                },
                error: function(response) {
                    alert(JSON.stringify(response));

                },
                beforeSend: function() {
                    $('#loading').show();
                },
                complete: function() {
                    $('#loading').hide();
                }
            });
        
      }
	});
	
	
</script>
</body>
<?php
        //}else{
           // header('location: http://jafree.alitechbd.com/');
        //}
    ?>
</html>