<!-- Start Serialize Product Modal -->
<div class="modal fade" id="serializeProductReturnModal">
    <div class="modal-dialog modal-dialog-scrollable" style="max-width: 30%;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Serialize Product<button type="button" class="btn btn-default btn-flat pull-right" data-dismiss="modal"><i class="fa fa-close"></i></button></h4>
                <strong>Remaining Quantity For Sale And Return: <span id="totalRemainingQuantity" style="font-size: 30px;"></span></strong>
                <div id='divErrorMsgSerialize' class='alert alert-danger alert-dismissible errorMessage float-right'></div>
            </div>
            <div class="modal-body card-body">
                <form id="serializeProductForm">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <table border="1" style="font-size: 13px; width:100%;">
                            <input type="hidden" id="tbl_tSalesId" name="tbl_tSalesId" value="0">
                                <input type="hidden" id="tbl_product_id" name="tbl_product_id" value="">
                                <input type="hidden" id="serializProductWarehouseId" name="serializProductWarehouseId" value="">
                                <thead>
                                    <tr>
                                        <th>SL.</th>
                                        <th>Remaining Qty</th>
                                        <th style="width: 25%">Return Qty</th>
                                    </tr>
                                </thead>
                                <tbody id="serializeProductReturnTable" class="text-center">
                                </tbody>
                                <tbody id="serializeProductReturnTableNew" class="text-center bg-info mt-3">
                                </tbody>
                            </table>
                            <strong>Total Return Quantity: <span name="totalStockQuantity" id="totalStockQuantity" style="font-size: 30px;"></span></strong><br><span class="text-danger">** Please Confirm To Store  Popup Data</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <!-- <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button> -->
                        <button type="button" class="btn btn-success pull-left" onclick="addRow();"> <span class="glyphicon glyphicon-plus" style="font-size: 18px; font-weight:800;"></span>Add Row </button>
                        <button type="button" class="btn btn-primary btn-flat " name="btnConfirmSerialzeProduct" id="btn_confirmSerialzeProduct" onclick="confirmSerialzeProduct()"><i class="fa fa-save"></i> Confirm </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Serialize Product Modal -->