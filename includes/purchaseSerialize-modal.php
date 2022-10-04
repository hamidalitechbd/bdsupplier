<!-- Unit Price From Purchase panel-->
<div class="modal fade" id="myModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<form class="form-horizontal" id="form_addPurchaseProducts" method="POST">
				<div class="modal-body">

					<input type="hidden" value="Suppliers" id="add_tblType" name="TblType">
					<div class="form-group">
						<div class="col-sm-6">
							<label for="add_unitPrice"> Product Type </label>
							<input type="text" class="form-control" id="add_productType" name="productType" Disabled />
						</div>
						<div class="col-sm-6">
							<label for="add_quantity"> Items-Box </label>
							<input type="text" class="form-control" id="add_items_in_box" name="items_in_box" Disabled />
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-6">
							<label for="add_unitPrice"> Purchase Price </label>
							<input type="text" class="form-control" id="add_unitPrice" name="unitPrice" placeholder=" Add Unit Price " value="0">
						</div>
						<div class="col-sm-6">
							<label for="add_quantity"> Quantity </label>
							<input type="text" class="form-control" id="add_quantity" name="quantity" placeholder=" Add Quantity " onchange="showSerializTable()">
						</div>
					</div>
					<div class="form-group" style='display:none;'>
						<div class="col-sm-6">
							<label for="add_minPrice"> Min Sale Price </label>
							<input type="text" class="form-control" id="add_minPrice" name="minPrice" value="0" onblur="MinimumNValidate()" placeholder=" Minimum Price ">
						</div>
						<div class="col-sm-6">
							<label for="add_maxPrice"> Max Sale Price </label>
							<input type="text" class="form-control" id="add_maxPrice" name="maxPrice" value="0" onblur="MaximumNValidate()" placeholder=" Maximum Price ">
						</div>
					</div>purchaseLocal-return.php
					<div class="form-group">
						<div class="col-sm-6">
							<label for="add_mfgDate"> Mfg. Date </label>
							<input type="date" style="line-height: 10px;" class="form-control" id="add_mfgDate" name="mfgDate" onblur="mfgValidate()">
						</div>
						<div class="col-sm-6">
							<label for="add_expDate"> Exp. Date </label>
							<input type="date" style="line-height: 10px;" class="form-control" id="add_expDate" name="expDate" onblur="expValidate()">

						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-6">
							<label for="WareHouse">Warehouse</label>
							<select class="form-control" name="wereHouse" id="add_wereHouse" style="width:100%;" required onchange="showSerializTable()">
								<option value="" selected>~~ Select Warehouse ~~</option>
								<?php
								$sql = "SELECT id,wareHouseName FROM `tbl_warehouse` WHERE status='Active' ORDER BY `id`  DESC";
								$query = $conn->query($sql);
								while ($prow = $query->fetch_assoc()) {
									echo "
							  <option value='" . $prow['id'] . "'>" . $prow['wareHouseName'] . "</option>
							";
								}
								?>
							</select>
							<input type="hidden" id="add_sessionId" name="add_sessionId" value="<?php echo $sessionId; ?>" />
						</div>

						<div class="col-sm-6">
							<label for="WareHouse">Lot No</label>
							<input type="text" style="line-height: 10px;" class="form-control" id="add_lotNo" name="lotNo" placeholder="Lot Number">
						</div>

					</div>
					<div class="form-group">
						<div class="col-sm-12">
							<table border="1" class="table">
								<thead>
									<tr>
										<th>SL</th>
										<th>Number Of Pics</th>
										<th>Serial Number</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody id="serializeProductTable" class="text-center">
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
					<button type="button" class="btn btn-success " onclick="addRow();"> <span class="glyphicon glyphicon-plus" style="font-size: 18px; font-weight:800;"></span>
						Add Row </button>
					<button type="submit" class="btn btn-primary btn-flat" name="addpurchaseProducts" id="btn_savePurchaseProducts"><i class="fa fa-save"></i> Save </button>

				</div>
			</form>
		</div>
	</div>
</div>