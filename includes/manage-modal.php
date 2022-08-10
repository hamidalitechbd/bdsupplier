<!-- Add -->
<div class="modal fade" id="addnew">
    <div class="modal-dialog">
        <div class="modal-content">
          	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              		<span aria-hidden="true">&times;</span></button>
            	<h4 class="modal-title"><b>Add New <?php echo $type; ?> </b></h4>
          	</div>
          	<div class="modal-body">
            	<form class="form-horizontal" id="form_addUnit" method="POST">
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
                    	<input type="text" class="form-control" id="add_unitName" name="UnitName" onblur="manageAvailability()" placeholder=" <?php echo $typeLabel; ?> ">
						<span id="manage-availability-status"></span>
					</div>
				</div>
				<div class="form-group">
                  	<label for="UnitDescription" class="col-sm-3 control-label">Description / Notes</label>

                  	<div class="col-sm-9">
                    	<input type="text" class="form-control" id="add_unitDescription" name="UnitDescription" placeholder=" Description ">
                    	<input type="hidden" id="add_type" name="type" value="<?php echo $type;?>" />
                  	</div>
				</div>
			
				<div class="modal-footer">
					<button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
					<button type="submit" class="btn btn-primary btn-flat" name="addUnit" id="btn_saveUnit"><i class="fa fa-save"></i> Save </button>
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
                    	<input type="text" class="form-control" id="edit_UnitName" name="UnitName" onblur="manageEditAvailability()" placeholder=" <?php echo $typeLabel; ?> " required readonly>
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
<!-- Edit Image -->
<div class="modal fade" id="editImage">
    <div class="modal-dialog">
        <div class="modal-content">
          	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              		<span aria-hidden="true">&times;</span></button>
            	<h4 class="modal-title"><b>Edit <?php echo $type; ?> Image </b></h4>
          	</div>
          	<div class="modal-body">
          	    <div id="editImageLoader" style="display:none; text-align:center;" class="col-md-12"><i class='fa fa-spinner fa-spin' style='font-size:50px;color:green'></i></div>
            	<form class="form-horizontal" method="POST" id="form_editImage">
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
                    	<input type="hidden" class="form-control" id="brand_id" name="brand_id">
                    	<input type="hidden" class="form-control" id="brand_type" name="brand_type">
                    	<input type="file" class="form-control" id="brand_logo" name="brandLogo" onchange="loadFile(event);" accept=".png, .jpg, .jpeg">
                    	<img  src="images/broken_image.png" style="width: 35%;height: 110px;border-radius: 10%;margin-top: 1%;" id="output"/>
                    	 <span class="text-danger"> (360*360) accept .png, .jpg, .jpeg </span>
					</div>
				</div>
				
				<div class="modal-footer">
					<button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
					<button type="submit" class="btn btn-primary btn-flat" name="editUnit" id="form_editImage12"><i class="fa fa-save"></i> Save </button>
					</form>
				</div>
			</div>
        </div>
    </div>
</div>