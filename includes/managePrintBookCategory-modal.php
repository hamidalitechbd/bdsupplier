<!-- Start Add new PrintBook Category-->

<div class="modal fade" id="addNewPrintBookCategory">
	<div class="modal-dialog" style="width: 80%;">
		<div class="modal-content" style="border-radius: 0%;">
			<form class="form-horizontal" id="form_addPrintBookCategory" method="POST">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title"><b>Add New Catalogue Info </b></h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-7">
							<div class="form-group">
								<div class="col-sm-4">
									<label for="addPrintBookName" class="control-label"> Catalogue Name :</label>
									<select class="form-control" id="add_printBookName" name="addPrintBookName" required>
										<option value="" selected>~~ Choose Catalogue Name ~~</option>
										<?php
										$sql = "SELECT id,book_name FROM `tbl_printbook` WHERE status='Active' AND deleted='No' ORDER BY id DESC";
										$query = $conn->query($sql);
										while ($prow = $query->fetch_assoc()) {
											echo "
											<option value='" . $prow['id'] . "'>" . $prow['book_name'] . "</option>
											";
										}
										?>
									</select>
								</div>
								<div class="col-sm-4">
									<label for="addCategoryName" class="control-label"> Category :</label>
									<select class="form-control" id="add_categoryName" name="addCategoryName" required>
										<option value="" selected>~~ Choose Category ~~</option>
										<?php
										$sql = "SELECT id,categoryName FROM `tbl_category` WHERE status='Active' AND deleted='no' ORDER BY id DESC";
										$query = $conn->query($sql);
										while ($prow = $query->fetch_assoc()) {
											echo "
											<option value='" . $prow['id'] . "'>" . $prow['categoryName'] . "</option>
											";
										}
										?>
									</select>
								</div>
								<div class="col-sm-4">
									<label for="addBrandName" class="control-label"> Brand :</label>
									<select class="form-control" id="add_brandName" name="addBrandName" onchange="loadItemByBrands(1)" required>
										<option value="" selected>~~ Choose Brand ~~</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-4">
								    
								    <label for="viewType" class="control-label"> View Type :</label>
									<select class="form-control" id="add_viewType" name="viewType">
										<option value="" selected>~~ Choose Type ~~</option>
										<option value="Column">Column</option>
										<option value="List">List</option>
										<option value="singleProducts">Single Product</option>
									</select>
								    
									<label for="addType" class="control-label"> Type :</label>
									<select class="form-control" id="add_Type" name="addType">
										<option value="" selected>~~ Choose Type ~~</option>
										<option value="Tools" selected>Tools</option>
										<option value="Accessories">Accessories</option>
									</select>
									
									
									
									
									<label for="list_offer" class="control-label"> List Offer :</label>
									<input type="text" id="list_offer" name="list_offer" class="form-control" />
								</div>
								
								<div class="col-sm-4">
									<label for="addBannerImage" class="control-label">Header Image :</label>
									<input type="file" class="form-control" id="add_bannerImage" name="addBannerImage">
									<span>* Image Size (1100*350)Px</span><br>
									<span>* For Single Products Image Size (1135*680)Px</span>
								</div>
								<div class="col-sm-4">
									<label for="addBannerImage" class="control-label">Header Image Alternate:</label>
									<input type="file" class="form-control" id="add_bannerImage2" name="addBannerImage2">
									<span>* Image Size (1100*350)Px</span><br>
									<span>* Not Applicable For Single Products</span>
								</div>
							</div>
							
							<div class="form-group">
								<div class="col-sm-12">
									<label for="addPageFooter" class="control-label"> Catalogue Footer :</label>
									<textarea id='addPageFooter' name='addPageFooter'></textarea><br>
								</div>
							</div>
						</div>
						<div class="col-md-5">
							<div class="form-group">
								<div class="col-sm-12">
									<table id="tbl_manageCart" style="table-layout: fixed;width: 100%;" class="table table-bordered">
										<thead>
											<tr>
												<th scope="col" width="10%">SN#</th>
												<th scope="col">Product</th>
												<th scope="col">productCode</th>
												<th scope="col" width="13%">Action</th>
											</tr>
										</thead>
										<tbody id="manageCartTable">

										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
					<button type="submit" class="btn btn-primary btn-flat" name="addPrintBookCategory" id="btn_savePrintBookCategory"><i class="fa fa-save"></i> Save Catalogue</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- Start Add new PrintBook Category-->




<!-- Start Edit PrintBook Category-->
<div class="modal fade" id="editPrintBookCategory">
	<div class="modal-dialog" style="width: 80%;">
		<div class="modal-content" style="border-radius: 0%;">
			<form class="form-horizontal" id="form_editPrintBookCategory" method="POST">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title"><b>Edit Added PrintBook Category Info </b></h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-7">
							<input type="hidden" id="edit_PrintBookCategoryId" name="printBookCategoryId">
							<div class="form-group">
								<div class="col-sm-4">
									<label for="editPrintBookName" class="control-label"> PrintBook :</label>
									<select class="form-control" id="edit_printBookName" name="editPrintBookName" required disabled>
										<option value="" selected>~~ Choose PrintBook ~~</option>
										<?php
										$sql = "SELECT id,book_name FROM `tbl_printbook` WHERE status='Active' AND deleted='No' ORDER BY id DESC";
										$query = $conn->query($sql);
										while ($prow = $query->fetch_assoc()) {
											echo "
									  <option value='" . $prow['id'] . "'>" . $prow['book_name'] . "</option>
									";
										}
										?>
									</select>
								</div>
								<div class="col-sm-4">
									<label for="editCategoryName" class="control-label"> Category :</label>
									<select class="form-control" id="edit_categoryName" name="editCategoryName" required disabled>
										<option value="" selected>~~ Choose Category ~~</option>
										<?php
										$sql = "SELECT id,categoryName FROM `tbl_category` WHERE status='Active' AND deleted='no' ORDER BY id DESC";
										$query = $conn->query($sql);
										while ($prow = $query->fetch_assoc()) {
											echo "
									  <option value='" . $prow['id'] . "'>" . $prow['categoryName'] . "</option>
									";
										}
										?>
									</select>
								</div>
								<div class="col-sm-4">
									<label for="editBrandName" class="control-label"> Brand :</label>
									<select class="form-control" id="edit_brandName" name="editBrandName" onchange="loadItemByBrands(0)" required disabled>
										<option value="">~~ Choose Brand ~~</option>
										<?php
										$sql = "SELECT id,brandName FROM `tbl_brands` WHERE status='Active' AND deleted='no' ORDER BY id DESC";
										$query = $conn->query($sql);
										while ($prow = $query->fetch_assoc()) {
											echo "
									  <option value='" . $prow['id'] . "'>" . $prow['brandName'] . "  </option>
									";
										}
										?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-4">
									<label for="editType" class="control-label"> Type :</label>
									<select class="form-control" id="edit_Type" name="editType">
										<option value="" selected>~~ Choose Type ~~</option>f
										<option value="Tools">Tools</option>
										<option value="Accessories">Accessories</option>
									</select>
									
									<select class="form-control" id="edit_viewType" name="editviewType"  style="display:none;">
										<option value="" selected>~~ Choose Column ~~</option>
										<option value="Column">Column</option>
										<option value="List">List</option>
										<option value="singleProducts">Single Product</option>
									</select>
									
									<label for="editList_Offer" class="control-label"> List Offer :</label>
									<input type="text" id="editListOffer" name="editList_Offer" class="form-control" />
									
								</div>
								<div class="col-sm-4">
									<label for="editBannerImage" class="control-label">Banner Image :</label>
									<input type="file" class="form-control" id="edit_bannerImage" name="editBannerImage">
									<img id="editBannerImageView" src="{{ url('upload/no_image.png') }}" style="width: 180px;height: 80px;border: 1px solid gray;border-radius: 4px;margin-top: 2%;">
								    <span>* Image Size (1100*350)Px</span><br>
									<span>* For Single Products Image Size (1135*680)Px</span>
								</div>
								<div class="col-sm-4">
									<label for="editBannerImage2" class="control-label">Banner Image Alternate:</label>
									<input type="file" class="form-control" id="edit_bannerImage2" name="editBannerImage2">
									<img id="editBannerImageView2" src="{{ url('upload/no_image.png') }}" style="width: 180px;height: 80px;border: 1px solid gray;border-radius: 4px;margin-top: 2%;">
								    <span>* Image Size (1100*350)Px</span><br>
									<span>* Not Applicable For Single Products</span>
								</div>
							</div>
							
							<textarea type="text" style="display:none;" rows="3" cols="20" class="form-control" id="edit_reportFooter" name="editReportFooter" placeholder=" Report Footer "></textarea>
							
							<div class="form-group">
							    <div class="col-sm-12" id="viewSingleSpec">
									<label for="editApplicationSpecification" class="control-label"> Application Specification :</label>
									<textarea id='editApplicationSpecification' name='editApplicationSpecification'></textarea>
								</div>
								<div class="col-sm-12">
									<label for="editPageFooter" class="control-label"> Report Footer :</label>
									<textarea id='editPageFooter' name='editPageFooter'></textarea><br>
								</div>
								<div class="col-sm-12">
								    <table class="table" width="100%" id="tbl_removedCart">
										<thead>
											<tr>
												<th scope="col">SN#</th>
												<th scope="col">Product</th>
												<th scope="col">productCode</th>
												<th scope="col">Action</th>
											</tr>
										</thead>
										<tbody id="manageRemovedCartTable">

										</tbody>
									</table>
								</div>
							</div>
						</div>
						<div class="col-md-5">
							<div class="form-group">
								<div class="col-sm-12">
									<table class="table" width="100%" id="tbl_manageCart">
										<thead>
											<tr>
												<th scope="col">SN#</th>
												<th scope="col">Product</th>
												<th scope="col">productCode</th>
												<th scope="col">Action</th>
											</tr>
										</thead>
										<tbody id="manageEditCartTable">

										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
					<button type="submit" class="btn btn-primary btn-flat" name="addPrintBookCategory" id="btn_savePrintBookCategory"><i class="fa fa-save"></i> Save Catalogue</button>
				</div>
			</form>
			<!-- End Edit PrintBook Category -->
		</div>
	</div>
</div>

<!-- Start Add Pdf-->
<div class="modal fade" id="addPdf">
	<div class="modal-dialog" style="width: 50%;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><b>Add New Downloaded Pdf File </b>
					<!-- <div id='divHeadMsg' class='alert alert-success alert-dismissible successMessage' style="width: 250px;float:right; margin-right:30px; font-size:14px;"></div> -->
				</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" method="POST" enctype="multipart/form-data" >
				<input type="hidden" id="printBookId" name="printBookId" value="">
				<input type="hidden" id="printBookCategoryId" name="printBookCategoryId" value="">
					<div class="form-group">
						<div class="col-sm-10">
							<label for="addSpecHeadName" class="control-label">Add Pdf : </label>
							<input type="file" class="form-control" id="pdfName" name="pdfName">
						</div>
						<div class="col-sm-2">
						    <button  style="margin-top: 33%;" type="button" class="btn btn-primary" name="addSpecHead" id="btn_saveSpecHead" onclick="savePdf();"><i class="fa fa-save"></i> Save </button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- End Add Pdf-->

