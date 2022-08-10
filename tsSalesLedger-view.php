<?php $conPrefix = '';
    include 'includes/session.php'; 
    include 'includes/header.php'; 
    /*$sessionId = time().uniqid();
	include '../timezone.php'; 
	$today = date('Y-m-d');
	$year = date('Y');
	if(isset($_GET['year'])){
    $year = $_GET['year'];
	}
	$first_date = date('Y-m-d',strtotime('first day of this month'));
	$last_date = date('Y-m-d',strtotime('last day of this month'));
	$first_day_of_year=date('Y-m-d', strtotime('first day of january this year'));*/
?>
<style> th,td{text-align: center;} </style>
<link rel="stylesheet" href="dist/css/select2.min.css" />
<body class="hold-transition skin-blue sidebar-mini">
    <script type='text/javascript'>
	
	function showMyData(){  
	//alert('Generate Reports From Start To End Date: '+$('#startDate').val());
	var ts = document.getElementById("add_ts").value;
      if(ts ==''){
      alert('Please select Ts Customer Name select-box'); return false;
      }
      else{
	$.ajax({ 
			type: "POST",
			url :"tsLedgerReportViewPdf.php",
			data:{
				 	cName:$('#add_ts').val(),
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
		<h1> TS Sales Ledger Reports View & Print </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">TS Sales Ledger Reports View & Print </li>
      </ol>
    </section>
    <!-- Main content -->
		<section class="content">
		<div class="row">
        <div class="col-xs-12">
			<div class="box">
				<div class="box-body" style="height: auto;"> 
				<h4 style="color: gray;text-align: center;"> TS Sales Ledger Wise Reports </h4>
					
						<form  class="form-horizontal" method="POST">
							<div class="col-md-12"><div class="col-md-1"></div>
							    <div class="col-md-2">
    								<a href="TsSalesLedgerpdf-view.php" target="_blank" class="btn btn-default btn-flat pull-left" style="margin-top: 19%;width: 100%;"><i class="fa fa-file-pdf-o" style="color: red;"></i>  TS ALL </a>
    							</div>
							    <div class="col-md-6">
							        <label for="categoryName" class="control-label">TS Customer Name :</label>
    								
    								<select class="form-control" id="add_ts" name="add_ts"  style="width:100%;" required>
                                        <option value="" selected>~~ Select TS Name ~~</option>
                                        <?php
                                        $sql = "SELECT DISTINCT tbl_party.id, tbl_party.partyName,tbl_party.partyAddress
                                            FROM tbl_tsalesproducts 
                                            LEFT OUTER JOIN tbl_temporary_sale ON tbl_temporary_sale.id = tbl_tsalesproducts.tbl_tSalesId AND tbl_temporary_sale.deleted = 'No'
                                            LEFT OUTER JOIN tbl_party ON tbl_temporary_sale.tbl_customerId = tbl_party.id
                                            WHERE tbl_tsalesproducts.deleted = 'No' AND tbl_tsalesproducts.status = 'Running'
                                            ORDER BY tbl_temporary_sale.tSalesDate DESC";
                                        $query = $conn->query($sql);
                                        while ($prow = $query->fetch_assoc()) {
                                            echo "<option value='" . $prow['id'] . "'>" . $prow['partyName'] . " - " . $prow['partyAddress'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                
    							<input name="min" id="startDate" name="startDate" type="hidden" value="<?php echo date('Y-m-d');?>" data-date-format="yyyy-mm-dd" />					
    							
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
    $("#add_ts").select2( {
    	placeholder: "Select TS Name",
    	allowClear: true
    	} );
    	
    
   
 </script>  	
</body>
</html>
