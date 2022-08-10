//$("#date").val(new Date().toISOString().substring(0, 10));
$("#customers").select2({
	placeholder: "Select Customer",
	allowClear: true
});
$("#wareHouse").select2({
	placeholder: "Select wareHouse",
	allowClear: true
});
$("#wareHouse").val(1).trigger('change');
$("#customers").change(function() {
    var customers = $("#customers").val();
    $.ajax({
		url:"phpScripts/temporarySaleAdjustmentAction.php",
		method:"POST",
		data:{customers:customers},
		dataType:"json",
		beforeSend: function(){
		    $('#manageTSTable').hide();
		    $("#loader").show();
        },
		success:function(data)
		{
		    $("#loader").hide();
			$('#manageTSTable').html(data.tableDetails);
			$('#manageTSTable').show();
		}
	});
});
function removeTSProducts(tsProductsId){
    var conMsg = confirm("Are you sure to delete??")
	if(conMsg){
		$("#"+tsProductsId).remove();
	}
}
function totalCalculation(tsProductsId){
    var quantity = $("#quantity_"+tsProductsId).html();
    var soldQuantity = $("#soldQuantity_"+tsProductsId).html();
    var returnedQuantity = $("#returnedQuantity_"+tsProductsId).html();
    var saleQuantity = $("#saleQuantity_"+tsProductsId).val();
    if(saleQuantity == ""){
        saleQuantity = 0;
        $("#saleQuantity_"+tsProductsId).val(saleQuantity)
    }
    var unitPrice = $("#unitPrice_"+tsProductsId).val();
    if(unitPrice == ""){
        unitPrice = 0;
        $("#unitPrice_"+tsProductsId).val(unitPrice)
    }
    var returnQuantity = $("#returnQuantity_"+tsProductsId).val();
    if(returnQuantity == ""){
        returnQuantity = 0;
        $("#returnQuantity_"+tsProductsId).val(returnQuantity)
    }
    var remainingQuantity = quantity - (parseFloat(soldQuantity)+parseFloat(returnedQuantity)) - (parseFloat(saleQuantity)+parseFloat(returnQuantity));
    var totalPrice = unitPrice*saleQuantity;
    $("#remainingQuantity_"+tsProductsId).html(remainingQuantity);
    $("#totalPrice_"+tsProductsId).html(totalPrice);
    var total = 0;
    $("span[id^='totalPrice_']").each(function() {
        var totalPrice = $(this).html();
        if(totalPrice != "" && totalPrice != 0){
            total += parseFloat(totalPrice);
        }
    });
    $("#total").html(total);
    var len = $("#discount").val().length;
    var discount = 0;
    if($("#discount").val().substring(len-1,len) == "%"){
		discount = parseFloat((total * parseFloat($("#discount").val())/100).toFixed(2));
	}else{
		discount = parseFloat($("#discount").val());
	}
    //var discount = $("#discount").val();
    
    $("#grandTotal").html(parseFloat(total) - parseFloat(discount));
    //$("#paidAmount").val($("#grandTotal").html());
    $("#paidAmount").val(0);
    if(remainingQuantity < 0){
        $('#btn_saleAndAdjust').attr('disabled', true);
        $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Remaining value cannot be negative.");
			$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
			  $(this).hide(); n();
		});
    }else{
        $('#btn_saleAndAdjust').attr('disabled', false);
    }
}
function calculateDiscount(){
    var total = $("#total").html();
    var len = $("#discount").val().length;
    var discount = 0;
    if($("#discount").val().substring(len-1,len) == "%"){
		discount = parseFloat((total * parseFloat($("#discount").val())/100).toFixed(2));
	}else{
		discount = parseFloat($("#discount").val());
	}
    //var discount = $("#discount").val();
    if(parseFloat(discount) > parseFloat(total)){
        $('#btn_saleAndAdjust').attr('disabled', true);
        $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Discount cannot be greater then total.");
				$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
				  $(this).hide(); n();
		});
		$("#grandTotal").html(total);
    }else{
        $('#btn_saleAndAdjust').attr('disabled', false);
        $("#grandTotal").html(total - parseFloat(discount));
        //$("#paidAmount").val($("#grandTotal").html());
        $("#paidAmount").val(0);
        $("#divErrorMsg").hide();
    }
}
$(document).on('click', '#btn_saleAndAdjust', function(){
    event.preventDefault();
    $(this).prop('disabled', true);
    var salesDate = $("#date").val();
    var customers = $("#customers").val();
    var totalAmount = $("#total").html();
    var totalProductDiscount = 0;
    var salesDiscount = $("#discount").val();
    var totalDiscount  = parseFloat(salesDiscount);
    var grandTotal = $("#grandTotal").html();
    var paidAmount = $("#paidAmount").val();
    var paymentMethod=$("#paymentMethod").val();
    var vat = 0;
    var ait = 0;
    var carringCost = 0; 
    var requisitionNo =0;
    var wareHouse = $("#wareHouse").val();
    
    var pojectName = $("#pojectName").val();
    var requisitionNo = $("#requisitionNo").val();
    var remarks = $("#remarks").val();
    
    var TSproductsId = [];
    var productQuantity=[];
    var productPrice = [];
    var productTotal=[];
    var returnTSproductsId = [];
    var returnQuantity=[];
    var remainingZeroQuantityId=[];
    var i = 0;
    $('input[id^="saleQuantity_"]').each(function() {
        var sQuantity = $(this).val();
        if(sQuantity > 0){
            var res = $(this).attr('id').split("_");
            var id = res[res.length-1];
            TSproductsId[i] = id+"@!@";
            productQuantity[i] = sQuantity+"@!@";
            productPrice[i] = $("#unitPrice_"+id).val()+"@!@";
            productTotal[i] = $("#totalPrice_"+id).html()+"@!@";
            i = i + 1;
        }
    });
    i = 0;
    $('input[id^="returnQuantity_"]').each(function() {
        var sQuantity = $(this).val();
        if(sQuantity > 0){
            var res = $(this).attr('id').split("_");
            var id = res[res.length-1];
            returnTSproductsId[i] = id+"@!@";
            returnQuantity[i] = sQuantity+"@!@";
            i = i + 1;
        }
    });
    i = 0;
    var errorRemaining = 0;
    $('span[id^="remainingQuantity_"]').each(function() {
        var sQuantity = $(this).html();
        if(sQuantity == '0'){
            var res = $(this).attr('id').split("_");
            var id = res[res.length-1];        
            remainingZeroQuantityId[i] = id+"@!@";
            i = i + 1;
        }else if(parseFloat(sQuantity) < 0){
            errorRemaining++;
        }
    });
    if(errorRemaining == 0){
        $('#btn_saleAndAdjust').attr('disabled', false);
    	var action = "salesAdjustment";
    	var fd = new FormData();
    	fd.append('salesDate',salesDate);
    	fd.append('customers',customers);
    	fd.append('wareHouse',wareHouse);
    	
    	fd.append('pojectName',pojectName);
    	fd.append('requisitionNo',requisitionNo);
    	fd.append('remarks',remarks);
    	
    	fd.append('totalAmount',totalAmount);
    	fd.append('salesDiscount',salesDiscount);
    	fd.append('grandTotal',grandTotal);
    	fd.append('paidAmount',paidAmount);
    	fd.append('paymentMethod',paymentMethod);
    	fd.append('vat',vat);
    	fd.append('ait',ait);
    	fd.append('TSproductsId',TSproductsId);
    	fd.append('productQuantity',productQuantity);
    	fd.append('productPrice',productPrice);
    	fd.append('productTotal',productTotal);
    	fd.append('returnTSproductsId',returnTSproductsId);
    	fd.append('returnQuantity',returnQuantity);
    	fd.append('remainingZeroQuantityId',remainingZeroQuantityId);
    	fd.append('action',action);
        $.ajax({
    		url:"phpScripts/temporarySaleAdjustmentAction.php",
    		method:"POST",
    		data:fd,
    		contentType: false,
    		processData: false,
    		dataType: 'json',
    		success:function(data)
    		{
    		    if(data.salesId == "0" && data.salesReturnId == "0"){
    		        $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> No sales and sales return products entered.");
        					$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
        					  $(this).hide(); n();
    				});
    		    }else{
    		        if(data.msg == "Success"){
                        $("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> Successfully Deleted");
        					$("#divMsg").show().delay(2000).fadeOut().queue(function(n) {
        					  $(this).hide(); n();
        				});
        				if(data.salesId != "0"){
        				    salesReport(data.salesId,'FS')
                            //window.open('http://jafree.alitechbd.com/fsSalesViewDetails.php?id='+data.salesId, '_blank');
        				}
        				if(data.salesReturnId != "0"){
                            window.open('http://jafree.alitechbd.com/tsSalesReturnViewDetails.php?id='+data.salesReturnId+"&rType=TS", '_blank');
        				}
                        $('#manageTSTable').html("");
                    }else{
                        $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> "+data);
        					$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
        					  $(this).hide(); n();
        				});
                    }    
    		    }
                			
    		},
    		error: function (xhr) {
    		    $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> "+xhr.responseText);
        			$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
        			  $(this).hide(); n();
        		});
    			alert(xhr.responseText);
    		}
    	});
    }else{
        $('#btn_saleAndAdjust').attr('disabled', true);
        $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Remaining value cannot be negative.");
			$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
			  $(this).hide(); n();
		});
    }

});