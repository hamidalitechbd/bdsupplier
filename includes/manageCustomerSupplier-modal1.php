<!-- Add New Customer/Supplier-->
<div class="modal fade" id="addnew">
    <div class="modal-dialog" style="width: 60%;">
        <div class="modal-content">
          	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              		<span aria-hidden="true">&times;</span></button>
            	<h4 class="modal-title"><b>Add New <span id="typeHeading"><?php echo $type; ?></span> </b></h4>
          	</div>
          	<div class="modal-body">
            	<form class="form-horizontal" id="form_addCustomer" method="POST" action="#">
				<input type="hidden" value="<?php echo $type; ?>" id="add_tblType" name="TblType">	
				<input type="hidden" value="" id="add_pageName" name="pageName">	
				<div class="form-group">
                  	<div class="col-sm-6">
					<label for="CustomerName"> Name </label> 
                    	<input type="text" class="form-control" id="add_customerName" name="CustomerName" placeholder=" Insert Name " required>
                  	</div>
					<div class="col-sm-6">
					<label for="EmailAddress"> Email Address </label> 
                    	<input type="text" class="form-control" id="add_emailAddress" name="EmailAddress" placeholder=" Valid Email Address " required>
                  	</div>
					
				</div>
				<div class="form-group">
                  	<div class="col-sm-6">
					<label for="ContactPerson"> Contact Person </label> 
                    	<input  class="form-control" id="add_contactPerson" name="ContactPerson"  placeholder="Contact Person">
                  	</div>
					<div class="col-sm-6">
					<label for="PhoneNumber"> Phone Number </label> 
                    	<input type="text" class="form-control" id="add_phoneNumber" name="PhoneNumber" placeholder=" Valid Phone Number " required>
                  	</div>
				</div>
				<div class="form-group">
                  	<div class="col-sm-6">
					<label for="CountryName"> Country Name </label> 
                    	<input type="text" class="form-control" id="add_countryName" name="CountryName" placeholder=" Country Name " required>
                  	</div>
					<div class="col-sm-6">
					<label for="CityName"> City Name </label> 
                    	<select class="form-control" id="add_cityName" name="CityName" required>
							<option value="" selected>~~ Select City ~~</option>
							<option value="Dhaka"> Dhaka </option>
							<option value="Chittagong"> Chittagong </option>
							<option value="Khulna"> Khulna </option>
							<option value="Rajshahi"> Rajshahi </option>
							<option value="Comilla"> Comilla </option>
							<option value="Shibganj"> Shibganj </option>
							<option value="Natore"> Natore </option>
							<option value="Rangpur"> Rangpur </option>
							<option value="Tongi"> Tongi </option>
							<option value="Bagerhat "> Bagerhat  </option>
							<option value="Coxs Bāzār"> Coxs Bāzār </option>
						</select>
                  	</div>
				</div>
				<div class="form-group">
                  	<div class="col-sm-6">
						<label for="CustomerType"> Customer Type </label> 
                    	<select class="form-control" id="add_customerType" name="CustomerType" required>
							<option value="" selected>~~ Select Type ~~</option>
							<option value="Cash"> Cash </option>
							<option value="Regular"> Regular </option>
						</select>
                  	</div>
					<div class="col-sm-6">
						<label for="Status"> Status </label> 
                    	<select class="form-control" id="add_customerStatus" name="CustomerStatus" required>
							<option value="" selected>~~ Select Status ~~</option>
							<option value="Active"> Active </option>
							<option value="Inactive"> In-Active </option>
						</select>
                  	</div>
				</div>
				<div class="form-group">
					<div class="col-sm-6">
					<label for="CreditLimit"> Credit Limit </label>
                    	<input type="text" class="form-control" id="add_creditLimit" name="CreditLimit" placeholder=" Credit Limit " >
                  	</div>
                  	<div class="col-sm-6">
					<label for="CreditLimit"> Address </label>
                    	<textarea  class="form-control" id="add_address" name="Address"  placeholder="Describe address here..."></textarea>
                  	</div>
				</div>
			</div>
          	<div class="modal-footer">
            	<button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
            	<button type="submit" class="btn btn-primary btn-flat" name="addCustomer" id="btn_saveCustomer"><i class="fa fa-save"></i> Save </button>
            	</form>
          	</div>
        </div>
    </div>
</div>
<!-- Edit added Customer/Supplier-->
<div class="modal fade" id="editCustomerSupplier">
    <div class="modal-dialog" style="width: 60%;">
        <div class="modal-content">
          	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              		<span aria-hidden="true">&times;</span></button>
            	<h4 class="modal-title"><b>Edit Added <?php echo $type; ?> </b></h4>
          	</div>
          	<div class="modal-body">
            	<form class="form-horizontal" id="form_editCustomer" method="POST" action="#">
				<input type="hidden" value="<?php echo $type; ?>" id="add_tblType" name="TblType">	
				<input type="hidden" id='Uid' name="id" >
				<div class="form-group">
                  	<div class="col-sm-6">
					<label for="CustomerName"> Customer Name </label> 
                    	<input type="text" class="form-control" id="edit_partyName" name="CustomerName" placeholder=" <?php echo $type; ?>  Name " required>
                  	</div>
					<div class="col-sm-6">
					<label for="EmailAddress"> Email Address </label> 
                    	<input type="text" class="form-control" id="edit_partyEmail" name="EmailAddress" placeholder=" <?php echo $type; ?> Email " required>
                  	</div>
					
				</div>
				<div class="form-group">
                  	<div class="col-sm-6">
					<label for="ContactPerson"> Contact Person </label> 
                    	<input  class="form-control" id="edit_contactPerson" name="ContactPerson"  placeholder="Contact Person">
                  	</div>
					<div class="col-sm-6">
					<label for="PhoneNumber"> Phone Number </label> 
                    	<input type="text" class="form-control" id="edit_partyPhone" name="PhoneNumber" placeholder=" <?php echo $type; ?>  Phone Number " required>
                  	</div>
				</div>
				<div class="form-group">
                  	<div class="col-sm-6">
					<label for="CountryName"> Country Name </label> 
                    	<input type="text" class="form-control" id="edit_tblCountry" name="CountryName" placeholder=" Country Name " required>
                  	</div>
					<div class="col-sm-6">
					<label for="CityName"> City Name </label> 
                    	<select class="form-control" id="edit_tblCity" name="CityName" required>
							<option value="" selected>~~ Select City ~~</option>
							<option value="Dhaka"> Dhaka </option>
							<option value="Chittagong"> Chittagong </option>
							<option value="Khulna"> Khulna </option>
							<option value="Rajshahi"> Rajshahi </option>
							<option value="Comilla"> Comilla </option>
							<option value="Shibganj"> Shibganj </option>
							<option value="Natore"> Natore </option>
							<option value="Rangpur"> Rangpur </option>
							<option value="Tongi"> Tongi </option>
							<option value="Bagerhat "> Bagerhat  </option>
							<option value="Coxs Bāzār"> Coxs Bāzār </option>
						</select>
                  	</div>
				</div>
				<div class="form-group">
                  	<div class="col-sm-6">
					<label for="CustomerType"> Customer Type </label> 
                    	<select class="form-control" id="edit_partyType" name="CustomerType" required>
							<option value="" selected>~~ Select Type ~~</option>
							<option value="Cash"> Cash </option>
							<option value="Regular"> Regular </option>
						</select>
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
					<div class="col-sm-6">
					<label for="CreditLimit"> Credit Limit </label>
                    	<input type="text" class="form-control" id="edit_creditLimit" name="CreditLimit" placeholder=" <?php echo $type; ?> Credit Limit " >
                  	</div>
                  	<div class="col-sm-6">
					<label for="CreditLimit"> Address </label>
                    	<textarea  class="form-control" id="edit_partyAddress" name="Address"  placeholder="Describe address here..."></textarea>
                  	</div>
				</div>
			</div>
          	<div class="modal-footer">
            	<button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
            	<button type="submit" class="btn btn-primary btn-flat" name="EditCustomer" id="btn_updateCustomer"><i class="fa fa-save"></i> Save </button>
            	</form>
          	</div>
        </div>
    </div>
</div>


<!-- Unit Price From Purchase panel-->
<div class="modal fade" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">
          	<div class="modal-body">
            	<form class="form-horizontal" id="form_addPurchaseProducts" method="POST" action="#">
				<input type="hidden" value="Suppliers" id="add_tblType" name="TblType">	
				<div class="form-group">
                  	<div class="col-sm-6">
					<label for="add_unitPrice"> Unit Price </label>
                    	<input type="text" class="form-control" id="add_unitPrice" name="unitPrice" placeholder=" Add Unit Price " required>
                  	</div>
					<div class="col-sm-6">
					<label for="add_quantity"> Quantity </label>
                    	<input type="text" class="form-control" id="add_quantity" name="quantity" placeholder=" Add Quantity " required>
                  	</div>
				</div>
				<div class="form-group">
                  	<div class="col-sm-6">
					<label for="add_maxPrice"> Min Price </label>
                    	<input type="text" class="form-control" id="add_maxPrice" name="maxPrice" placeholder=" Minimum Price " required>
                  	</div>
					<div class="col-sm-6">
					<label for="add_minPrice"> Max Price </label>
                    	<input type="text" class="form-control" id="add_minPrice" name="minPrice" placeholder=" Maximum Price " required>
                  	</div>
				</div>
				<div class="form-group">
                  	<div class="col-sm-6">
					<label for="add_mfgDate"> Mfg. Date </label>
                    	<input type="date" style="line-height: 10px;" class="form-control" id="add_mfgDate" name="mfgDate" required>
                  	</div>
					<div class="col-sm-6">
					<label for="add_expDate"> Exp. Date </label>
                    	<input type="date" style="line-height: 10px;" class="form-control" id="add_expDate" name="expDate" required>
                  	</div>
				</div>
				<div class="form-group">
				    <div class="col-sm-6">
                        <label for="WareHouse">Warehouse</label> 
                        <select class="form-control" name="wereHouse" id="add_wereHouse" style="width:100%;" required>
                            <option value="" selected>~~ Select Warehouse ~~</option>
                            <?php
                            $sql = "SELECT id,wareHouseName FROM `tbl_warehouse` WHERE status='Active' ORDER BY `id`  DESC";
                            $query = $conn->query($sql);
                            while ($prow = $query->fetch_assoc()) {
                                echo "
								  <option value='" . $prow['id'] . "'>" . $prow['wareHouseName'] . "</option>
								";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <input type="hidden" id="add_sessionId" name="add_sessionId" value="<?php echo $sessionId;?>" />
                    </div>
				</div>
			</div>
          	<div class="modal-footer">
            	<button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
            	<button type="submit" class="btn btn-primary btn-flat" name="addpurchaseProducts" id="btn_savePurchaseProducts"><i class="fa fa-save"></i> Save </button>
            	</form>
          	</div>
        </div>
    </div>
</div>