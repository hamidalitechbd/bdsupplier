<?php $conPrefix = '';
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
              <h1>Discount Offer Information </h1>
              <ol class="breadcrumb">
                <li><a href="manage-view.php"><i class="fa fa-dashboard"></i> Discount Offer</a></li>
                <li class="active"> Home</li>
              </ol>
            </section>
            <!-- Main content -->
            <section class="content">
              
              
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
        			    <form class="form-horizontal" id="form_discountOffer" method="POST" action="#">
        				    <div class="form-group">
            				    <div class="col-sm-4">
            					    <label for="partyName">Offer Name</label>
            					    <input type="text" class="form-control" name="offerName" id="add_offerName" placeholder="Write Offer Name">
            					</div>  
            					<div class="col-sm-2">
            					    <label for="partyType">Offer Design For</label>
            					    <select class="form-control" name="partyType" id="add_partyType" required>
            							<option value="">~~ Select One ~~</option>
            							<option value="Party"> Party </option>
            							<option value="TS"> TS</option>
            						</select>
            					</div>
            					<div class="col-sm-3">
            					    <label for="amount">Start Date</label>
            					    <input type="date" class="form-control" name="date" id="add_startDate" placeholder="Date" style="padding: inherit;" value="<?php echo date('Y-m-d');?>">
            					</div>
            					<div class="col-sm-3">
            					    <label for="paymentMethod">End Date</label>
            						<input type="date" class="form-control" name="date" id="add_endDate" placeholder="Date" style="padding: inherit;" value="<?php echo date('Y-m-d');?>">
            							
            						</select>
            					</div>
            							
        				    </div>
        					<div class="form-group">
            					<div class="col-md-8">
        							<label for="products">Product Name </label> 
        							<div class="input-group">
                                    <select class="form-control" id="add_products" name="products"  style="width:100%;" required>
                                        <option value="0" selected>~~ All Products ~~</option>
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
                                    <a href="#" onclick="advanceSearch('discountOffer')" class="input-group-addon" style="background-color:#01006c;color:#fff;border-radius: 0px 8px 8px 0px;box-shadow: -5px 0px 0px 0px #01006c;border: 0px solid #01006c;"><i class="fa fa-search"></i></a>
                                    </div>
        						</div>
            					<div class="col-sm-4">
            					    <label for="remarks">Priority</label>
            					    <select class="form-control" name="priority" id="add_priority">
            							<option value="">~~ Select One ~~</option>
            							<option value="3"> High </option>
            							<option value="2"> Medium </option>
            							<option value="1"> Low </option>
            						</select>
            					</div>
        				    </div>
        				    <div class="form-group">
        				        <div class="col-sm-2">
            					    <label for="offerFor">Offer For</label>
            					    <input type="text" class="form-control" name="offerFor" id="add_offerFor" placeholder="Offer Quantity" autocomplete="off" >
            					</div>
            					<div class="col-sm-2">
            					    <label for="remarks">Select PC</label>
            					    <select class="form-control" name="offerForType" id="add_offerForType">
            							<option value="PC" selected> PC </option>
            							<!--<option value="TK"> TK </option>-->
            						</select>
            					</div>
            					<div class="col-sm-2">
            					    <label for="discount">Discount</label>
            					    <input type="text" class="form-control" name="discount" id="add_discount" placeholder="Discount" autocomplete="off" >
            					</div>
            					<div class="col-sm-2">
            					    <label for="remarks">Type</label>
            					    <select class="form-control" name="discountType" id="add_discountType">
            							<option value="">~~Select~~</option>
            							<option value="PC"> PC </option>
            							<option value="TK"> TK </option>
            							<option value="%"> % </option>
            						</select>
            					</div>
            					<div class="col-sm-2">
            					    <label for="andDiscount">And Discount</label>
            					    <input type="text" class="form-control" name="andDiscount" id="add_and_discount" placeholder="Discount" autocomplete="off" >
            					</div>
            					<div class="col-sm-2">
            					    <label for="andDiscountType">And Type</label>
            					    <select class="form-control" name="andDiscountType" id="add_and_discountType">
            							
            						</select>
            					</div>
        					</div>
    				        <div class="form-group">
            					<div class="col-sm-6">
            					    <label for="remarks">Remarks</label>
            					    <input type="text" class="form-control" name="remarks" id="add_remarks" placeholder="Remarks" autocomplete="off" >
            					</div>
            					<div class="col-sm-3">
            					    <label for="amount">Remainder Date</label>
            					    <input type="date" class="form-control" name="date" id="add_remainder" placeholder="Date" style="padding: inherit;" value="<?php echo date('Y-m-d');?>">
            					</div>
            					
            				</div>
            				<button type="submit" class="btn btn-primary btn-flat pull-left" name="btn_saveDiscount" id="btn_saveDiscount"><i class="fa fa-save"></i> Save Discount Offer </button>
            			
        			</form>
                  </div>
                </div>
              </div>
              </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="box">
                            <div class="box-header with-border">
                                <div class="col-xs-6">
                                  <!--<a href="sale-return.php"  class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i> Sale Return </a>-->
                                </div>
                            	<div class="col-xs-6">
                            		<!--<div id='divMsg' class='alert alert-success alert-dismissible' style='margin: -13% -5% -4% 20%;display:none;'></div>
                            		<div id='divErrorMsg' class='alert alert-danger alert-dismissible' style='margin: -13% -5% -4% 20%;display:none;'></div>-->
                            		<select id="sortData" name="sortData" style='float:right;'>
                                        <option value="All">All</option>
                                        <?php
                                            /*$initialYear = 2020;
                                            $fromDate = date('Y-m-d', strtotime('-7 days'));
                    					    $toDate = date('Y-m-d');
                    				        echo '<option value="'.$fromDate.','.$toDate.'" Selected>7 Days</option>';
                                            $fromDate = date('Y-m-d', strtotime('-30 days'));
                                            $toDate = date('Y-m-d');
                                            echo '<option value="'.$fromDate.','.$toDate.'">30 Days</option>';
                                            $fromDate = date('Y-m-d', strtotime('-180 days'));
                                            $toDate = date('Y-m-d');
                                            echo '<option value="'.$fromDate.','.$toDate.'">180 Days</option>';
                                            for($i = date("Y"); $i >= $initialYear; $i--){
                                                $fromDate = $i.'-01-01';
                                                $toDate = $i.'-12-31';
                                                echo '<option value="'.$fromDate.','.$toDate.'">Year - '.$i.'</option>';
                                            }*/
                                        ?>
                					</select>
                            	</div>
                            </div>
                            <div class="box-body">
                            	<table id="manageDiscountOffer" class="table table-bordered" width="100%">
                                    <thead>
                                        <th>SN#</th>
                                        <th>Offer Name</th>
                                        <th>Product Information</th>
                                        <th>Date</th>
                                        <th>Description</th>
                                        <th>Priority</th>
                                        <th style="width:8%;">Action</th>
                                    </thead>
                            	</table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>   
            
        </div>
       
      <?php 
      include 'includes/footer.php';
      include 'includes/productAdvanceSearch-modal.php'; 
      ?>
    </div>
    <?php include 'includes/scripts.php'; ?>
    <script src="dist/js/select2.min.js"></script>
    <script src="includes/js/discountOffer.js"></script>	
</body>
</html>
<script>
    
    $("#add_products").select2( {
    	placeholder: "Select Item Name",
    	allowClear: true
	});
	$("#add_discountType").change(function (){
        if($("#add_discountType").val() == "PC"){
            $("#add_and_discount").prop('disabled',false);
            $("#add_and_discountType").prop('disabled',false);
            $("#add_and_discountType").html('<option value="">~~Select~~</option><option value="TK"> TK </option>');
        }else if($("#add_discountType").val() == "TK"){
            $("#add_and_discount").val('');
            $("#add_and_discount").prop('disabled',true);
            $("#add_and_discountType").prop('disabled',true);
            $("#add_and_discountType").html('<option value="">~~No Option~~</option>');
        }else if($("#add_discountType").val() == "%"){
            $("#add_and_discount").val('');
            $("#add_and_discount").prop('disabled',true);
            $("#add_and_discountType").prop('disabled',true);
            $("#add_and_discountType").html('<option value="">~~No Option~~</option>');
        }
	})
</script>