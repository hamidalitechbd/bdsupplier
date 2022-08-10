var salesType = $('#salesType').val();
var orderSalesCheckTable
$(document).ready(function() {
	orderSalesCheckTable = $("#orderSalesCheckTable").DataTable({
		'ajax': 'phpScripts/confirmOrderWarehouseAction.php?salesType=OrderSales&id='+$("#orderId").val(),
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
	
	$("#wareHouse").select2({
		placeholder: "Select Warehouse",
		allowClear: true
	});
	loadremoveProduct($("#orderId").val());
	
	$("#splitQty").keyup(function(){
        var splitQty = parseFloat($("#splitQty").val());
        var deletedUnallocatedQty = parseFloat($("#deletedUnallocatedQty").val());
        if(deletedUnallocatedQty-splitQty >= 0){
            
        }else{
            $("#splitQty").val($("#deletedUnallocatedQty").val());
            $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error! </strong> Qty must be lower then deleted and allocated qty");
        	$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
        	  $(this).hide(); n();
        	});
        }
    })
});
function loadCustomersSuppliers(tblType){
	var dataString = "tblType="+tblType;
	$.ajax({
        type: 'GET',
        url: 'phpScripts/loadCustomerSupplier.php',
        data: dataString,
        dataType: 'json',
        success: function(response){
          var len = response.length;
			$("#customerId").empty();
			for( var i = 0; i<len; i++){
				var id = response[i]['id'];
				var partyName = response[i]['partyName'];
				$("#customerId").append("<option value='"+id+"'>"+partyName+"</option>");
			}
        },error: function (xhr) {
            //alert(xhr.responseText);
            $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error 1! </strong>"+xhr.responseText);
        	$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
        	  $(this).hide(); n();
        	});
        }
    });
}

$("#btn_confirmOrder").click(function (){
    var orderId = $("#orderId").val();
    var noofUnallocatedProducts = $('#removeProductId option').length;
    if(noofUnallocatedProducts <= 1)
    {
        var fd = new FormData();
        fd.append('orderId',orderId);
        fd.append('action','confirmOrder');
        $.ajax({
            type: 'POST',
            url: 'phpScripts/confirmOrderWarehouseAction.php',
            data: fd,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response){
                if(response == "Success"){
                    $("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> Confirmed Successfully");
    				$("#divMsg").show().delay(2000).fadeOut().queue(function(n) {
    				  $(this).hide(); n();
    			    });
    			    location.href = 'orderList.php?page=Checked';
                }else{
                    $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error 7! </strong>"+response);
            		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
            		  $(this).hide(); n();
            		});
                }
            },error: function (xhr) {
                //alert(xhr.responseText);
                $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error 8! </strong>"+xhr.responseText);
            	$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
            	  $(this).hide(); n();
            	});
            }
        });  
    }else{
        $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error 8! </strong> assign all product warehouse first");
    	$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
    	  $(this).hide(); n();
    	});
    }
})
$("#btn_salesOrderConfirm").click(function (){
    var i = 0;
     var checkChangeQTY = []; 
    $('input[id^="checkChangeQTY_"]').each(function() {
        if($(this).val() != ""){
            checkChangeQTY[i] = $(this).val();
        }else{
            checkChangeQTY[i] = "";
        }
        i = i + 1;
    });
    i = 0;
    var checkQTY = []; 
    $('input[id^="checkQTY_"]').each(function() {
        if($(this).val() != ""){
            checkQTY[i] = $(this).val();
        }else{
            checkQTY[i] = "";
        }
        i = i + 1;
    });
    i = 0;
    var detailsId = []; 
    $('input[id^="detailsId_"]').each(function() {
        if($(this).val() != ""){
            detailsId[i] = $(this).val();
        }else{
            detailsId[i] = "";
        }
        i = i + 1;
    });
    var countRow = checkQTY.length;
    var error = 0;
    var isChange = [];
    for(i=0; i< countRow; i++){
        if(parseFloat(checkChangeQTY[i]) > parseFloat(checkQTY[i])){
            error++;
        }else if(parseFloat(checkChangeQTY[i]) < parseFloat(checkQTY[i])){
            isChange[i] = '1';
        }else{
            isChange[i] = '0'
        }
    }
    if(error == 0){
        var confirmOrderDate = $("#confirmOrderDate").val();
        var accountNo = $("#accountNo").val();
        var bankRferenceNumber = $("#bankRferenceNumber").val();
        var advanceAmount = $("#advanceAmount").val();
        var transportName = $("#transportName").val();
        var orderId = $("#orderId").val();
        
        if(accountNo != "" && advanceAmount != ""){
            var orderCode = $("#salesCode").val();
            var orderId = $("#orderId").val();
            var fd = new FormData();
            fd.append('confirmOrderDate',confirmOrderDate);
            fd.append('accountNo',accountNo);
            fd.append('bankRferenceNumber',bankRferenceNumber);
            fd.append('advanceAmount',advanceAmount);
            fd.append('transportName',transportName);
            fd.append('orderId',orderId);
            fd.append('orderCode',orderCode);
            fd.append('checkChangeQTY',checkChangeQTY);
            fd.append('checkQTY',checkQTY);
            fd.append('isChange',isChange);
            fd.append('detailsId',detailsId);
            fd.append('action','salesOrderConfirm');
            $.ajax({
                type: 'POST',
                url: 'phpScripts/CheckOrderSalesAction.php',
                data: fd,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(response){
                    if(response == "Success"){
                        $("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success! </strong> Return Successfully");
        				$("#divMsg").show().delay(2000).fadeOut().queue(function(n) {
        				  $(this).hide(); n();
        			    });
        			    location.href = 'orderProcessList.php';
                    }else{
                        $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error! </strong>"+response);
                		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
                		  $(this).hide(); n();
                		});
                    }
                },error: function (xhr) {
                    //alert(xhr.responseText);
                    $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error! </strong>"+xhr.responseText);
                	$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
                	  $(this).hide(); n();
                	});
                }
            });  
        }else{
            $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error! </strong>You must select all the required field.");
    		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
    		  $(this).hide(); n();
    		});
        }
    }else{
        $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error! </strong>Change quantity must be less or equal to checked quantity.");
		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
		  $(this).hide(); n();
		});
    }
})


//Delete Sales Return
function deleteSalesReturn(salesReturnId){
    var conMsg = confirm("Are you sure to delete??");
	if(conMsg){
		var dataString = "id="+salesReturnId+"&action=TSSalesReturn";
		$.ajax({
			type: 'POST',
			url: 'phpScripts/orderSalesCheckAction.php',
			data: dataString,
			dataType: 'json',
			success: function(response){
				//alert("1="+response);
				if(response == "Success"){
				    $("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> Successfully Deleted");
    				$("#divMsg").show().delay(2000).fadeOut().queue(function(n) {
    				  $(this).hide(); n();
    			    });
					orderSalesCheckTable.ajax.reload(null, false);
				}else{
					//alert(response);
					$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error 10! </strong> "+response);
                	$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
                	  $(this).hide(); n();
                	});
				}
			},
			error: function (xhr) {
				//alert(xhr.responseText);
				$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error 9! </strong>"+xhr.responseText);
            	$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
            	  $(this).hide(); n();
            	});
			}
	    });  
	}
}

$("#RecalculationBtn").click(function (){
    var availableQTY = []; 
    var i = 0;
    $('input[id^="availableQTY_"]').each(function() {
        if($(this).val() != ""){
            availableQTY[i] = $(this).val();
        }else{
            availableQTY[i] = "";
        }
        i = i + 1;
    });
    var updatedAmount = []; 
    i = 0;
    $('input[id^="updatedAmount_"]').each(function() {
        if($(this).val() != ""){
            updatedAmount[i] = $(this).val();
        }else{
            updatedAmount[i] = "";
        }
        i = i + 1;
    });
    var sailedAmount = []; 
    i = 0;
    $('div[id^="sales_amount_"]').each(function() {
        if($(this).html() != ""){
            sailedAmount[i] = $(this).html();
        }else{
            sailedAmount[i] = "";
        }
        i = i + 1;
    });
    var detailsId = []; 
    i = 0;
    $('input[id^="detailsId_"]').each(function() {
        if($(this).val() != ""){
            detailsId[i] = $(this).val();
        }else{
            detailsId[i] = "";
        }
        i = i + 1;
    });
    var offerQty = [];
    i = 0;
    $('div[id^="offer_quantity_"]').each(function() {
        if($(this).html() != ""){
            offerQty[i] = $(this).html();
        }else{
            offerQty[i] = "";
        }
        i = i + 1;
    });
    var offerDiscountAmount = [];
    i = 0;
    $('div[id^="offer_discount_amount_"]').each(function() {
        if($(this).html() != ""){
            offerDiscountAmount[i] = $(this).html();
        }else{
            offerDiscountAmount[i] = "";
        }
        i = i + 1;
    });
    var totalAfterDiscount = [];
    i = 0;
    $('div[id^="total_after_discount_"]').each(function() {
        if($(this).html() != ""){
            totalAfterDiscount[i] = $(this).html();
        }else{
            totalAfterDiscount[i] = "";
        }
        i = i + 1;
    });
    var fd = new FormData();
    fd.append('availableQTY',availableQTY);
    fd.append('updatedAmount',updatedAmount);
    fd.append('sailedAmount',sailedAmount);
    fd.append('detailsId',detailsId);
    fd.append('offerQty',offerQty);
    fd.append('offerDiscountAmount',offerDiscountAmount);
    fd.append('totalAfterDiscount',totalAfterDiscount);
    fd.append('action','recalculation');
    $.ajax({
        type: 'POST',
        url: 'phpScripts/CheckOrderSalesAction.php',
        data: fd,
        contentType: false,
        processData: false,
        dataType: 'json',
        beforeSend: function () {
            $('#loading').show();
        },
        complete: function () {
            $('#loading').hide();
        },
        success: function(response){
            /*jQuery.each( response, function( i, val ) {
             alert(val[i])
            });*/
            for (var i=0; i < response["data"].length; i++){
                var row = response['data'][i];
                $("#offer_quantity_"+row[0]).text(row[1]);
                $("#offer_discount_amount_"+row[0]).text(row[2]);
                $("#total_after_discount_"+row[0]).text(row[3]);
            }
        },error: function (xhr) {
            //alert(xhr.responseText);
            $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error 9! </strong>"+xhr.responseText);
        	$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
        	  $(this).hide(); n();
        	});
        }
    });  
})

function removeOrderDetails(orderDetailsId, orderId){ 
    var conMsg = confirm("Are You Sure ! Split Warehouse ?");
	if(conMsg){
	var dataString = "id="+orderDetailsId+"&orderId="+orderId+"&action=deleteOrderDetails";
    $.ajax({
		type: 'POST',
		url: 'phpScripts/confirmOrderWarehouseAction.php',
		data: dataString,
		dataType: 'json',
		success: function(response){
			if(response != ""){
			    $("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> Successfully Deleted");
				$("#divMsg").show().delay(2000).fadeOut().queue(function(n) {
				  $(this).hide(); n();
			    });
			    loadremoveProduct(orderId);
				orderSalesCheckTable.ajax.reload(null, false);
			}else{
				//alert(response);
				$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error 10! </strong> "+response);
            	$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
            	  $(this).hide(); n();
            	});
			}
		},
		error: function (xhr) {
			//alert(xhr.responseText);
			$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error 9! </strong>"+JSON.stringify(xhr));
        	$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
        	  $(this).hide(); n();
        	});
		}
    });  
	}
}

function loadremoveProduct(id){
    var dataString = "id="+id+"&action=unallocatedOrderProduct";
    $.ajax({
		type: 'POST',
		url: 'phpScripts/confirmOrderWarehouseAction.php',
		data: dataString,
		success: function(response){
			 $("#removeProductId").html(response);
			 $("#productwiseWarehouseId").html('');
			 $("#deletedUnallocatedQty").val('');
			 $("#splitQty").val('');
			 
		},
		error: function (xhr) {
			//alert(xhr.responseText);
			$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error 9! </strong>"+JSON.stringify(xhr));
        	$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
        	  $(this).hide(); n();
        	});
		}
    });
}
$("#removeProductId").change(function(){
    if($("#removeProductId").val() != ""){
        var dataString = "id="+$("#removeProductId").val()+"&orderId="+$("#orderId").val()+"&action=loadProductwiseWarehouse";
        $.ajax({
    		type: 'POST',
    		url: 'phpScripts/confirmOrderWarehouseAction.php',
    		data: dataString,
    		dataType: 'json',
    		success: function(response){
    		    if(response.msg=="Success"){
    			    $("#productwiseWarehouseId").html(response.loadWarehouse);
    			    $("#deletedUnallocatedQty").val(response.remaining);
    			    
    		    }else{
    		        $("#productwiseWarehouseId").html('');
    		        $("#deletedUnallocatedQty").val('');
    		    }
    		},
    		error: function (xhr) {
    			//alert(xhr.responseText);
    			$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error 9! </strong>"+JSON.stringify(xhr));
            	$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
            	  $(this).hide(); n();
            	});
    		}
        });
    }else{
        $("#productwiseWarehouseId").html("");
    }
})
function saveOrderDetails(){
    var productId = $("#removeProductId").val();
    var orderId = $("#orderId").val();
    var warehouseId = $("#productwiseWarehouseId").val();
    var checkedQty = parseFloat($("#splitQty").val());
    var remainQty = $("#deletedUnallocatedQty").val();
    var dataString = "productId="+productId+"&orderId="+orderId+"&warehouseId="+warehouseId+"&checkedQty="+checkedQty+"&remainQty="+remainQty+"&action=saveReallocatedOrderDetails";
    if(warehouseId != "" && warehouseId != "0" && productId != "" && productId != "0" && checkedQty > 0){
        $.ajax({
    		type: 'POST',
    		url: 'phpScripts/confirmOrderWarehouseAction.php',
    		data: dataString,
    		dataType: 'json',
    		success: function(response){
    		    if(response=="Success"){
    			    loadremoveProduct(orderId);
    				orderSalesCheckTable.ajax.reload(null, false);
    		    }else{
    		        $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error 9! </strong>"+JSON.stringify(response));
                	$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
                	  $(this).hide(); n();
                	});
    		    }
    		},
    		error: function (xhr) {
    			//alert(xhr.responseText);
    			$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error 9! </strong>"+JSON.stringify(xhr));
            	$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
            	  $(this).hide(); n();
            	});
    		}
        });
    }else{
        $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error 9! </strong> Select all information properly");
    	$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
    	  $(this).hide(); n();
    	});
    }
}
