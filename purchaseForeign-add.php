<?php 
    $conPrefix = '';
    include 'includes/session.php'; 
    include 'includes/header.php'; 
    $sessionId = time().uniqid();
    if(isset($_GET['id'])){
        $getId = $_GET['id'];
        $sql_fp = "SELECT tbl_purchaseForeign.id, tbl_purchaseForeign.purchaseOrderNo, tbl_purchaseForeign.purchaseDate, tbl_purchaseForeign.lcNo, tbl_purchaseForeign.lcOpeningDate, 
                        tbl_purchaseForeign.fileNo, tbl_purchaseForeign.deliveryDate, tbl_purchaseForeign.blNo, tbl_purchaseForeign.tbl_bankInfoId, tbl_purchaseForeign.tbl_supplierId
                    FROM tbl_purchaseForeign
                    WHERE tbl_purchaseForeign.deleted='No' AND tbl_purchaseForeign.id='$getId'";
        $query_fp = $conn->query($sql_fp);
        while ($row_fp = $query_fp->fetch_assoc()) {
            $maxDate = $row_fp['purchaseDate'];
            $minDate = $maxDate;
            $supplierId = $row_fp['tbl_supplierId'];
            $lcNo = $row_fp['lcNo'];
            $blNo = $row_fp['blNo'];
            $fileNo = $row_fp['fileNo'];
            $lcOpeningDate = $row_fp['lcOpeningDate'];
            $deliveryDate = $row_fp['deliveryDate'];
            $tbl_bankInfoId = $row_fp['tbl_bankInfoId'];
        }
        $sql_fp_delete = "DELETE 
                            FROM `tbl_tempForeignPurchaseProducts` 
                            WHERE entryBy = '$loginID'";
        $conn->query($sql_fp_delete);
        $sql_fp_temp_insert = "INSERT INTO tbl_tempForeignPurchaseProducts(tbl_productsId, sessionId, quantity, tbl_wareHouseId, purchaseAmount, totalAmount, entryBy) 
                                SELECT tbl_purchaseForeignProducts.tbl_productsId, '$sessionId', tbl_purchaseForeignProducts.quantity, tbl_purchaseForeignProducts.tbl_wareHouseId, tbl_purchaseForeignProducts.purchaseAmount, tbl_purchaseForeignProducts.totalAmount, '$loginID'
                                FROM tbl_purchaseForeignProducts
                                WHERE tbl_purchaseForeignProducts.tbl_purchaseForeignId='$getId'";
        $conn->query($sql_fp_temp_insert);
    }else{
        $getId = "";
        $supplierId = "";
        $lcNo = "";
        $blNo = "";
        $fileNo = "";
        $lcOpeningDate = "";
        $deliveryDate = "";
        $tbl_bankInfoId = "";
        $maxDate = date("Y-m-d");
        if(strtolower($_SESSION['userType']) == 'admin' || strtolower($_SESSION['userType']) == 'super admin'){
            $minDate = date('Y-m-d',strtotime("-60 days"));    
        }else{
            $minDate = $maxDate; 
        }
    }
?>

<body class="hold-transition skin-blue sidebar-mini">
<link rel="stylesheet" href="dist/css/select2.min.css" />
<div class="wrapper">

  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <section class="content-header">
      <h1>Import</h1>
      <ol class="breadcrumb">
        <li><a href="manage-view.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Import</li>
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
              
			  <?php
			  if($getId != ''){
			      echo '<div style="width:100%; color:white; background-color:#b94916; margin-bottom:20px; padding:10px;">* Do not reload or leave this page when you edit the import otherwise all temporary edit will be removed.</div>';
			  }
			  ?>
				<form class="form-horizontal" id="form_addPurchase" method="POST" action="#">
					<div class="form-group">
						<div class="col-sm-3">
						    <input type="hidden" name="fpId" id="fpId" value="<?php echo $getId;?>" />
						    <input type="hidden" name="editSupplierId" id="editSupplierId" value="<?php echo $supplierId;?>" />
						    <input type="hidden" name="editBankInfoId" id="editBankInfoId" value="<?php echo $tbl_bankInfoId;?>" />
						    <label for="purchaseDate">Import Date</label> 
							<input type="date" style="line-height: 10px;" class="form-control" value="<?php echo $maxDate;?>" id="add_purchaseDate" name="purchaseDate" placeholder=" Import Date " />
							<input type="hidden" class="form-control" id="edit_purchaseId"  name="purchaseId">
						</div>
						<div class="col-sm-3">
							<label for="add_supplier">Supplier </label> 
                            <!--<div class="input-group">-->
							<select class="form-control" id="add_supplier" name="supplier"  style="width:100%;" required></select>
							<!--data-toggle="modal" data-target="#supplierModal"-->
								<!--<a class="input-group-addon" onclick="addSupplier('Suppliers')" style="background-color:#d0d0d0;color:#060afd;border-radius: 0px 8px 8px 0px;"><i class="fa fa-plus"></i></a> 
							 </div>-->
						</div>
						<div class="col-sm-3">
    						<label for="lcno">LC Number</label> 
    						<input type="text" class="form-control" id="add_lcNo" name="lcno" value="<?php echo $lcNo;?>" placeholder=" LC Number" maxlength="13">
    					</div>
    					<div class="col-sm-3">
    						<label for="fileNo">File Number</label> 
    						<input type="text" class="form-control" id="add_fileNo" name="fileNo" value="<?php echo $fileNo;?>" placeholder=" File Number" maxlength="13">
						</div>
    				</div>
    				<div class="form-group">
    				    <div class="col-sm-3">
    				        <?php
    				            $maxDate = date("Y-m-d");
    				            if(strtolower($_SESSION['userType']) == 'admin' || strtolower($_SESSION['userType']) == 'super admin'){
    				                $minDate = date('Y-m-d',strtotime("-180 days"));    
    				            }else{
    				                $minDate = $maxDate; 
    				            }
    				        ?>
						    <label for="purchaseDate">LC Opening Date</label> 
							<input type="date" style="line-height: 10px;" class="form-control" value="<?php echo $lcOpeningDate;?>" id="add_lcOpeningDate" onblur="LcOpenValidate()" name="lcOpeningDate" placeholder=" LC Opening Date ">
						</div>
    					<div class="col-sm-3">
						    <label for="purchaseDate">LC Delivery Date</label> 
							<input type="date" style="line-height: 10px;" class="form-control" value="<?php echo $deliveryDate;?>" id="add_lcDeliveryDate" onblur="LcDeliveryValidate()" name="lcDeliveryDate" placeholder=" LC Delivery Date ">
						</div>
					    
						<div class="col-sm-3">
    						<label for="blNo">BL Number</label> 
    						<input type="text" class="form-control" id="add_blNo" value="<?php echo $blNo;?>" name="blNo" placeholder=" BL Number" maxlength="13">
    					</div>
    					<div class="col-sm-3">
    						<label for="bankInformation">bank Information</label> 
    							<select class="form-control" id="add_bankInformation" name="bankInformation"  style="width:100%;">
                                <option value="" selected>~~ Select Bank Information ~~</option>
                                <?php
                                $sql = "SELECT id, accountNo, accountName, bankName 
                                        FROM tbl_bank_account_info
										WHERE status='Active' 
										AND deleted='No'
										ORDER BY id  DESC";
                                $query = $conn->query($sql);
                                while ($prow = $query->fetch_assoc()) {
                                    echo "<option value='" . $prow['id'] . "'>" . $prow['accountNo'] . " - " . $prow['accountName'] . " (" . $prow['bankName'] . ")</option>";
                                }
                                ?>
                                </select>
    					
    					</div>
					</div>
					<div class="form-group">
    					<div class="col-sm-6">
							<label for="products">Products Name </label> 
							<div class="input-group">
                            <select class="form-control" id="add_products" name="products"  style="width:100%;">
                                <option value="" selected>~~ Select Products ~~</option>
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
						<div class="col-sm-12">
							<table id="managePurchaseProductTable" class="table table-bordered"></table>
						</div>
					</div>
					<div class="form-group" style="display:none;">
						<div class="col-sm-7"></div>
						<div class="col-sm-5">
    						<label for="add_grandTotal">Grand Total</label> 
							<input type="text" class="form-control" id="add_grandTotal" name="grandTotal" Readonly />
    						
    						<label for="add_paid">Paid </label> 
							<input type="text" class="form-control" id="add_paid" name="paid" Readonly />
    						
    						<label for="add_due">Due </label> 
							<input type="text" class="form-control" id="add_due" name="due" Readonly />
					    </div>
					</div>
					<div class="form-group">
						<div class="col-sm-12">
						<button type="submit" class="btn btn-primary btn-flat" name="btn_savePurchase" id="btn_saePurchase"><i class="fa fa-save"></i> Save Import </button>
						<a href="purchaseForeign-view.php" class="btn btn-primary btn-flat"><i class="fa fa-mail-reply"></i> Back </a>
						</div>
					</div>
				</form>
          </div>
        </div>
      </div>
    </div>
    </section>   
    
  
  </div> 
  <?php 
    include 'includes/footer.php';
    include 'includes/manageForeignPurchase-modal.php'; 
    include 'includes/productAdvanceSearch-modal.php';
  ?>

</div>
<?php include 'includes/scripts.php'; ?>
<script src="dist/js/select2.min.js"></script>
<script src="includes/js/managePurchaseForeign.js"></script> 
<script type="text/javascript">
	//$("#add_purchaseDate").val(new Date().toISOString().substring(0, 10));
	$("#add_products").on("change", function (){        
      $modal = $('#myModal');
      if($(this).val()){
        $modal.modal('show');
      }
	});
	
	
</script>
</body>
</html>