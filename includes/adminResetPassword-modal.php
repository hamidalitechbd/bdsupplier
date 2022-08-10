<!-- Re-set password new User-->
<?php 
    if(strtolower($_SESSION['userType']) == "admin" || strtolower($_SESSION['userType']) == 'super admin'){	
?>
<div class="modal fade" id="ChangePassword">
    <div class="modal-dialog">
        <div class="modal-content">
          	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              		<span aria-hidden="true">&times;</span></button>
            	<h4 class="modal-title"><b>Re-set Password </b></h4>
          	</div>
          	<div class="modal-body">
            	<form class="form-horizontal" id="form_ReSetPassword" method="POST">
				<div class="form-group">
					<div class="col-sm-6">
						<label for="transportName" class="control-label">User Name :</label>
						<select class="form-control" id="reset_userName" name="userName">
							<option value="" selected>~~ Choose User Name ~~</option>
							 <?php
                                $sql = "SELECT id,username FROM `tbl_users` WHERE accountStatus='Approved'";
                                $query = $conn->query($sql);
                                while ($prow = $query->fetch_assoc()) {
                                    echo "
									  <option value='" . $prow['id'] . "'>" . $prow['username'] . "</option>
									";
                                }
                                ?>
						</select>
					</div>
					<div class="col-sm-6">
						<label for="transportName" class="control-label">Password :</label>
						<input type="text" class="form-control" id="add_userPassword" name="userPassword" placeholder=" User Password ">
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
					<button type="submit" class="btn btn-primary btn-flat" name="updateUserPassword" id="btn_updateUserPassword"><i class="fa fa-save"></i> Re-set Password </button>
					
				</div>
				</form>
			</div>
        </div>
    </div>
</div>
<?php
}
?>