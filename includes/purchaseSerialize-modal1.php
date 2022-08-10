 <!-- Start Serialize Product Modal -->
    <div class="modal fade" id="serialNumsModal">
        <div class="modal-dialog modal-dialog-scrollable" style="max-width: 30%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Serialize Product</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                            class="fas fa-window-close"></i></button>
                </div>
                <div class="modal-body card-body">
                    <form id="serializeProductForm">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <table border="1">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Number Of Pics</th>
                                            <th>Serial Number</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <input type="hidden" id="serializProductId" name="serializProductId"
                                        value="">
                                    <input type="hidden" id="serializProductWarehouseId"
                                        name="serializProductWarehouseId" value="">
                                    <tbody id="serializeProductTable" class="text-center">
                                    </tbody>
                                </table>
                                <strong>Total Purchase Quantity: <span name="totalStockQuantity"
                                        id="totalStockQuantity"></span></strong><br><span class="text-danger">** Purchase
                                    Qty & Total Qty Must Be Same</span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary mr-auto" data-dismiss="modal">x
                                Close</button>
                            <button type="button" class="btn btn-success " onclick="addRow();"> <span
                                    class="glyphicon glyphicon-plus"
                                    style="font-size: 18px; font-weight:800;"><strong>+</strong></span>
                                Add Row </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End Serialize Product Modal -->