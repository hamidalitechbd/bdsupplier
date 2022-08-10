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
        Import
      </h1>
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
              <div class="col-xs-6">
                  <a href="purchaseForeign-add.php"  class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i> Add New Import </a>
              </div>
				<div class="col-xs-6">
					<div id='divMsg' class='alert alert-success alert-dismissible' style='margin: -13% -5% -4% 20%;display:none;'></div>
					<div id='divErrorMsg' class='alert alert-danger alert-dismissible' style='margin: -13% -5% -4% 20%;display:none;'></div>
					<select id="sortData" name="sortData" style='float:right;'>
					    <option value="0,0">All</option>
					    <?php
    					    $initialYear = 2020;
    					    $fromDate = date('Y-m-d', strtotime('-7 days'));
    					    $toDate = date('Y-m-d');
    				        echo '<option value="'.$fromDate.','.$toDate.'" Selected>7 Days</option>';
    					    $fromDate = date('Y-m-d', strtotime('-30 days'));
    					    //$toDate = date('Y-m-d');
    				        echo '<option value="'.$fromDate.','.$toDate.'">30 Days</option>';
    				        $fromDate = date('Y-m-d', strtotime('-180 days'));
    					    //$toDate = date('Y-m-d');
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
              <input type="hidden" id="type" name="type" value="<?php echo $type;?>" />
				<table id="managePurchaseTable" class="table table-bordered">
					<thead>
					  <th>SN</th>
					  <th>Import Code</th>
					  <th>Import Date</th>
					  <th>Supplier</th>                  
					  <th>LC No</th>
					  <th>Imported Products</th>
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
</div>
<?php include 'includes/scripts.php'; ?>
    <script src="dist/js/select2.min.js"></script>
	
    <script src="includes/js/managePurchaseForeign.js"></script> 
	

</body>
<?php
        //}else{
           // header('location: http://jafree.alitechbd.com/');
        //}
    ?>
</html>