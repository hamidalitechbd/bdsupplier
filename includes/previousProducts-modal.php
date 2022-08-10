<!-- previousSoldProductsView -->
<div class="modal fade" id="previousSoldProductsView">
    <div class="modal-dialog" style="width: 60%;">
        <div class="modal-content">
          	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              		<span aria-hidden="true">&times;</span></button>
            	<h4 class="modal-title"><b>Previous Products History</span> </b></h4>
          	</div>
          	<div class="modal-body form-horizontal">
				<input type="hidden" id="hitPageName" name="hitPageName">
				<div id='divASMsg' class='alert alert-success alert-dismissible' style='text-align:right; margin-left:70%; display:none;'></div>
				<div class="form-group">
                  	<div class="col-sm-12">
                  	    <table id="previousProducts" class="table table-bordered" style="table-layout: fixed; width:100%">
                        
                        </table>
                  	</div>
				</div>
			</div>
        </div>
    </div>
</div>

<!--WI previousSoldProductsView -->
<div class="modal fade" id="previousSoldWiProductsView">
    <div class="modal-dialog" style="width: 60%;">
        <div class="modal-content">
          	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              		<span aria-hidden="true">&times;</span></button>
            	<h4 class="modal-title"><b>Previous Products History</span> </b></h4>
          	</div>
          	<div class="modal-body form-horizontal">
				<input type="hidden" id="hitPageName" name="hitPageName">
				<div id='divASMsg' class='alert alert-success alert-dismissible' style='text-align:right; margin-left:70%; display:none;'></div>
				<div class="form-group">
                  	<div class="col-sm-12">
                  	    <table id="previousWiProducts" class="table table-bordered" style="table-layout: fixed; width:100%">
                        
                        </table>
                  	</div>
				</div>
			</div>
        </div>
    </div>
</div>
<!-- product Specification -->
<div class="modal fade" id="productSpecificationView">
    <div class="modal-dialog" style="width: 50%;">
        <div class="modal-content">
          	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              		<span aria-hidden="true">&times;</span></button>
            	<h4 class="modal-title"><b>Product Details</span> </b></h4>
          	</div>
          	<div class="modal-body form-horizontal">
				<input type="hidden" id="hitPageName" name="hitPageName">
				<div id='divASMsg' class='alert alert-success alert-dismissible' style='text-align:right; margin-left:70%; display:none;'></div>
				<div class="form-group">
                  	<div class="col-sm-12">
                  	    <table id="productsSpec" class="table table-bordered" style="table-layout: fixed; width:100%">
                        
                        </table>
                  	</div>
				</div>
			</div>
        </div>
    </div>
</div>


<!-- discount offer -->
<div class="modal fade" id="discountOfferModal">
    <div class="modal-dialog" style="width: 60%;">
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


<!-- discount offer Details-->
<div class="modal fade" id="discountOfferModalDetails">
    <div class="modal-dialog" style="width: 60%;">
        <div class="modal-content">
          	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              		<span aria-hidden="true">&times;</span></button>
            	<h4 class="modal-title"><b>Oreder Preview</span> </b></h4>
          	</div>
          	<div class="modal-body form-horizontal">
				<input type="hidden" id="hitPageNam12e" name="hitPageName">
				<div id='divASMsg' class='alert alert-success alert-dismissible' style='text-align:right; margin-left:70%; display:none;'></div>
				<div class="form-group">
                  	<div class="col-sm-12">
                  	    <table id="discountOfferDetailsPreview" class="table table-bordered" style="table-layout: fixed; width:100%">
                        
                        </table>
                  	</div>
				</div>
			</div>
        </div>
    </div>
</div>

<!-- discount offer Details-->
<div class="modal fade" id="salesconfirmation">
    <div class="modal-dialog">
        <div class="modal-content">
          	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              		<span aria-hidden="true">&times;</span></button>
            	<h4 class="modal-title"><b>Product <img src="images/discount.png"></b><b style="margin: 0% 0% 0% 25%;color: green;">Sure ! You Want To Do This Order ?</b></h4>
          	</div>
          	<div class="modal-body form-horizontal">
          	    <form class="form-horizontal" id="form_addsales" method="POST" action="#">
    				<input type="hidden" id="sales" name="sales" />
    				<input type="hidden" id="salesProduct" name="salesProduct" />
    				<input type="hidden" id="vouchers" name="vouchers" />
    				<input type="hidden" id="warehouseData" name="warehouseData" />
    				<div id='divASMsg' class='alert alert-success alert-dismissible' style='text-align:right; margin-left:70%; display:none;'></div>
    				<div class="col-sm-12"><span id="offerProductWarehouse"></span></div>
    				<a type="submit"  href="#" class="btn btn-default" id="finalSaleConfirm" style="box-shadow: 1px 1px 1px 0px #ff0202;width: 100%;background-color: aliceblue;">
					<span class="glyphicon glyphicon-shopping-cart" style="color: #000cbd;"></span> Place Order
					</a>
				</form>
			</div>
        </div>
    </div>
</div>
