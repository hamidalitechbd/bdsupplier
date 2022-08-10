<!-- Add -->
<div class="modal fade" id="addnew">
    <div class="modal-dialog">
        <div class="modal-content">
          	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              		<span aria-hidden="true">&times;</span></button>
            	<h4 class="modal-title"><b>Add / Change Warehouse</b></h4>
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



