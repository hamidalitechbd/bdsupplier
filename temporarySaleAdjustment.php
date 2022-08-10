<?php 
    $conPrefix = '';
    include 'includes/session.php'; 
    include 'includes/header.php'; 
?>
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
        Temporary Sale Adjustment
      </h1>
      <ol class="breadcrumb">
        <li><a href="user-home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Temporary Sale Adjustment</li>
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
                  <!--<a href="temporarySale.php"  class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i> Temporary Sale </a>-->
                </div>
				<div class="col-xs-6">
					<div id='divMsg' class='alert alert-success alert-dismissible successMessage'></div>
					<div id='divErrorMsg' class='alert alert-danger alert-dismissible errorMessage'></div>
				</div>
            </div>
            <div class="box-body">
                <form class="form-horizontal" id="form_tsAdjustment" method="POST" action="#">
                    <div class="form-group">
                        <div class="col-md-4">
                            <label for="customers"> Date </label>
                            <input type="date" id="date" name="date" value=<?php echo date('Y-m-d');?> class="form-control" style="padding: inherit;"/>
    					</div>
    					<div class="col-md-4">
				            <label for="customers"> Customer </label>
                            <select class="form-control" id="customers" style="width:100%;" required> 
    						    <option value="" selected>~~ Select Customers ~~</option>
    							<?php
                                    $sql = "SELECT id, partyName,locationArea,tblCity
                                            FROM tbl_party
                                            WHERE deleted='No' AND status='Active' AND partyType <> 'Suppliers'
                                            ORDER BY id ASC";
                                    $res = $conn->query($sql);
                                    while($row = $res->fetch_assoc()){
                                        echo '<option value="'.$row['id'].'">'.$row['partyName'].' - '.$row['locationArea'].' '.$row['tblCity'].'</option>';
                                    }
    							?>
    						</select>
    					</div>
    					<div class="col-md-4">
				            <label for="customers"> Return WareHouse </label>
                            <select class="form-control" id="wareHouse" style="width:100%;" required> 
    						    <option value="" selected>~~ Select WareHouse ~~</option>
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
                        <div class="col-md-4">
                            <label for="customers"> Project Name </label>
                            <input type="text" id="pojectName" name="pojectName" class="form-control" placeholder="Project Name"/>
    					</div>
    					<div class="col-md-4">
                            <label for="customers"> Requisition No </label>
                            <input type="text" id="requisitionNo" name="requisitionNo" class="form-control" placeholder="Requisition No"/>
    					</div>
    					<div class="col-md-4">
                            <label for="customers"> Remarks </label>
                            <input type="text" id="remarks" name="remarks" class="form-control" placeholder="Remarks Here"/>
    					</div>
    				</div>	
					<div class="form-group">
					    <div class="col-md-12">
    						<div id="loader" style="display:none; text-align:center;" class="col-md-12"><i class='fa fa-spinner fa-spin' style='font-size:50px;color:green'></i></div>
            				<table id="manageTSTable" class="table table-bordered"></table>
        				</div>
                    </div>
                </form>
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
    <script src="includes/js/manageTemporarySaleAdjustment.js"></script> 
</body>
</html>