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
        Final Sale
      </h1>
      <ol class="breadcrumb">
        <li><a href="user-home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Final Sale</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
	  <link rel="stylesheet" href="css/buttons.dataTables.min.css"/>
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border">
                <div class="col-xs-3">
                </div>
                <div class="col-xs-6">
                    <div id="customer_div"><select id="customers" name="customers"></select></div>
				</div>
				<div class="col-xs-3">
					<div id='divMsg' class='alert alert-success alert-dismissible successMessage'></div>
					<div id='divErrorMsg' class='alert alert-danger alert-dismissible errorMessage'></div>
					<select id="sortData" class="form-control" name="sortData" style='float:right;'>
                        <option value="0,0">All</option>
                        <?php
                            $initialYear = 2020;
                            
                            $fromDate = date('Y-m-d',strtotime('+1 days'));
                            $toDate = date('Y-m-d', strtotime('+1 days'));
                            echo '<option value="'.$fromDate.','.$toDate.'">Next Day</option>';
                            
    					    $fromDate = date('Y-m-d', strtotime('-0 days'));
    					    $toDate = date('Y-m-d');
    					    echo '<option value="'.$fromDate.','.$toDate.'" Selected>Today</option>';
    					    
    					    $fromDate = date('Y-m-d', strtotime('-2 days'));
    					    $toDate = date('Y-m-d');
    				        echo '<option value="'.$fromDate.','.$toDate.'" >2 Days</option>';
    					    
    					    $fromDate = date('Y-m-d', strtotime('-7 days'));
    					    $toDate = date('Y-m-d');
    				        echo '<option value="'.$fromDate.','.$toDate.'" >7 Days</option>';
    					    
    					    $fromDate = date('Y-m-d', strtotime('-15 days'));
    					    $toDate = date('Y-m-d');
    				        echo '<option value="'.$fromDate.','.$toDate.'" >15 Days</option>';
    					    
    					    $fromDate = date('Y-m-d', strtotime('-30 days'));
    					    $fromDate = date('Y-m-d', strtotime('-30 days'));
    					    $toDate = date('Y-m-d');
    					    echo '<option value="'.$fromDate.','.$toDate.'" >30 Days</option>';
    				        echo '<option value="-1,-1">More Search By Party Name</option>';
    				        /*$fromDate = date('Y-m-d', strtotime('-45 days'));
    				        $toDate = date('Y-m-d');
    					    echo '<option value="'.$fromDate.','.$toDate.'" >45 Days</option>';
                            
                            $fromDate = date('Y-m-d', strtotime('-60 days'));
    				        $toDate = date('Y-m-d');
    					    echo '<option value="'.$fromDate.','.$toDate.'" >60 Days</option>';
    					    
                            $fromDate = date('Y-m-d', strtotime('-180 days'));
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
				<table id="manageFSTable" class="table table-bordered" width="100%">
					<thead>
                        <th style="width:6%;">SN#</th>
                        <th style="width:10%;">Sale Code</th>
                        <th>Customer</th>   
                        <th>Sold By</th>
                        <th>Sale Products</th>
                        <th style="width: 11%;">Price Details</th>
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
</div>
<?php include 'includes/scripts.php'; ?>
    <script src="dist/js/select2.min.js"></script>
    <script src="includes/js/manageFSAction.js"></script> 
</body>
</html>