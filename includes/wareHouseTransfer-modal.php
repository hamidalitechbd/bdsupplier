<!-- WareHouse Transfer-->
<div class="modal fade" id="warehouseTransfer">
    <div class="modal-dialog" style="width: 60%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><b> WareHouse Transfer </b></h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="form_transferWarehouse" method="POST" action="#" enctype="multipart/form-data">
                    <fieldset class="scheduler-border">
					<legend class="scheduler-border">WareHouse From</legend>
					<div class="form-group">
					    <div class="col-sm-4">
					        <label for="transferDate">Transfer Date</label>
					        <input type="date" id="transferDate" name="transferDate" style="padding: inherit;"  class="form-control" />
					    </div>
                        <div class="col-sm-8">
                            <label for="ItemName">Select Product</label> 
                            <div class="input-group">
                            <select class="form-control" id="products" name="products">
                                <option value="" selected>~~ Select Product ~~</option>
                                <?php
                                $sql = "SELECT tbl_products.id,tbl_products.productName,tbl_products.productCode,tbl_brands.brandName
										FROM `tbl_products` 
										LEFT OUTER JOIN tbl_brands ON tbl_brands.id=tbl_products.tbl_brandsId
										WHERE tbl_products.status='Active' ORDER BY `id`  DESC";
                                $query = $conn->query($sql);
                                while ($prow = $query->fetch_assoc()) {
                                    echo "<option value='" . $prow['id'] . "'>" . $prow['productName'] . "-".$prow['productCode']." (".$prow['brandName'].") </option>";
                                }
                                ?>
                            </select>
                             <a href="#" onclick="advanceSearch('wareHouseTransfer')" class="input-group-addon" style="background-color:#171991;color:#fff;border-radius: 0px 8px 8px 0px;box-shadow: -5px 0px 0px 0px #171991;border: 1px solid #171991;"><i class="fa fa-search" style="font-size: 18px;"></i></a>
                            </div>
                        </div>
                        				
					</div>
					<div class="form-group">
					<div class="col-sm-6">
                            <label for="wareHouseID">Select Warehouse</label> 
                            <select class="form-control" name="wareHouseID" id="wareHouseID" style="width:100%;" required>
                                
                            </select>
                            <select class="form-control" name="wareHouseStock" id="wareHouseStock" style="display:none;">
                                
                            </select>
                        </div>
						<div class="col-sm-3">
						    <label for="currentStock">Current Stock</label>  
                            <input type="text" class="form-control" id="currentStock" name="currentStock" placeholder=" Current Stock " readonly>
						</div>
						<div class="col-sm-3">
						    <label for="remainingStock">Remaining Stock</label>  
                            <input type="text" class="form-control" id="remainingStock" name="remainingStock" placeholder=" Remaining Stock " readonly>
						</div>
					</div>
					</fieldset>
                    <fieldset class="scheduler-border">
					<legend class="scheduler-border">WareHouse To</legend>
					<div class="form-group">
                        <div class="col-sm-6">
                            <label for="transferWareHouse">Select Warehouse</label> 
                           <select class="form-control" name="transferWareHouse" id="transferWareHouse" style="width:100%;" required>
                               <option value="" selected>~~ Select Warehouse ~~</option>
                                <?php
                                $sql = "SELECT id,wareHouseName FROM tbl_warehouse WHERE status='Active' ORDER BY id  DESC";
                                $query = $conn->query($sql);
                                while ($prow = $query->fetch_assoc()) {
                                    echo "<option value='" . $prow['id'] . "'>" . $prow['wareHouseName'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
						<div class="col-sm-6">
						    <label for="transferStock">Transfer Quantity</label>  
                            <input type="text" class="form-control" id="transferStock" name="transferStock" placeholder=" Transfer Quantity ">
						</div>
					</div>
					</fieldset>
					
           
				<div class="modal-footer">
					<button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
					<button type="submit" class="btn btn-primary btn-flat" name="addTransfer" id="btn_saveTransfer"><i class="fa fa-save"></i> Transfer WareHouse </button>
				</form>
				</div>
			</div>
        </div>
    </div>
</div>