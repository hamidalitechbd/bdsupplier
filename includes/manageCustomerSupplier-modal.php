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
            	<form class="form-horizontal" id="form_addCustomer" method="POST">
				<input type="hidden" value="<?php echo $type; ?>" id="add_tblType" name="TblType">	
				<input type="hidden" value="" id="add_pageName" name="pageName">	
				<div class="form-group">
                  	<div class="col-sm-6">
					<label for="CustomerName"> Name </label> 
                    	<input type="text" class="form-control" id="add_customerName" name="CustomerName" autocomplete="off" placeholder=" Insert Name ">
                  	</div>
					<div class="col-sm-6">
					<label for="EmailAddress"> Email Address </label> 
                    	<input type="email" class="form-control" id="add_emailAddress" name="EmailAddress" autocomplete="off" placeholder=" Valid Email Address ">
                  	</div>
					
				</div>
				<div class="form-group">
                  	<div class="col-sm-6">
					<label for="ContactPerson"> Contact Person </label> 
                    	<input  class="form-control" id="add_contactPerson" name="ContactPerson"  autocomplete="off" placeholder="Contact Person">
                  	</div>
					<div class="col-sm-6">
					<label for="PhoneNumber"> Phone Number </label> 
                    	<input type="text" class="form-control" id="add_phoneNumber" name="PhoneNumber" autocomplete="off" placeholder=" Valid Phone Number ">
                  	</div>
				</div>
				<div class="form-group">
                  	<div class="col-sm-6">
					<label for="CountryName"> Country Name </label> 
                    	<input type="text" class="form-control" id="add_countryName" name="CountryName" Value="Bangladesh" autocomplete="off" placeholder=" Country Name " >
                  	</div>
                  	<div class="col-sm-6">
					<label for="PhoneNumber"> Alter Phone Number </label> 
                    	<input type="text" class="form-control" id="add_altphoneNumber" name="altPhoneNumber" autocomplete="off" placeholder=" Valid Phone Number ">
                  	</div>
				</div>
				<div class="form-group">
                  	<div class="col-sm-6">
					<label for="CityName"> District Name </label> 
                    	<select class="form-control" id="add_cityName" name="CityName" autocomplete="off" required>
							<option value="">~~ Select District. ~~</option>
							<option value="Barguna"> Barguna</option>
							<option value="Barishal">	Barishal </option>
							<option value="Bhola">	Bhola </option>
							<option value="Jhalokati">	Jhalokati </option>
							<option value="Patuakhali">	Patuakhali</option>
							<option value="Pirojpur">	Pirojpur </option>
							<option value="Bandarban">	Bandarban</option>
							<option value="Brahmanbaria">	Brahmanbaria</option>
							<option value="Chandpur">	Chandpur</option>
							<option value="Chattogram" selected>	Chattogram</option>
							<option value="Cumilla">	Cumilla </option>
							<option value="Coxs Bazar">	Cox's Bazar</option>
							<option value="Feni">	Feni</option>
							<option value="Khagrachhari">	Khagrachhari</option>
							<option value="Lakshmipur">	Lakshmipur</option>
							<option value="Noakhali">	Noakhali</option>
							<option value="Rangamati">	Rangamati</option>
							<option value="Dhaka">	Dhaka</option>
							<option value="Faridpur">	Faridpur </option>
							<option value="Gazipur">	Gazipur</option>
							<option value="Gopalganj">	Gopalganj </option>
							<option value="Kishoreganj">	Kishoreganj</option>
							<option value="Madaripur">	Madaripur</option>
							<option value="Manikganj">	Manikganj</option>
							<option value="Munshiganj">	Munshiganj</option>
							<option value="Narayanganj">	Narayanganj</option>
							<option value="Narsingdi">	Narsingdi</option>
							<option value="Rajbari">	Rajbari</option>
							<option value="Shariatpur">	Shariatpur</option>
							<option value="Tangail">	Tangail</option>
							<option value="Bagerhat">	Bagerhat</option>
							<option value="Chuadanga">	Chuadanga</option>
							<option value="Jashore">	Jashore</option>
							<option value="Jhenaidah">	Jhenaidah</option>
							<option value="Khulna">	Khulna</option>
							<option value="Kushtia">	Kushtia</option>
							<option value="Magura">	Magura</option>
							<option value="Meherpur">	Meherpur</option>
							<option value="Narail">	Narail</option>
							<option value="Satkhira">	Satkhira</option>
							<option value="Jamalpur">	Jamalpur</option>
							<option value="Mymensingh">	Mymensingh</option>
							<option value="Netrokona">	Netrokona</option>
							<option value="Sherpur">	Sherpur</option>
							<option value="Bogura">	Bogura</option>
							<option value="Joypurhat">	Joypurhat</option>
							<option value="Naogaon">	Naogaon</option>
							<option value="Natore">	Natore</option>
							<option value="Chapainawabganj">	Chapainawabganj</option>
							<option value="Pabna">	Pabna</option>
							<option value="Rajshahi">	Rajshahi</option>
							<option value="Sirajganj">	Sirajganj</option>
							<option value="Dinajpur">	Dinajpur</option>
							<option value="Gaibandha">	Gaibandha</option>
							<option value="Kurigram">	Kurigram</option>
							<option value="Lalmonirhat">	Lalmonirhat</option>
							<option value="Nilphamari">	Nilphamari</option>
							<option value="Panchagarh">	Panchagarh</option>
							<option value="Rangpur">	Rangpur </option>
							<option value="Thakurgaon">	Thakurgaon</option>
							<option value="Habiganj">	Habiganj</option>
							<option value="Moulvibazar">	Moulvibazar</option>
							<option value="Sunamganj">	Sunamganj</option>
							<option value="Sylhet">	Sylhet</option>
						</select>
                  	</div>
					<div class="col-sm-6">
						<label for="locationArea">Location Area </label>
                    	<input type="text" class="form-control" id="add_locationArea" name="LocationArea" autocomplete="off" placeholder=" Location Area " >
                  	</div>
				</div>
				<div class="form-group">
					<div class="col-sm-6">
					<label for="CreditLimit"> Credit Limit </label>
                    	<input type="text" class="form-control" id="add_creditLimit" name="CreditLimit" autocomplete="off" placeholder=" Credit Limit " >
                    </div>
    				<div class="col-sm-6">
    				<label for="CustomerType"> <?php echo $type; ?></span> Type </label> 
                    	<select class="form-control" id="add_customerType" name="CustomerType" >
							<option value="" selected>~~ Select Type ~~</option>
							<option value="Cash"> Cash </option>
							<option value="Regular"> Regular </option>
						</select>
                  	</div>
                </div>
                <div class="form-group">
                  	<div class="col-sm-6">
					<label for="CreditLimit"> Address </label>
                    	<textarea  class="form-control" id="add_address" rows="1" cols="50" name="Address"  placeholder="Describe address here..."></textarea>
                  	</div>
                  	<div class="col-sm-6">
    				<label for="CustomerType"> <?php echo $type; ?></span>Sales Type </label> 
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
					<button type="submit" class="btn btn-primary btn-flat" name="addCustomer" id="btn_saveCustomer"><i class="fa fa-save"></i> Save </button>
				</div>
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
          	    <div id="editLoader" style="display:none; text-align:center;" class="col-md-12"><i class='fa fa-spinner fa-spin' style='font-size:50px;color:green'></i></div>
            	<form class="form-horizontal" id="form_editCustomer" method="POST" action="#">
				<input type="hidden" value="<?php echo $type; ?>" id="add_tblType" name="TblType">	
				<input type="hidden" id='Uid' name="id" >
				<div class="form-group">
                  	<div class="col-sm-6">
					<label for="CustomerName"> <?php echo $type; ?> Name </label> 
                    	<input type="text" class="form-control" id="edit_partyName" name="CustomerName" placeholder=" <?php echo $type; ?>  Name " >
                  	</div>
					<div class="col-sm-6">
					<label for="EmailAddress"> Email Address </label> 
                    	<input type="email" class="form-control" id="edit_partyEmail" name="EmailAddress" placeholder=" <?php echo $type; ?> Email ">
                  	</div>
					
				</div>
				<div class="form-group">
                  	<div class="col-sm-6">
					<label for="ContactPerson"> Contact Person </label> 
                    	<input  class="form-control" id="edit_contactPerson" name="ContactPerson"  placeholder="Contact Person">
                  	</div>
					<div class="col-sm-6">
					<label for="PhoneNumber"> Phone Number </label> 
                    	<input type="text" class="form-control" id="edit_partyPhone" name="PhoneNumber" placeholder=" <?php echo $type; ?>  Phone Number " >
                  	</div>
				</div>
				<div class="form-group">
                  	<div class="col-sm-6">
					<label for="CountryName"> Country Name </label> 
                    	<input type="text" class="form-control" id="edit_tblCountry" name="CountryName" placeholder=" Country Name " >
                  	</div>
                  	<div class="col-sm-6">
					<label for="PhoneNumber"> Alter Phone Number </label> 
                    	<input type="text" class="form-control" id="edit_altphoneNumber" name="altPhoneNumber" placeholder=" Valid Phone Number " >
                  	</div>
					
				</div>
				<div class="form-group">
                  	<div class="col-sm-6">
						<label for="CityName"> District Name </label> 
                    	<select class="form-control" id="edit_tblCity" name="CityName" >
							<option value="">~~ Select District. ~~</option>
							<option value="Barguna"> Barguna</option>
							<option value="Barishal">	Barishal </option>
							<option value="Bhola">	Bhola </option>
							<option value="Jhalokati">	Jhalokati </option>
							<option value="Patuakhali">	Patuakhali</option>
							<option value="Pirojpur">	Pirojpur </option>
							<option value="Bandarban">	Bandarban</option>
							<option value="Brahmanbaria">	Brahmanbaria</option>
							<option value="Chandpur">	Chandpur</option>
							<option value="Chattogram" selected>	Chattogram</option>
							<option value="Cumilla">	Cumilla </option>
							<option value="Coxs Bazar">	Cox's Bazar</option>
							<option value="Feni">	Feni</option>
							<option value="Khagrachhari">	Khagrachhari</option>
							<option value="Lakshmipur">	Lakshmipur</option>
							<option value="Noakhali">	Noakhali</option>
							<option value="Rangamati">	Rangamati</option>
							<option value="Dhaka">	Dhaka</option>
							<option value="Faridpur">	Faridpur </option>
							<option value="Gazipur">	Gazipur</option>
							<option value="Gopalganj">	Gopalganj </option>
							<option value="Kishoreganj">	Kishoreganj</option>
							<option value="Madaripur">	Madaripur</option>
							<option value="Manikganj">	Manikganj</option>
							<option value="Munshiganj">	Munshiganj</option>
							<option value="Narayanganj">	Narayanganj</option>
							<option value="Narsingdi">	Narsingdi</option>
							<option value="Rajbari">	Rajbari</option>
							<option value="Shariatpur">	Shariatpur</option>
							<option value="Tangail">	Tangail</option>
							<option value="Bagerhat">	Bagerhat</option>
							<option value="Chuadanga">	Chuadanga</option>
							<option value="Jashore">	Jashore</option>
							<option value="Jhenaidah">	Jhenaidah</option>
							<option value="Khulna">	Khulna</option>
							<option value="Kushtia">	Kushtia</option>
							<option value="Magura">	Magura</option>
							<option value="Meherpur">	Meherpur</option>
							<option value="Narail">	Narail</option>
							<option value="Satkhira">	Satkhira</option>
							<option value="Jamalpur">	Jamalpur</option>
							<option value="Mymensingh">	Mymensingh</option>
							<option value="Netrokona">	Netrokona</option>
							<option value="Sherpur">	Sherpur</option>
							<option value="Bogura">	Bogura</option>
							<option value="Joypurhat">	Joypurhat</option>
							<option value="Naogaon">	Naogaon</option>
							<option value="Natore">	Natore</option>
							<option value="Chapainawabganj">	Chapainawabganj</option>
							<option value="Pabna">	Pabna</option>
							<option value="Rajshahi">	Rajshahi</option>
							<option value="Sirajganj">	Sirajganj</option>
							<option value="Dinajpur">	Dinajpur</option>
							<option value="Gaibandha">	Gaibandha</option>
							<option value="Kurigram">	Kurigram</option>
							<option value="Lalmonirhat">	Lalmonirhat</option>
							<option value="Nilphamari">	Nilphamari</option>
							<option value="Panchagarh">	Panchagarh</option>
							<option value="Rangpur">	Rangpur </option>
							<option value="Thakurgaon">	Thakurgaon</option>
							<option value="Habiganj">	Habiganj</option>
							<option value="Moulvibazar">	Moulvibazar</option>
							<option value="Sunamganj">	Sunamganj</option>
							<option value="Sylhet">	Sylhet</option>
						</select>
                  	</div>
					<div class="col-sm-6">
						<label for="locationArea">Location Area </label>
                    	<input type="text" class="form-control" id="edit_locationArea" name="LocationArea" placeholder=" Location Area " >
                  	</div>
				</div>
				<div class="form-group">
				    <div class="col-sm-6">
					<label for="CustomerType"> <?php echo $type; ?> Type </label> 
                    	<select class="form-control" id="edit_partyType" name="CustomerType" >
							<option value="" selected>~~ Select Type ~~</option>
							<option value="Cash"> Cash </option>
							<option value="Regular"> Regular </option>
						</select>
                  	</div>
					<div class="col-sm-6">
					<label for="CreditLimit"> Credit Limit</label>
					    <?php if($_SESSION['user']=='12'){?>
                    	    <input type="text" class="form-control" id="edit_creditLimit" name="CreditLimit" placeholder=" <?php echo $type; ?> Credit Limit " readonly>
                    	<?php }else{?>
                    	    <input type="text" class="form-control" id="edit_creditLimit" name="CreditLimit" placeholder=" <?php echo $type; ?> Credit Limit " >
                    	<?php } ?>
                    	
					</div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <label for="Status"> Status </label>
                    	<select class="form-control" id="edit_status" name="CustomerStatus" >
							<option value="" selected>~~ Select Status ~~</option>
							<option value="Active"> Active </option>
							<option value="Inactive"> In-Active </option>
						</select>
					</div>	
					<div class="col-sm-6">	
						<label for="Status" style="margin-top: 3%;"> <?php echo $type; ?> Status </label> 
                    	<select class="form-control" id="edit_tblType" name="edit_tblType" >
							<option value="Suppliers"> Suppliers </option>
							<option value="Customers"> Customers </option>
							<option value="Both"> Both </option>
						</select>
					</div>
				</div>	
				<div class="form-group">	
                  	<div class="col-sm-6">
					<label for="CreditLimit"> Address </label>
                    	<textarea  class="form-control" id="edit_partyAddress" name="Address" rows="3" cols="50"  placeholder="Describe address here..."></textarea>
                        
                  	</div>
                  	<div class="col-sm-6">
    				<label for="CustomerType"> <?php echo $type; ?></span>Sales Type </label> 
                    	<select class="form-control" id="EdiCustomerSalesType" name="CustomerSalesType" >
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
					<button type="submit" class="btn btn-primary btn-flat" name="EditCustomer" id="btn_updateCustomer"><i class="fa fa-save"></i> Save </button>
				</div>
				</form>
			</div>
        </div>
    </div>
</div>



<!-- Edit added Customer/Supplier-->
<div class="modal fade" id="editCustomerSupplierBangla">
    <div class="modal-dialog" style="width: 60%;">
        <div class="modal-content">
          	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              		<span aria-hidden="true">&times;</span></button>
            	<h4 class="modal-title"><b>যোগ করা <?php echo $type; ?> সম্পাদনা করুন</b></h4>
          	</div>
          	<div class="modal-body">
          	    <div id="editLoader" style="display:none; text-align:center;" class="col-md-12"><i class='fa fa-spinner fa-spin' style='font-size:50px;color:green'></i></div>
            	<form class="form-horizontal" id="form_editCustomerBangla" method="POST" action="#">
				<input type="hidden" value="<?php echo $type; ?>" id="add_tblType" name="TblType">	
				<input type="hidden" id='UidB' name="id" >
				<div class="form-group">
                  	<div class="col-sm-6">
					<label for="CustomerName"> সরবরাহকারীর নাম </label> 
                    	<br><span id="edit_partyNameB"></span>
                    	<input type="text" class="form-control" id="edit_partyNameBangla" name="edit_partyNameBangla" placeholder=" সরবরাহকারীর নাম  " >
                  	</div>
                  	<div class="col-sm-6">
					<label for="ContactPerson"> যোগাযোগ ব্যক্তি </label> 
                    	<br><span id="edit_contactPersonB"></span>
                    	<input  class="form-control" id="edit_contactPersonBangla" name="edit_contactPersonBangla"  placeholder="যোগাযোগ ব্যক্তি">
                  	</div>
					
					
				</div>
				<div class="form-group">
                  	
					<div class="col-sm-6">
					<label for="PhoneNumber"> ফোন নম্বর </label> 
                    	<br><span id="edit_partyPhoneB"></span>
                    	<input type="text" class="form-control" id="edit_partyPhoneBangla" name="edit_partyPhoneBangla" placeholder="   ফোন নম্বর " >
                  	</div>
                  	<div class="col-sm-6">
						<label for="locationArea">অবস্থান এলাকা </label>
                    	<br><span id="edit_locationAreaB"></span>
                    	<input type="text" class="form-control" id="edit_locationAreaBangla" name="LocationArea" placeholder=" অবস্থান এলাকা " >
                  	</div>
				</div>	
				<div class="form-group">	
                  	<div class="col-sm-6">
					<label for="CreditLimit"> ঠিকানা </label>
                    	<br><span id="edit_partyAddressB"></span>
                    	<textarea  class="form-control" id="edit_partyAddressBangla" name="edit_partyAddressBangla" rows="3" cols="50"  placeholder="ঠিকানা..."></textarea>
                        
                  	</div>
				</div>
				
				
				<div class="modal-footer">
					<button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
					<button type="submit" class="btn btn-primary btn-flat" name="EditCustomer" id="btn_updateCustomerBangla"><i class="fa fa-save"></i> Save </button>
				</div>
				</form>
			</div>
        </div>
    </div>
</div>



