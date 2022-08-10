<!-- Add New Customer/Supplier-->
<div class="modal fade" id="advanceProductSearch">
    <div class="modal-dialog" style="width: 80%;">
        <div class="modal-content">
          	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              		<span aria-hidden="true">&times;</span></button>
            	<h4 class="modal-title"><b>Stock Product Advance Search</span> </b></h4>
          	</div>
          	<div class="modal-body form-horizontal">
				<input type="hidden" id="hitPageName" name="hitPageName">
				<div id='divASMsg' class='alert alert-success alert-dismissible successMessage'></div>
				<div class="form-group">
                  	<div class="col-sm-12">
                  	    <table id="searchProductsTable" class="table table-bordered" style="table-layout: fixed; width:100%">
                        <thead>
                            <th class="col-md-2">Product</th>
                            <th class="col-md-2">Category</th> 
                            <th class="col-md-2">Specification </th>
                            <th class="col-md-2">Price</th>
                            <th class="col-md-3">Stock Information</th>
                            <th class="col-md-1">Action</th>
                        </thead>
                        <tbody style="font-size: 12px;"></tbody>
                        </table>
                  	</div>
				</div>
			</div>
        </div>
    </div>
</div>
<!--script src="https://code.jquery.com/jquery-1.12.4.js"></script-->
<script>
    var clickCount = 0;
    function advanceSearch(pageName){
        var loadAdvanceSearch = 1;
        if(pageName == "partySale")
        {
            if($("#customers").val() == ""){
                loadAdvanceSearch = 0;
                $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> select customer first then change credit information");
        		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
        		  $(this).hide(); n();
        		});
            }else{
                $("#divErrorMsg").hide();
                var loadAdvanceSearch = 1;
            }
        }
        if(loadAdvanceSearch == 1){
            $("#hitPageName").val(pageName);
            $("#advanceProductSearch").modal('show');
            	// manage Shop table
        	if(clickCount == 0){
            	$("#searchProductsTable").DataTable({
            		'ajax': 'phpScripts/productAdvanceSearchActionMenue.php',
            		destroy: true,
            		language: {
                     processing: "<i class='fa fa-spinner fa-spin' style='font-size:50px;color:green'></i>"
                  },
                  processing: true
            	});
        	    clickCount = 1;
        	}
        }
    }
    
    function ViewProductStock(productId){
        $.ajax({
    			url:"phpScripts/productAdvanceSearchActionMenue.php",
    			method:"POST",
    			data:{id:productId},
    			dataType: 'json',
    			success:function(data)
    			{
    			    $("#stock_"+productId).html(data);
    			},
    			error: function (xhr) {
    				alert(xhr.responseText);
    			}
    		});
    }
    
    
    
</script>