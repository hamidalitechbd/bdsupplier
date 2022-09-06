var manageDamageProductsTable
$(document).ready(function() {
	manageDamageProductsTable = $("#manageDamageProductsTable").DataTable({
		'ajax': 'phpScripts/productDamageAction.php?sortData='+$("#sortData").val(),
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

//=========== For Serialize Product ===========//
var tbl_serialize_productsIdArray = [];
var stockQuantities = [];
var tempCurrentStock = 0;
var product_type = '';
//=========== End ===========//
$( "#damageProducts" ).change(function() {
    var productsId = $(this).val();
    var action = "fetchWareHouse";
    $.ajax({
		url:"phpScripts/wareHouseTransferaction.php",
		method:"POST",
		data:{action:action, productsId:productsId},
		dataType:"json",
		success:function(data)
		{
		    $("#damageWareHouse").empty().trigger('change');
		    $("#wareHouseStock").empty();
		    $("#damageWareHouse").append("<option value=''>~~ Select Warehouse ~~</option>");
		    $("#wareHouseStock").append("<option value=''>~~ Select Warehouse Stock ~~</option>");
		    for(var i=0; i<data.length; i++){
			    $("#damageWareHouse").append("<option value='"+data[i].id+"'>"+data[i].wareHouseName+"</option>");
			    $("#wareHouseStock").append("<option value='"+data[i].id+"'>"+data[i].currentStock+"</option>");
				// Serialize Product
				product_type = data[i].type;
				if ( product_type == "serialize") {
					$('#damageQuantity').attr('disabled', true);
					$("#ShowSerializeBtn").removeClass("hidden");
					$("#btn_close").removeClass("hidden");
					$("#btn_confirmSerialzeProduct").addClass("hidden");
					$("#btn_addRow").addClass("hidden");
				}else{
					$('#damageQuantity').attr('disabled', false);
					$("#ShowSerializeBtn").addClass("hidden");
					tbl_serialize_productsIdArray = [];
					stockQuantities = [];
				}
				// End
			}
		}
	});
});
$("#damageWareHouse").change(function(){
    $("#wareHouseStock").val($(this).val());
    var str = $( "#wareHouseStock option:selected" ).text();
    if(str != "~~ Select Warehouse Stock ~~"){
        $("#currentStock").val(str);
    }else{
        $("#currentStock").val("0");
    }
    $("#damageQuantity").val("0");
});

$("#damageQuantity").on("keyup", function (){
    var currentStock = $("#currentStock").val();
    var damageQuantity = $(this).val();
    if(parseFloat(damageQuantity) > parseFloat(currentStock)){
        $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Damage quantity can not be greater then current stock.");
		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
		  $(this).hide(); n();
		});	    
    }
});

//=========== Start Serialize Product ===========//
function showSerializTable() {
	var product_id = $("#damageProducts").val();
	var warehouseId = $("#damageWareHouse").val();
	tempCurrentStock = $("#currentStock").val();
	if (warehouseId == '' || product_id == '') {
		$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> please select warehouse & product");
		$("#divErrorMsg").show().delay(5000).fadeOut().queue(function(n) {
		  $(this).hide(); n();
		});
		return;
	}
	$(".qtyTxt").text("Damage Quantity");
	$(".txtView").text("Available Quantity: ");
	$(".txtTotalView").text("Total Damage Quantity: ");
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
				let totalDamageQuantity = 0;
				let damageQuantity = 0;
				tbl_serialize_productsIdArray = [];
				serializeProducts.forEach(serializeProduct => {
					remainingQty = serializeProduct.quantity - serializeProduct.used_quantity;
					tbl_serialize_products_id = serializeProduct.id;
					tbl_serialize_productsIdArray[key] = serializeProduct.id;
					if (len > 0) {
						damageQuantity = stockQuantities[key];
					}
					totalDamageQuantity +=  parseInt((damageQuantity));
					rows += '<tr><td>' + (key + 1) + '</td>' +
						'<td id="serializeRemainingQty_' + tbl_serialize_products_id + '">' + remainingQty + '</td>'+
						'<td><input class="form-control only-number input-sm stockQuantity' + key +
						'" id="stockQuantity_' + tbl_serialize_products_id + '" type="text" name="stockQuantity" placeholder=" ... " required oninput="calculateTotalQuantity(this.value,' + product_id + ',' + warehouseId + ',' + tbl_serialize_products_id + ','+"'addReturnQty'"+')" value="' +damageQuantity + '"></td></tr>';
					key++;
				});
				$("#serializeProductReturnTable").html('');
				$("#serializeProductReturnTable").html(rows);
				$("#serializeProductReturnModal").modal("show");
				$("#totalStockQuantity").text(totalDamageQuantity);
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
	$('#damageQuantity').val(totalStockQuantity);
}
//=========== End Serialize Product ===========//

/*---------------------------- Start Voucher save portion ----------------------------------------------------*/
    $(document).ready(function() {
		$('#form_damageProducts').bootstrapValidator({
		live:'enabled',
		message:'This value is not valid',
		submitButton:'$form_damageProducts button [type="Submit"]',
		submitHandler: function(validator, form, submitButton){
    //event.preventDefault();
    var damageDate=$('#damageDate').val();
    var damageProducts=$("#damageProducts").val();
    var damageWareHouse=$('#damageWareHouse').val();
    var currentStock=$('#currentStock').val();
    var damageQuantity=$('#damageQuantity').val();
    var damageRemarks=$('#damageRemarks').val();
    if(parseFloat(damageQuantity) <= parseFloat(currentStock) && parseFloat(damageQuantity) > 0){
        var fd = new FormData();
        fd.append('damageDate',damageDate);
        fd.append('damageProducts',damageProducts);
        fd.append('damageWareHouse',damageWareHouse);
        fd.append('currentStock',currentStock);
        fd.append('damageQuantity',damageQuantity);
        fd.append('damageRemarks',damageRemarks);
		// Serialize Product
		if (product_type  != "serialize") {
			tbl_serialize_productsIdArray = 0;
			stockQuantities = 0;
		}
		fd.append('tbl_serialize_productsIdArray',tbl_serialize_productsIdArray);
        fd.append('stockQuantities',stockQuantities);
        fd.append('product_type', product_type);
		// End  Serialize Product
        fd.append('action','saveDamage');
        $.ajax({
            type: 'POST',
            url: 'phpScripts/productDamageAction.php',
            data: fd,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response){
                if(response == "Success"){
                    /*BootstrapDialog.show({
                        title: 'Duronto damage-products says',
                        message: 'Saved Successfully.'
                    });*/
                    $("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> Saved Successfully");
    				$("#divMsg").show().delay(2000).fadeOut().queue(function(n) {
    				  $(this).hide(); n();
    			    });
    	            manageDamageProductsTable.ajax.reload(null, false);
    	            $("#damageProducts").val('').trigger("change");
                    $('#damageWareHouse').empty();
                    $('#currentStock').val('');
                    $('#damageQuantity').val('');
                    $('#damageRemarks').val('');
                    $("#btn_saveVoucher").attr("disabled",false);
                }else{
                    $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> "+response);
            		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
            		    $(this).hide(); n();
            		});
            		$("#btn_saveVoucher").attr("disabled",false);
                }
            },error: function (xhr) {
                alert(xhr.responseText);
            }
        });
	}else{
	    $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Damage quantity can not be greater then current stock and damage quantity must be greater then 0.");
		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
		    $(this).hide(); n();
		});
	}
	//$('#myModal').modal('hide');
		},
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
		excluded: [':disabled'],
        fields: {
				damageQuantity: {
					validators: {
							stringLength: {
							min: 1,
						},
							notEmpty: {
							message: 'Please Insert Amount'
						},
						regexp: {
							regexp: /^[0-9]+(?:\.[0-9]+)?$/,
							message: 'Please insert numeric value only'
						}
					}
				},
				damageRemarks: {
					validators: {
							stringLength: {
							min: 1,
						},
						regexp: {
							regexp: /^([a-zA-Z0-9_ '\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ '\.\-\s\,\;\:\/\&\$\%\(\)]+$/,
							message: 'Please insert alphanumeric value only'
						}
					}
				}
			}
			});
		});
/*---------------------------- end Voucher save portion ----------------------------------------------------*/
function deleteDamage(id){
    var conMsg = confirm("Are you sure to delete??");
	if(conMsg){
    	var action = "deleteDamage";
    	$.ajax({
    		url:"phpScripts/productDamageAction.php",
    		method:"POST",
    		data:{id:id, action:action},
    		dataType: 'json',
    		success:function(data)
    		{
    		    if(data == "Success"){
    		        $("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> Deleted Successfully");
				    $("#divMsg").show().delay(2000).fadeOut().queue(function(n) {
    				    $(this).hide(); n();
    			    });
        			manageDamageProductsTable.ajax.reload(null, false);
        			$("#damageProducts").val('').trigger("change");
                    $('#damageWareHouse').empty();
                    $('#currentStock').val('');
                    $('#damageQuantity').val('');
                    $('#damageRemarks').val('');
                    $("#btn_saveVoucher").attr("disabled",false);
    		    }else{
    		        $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> "+data);
            		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
            		    $(this).hide(); n();
            		});
    		    }
    		},
    		error: function (xhr) {
    			alert(xhr.responseText);
    		}
    	});
	}
}

/*------------------------------------- Select 2 portion ------------------------------------*/

$("#damageWareHouse").select2( {
	placeholder: "~~ Select Warehouse ~~",
	allowClear: true
} );
$("#damageProducts").select2({
	placeholder: "~~ Select Products ~~",
	allowClear: true
});
