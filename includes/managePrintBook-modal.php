<!-- Add new Book-->
<div class="modal fade" id="addNewBook">
    <div class="modal-dialog" style="width: 35%;">
        <div class="modal-content">
          	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              		<span aria-hidden="true">&times;</span></button>
            	<h4 class="modal-title"><b>Add New Catalogue Info </b></h4>
          	</div>
          	<div class="modal-body">
            	<form class="form-horizontal" id="form_addPrintBook" method="POST">
				<div class="form-group">
					<div class="col-sm-12">
						<label for="printBookName" class="control-label">Catalogue Name :</label>
						<input type="text" class="form-control" id="add_printBookName" name="printBookName" placeholder=" Catalogue Name ">
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-6">
						<label for="printBookDate" class="control-label">Date :</label>
						<input type="date" value="<?=date("Y-m-d")?>" class="form-control" id="add_printBookDate" name="printBookDate" >
					</div>
					<div class="col-sm-6">
						<label for="printBookStatus" class="control-label">Status :</label>
						<select class="form-control" id="add_printBookStatus" name="printBookStatus" required>
							<option value="" selected>~~ Choose Status ~~</option>
							<option value="Active" selected>Active</option>
							<option value="Inactive">Inactive</option>
						</select>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
					<button type="submit" class="btn btn-primary" name="addBook" id="btn_saveBook"><i class="fa fa-save"></i> Save Catalogue</button>
				</div>
				</form>
			</div>
        </div>
    </div>
</div>
<!-- Edit new User-->
<div class="modal fade" id="editPrintBook">
    <div class="modal-dialog" style="width: 35%;">
        <div class="modal-content">
          	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              		<span aria-hidden="true">&times;</span></button>
            	<h4 class="modal-title"><b>Edit Added Catalogue Info </b></h4>
          	</div>
          	<div class="modal-body">
            	<form class="form-horizontal" id="form_updatePrintBook" method="POST">
				<input type="hidden" id="edit_printBookId" name="printBookId">
				<div class="form-group">
					<div class="col-sm-12">
						<label for="editPrintBookName" class="control-label">Catalogue Name :</label>
						<input type="text" class="form-control" id="edit_printBookName" name="editPrintBookName" placeholder="Edit Catalogue Name ">
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-6">
						<label for="editPrintBookDate" class="control-label">Date :</label>
						<input type="date"  class="form-control" id="edit_printBookDate" name="editPrintBookDate" >
					</div>
					<div class="col-sm-6">
						<label for="editPrintBookStatus" class="control-label">Status :</label>
						<select class="form-control" id="edit_printBookStatus" name="editPrintBookStatus" required>
							<option value="" selected>~~ Choose Status ~~</option>
							<option value="Active">Active</option>
							<option value="Inactive">Inactive</option>
						</select>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
					<button type="submit" class="btn btn-primary btn-flat" name="updateUser" id="btn_updateUser"><i class="fa fa-save"></i> Update Catalogue</button>
				</div>
				</form>
			</div>
        </div>
    </div>
</div>
