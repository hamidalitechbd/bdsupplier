
<!-- Start Add Spec Head-->
<div class="modal fade" id="addSpecHead">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><b>Add Spec Head </b>
					<!-- <div id='divHeadMsg' class='alert alert-success alert-dismissible successMessage' style="width: 250px;float:right; margin-right:30px; font-size:14px;"></div> -->
				</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal">
				<input type="hidden" id="printBookId" name="printBookId">
				<input type="hidden" id="editSpecHead" name="editSpecHead" value="">
					<div class="form-group">
						<div class="col-sm-10">
							<label for="addSpecHeadName" class="control-label">Spec Head Name : </label>
							<input type="text" class="form-control" id="addSpecHeadName" name="addSpecHeadName" placeholder=" Enter Head Name">
						</div>
						<div class="col-sm-2">
						    <button  style="margin-top: 38%;" type="button" class="btn btn-primary" name="addSpecHead" id="btn_saveSpecHead" onclick="saveSpecHead();"><i class="fa fa-save"></i> Save </button>
						</div>
					</div>
					    <div class="table-responsive">
    						<table class="table" border='1'>
    							<thead>
    								<tr>
										<th width="5%">SN#</th>
										<th>Spec Head Name</th>
										<th width="15%">Action</th>
    								</tr>
    							</thead>
    							<tbody id="manageAddSpecHeadTable">
    
    							</tbody>
    						</table>
						</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- End Add Spec Head-->




<!-- Start Add New Spec List -->
<div class="modal fade" id="addSpecList">
	<div class="modal-dialog" style="width: 50%;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><b>Add New Spec</b>
				</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" id="form_addProductSpecList"  method="POST">
					<input type="hidden" id="addSpecProductId" name="addSpecProductId" />
					<input type="hidden" id="specId" name="specId" value="" />
					<input type="hidden" id="editSpecHeadId" name="editSpecHeadId" value="" />
					<div class="form-group" id="specListFileds"></div>
						<div class="modal-footer">
						    <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
						    <button  type="button" class="btn btn-primary" name="addSpecList" id="btn_saveSpecList" onclick="saveSpecList();"><i class="fa fa-save"></i> Save </button>
						</div>
					
				</form>
			</div>
		</div>
	</div>
</div>
<!-- End Add New Spec List -->




<!-- Start Add Pdf-->
<div class="modal fade" id="addPdf">
	<div class="modal-dialog" style="width: 50%;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><b>Add Pdf  </b>
					<!-- <div id='divHeadMsg' class='alert alert-success alert-dismissible successMessage' style="width: 250px;float:right; margin-right:30px; font-size:14px;"></div> -->
				</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" method="POST" enctype="multipart/form-data" >
				<input type="hidden" id="printBookId" name="printBookId" value="">
				<input type="hidden" id="printBookCategoryId" name="printBookCategoryId" value="">
					<div class="form-group">
						<div class="col-sm-8">
							<label for="addSpecHeadName" class="control-label">Add Pdf : </label>
							<input type="file" class="form-control" id="pdfName" name="pdfName">
						</div>
						<div class="col-sm-2">
						    <button  style="margin-top: 32%;" type="button" class="btn btn-primary" name="addSpecHead" id="btn_saveSpecHead" onclick="savePdf();"><i class="fa fa-save"></i> Save </button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- End Add Pdf-->



<!-- Start Add Pdf Change Status-->
<div class="modal fade" id="changeStatus">
	<div class="modal-dialog" style="width: 30%;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><b>Change Status  </b>
					<!-- <div id='divHeadMsg' class='alert alert-success alert-dismissible successMessage' style="width: 250px;float:right; margin-right:30px; font-size:14px;"></div> -->
				</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" method="POST" enctype="multipart/form-data" >
				<input type="text" id="printBookId" name="printBookId" value="">
				<input type="text" id="printBookCategoryId" name="printBookCategoryId" value="">
					<div class="form-group">
						<div class="col-sm-12">
							<label for="addSpecHeadName" class="control-label">Add Pdf : </label>
							<select class="form-control" id="printStatus" name="printStatus" required>
								<option selected>~~ Choose Status ~~</option>
								<option value="Active"> Active</option>
								<option value="Inactive"> Inactive</option>
							</select>
						</div>
						<div class="col-sm-2">
						    <button  style="margin-top: 32%;" type="button" class="btn btn-primary" name="addSpecHead" id="btn_saveSpecHead" onclick="saveStatus();"><i class="fa fa-save"></i> Save </button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- End Add Pdf Change Status-->