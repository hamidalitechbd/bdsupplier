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
              <div class="col-xs-6">
                  <!--a href="purchaseLocal-return.php"  class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i> Local Purchase Return </a-->
              </div>
				<div class="col-xs-6">
					<div id='divMsg' class='alert alert-success alert-dismissible successMessage'></div>
					<div id='divErrorMsg' class='alert alert-danger alert-dismissible errorMessage'></div>
					<select id="sortData" name="sortData" style='float:right;'>
					    <option value="0,0">All</option>
					    <?php
    					    $initialYear = 2019;
    					    $fromDate = date('Y-m-d', strtotime('-30 days'));
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
				<table id="managePurchaseReturnTable" class="table table-bordered">
					<thead>
					  <th>SN#</th>
					  <th>Return Code</th>
					  <th>Return Date</th>
					  <th>Purchase Code</th>
					  <th>Return Products</th>
					  <th>Status</th>
					  <th style="width:8%;">Action</th>
					</thead>
				</table>
            </div>
          </div>
        </div>
      </div>
    </section>   
    
  </div>
   
  <?php include 'includes/footer.php'; ?>
  <?php include 'includes/manageItem-modal.php'; ?>
</div>
<?php include 'includes/scripts.php'; ?>
    <script src="dist/js/select2.min.js"></script>
	
    <script src="includes/js/managePurchaseLocalReturn.js"></script> 
	

</body>
<?php
        //}else{
           // header('location: http://jafree.alitechbd.com/');
        //}
    ?>
</html>