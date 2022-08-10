<?php 
    $conPrefix = '';
    include 'includes/session.php'; 
    include 'includes/header.php'; 
?>
<link rel="stylesheet" href="dist/css/select2.min.css" />
<style>
    a.w3view-cart {
    outline: none;
    border: 1px solid #cac5c5;
    width: 30%;
    background: #d9d9d9;
    font-size: 24px;
    color: #070bff;
    padding: 2%
    border-radius: 5%;
    }
    a.w3view-cart:hover {
    outline: none;
    border: 1px solid #cac5c5;
    background:#337ab7;
    width: 30%;
    font-size: 24px;
    color: #fff;
    padding: 2%
    border-radius: 5%;
    }
    .select2 {width:100%!important;}
</style>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Order Panel</h1>
        <ol class="breadcrumb">
            <li><a href="user-home.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Order Panel</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
	  <link rel="stylesheet" href="css/buttons.dataTables.min.css"/>
      <div class="row">
        <div class="col-xs-12">
            <div class="box">
            <div class="box-header with-border">
                <div class="col-lg-5 col-lg-4 col-xl-3">
                    <div class="form-group">
                        <?php
                        $brandId = '';
                        if(isset($_GET['id'])){
                            $brandId = $_GET['id'];
                        }else{
                            $brandId = '0';
                        }
                        ?>
                        <input type='hidden' name='brandId' id='brandId' value=<?php echo $brandId;?> />
                        <select id="table-filter" class='form-control' name="brandName">
        					<option value="0" selected>All</option>
        						<?php
        						  $sql = "SELECT id,brandName FROM tbl_brands WHERE status='Active' ORDER BY brandName ASC";
        						  $query = $conn->query($sql);
        						  while($prow = $query->fetch_assoc()){
        						      if($brandId == $prow['id']){
        						          $selected='Selected';
        						      }else{
        						          $selected = '';
        						      }
        					        echo "<option value='".$prow['id']."' ".$selected.">".$prow['brandName']."</option>";
        						  }
        						?>
    			    	</select>
                    </div>
                </div>
                <div class="col-md-5 col-lg-4 col-xl-3" style="text-align: center;">
                    <span id='divMsg' class='alert alert-success alert-dismissible' style='padding: 8px;;display:none;'></span>
                </div>
                <div class="col-md-2 col-lg-4 col-xl-3" style="text-align: right;">
                    <div class="form-group">
                        <a href="orderCheckOutView.php" class="w3view-cart" type="submit" name="submit" value="" >
        					<i class="fa fa-cart-arrow-down" aria-hidden="true"> (<span id="cartCount">0</span> items)</i>
        				</a>
    				</div>
    			</div>	
            </div>
            <div class="box-body">
                <table id="manageOrderTable" class="table table-bordered" style="width:100%">
                    <thead>
                        <th>SN</th>
                        <th>Image</th>
                        <th style="width: 50%;">Product Details</th>                  
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Action</th>
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
<script src="includes/js/manageOrder.js"></script> 
<script src="dist/js/select2.min.js"></script>
</body>
</html>
<script type="text/javascript">
    $("#table-filter").select2({
		placeholder: "All Brand",
		allowClear: true
	});
</script>	


