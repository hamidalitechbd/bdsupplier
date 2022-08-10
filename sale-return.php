<?php 
    $conPrefix = '';
    include 'includes/session.php'; 
    include 'includes/header.php'; 
	if(isset($_GET['salesId']) && isset($_GET['salesType'])){
		$getSalesId=$_GET['salesId'];
		$getType=$_GET['salesType'];
	}else{
		header("Location: user-home.php");
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
        <?php
            if($getType=='PartySale'){
                $pageHeader = 'Party Sales Return';
            }else if ($getType == 'WalkinSale'){
                $pageHeader = 'Walk-in Sales Return';
            }else if($getType=='TS'){
                $pageHeader = 'TS Return';
            }else if($getType=='FS'){
                $pageHeader = 'FS Return';
            }
            echo $pageHeader;
        ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="manage-view.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><?php
            if($getType=='PartySale'){
                $pageHeader = 'Party Sales Return';
            }else if ($getType == 'WalkinSale'){
                $pageHeader = 'Walk-in Sales Return';
            }else if($getType=='TS'){
                $pageHeader = 'TS Return';
            }else if($getType=='FS'){
                $pageHeader = 'FS Return';
            }
            echo $pageHeader;
        ?></li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
	  <link rel="stylesheet" href="css/buttons.dataTables.min.css"/>
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="">
				<div class="col-xs-6"><!--<h3><?php echo $pageHeader;?></h3>--></div>
				<div class="col-xs-6">
					<div id='divMsg' class='alert alert-success alert-dismissible successMessage'></div>
					<div id='divErrorMsg' class='alert alert-danger alert-dismissible errorMessage'></div>
				</div>
            </div>
            <div class="box-body">
				<form class="form-horizontal" id="form_salesReturn" method="POST" action="#">
					<div class="form-group">
						<div class="col-sm-4">
							<label for="returnDate">Return Date:</label> 
							<?php
    							$saleDateDisable = '';
    							if(strtolower($_SESSION['userType']) != 'super admin'){
    			                    $saleDateDisable = 'Disabled';
    			                }
							?>
							<input type="date" style="line-height: 10px;" class="form-control" id="returnDate" value="<?php echo date('Y-m-d');?>" <?php echo $saleDateDisable;?> name="returnDate" placeholder=" Sale Return Date ">
						</div>
						<div class="col-sm-4">
						<label for="chalanNumber">Sales Code:</label> 
							<input type="text" class="form-control" id="salesCode" name="salesCode" placeholder=" Sales Code " readonly />
							<input type="hidden" class="form-control" id="salesId" name="salesId" placeholder=" Sales ID " value="<?php echo $getSalesId; ?>" readonly />
							<input type="hidden" class="form-control" id="salesType" name="salesType" placeholder=" Sales Type " value="<?php echo $getType; ?>" readonly />
						</div>
						<div class="col-sm-4">
							<label for="add_supplier">Customer Name: </label> 
							<?php
			                    if($getType=='PartySale'){
                                    echo '<select class="form-control" id="customerId" name="customerId"  style="width:100%;" disabled>';
                                    $sql12 = "SELECT id,tbl_customerId FROM `tbl_sales` WHERE id='".$getSalesId."'";
                                    $result = $conn->query($sql12);
                                    while( $row = mysqli_fetch_array($result) ){
                                        $tbl_customerId = $row['tbl_customerId'];
                                    }
                                    
                                    /*$sql = "SELECT id,partyName,tblCity,locationArea FROM `tbl_party` WHERE status='Active' AND tblType<>'$tblType' ORDER BY `id`  DESC";*/
                                    $sql = "SELECT id,partyName,tblCity,locationArea FROM `tbl_party` WHERE id='".$tbl_customerId."' AND status='Active' ORDER BY `id`  DESC";
                                    $result = $conn->query($sql);
                                    while( $row = mysqli_fetch_array($result) ){
                                        $partyName = $row['partyName'].' ('.$row['tblCity'].' - '.$row['locationArea'].')';
                                        echo '<option value="'.$row['id'].'">'.$partyName.'</option>';
                                    }
                                    echo '</select>';
                                }else if ($getType == 'WalkinSale'){
                                    echo '<input type="text" class="form-control" id="customerName" name="customerName" value="" style="width:100%;" readonly >';
                                    echo '<input type="hidden" class="form-control" id="customerId" name="customerId" value="" style="width:100%;" readonly >';
                                }else if($getType=='FS'){
                                    echo '<select class="form-control" id="customerId" name="customerId"  style="width:100%;" disabled>';
                                    $sql12 = "SELECT id,tbl_customerId FROM `tbl_sales` WHERE id='".$getSalesId."'";
                                    $result = $conn->query($sql12);
                                    while( $row = mysqli_fetch_array($result) ){
                                        $tbl_customerId = $row['tbl_customerId'];
                                    }
                                    
                                    /*$sql = "SELECT id,partyName,tblCity,locationArea FROM `tbl_party` WHERE status='Active' AND tblType<>'$tblType' ORDER BY `id`  DESC";*/
                                    $sql = "SELECT id,partyName,tblCity,locationArea FROM `tbl_party` WHERE id='".$tbl_customerId."' AND status='Active' ORDER BY `id`  DESC";
                                    $result = $conn->query($sql);
                                    while( $row = mysqli_fetch_array($result) ){
                                        $partyName = $row['partyName'].' ('.$row['tblCity'].' - '.$row['locationArea'].')';
                                        echo '<option value="'.$row['id'].'">'.$partyName.'</option>';
                                    }
                                    echo '</select>';
                                }				
							?>
                            
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-4">
							<label for="chalanNo">Requisition No </label> 
                            <input type="text" style="line-height: 10px;" class="form-control" id="requisitionNo" name="requisitionNo" placeholder=" Invoice Number " readonly>
						</div>
						<div class="col-sm-4">
							<label for="purchaseDate">Sales Date:</label> 
							<input type="date" style="line-height: 10px;" class="form-control" id="salesDate" name="salseDate" placeholder=" sales Date " readonly>
						</div>
						<div class="col-sm-4" style="display:none;">
						    <label for="wareHouse">Warehouse:</label> 
							<select class="form-control" name="wareHouse" id="wareHouse" style="width:100%;">
							    <option value=''>~~ Select Warehouse ~~</option>
                                <?php
                                    /*$sql = "SELECT id, wareHouseName FROM tbl_warehouse WHERE status='Active' AND deleted='No' ORDER BY id  DESC";
                                    $query = $conn->query($sql);
                                    while ($prow = $query->fetch_assoc()) {
                                        echo "<option value='" . $prow['id'] . "'>" . $prow['wareHouseName'] . "</option>";
                                    }*/
                                ?>
                            </select>
						</div>
						<input type="hidden" style="line-height: 10px;" class="form-control" id="purchaseId" name="purchaseId" placeholder=" Hidden Purchase Id ">
						
					</div>
					<div class="form-group">
						<div class="col-sm-12">
							<table id="manageSalesReturnTable" class="table table-bordered"></table>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-12">
						<button type="submit" class="btn btn-primary btn-flat" name="btn_salesReturn" id="btn_purchaseReturn"><i class="fa fa-save"></i> Return Sales </button>
						<a href="saleReturnView.php?salesType=<?php echo $getType;?>" class="btn btn-primary btn-flat"><i class="fa fa-mail-reply"></i> Back </a>
						</div>
					</div>
				</form>
          </div>
        </div>
      </div>
    </section>   
  </div>
  <?php include 'includes/footer.php'; ?>
</div>
<?php include 'includes/scripts.php'; ?>
<script src="dist/js/select2.min.js"></script>
<script src="includes/js/manageSalesReturn.js"></script> 
<script type="text/javascript">
	//$("#returnDate").val(new Date().toISOString().substring(0, 10));
	var salesId = "<?php echo $getSalesId;?>";
	var salesType  = "<?php echo $getType;?>";
	loadSales(salesId, salesType);
</script>
</body>
</html>