load_cart_data();
function load_cart_data(){
    //alert("Calling");
	$.ajax({
		url:"phpScripts/orderCheckOutAction.php",
		method:"POST",
		data:{action:'fetchCart'},
		dataType:"json",
		success:function(data)
		{
			//alert(data);
			$('#cart_details').html(data.cart_details);
			$('.total_price').text(data.total_price);   
			$('.badge').text(data.total_item);
		},
        error: function (xhr) {
            alert(xhr.responseText);
        }
	});
}
$(document).on('click', '#clear_cart', function(){
	var action = 'empty';
	alert(action);
	$.ajax({
		url:"phpScripts/orderCheckOutAction.php",
		method:"POST",
		data:{action:action},
		beforeSend: function () {
                    $('#loading').show();
                },
		success:function()
		{
			load_cart_data();
			$('#cart-popover').popover('hide');
			$("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> Your Cart has been clear");
			$("#divMsg").show().delay(2000).fadeOut().queue(function(n) {
			  $(this).hide(); n();
			});
		},complete: function () {
                    $('#loading').hide();
                },
        error: function (xhr) {
            alert(xhr.responseText);
        }
	});
});

$(document).on('click', '#check_out_cart', function(){
	$('#form_ordersales').bootstrapValidator({
	live:'enabled',
	message:'This value is not valid',
	submitButton:'$form_ordersales button [type="Submit"]',
	submitHandler: function(validator, form, submitButton){
        var orderDate = $("#orderDate").val();
        var customerId = $("#orderCustomer").val();
        var transportName = $("#transportName").val();
        var remarks = $("#orderRemarks").val();
        var totalAmount = $(".totalAmount").html();
        if(totalAmount == ""){
            totalAmount = 0;
        }
        var totalProductDiscount = $(".totalProductDiscount").html();
        if(totalProductDiscount == ""){
            totalProductDiscount = 0;
        }
        var orderDiscount = $("#orderDiscount").val();
        if(orderDiscount == ""){
            orderDiscount = 0;
        }
        var totalDiscount  = $(".totalDiscount").html();
        if(totalDiscount == ""){
            totalDiscount = 0;
        }
        var grandTotal = $(".grandTotal").html();
        if(grandTotal == ""){
            grandTotal = 0;
        }
        var paidAmount = $("#paid").val();
        if(paidAmount == ""){
            paidAmount = 0;
        }
        var paymentMethod=$("#paymentMethod").val();
        var vat = $("#vat").val();
        if(vat == ""){
            vat = 0;
        }
        var ait = $("#ait").val();
        if(ait == ""){
            ait = 0;
        }
        var carringCost = '0'; 
        var requisitionNo ='0';
        var wareHouse = '1';
        var productId = [];
        var productQuantity=[];
        var productPrice = [];
        var productDiscount=[];
        var productTotal=[];
        var i = 0;
        var checkProduct = 0;
        $('input[id^="productId"]').each(function() {
           productId[i] = $(this).val()+"@!@";
           i = i + 1;
           checkProduct++;
        });
        i = 0;
        $('input[id^="productQuantity"]').each(function() {
           productQuantity[i] = $(this).val()+"@!@";
           i = i + 1;
        });
        i = 0;
        $('input[id^="productPrice"]').each(function() {
           productPrice[i] = $(this).val()+"@!@";
           i = i + 1;
        });
        i = 0;
        $('input[id^="productDiscount"]').each(function() {
           productDiscount[i] = $(this).val()+"@!@";
           i = i + 1;
        });
        i = 0;
        var errorProductTotal = 0;
        $('span[id^="productTotal"]').each(function() {
           productTotal[i] = $(this).html()+"@!@";
           if(parseFloat($(this).html()) < 0){
                errorProductTotal = errorProductTotal+1;
            }
           i = i + 1;
        });
        if(parseFloat(checkProduct) == 0){
            $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Select atleast one product to sale");
    		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
    		  $(this).hide(); n();
    		});	    
        }else if(parseFloat(errorProductTotal) > 0 || parseFloat(grandTotal) < 0){
            $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Discount cannot be greater then product price");
    		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
    		  $(this).hide(); n();
    		});	    
    	}else if(customerId == '0' || customerId == ''){
    	    $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Customer not selected");
    		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
    		  $(this).hide(); n();
    		});	
    	}
    	else if(transportName == '0' || transportName == ''){
    	    $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Transport name not selected");
    		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
    		  $(this).hide(); n();
    		});	
    	}
    	else{
        	var action = "check_out_cart";
        	var fd = new FormData();
        	fd.append('orderDate',orderDate);
        	fd.append('customerId',customerId);
        	fd.append('transportName',transportName);
        	fd.append('wareHouse',wareHouse);
        	fd.append('remarks',remarks);
        	fd.append('totalAmount',totalAmount);
        	fd.append('totalProductDiscount',totalProductDiscount);
        	fd.append('orderDiscount',orderDiscount);
        	fd.append('totalDiscount',totalDiscount);
        	fd.append('grandTotal',grandTotal);
        	fd.append('paidAmount',paidAmount);
        	fd.append('paymentMethod',paymentMethod);
        	fd.append('vat',vat);
        	fd.append('ait',ait);
        	fd.append('carringCost',carringCost);
        	fd.append('requisitionNo',requisitionNo);
        	fd.append('productId',productId);
        	fd.append('productQuantity',productQuantity);
        	fd.append('productPrice',productPrice);
        	fd.append('productDiscount',productDiscount);
        	fd.append('productTotal',productTotal);
        	fd.append('action',action);
            $.ajax({
        		url:"phpScripts/orderCheckOutAction.php",
        		method:"POST",
        		data:fd,
        		contentType: false,
        		processData: false,
        		dataType: 'json',
        		success:function(data)
        		{
                    if(data.msg == "Success"){
                        alert("Success ! Sales Order Submitted");
        				window.open(window.location.origin+'/tempSalesOrderViewDetails.php?id='+data.salesId, '_blank');
                        setTimeout(function(){ window.location.href = window.location.origin+"/orderList.php?page=Pending"; }, 3000);
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
	},
    feedbackIcons: {
        valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
    },
	excluded: [':disabled'],
    fields: {
			salesMan: {
				validators: {
						
						notEmpty: {
						message: 'Please Select Sales Man'
					}
				}
			},
			contactNo: {
				validators: {
						stringLength: {
						min: 3,
					},
						notEmpty: {
						message: 'Please Insert Phone Number'
					},
					regexp: {
						regexp: /^(?:\+?88)?01[12-9]\d{8}$/,
						message: 'Mobile Ex: 01800000000'
					}
				}
			},
			customerName: {
				validators: {
						stringLength: {
						min: 1,
					},
						notEmpty: {
						message: 'Please Insert Customer Name'
					},
					regexp: {
						regexp: /^([a-zA-Z0-9_ \.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ \.\-\s\,\;\:\/\&\$\%\(\)]+$/,
						message: 'Please insert alphanumeric value only'
					}
				}
			},
			customerAddress: {
				validators: {
						stringLength: {
						min: 3,
					},
					regexp: {
						regexp: /^([a-zA-Z0-9_ \.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ \.\-\s\,\;\:\/\&\$\%\(\)]+$/,
						message: 'Please insert CustomerAddress only'
					}
				}
			}
			
		}
	});

});

// preview screan
function discountOfferPreview(){
    	var action = "discountOfferOrderPreview";
    	var fd = new FormData();
    	fd.append('action',action);
    	fd.append('type','Party');
        $.ajax({
    		url:"phpScripts/discountOfferAction.php",
    		method:"POST",
    		data:fd,
    		contentType: false,
    		processData: false,
    		beforeSend: function () {
                $('#loading').show();
            },
    		success:function(data)
    		{
                $("#discountOfferModalDetails").modal('show');
                $("#discountOfferDetailsPreview").html(data);
    		},
            complete: function () {
                $('#loading').hide();
            },
    		error: function (xhr) {
    			alert("xhr error: "+xhr.responseText);
    		}
    	});
    
}

// delete Cart
$(document).on('click', '.delete', function(){
    var conMsg = confirm("Are you sure to delete??");
	if(conMsg){
    	var product_id = $(this).attr("id");
    	var action = "remove";
    	$.ajax({
    		url:"phpScripts/orderCheckOutAction.php",
    		method:"POST",
    		data:{product_id:product_id, action:action},
    		beforeSend: function () {
                $('#loading').show();
            },
    		success:function(data)
    		{
    			load_cart_data();
    		},complete: function () {
                $('#loading').hide();
            },
    		error: function (xhr) {
    			alert(xhr.responseText);
    		}
    	});
	}
});

// previousPriceSingle product sales 
$(document).on('click', '.previousPriceSingleOrder', function(){
    if($("#orderCustomer").val() == ""){
        $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> select customer first then change credit information");
		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
		  $(this).hide(); n();
		});
    } else{
        $("#divErrorMsg").hide();
        var customers = $("#orderCustomer").val();
        var productId = $(this).attr("id").substring(1);
    	var action = "previousPriceSingle";
    	var fd = new FormData();
    	fd.append('customers',customers);
    	fd.append('productId',productId);
    	fd.append('action',action);
        $.ajax({
    		url:"phpScripts/whoseSaleAction.php",
    		method:"POST",
    		data:fd,
    		contentType: false,
    		processData: false,
    		beforeSend: function () {
                $('#loading').show();
            },
    		success:function(data)
    		{
                $("#previousSoldProductsView").modal('show');
                $("#previousProducts").html(data);
    		},
            complete: function () {
                $('#loading').hide();
            },
    		error: function (xhr) {
    			alert(xhr.responseText);
    		}
    	});
    }
});
// productSpecification script
$(document).on('click', '.productSpecification', function(){
    if($("#customers").val() == ""){
        $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> select customer first then change credit information");
		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
		  $(this).hide(); n();
		});
    } else{
        $("#divErrorMsg").hide();
        var customers = $("#customers").val();
        var productId = $(this).attr("id").substring(1);
    	var action = "productSpecification";
    	var fd = new FormData();
    	fd.append('customers',customers);
    	fd.append('productId',productId);
    	fd.append('action',action);
        $.ajax({
    		url:"phpScripts/whoseSaleAction.php",
    		method:"POST",
    		data:fd,
    		contentType: false,
    		processData: false,
    		beforeSend: function () {
                $('#loading').show();
            },
    		success:function(data)
    		{
    		    //alert(data);
                $("#productSpecificationView").modal('show');
                $("#productsSpec").html(data);
    		},
            complete: function () {
                $('#loading').hide();
            },
    		error: function (xhr) {
    			alert(xhr.responseText);
    		}
    	});
    }
});

//Calculate total amount
function calculateTotal(id){
	var total = ($("#productQuantity"+id).val()*$("#productPrice"+id).val());
	var len = $("#productDiscount"+id).val().length;
	if($("#productDiscount"+id).val().substring(len-1,len) == "%"){
		$("#productTotal"+id).html((total - (total * parseFloat($("#productDiscount"+id).val())/100)).toFixed(2));
	}else{
		$("#productTotal"+id).html((total-$("#productDiscount"+id).val()).toFixed(2));
	}
    	//Calculate Total
	var totalAmount = parseFloat(0);
	$("span[id^='productTotal']").each(function() {
	    totalAmount += parseFloat($(this).html());
    });
    $(".totalAmount").html(parseFloat(totalAmount).toFixed(2));
        
        //Calculate Discount
    var totalProductDiscount = parseFloat(0);
    $("input[id^='productDiscount']").each(function() {
        len = $(this).val().length;
        var productId = $(this).attr("id").substring(15);
        total = ($("#productQuantity"+productId).val()*$("#productPrice"+productId).val());
	    var unitProductDiscount = parseFloat(0);
	    if($(this).val().substring(len-1,len) == "%"){
	        totalProductDiscount += parseFloat(total * parseFloat($(this).val())/100);
	    }else{
	        totalProductDiscount += parseFloat($(this).val());
	    }
    });
    $(".totalProductDiscount").html(parseFloat(totalProductDiscount).toFixed(2));
    if($("#salesDiscount").val() == ""){
        $("#salesDiscount").val("0");
    }
    var salesDiscount = $("#salesDiscount").val();
    len = salesDiscount.length;
    var discountAmount = 0;
    if(salesDiscount.substring(len-1) == "%"){
        discountAmount = parseFloat(totalAmount * (parseFloat(salesDiscount)/100));
    }else{
        discountAmount = parseFloat(salesDiscount);
    }
    
    var totalDiscount = parseFloat(totalProductDiscount + discountAmount).toFixed(2);
    $(".totalDiscount").html(totalDiscount);
    $(".grandTotal").html(totalAmount.toFixed(2));
    /*var carringCost = $("#carringCost").val();
    if(carringCost == ""){
        carringCost = 0;
    }
    var vat = $("#vat").val();
    if(vat == ""){
        vat = 0;
    }
    var ait = $("#ait").val();
    if(ait == ""){
        ait = 0;
    }*/
    //$(".grandTotal").html((totalAmount+parseFloat(vat)+parseFloat(ait)+parseFloat(carringCost)-discountAmount).toFixed(2));

        //Calculate GrandTotal
        //$(".grandTotal").html((totalAmount+parseFloat(carringCost)-discountAmount).toFixed(2));
        //Calculate Credit Limit
        var grandTotal = parseFloat($(".grandTotal").html());
    	if(parseFloat($("#productTotal"+id).html()) < 0 || parseFloat($(".grandTotal").html()) < 0){
    	    $("#check_out_cart").attr('disabled',true);	  
            $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Discount cannot be greater then product price");
			$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
			  $(this).hide(); n();
			});
    	}
    	else{
    	    $("#check_out_cart").attr('disabled',false);
    	}
    //check_out_cart
}

//Discount calculation when entry sales discount
function calculateTotalDiscount(){
    var totalAmount = parseFloat($(".totalAmount").html());
    var totalProductDiscount = parseFloat($(".totalProductDiscount").html());
    if($("#salesDiscount").val() == ""){
        $("#salesDiscount").val("0");
    }
    var salesDiscount = $("#salesDiscount").val();
    var len = salesDiscount.length;
    var discountAmount = 0;
    if(salesDiscount.substring(len-1) == "%"){
        discountAmount = parseFloat(totalAmount * parseFloat(salesDiscount)/100);
    }else{
        discountAmount = parseFloat(salesDiscount);
    }
    
    var totalDiscount = parseFloat(totalProductDiscount + discountAmount).toFixed(2);
    $(".totalDiscount").html(totalDiscount);
    /*var carringCost = $("#carringCost").val();
    if(carringCost == ""){
        carringCost = 0;
    }
    var vat = $("#vat").val();
    if(vat == ""){
        vat = 0;
    }
    var ait = $("#ait").val();
    if(ait == ""){
        ait = 0;
    }
    $(".grandTotal").html((totalAmount+parseFloat(vat)+parseFloat(ait)+parseFloat(carringCost)-discountAmount).toFixed(2));*/
    $(".grandTotal").html(totalAmount.toFixed(2));
    var grandTotal = parseFloat($(".grandTotal").html());
	if(parseFloat(grandTotal) < 0){
	    $("#check_out_cart").attr('disabled',true);	  
        $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Discount cannot be greater then product price");
		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
		  $(this).hide(); n();
		});
	}
	else{
	    $("#check_out_cart").attr('disabled',false);
	}
}

//Update Session
function updateSession(product_id){
	var product_price = $('#productPrice'+product_id+'').val();
	var max_price = $('#productMaxPrice'+product_id).val();
	var min_price = $('#productMinPrice'+product_id).val();
	var product_quantity = $('#productQuantity'+product_id).val();
	var product_discount = $('#productDiscount'+product_id).val();
	var productName = $('#productName'+product_id).val();
	var action = "adjust";
	var grandTotal = 0;
	if($(".grandTotal").html() == undefined){
	    grandTotal = 0;
	}else{
	    grandTotal = parseFloat($(".grandTotal").html());
	}
	//alert(grandTotal);
	//grandTotal += parseFloat(product_quantity)*parseFloat(product_price);
	//alert("PQ: "+product_quantity+" PP: "+product_price+" ="+grandTotal);
	var customerCreditLimit = parseFloat($("#customerCreditLimit").val());
	var totalQuantity = $('#availableQuantity'+product_id).val();
	$('#productQuantity'+product_id).css({"color":"black","font-weight":"normal"});
	//alert(totalQuantity+ " " +product_quantity);
	if(parseFloat(totalQuantity) < parseFloat(product_quantity))
    {
        $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong>"+productName+" Sale quantity  cannot be greater then total available stock quantity");
		//$('#productName'+product_id).val().style.borderTopColor = 'red';
		$('#productQuantity'+product_id).css({"color":"red","font-weight":"bold"}); 
		$("#divErrorMsg").show().delay(5000).fadeOut().queue(function(n) {
		  $(this).hide(); n();
		});
    }
    if(parseFloat(product_price) < parseFloat(max_price) && userType.toLowerCase() != "super admin" && userType.toLowerCase() != "admin support" && userType.toLowerCase() != "admin support plus"){
        $('#productPrice'+product_id+'').val(max_price);
        $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Sale price ["+product_price+"] cannot be smaller then minimum price ["+min_price+"]");
		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
		  $(this).hide(); n();
		});
		product_price = max_price;
		calculateTotal(product_id);
    }
    $.ajax({
		url:"phpScripts/orderCheckOutAction.php",
		method:"POST",
		data:{product_id:product_id, product_discount:product_discount, product_price:product_price, product_quantity:product_quantity,product_limit:totalQuantity, action:action},
		success:function(data)
		{
			//load_cart_data();
		},
		error: function (xhr) {
			alert(xhr.responseText);
		}
	});
    
}
$("#orderCustomer").change(function() {
    if($("#orderCustomer").val() != ""){
        var action = 'loadPartyDue';
        var partyId = $(this).val();
        var voucherType = 'adjustment'; //Initially send to calculate just
        var entryVoucherType = "adjustment"; //Initially send to calculate just
        var dataString = "partyType="+entryVoucherType+"&voucherType="+voucherType+"&partyId="+partyId+"&action="+action;
        $.ajax({
            type: 'POST',
            url: 'phpScripts/paymentVoucherAction.php',
            data: dataString,
            dataType: 'json',
            beforeSend: function(){
                    // Show image container
                    $("#loading").show();
               },
            success: function(response){
                $("#book").html(response.previousDue);
                $("#previousDue").val(parseFloat(response.totalDue));
            },
            complete:function(data){
                // Hide image container
                $("#loading").hide();
            },error: function (xhr) {
                alert(xhr.responseText);
            }
        });
    }
});
