<?php 
    $conPrefix = '';
    include 'includes/session.php'; 
    include 'includes/header.php'; 
    //$type = htmlspecialchars($_GET["page"]);
    //if($type != "")
    //{
    //$sessionId = time().uniqid();
    $sessionId = $_GET['id'];
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
					<div id='divMsg' class='alert alert-success alert-dismissible' style='margin: -13% -5% -4% 20%;display:none;'></div>
					<div id='divErrorMsg' class='alert alert-danger alert-dismissible' style='margin: -13% -5% -4% 20%;display:none;'></div>
				</div>
            </div>
            <div class="box-body">
              
			  
				<form class="form-horizontal" id="form_editPurchase" method="POST" action="#">
					<div class="form-group">
						<div class="col-sm-4">
						<label for="purchaseDate">Purchase Date</label> 
							<input type="date" style="line-height: 10px;" class="form-control" id="edit_purchaseDate" name="purchaseDate" placeholder=" Purchase Date " required>
							<input type="hidden" class="form-control" id="edit_purchaseCode" name="purchaseCode">
							<input type="hidden" class="form-control" id="edit_purchaseId" name="purchaseId">
						</div>
						<div class="col-sm-4">
							<label for="add_supplier">Supplier </label> 
                            
							<select class="form-control" id="edit_supplier" name="supplier"  style="width:100%;" required>
								<?php
									$sql = "SELECT id,partyName FROM `tbl_party` WHERE status='Active' AND tblType<>'Customers' ORDER BY `id`  DESC";
									$result = $conn->query($sql);
									while($row = $result->fetch_assoc()){
										echo "<option value='".$row['id']."'>".$row['partyName']."</option>";
									}
								?>
							</select>
								<!--<span class="input-group-addon" data-toggle="modal" data-target="#supplierModal" style="background-color:#d0d0d0;color:#060afd;border-radius: 0px 8px 8px 0px;"><i class="fa fa-plus"></i></span> -->
							 
						</div>
						
						<div class="col-sm-4">
						<label for="chalanNumber">Chalan Number</label> 
							<input type="text" class="form-control" id="edit_chalanNumber" name="chalanNumber" placeholder=" Chalan Number">
						</div>
					</div>
					
					<div class="form-group">
						<div class="col-sm-8">
							<label for="products">Items </label> 
                            <select class="form-control" id="add_products" name="products"  style="width:100%;">
                                <option value="" selected>~~ Select Item ~~</option>
                                <?php
                                $sql = "SELECT tbl_products.id,tbl_products.productName,tbl_products.productCode,tbl_brands.brandName
										FROM `tbl_products` 
										LEFT OUTER JOIN tbl_brands ON tbl_brands.id=tbl_products.tbl_brandsId
										WHERE tbl_products.status='Active' ORDER BY `id`  DESC";
                                $query = $conn->query($sql);
                                while ($prow = $query->fetch_assoc()) {
                                    echo "<option value='" . $prow['id'] . "'>" . $prow['productName'] . " - " . $prow['productCode'] . " (" . $prow['brandName'] . ")</option>";
                                }
                                ?>
                            </select>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-12">
							<table id="edit_managePurchaseProductTable" class="table table-bordered">
								
							</table>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-7"></div>
						<div class="col-sm-5">
						<label for="add_grandTotal">Grand Total</label> 
							<input type="text" class="form-control" id="edit_grandTotal" name="grandTotal" readonly>
						
						<label for="add_paid">Paid </label> 
							<input type="text" class="form-control" id="edit_paid" name="paid" required>
						
						<label for="add_due">Due </label> 
							<input type="text" class="form-control" id="edit_due" name="due" required>
					    </div>
					</div>
					<div class="form-group">
						<div class="col-sm-12">
						<button type="submit" class="btn btn-primary btn-flat" name="btn_savePurchase" id="btn_saePurchase"><i class="fa fa-save"></i> Save Purchase </button>
						<a href="purchaseLocal-view.php" class="btn btn-primary btn-flat"><i class="fa fa-mail-reply"></i> Back </a>
						</div>
					</div>
				</form>
          </div>
        </div>
      </div>
    </section>   
    
  </div>
   
  <?php include 'includes/footer.php'; ?>
  <?php include 'includes/manageCustomerSupplier-modal.php'; ?>
</div>
<?php include 'includes/scripts.php'; ?>
    <script src="dist/js/select2.min.js"></script>
<script src="includes/js/managePurchaseLocal.js"></script> 
<script type="text/javascript">

	$("#add_products").on("change", function (){        
      $modal = $('#myModal');
      if($(this).val()){
        $modal.modal('show');
    }
	});
	
	<?php
	    if($_GET['id']){
	        
    ?>
        var id = <?php echo $_GET['id'];?>;
        var dataString = "id="+id;
          $.ajax({
                type: 'POST',
                url: 'phpScripts/managePurchaseProducts-row.php',
                data: dataString,
                dataType: 'json',
                success: function(response){
                  $("#edit_purchaseDate").val(response[0].purchaseDate);
                  $("#edit_purchaseCode").val(response[0].purchaseOrderNo);
                  $("#edit_purchaseId").val(response[0].id);
                  $("#edit_supplier").val(response[0].tbl_supplierId).trigger('change');
                  $("#edit_chalanNumber").val(response[0].chalanNo);
                  productsHTML = "<thead style='background-color: #e1e1e1;'><th>SN</th><th>Item Name(Code)</th><th>Unit Price</th><th>Quantity/Amount</th><th>Total</th><th style='width:6%;'>Action</th></thead>";
                  var grandTotal = parseFloat(0);
                  for(var i=0;i<response.length;i++){
                      if(response[i].productName != "undefined"){
                        productsHTML += "<tr><td>"+(i+1)+"</td><td>"+response[i].productName+" - "+response[i].productCode+"</td><td>"+response[i].purchaseAmount+"</td><td>"+response[i].quantity+"</td><td>"+response[i].totalAmount+"</td><td><a href='#' class='btn btn-danger btn-sm btn-flat' onclick='deletePurchaseProducts("+response[i].purchaseProductsId+")'><i class='fa fa-trash'></i></a></td></tr>";
                        grandTotal += parseFloat(response[i].totalAmount);
                      }
				  }
				  $("#edit_managePurchaseProductTable").html(productsHTML);
				  $("#edit_grandTotal").val(grandTotal);
				  $("#edit_paid").val(response[0].paidAmount);
				  $("#edit_due").val(response[0].dueAmount);
                },error: function (xhr) {
                    alert(xhr.responseText);
                }
          })
    <?php
	    }
	?>
	
</script>
</body>
<?php
        //}else{
           // header('location: http://jafree.alitechbd.com/');
        //}
    ?>
</html>