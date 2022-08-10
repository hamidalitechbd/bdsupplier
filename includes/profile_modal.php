<!-- Add -->
<div class="modal fade" id="profile">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><b>User Profile</b></h4>
            </div>
            <form class="form-horizontal" method="POST" id="form_EditProfile" method="POST" action="#" enctype="multipart/form-data">
                <div class="modal-body">

                    <div class="form-group">
                        <label for="username" class="col-sm-3 control-label">User Name</label>

                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="add_username" name="username" value="<?php echo $user['fname'] .' '. $user['lname'];?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password" class="col-sm-3 control-label">New Password</label>

                        <div class="col-sm-9"> 
                            <input type="password" class="form-control" id="add_password" name="password" placeholder="New password">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="curr_password" class="col-sm-3 control-label">Current Password:</label>

                        <div class="col-sm-9">
                            <input type="password" class="form-control" id="curr_password" name="curr_password" placeholder="input current password to save changes" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="photo" class="col-sm-3 control-label">Photo:</label>

                        <div class="col-sm-9">
                            <input type="file" id="add_photo" name="photo">
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                    <button type="submit" class="btn btn-success btn-flat" name="save"><i class="fa fa-check-square-o"></i> Save</button>

                </div>
            </form>
        </div>
    </div>
</div>
