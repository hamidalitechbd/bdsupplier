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
    <?php
        
    ?>
    <section class="content-header">
      <h1>
        Manage User
      </h1>
      <ol class="breadcrumb">
        <li><a href="manageUser-view.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage User</li>
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
				<a href="#addnew" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i> Add New User</a>
            </div>
			<div class="col-xs-6">
				<div id='divMsg' class='alert alert-success alert-dismissible successMessage'></div>
			</div>
			</div>
            <div class="box-body">
              <table id="manageUserTable" class="table table-bordered">
                <thead>
                  <th>SN</th>
                  <th>Full Name</th>
                  <th>User Name</th>
                  <th>Email</th>
                  <th>Contact No</th>
                  <th>User Type</th>
                  <th>Status</th>
                  <th width='8%'>Action</th>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>   
    
  </div>
   
  <?php include 'includes/footer.php'; ?>
  <?php include 'includes/manageUser-modal.php'; ?>
</div>
<script src="notify.js"></script>
<?php include 'includes/scripts.php'; ?>
<script src="dist/js/select2.min.js"></script>
<script src="includes/js/manageUser.js"></script> 
</body>
</html>
