<!-- Add -->
<div class="modal fade" id="addnew">
    <div class="modal-dialog">
        <div class="modal-content">
          	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              		<span aria-hidden="true">&times;</span></button>
            	<h4 class="modal-title"><b>Add New Transport Info </b></h4>
          	</div>
          	<div class="modal-body">
            	<form class="form-horizontal" id="form_addTransportInfo" method="POST">
				<div class="form-group">
                  	<label for="transportName" class="col-sm-3 control-label">Transport Name</label>
                  	<div class="col-sm-9">
                    	<input type="text" class="form-control" id="add_transportName" name="transportName" placeholder=" Transport Name ">
					</div>
				</div>
				<div class="form-group">
                  	<label for="contactPerson" class="col-sm-3 control-label">Contact Person</label>
                  	<div class="col-sm-9">
                    	<input type="text" class="form-control" id="add_contactPerson" name="contactPerson" placeholder=" Contact Person ">
                  	</div>
				</div>
			    <div class="form-group">
                  	<label for="contactNo" class="col-sm-3 control-label">Contact Number</label>
                  	<div class="col-sm-9">
                    	<input type="text" class="form-control" id="add_contactNo" name="contactNo" placeholder=" Contact Number ">
                  	</div>
				</div>
				<div class="form-group">
                  	<label for="contactEmail" class="col-sm-3 control-label">Contact Email</label>
                  	<div class="col-sm-9">
                    	<input type="email" class="form-control" id="add_contactEmail" name="contactEmail" placeholder=" Contact Email ">
                  	</div>
				</div>
				<div class="form-group">
                  	<label for="address" class="col-sm-3 control-label">Address</label>
                  	<div class="col-sm-9">
                    	<textarea type="text" class="form-control" id="add_address" name="address" placeholder=" Address "></textarea>
                  	</div>
				</div>
				<div class="form-group">
                  	<label for="remarks" class="col-sm-3 control-label">Remarks</label>
                  	<div class="col-sm-9">
                    	<textarea type="text" class="form-control" id="add_remarks" name="remarks" placeholder=" Remarks "></textarea>
                  	</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
					<button type="submit" class="btn btn-primary btn-flat" name="addTransport" id="btn_saveTransport"><i class="fa fa-save"></i> Save </button>
					</form>
				</div>
			</div>
        </div>
    </div>
</div>
<!-- Add and Edit -->
<div class="modal fade" id="editTransportInfo">
    <div class="modal-dialog">
        <div class="modal-content">
          	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              		<span aria-hidden="true">&times;</span></button>
            	<h4 class="modal-title"><b>Edit Added Transport Information </b></h4>
          	</div>
          	<div class="modal-body">
          	    <div id="editLoader" style="display:none; text-align:center;" class="col-md-12"><i class='fa fa-spinner fa-spin' style='font-size:50px;color:green'></i></div>
            	<form class="form-horizontal" method="POST" id="form_updateTransportInfo">
				<input type="hidden" id="transportInfoId" name="id">
				<div class="form-group">
                  	<label for="transportName" class="col-sm-3 control-label">Transport Name</label>
                  	<div class="col-sm-9">
                    	<input type="text" class="form-control" id="edit_transportName" name="transportName" placeholder=" Transport Name ">
					</div>
				</div>
				<div class="form-group">
                  	<label for="contactPerson" class="col-sm-3 control-label">Contact Person</label>
                  	<div class="col-sm-9">
                    	<input type="text" class="form-control" id="edit_contactPerson" name="contactPerson" placeholder=" Contact Person ">
                  	</div>
				</div>
			    <div class="form-group">
                  	<label for="contactNo" class="col-sm-3 control-label">Contact Number</label>
                  	<div class="col-sm-9">
                    	<input type="text" class="form-control" id="edit_contactNo" name="contactNo" placeholder=" Contact Number ">
                  	</div>
				</div>
				<div class="form-group">
                  	<label for="contactEmail" class="col-sm-3 control-label">Contact Email</label>
                  	<div class="col-sm-9">
                    	<input type="email" class="form-control" id="edit_contactEmail" name="contactEmail" placeholder=" Contact Email ">
                  	</div>
				</div>
				<div class="form-group">
                  	<label for="address" class="col-sm-3 control-label">Address</label>
                  	<div class="col-sm-9">
                    	<textarea type="text" class="form-control" id="edit_address" name="address" placeholder=" Address "></textarea>
                  	</div>
				</div>
				<div class="form-group">
                  	<label for="remarks" class="col-sm-3 control-label">Remarks</label>
                  	<div class="col-sm-9">
                    	<textarea type="text" class="form-control" id="edit_remarks" name="remarks" placeholder=" Remarks "></textarea>
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
					<button type="submit" class="btn btn-primary btn-flat" name="updateTransport" id="btn_updateTransport"><i class="fa fa-save"></i> Save </button>
					</form>
				</div>
			</div>
        </div>
    </div>
</div>
<!-- Add and Edit -->
<div class="modal fade" id="editTransportInfoBangla">
    <div class="modal-dialog">
        <div class="modal-content">
          	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              		<span aria-hidden="true">&times;</span></button>
            	<h4 class="modal-title"><b>যোগ করা পরিবহন তথ্য সম্পাদনা করুন </b></h4>
          	</div>
          	<div class="modal-body">
          	    <div id="editLoader" style="display:none; text-align:center;" class="col-md-12"><i class='fa fa-spinner fa-spin' style='font-size:50px;color:green'></i></div>
            	<form class="form-horizontal" method="POST" id="form_updateTransportInfoBangla">
				<input type="hidden" id="transportInfoIdBangla" name="id">
				<div class="form-group">
                  	<label for="transportName" class="col-sm-3 control-label">পরিবহনের নাম</label>
                  	<div class="col-sm-9">
                    	<span id="edit_transportNameB"></span>
                    	<input type="text" class="form-control" id="edit_transportNameBangla" name="edit_transportNameBangla" placeholder=" পরিবহনের নাম ">
					</div>
				</div>
				<div class="form-group">
                  	<label for="contactPerson" class="col-sm-3 control-label">যোগাযোগ ব্যক্তি</label>
                  	<div class="col-sm-9">
                  	    <span id="edit_contactPersonB"></span>
                    	<input type="text" class="form-control" id="edit_contactPersonBangla" name="edit_contactPersonBangla" placeholder=" যোগাযোগ ব্যক্তি ">
                  	</div>
				</div>
			    <div class="form-group">
                  	<label for="contactNo" class="col-sm-3 control-label">যোগাযোগের নম্বর</label>
                  	<div class="col-sm-9">
                  	    <span id="edit_contactNoB"></span>
                    	<input type="text" class="form-control" id="edit_contactNoBangla" name="edit_contactNoBangla" placeholder=" যোগাযোগের নম্বর ">
                  	</div>
				</div>
			
				<div class="form-group">
                  	<label for="address" class="col-sm-3 control-label">ঠিকানা</label>
                  	<div class="col-sm-9">
                  	    <span id="edit_addressB"></span>
                    	<textarea type="text" class="form-control" id="edit_address_Bangla" name="edit_address_Bangla" placeholder=" ঠিকানা "></textarea>
                  	</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
					<button type="submit" class="btn btn-primary btn-flat" name="updateTransportBangla" id="btn_updateTransportBangla"><i class="fa fa-save"></i> সংরক্ষণ </button>
				</form>
				</div>
			</div>
        </div>
    </div>
</div>
<!--Used in challan system to update transport and transportChallanNo, date-->
<div class="modal fade" id="editTransport">
    <div class="modal-dialog">
        <div class="modal-content">
          	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              		<span aria-hidden="true">&times;</span></button>
            	<h4 class="modal-title"><b>Update Transport </b></h4>
          	</div>
          	<div class="modal-body">
            	<form class="form-horizontal" id="form_updateTransport" method="POST">
            	    <input type="hidden"  id="code" name="code">
    				<div class="form-group">
                      	<label for="transportName" class="col-sm-3 control-label">Transport Name :</label>
                      	<div class="col-sm-9">
                        	<select type="text" class="form-control" name="updateTransport" id="Tid" style="width:100%;" >
                                <?php
                                    $sql = "SELECT id,transportName,contactNo FROM `tbl_transportInfo` WHERE status='Active'";
                                    $query = $conn->query($sql);
                                    while ($prow = $query->fetch_assoc()) {
                                        echo "<option value='" . $prow['id'] . "'>" . $prow['transportName'] . " - " . $prow['contactNo'] . "</option>";
                                    }
                                ?>
                            </select>
    					</div>
    				</div>
    				<div class="form-group">
                      	<label for="contactNo" class="col-sm-3 control-label">Trans. Challan No :</label>
                      	<div class="col-sm-9">
                        	<input type="text" class="form-control" id="transportChallanNo" name="transportChallanNo" placeholder=" Transport Challan No ">
                      	</div>
				    </div>
				    <div class="form-group">
                      	<label for="contactNo" class="col-sm-3 control-label">Date :</label>
                      	<div class="col-sm-9">
                        	<input type="date" class="form-control" id="transportDate" name="transportDate" ">
                      	</div>
				    </div>
    				
				<div class="modal-footer">
					<button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
					<button type="submit" class="btn btn-primary btn-flat" name="updateTransport" id="btn_saveTransport"><i class="fa fa-save"></i> Save </button>
				</form>
				</div>
			</div>
        </div>
    </div>
</div>