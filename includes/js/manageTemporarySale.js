var countCartProduct = 1;
loadCustomersSuppliers("customersWithCreditLimit");
var adjCounter = 0;
/*$("#sortData").change(function() {
    manageTsTable.ajax.url("phpScripts/temporarySaleAction.php?sortData="+$("#sortData").val()).load();
});*/
//$("#salesDate").val(new Date().toISOString().substring(0, 10));
$("#wareHouse").select2( {
	placeholder: "Select Warehouse",
	allowClear: true
});
$("#wareHouse").prop('selectedIndex', 1).trigger('change');
loadWarehouseWiseProductsWithStock();
$("#customers").select2({
	placeholder: "Select Customer",
	allowClear: true
});
$("#salesMan").select2({
	placeholder: "Select Sales Man",
	allowClear: true
});
function selectSalesMan(userId){
    $("#salesMan").val(userId).trigger('change');
}
function loadCustomersSuppliers(tblType){
	var dataString = "tblType="+tblType;
	$.ajax({
        type: 'GET',
        url: 'phpScripts/loadCustomerSupplier.php',
        data: dataString,
        dataType: 'json',
        success: function(response){
            var len = response.length;
			$("#customers").empty();
			if(tblType == "customersWithCreditLimit"){
			    $("#customersLimit").empty();
			}
			for( var i = 0; i<len; i++){
				var id = response[i]['id'];
				var partyName = response[i]['partyName'];
				$("#customers").append("<option value='"+id+"'>"+partyName+"</option>");
                if(tblType == "customersWithCreditLimit"){
                    $("#customersLimit").append("<option value='"+id+"'>"+response[i]['creditLimit']+"</option>");
    			}
			}
			
        },error: function (xhr) {
            alert(xhr.responseText);
        }
    });
}

//Split whole sale customer credit limit
$("#customers").change(function() {
    if($("#customers").val() != ""){
        $("#customersLimit").val($("#customers").val());
        var str = $( "#customersLimit option:selected" ).text();
        //var res = str.split("__");
        if(str != "~~ Select Customer to credit limit ~~"){
            $("#customerCreditLimit").val(str);
        }else{
            $("#customerCreditLimit").val("0");
        }
        if(parseFloat($("#customerCreditLimit").val()) < parseFloat($(".grandTotal").html())){
            $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> This customer doesnot have enough credit.");
    		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
    		  $(this).hide(); n();
    		});
    		$("#check_out_cart").attr('disabled',true);
        }else{
            $("#check_out_cart").attr('disabled',false);
        }
    }else{
        $("#customerCreditLimit").val("");
        $("#check_out_cart").attr('disabled',true);
    }
});
/*$("#add_products").on("change", function (){        
    $modal = $('#myModal');
    if($(this).val()){
        $modal.modal('show');
    }
});*/
//previously saved cart data
load_cart_data();
function load_cart_data(){
	$.ajax({
		url:"phpScripts/temporarySaleAction.php",
		method:"POST",
		data:{fetchCart:'action'},
		dataType:"json",
		success:function(data)
		{
			$('#cart_details').html(data.cart_details);
			$('.total_price').text(data.total_price);
			$('.badge').text(data.total_item);
		}
	});
}
//Clear all cart data
$(document).on('click', '#clear_cart', function(){
	var action = 'empty';
	$.ajax({
		url:"phpScripts/temporarySaleAction.php",
		method:"POST",
		data:{action:action},
		success:function()
		{
			load_cart_data();
			$('#cart-popover').popover('hide');
			alert("Your Cart has been clear");
		}
	});
});
//Cart data added
$(document).on('click', '.add_to_cart', function(){
    if($("#customers").val() == ""){
        $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> select customer first then change credit information");
		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
		  $(this).hide(); n();
		});
    }else{
        var product_id = $(this).attr("id");
        var totalQuantity = $('#totalStock'+product_id).html();
        var sale_quantity = $('#productQuantity'+product_id).val();
    	if($('#productQuantity'+product_id).val() == undefined){
    	    sale_quantity = 0;
    	}
    	if(parseFloat(totalQuantity) > parseFloat(sale_quantity))
    	{
			//var id = countCartProduct++;
            var product_name = $('#name'+product_id+'').val();
        	var product_price = $('#price'+product_id+'').val();
        	var min_price = $('#min_price'+product_id+'').val();
			var max_price = $('#max_price'+product_id+'').val();
        	var product_quantity = $('#quantity'+product_id).val();
        	var warehouse_id = $("#wareHouse").val();
        	var warehouse_name = $("#wareHouse option:selected").text();
        	var product_discount = '0';
        	var action = "add";
        	var grandTotal = 0;
        	if($(".grandTotal").html() == undefined){
        	    grandTotal = 0;
        	}else{
        	    grandTotal = parseFloat($(".grandTotal").html());
        	}
        	grandTotal += parseFloat(product_price) * parseFloat(product_quantity);
        	var customerCreditLimit = parseFloat($("#customerCreditLimit").val());
        	if(parseFloat(customerCreditLimit) >= parseFloat(grandTotal)){
            	if(product_quantity > 0)
            	{
            		$.ajax({
            			url:"phpScripts/temporarySaleAction.php",
            			method:"POST",
            			data:{product_id:product_id, product_name:product_name, product_discount:product_discount, product_price:product_price,min_price:min_price, max_price:max_price, product_quantity:product_quantity, warehouse_id:warehouse_id, warehouse_name:warehouse_name, action:action},
            			success:function(data)
            			{
            				load_cart_data();
            			},
            			error: function (xhr) {
            				alert(xhr.responseText);
            			}
            		});
            	}
            	else
            	{
            		$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Enter Number of Quantity");
            		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
            		  $(this).hide(); n();
            		});
            	}
        	}else{
        	    $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Credit limit already over");
        		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
        		  $(this).hide(); n();
        		});
        	}
    	}else{
            $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Sale quantity ["+sale_quantity+"] cannot be greater then total available stock quantity["+totalQuantity+"]");
    		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
    		  $(this).hide(); n();
    		});
        }
    }
});

function add_to_cart(product_id, product_name, product_price, min_price, max_price, product_quantity, warehouse_id, warehouse_name){
    if($("#customers").val() == ""){
        $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> select customer first then change credit information");
		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
		  $(this).hide(); n();
		});
    }else{
    	var product_discount = '0';
		//var id = countCartProduct++;
    	var action = "add";
    	var grandTotal = 0;
    	if($(".grandTotal").html() == undefined){
    	    grandTotal = 0;
    	}else{
    	    grandTotal = parseFloat($(".grandTotal").html());
    	}
    	grandTotal += parseFloat(product_price) * parseFloat(product_quantity);
    	var customerCreditLimit = parseFloat($("#customerCreditLimit").val());
    	if(parseFloat(customerCreditLimit) >= parseFloat(grandTotal)){
        	if(product_quantity > 0)
        	{
        		$.ajax({
        			url:"phpScripts/temporarySaleAction.php",
        			method:"POST",
        			data:{product_id:product_id, product_name:product_name, product_discount:product_discount, product_price:product_price,min_price:min_price, max_price:max_price, product_quantity:product_quantity, warehouse_id:warehouse_id, warehouse_name:warehouse_name, action:action},
					dataType: 'json',
        			success:function(data)
        			{
        				if(data > 0){
    			           $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Quantity must be less then not available");
                    		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
                    		  $(this).hide(); n();
                    		});
                    		
                    		$("#divASErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Quantity must be less then available");
                    		$("#divASErrorMsg").show().delay(2000).fadeOut().queue(function(n) {
                    		  $(this).hide(); n();
                    		}); 
        			    }else{
        				    load_cart_data();
							// Start Serialize Product
							if (data.productType == "serialize") {
								let productId = data.productId;
								let warehouseId = data.warehouseId;
								let currentStockId = data.currentStockId;
								showSerializTable(productId, warehouseId, '', currentStockId);
							}
							// End Serialize Product
        				     $("#divASMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> Product Added");
                    		$("#divASMsg").show().delay(2000).fadeOut().queue(function(n) {
                    		  $(this).hide(); n();
                    		});  
        			    }
        			},
        			error: function (xhr) {
        				alert(xhr.responseText);
        			}
        		});
        	}
        	else
        	{
        		$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Please Enter Number of Quantity");
        		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
        		  $(this).hide(); n();
        		});
        	}
    	}else{
    	    $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Credit limit already over");
    		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
    		  $(this).hide(); n();
    		});
    	}
    }
}

// Start Serialize Product
var serializeProductsId = 0;
var serializeSaleQuantity = 0;
var countLen = 0;
var checkSerializeProductQuantity = false;

function showSerializTable(id, warehouseId, txt, product_id) {
	$("#serializProductId").val(id);
	$("#serializProductWarehouseId").val(warehouseId);
	let matchQuantity = '';
	//let _token = $('input[name="_token"]').val();
	let fd = new FormData();
	if (txt == "checkSerializeTotalQuantity") {
		var totalSaleQuantity = 0;
		$('[name="checkSerialize"]').each(function () {
			let productAndWarehouse = $(this).val();
			let tempArray = productAndWarehouse.split(',');
			//totalSaleQuantity += parseInt($("#productQuantity" + product_id).val());
			totalSaleQuantity += parseInt($("#productQuantity" + tempArray[0]).val());
		});
		matchQuantity = "CheckQuantity";
	}
	fd.append('matchQuantity', matchQuantity);
	fd.append('id', id);
	fd.append('product_id', product_id);
	fd.append('warehouseId', warehouseId);
	fd.append('action', "showSerializTable");
	$.ajax({
		url: "phpScripts/temporarySaleAction.php",
		method: "POST",
		data: fd,
		contentType: false,
		processData: false,
		dataType: "json",
		success: function (result) {
			if (txt == "checkSerializeTotalQuantity") {
				checkSerializeProductQuantity = false;
				if (result.totalMatchQuantity == totalSaleQuantity) {
					checkSerializeProductQuantity = true;
				}
			} else {
				console.log(result.totalQuantityForSale);
				$("#serializeProductTable").html('');
				$("#serializeProductTable").html(result.displayTable);
				$("#serialNumsModal").modal("show");
				$("#totalStockQuantity").text(result.totalQuantityForSale);
			}
		},
		beforeSend: function () {
			$('#loading').show();
		},
		complete: function () {
			$('#loading').hide();
			//let totalStockQuantity = $("#quantity_" + id + "_" + warehouseId).val();
			/* let totalStockQuantity = $("#productQuantity" + product_id).val();
			$("#totalStockQuantity").text(totalStockQuantity); */
		},
		error: function (response) {
			alert(JSON.stringify(response))
			$("#serializeProductTable").text("Something Went Wrong.Please Try Again");
		}
	});
}

function calculateTotalQuantity(saleQty, product_id, warehouse_id, tblSerializeId) {
	var serializeRemainingQty = parseFloat($("#serializeRemainingQty_" + tblSerializeId).text());
	if (saleQty > serializeRemainingQty) {
		$("#stockQuantity_" + tblSerializeId).val('');
		alert("Quantity Not Available!");
		return 0;
	}
	var totalStockQuantity = 0;
	$('[name="stockQuantity"]').each(function () {
		var currentTxtQuantity = $(this).val();
		if (currentTxtQuantity == '') {
			currentTxtQuantity = 0;
		}
		totalStockQuantity += parseFloat(currentTxtQuantity);
	});
	$("#totalStockQuantity").text(totalStockQuantity);
	$("#productQuantity" + product_id).val(totalStockQuantity);
	serializeProductsId = tblSerializeId;
	serializeSaleQuantity = saleQty;
	let product_type = "serialize";
	updateSession(product_id, product_type);
}

// End Serialize Product

$(document).on('click', '.delete', function(){
    var conMsg = confirm("Are you sure to delete??");
	if(conMsg){
    	//var product_id = $(this).attr("id");
    	var id = $(this).attr("id");
		//var id=$("#id"+product_id).val();
    	var action = "remove";
    	$.ajax({
    		url:"phpScripts/temporarySaleAction.php",
    		method:"POST",
    		data:{id:id, /*product_id:product_id, */action:action},
    		success:function(data)
    		{
    			load_cart_data();
    		},
    		error: function (xhr) {
    			alert(xhr.responseText);
    		}
    	});
	}
});

//Calculate total amount
function calculateTotal(id){
    if($("#customers").val() == ""){
        $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> select customer first then change credit information");
		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
		  $(this).hide(); n();
		});
        load_cart_data();
    }else if (parseFloat($("#availableQuantity"+id).val()) < parseFloat($("#productQuantity"+id).val())){
        //$("#check_out_cart").attr('disabled',true);	  
        $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Product Quantity Cannot be larger then Available Quantity");
		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
		    $(this).hide(); n();
		});
		load_cart_data();
    }else{
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
            var stringId = $(this).attr('id').substring(15,$(this).attr('id').length);
            len = $("#productDiscount"+stringId).val().length;
            total = ($("#productQuantity"+stringId).val()*$("#productPrice"+stringId).val());
    	    if($("#productDiscount"+stringId).val().substring(len-1,len) == "%"){
    	        totalProductDiscount += parseFloat(total * parseFloat($("#productDiscount"+stringId).val())/100);
    	    }else{
    	        totalProductDiscount += parseFloat($("#productDiscount"+stringId).val());
    	    }
    	   
            /*len = $(this).val().length;
            var productId = $(this).attr("id").substring(15);
            total = ($("#productQuantity"+productId).val()*$("#productPrice"+productId).val());
    	    if($(this).val().substring(len-1,len) == "%"){
    	        totalProductDiscount += parseFloat(total * parseFloat($(this).val())/100);
    	    }else{
    	        totalProductDiscount += parseFloat($(this).val());
    	    }*/
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
        
        //Calculate GrandTotal
        $(".grandTotal").html((totalAmount-discountAmount).toFixed(2));
        //Calculate Credit Limit
        var customerCreditLimit = parseFloat($("#customerCreditLimit").val());
        var grandTotal = parseFloat($(".grandTotal").html());
    	if(customerCreditLimit < grandTotal){
            $("#check_out_cart").attr('disabled',true);	  
            $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> credit limit is already over. Change the quantity or update the limit to complete this voucher");
    		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
    		  $(this).hide(); n();
    		});
    	}else{
    	    $("#check_out_cart").attr('disabled',false);
    	}
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
    
    $(".grandTotal").html((totalAmount-discountAmount).toFixed(2));
    
    var customerCreditLimit = parseFloat($("#customerCreditLimit").val());
    var grandTotal = parseFloat($(".grandTotal").html());
	if(customerCreditLimit < grandTotal){
        $("#check_out_cart").attr('disabled',true);
        $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Credit limit is already over. Change the quantity or update the limit to complete this voucher");
		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
		  $(this).hide(); n();
		});
	}else{
	    $("#check_out_cart").attr('disabled',false);
	}
}

//Split whole sale customer credit limit
/*$("#customers").change(function() {
    var str = $( "#customers option:selected" ).text();
    var res = str.split("__");
    $("#customerCreditLimit").val(res[1]);
});*/

//Load Stock for warehouse change
/*$("#wareHouse").change(function(){
    loadWarehouseWiseProductsWithStock();
});*/
function loadWarehouseWiseProductsWithStock(){
    var dataString = "wareHouseId="+$("#wareHouse").val()+"&type=TS";
    $.ajax({
		type: 'POST',
		url: 'phpScripts/manageSaleProductsView.php',
		data: dataString,
		beforeSend: function () {
            $('#loading').show();
        },
		success: function(response){
			$("#results").html(response);
		},
		complete: function () {
            $('#loading').hide();
        },
		error: function (xhr) {
			alert(xhr.responseText);
		}
	});
}

//Update Session
function updateSession(id, product_type) {
	var product_price = $('#productPrice'+id+'').val();
	var max_price = $('#productMaxPrice'+id).val();
	var min_price = $('#productMinPrice'+id).val();
	var product_quantity = $('#productQuantity'+id).val();
	var product_discount = $('#productDiscount'+id).val();
	var action = "adjust";
	var grandTotal = 0;
	if($(".grandTotal").html() == undefined){
	    grandTotal = 0;
	}else{
	    grandTotal = parseFloat($(".grandTotal").html());
	}
	//grandTotal += parseFloat(product_price) * parseFloat(product_quantity);
	var customerCreditLimit = parseFloat($("#customerCreditLimit").val());
	var totalQuantity = $('#availableQuantity'+id).val();
	if(parseFloat(totalQuantity) < parseFloat(product_quantity))
    {
        $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Sale quantity ["+product_quantity+"] cannot be greater then total available stock quantity["+totalQuantity+"]");
		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
		  $(this).hide(); n();
		});
    }
	else if(parseFloat(customerCreditLimit) >= parseFloat(grandTotal)){
	    if(parseFloat(product_price) < parseFloat(max_price) && userType.toLowerCase() != "super admin" && userType.toLowerCase() != "admin support" && userType.toLowerCase() != "admin support plus"){
            $('#productPrice'+id+'').val(max_price);
            $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Sale price ["+product_price+"] cannot be smaller then minimum price ["+min_price+"]");
    		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
    		  $(this).hide(); n();
    		});
    		product_price = max_price;
    		calculateTotal(id);
        }
        $.ajax({
    		url:"phpScripts/temporarySaleAction.php",
    		method:"POST",
    		data:{id:id, product_discount:product_discount, product_price:product_price, product_quantity:product_quantity,product_limit:totalQuantity, action:action, product_type: product_type, serializeProductsId: serializeProductsId, serializeSaleQuantity: serializeSaleQuantity },
    		beforeSend: function () {
                    //$('#loading').show();
                adjCounter++;
            },success:function(data)
    		{
    			//load_cart_data();
    		},complete: function () {
    		    adjCounter--;
                //$('#loading').hide();
            },
    		error: function (xhr) {
    			alert(xhr.responseText);
    		}
    	});
    }else{
        $("#check_out_cart").attr('disabled',true);	  
	    $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Credit limit already over");
		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
		  $(this).hide(); n();
		});
	}
}


function discountOfferPreview(){
    	var action = "discountOfferPreview";
    	var fd = new FormData();
    	fd.append('action',action);
    	fd.append('type','TS');
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
    		    //alert(data);
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

//Order to whole sale process

function discountOffer(type, productId){
    	var action = "discountOffer";
    	var fd = new FormData();
    	fd.append('productId',productId);
    	fd.append('type',type);
    	fd.append('action',action);
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
    		    //alert(data);
                $("#discountOfferModal").modal('show');
                $("#discountOfferDetails").html(data);
    		},
            complete: function () {
                $('#loading').hide();
            },
    		error: function (xhr) {
    			alert(xhr.responseText);
    		}
    	});
    
}
$(document).on('click', '#finalSaleConfirm', function(){
    var rowId = [];
    var updateWarehouse = [];
    var sales = $("#sales").val();
    var salesProduct = $("#salesProduct").val();

    var vouchers = $("#vouchers").val();
    var i = 0;
    $('input[id^="rowId"]').each(function() {
        rowId[i] = $(this).val();
        updateWarehouse[i] = $("#updateWarehouse"+rowId[i]).val();
        i++;
    });
    
    var fd = new FormData();
	fd.append('sales',sales);
	fd.append('salesProduct',salesProduct);
	fd.append('vouchers',vouchers);
	fd.append('rowId',rowId);
	fd.append('updateWarehouse',updateWarehouse);
	fd.append('action',"FinalSalesConfirmation");
    $.ajax({
		url:"phpScripts/temporarySaleAction.php",
		method:"POST",
		data:fd,
		contentType: false,
		processData: false,
		dataType: 'json',
		beforeSend: function () {
            $('#loading').show();
        },
		success:function(data)
		{
            if(data.msg == "Success"){
                window.open('https://jafree.alitechbd.com/tsSalesViewDetails.php?id='+data.salesId, '_blank');
                    $('#customers').val('').trigger('change'); 
                    $('#remarks').val('');
                    load_cart_data();
                    $("#salesconfirmation").modal('hide');
                /*window.open('https://jafreeuat.alitechbd.com/wholesalesViewDetails.php?id='+data.salesId, '_blank');
                loadCustomersSuppliers("customersWithCreditLimit");
                load_cart_data();
                checkProduct = 0;
                $("#customers").val('').trigger('change');
                $("#customersLimit").val('');
                $("#customersInitCreditLimit").val('');
                $("#transportName").val('').trigger('change');
                loadWarehouseWiseProductsWithStock();
                $("#requisitionNo").val(''); 
                $("#projectName").val('');
                $("#customerCreditLimit").val("0");
                */
            }else{
                $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> "+data);
        		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
        		  $(this).hide(); n();
        		});
            }
		},
		complete: function () {
            $('#loading').hide();
        },
		error: function (xhr) {
			alert(xhr.responseText);
		}
	});
})

$(document).on('click', '#check_out_cart', function(){
    var salesDate = $("#salesDate").val();
    var customers = $("#customers").val();
    var salesMan = $("#salesMan").val();
    var totalAmount = $(".totalAmount").html();
    var totalProductDiscount = $(".totalProductDiscount").html();
    var salesDiscount = $("#salesDiscount").val();
    var totalDiscount  = $(".totalDiscount").html();
    var grandTotal = $(".grandTotal").html();
    var paidAmount = $("#paid").val();
    var paymentMethod=$("#paymentMethod").val();
    var vat = $("#vat").val();
    var ait = $("#ait").val();
    var referenceInfo = $("#reference").val();
    var remarks = $("#remarks").val();
    var carringCost = $("#carringCost").val();; 
    var requisitionNo =$("#requisitionNo").val();
    var wareHouse = $("#wareHouse").val();
    var productId = [];
    var productQuantity=[];
    var productPrice = [];
    var productDiscount=[];
    var productTotal=[];
	var warehouse_id=[];
    var i = 0;
    var checkProduct = 0;
    $('input[id^="productId"]').each(function() {
       productId[i] = $(this).val()+"@!@";
       i = i + 1;
       checkProduct++;
    });
	i = 0;
        $('input[id^="warehouseId"]').each(function() {
           warehouse_id[i] = $(this).val()+"@!@";
           i = i + 1;
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
    $('span[id^="productTotal"]').each(function() {
       productTotal[i] = $(this).html()+"@!@";
       i = i + 1;
    });
    if(parseFloat(checkProduct) == 0){
        $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Select atleast one product to sale");
		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
		  $(this).hide(); n();
		});	    
    }else if(adjCounter > 0){
        $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Please try again. Click check out cart button again");
		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
		  $(this).hide(); n();
		});
    }else if($('#check_out_cart').attr('disabled')){
        $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Disabled button cannot be accessed");
		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
		  $(this).hide(); n();
		});
    }else{
    	var action = "check_out_cart";
    	var fd = new FormData();
    	fd.append('salesDate',salesDate);
    	fd.append('customers',customers);
    	fd.append('wareHouse',wareHouse);
    	fd.append('salesMan',salesMan);
    	fd.append('totalAmount',totalAmount);
    	fd.append('totalProductDiscount',totalProductDiscount);
    	fd.append('salesDiscount',salesDiscount);
    	fd.append('totalDiscount',totalDiscount);
    	fd.append('grandTotal',grandTotal);
    	fd.append('paidAmount',paidAmount);
    	fd.append('paymentMethod',paymentMethod);
    	fd.append('vat',vat);
    	fd.append('ait',ait);
    	fd.append('carringCost',carringCost);
    	fd.append('requisitionNo',requisitionNo);
    	fd.append('productId',productId);
		fd.append('warehouse_id',warehouse_id);
    	fd.append('productQuantity',productQuantity);
    	fd.append('productPrice',productPrice);
    	fd.append('productDiscount',productDiscount);
    	fd.append('productTotal',productTotal);
    	fd.append('referenceInfo',referenceInfo);
    	fd.append('remarks',remarks);
    	fd.append('type',"PartySale");
    	fd.append('action',action);
        $.ajax({
    		url:"phpScripts/temporarySaleAction.php",
    		method:"POST",
    		data:fd,
    		contentType: false,
    		processData: false,
    		dataType: 'json',
    		success:function(data)
    		{
    		    if(data.msg == "Success"){
    		        $("#sales").val(data.sales);
                    $("#salesProduct").val(data.salesProduct);
                    //$("#vouchers").val(data.vouchers);
                    var offerProductWarehouseData = "";
                    for(var i = 0; i < data.salesProduct.length; i++){
                        if(data.salesProduct[i][9] == '0'){
                            offerProductWarehouseData += "<table class='table table-bordered'><tr><th>Product name</th><th> Quantity</th><th>Wharehouse</th></tr><tr><td><input type='hidden' name='rowId"+data.salesProduct[i][10]+"' id='rowId"+data.salesProduct[i][10]+"' value='"+data.salesProduct[i][10]+"'>"+data.salesProduct[i][11]+"</td><td>"+data.salesProduct[i][1]+"</td><td><select class='form-control' id='updateWarehouse"+data.salesProduct[i][10]+"' name='updateWarehouse"+data.salesProduct[i][10]+"'>"+data.salesProduct[i][12]+"</td></tr></table>";
                        }
                    }
                    $("#offerProductWarehouse").html(offerProductWarehouseData);
                    $("#salesconfirmation").modal('show');
                    
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

});


$(document).on('click', '#btn_previousPrice', function(){
    if($("#customers").val() == ""){
        $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> select customer first then change credit information");
		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
		  $(this).hide(); n();
		});
    } else{
        $("#divErrorMsg").hide();
        var customers = $("#customers").val();
        var productId = [];
        var i = 0;
        var checkProduct = 0;
        $('input[id^="productId"]').each(function() {
           productId[i] = $(this).val()+"@!@";
           i = i + 1;
           checkProduct++;
        });
        if(parseFloat(checkProduct) == 0){
            $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Select atleast one product to sale");
    		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
    		  $(this).hide(); n();
    		});	    
        }
    	else{
        	var action = "previousPrice";
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
    }
});

$(document).on('click', '.previousPriceSingleTs', function(){
    if($("#customers").val() == ""){
        $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> select customer first then change credit information");
		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
		  $(this).hide(); n();
		});
    } else{
        $("#divErrorMsg").hide();
        var customers = $("#customers").val();
        var productId = $(this).attr("id").substring(1);
    	var action = "previousPriceSingleTs";
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

/*------------------ Start Delete walkin sales, sales products and sales voucher (if exists) ---------------------- */
/*function deleteSales(salesId){
    var conMsg = confirm("Are you sure to delete??");
	if(conMsg){
	    $.ajax({
			type: 'POST',
			url: 'phpScripts/temporarySaleAction.php',
			data: "action=deleteSales&id="+salesId,
			dataType: 'json',
			success: function(response){
				if(response == "Success"){
					manageSalesTable.ajax.reload(null, false);
					$("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> Successfully Deleted");
					$("#divMsg").show().delay(2000).fadeOut().queue(function(n) {
					  $(this).hide(); n();
					});
				}else{
					$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> "+response);
					$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
					  $(this).hide(); n();
					});
				}
			},error: function (xhr) {
				alert(xhr.responseText);
			}
	    });
	}
}*/
/*------------------ End Delete walkin sales, sales products and sales voucher (if exists) ---------------------- */

// loader js
/*setTimeout(function() {
    $('#loader').fadeOut('fast');
	}, 25000); // <-- time in milliseconds*/
	
