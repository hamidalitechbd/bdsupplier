<?php 
    $conPrefix = '';
    include 'includes/session.php'; 
    include 'includes/header.php'; 
?>
<script type="text/javascript">
window.onload=function(){      
    $("#filter").keyup(function(evt) {
        var filter = $(this).val(),
        count = 0;
        $('#results .results').each(function() {
            if ($(this).text().search(new RegExp(filter, "i")) < 0) {
                $(this).hide();
            } else {
                $(this).show();
                count++;
            }
        });
    });
}
</script>
<style>  #units tr td, #units { 
  border: 1px solid #c6c6c6; 
} 

#loader {
border: 6px solid #d0d0d061;
border-top-color: rgb(243, 243, 243);
border-top-style: solid;
border-top-width: 8px;
border-radius: 50%;
border-top: 6px solid #2408e3;
width: 60px;
height: 60px;
margin-left: 46%;margin-top: 10%;
-webkit-animation: spin 2s linear infinite;
animation: spin 2s linear infinite;
}

/* Safari */
@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>
<body class="hold-transition skin-blue sidebar-mini sidebar-collapse">
<link rel="stylesheet" href="dist/css/select2.min.css" />
<div class="wrapper">
  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Temporary Sale
      </h1>
      <ol class="breadcrumb">
        <li><a href="manage-view.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Temporary Sale</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
	  <link rel="stylesheet" href="css/buttons.dataTables.min.css"/>
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="">
				<div class="col-xs-6">
					
				</div>
				<div class="col-xs-6">
					<div id='divMsg' class='alert alert-success alert-dismissible successMessage'></div>
					<div id='divErrorMsg' class='alert alert-danger alert-dismissible errorMessage'></div>
				</div>
            </div>
            <div class="box-body" style="background: #e7e7e7;">
				<form class="form-horizontal" id="form_addsales" method="POST" action="#">
					<div class="col-md-8" style="background: white;border-radius: 5px;">
						<div class="row"><br>
							<div class="col-md-12">
						    <div class="form-group">
							    <div class="col-md-6">
								<!--<a style="width: 45%;" href="#addnew" data-toggle="modal" class="btn btn-default btn-sm btn-flat"><i class="fa fa-outdent"></i> Open Hold Sales </a>
								<a style="width: 45%;" href="#addnew" data-toggle="modal" class="btn btn-default btn-sm btn-flat"><i class="fa fa-dashboard"></i> Calculator </a>-->
								<?php
        				            $maxDate = date("Y-m-d");
        				            if(strtolower($_SESSION['userType']) == 'admin' || strtolower($_SESSION['userType']) == 'super admin' || strtolower($_SESSION['userType']) == 'admin support plus'){
        				                $minDate = date('Y-m-d',strtotime("-180 days"));    
        				            }else{
        				                $minDate = $maxDate; 
        				            }
        				        ?>
								<input type="date" id="salesDate" name="salesDate" value="<?php echo $maxDate;?>" min="<?php echo $minDate;?>" max="<?php echo $maxDate;?>" class="form-control" style="padding: inherit;" />
							</div>
							<div class="col-md-6">
							    <select class="form-control" id="wareHouse" style="width:100%;" required> 
								    <option value="" selected>~~ Select Warehouse ~~</option>
									<?php
									    $sql = "SELECT id, wareHouseName
                                                FROM tbl_warehouse
                                                WHERE deleted='No' AND status='Active' 
                                                ORDER BY id ASC";
                                        $res = $conn->query($sql);
                                        while($row = $res->fetch_assoc()){
                                            echo '<option value="'.$row['id'].'">'.$row['wareHouseName'].'</option>';
                                        }
									?>
								</select>
							</div>
						</div>
						<div class="form-group">
							
							<div class="col-md-6">
								<select class="form-control" id="customers" style="width:100%;" required> 
								    <option value="" selected>~~ Select Customers ~~</option>
									<?php
                                        /*$sql = "SELECT id, partyName, tblCity,locationArea, IFNULL(creditLimit , 0) - IFNULL(dbtTransHistory.amount,0) as creditLimit
                                                FROM tbl_party
                                                LEFT OUTER JOIN (SELECT dbt.tbl_partyId, Sum(CASE dbt.type 
                                                             WHEN 'partyPayable' THEN dbt.amount 
                                                             WHEN 'payment' THEN dbt.amount
                                                             WHEN 'adjustment' THEN  dbt.amount
                                                             WHEN 'paymentReceived' THEN -dbt.amount 
                                                             WHEN 'payable' THEN -dbt.amount 
                                                             WHEN 'paymentAdjustment' THEN -dbt.amount 
                                                           END) AS amount
                                                FROM(
                                                SELECT SUM(amount) as amount, type, tbl_partyId 
                                                FROM tbl_paymentVoucher 
                                                WHERE deleted='No' AND status='Active' AND voucherType='Customer Sale' AND tbl_purchaseId=0 AND tbl_sales_Id > 0
                                                GROUP BY tbl_partyId, type) as dbt
                                                GROUP BY dbt.tbl_partyId) as dbtTransHistory ON tbl_party.id = dbtTransHistory.tbl_partyId
                                                WHERE deleted='No' AND status='Active' AND partyType <> 'Suppliers'
                                                ORDER BY id ASC";
                                        $res = $conn->query($sql);
                                        while($row = $res->fetch_assoc()){
                                            echo '<option value="'.$row['id'].'">'.$row['partyName'].' ('.$row['tblCity'].' - '.$row['locationArea'].')'.'__'.$row['creditLimit'].'</option>';
                                        }*/
									?>
								</select>
								<select class="form-control" id="customersLimit" style="display:none"> 
								    <option value="" selected>~~ Select Customers ~~</option>
								</select>
							</div>
							<div class="col-md-6">
							    <input type="text" class="form-control" id="customerCreditLimit" name="customerLimit" value="" Readonly />
							</div>
							</div>
							<div class="form-group">
							    <div class="col-md-6">
    								<select class="form-control" id="salesMan" style="width:100%;" required>
    								    <option value="" selected>~~ Select Sales Users ~~</option>
    									<?php
    									    $sql = "SELECT id, fname, username  
                                                    FROM tbl_users
                                                    WHERE accountStatus='approved' and deleted='No'
                                                    ORDER BY priority ASC";
                                            $res = $conn->query($sql);
                                            while($row = $res->fetch_assoc()){
                                                echo '<option value="'.$row['id'].'">'.$row['fname'].' - '.$row['username'].'</option>';
                                            }
    									?>
    								</select>
    							</div>
    							<div class="col-md-6">
    							    <input type="text" class="form-control" id="requisitionNo" name="requisitionNo" value="" placeholder="Requisition No"/>
    						    </div>
						    </div>
						    <div class="form-group">
						        <div class="col-md-6">
							    <input type="text" class="form-control" id="reference" name="reference" value="" placeholder = " Reference name and information " />
							</div>
							<div class="col-md-6">
							        <input type="text" class="form-control" id="remarks" name="remarks" value="" placeholder=" Remarks " />
							    </div>
						    </div>
						</div>
						</div>
						
						<span id="cart_details"></span>
						<div align="" style="background: #e7e7e7;padding: 2%;margin-bottom: 2%;text-align: center;">
						    <a href="#" class="btn btn-default" id="btn_previousPrice" style="width: auto;box-shadow: 1px 1px 1px 0px #909090;">
							<span class="glyphicon glyphicon-th" style="color: #000cbd;"></span> Check Previous Priced
							</a>
							<a href="#" class="btn btn-default" id="clear_cart" style="width: 20%;box-shadow: 1px 1px 1px 0px #909090;">
							<span class="glyphicon glyphicon-refresh" style="color: #000cbd;"></span> Clear
							</a>
							<a class="btn btn-default" id="check_out_cart12" style="width: 20%;box-shadow: 1px 1px 1px 0px #909090;" onclick=discountOfferPreview()>
							<span class="glyphicon glyphicon-th-list" style="color: #000cbd;"></span> Preview
							</a>
							
							<a type="submit"  href="#" class="btn btn-default" id="check_out_cart" style="width: 28%;box-shadow: 1px 1px 1px 0px #909090;">
							<span class="glyphicon glyphicon-shopping-cart" style="color: #000cbd;"></span> Place Order
							</a>
						</div>
					</div>
					
					<div class="col-md-4" style="background: white;height:600px;overflow-y:scroll;left: 1%;border-radius: 5px;">
						<div class="row"><br>
							<div class="col-md-12">
								<div class="input-group">
                                    <!--<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>-->
                                    <input id="filter" type="text" placeholder="Search Products" class="form-control"/>
                                    <a href="#" onclick="advanceSearch('temporarySale')" class="input-group-addon" style="background-color:#e1e1e1;color:#060afd;border-radius: 0px 8px 8px 0px;box-shadow: -5px 0px 0px 0px #e1e1e1;"><i class="fa fa-search"></i></a>
                                </div>
							</div>
							<!--<div class="col-md-6">
								<div class="input-group">
									<input id="filter" type="text" placeholder="Keywords 02" class="form-control"/>
									<a class="input-group-addon" onclick="addSupplier('Suppliers')" style="background-color:#d0d0d0;color:#060afd;border-radius: 0px 8px 8px 0px;"><i class="fa fa-plus"></i></a> 
								</div>
							</div>-->
						<br><br><br>
						<div class="" id="results"><div id='loader'></div></div></div>
					</div>
					
				</form>
          </div>
        </div>
      </div>
    </section>   
    
  </div>
   
  <?php include 'includes/footer.php'; ?>
</div>
<?php 
    include 'includes/scripts.php'; 
    include 'includes/productAdvanceSearch-modal.php';
    include 'includes/previousProducts-modal.php';
?>
<script src="dist/js/select2.min.js"></script>
<script src="includes/js/manageTemporarySale.js"></script>
<script>
    var userType = "<?php echo $_SESSION['userType'];?>";
    selectSalesMan(<?php echo $_SESSION['user'];?>);
</script>
</body>
</html>