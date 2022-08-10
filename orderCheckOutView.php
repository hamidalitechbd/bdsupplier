<?php 
    $conPrefix = '';
    include 'includes/session.php'; 
    include 'includes/header.php'; 
    $sessionId = time().uniqid();
    ordersGoBackToPending();
?>
<script type="text/javascript">
window.onload=function(){      
    $("#filter").keyup(function() {
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
    <?php
        
    ?>
    <section class="content-header">
      <h1>
        Order Check-Out Information
      </h1>
      <ol class="breadcrumb">
        <li><a href="user-home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Order Check-Out Information</li>
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
					<div id='divErrorMsg' class='alert alert-danger alert-dismissible messageTabe errorMessage'></div>
				</div>
            </div>
            <div class="box-body" style="background: #e7e7e7;">
				<form class="form-horizontal" id="form_ordersales" method="POST" action="#">
				    <div class="col-md-12"></div>
					<div class="col-md-12" style="background: white;/*! width: 56%; */border-radius: 5px;">
						
						<span id="cart_details"></span>
						
						<div class="row"><br>
						    <div class="col-md-12">
						        <div class="form-group">
						            <div class="col-md-4">
        								<select class="form-control" id="orderCustomer" name="orderCustomer" style="width:100%;" required> 
        								    <option value="" selected>~~ Select Customers ~~</option>
        								    <?php
                                                $sql = "SELECT tbl_party.id,tbl_party.partyName,tbl_party.locationArea,tbl_party.tblCity FROM `tbl_party` WHERE status='Active'";
                                                $query = $conn->query($sql);
                                                while ($prow = $query->fetch_assoc()) {
                                                    echo "
                    								  <option value='" . $prow['id'] . "'>" . $prow['partyName'] . " - " . $prow['locationArea'] . " - " . $prow['tblCity'] . "</option>
                    								";
                                                }
                                            ?>
        								</select>
    							    </div>
    							    <div class="col-md-4">
        								<select class="form-control" id="transportName" name="transportName" style="width:100%;" required> 
        								    <option value="" selected>~~ Select Transport ~~</option>
        								    <?php
                                                $sql = "SELECT id,transportName FROM `tbl_transportInfo` WHERE status='Active'";
                                                $query = $conn->query($sql);
                                                while ($prow = $query->fetch_assoc()) {
                                                    echo "
                    								  <option value='" . $prow['id'] . "'>" . $prow['transportName'] . "</option>
                    								";
                                                }
                                            ?>
        								</select>
    							    </div>
    							    <div class="col-md-4">
							        <?php
							            $maxDate = date('Y-m-d');
							            $saleDateDisable = '';
							            if(strtolower($_SESSION['userType']) == 'admin' || strtolower($_SESSION['userType']) == 'super admin'){
							                $minDate = date('Y-m-d',strtotime("-60 days"));    
							                if(strtolower($_SESSION['userType']) == 'super admin'){
							                    $saleDateDisable = '';
							                }else{
							                    $saleDateDisable = 'Disabled';
							                }
							            }else{
							                $minDate = $maxDate; 
							                $saleDateDisable = 'Disabled';
							            }
							        ?>
								    <input type="date"  class="form-control" id="orderDate" name="orderDate" value="<?php echo $maxDate;?>" <?php echo $saleDateDisable;?> style="padding: 0px;text-align: center;"/>
							        </div>
							   </div> 
							   
    							<div class="form-group">
    							    <div class="col-md-3">
    							        <select class="form-control" id="book" name="book"></select>
    							    </div>
    							    <div class="col-md-3">
    							        <input type="text" class="form-control" id="previousDue" name="previousDue" style="margin-bottom: 2%;" value="" placeholder=" Previous Due " Readonly/>
    							    </div>
            						<div class="col-md-6">
    							        <input type="text" class="form-control" id="orderRemarks" name="orderRemarks" value="" placeholder=" Remarks " />
    							    </div>
    							    <input type="hidden" id="customerEmail" name="customerEmail"/>
    							</div>
							</div>
						</div>
						<div style="padding: 2%;margin-bottom: 3%;">
							<a  style="width: 25%;margin-left: 5%;box-shadow: 1px 1px 1px 0px #909090;" href="orderPanel.php" class="btn btn-primary" id="continueOrder">
							    <span class="glyphicon glyphicon-share-alt" style="color: #ffbf00;"></span> Continue Order
							</a>
							<a  style="width: 15%;box-shadow: 1px 1px 1px 0px #909090;" href="#" class="btn btn-danger" id="clear_cart">
							    <span class="glyphicon glyphicon-refresh" style="color: #ffbf00;"></span> Clear
							</a>
							<a  style="width: 20%;box-shadow: 1px 1px 1px 0px #909090;" id="check_out_cart12" href="#" class="btn btn-default" onclick=discountOfferPreview()>
							    <span class="glyphicon glyphicon-th-list" style="color: #000cbd;"></span> Preview
							</a>
							<button type="submit" style="width: 30%;box-shadow: 1px 1px 1px 0px #909090;" href="#" class="btn btn-success" id="check_out_cart">
							<span class="glyphicon glyphicon-shopping-cart" style="color: #000cbd;"></span> Place Order
							</button>
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
    include 'includes/previousProducts-modal.php';
 ?>
<script src="dist/js/select2.min.js"></script>
<script src="includes/js/orderCheckOutView.js"></script>
<!--script src="includes/js/manageWholeSale.js"></script-->
<script>
    setTimeout(function() {
    $('#loader').fadeOut('fast');
	}, 1000); 
	
	$("#orderCustomer").select2( {
	placeholder: "Select Customer",
	allowClear: true
    });
    $("#transportName").select2( {
	placeholder: "Select Transport Name",
	allowClear: true
    });
    

    var userType = "<?php echo $_SESSION['userType'];?>";
    selectSalesMan(<?php echo $_SESSION['user'];?>);
</script>
</body>
</html>