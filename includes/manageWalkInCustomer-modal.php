<!-- Edit added Walkin Customer-->
<div class="modal fade" id="editWalkInCustomer">
    <div class="modal-dialog" style="width: 60%;">
        <div class="modal-content">
          	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              		<span aria-hidden="true">&times;</span></button>
            	<h4 class="modal-title"><b>Edit Added WalkIn Customer </b></h4>
          	</div>
          	<div class="modal-body">
          	    <div id="editLoader" style="display:none; text-align:center;" class="col-md-12"><i class='fa fa-spinner fa-spin' style='font-size:50px;color:green'></i></div>
            	<form class="form-horizontal" id="form_editWalkinCustomer" method="POST" action="#">
			    <input type="hidden" id='Uid' name="id" >
				<div class="form-group">
                  	<div class="col-sm-6">
					<label for="CustomerName">Customer Name </label> 
                    	<input type="text" class="form-control" id="edit_customerName" name="CustomerName" placeholder="   Name " required>
                  	</div>
                  	<div class="col-sm-6">
					<label for="PhoneNumber"> Phone Number </label> 
                    	<input type="text" class="form-control" id="edit_phoneNo" name="PhoneNumber" placeholder="   Phone Number " required>
                  	</div>
				</div>
				<div class="form-group">
				    <div class="col-sm-6">
					<label for="EmailAddress"> Email Address </label> 
                    	<input type="email" class="form-control" id="edit_contactEmail" name="EmailAddress" placeholder=" Email ">
                  	</div>
					<div class="col-sm-6">
                        <label for="Status"> Status </label>
                    	<select class="form-control" id="edit_status" name="CustomerStatus" required>
							<option value="" selected>~~ Select Status ~~</option>
							<option value="Active"> Active </option>
							<option value="Inactive"> In-Active </option>
						</select>
						
					</div>
				</div>
				
                <div class="form-group">
                    
                  	<div class="col-sm-12">
					<label for="CreditLimit"> Address </label>
                    	<textarea  class="form-control" id="edit_customerAddress" name="Address"  placeholder="Describe address here..."></textarea>
                        
                  	</div>
				</div>
				
				<div class="modal-footer">
					<button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
					<button type="submit" class="btn btn-primary btn-flat" name="EditCustomer" id="btn_updateCustomer"><i class="fa fa-save"></i> Save </button>
				</div>
				</form>
			</div>
        </div>
    </div>
</div>