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
              <h1> Damage Products </h1>
              <ol class="breadcrumb">
                <li><a href="user-home.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">
                     Damage Products
                </li>
              </ol>
            </section>
            <!-- Main content -->
            <section class="content">
              
              <link rel="stylesheet" href="css/buttons.dataTables.min.css"/>
              <div class="row">
                <div class="col-xs-12">
                  <div class="box">
                    <div class="box-header">
                        <div class="col-xs-6"></div>
            			<div class="col-xs-6">
            				<div id='divMsg' class='alert alert-success alert-dismissible successMessage'></div>
            				<div id='divErrorMsg' class='alert alert-danger alert-dismissible errorMessage'></div>
            			</div>
                    </div>
                    <div class="box-body">
            			    <form class="form-horizontal" id="form_damageProducts" method="POST" action="#">
            				    <div class="form-group">
            				        <!--<div class="col-sm-1"></div>-->
            					    <div class="col-sm-2">
            					        <label for="transferDate">Date</label>
            					        <input type="date" id="damageDate" name="damageDate" style="padding: inherit;" value="<?php echo date("Y-m-d");?>"  class="form-control" />
            					    </div>
                                    <div class="col-sm-7">
                                        <label for="ItemName">Select Product</label> 
                                        <div class="input-group">
                                        <select class="form-control" id="damageProducts" name="damageProducts" style="width:100%;">
                                            <option value="" selected>~~ Select Product ~~</option>
                                            <?php
                                            $sql = "SELECT tbl_products.id,tbl_products.productName,tbl_products.productCode,tbl_brands.brandName
            										FROM tbl_products 
            										LEFT OUTER JOIN tbl_brands ON tbl_brands.id=tbl_products.tbl_brandsId
            										WHERE tbl_products.status='Active' AND tbl_products.deleted='No' 
            										ORDER BY tbl_products.id  DESC";
    										
                                            $query = $conn->query($sql);
                                            while ($prow = $query->fetch_assoc()) {
                                                echo "<option value='" . $prow['id'] . "'>" . $prow['productName'] . "-".$prow['productCode']." (".$prow['brandName'].") </option>";
                                            }
                                            ?>
                                        </select>
                                        <a href="#" onclick="advanceSearch('damageProducts')" class="input-group-addon" style="background-color:#171991;color:#fff;border-radius: 0px 8px 8px 0px;box-shadow: -5px 0px 0px 0px #171991;border: 1px solid #171991;"><i class="fa fa-search"></i></a>
                                         </div>
                                    </div>
                                    
                                    <div class="col-sm-3">
                                        
                                        <label for="WareHouse">Select Warehouse</label> 
                                        <select class="form-control" name="damageWareHouse" id="damageWareHouse" style="width:100%;" required>
                                           <option value="" selected>~~ Select Warehouse ~~</option>
                                        </select>
                                        <select class="form-control" name="wareHouseStock" id="wareHouseStock" style="display:none;">
                                        </select>
                                    </div>
            					</div>
            					<div class="form-group">
            					    <!--<div class="col-sm-1"></div>-->
                                    <div class="col-sm-2">
                                        <label for="transferDate">Available Quantity</label>
            					        <input type="text" id="currentStock" name="currentStock"  value="0"  class="form-control" Readonly />
                                    </div>
                                    
            						<div class="col-sm-2">
            						    <label for="damageQuantity">Quantity</label>  
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="damageQuantity" name="damageQuantity" placeholder=" Damage Quantity ">
                                            <span class="input-group-btn">
                                                    <button id="ShowSerializeBtn" class="btn btn-primary hidden" type="button" onclick="showSerializTable()"><i class="fa fa-eye"></i></button>
                                            </span>
                                        </div>
            						</div>
            						<div class="col-sm-8">
            						    <label for="transferStock">Remarks</label>  
                                        <input type="text" class="form-control" id="damageRemarks" name="damageRemarks" placeholder=" Remarks ">
            						</div>
            					</div>
            							  
            				<div class="form-group">
            				    <!--<div class="col-sm-1"></div>-->
            					<div class="col-sm-12">
            					<button type="submit" class="btn btn-primary btn-flat" name="btn_saveVoucher" id="btn_saveVoucher"><i class="fa fa-save"></i> Save Damage Product </button>
            				    </div>
            				</div>
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
                                        <option value="0,0">All</option>
                                        <?php
                                            $initialYear = 2019;
                                            $fromDate = date('Y-m-d', strtotime('-7 days'));
                                            $toDate = date('Y-m-d');
                                            echo '<option value="'.$fromDate.','.$toDate.'" Selected>30 Days</option>';
                                            $fromDate = date('Y-m-d', strtotime('-180 days'));
                                            $toDate = date('Y-m-d');
                                            echo '<option value="'.$fromDate.','.$toDate.'">180 Days</option>';
                                            for($i = date("Y"); $i >= $initialYear; $i--){
                                                $fromDate = $i.'-01-01';
                                                $toDate = $i.'-12-31';
                                                echo '<option value="'.$fromDate.','.$toDate.'">Year - '.$i.'</option>';
                                            }
                                        ?>
                					</select>
                            	</div>
                            </div>
                            <div class="box-body">
                                <input type="hidden" id="salesType" name="salesType" value="<?php echo $getType;?>"/>
                            	<table id="manageDamageProductsTable" class="table table-bordered">
                                    <thead>
                                        <th>SN#</th>
                                        <th>Date</th>
                                        <th>Damage Order No</th>
                                        <th>Product Name</th>
                                        <th>Specification</th>
                                        <th>Warehouse</th>
                                        <th>Qty</th>
                                        <th>Action</th>
                                    </thead>
                            	</table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>   
            
        </div>
       
      <?php include 'includes/footer.php'; ?>
      <?php 
        include 'includes/productAdvanceSearch-modal.php';
        include 'includes/saleSerializeProductReturn-modal.php';
      ?>
    </div>
    <?php include 'includes/scripts.php'; ?>
    <script src="dist/js/select2.min.js"></script>
    <script src="includes/js/manageDamageProducts.js"></script>
</body>
</html>