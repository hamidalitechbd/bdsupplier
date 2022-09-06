var countCartProduct = 1;
$("#wareHouse").select2({
	placeholder: "Select Warehouse",
	allowClear: true
});

$("#salesMan").select2({
	placeholder: "Select Reference By",
	allowClear: true
});
function selectSalesMan(userId) {
	$("#salesMan").val(userId).trigger('change');
}
$("#wareHouse").prop('selectedIndex', 1).trigger('change');
loadWarehouseWiseProductsWithStock();
/*$("#wareHouse").change(function(){
	loadWarehouseWiseProductsWithStock();
});*/
function loadWarehouseWiseProductsWithStock() {
	var dataString = "type=wiCustomer&wareHouseId=" + $("#wareHouse").val();
	$.ajax({
		type: 'POST',
		url: 'phpScripts/manageSaleProductsView.php',
		data: dataString,
		beforeSend: function () {
			$('#loading').show();
		},
		success: function (response) {
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

$("#contactNo").on("blur", function () {
	$("#divErrorMsg").hide();
	var contactNo = $("#contactNo").val();
	var action = "fetch_customer";
	if (contactNo.length == 11) {
		$.ajax({
			url: "phpScripts/action.php",
			method: "POST",
			data: { action: action, contact_no: contactNo },
			dataType: "json",
			beforeSend: function () {
				$('#loading').show();
			},
			success: function (data) {
				if (data != null) {
					$('#customerId').val(data.id);
					$('#customerName').val(data.customerName);
					$('#customerEmail').val(data.contactEmail);
					$('#customerAddress').val(data.customerAddress);
					$("#check_out_cart").attr('disabled', false);
				} else {
					$('#customerId').val('0');
					$("#customerName").val('');
					$("#customerEmail").val('');
					$("#customerAddress").val('');
					$("#check_out_cart").attr('disabled', false);
					$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Customer not found");
					$("#divErrorMsg").show().delay(10000).fadeOut().queue(function (n) {
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
	} else {
		$('#customerId').val('0');
		$("#customerName").val('');
		$("#customerEmail").val('');
		$("#customerAddress").val('');
	}
});
$("#add_products").on("change", function () {
	$modal = $('#myModal');
	if ($(this).val()) {
		$modal.modal('show');
	}
});
// Start Check Serialize Product Quantity 
$("#paid").on("change", function () {
	countLen = $('input[name*="checkSerialize"]').length;
	if (countLen > 0) {
		showSerializTable(0, 0, "checkSerializeTotalQuantity", "");
	}
});
// End Check Serialize Product Quantity 

load_cart_data();
$(document).on('click', '#clear_cart', function () {
	var action = 'empty';
	$.ajax({
		url: "phpScripts/action.php",
		method: "POST",
		data: { action: action },
		success: function () {
			load_cart_data();
			$('#customerId').val('0');
			$("#customerName").val('');
			$("#customerEmail").val('');
			$("#customerAddress").val('');
			$("#contactNo").val('');
			$('#cart-popover').popover('hide');
			$("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> Your Cart has been clear");
			$("#divMsg").show().delay(2000).fadeOut().queue(function (n) {
				$(this).hide(); n();
			});
		}
	});
});

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
		url: "phpScripts/action.php",
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
		$("#stockQuantity_" + tblSerializeId).val(0);
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

function load_cart_data() {
	$.ajax({
		url: "phpScripts/fetch_cart.php",
		method: "POST",
		dataType: "json",
		success: function (data) {
			$('#cart_details').html(data.cart_details);
			$('.total_price').text(data.total_price);
			$('.badge').text(data.total_item);
		}
	});
}
$(document).on('click', '.add_to_cart', function () {
	if ($("#customerName").val() == "") {
		$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Add customer information first then change product information");
		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function (n) {
			$(this).hide(); n();
		});
		load_cart_data();
	} else {
		var product_id = $(this).attr("id");
		var totalQuantity = $('#totalStock' + product_id).html();
		var sale_quantity = $('#productQuantity' + product_id).val();
		var min_price = $('#min_price' + product_id + '').val();
		var max_price = $('#max_price' + product_id + '').val();
		var warehouse_id = $('#wareHouse').val();
		var warehouse_name = $("#wareHouse option:selected").text();
		if ($('#productQuantity' + product_id).val() == undefined) {
			sale_quantity = 0;
		}
		if (parseFloat(totalQuantity) > parseFloat(sale_quantity)) {
			/*if(parseInt($("#maxRowId").val()) > 0){
				countCartProduct = parseInt($("#maxRowId").val());
				countCartProduct++;
			}else{
				countCartProduct = 1;
			}
			var id = countCartProduct++;*/
			var product_name = $('#name' + product_id + '').val();
			var product_price = $('#price' + product_id + '').val();
			var product_quantity = $('#quantity' + product_id).val();
			var action = "add";
			if (product_quantity > 0) {
				$.ajax({
					url: "phpScripts/action.php",
					method: "POST",
					data: { product_id: product_id, product_name: product_name, product_price: product_price, min_price: min_price, max_price: max_price, product_quantity: product_quantity, warehouse_id: warehouse_id, warehouse_name: warehouse_name, action: action },
					success: function (data) {
						load_cart_data();
					},
					error: function (xhr) {
						alert(xhr.responseText);
					}
				});
			}
			else {
				$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Please Enter Number of Quantity");
				$("#divErrorMsg").show().delay(10000).fadeOut().queue(function (n) {
					$(this).hide(); n();
				});

			}
		} else {
			$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Sale quantity [" + sale_quantity + "] cannot be greater then total available stock quantity[" + totalQuantity + "]");
			$("#divErrorMsg").show().delay(10000).fadeOut().queue(function (n) {
				$(this).hide(); n();
			});
		}
	}
});

function add_to_cart(product_id, product_name, product_price, min_price, max_price, product_quantity, warehouse_id, warehouse_name) {
	var action = "add";
	/*if(parseInt($("#maxRowId").val()) > 0){
		countCartProduct = parseInt($("#maxRowId").val());
		countCartProduct++;
	}else{
		countCartProduct = 1;
	}
	var id = countCartProduct++;*/
	if (product_quantity > 0) {
		$.ajax({
			url: "phpScripts/action.php",
			method: "POST",
			data: { product_id: product_id, product_name: product_name, product_price: product_price, min_price: min_price, max_price: max_price, product_quantity: product_quantity, warehouse_id: warehouse_id, warehouse_name: warehouse_name, action: action },
			dataType: 'json',
			success: function (data) {
				//alert("Data = "+data);
				if (data.count > 0) {
					$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Quantity must be less then not available");
					$("#divErrorMsg").show().delay(10000).fadeOut().queue(function (n) {
						$(this).hide(); n();
					});

					$("#divASErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Quantity must be less then available");
					$("#divASErrorMsg").show().delay(2000).fadeOut().queue(function (n) {
						$(this).hide(); n();
					});
				} else {
					load_cart_data();
					if (data.productType == "serialize") {
						let productId = data.productId;
						let warehouseId = data.warehouseId;
						let currentStockId = data.currentStockId;
						showSerializTable(productId, warehouseId, "", currentStockId);
					}
					$("#divASMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> Product Added");
					$("#divASMsg").show().delay(2000).fadeOut().queue(function (n) {
						$(this).hide(); n();
					});
				}

			},
			error: function (xhr) {
				alert(xhr.responseText);
			}
		});
	}
	else {
		$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Please Enter Number of Quantity");
		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function (n) {
			$(this).hide(); n();
		});
		$("#divASErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Please Enter Number of Quantity");
		$("#divASErrorMsg").show().delay(10000).fadeOut().queue(function (n) {
			$(this).hide(); n();
		});


	}
}
//Calculate total amount
function calculateTotal(id) {
	if ($("#customerName").val() == "") {
		$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Add customer information first then change product information");
		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function (n) {
			$(this).hide(); n();
		});
		load_cart_data();
	} else if (parseFloat($("#availableQuantity" + id).val()) < parseFloat($("#productQuantity" + id).val())) {
		//$("#check_out_cart").attr('disabled',true);	  
		$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Product Quantity Cannot be larger then Available Quantity");
		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function (n) {
			$(this).hide(); n();
		});
		load_cart_data();
	} else {
		var total = ($("#productQuantity" + id).val() * $("#productPrice" + id).val());
		var len = $("#productDiscount" + id).val().length;
		if ($("#productDiscount" + id).val().substring(len - 1, len) == "%") {
			$("#productTotal" + id).html((total - (total * parseFloat($("#productDiscount" + id).val()) / 100)).toFixed(2));
		} else {
			$("#productTotal" + id).html((total - $("#productDiscount" + id).val()).toFixed(2));
		}
		//Calculate Total
		var totalAmount = parseFloat(0);
		$("span[id^='productTotal']").each(function () {
			totalAmount += parseFloat($(this).html());
		});
		$(".totalAmount").html(parseFloat(totalAmount).toFixed(2));
		//Calculate Discount
		var totalProductDiscount = parseFloat(0);
		$("input[id^='productDiscount']").each(function () {
			var stringId = $(this).attr('id').substring(15, $(this).attr('id').length);
			len = $("#productDiscount" + stringId).val().length;
			total = ($("#productQuantity" + stringId).val() * $("#productPrice" + stringId).val());
			if ($("#productDiscount" + stringId).val().substring(len - 1, len) == "%") {
				totalProductDiscount += parseFloat(total * parseFloat($("#productDiscount" + stringId).val()) / 100);
			} else {
				totalProductDiscount += parseFloat($("#productDiscount" + stringId).val());
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
		if ($("#salesDiscount").val() == "") {
			$("#salesDiscount").val("0");
		}
		var salesDiscount = $("#salesDiscount").val();
		len = salesDiscount.length;
		var discountAmount = 0;
		if (salesDiscount.substring(len - 1) == "%") {
			discountAmount = parseFloat(totalAmount * (parseFloat(salesDiscount) / 100));
		} else {
			discountAmount = parseFloat(salesDiscount);
		}
		var totalDiscount = parseFloat(totalProductDiscount + discountAmount).toFixed(2);
		$(".totalDiscount").html(totalDiscount);
		var vat = $("#vat").val();
		if (vat == "") {
			vat = 0;
		}
		var ait = $("#ait").val();
		if (ait == "") {
			ait = 0;
		}
		//Calculate GrandTotal
		$(".grandTotal").html((parseFloat(totalAmount) + parseFloat(vat) + parseFloat(ait) - parseFloat(discountAmount)).toFixed(2));
		//$("#paid").val((totalAmount-discountAmount).toFixed(2));
		//Calculate Credit Limit
		//var customerCreditLimit = parseFloat($("#customerCreditLimit").val());
		var grandTotal = parseFloat($(".grandTotal").html());

    	/*if(customerCreditLimit < grandTotal){
            $("#check_out_cart").attr('disabled',true);	  
            $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> credit limit is already over. Change the quantity or update the limit to complete this voucher");
			$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
			  $(this).hide(); n();
			});
    	}else */if (parseFloat($("#productTotal" + id).html()) < 0 || parseFloat(grandTotal) < 0) {
			//$("#check_out_cart").attr('disabled',true);	  
			$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Discount cannot be greater then product price");
			$("#divErrorMsg").show().delay(10000).fadeOut().queue(function (n) {
				$(this).hide(); n();
			});
		} else if (parseFloat(vat) > parseFloat(totalAmount)) {
			$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> VAT cannot be greater then Total price");
			$("#divErrorMsg").show().delay(10000).fadeOut().queue(function (n) {
				$(this).hide(); n();
			});
		} else if (parseFloat(ait) > parseFloat(totalAmount)) {
			$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> AIT cannot be greater then Total price");
			$("#divErrorMsg").show().delay(10000).fadeOut().queue(function (n) {
				$(this).hide(); n();
			});
		} else {
			//$("#check_out_cart").attr('disabled',false);
		}
	}
	//check_out_cart
}

//Discount calculation when entry sales discount
function calculateTotalDiscount() {
	var totalAmount = parseFloat($(".totalAmount").html());
	var totalProductDiscount = parseFloat($(".totalProductDiscount").html());
	if ($("#salesDiscount").val() == "") {
		$("#salesDiscount").val("0");
	}
	var salesDiscount = $("#salesDiscount").val();
	var len = salesDiscount.length;
	var discountAmount = 0;
	if (salesDiscount.substring(len - 1) == "%") {
		discountAmount = parseFloat(totalAmount * parseFloat(salesDiscount) / 100);
	} else {
		discountAmount = parseFloat(salesDiscount);
	}

	var totalDiscount = parseFloat(totalProductDiscount + discountAmount).toFixed(2);

	$(".totalDiscount").html(totalDiscount);
	var vat = $("#vat").val();
	if (vat == "") {
		vat = 0;
	}
	var ait = $("#ait").val();
	if (ait == "") {
		ait = 0;
	}
	$(".grandTotal").html((parseFloat(totalAmount) + parseFloat(vat) + parseFloat(ait) - parseFloat(discountAmount)).toFixed(2));
	//$("#paid").val((totalAmount-discountAmount).toFixed(2));
	//var customerCreditLimit = parseFloat($("#customerCreditLimit").val());
	var grandTotal = parseFloat($(".grandTotal").html());

	/*if(customerCreditLimit < grandTotal){
        $("#check_out_cart").attr('disabled',true);	  
        $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> credit limit is already over. Change the quantity or update the limit to complete this voucher");
		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
		  $(this).hide(); n();
		});
	}else */if (parseFloat(grandTotal) < 0) {
		//$("#check_out_cart").attr('disabled',true);	  
		$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Discount cannot be greater then product price");
		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function (n) {
			$(this).hide(); n();
		});
	} else if (parseFloat(vat) > parseFloat(totalAmount)) {
		$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> VAT cannot be greater then Total price");
		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function (n) {
			$(this).hide(); n();
		});
	} else if (parseFloat(ait) > parseFloat(totalAmount)) {
		$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> AIT cannot be greater then Total price");
		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function (n) {
			$(this).hide(); n();
		});
	} else {
		$("#check_out_cart").attr('disabled', false);
	}
}


//Update Session
//function updateSession(id) {
function updateSession(id, product_type) {
	var product_price = $('#productPrice' + id + '').val();
	var product_quantity = $('#productQuantity' + id).val();
	var max_price = $('#productMaxPrice' + id).val();
	var min_price = $('#productMinPrice' + id).val();
	var product_discount = $('#productDiscount' + id).val();

	var action = "adjust";
	var grandTotal = 0;
	if ($(".grandTotal").html() == undefined) {
		grandTotal = 0;
	} else {
		grandTotal = parseFloat($(".grandTotal").html());
	}
	grandTotal += parseFloat(product_quantity) * parseFloat(product_price);
	var totalQuantity = $('#availableQuantity' + id).val();

	if (parseFloat(totalQuantity) < parseFloat(product_quantity)) {
		$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Sale quantity [" + product_quantity + "] cannot be greater then total available stock quantity[" + totalQuantity + "]");
		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function (n) {
			$(this).hide(); n();
		});
	}
	else {
		if (parseFloat(product_price) < parseFloat(min_price) && userType.toLowerCase() != "super admin" && userType.toLowerCase() != "admin support" && userType.toLowerCase() != "admin support plus") {
			$('#productPrice' + id + '').val(min_price);
			$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Sale price [" + product_price + "] cannot be smaller then minimum price [" + min_price + "]");
			$("#divErrorMsg").show().delay(10000).fadeOut().queue(function (n) {
				$(this).hide(); n();
			});
			product_price = min_price;
			calculateTotal(id);
		}
		$.ajax({
			url: "phpScripts/action.php",
			method: "POST",
			data: { id: id, product_discount: product_discount, product_price: product_price, product_quantity: product_quantity, product_limit: totalQuantity, action: action, product_type: product_type, serializeProductsId: serializeProductsId, serializeSaleQuantity: serializeSaleQuantity },
			dataType: 'json',
			success: function (data) {
				//load_cart_data();
			},
			error: function (xhr) {
				alert(xhr.responseText);
			}
		});
	}
}

$(document).on('click', '.delete', function () {
	var conMsg = confirm("Are you sure to delete??")
	if (conMsg) {
		//var product_id = $(this).attr("id");
		var id = $(this).attr("id");
		//var id=$("#"+product_id).val();
		//alert(id);
		var action = "remove";

		$.ajax({
			url: "phpScripts/action.php",
			method: "POST",
			data: { id: id, /*product_id:product_id, */action: action },
			success: function (data) {
				load_cart_data();
			},
			error: function (xhr) {
				alert(xhr.responseText);
			}
		});
	}
});

//Order to sale process
$(document).on('click', '#check_out_cart', function () {
	$('#form_addsales').bootstrapValidator({
		live: 'enabled',
		message: 'This value is not valid',
		submitButton: '$form_addsales button [type="Submit"]',
		submitHandler: function (validator, form, submitButton) {

			// Start Check Serialize Product Quantity 
			/* countLen = $('input[name*="checkSerialize"]').length;
			if (countLen > 0) {
				showSerializTable(0, 0, "checkSerializeTotalQuantity", "");
			} */
			// End Check Serialize Product Quantity 
			var salesDate = $("#salesDate").val();
			var customerId = $("#customerId").val();
			var customers = $("#customerName").val();
			var contactNo = $("#contactNo").val();
			var customerEmail = $("#customerEmail").val();
			var customerAddress = $("#customerAddress").val();
			var remarks = $("#remarks").val();
			var salesMan = $("#salesMan").val();
			var totalAmount = $(".totalAmount").html();
			if (totalAmount == "") {
				totalAmount = 0;
			}
			var totalProductDiscount = $(".totalProductDiscount").html();
			if (totalProductDiscount == "") {
				totalProductDiscount = 0;
			}
			var salesDiscount = $("#salesDiscount").val();
			if (salesDiscount == "") {
				salesDiscount = 0;
			}
			var totalDiscount = $(".totalDiscount").html();
			if (totalDiscount == "") {
				totalDiscount = 0;
			}
			var grandTotal = $(".grandTotal").html();
			if (grandTotal == "") {
				grandTotal = 0;
			}
			var paidAmount = $("#paid").val();
			if (paidAmount == "") {
				paidAmount = 0;
			}
			var paymentMethod = $("#paymentMethod").val();
			var vat = $("#vat").val();
			if (vat == "") {
				vat = 0;
			}
			var ait = $("#ait").val();
			if (ait == "") {
				ait = 0;
			}
			var carringCost = '0';
			var requisitionNo = '0';
			var wareHouse = $("#wareHouse").val();
			var productId = [];
			var productQuantity = [];
			var productPrice = [];
			var productDiscount = [];
			var productTotal = [];
			var warehouseId = [];
			var i = 0;
			var checkProduct = 0;
			$('input[id^="productId"]').each(function () {
				productId[i] = $(this).val() + "@!@";
				i = i + 1;
				checkProduct++;
			});
			i = 0;
			$('input[id^="warehouseId"]').each(function () {
				warehouseId[i] = $(this).val() + "@!@";
				i++;
			});
			i = 0;
			$('input[id^="productQuantity"]').each(function () {
				productQuantity[i] = $(this).val() + "@!@";
				i = i + 1;
			});
			i = 0;
			$('input[id^="productPrice"]').each(function () {
				productPrice[i] = $(this).val() + "@!@";
				i = i + 1;
			});
			i = 0;
			$('input[id^="productDiscount"]').each(function () {
				productDiscount[i] = $(this).val() + "@!@";
				i = i + 1;
			});
			i = 0;
			var errorProductTotal = 0;
			$('span[id^="productTotal"]').each(function () {
				productTotal[i] = $(this).html() + "@!@";
				if (parseFloat($(this).html()) < 0) {
					errorProductTotal = errorProductTotal + 1;
				}
				i = i + 1;
			});
			if (parseFloat(checkProduct) == 0) {
				$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Select atleast one product to sale");
				$("#divErrorMsg").show().delay(10000).fadeOut().queue(function (n) {
					$(this).hide(); n();
				});
			} else if (parseFloat(errorProductTotal) > 0 || parseFloat(grandTotal) < 0) {
				$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Discount cannot be greater then product price");
				$("#divErrorMsg").show().delay(10000).fadeOut().queue(function (n) {
					$(this).hide(); n();
				});
			} else if (parseFloat(vat) > parseFloat(totalAmount)) {
				$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> VAT cannot be greater then Total price");
				$("#divErrorMsg").show().delay(10000).fadeOut().queue(function (n) {
					$(this).hide(); n();
				});
			} else if (parseFloat(ait) > parseFloat(totalAmount)) {
				$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> AIT cannot be greater then Total price");
				$("#divErrorMsg").show().delay(10000).fadeOut().queue(function (n) {
					$(this).hide(); n();
				});
			} else if (customers == "" || contactNo == "") {
				$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Customer Must be add before check out cart.");
				$("#divErrorMsg").show().delay(10000).fadeOut().queue(function (n) {
					$(this).hide(); n();
				});
			}
			else {
				var action = "check_out_cart";
				var fd = new FormData();
				fd.append('salesDate', salesDate);
				fd.append('customerId', customerId);
				fd.append('customers', customers);
				fd.append('wareHouse', wareHouse);
				fd.append('contactNo', contactNo);
				fd.append('customerEmail', customerEmail);
				fd.append('customerAddress', customerAddress);
				fd.append('remarks', remarks);
				fd.append('salesMan', salesMan);
				fd.append('totalAmount', totalAmount);
				fd.append('totalProductDiscount', totalProductDiscount);
				fd.append('salesDiscount', salesDiscount);
				fd.append('totalDiscount', totalDiscount);
				fd.append('grandTotal', grandTotal);
				fd.append('paidAmount', paidAmount);
				fd.append('paymentMethod', paymentMethod);
				fd.append('vat', vat);
				fd.append('ait', ait);
				fd.append('carringCost', carringCost);
				fd.append('requisitionNo', requisitionNo);
				fd.append('productId', productId);
				fd.append('warehouseId', warehouseId);

				fd.append('productQuantity', productQuantity);
				fd.append('productPrice', productPrice);
				fd.append('productDiscount', productDiscount);
				fd.append('productTotal', productTotal);
				fd.append('type', "WalkinSale");
				fd.append('action', action);
				// Start Check Serialize Product Quantity 
				if (countLen > 0) {
					if (checkSerializeProductQuantity == false) {
						$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Please select serilize product qty properly.");
						$("#divErrorMsg").show().delay(10000).fadeOut().queue(function (n) {
							$(this).hide(); n();
						});
						return;
					}
				}
				// End Check Serialize Product Quantity 
				$.ajax({
					url: "phpScripts/action.php",
					method: "POST",
					data: fd,
					contentType: false,
					processData: false,
					dataType: 'json',
					success: function (data) {
						if (data.msg == "Success") {
							$('#check_out_cart').trigger("reset");
							checkProduct = 0;
							$("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> Sales Order Submitted");
							$("#divMsg").show().delay(2000).fadeOut().queue(function (n) {
								$(this).hide(); n();
							});
							$('#customerId').val('0');
							$("#customerName").val('');
							$("#customerEmail").val('');
							$("#customerAddress").val('');
							$("#remarks").val('');
							$("#contactNo").val('');
							salesReport(data.salesId, 'WalkinSale');
							//window.open('http://jafree.alitechbd.com/salesViewDetails.php?id='+data.salesId, '_blank');
							load_cart_data();
							loadWarehouseWiseProductsWithStock();
							//$("#check_out_cart").attr('disabled',false);
						} else {
							$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> " + data);
							$("#divErrorMsg").show().delay(10000).fadeOut().queue(function (n) {
								$(this).hide(); n();
							});
						}
					},
					error: function (xhr) {
						alert(JSON.stringify(xhr));
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

$(document).on('click', '.previousPriceSingle', function () {
	if ($("#customerId").val() == "") {
		$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> select customer first then change credit information");
		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function (n) {
			$(this).hide(); n();
		});
	} else {
		$("#divErrorMsg").hide();
		var customers = $("#customerId").val();
		var productId = $(this).attr("id").substring(1);
		var action = "previousPriceSingle";
		var fd = new FormData();
		fd.append('customers', customers);
		fd.append('productId', productId);
		fd.append('action', action);
		$.ajax({
			url: "phpScripts/action.php",
			method: "POST",
			data: fd,
			contentType: false,
			processData: false,
			beforeSend: function () {
				$('#loading').show();
			},
			success: function (data) {
				$("#previousSoldWiProductsView").modal('show');
				$("#previousWiProducts").html(data);
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

$(document).on('click', '.productSpecification', function () {
	if ($("#customers").val() == "") {
		$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> select customer first then change credit information");
		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function (n) {
			$(this).hide(); n();
		});
	} else {
		$("#divErrorMsg").hide();
		var customers = $("#customers").val();
		var productId = $(this).attr("id").substring(1);
		var action = "productSpecification";
		var fd = new FormData();
		fd.append('customers', customers);
		fd.append('productId', productId);
		fd.append('action', action);
		$.ajax({
			url: "phpScripts/whoseSaleAction.php",
			method: "POST",
			data: fd,
			contentType: false,
			processData: false,
			beforeSend: function () {
				$('#loading').show();
			},
			success: function (data) {
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
			url: 'phpScripts/action.php',
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