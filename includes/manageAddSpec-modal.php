<!-- Add new Book-->
<div class="modal fade" id="addNewSpec">
	<div class="modal-dialog" style="width: 50%;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><b>Add New Spec </b>
					<div id='divMsg' class='alert alert-success alert-dismissible successMessage' style="width: 250px;float:right; margin-right:30px; font-size:14px;"></div>
				</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" id="form_addPrintBookProductSpec"  method="POST">
					<input type="hidden" id="addSpecProductId" name="addSpecProductId" />
					<input type="hidden" id="specId" name="specId" value="" />
					<div class="form-group">
						<div class="col-sm-4">
							<label for="addSpecName" class="control-label">Spec Name :</label>
							<input type="text" class="form-control" id="add_specName" name="addSpecName" placeholder=" Spec Name ">
						</div>
						<div class="col-sm-3">
							<label for="addSpecValue" class="control-label">Spec Value :</label>
							<input type="text" class="form-control" id="add_specValue" name="addSpecValue" placeholder=" Spec Value ">
						</div>
						<div class="col-sm-3">
							<label for="addSpecType" class="control-label"> Spec Type :</label>
							<select class="form-control" id="add_specType" name="addSpecType" required>
								<option value="Non-Price" selected>Non-Price</option>
							</select>
						</div>
						<div class="col-sm-2">
						    <button  style="margin-top: 32%;" type="submit" class="btn btn-primary" name="addPrintBookSpec" id="btn_savePrintBookSpec"><i class="fa fa-save"></i> Save </button>
						</div>
					</div>
					    <div class="table-responsive">
    						<table class="table" border='1'>
    							<thead>
    								<tr>
    									<th>Spec. Name</th>
    									<th>Spec. Value</th>
    									<th>Spec. Type</th>
    									<th width="14%">Action</th>
    								</tr>
    							</thead>
    							<tbody id="printBookSpecTable">
    
    							</tbody>
    						</table>
						</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- Edit new User-->
<div class="modal fade" id="insertImageAd">
    <div class="modal-dialog">
        <div class="modal-content">
          	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              		<span aria-hidden="true">&times;</span></button>
            	<h4 class="modal-title"><b>Add Ad Image </b></h4>
          	</div>
          	<div class="modal-body">
          	    <form class="form-horizontal" method="POST" id="form_adImageAdd">
			    <div class="form-group">
                  	<div class="col-sm-12">
                  	    
                  	    <input type="hidden" class="form-control" id="catatlougAd" name="catatlougAd">
                  	    <input type="file" class="form-control" id="ad_logo" name="adLogoImage" onchange="loadFile(event);" accept=".png, .jpg, .jpeg">
                    	 <div class="col-sm-5">  
                    	    <img  src="images/broken_image.png" style="width: 100%;height: 150px;margin-top: 1%;" id="output"/>
                    	 </div>
                    	 <div class="col-sm-7" style="margin-top: 10%;">
                    	    <span class="text-danger">For Grid-04 (250 * ~) accept .png, .jpg, .jpeg </span><br>
                    	    <span class="text-danger">For Grid-02 (560 * ~) accept .png, .jpg, .jpeg </span>
                    	 </div>
					</div>
				</div>
				
				<div class="modal-footer">
					<button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
					<button type="submit" class="btn btn-primary btn-flat" name="editUnit" id="form_editImage12"><i class="fa fa-save"></i> Save AD</button>
					</form>
				</div>
			</div>
        </div>
    </div>
</div>


<!-- Start Add Image for List View -->
<div class="modal fade" id="addImage">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><b>Add Apec Image </b></h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" method="POST" id="form_image">
					<div class="form-group">
						<div class="col-sm-12">

							<input type="hidden" class="form-control" id="catatlougId" name="catatlougId">
							<input type="file" class="form-control" id="specImage" name="specImage" onchange="loadImage(event);" accept=".png, .jpg, .jpeg">
							<div class="col-sm-5">
								<img src="images/broken_image.png" style="width: 100%;height: 150px;margin-top: 1%;" id="outputImage" />
							</div>
							<div class="col-sm-7" style="margin-top: 10%;">
								<span class="text-danger">For(200 *600) accept .png, .jpg, .jpeg </span><br>
							</div>
						</div>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
						<button type="submit" class="btn btn-primary btn-flat" name="editUnit" id="form_editImage12"><i class="fa fa-save"></i> Save Image</button>
				</form>
			</div>
		</div>
	</div>
</div>
</div>
<!-- End Add Image for List View  -->