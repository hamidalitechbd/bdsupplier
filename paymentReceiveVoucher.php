<?php 
    $conPrefix = '';
    include 'includes/session.php'; 
    include 'includes/header.php'; 
	if(isset($_GET['purid'])){
		$getPurchaseCode=$_GET['purid'];
	}else{
		$getPurchaseCode = '';
	}
?>
<style>
.select2-container .select2-selection--single .select2-selection__rendered{
    white-space: wrap !important;
    width:200px !important;
 }
 .select2-search--dropdown .select2-search__field {
 width: 98% !important;
 white-space: wrap !important;
 
 }
 .form-control.has-error{
     border-color: #dd4b39;
    box-shadow: none;
 }
</style>
<body class="hold-transition skin-blue sidebar-mini sidebar-collapse">
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
      <h1>Payment (Cheque) Receive Voucher</h1>
      <ol class="breadcrumb">
        <li><a href="manage-view.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Payment (Cheque) Receive</li>
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
					<div id='divMsg' class='alert alert-success alert-dismissible' style='margin: -13% -5% -4% 20%;display:none;'></div>
					<div id='divErrorMsg' class='alert alert-danger alert-dismissible' style='margin: -13% -5% -4% 20%;display:none;'></div>
				</div>
            </div>
            <div class="box-body">
              
			  
				<form class="form-horizontal" id="form_paymentReceivedVoucher" method="POST" action="#">
					<div class="col-sm-12">
					   <div class="form-group">
					       <div class="col-sm-2"></div>
					        <div class="col-sm-4">
					            <label>Date</label>
								<input type="date" class="form-control" name="add_date" id="add_date" style="padding: inherit;" value=<?php echo date("Y-m-d");?> >
							</div>
					        <div class="col-sm-4">
					            <label>Payment Mode</label>
								<select onchange="yesnoCheck(this);" class="form-control" name="add_payMode" class="form-control" id="add_payMode">
									<option value="" selected>~~ Select Payment Mode ~~</option>
									    <?php
									    $sql = "SELECT id, methodName
                                                FROM tbl_paymentMethod
                                                WHERE status = 'Active' AND deleted = 'No' 
                                                ORDER BY `tbl_paymentMethod`.`sort_order` ASC";
                                        $query = $conn->query($sql);
            							while ($prow = $query->fetch_assoc()) {
            								echo "<option value='" . $prow['methodName'] . "'>" . $prow['methodName'] . "</option>";
            							 }
            							 ?>
									<!--<option value="CHEQUE">Cheque</option>
									<option value="EFT">EFT</option>-->
								</select>
							</div>
							<div class="col-sm-2"></div>
					   </div>
					<div class="form-group">
						<div class="table-responsive">
							<table class="table table-bordered" id="dynamic_field" style="display: none;">  
                                <!--tr>
                                    <td>Party Name</td><td>Amounts</td><td>Discounts</td><td>Deposit Bank</td><td>Remarks</td><td>More</td>
                                </tr-->
                                <tr>  
									<td class="col-sm-3">
										<select class="form-control" name="partyName[0]" id="name_0" style='width:100%' onchange="calculateDue(this.value, 0)">
											<option value="" selected>~~ Party Name ~~</option>
											<?php
											$sql = "SELECT id,partyName,locationArea, tblCity FROM `tbl_party` WHERE status!='Inactive' AND deleted='No' AND tblType <> 'Suppliers'";
											$query = $conn->query($sql);
											while ($prow = $query->fetch_assoc()) {
												echo "<option value='" . $prow['id'] . "'>" . $prow['partyName'] . " - ".$prow['locationArea']." - ".$prow['tblCity']."</option>";
											}
											?>
										</select> <br>
										<input type="text" class="form-control" style="margin-top: 3%;" name="remarks[]" placeholder=" Remarks " >
									</td> 
									<td class="col-sm-2">
									    <input type="text" class="form-control" name="amounts[0]" id="amounts_0" placeholder=" Amounts " >
									    <div class="trOther" style='display:none;margin-top: 3%;'>
									    <select class="form-control" name="depositBank[0]"  id="depositBank_0">
											<option value="" selected>~~ Deposit Bank ~~</option>
											<?php
											$sql = "SELECT id,bankName, accountName FROM `tbl_bank_account_info` WHERE status='Active' AND deleted='No'";
											$query = $conn->query($sql);
											while ($prow = $query->fetch_assoc()) {
												echo "<option value='" . $prow['id'] . "'>" . $prow['bankName'] . " - ".$prow['accountName']."</option>";
											}
											?>
										</select>
									    </div>
									</td>
									<td class="col-sm-3">
									    <input type="text" class="form-control" name="discounts[0]" id="discounts_0" placeholder=" Discounts " value="0" >
									    <div class="trCheque" style='display:none;'>
									    <input type="text" style="margin-top: 3%;" class="form-control" name="chequeNo[0]" id="chequeN_0" placeholder=" Checque No " >
									    </div>
									    
									</td>
									<td class="col-sm-2">
									    <input type="text" Readonly class="form-control" name="due[0]" id="due_0" placeholder=" Due ">
										<div class="trCheque" style='display:none; margin-top: 3%;'>
									    <select class="form-control" name="checqueBankName[0]" class="form-control" id="checqueBank_0" style="Width:100%;">
											<option value="" selected>~~ Cheque Bank Name  ~~</option>
												<option value="AB Bank Limited"> AB Bank Limited </option>
												<option value="Bangladesh Commerce Bank Limited"> Bangladesh Commerce Bank Limited </option>
												<option value="Bank Asia Limited"> Bank Asia Limited </option>
												<option value="BRAC Bank Limited"> BRAC Bank Limited </option>
												<option value="City Bank Limited"> City Bank Limited </option>
												<option value="Dhaka Bank Limited"> Dhaka Bank Limited </option>
												<option value="Dutch-Bangla Bank Limited"> Dutch-Bangla Bank Limited </option>
												<option value="Eastern Bank Limited"> Eastern Bank Limited </option>
												<option value="HSBC Bank Bangladesh"> HSBC Bank Bangladesh </option>
												<option value="IFIC Bank Limited"> IFIC Bank Limited </option>
												<option value="Jamuna Bank Limited"> Jamuna Bank Limited </option>
												<option value="Meghna Bank Limited"> Meghna Bank Limited </option>
												<option value="Mercantile Bank Limited"> Mercantile Bank Limited </option>
												<option value="Mutual Trust Bank Limited"> Mutual Trust Bank Limited </option>
												<option value="National Bank Limited"> National Bank Limited </option>
												<option value="NCC Bank Limited"> NCC Bank Limited </option>
												<option value="NRB Bank Limited"> NRB Bank Limited </option>
												<option value="NRB Commercial Bank Ltd"> NRB Commercial Bank Ltd </option>
												<option value="NRB Global Bank Ltd"> NRB Global Bank Ltd </option>
												<option value="One Bank Limited"> One Bank Limited </option>
												<option value="Premier Bank Limited"> Premier Bank Limited </option>
												<option value="Prime Bank Limited"> Prime Bank Limited </option>
												<option value="Pubali Bank Limited"> Pubali Bank Limited </option>
												<option value="Standard Bank Limited"> Standard Bank Limited </option>
												<option value="Shimanto Bank Ltd"> Shimanto Bank Ltd </option>
												<option value="Southeast Bank Limited"> Southeast Bank Limited </option>
												<option value="South Bangla Agriculture and Commerce Bank Limited"> South Bangla Agriculture and Commerce Bank Limited </option>
												<option value="Trust Bank Limited"> Trust Bank Limited </option>
												<option value="United Commercial Bank Ltd"> United Commercial Bank Ltd </option>
												<option value="Uttara Bank Limited"> Uttara Bank Limited </option>
												<option value="Bengal Commercial Bank Ltd"> Bengal Commercial Bank Ltd </option>
												<option value="Islami Bank Bangladesh Limited"> Islami Bank Bangladesh Limited </option>
												<option value="Al-Arafah Islami Bank Limited"> Al-Arafah Islami Bank Limited </option>
												<option value="EXIM Bank Limited"> EXIM Bank Limited </option>
												<option value="First Security Islami Bank Limited"> First Security Islami Bank Limited </option>
												<option value="Shahjalal Islami Bank Limited"> Shahjalal Islami Bank Limited </option>
												<option value="Social Islami Bank Limited"> Social Islami Bank Limited </option>
												<option value="Union Bank Limited"> Union Bank Limited  </option>
												<option value="Sonali Bank Limited"> Sonali Bank Limited </option>
												<option value="Janata Bank Limited"> Janata Bank Limited </option>
												<option value="Agrani Bank Limited"> Agrani Bank Limited </option>
												<option value="Rupali Bank Limited"> Rupali Bank Limited </option>
												<option value="BASIC Bank Limited"> BASIC Bank Limited </option>
										</select>
										</div>
									
									</td>
									<td class="col-sm-2">
									    <select class="form-control"  name="book[]" id="book_0">
            					        <?php
            					            $initialYear = 2020;
            					            for($i = $initialYear; $i <= date("Y"); $i++){
            					                echo '<option value="'.$i.'">'.$i.'</option>';
            					            }
            					        ?>
        					            </select>
        					            <div class="trCheque" style='display:none;'>
									    <input type="date"  style="margin-top: 3%;" class="form-control" name="transitDate[]" id="transitDate_0" style="padding: initial;"> 
									    </div>
									</td>
									<td><button type="button" name="add" id="add" class="btn btn-success btn-sm add"><span class="glyphicon glyphicon-plus"></span></button></td>
									</tr>
                            </table>  
						</div>
					</div>
					
					<!--<div class="form-group">
						<div class="table-responsive">
							<table class="table table-bordered" id="dynamic_field2" style="display: none;">  
                                <tr>
									<th> Party Name </th><th> Due </th><th> Discount </th><th> Amount </th><th> Deposit Bank </th><th> Comments </th><th></th>
								</tr>
								
								<tr>  
									<td class="col-sm-3">
										<select class="form-control" name="partyNameEft[0]" class="form-control" id="nameEft_0" style='width:100%' onchange="calculateDue(this.value, 0)">
											<option value="" selected>~~ Party Name ~~</option>
											<?php
											/*$sql = "SELECT id,partyName,locationArea, tblCity FROM `tbl_party` WHERE status!='Inactive' AND deleted='No' AND tblType <> 'Suppliers'";
											$query = $conn->query($sql);
											while ($prow = $query->fetch_assoc()) {
												echo "<option value='" . $prow['id'] . "'>" . $prow['partyName'] . " - ".$prow['locationArea']." - ".$prow['tblCity']."</option>";
											}*/
											?>
										</select>
									</td> 
									<td class="col-sm-1"><input type="text" Readonly class="form-control" name="dueEft[0]" id="dueEft_0" placeholder=" Due "></td>
									<td class="col-sm-1"><input type="text" class="form-control" name="discountEft[0]" id="discountEft_0" placeholder=" Discount " ></td>
									<td class="col-sm-2"><input type="text" class="form-control" name="amountsEft[0]" id="amountsEft_0" placeholder=" Amounts " ></td>
									<td class="col-sm-3">
										<select class="form-control" name="depositBankEft[0]" class="form-control" id="depositBankEft_0">
											<option value="" selected>~~ Deposit Bank ~~</option>
											<?php
											/*$sql = "SELECT id,bankName, accountName FROM `tbl_bank_account_info` WHERE status='Active' AND deleted='No'";
											$query = $conn->query($sql);
											while ($prow = $query->fetch_assoc()) {
												echo "<option value='" . $prow['id'] . "'>" . $prow['bankName'] . " - ".$prow['accountName']."</option>";
											}*/
											?>
										</select>
									</td>
									<td class="col-sm-2"><input type="text" class="form-control" name="remarksEft[]" placeholder=" Remarks " ></td>
									<td><button type="button" name="add" id="addEft" class="btn btn-success btn-sm add"><span class="glyphicon glyphicon-plus"></span></button></td>
                                </tr>  
                            </table>  
						</div>
					</div>-->
					
					
					<div class="form-group">
						<div class="col-sm-12">
						<button type="submit" class="btn btn-primary btn-flat" name="btn_purchaseReturn" id="btn_purchaseReturn"><i class="fa fa-save"></i> Save Payment Cheque Voucher </button>
					    </div>
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
<script src="includes/js/paymentReceiveVoucher.js"></script> 
</body>
</html>