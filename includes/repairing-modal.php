<!-- Add -->
<div class="modal fade" id="addnew">
    <div class="modal-dialog">
        <div class="modal-content">
          	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              		<span aria-hidden="true">&times;</span></button>
            	<h4 class="modal-title"><b>New Repairing Form </b></h4>
          	</div>
          	<div class="modal-body">
            	<form class="form-horizontal" id="form_addRepair" method="POST">
				<div class="form-group">
                  	<div class="col-sm-6">
                  	    <label for="UnitName" class="control-label">Customer Name</label>
                    	<select class="form-control" name="partyName" id="add_partyName" style="width:100%;" required>
                            <option value="" selected>~~ Select Warehouse ~~</option>
                            <?php
                            $sql = "SELECT id,partyName,locationArea,tblCity FROM `tbl_party` WHERE status='Active' AND deleted='No' ORDER BY `partyName`  ASC";
                            $query = $conn->query($sql);
                            while ($prow = $query->fetch_assoc()) {
                                echo "
								  <option value='" . $prow['id'] . "'>". $prow['partyName'] ." - ".$prow['locationArea']." ".$prow['tblCity']."</option>
								";
                            }
                            ?>
                        </select>
					</div>
					<div class="col-sm-6">
                  	    <label for="referenceBy" class="control-label">Reference By</label>
                    	<select class="form-control" name="referenceBy" id="add_referenceBy" style="width:100%;" required>
                            <option value="" selected>~~ Select Warehouse ~~</option>
                            <?php
                            $sql = "SELECT id,fname FROM `tbl_users` WHERE accountStatus='approved' AND deleted='no' ORDER BY `tbl_users`.`id`  ASC";
                            $query = $conn->query($sql);
                            while ($prow = $query->fetch_assoc()) {
                                echo "
								  <option value='" . $prow['id'] . "'>". $prow['fname'] ."</option>
								";
                            }
                            ?>
                        </select>
					</div>
				</div>
				<div class="form-group">
				    <div class="col-sm-12">
                  	    <label for="UnitDescription" class="control-label">Description / Notes</label>
                    	<textarea type="text" class="form-control" id="add_repairDescription" name="repairDescription" placeholder=" Description "></textarea>
                    </div>
                    
				</div>
				<div class="form-group">
				    <div class="col-sm-6">
					    <label for="date" class="control-label">Date</label>
                    	<input type="date" class="form-control" id="add_Date" name="date" >
                    </div>
                    <div class="col-sm-6">
					    <label for="date" class="control-label">Amount</label>
                    	<input type="text" class="form-control" id="add_amount" name="amount" placeholder=" Repairng Amount " >
                    </div>
				</div>
			
				<div class="modal-footer">
					<button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
					<button type="submit" class="btn btn-primary btn-flat" name="addRepair" id="btn_saveRepair"><i class="fa fa-save"></i> Save </button>
					</form>
				</div>
			</div>
        </div>
    </div>
</div>
<!-- Add and Edit -->
<div class="modal fade" id="editUnit">
    <div class="modal-dialog">
        <div class="modal-content">
          	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              		<span aria-hidden="true">&times;</span></button>
            	<h4 class="modal-title"><b>Edit Added <?php echo $type; ?> </b></h4>
          	</div>
          	<div class="modal-body">
          	    <div id="editLoader" style="display:none; text-align:center;" class="col-md-12"><i class='fa fa-spinner fa-spin' style='font-size:50px;color:green'></i></div>
            	<form class="form-horizontal" method="POST" id="form_editUnit">
				<input type="hidden" id="Uid" name="id">
				<div class="form-group">
                  	<label for="UnitName" class="col-sm-3 control-label">
                  	<?php 
                  	    $typeLabel = $type;
              	        if($type != "User Type"){
              	            $typeLabel .= " Name";
              	        }
              	        echo $typeLabel;
          	        ?>   
          	        </label>

                  	<div class="col-sm-9">
                    	<input type="text" class="form-control" id="edit_UnitName" name="UnitName" onblur="manageEditAvailability()" placeholder=" <?php echo $typeLabel; ?> " required>
						<span id="manageEdit-availability-status"></span>
					</div>
				</div>
				<div class="form-group">
                  	<label for="editUnitDescription" class="col-sm-3 control-label">Description / Note</label>

                  	<div class="col-sm-5">
                    	<input type="text" class="form-control" id="edit_UnitDescription" name="editUnitDescription" placeholder=" Description " >
                    	<span class="descriptionErr"></span>
                  	</div>
					<div class="col-sm-4">
						<select class="form-control" id="edit_Unitstatus" name="Ustatus">
							<option value="Active">Active</option>
							<option value="In-Active">In-Active</option>
						</select>
					</div>
				</div>
			    <input type="hidden" id="edit_type" name="type" value="<?php echo $type;?>" required />
				<div class="modal-footer">
					<button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
					<button type="submit" class="btn btn-primary btn-flat" name="editUnit" id="btn_updateUnit"><i class="fa fa-save"></i> Save </button>
					</form>
				</div>
			</div>
        </div>
    </div>
</div>