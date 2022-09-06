var manageWareHouseTransferTable;
$(document).ready(function() {
	// manage Shop table
	manageWareHouseTransferTable = $("#manageWareHouseTransferTable").DataTable({
		'ajax': 'phpScripts/wareHouseTransferaction.php',
		'order': [],
		'dom': 'Bfrtip',
        'buttons': [
            'pageLength','copy', 'csv', 'pdf', 'print'
        ],
		language: {
            processing: "<img src='../images/loader.gif'>"
        },
        processing: true
	});
});
$("#transferDate").val(new Date().toISOString().substring(0, 10));
$("#products").select2({
	placeholder: "Select Item Name",
	allowClear: true
});

$("#wareHouseID").select2({
	placeholder: "Select Warehouse",
	allowClear: true
});
$("#transferWareHouse").select2({
	placeholder: "Select Warehouse",
	allowClear: true
});

//=========== For Serialize Product ===========//
var tbl_serialize_productsIdArray = [];
var stockQuantities = [];
var tempCurrentStock = 0;
var product_type = '';
//=========== End ===========//

$("#products").change(function(){
    var productsId = $(this).val();
    var action = "fetchWareHouse";
    $.ajax({
		url:"phpScripts/wareHouseTransferaction.php",
		method:"POST",
		data:{action:action, productsId:productsId},
		dataType:"json",
		success:function(data)
		{
		    $("#wareHouseID").empty().trigger('change');
		    $("#wareHouseStock").empty();
		    $("#wareHouseID").append("<option value=''>~~ Select Warehouse ~~</option>");
		    $("#wareHouseStock").append("<option value=''>~~ Select Warehouse Stock ~~</option>");
		    for(var i=0; i<data.length; i++){
			    $("#wareHouseID").append("<option value='"+data[i].id+"'>"+data[i].wareHouseName+"</option>");
			    $("#wareHouseStock").append("<option value='"+data[i].id+"'>"+data[i].currentStock+"</option>");
				// Serialize Product
				product_type = data[i].type;
				if ( product_type == "serialize") {
					$('#transferStock').attr('disabled', true);
					$("#ShowSerializeBtn").removeClass("hidden");
					$("#btn_close").removeClass("hidden");
					$("#btn_confirmSerialzeProduct").addClass("hidden");
					$("#btn_addRow").addClass("hidden");
				}else{
					$('#transferStock').attr('disabled', false);
					$("#ShowSerializeBtn").addClass("hidden");
					tbl_serialize_productsIdArray = [];
					stockQuantities = [];
				}
				// End
			}
		}
	});
});

$("#wareHouseID").change(function(){
    /* wareHouse = $.trim($("#wareHouseID option:selected").text());
    var res = wareHouse.split("-");
    $("#currentStock").val($.trim(res[res.length-1]));
    $("#remainingStock").val($.trim(res[res.length-1]));*/
    $("#wareHouseStock").val($("#wareHouseID").val());
    var str = $( "#wareHouseStock option:selected" ).text();
    //var res = str.split("__");
    if(str != "~~ Select Warehouse Stock ~~"){
        $("#currentStock").val(str);
        $("#remainingStock").val(str);
		if ( product_type == "serialize" && str > 0) {
			showSerializTable();
		}
    }else{
        $("#currentStock").val("0");
        $("#remainingStock").val("0");
    }
    $("#transferStock").val("0");
});

$("#transferStock").on("keyup", function (){
    var currentStock = $("#currentStock").val();
    var transferStock = $(this).val();
    if(parseFloat(transferStock) <= parseFloat(currentStock)){
        $("#remainingStock").val(currentStock-transferStock);
    }else{
        $(this).val(currentStock);
        $("#remainingStock").val(0);
        alert("Transfer quantity can not be greater then current stock");
    }
});

//=========== Start Serialize Product ===========//
function showSerializTable() {
	var product_id = $("#products").val();
	var warehouseId = $("#wareHouseID").val();
	tempCurrentStock = $("#currentStock").val();
	if (warehouseId == '' || product_id == '') {
		$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> please select warehouse & product");
		$("#divErrorMsg").show().delay(5000).fadeOut().queue(function(n) {
		  $(this).hide(); n();
		});
		return;
	}
	$(".qtyTxt").text(" Quantity");
	$(".txtView").text("Available Quantity: ");
	$(".txtTotalView").text("Total Quantity: ");
	$("#totalRemainingQuantity").text(tempCurrentStock);
	$("#tbl_product_id").val(product_id);
	$("#serializProductWarehouseId").val(warehouseId);
	let fd = new FormData();
	fd.append('product_id', product_id);
	fd.append('warehouseId', warehouseId);
	fd.append('tbl_tSalesId', 000);
	fd.append('action', "showSerializTable");
	$.ajax({
		url: "phpScripts/temporarySaleAdjustmentAction.php",
		method: "POST",
		data: fd,
		contentType: false,
		processData: false,
		dataType: "json",
		success: function (result) {
				let len = stockQuantities.length;
				let serializeProducts = result.serializeProducts;
				let rows = '';
				let key = 0;
				let totalTrnsfrQuantity = 0;
				let damageQuantity = 0;
				let trnsfrQuantity = 0;
				tbl_serialize_productsIdArray = [];
				serializeProducts.forEach(serializeProduct => {
					remainingQty = serializeProduct.quantity - serializeProduct.used_quantity;
					tbl_serialize_products_id = serializeProduct.id;
					tbl_serialize_productsIdArray[key] = serializeProduct.id;
					if (len > 0) {
						trnsfrQuantity = stockQuantities[key];
					}
					totalTrnsfrQuantity +=  parseInt((trnsfrQuantity));
					rows += '<tr><td>' + (key + 1) + '</td>' +
						'<td id="serializeRemainingQty_' + tbl_serialize_products_id + '">' + remainingQty + '</td>'+
						'<td><input class="form-control only-number input-sm stockQuantity' + key +
						'" id="stockQuantity_' + tbl_serialize_products_id + '" type="text" name="stockQuantity" placeholder=" ... " required oninput="calculateTotalQuantity(this.value,' + product_id + ',' + warehouseId + ',' + tbl_serialize_products_id + ','+"'addReturnQty'"+')" value="' +trnsfrQuantity + '"></td></tr>';
					key++;
				});
				$("#serializeProductReturnTable").html('');
				$("#serializeProductReturnTable").html(rows);
				$("#serializeProductReturnModal").modal("show");
				$("#totalStockQuantity").text(totalTrnsfrQuantity);
		},
		beforeSend: function () {
			$('#loading').show();
		},
		complete: function () {
			$('#loading').hide();
		},
		error: function (response) {
			alert("Something Went Wrong.Please Try Again");
		}
	});
}

function calculateTotalQuantity(saleQty, product_id, warehouse_id, tblSerializeId) {
	var serializeRemainingQty = parseFloat($("#serializeRemainingQty_" + tblSerializeId).text());
	if (saleQty > serializeRemainingQty) {
		$("#stockQuantity_" + tblSerializeId).val(0);
		$("#divErrorMsgSerialize").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Quantity Not Avialable!");
		$("#divErrorMsgSerialize").show().delay(3000).fadeOut().queue(function(n) {
		    $(this).hide(); n();
		});
	}
	var totalStockQuantity = 0;
	stockQuantities = $('input[name^=stockQuantity]').map(function(index, quantity) {
		return parseInt($(quantity).val()) ? parseInt($(quantity).val()) : 0 ;
	}).get();
	totalStockQuantity = stockQuantities.reduce((a, b) => a + b, 0);
	$("#totalStockQuantity").text(totalStockQuantity);
	$('#transferStock').val(totalStockQuantity);
	$('#remainingStock').val(tempCurrentStock-totalStockQuantity);
}
//=========== End Serialize Product ===========//

$(document).ready(function() {
	$('#form_transferWarehouse').bootstrapValidator({
    	live:'enabled',
    	message:'This value is not valid',
    	submitButton:'$form_transferWarehouse button [type="Submit"]',
    	submitHandler: function(validator, form, submitButton){
            var transferDate = $("#transferDate").val();
            var products = $("#products").val();
            var wareHouseID=$("#wareHouseID").val();
            var currentStock=$("#currentStock").val();
            var remainingStock=$("#remainingStock").val();
            var transferWareHouse=$("#transferWareHouse").val();
            var transferStock=$("#transferStock").val();
            var action = "saveTransfer";
            if(wareHouseID != transferWareHouse && parseFloat(remainingStock) >= 0 && parseFloat(transferStock) > 0){
                var fd = new FormData();
                fd.append('transferDate',transferDate);
                fd.append('products',products);
                fd.append('wareHouseID',wareHouseID);
                fd.append('currentStock',currentStock);
                fd.append('remainingStock',remainingStock);
                fd.append('transferWareHouse',transferWareHouse);
                fd.append('transferStock',transferStock);
				// Serialize Product
				if (product_type  != "serialize") {
					tbl_serialize_productsIdArray = 0;
					stockQuantities = 0;
				}
				fd.append('tbl_serialize_productsIdArray',tbl_serialize_productsIdArray);
				fd.append('stockQuantities',stockQuantities);
				fd.append('product_type', product_type);
                fd.append('action',action);
                $.ajax({
                    type: 'POST',
                    url: 'phpScripts/wareHouseTransferaction.php',
                    data: fd,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(response){
                    	if(response == "Success"){
                    		$("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> Successfully Saved");
                    	    $("#divMsg").show().delay(2000).fadeOut().queue(function(n) {
                    		    $(this).hide(); n();
                    		});
                    		manageWareHouseTransferTable.ajax.reload(null, false);
                    		$("#products").val('').trigger('change');
                    		$("#wareHouseID").empty().trigger('change');
                    		$("#currentStock").val('');
                    		$("#remainingStock").val('');
                    		$("#transferWareHouse").val('').trigger('change');
                    		$("#transferStock").val('');
                    	}else{
                    	    alert(response);
                    	}
                    },error: function (xhr) {
                    	alert(xhr.responseText);
                    }
                });
                $('#warehouseTransfer').modal('hide');
            }else{
                $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Product warehouse and transfer warehouse cannot be same. Remaining Quantity must be greater then negative or transfer quantity must be greater then 0 ");
        		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
        		  $(this).hide(); n();
        		});	    
            }
    	},
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
    	excluded: [':disabled'],
        fields: {
    			
    			currentStock: {
    				validators: {
    						stringLength: {
    						min: 1,
    					},
    						notEmpty: {
    						message: 'Please Insert Item Name'
    					},
    					regexp: {
    						regexp: /^([a-zA-Z0-9_ '\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ '\.\-\s\,\;\:\/\&\$\%\(\)]+$/,
    						message: 'Please insert alphanumeric value only'
    					}
    				}
    			},
    			
    			transferStock: {
    				validators: {
    					
    					regexp: {
    						regexp: /^[1-9]\d*$/,
    						message: 'Only Amount : 200 '
    					}
    				}
    			}
    		}
		});
	}); 

function deleteTransfer(transferId){
    var conMsg = confirm("Are you sure to delete??");
	if(conMsg){
        var action = "deleteTransfer";
        $.ajax({
    	    url: 'phpScripts/wareHouseTransferaction.php',
    		method:"POST",
    		data:{action:action, id:transferId},
    		success:function(data)
    		{
    		    if(data=="Success"){
    		        $("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> Successfully Deleted");
            	    $("#divMsg").show().delay(2000).fadeOut().queue(function(n) {
            		    $(this).hide(); n();
            		});
    		        manageWareHouseTransferTable.ajax.reload(null, false);
    		    }else{
    		        alert(data);
    		    }
    		}
    	});
	}
}