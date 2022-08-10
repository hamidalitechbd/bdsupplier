<!-- Add -->
<div class="modal fade" id="addnew">
    <div class="modal-dialog">
        <div class="modal-content">
          	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              		<span aria-hidden="true">&times;</span></button>
            	<h4 class="modal-title"><b>Add New Bank Account </b></h4>
          	</div>
          	<div class="modal-body">
            	<form class="form-horizontal" id="form_addBankAccount" method="POST">
				<div class="form-group">
                  	<label for="accountNo" class="col-sm-3 control-label">Account Number</label>
                  	<div class="col-sm-9">
                    	<input type="text" class="form-control" id="add_accountNo" name="accountNo" maxlength ="13" placeholder=" Account Number ">
					</div>
				</div>
				<div class="form-group">
                  	<label for="accountName" class="col-sm-3 control-label">Account Name</label>
                  	<div class="col-sm-9">
                    	<input type="text" class="form-control" id="add_accountName" name="accountName" placeholder=" Account Name ">
                  	</div>
				</div>
			    <div class="form-group">
                  	<label for="bankName" class="col-sm-3 control-label">Bank Name</label>
                  	<div class="col-sm-9">
                    	<input type="text" class="form-control" id="add_bankName" name="bankName" placeholder=" Bank Name ">
                  	</div>
				</div>
				<div class="form-group">
                  	<label for="branchName" class="col-sm-3 control-label">Branch Name</label>
                  	<div class="col-sm-9">
                    	<input type="text" class="form-control" id="add_branchName" name="branchName" placeholder=" Branch Name ">
                  	</div>
				</div>
				<div class="form-group">
                  	<label for="swiftCode" class="col-sm-3 control-label">Swift Code</label>
                  	<div class="col-sm-9">
                    	<input type="text"  class="form-control" id="add_swiftCode" name="swiftCode" placeholder=" Swift Code ">
                  	</div>
				</div>
				<div class="form-group">
                  	<label for="address" class="col-sm-3 control-label">Address</label>
                  	<div class="col-sm-9">
                    	<textarea type="text" class="form-control" id="add_address" name="address" placeholder=" Bank Address "></textarea>
                  	</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
					<button type="submit" class="btn btn-primary btn-flat" name="addUnit" id="btn_saveBankAccount"><i class="fa fa-save"></i> Save </button>
					</form>
				</div>
			</div>
        </div>
    </div>
</div>
<!-- Add and Edit -->
<div class="modal fade" id="editBankAccount">
    <div class="modal-dialog">
        <div class="modal-content">
          	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              		<span aria-hidden="true">&times;</span></button>
            	<h4 class="modal-title"><b>Edit Added Bank Account </b></h4>
          	</div>
          	<div class="modal-body">
          	    <div id="editLoader" style="display:none; text-align:center;" class="col-md-12"><i class='fa fa-spinner fa-spin' style='font-size:50px;color:green'></i></div>
            	<form class="form-horizontal" method="POST" id="form_updateBankAccount">
				<input type="hidden" id="bankAccountId" name="id">
				<div class="form-group">
                  	<label for="accountNo" class="col-sm-3 control-label">Account Number</label>
                  	<div class="col-sm-9">
                    	<input type="text" class="form-control" id="edit_accountNo" name="accountNo" placeholder=" Account Number ">
					</div>
				</div>
				<div class="form-group">
                  	<label for="accountName" class="col-sm-3 control-label">Account Name</label>
                  	<div class="col-sm-9">
                    	<input type="text" class="form-control" id="edit_accountName" name="accountName" placeholder=" Account Name ">
                  	</div>
				</div>
			    <div class="form-group">
                  	<label for="bankName" class="col-sm-3 control-label">Bank Name</label>
                  	<div class="col-sm-9">
                    	<input type="text" class="form-control" id="edit_bankName" name="bankName" placeholder=" Bank Name ">
                  	</div>
				</div>
				<div class="form-group">
                  	<label for="branchName" class="col-sm-3 control-label">Branch Name</label>
                  	<div class="col-sm-9">
                    	<input type="text" class="form-control" id="edit_branchName" name="branchName" placeholder=" Branch Name ">
                  	</div>
				</div>
				<div class="form-group">
                  	<label for="swiftCode" class="col-sm-3 control-label">Swift Code</label>
                  	<div class="col-sm-9">
                    	<input type="text"  class="form-control" id="edit_swiftCode" name="swiftCode" placeholder=" Swift Code ">
                  	</div>
				</div>
				<div class="form-group">
                  	<label for="address" class="col-sm-3 control-label">Address</label>
                  	<div class="col-sm-9">
                    	<textarea type="text" class="form-control" id="edit_address" name="address" placeholder=" Bank Address "></textarea>
                  	</div>
				</div>
			    <div class="form-group">
                  	<label for="status" class="col-sm-3 control-label">Status</label>
                  	<div class="col-sm-9">
                    	<select class="form-control" id="edit_status" name="status" required>
							<option value="Active"> Active </option>
							<option value="Inactive"> In-Active </option>
						</select>
                  	</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
					<button type="submit" class="btn btn-primary btn-flat" name="editUnit" id="btn_updateUnit"><i class="fa fa-save"></i> Save </button>
					</form>
				</div>
			</div>
        </div>
    </div>
</div>