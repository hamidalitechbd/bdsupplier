<?php $conPrefix = '';
    include 'includes/session.php'; 
    include 'includes/header.php'; 
    $sessionId = time().uniqid();
	include '../timezone.php'; 
	$today = date('Y-m-d');
	$year = date('Y');
	if(isset($_GET['year'])){
    $year = $_GET['year'];
	}
	$first_date = date('Y-m-d',strtotime('first day of this month'));
	$last_date = date('Y-m-d',strtotime('last day of this month'));
	$first_day_of_year=date('Y-m-d', strtotime('first day of january this year'));
?>
<style>
    th,td{text-align: center;}
    
</style>
<link rel="stylesheet" href="dist/css/select2.min.css" />
<body class="hold-transition skin-blue sidebar-mini">
<script type='text/javascript'>
	function showMyData(){  
	//alert('Generate Reports From Start To End Date: '+$('#startDate').val());
	var walkin = document.getElementById("add_walkin").value;
      if(walkin ==''){
      alert('Please select Walkin Customer Name select-box'); return false;
      }
      else{
	$.ajax({ 
			type: "POST",
			url :"walkinLedgerReportViewPdf.php",
			data:{
				 	cName:$('#add_walkin').val(),
				 	startDate:$('#startDate').val(),
					endtDate:$('#endtDate').val()
			 },
			 beforeSend: function () {
                    $('#loading').show();
                },
			 success: function(data){
				// alert(data);
				$("#loader").load(" #loader");
				 $("#myDiv").html(data);
			 },
			  complete: function () {
                    $('#loading').hide();
                }
	});
	}
	}
	
</script>

<div class="wrapper">

  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>
	
	
	
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
		<h1> Walkin Sales Ledger Reports View & Print </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Walkin Sales Ledger Reports View & Print </li>
      </ol>
    </section>
    <!-- Main content -->
		<section class="content">
		<div class="row">
        <div class="col-xs-12">
			<div class="box">
				<div class="box-body" style="height: auto;"> 
				<h4 style="color: gray;text-align: center;"> Walkin Sales Ledger Wise Reports </h4>
					
						<form  class="form-horizontal" method="POST">
							<div class="col-md-12">
							    <div class="col-md-6">
							        <label for="categoryName" class="control-label">Walkin Customer Name :</label>
    								
    								<select class="form-control" id="add_walkin" name="add_walkin"  style="width:100%;" required>
                                    <option value="" selected>~~ Select Party Name ~~</option>
                                    <?php
                                    $sql = "SELECT id,customerName,customerAddress,phoneNo FROM `tbl_walkin_customer` WHERE status!='Inactive'";
                                    $query = $conn->query($sql);
                                    while ($prow = $query->fetch_assoc()) {
                                        echo "<option value='" . $prow['id'] . "'>" . $prow['customerName'] . " - " . $prow['customerAddress'] . " " . $prow['phoneNo'] . "</option>";
                                    }
                                    ?>
                                    </select>
                                    
							    </div>
    							<div class="col-md-2">
    								<label for="categoryName" class="control-label">Start Date :</label>
    								<input name="min" id="startDate" style="width:105%;" class="form-control datetimepicker" placeholder="Select Start date" name="startDate" type="date" value="<?php echo date('Y-m-d');?>" data-date-format="yyyy-mm-dd" />					
    							</div>
							    <label class="control-label col-md-1" style="text-align: center;margin-top: 2.2%;">-To-</label>
    							<div class="col-md-2">
    								<label for="categoryName" class="control-label">End Date :</label>
    								<input name="max" id="endtDate" style="width:105%;" class="form-control datetimepicker" placeholder="Select End date" name="endtDate" type="date" value="<?php echo date('Y-m-d');?>" data-date-format="yyyy-mm-dd"/>
    							</div>
    							<div class="col-md-1">
    								<button type="button" id="btndisplay" class="btn btn-default btn-flat pull-left" name="btndisplay" onclick="showMyData();" style="background-color: #3f3e93;color: #fff;margin-top: 48%;border-color: #3f3e93;"><i class="fa fa-search"></i> Search </button>
    								
    							</div>
    					    </div>
						</form><br><br>
						<!--input type="submit" id="btndisplay" value="show" onclick="showMyData();"-->
						<div id="myDiv"></div>
						 <br><br>
					
				</div>
            </div>
         </div>
        </div>
		</section> 
		
  </div>
    
  <?php 
  include 'includes/footer.php'; 
  ?>
</div>
<?php include 'includes/scripts.php'; ?>
<script src="dist/js/select2.min.js"></script>
<script>
    $("#add_walkin").select2( {
    	placeholder: "Select Party Name",
    	allowClear: true
    	} );
    	
    
   
 </script>  	
</body>
</html>
