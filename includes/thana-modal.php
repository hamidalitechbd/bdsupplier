<!-- Add -->
<div class="modal fade" id="addnew">
    <div class="modal-dialog">
        <div class="modal-content">
          	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              		<span aria-hidden="true">&times;</span></button>
            	<h4 class="modal-title"><b>Add New Thana</b></h4>
          	</div>
          	<div class="modal-body">
            	<form class="form-horizontal" method="POST" action="thana_add.php">
				<div class="form-group">
                  	<label for="Thananame" class="col-sm-3 control-label">Thana Name</label>

                  	<div class="col-sm-9">
                    	<input type="text" class="form-control" id="Thananame" name="Thananame" placeholder=" Thana name " required>
                  	</div>
				</div>
          		<div class="form-group">
                  	<label for="division" class="col-sm-3 control-label">Divisions</label>

                  	<div class="col-sm-9">
                    	<select class="form-control" id="division" name="division"  required>
							<option value="" selected>~~ Select One ~~</option>
							<?php
								  $sql = "SELECT * FROM `districts`";
								  $query = $conn->query($sql);
								  while($prow = $query->fetch_assoc()){
									echo "
									  <option value='".$prow['id']."'>".$prow['division']." - ".$prow['districts']."</option>
									";
								  }
								?>
						</select>
                  	</div>
                </div>
				
				<div class="form-group">
                  	<label for="Thananame" class="col-sm-3 control-label">Phone</label>

                  	<div class="col-sm-9">
                    	<input type="text" class="form-control" id="Thanaphone" name="phone" placeholder=" Thana phone " required>
                  	</div>
				</div>
                <div class="form-group">
                    <label for="Address" class="col-sm-3 control-label">Address</label>

                    <div class="col-sm-9">
                      <textarea type="text" class="form-control" id="Address" name="Address" placeholder=" Write about category " ></textarea>
                    </div>
                </div>
				<div class="form-group">
				<label for="Address" class="col-sm-3 control-label">Status</label>
				<div class="col-sm-9">
					<select class="form-control" id="substatus11" name="Status"  required>
						<option value="" selected >~~ Select One ~~</option>
						<option value="1"> Active </option>
						<option value="2"> Inactive </option>
					</select>
				</div>
				</div>
			</div>
          	<div class="modal-footer">
            	<button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
            	<button type="submit" class="btn btn-primary btn-flat" name="addthana"><i class="fa fa-save"></i> Save </button>
            	</form>
          	</div>
        </div>
    </div>
</div>

<!-- Edit -->
<div class="modal fade" id="editThana">
    <div class="modal-dialog">
        <div class="modal-content">
          	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              		<span aria-hidden="true">&times;</span></button>
            	<h4 class="modal-title"><b>Edit New Thana</b></h4>
          	</div>
          	<div class="modal-body">
            	<form class="form-horizontal" method="POST" action="thana_edit.php">
				<input type="hidden" id="subThanaid" name="id" > 
				<div class="form-group">
                  	<label for="Thananame" class="col-sm-3 control-label">Thana Name</label>

                  	<div class="col-sm-9">
                    	<input type="text" class="form-control" id="thana_name" name="Thananame" placeholder=" Thana name " required>
                  	</div>
				</div>
          		<div class="form-group">
                  	<label for="division" class="col-sm-3 control-label">Divisions</label>

                  	<div class="col-sm-9">
                    	<select class="form-control" id="districtsID" name="division"  required>
							<option value="" selected>~~ Select One ~~</option>
							<?php
								  $sql = "SELECT * FROM `districts`";
								  $query = $conn->query($sql);
								  while($prow = $query->fetch_assoc()){
									echo "
									  <option value='".$prow['id']."'>".$prow['division']." - ".$prow['districts']."</option>
									";
								  }
								?>
						</select>
                  	</div>
                </div>
				
				<div class="form-group">
                  	<label for="Thananame" class="col-sm-3 control-label">Phone</label>

                  	<div class="col-sm-9">
                    	<input type="text" class="form-control" id="subphone" name="phone" placeholder=" Thana phone " required>
                  	</div>
				</div>
                <div class="form-group">
                    <label for="Address" class="col-sm-3 control-label">Address</label>

                    <div class="col-sm-9">
                      <textarea type="text" class="form-control" id="Subaddress" name="Address" placeholder=" Write about category " ></textarea>
                    </div>
                </div>
				<div class="form-group">
				
					<label for="Address" class="col-sm-3 control-label">Status</label>
					<div class="col-sm-9">
					<select class="form-control" id="substatus" name="Status"  required>
						<option value="" selected>~~ Select One ~~</option>
						<option value="1" selected> Active </option>
						<option value="2" selected> Inactive </option>
					</select>
				</div>
				</div>
			</div>
          	<div class="modal-footer">
            	<button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
            	<button type="submit" class="btn btn-primary btn-flat" name="editthana"><i class="fa fa-save"></i> Save </button>
            	</form>
          	</div>
        </div>
    </div>
</div>
<!-- Delete -->
<div class="modal fade" id="delete">
    <div class="modal-dialog">
        <div class="modal-content">
          	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              		<span aria-hidden="true">&times;</span></button>
            	<h4 class="modal-title"><b>Deleting...</b></h4>
          	</div>
          	<div class="modal-body">
            	<form class="form-horizontal" method="POST" action="company_delete.php">
            		<input type="hidden" id="del_comid" name="id">
            		<div class="text-center">
	                	<p>DELETE POSITION</p>
	                	<h2 id="del_company" class="bold"></h2>
	            	</div>
          	</div>
          	<div class="modal-footer">
            	<button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
            	<button type="submit" class="btn btn-danger btn-flat" name="delete"><i class="fa fa-trash"></i> Delete</button>
            	</form>
          	</div>
        </div>
    </div>
</div>

<!-- Add new soldires -->
<div class="modal fade" id="addnewSoldiers">
    <div class="modal-dialog">
        <div class="modal-content">
          	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              		<span aria-hidden="true">&times;</span></button>
            	<h4 class="modal-title"><b>Add New Soldiers</b></h4>
          	</div>
          	<div class="modal-body">
            	<form class="form-horizontal" method="POST" action="soldiers-add.php">
				
          		<div class="form-group" id="frmCheckUsername">
                  	<label for="Thananame" class="col-sm-3 control-label">Police Code</label>

                  	<div class="col-sm-9">
                    	<input type="text" class="form-control" id="policeCode" name="policeCode" placeholder=" Thana name " onBlur="checkAvailability()" required>
						<span id="user-availability-status"></span>
					</div>
				</div>
				<div class="form-group">
                  	<label for="policename" class="col-sm-3 control-label">Name</label>

                  	<div class="col-sm-9">
                    	<input type="text" class="form-control" id="policename" name="policename" placeholder=" Thana name " required>
                  	</div>
				</div>
				<div class="form-group">
                  	<label for="Thananame" class="col-sm-3 control-label">Phone</label>

                  	<div class="col-sm-9">
                    	<input type="text" class="form-control" id="Thanaphone" name="phone" placeholder=" Thana phone " required>
                  	</div>
				</div>
                <div class="form-group">
                    <label for="Address" class="col-sm-3 control-label">Address</label>

                    <div class="col-sm-9">
                      <textarea type="text" class="form-control" id="Address" name="Address" placeholder=" Write about category " ></textarea>
                    </div>
                </div>
				<div class="form-group">
				<label for="Address" class="col-sm-3 control-label">Status</label>
				<div class="col-sm-9">
					<select class="form-control" id="substatus11" name="Status"  required>
						<option value="" selected >~~ Select One ~~</option>
						<option value="Active"> Active </option>
						<option value="Inactive"> Inactive </option>
					</select>
				</div>
				</div>
			</div>
          	<div class="modal-footer">
            	<button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
            	<button type="submit" class="btn btn-primary btn-flat" name="addSoldiers"><i class="fa fa-save"></i> Save </button>
            	</form>
          	</div>
        </div>
    </div>
</div>


<!-- Edit soldires -->
<div class="modal fade" id="editSoldiers">
    <div class="modal-dialog">
        <div class="modal-content">
          	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              		<span aria-hidden="true">&times;</span></button>
            	<h4 class="modal-title"><b>Edit Soldiers</b></h4>
          	</div>
          	<div class="modal-body">
            	<form class="form-horizontal" method="POST" action="soldiers-add.php">
				<input type="hidden" id="soldiersid" name="id" >
          		<div class="form-group" id="frmCheckUsername">
                  	<label for="Thananame" class="col-sm-3 control-label">Police Code</label>

                  	<div class="col-sm-9">
                    	<input type="text" class="form-control" id="soldiers_Id" name="policeCode" placeholder=" Thana name "  readonly>
						<span id="user-availability-status"></span>
					</div>
				</div>
				<div class="form-group">
                  	<label for="policename" class="col-sm-3 control-label">Name</label>

                  	<div class="col-sm-9">
                    	<input type="text" class="form-control" id="soldiersname" name="policename" placeholder=" Thana name " required>
                  	</div>
				</div>
				<div class="form-group">
                  	<label for="Thananame" class="col-sm-3 control-label">Phone</label>

                  	<div class="col-sm-9">
                    	<input type="text" class="form-control" id="soldiersPhone" name="phone" placeholder=" Thana phone " required>
                  	</div>
				</div>
                <div class="form-group">
                    <label for="Address" class="col-sm-3 control-label">Address</label>

                    <div class="col-sm-9">
                      <textarea type="text" class="form-control" id="soldiersaddress" name="Address" placeholder=" Write about category " ></textarea>
                    </div>
                </div>
				<div class="form-group">
				<label for="Address" class="col-sm-3 control-label">Status</label>
				<div class="col-sm-9">
					<select class="form-control" id="soldiersstatus" name="Status"  required>
						<option value="" selected >~~ Select One ~~</option>
						<option value="Active"> Active </option>
						<option value="Inactive"> Inactive </option>
					</select>
				</div>
				</div>
			</div>
          	<div class="modal-footer">
            	<button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
            	<button type="submit" class="btn btn-primary btn-flat" name="EditSoldiers"><i class="fa fa-save"></i> Save </button>
            	</form>
          	</div>
        </div>
    </div>
</div>

<!-- Delete soldiers-->
<div class="modal fade" id="deleteSoldiers">
    <div class="modal-dialog">
        <div class="modal-content">
          	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              		<span aria-hidden="true">&times;</span></button>
            	<h4 class="modal-title"><b>Deleting...</b></h4>
          	</div>
          	<div class="modal-body">
            	<form class="form-horizontal" method="POST" action="soldiers-add.php">
            		<input type="hidden" id="del_solid" name="id">
            		<input type="hidden"  name="status" value='Inactive'>
            		<div class="text-center">
	                	<p>DELETE POSITION</p>
	                	<h2 id="del_soldiers_Id" class="bold"></h2>
	            	</div>
          	</div>
          	<div class="modal-footer">
            	<button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
            	<button type="submit" class="btn btn-danger btn-flat" name="deleteSoldiers"><i class="fa fa-trash"></i> Delete</button>
            	</form>
          	</div>
        </div>
    </div>
</div>
