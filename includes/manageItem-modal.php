<!-- Add New Customer/Supplier-->
<div class="modal fade" id="addnew">
    <div class="modal-dialog" style="width: 80%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><b>Add New Product</b></h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="form_addProduct" method="POST" action="#" enctype="multipart/form-data">
                    <div class="form-group">
                        <!--div class="col-sm-3">
                            <label for="WareHouse">Warehouse</label> 
                            <select type="hidden" class="form-control" name="add_wereHouse" id="add_wereHouse" style="width:100%;" >
                                <option value="" selected>~~ Select Warehouse ~~</option>
                                <?php
                               /* $sql = "SELECT id,wareHouseName FROM `tbl_warehouse` WHERE status='Active' ORDER BY `id`  DESC";
                                $query = $conn->query($sql);
                                while ($prow = $query->fetch_assoc()) {
                                    echo "<option value='" . $prow['id'] . "'>" . $prow['wareHouseName'] . "</option>";
                                }*/
                                ?>
                            </select>
                        </div-->
                        <div class="col-sm-3">
                            <label for="ItemName">Product Name</label> 
                            <input type="text" class="form-control" id="add_ProductName" name="ItemName" placeholder=" Product same ">
                        </div>
                        <div class="col-sm-3">
                            <label for="productCode">Product Code</label> 
                            <input type="text" class="form-control" id="add_productCode" autocomplete="off" name="productCode" placeholder=" Product Code " />
                        </div>
						<div class="col-sm-3">
						    <label for="modelNo">Model No</label>  
                            <input type="text" class="form-control" id="add_modelNumber" name="modelNumber" placeholder=" Model Number ">
						</div>
						<div class="col-sm-3">
                            <label for="brands">Brands</label> 
                            <select class="form-control" name="add_Brand" id="add_Brand" >
                                <option value="" selected>~~ Select Brands ~~</option>
                                <?php
                                $sql = "SELECT id,brandName FROM `tbl_brands` WHERE status='Active' ORDER BY `id`  DESC";
                                $query = $conn->query($sql);
                                while ($prow = $query->fetch_assoc()) {
                                    echo "
									  <option value='" . $prow['id'] . "'>" . $prow['brandName'] . "</option>
									";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
						
						<div class="col-sm-3">
                            <label for="categories">Categories</label> 
                            <select class="form-control" name="categories" id="add_category">
                                <option value="" selected>~~ Select Category ~~</option>
                                <?php
                                $sql = "SELECT id,categoryName FROM `tbl_category` WHERE status='Active' ORDER BY `id`  DESC";
                                $query = $conn->query($sql);
                                while ($prow = $query->fetch_assoc()) {
                                    echo "
									  <option value='" . $prow['id'] . "'>" . $prow['categoryName'] . "</option>
									";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label for="units">Units</label> 
                            <select class="form-control" name="units" id="add_units">
                                <option value="" selected>~~ Select Unit ~~</option>
                                <?php
                                $sql = "SELECT id,unitName FROM `tbl_units` WHERE status='Active' ORDER BY `id`  DESC";
                                $query = $conn->query($sql);
                                while ($prow = $query->fetch_assoc()) {
                                    echo "
									  <option value='" . $prow['id'] . "'>" . $prow['unitName'] . "</option>
									";
                                }
                                ?>
                            </select>
                        </div>
						<div class="col-sm-3">
                            <label for="Min Quantity">Minimum Quantity</label> 
                            <input type="text" class="form-control" id="add_lowQuantity" value='0' autocomplete="off" name="lowQuantity" placeholder=" Enter Product Minimum Quantity ">
                        </div>
                        	<div class="col-sm-3">
                            <label for="itemNote">Note</label>  
                            <input type="text" class="form-control" id="add_itemNote" name="itemNote" placeholder=" Note about Product ">
                        </div>
						
                    </div>
                    <div class="form-group">
                        <div class="col-sm-3">
                            <label for="minPrice">Minimum Price</label> 
                            <input type="text" class="form-control" id="add_minSalePrice" name="minPrice" onblur="MinimumNValidate()" placeholder=" Minimum Price ">
                        </div>
                        <div class="col-sm-3">
                            <label for="productCode">Maximum Price</label> 
                            <input type="text" class="form-control" id="add_maxSalePrice" autocomplete="off" onblur="MaximumNValidate()" name="maxPrice" placeholder=" Maximum Price" />
                        </div>
                        <div class="col-sm-3">
                            <label for="purchasePrice">Purchase Price</label> 
                            <input type="text" class="form-control" id="add_purchasePrice" autocomplete="off" onblur="MaximumNValidate()" name="purchasePrice" placeholder=" Purchase Price" />
                        </div>
                        <div class="col-md-3">
                            <label> Type <span class="text-danger"> * </span></label>
                            <select id="product_type" name="product_type" class="form-control input-sm"
                                onchange="checkType()">
                                <option value="regular"> Regular </option>
                                <option value="serialize"> Serialize </option>
                                <option value="service"> Service </option>
                            </select>
                            <span class="text-danger" id="typeError"></span>
                        </div>
                        
                    </div>
                    <div class="form-group">
                    <div class="col-md-3">
                            <label> Stock Check <span class="text-danger"> * </span></label>
                            <select id="stockCheck" name="stockCheck" class="form-control input-sm">
                                <option value="No"> No </option>
                                <option value="Yes"> Yes</option>
                            </select>
                            <span class="text-danger" id="stockCheckError"></span>
                        </div>
                        <div class="col-md-3">
                            <label>Items In Box: <span class="text-danger"></span></label>
                            <input class="form-control input-sm serialize" id="itemsInBox" type="number"
                                min="0" name="itemsInBox" placeholder=" Number " onchange="checkType()"
                                disabled>
                            <span class="text-danger" id="itemsInBoxError"></span>
                        </div>
                        <div class="col-md-3" id="showBtn" style="display:none;">
                            <label style="color: white;">.</label>
                            <button type="button" class="btn btn-success form-control " onclick="checkType()"><i
                                    class="fa fa-table"></i>
                                Show Serialize Table</button>
                        </div>
                        <div class="col-sm-3">
                            <label for="productCode">Packing Qty</label> 
                            <input type="text" class="form-control" id="add_cartonUnit" autocomplete="off" onblur="add_cartonName()" name="cartonUnit" placeholder=" Units per Qty " />
                        </div>
                        <div class="col-sm-3">
                            <label for="productCode">Package Type</label> 
                            <select class="form-control" name="add_carton_type" id="add_carton_type" onchange="add_cartonName()">
                                <option value="Unit">Single</option>
                                <option value="Carton">Multiple</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-4">
                            <label for="minPrice">Package Name</label> 
                            <input type="text" class="form-control" id="add_package_unit" name="package_unit" placeholder=" Package Name Carton, Sacks, Bundle etc... ">
                        </div>
                        <div class="col-sm-4">
                            <label for="minPrice">Item Description</label> 
                            <span id="add_carton_name" name="carton_name"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-3">
                            <label for="ItemImage">Product Image</label>  
                            <input type="file" class="form-control" id="add_itemImage" name="ItemImage" onchange="loadFile(event);" accept=".png, .jpg, .jpeg" >
							<img  src="images/broken_image.png" style="width: 60%;height: 110px;border-radius: 10%;margin-top: 8%;" id="output"/>
						</div>
						<div class="col-sm-6">
                            <label for="itemNote">Product Specification</label> 
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dynamic_field">  
                                    <tr>  
                                        <td class="col-sm-6"><input type="text" class="form-control" name="spacName[]" placeholder="Name"></td>  
                                        <td class="col-sm-6"><input type="text" class="form-control" name="spacValue[]" placeholder="value" ></td>
                                        <td><button type="button" name="add" id="add" class="btn btn-success btn-sm add"><span class="glyphicon glyphicon-plus"></span></button></td>
                                    </tr>  
                                </table>  
                            </div>
                        </div>
					</div>
                    
           
				<div class="modal-footer">
					<button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
					<button type="submit" class="btn btn-success btn-flat" name="addItem" id="btn_saveItem"><i class="fa fa-save"></i> Save </button>
				</form>
				</div>
			</div>
        </div>
    </div>
</div>
<!-- Add and Edit -->
<div class="modal fade" id="editItem">
    <div class="modal-dialog" style="width: 80%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><b>Edit added Product </b></h4>
            </div>
            <div class="modal-body">
                <div id="editLoader" style="display:none; text-align:center;" class="col-md-12"><i class='fa fa-spinner fa-spin' style='font-size:50px;color:green'></i></div>
                <form class="form-horizontal" id="form_updateProduct" method="POST" action="#" enctype="multipart/form-data">
                    <input type="hidden" id="editId" name="id" >
					<div class="form-group">
                        <!--div class="col-sm-3">
                            <label for="WareHouse">Warehouse</label> 
                            <select class="form-control" name="edittbl_wareHouseID" id="edittbl_wareHouseID" style="width:100%;" required>
                                <?php
                                /*$sql = "SELECT id,wareHouseName FROM `tbl_warehouse` WHERE status='Active' ORDER BY `id`  DESC";
                                $query = $conn->query($sql);
                                while ($prow = $query->fetch_assoc()) {
                                    echo "<option value='" . $prow['id'] . "'>" . $prow['wareHouseName'] . "</option>";
                                }*/
                                ?>
                            </select>
                        </div
                        <div class="col-sm-3">
                            <label for="ItemName">Open Stock</label> 
                            <input type="text" class="form-control" id="editopenStock" name="editopenStock" placeholder=" edit openStock ">
                        </div>-->
                        <div class="col-sm-3">
                            <label for="ItemName">Product Name</label> 
                            <input type="text" class="form-control" id="editproductName" name="ItemName" placeholder=" Product same ">
                        </div>
                        <div class="col-sm-3">
                            <label for="productCode">Product Code</label> 
                            <input type="text" class="form-control" id="editproductCode" name="productCode" placeholder=" Product sode ">
                        </div>
						<div class="col-sm-3">
							<label for="modelNo">Model No</label>  
							<input type="text" class="form-control" id="edit_modelNumber" name="modelNumber" placeholder=" Model Number ">
						</div>
						<div class="col-sm-3">
                            <label for="brands">Brands</label> 
                            <select class="form-control" name="add_Brand" id="edittbl_brandsId">
                                 <?php
                                $sql = "SELECT id,brandName FROM `tbl_brands` WHERE status='Active' ORDER BY `id`  DESC";
                                $query = $conn->query($sql);
                                while ($prow = $query->fetch_assoc()) {
                                    echo "
									  <option value='" . $prow['id'] . "'>" . $prow['brandName'] . "</option>
									";
                                }
                                ?>
                            </select>
                        </div>
					</div>
                    <div class="form-group">
						
						<div class="col-sm-3">
                            <label for="categories">Categories</label> 
                            <select class="form-control" name="categories" id="editcategoryId">
                                <?php
                                $sql = "SELECT id,categoryName FROM `tbl_category` WHERE status='Active' ORDER BY `id`  DESC";
                                $query = $conn->query($sql);
                                while ($prow = $query->fetch_assoc()) {
                                    echo "
									  <option value='" . $prow['id'] . "'>" . $prow['categoryName'] . "</option>
									";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label for="units">Units</label> 
                            <select class="form-control" name="units" id="editunits">
                                <?php
                                $sql = "SELECT id,unitName FROM `tbl_units` WHERE status='Active' ORDER BY `id`  DESC";
                                $query = $conn->query($sql);
                                while ($prow = $query->fetch_assoc()) {
                                    echo "
									  <option value='" . $prow['id'] . "'>" . $prow['unitName'] . "</option>
									";
                                }
                                ?>
                            </select>
                        </div>
						<div class="col-sm-3">
                            <label for="Min Quantity">Minimum Quantity</label> 
                            <input type="text" class="form-control" id="edit_lowQuantity" name="lowQuantity" placeholder=" Enter Product Minimum Quantity ">
                        </div>
                        <div class="col-sm-3">
                             <label for="itemNote">Note</label>  
                            <input type="text" class="form-control" id="editproductDescriptions" name="itemNote" placeholder=" Note about Product ">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-3">
                            <label for="minPrice">Minimum Price</label> 
                            <input type="text" class="form-control" id="editminimumSalePrice" name="minPrice" onblur="MinimumNValidate()" placeholder=" Minimum Price ">
                        </div>
                        <div class="col-sm-3">
                            <label for="productCode">Maximum Price</label> 
                            <input type="text" class="form-control" id="editmaxSalePrice" autocomplete="off" onblur="MaximumNValidate()" name="maxPrice" placeholder=" Maximum Price" />
                        </div>
                        <div class="col-sm-3">
                            <label for="productCode">Purchase Price</label> 
                            <input type="text" class="form-control" id="editpurchasePrice" autocomplete="off" onblur="MaximumNValidate()" name="purchasePrice" placeholder=" PurchasePrice Price" />
                        </div>
                        <div class="col-sm-3">
                            <label for="productCode">Packing Qty</label> 
                            <input type="text" class="form-control" id="editcartonUnit" autocomplete="off" onblur="edit_cartonName()" name="cartonUnit" placeholder=" Units per qty " />
                        </div>
                        <div class="col-sm-3">
                            <label for="productCode">Package Type</label> 
                            <select class="form-control" name="edit_carton_type" id="edit_carton_type" onchange="edit_cartonName()">
                                <option value="Unit">Single</option>
                                <option value="Carton">Multiple</option>
                            </select>
                        </div>
                        
                    </div>
                    <div class="form-group">
                        <div class="col-sm-4">
                            <label for="minPrice">Package Name</label> 
                            <input type="text" class="form-control" id="editpackage_unit" name="package_unit" placeholder=" Package Name Carton, Sacks, Bundle etc... ">
                        </div>
                        <div class="col-sm-4">
                            <label for="minPrice">Item Description</label> 
                            <span id="editcartonName" name="editcartonName"></span>
                        </div>
                    </div>
                   <div class="form-group">
						<div class="col-sm-3">
                            <label for="ItemImage">Product Image</label>  
                            <input type="file" class="form-control" id="editproductImage"  name="ItemImage" onchange="loadFile1(event)"  accept=".png, .jpg, .jpeg">
							<img style="width: 60%;height: 110px;border-radius: 10%;margin-top: 8%;" id="editViewImage"></img>
						</div>
						<div class="col-sm-3">
						<label for="modelNo">Status</label>  
                        <select id="edit_status" name="status"  class="form-control">
							<option value="Active">Active</option>
							<option value="Inactive">Inactive</option>
						</select>
						</div>
                        <div class="col-sm-6">
                            <label for="itemNote">Product Specification    <button style="margin-left: 10px;" type='button' name='editSpac' id='editSpac' class='btn btn-success btn-sm add'>   <span class='glyphicon glyphicon-plus'></span></button></label> 
                            <div class="table-responsive">
                                <table class="table table-bordered" id="itemSpecifications"></table>  
                            </div>
                        </div>
                    </div>
					
				<div class="modal-footer">
                <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                <button type="submit" class="btn btn-success btn-flat" name="updateItem" id="btn_updateItem"><i class="fa fa-save"></i> Save </button>
                </form>
				</div>
			</div>
        </div>
    </div>
</div>

<!-- discount offer -->
<div class="modal fade" id="discountOfferModal">
    <div class="modal-dialog">
        <div class="modal-content">
          	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              		<span aria-hidden="true">&times;</span></button>
            	<h4 class="modal-title"><b>Offer Details</span> </b></h4>
          	</div>
          	<div class="modal-body form-horizontal">
				<input type="hidden" id="hitPageNam12e" name="hitPageName">
				<div id='divASMsg' class='alert alert-success alert-dismissible' style='text-align:right; margin-left:70%; display:none;'></div>
				<div class="form-group">
                  	<div class="col-sm-12">
                  	    <table id="discountOfferDetails" class="table table-bordered" style="table-layout: fixed; width:100%">
                        
                        </table>
                  	</div>
				</div>
			</div>
        </div>
    </div>
</div>

 <!-- Start Serialize Product Modal 
    <div class="modal fade" id="serializeProductModal">
        <div class="modal-dialog" style="max-width: 30%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Serialize Product</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                            class="fas fa-window-close"></i></button>
                </div>
                <div class="modal-body card-body">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <table border="1">
                                <thead>
                                    <tr>
                                        <th>Sr.</th>
                                        <th>Number Of Pics</th>
                                        <th>Serial Number</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="serializeProductTable" class="text-center"></tbody>
                            </table>
                            Total Quantity: <span name="totalStockQuantity" id="totalStockQuantity"></span><br><span
                                class="text-danger">** Openning Stock and Total Quantity Must Be Same</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary mr-auto" data-dismiss="modal">x Close</button>
                        <button type="button" class="btn btn-success " onclick="addRow();"> <span
                                class="glyphicon glyphicon-plus"
                                style="font-size: 18px; font-weight:800;"><strong>+</strong></span>
                            Add Row </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Serialize Product Modal -->
    
     <!-- edit open stock modal -->
    <div class="modal fade" id="editOpenStockModal">
        <div class="modal-dialog" style="max-width: 50%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Update Open Stock Prodcuts</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                            class="fas fa-window-close"></i></button>
                </div>
                <div class="modal-body">
                    <form id="editOpenStockProductForm" method="POST" enctype="multipart/form-data" action="#">
                        <div class="row">

                            <div class="form-group col-md-12">
                                <input type="hidden" name="editOpenStockId" id="editOpenStockId">
                                <label> Product Name <span class="text-danger"> * </span></label>
                                <input class="form-control input-sm" id="editOpenStockName" type="text"
                                    name="editOpenStockName" disabled>
                                <span class="text-danger" id="editOpenStockNameError"></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label> Product Type </label>
                                <input class="form-control input-sm" id="editOSProductType" type="text"
                                    name="editOSProductType" disabled>
                                <span class="text-danger" id="editOSProductTypeError"></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label> Items per box </label>
                                <input class="form-control input-sm" id="editOSItemsInBox" type="text"
                                    name="editOSItemsInBox" disabled>
                                <span class="text-danger" id="editOSItemsInBoxError"></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label> Opening Stock </label>
                                <input class="form-control input-sm" id="editOpenStockInsert" type="number"
                                    name="editOpenStockInsert" onblur="calculate_openingStock()">
                                <span class="text-danger" id="editOpenStockError"></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label> Select Warehouse <span class="text-danger"> * </span></label>
                                <select class="form-control input-sm" id="edit_open_stock_warehouse"
                                    name="edit_open_stock_warehouse">
                                    <option value="" selected>Select Warehouse</option>
                                    <?php
                                    $sql = "SELECT id,wareHouseName FROM `tbl_warehouse` WHERE status='Active' ORDER BY `id`  DESC";
                                    $query = $conn->query($sql);
                                    while ($prow = $query->fetch_assoc()) {
                                        echo "<option value='" . $prow['id'] . "'>" . $prow['wareHouseName'] . "</option>";
                                    }
                                    ?>
                                </select>
                                <span class="text-danger" id="edit_open_stock_warehouseError"></span>
                            </div>
                            <div class="form-group col-md-12">
                            <table  id="OSserialize_data" class="col-md-12" style="display:none;">
                                <thead>
                                    <tr>
                                        <th>Sr.</th>
                                        <th>Number Of Pics</th>
                                        <th>Serial Number</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="OSserialize_data_table" class="text-center"></tbody>
                            </table>
                            Total Quantity: <span name="totalStockQuantity" id="totalStockQuantity"></span><br><span
                                class="text-danger">** Openning Stock and Total Quantity Must Be Same</span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary mr-auto" data-dismiss="modal">x
                                Close</button>
                            <button type="button" class="btn btn-success " onclick="addRow();"> <span
                                class="glyphicon glyphicon-plus"
                                style="font-size: 18px; font-weight:800;"></span>
                            Add Row </button>
                            <button type="submit" class="btn btn-primary " id="saveStock"><i class="fa fa-save"></i>
                                Update Opening Stock</button>
                        </div>
                    </form>
                    
                    <table>
                        <thead>
                            <tr>
                                <th>Warehouse Name</th>
                                <th>Opening Stock</th>
                                <th>Current Stock</th>
                            </tr>
                        </thead>
                        <tbody id="initialStockData"></tbody>
                    </table>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

