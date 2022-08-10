<!-- Add New Customer/Supplier-->
<div class="modal fade" id="advanceProductSearch">
    <div class="modal-dialog" style="width: 80%;">
        <div class="modal-content">
          	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              		<span aria-hidden="true">&times;</span></button>
            	<div class="col-sm-3">	
            	    <h4 class="modal-title"><b>Product Advance Search</span> </b></h4>
            	</div>
            	<div style="display:none;">
			            <select id="asWarehouse" class="form-control">
			                <?php
			                $sql = "SELECT id,wareHouseName FROM `tbl_warehouse` WHERE deleted='No'";
			                 $result = $conn->query($sql);
                            while($row = $result->fetch_assoc()){
                                echo '<option value="'.$row["id"].'">'.$row["wareHouseName"].'</option>';
                            }
			                ?>
			            </select>
			    </div>
			    <div class="col-sm-8">
			        <div id='divASMsg' class='alert alert-success alert-dismissible successMessage'></div>
				    <div id='divASErrorMsg' class='alert alert-danger alert-dismissible errorMessage'></div>
				</div>
          	</div>
          	<div class="modal-body form-horizontal">
				<input type="hidden" id="hitPageName" name="hitPageName">
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
<script>
    var clickCount = 0;
    $("#asWarehouse").change(function (){
        $("#wareHouse").val($("#asWarehouse").val()).trigger('change');
    })
    function advanceSearch(pageName){
        $("#asWarehouse").val($("#wareHouse").val());
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
            		'ajax': 'phpScripts/productAdvanceSearchAction.php?page='+pageName,
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
    function selectProducts(productId, warehouse_id){
        if($("#hitPageName").val() == "purchase" || $("#hitPageName").val() == "import"){
            $("#add_products").val(productId).trigger('change');
            $("#advanceProductSearch").modal('hide');
        }else if($("#hitPageName").val() == "wareHouseTransfer"){
            $("#products").val(productId).trigger('change');
            $("#advanceProductSearch").modal('hide');
        }else if($("#hitPageName").val() == "walkinSales" || $("#hitPageName").val() == "partySale" || $("#hitPageName").val() == "temporarySale"){
            if(warehouse_id == "" || warehouse_id == undefined){
                warehouse_id = $("#wareHouse").val();    
            }else{
                $("#wareHouse").val(warehouse_id).trigger('change');    
                $("#asWarehouse").val(warehouse_id).trigger('change');    
            }
            
            var warehouse_name = $("#wareHouse option:selected").text();
            $.ajax({
    			url:"phpScripts/manageItem-row.php",
    			method:"POST",
    			data:{id:productId},
    			dataType: 'json',
    			success:function(data)
    			{
    			    if($("#hitPageName").val() == "partySale"){
    				   add_to_cart(productId, data[0].productName+' - '+data[0].modelNo + ' - '+data[0].brandName, data[0].maxSalePrice,data[0].minSalePrice,data[0].maxSalePrice, 1, warehouse_id, warehouse_name);
    			    }else if($("#hitPageName").val() == "temporarySale"){
    			       add_to_cart(productId, data[0].productName+' - '+data[0].modelNo + ' - '+data[0].brandName, data[0].maxSalePrice,data[0].minSalePrice,data[0].maxSalePrice, 1, warehouse_id, warehouse_name);
    			    }
    			    else if($("#hitPageName").val() == "walkinSales"){
    			        add_to_cart(productId, data[0].productName+' - '+data[0].modelNo + ' - '+data[0].brandName, data[0].maxSalePrice,data[0].minSalePrice,data[0].maxSalePrice, 1, warehouse_id, warehouse_name);
    			    }
    			},
    			error: function (xhr) {
    				alert(xhr.responseText);
    			}
    		});
        }else if($("#hitPageName").val() == "damageProducts"){
            $("#damageProducts").val(productId).trigger('change');
            $("#advanceProductSearch").modal('hide');
        }else if ($("#hitPageName").val() == "discountOffer"){
            $("#add_products").val(productId).trigger('change');
            $("#advanceProductSearch").modal('hide');
        }
    }
    function ViewProductStock(productId){
        $.ajax({
    			url:"phpScripts/productAdvanceSearchAction.php",
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