<?php 
    $conPrefix = '';
    include 'includes/session.php'; 
    include 'includes/header.php'; 
    $toDay = (new DateTime())->format("Y-m-d H:i:s");
    $loginID = $_SESSION['user'];
    ordersGoBackToPending();
    $type = htmlspecialchars($_GET["page"]);
    if(isset($_GET['notId'])){
        $notId = htmlspecialchars($_GET["notId"]);
        if(strtolower($_SESSION['userType']) == 'super admin'){
            $sql = "UPDATE tbl_notification SET status='Checked',checked_by='$loginID',checked_time='$toDay'
                    WHERE notify_for='createOrder' AND id='$notId'";
            $conn->query($sql);
        }else if(strtolower($_SESSION['userType']) == 'sales executive'){
            $sql = "UPDATE tbl_notification SET status='Checked',checked_by='$loginID',checked_time='$toDay'
                    WHERE notify_for='createOrder' AND id='$notId'";
            $conn->query($sql);
        }
        $sql = "";
    }else{
       
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
    <section class="content-header">
      <h1>
        <?php echo $type; ?> Order List
      </h1>
      <ol class="breadcrumb">
        <li><a href="user-home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><?php echo $type; ?> Order List</li>
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
				    <div id='divMsg' class='alert alert-success alert-dismissible' style='display:none;'></div>
				    <div id='divErrorMsg' class='alert alert-danger alert-dismissible' style='display:none;'></div>
                    <div id="customer_div"><select id="customers" name="customers"></select></div>
				</div>
                <div class="col-xs-3">
                    <?php if($type=='Completed'){
                        
                    
                    
                    ?>
					<select id="sortData" class="form-control" name="sortData" style='float:right;'>
					    <!--option value="0,0">All</option-->
					    <?php
    					    $initialYear = 2020;
                            
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
    					    $toDate = date('Y-m-d');
    					    echo '<option value="'.$fromDate.','.$toDate.'" >30 Days</option>';
    				        echo '<option value="-1,-1">More Search By Party Name</option>';
					    ?>
					</select>
					<?php }?>
				</div>
            </div>
            <div class="box-body">
                 <input type="hidden" id="type" name="type" value="<?php echo $type;?>" />
				<table id="orderSalesTableView" class="table table-bordered" width="100%">
					<thead>
					    <th style="width:6%;">SN</th>
					    <th style="width:10%;">Order No<br>Sold By</th>
					    <th>Customer Details</th>   
					    <th>Sale Products</th>
					    <th style="width:11%;">Price Details</th>
					    <th style="width:11%;">Status</th>
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
    <script src="includes/js/manageOrder.js"></script> 
</body>
</html>
