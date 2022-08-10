<?php 
    $conPrefix = '';
    include 'includes/session.php'; 
    include 'includes/header.php'; 
?>
<style>
	fieldset.scheduler-border {
    border: 1px groove #ddd !important;
    padding: 0 1.4em 1.4em 1.4em !important;
    margin: 0 0 1.5em 0 !important;
    -webkit-box-shadow:  0px 0px 0px 0px #000;
            box-shadow:  0px 0px 0px 0px #000;
}
legend.scheduler-border {
        font-size: 1.2em !important;
        font-weight: bold !important;
        text-align: left !important;
        width:auto;
        padding:0 10px;
        border-bottom:none;
    }
</style>
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
        Warehouse Transfer 
      </h1>
      <ol class="breadcrumb">
        <li><a href="user-home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Warehouse Transfer</li>
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
                  <a href="#warehouseTransfer" data-toggle="modal"  class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i> Warehouse Transfer [Product] </a>
                </div>
				<div class="col-xs-6">
					<div id='divMsg' class='alert alert-success alert-dismissible successMessage'></div>
					<div id='divErrorMsg' class='alert alert-danger alert-dismissible errorMessage'></div>
				</div>
            </div>
            <div class="box-body">
				<table id="manageWareHouseTransferTable" class="table table-bordered">
					<thead>
					  <th>#SN</th>
					  <th>Transfer Date</th>
					  <th>Product</th>
					  <th>Warehouse From</th>
					  <th>Warehouse To</th>   
					  <th>Transfer Quantity</th>
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
<?php 
    include 'includes/scripts.php'; 
    include 'includes/wareHouseTransfer-modal.php';    
    include 'includes/productAdvanceSearch-modal.php';
?>
    <script src="dist/js/select2.min.js"></script>
    <script src="includes/js/manageWareHouseTransfer.js"></script> 
</body>
</html>