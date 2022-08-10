<!-- Add new User-->
<div class="modal fade" id="addnew">
    <div class="modal-dialog" style="width: 65%;">
        <div class="modal-content">
          	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              		<span aria-hidden="true">&times;</span></button>
            	<h4 class="modal-title"><b>Add New Incentive </b></h4>
          	</div>
          	<div class="modal-body">
            	<form class="form-horizontal" id="form_addIncentive" method="POST">
				<div class="form-group">
				    <div class="col-sm-4">
						<label for="name" class="control-label">Name :</label>
						<input type="text" class="form-control" id="add_name" name="name" placeholder=" Total Buy Amount">
					</div>
					<div class="col-sm-4">
						<label for="dateFrom" class="control-label">Date From :</label>
						<input type="date" class="form-control" id="add_dateFrom" name="dateFrom">
					</div>
					<div class="col-sm-4">
					    <label for="dateTo" class="control-label">Date To :</label>
						<input type="date" class="form-control" id="add_dateTo" name="dateTo" >
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-4">
						<label for="buyAmount" class="control-label">Buy Amount :</label>
						<input type="text" class="form-control" id="add_buyAmount" name="buyAmount" placeholder=" Total Buy Amount">
					</div>
				    <div class="col-sm-4">
						<label for="restAmount" class="control-label">Rest Amount :</label>
						<input type="text" class="form-control" id="add_restAmount" name="restAmount" placeholder=" Due Amount " value="0">
					</div>
					<div class="col-sm-4">
						<label for="ownerIncentive" class="control-label">Owners Icentive % :</label>
						<input type="text" class="form-control" id="add_ownerIncentive" name="ownerIncentive" placeholder=" Owners Incentive 0.5% ">
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-4">
						<label for="employeeIcentive" class="control-label">Employee Icentive % :</label>
						<input type="text" class="form-control" id="add_employeeIcentive" name="employeeIcentive" placeholder=" Employee Icentive 0.5%">
					</div>
					<div class="col-sm-4">
						<label for="applayDate" class="control-label">Applay Date:</label>
						<input type="date" class="form-control" id="add_applayDate" name="applayDate" >
					</div>
					<div class="col-sm-4">
						<label for="CustomerSalesType" class="control-label">Customer Sales Type:</label>
						<select class="form-control" id="add_customerSalesType" name="CustomerSalesType" >
							<option value="" selected>~~ Select Customer Type ~~</option>
							<option value="Wholesellers Shop"> Wholesellers Shop </option>
							<option value="Wholesellers Office"> Wholesellers Office </option>
							<option value="Corporate Shop"> Corporate Shop </option>
							<option value="Corporate Office"> Corporate Office </option>
							<option value="Project Shop"> Project Shop </option>
							<option value="Project Office"> Project Office </option>
							<option value="Regular Retail Clients"> Regular Retail Clients </option>
						</select>
					</div>
					
				</div>
				
				
				<div class="modal-footer">
					<button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
					<button type="submit" class="btn btn-primary btn-flat" name="addIncentive" id="btn_saveIncentive"><i class="fa fa-save"></i> Save Incentive </button>
				</div>
				</form>
			</div>
        </div>
    </div>
</div>
<!-- Edit new User-->
<div class="modal fade" id="editUser">
    <div class="modal-dialog" style="width: 65%;">
        <div class="modal-content">
          	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              		<span aria-hidden="true">&times;</span></button>
            	<h4 class="modal-title"><b>Edit Added User Info </b></h4>
          	</div>
          	<div class="modal-body">
            	<form class="form-horizontal" id="form_updateUser" method="POST">
				<input type="hidden" id="edit_userId" name="userId">
				<div class="form-group">
					<div class="col-sm-4">
						<label for="transportName" class="control-label">User Full Name :</label>
						<input type="text" class="form-control" id="edit_userFullName" name="userFullName" placeholder=" User Full Name ">
					</div>
					<div class="col-sm-4">
						<label for="transportName" class="control-label">Phone Number :</label>
						<input type="text" class="form-control" id="edit_userPhone" name="userPhone" placeholder=" User Phone Number">
					</div>
					<div class="col-sm-4">
						<label for="transportName" class="control-label">Mail Address :</label>
						<input type="text" class="form-control" id="edit_userMail" name="userMail" placeholder=" Mail Address ">
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-4">
						<label for="transportName" class="control-label">Gender :</label>
						<select class="form-control" id="edit_userGender" name="userGender">
							<option value="" selected>~~ Choose Gender ~~</option>
							<option value="Mail">Male</option>
							<option value="Femail">Female</option>
						</select>
					</div>
					<div class="col-sm-4">
					    <label for="userName" class="control-label">User Name :</label>
						<input type="text" class="form-control" id="edit_userName" name="userName" placeholder=" User Name " Readonly>
					</div>
					<div class="col-sm-4">
						<label for="transportName" class="control-label">User Type :</label>
						<select class="form-control" id="edit_userType" name="userType">
							<option value="" selected>~~ Choose User Type ~~</option>
							 <?php
                                $sql = "SELECT id,accountType FROM `tbl_accountType` WHERE status='Active' AND deleted='No'";
                                $query = $conn->query($sql);
                                while ($prow = $query->fetch_assoc()) {
                                    echo "
									  <option value='" . $prow['id'] . "'>" . $prow['accountType'] . "</option>
									";
                                }
                                ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-4">
						<label for="transportName" class="control-label">NID Number :</label>
						<input type="text" class="form-control" id="edit_nidNumber" name="nidNumber" placeholder=" Nid Number ">
					</div>
					<div class="col-sm-4">
						<label for="transportName" class="control-label">User Designation :</label>
						<input type="text" class="form-control" id="edit_userDesignation" name="userDesignation" placeholder=" User Designation ">
					</div>
					<div class="col-sm-4">
						<label for="transportName" class="control-label">User Status :</label>
						<select class="form-control" id="edit_userStatus" name="userStatus">
							<option value="" selected>~~ Choose User Status ~~</option>
							<option value="approved">Approved</option>
							<option value="pending">Pending</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-4">
						<label for="transportName" class="control-label">Profile Image :</label>
						<input type="file" class="form-control" id="edit_userPhoto" name="userPhoto">
					</div>
                  	<div class="col-sm-4">
						<label for="transportName" class="control-label">Print Phone :</label>
						<input type="text" class="form-control" id="edit_printPhone" name="printPhone" placeholder=" Print Phone ">
					</div>
					<div class="col-sm-4">
						<label for="transportName" class="control-label">Print Mobile :</label>
						<input type="text" class="form-control" id="edit_printMobile" name="printMobile" placeholder=" Print Mobile ">
					</div>
				</div>
				<div class="form-group">
                  	<div class="col-sm-12">
						<label for="address" class="control-label">Address</label>
                    	<textarea type="text" class="form-control" id="edit_userAddress" name="userAddress" placeholder=" Address "></textarea>
                  	</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
					<button type="submit" class="btn btn-primary btn-flat" name="updateUser" id="btn_updateUser"><i class="fa fa-save"></i> Save </button>
				</div>
				</form>
			</div>
        </div>
    </div>
</div>
