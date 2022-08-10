var countCartProduct = 1;
var manageSalesTable;
var adjCounter = 0;
$(document).ready(function () {
	// manage Shop table
	/*manageSalesTable = $("#manageSalesTable").DataTable({
		'ajax': 'phpScripts/whoseSaleAction.php?sortData='+$("#sortData").val(),
		'order': [],
		'dom': 'Bfrtip',
		'buttons': [
			'pageLength','copy', 'csv', 'pdf', 'print'
		],
		//language: {
		 //processing: "<i class='fa fa-spinner fa-spin' style='font-size:50px;color:green'></i>"
	  //},
	 // processing: true
	});*/
	loadCustomersSuppliers("customersWithCreditLimit");

});
function loadCustomersSuppliers(tblType) {
	var dataString = "tblType=" + tblType;
	$.ajax({
		type: 'GET',
		url: 'phpScripts/loadCustomerSupplier.php',
		data: dataString,
		dataType: 'json',
		beforeSend: function () {
			$('#loading').show();
		},
		success: function (response) {
			var len = response.length;
			$("#customers").empty();
			if (tblType == "customersWithCreditLimit") {
				$("#customersLimit").empty();
				$("#customersInitCreditLimit").empty();
			}
			//$("#edit_supplier").empty();
			for (var i = 0; i < len; i++) {
				var id = response[i]['id'];
				var partyName = response[i]['partyName'];
				$("#customers").append("<option value='" + id + "'>" + partyName + "</option>");
				if (tblType == "customersWithCreditLimit") {
					$("#customersLimit").append("<option value='" + id + "'>" + response[i]['creditLimit'] + "</option>");
					$("#customersInitCreditLimit").append("<option value='" + id + "'>" + response[i]['initialLimit'] + "</option>");
				}
			}
		},
		complete: function () {
			$('#loading').hide();
		},
		error: function (xhr) {
			alert(xhr.responseText);
		}
	});
}
/*$("#sortData").change(function() {
	manageSalesTable.ajax.url("phpScripts/whoseSaleAction.php?sortData="+$("#sortData").val()).load();
});*/
//$("#salesDate").val(new Date().toISOString().substring(0, 10));
$("#wareHouse").select2({
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
$("#transportName").select2({
	placeholder: "Select Transport",
	allowClear: true
});

$("#asWarehouse").select2({
	placeholder: "Select Warehouse",
	dropdownParent: $('#advanceProductSearch'),
	allowClear: true
});
function selectSalesMan(userId) {
	$("#salesMan").val(userId).trigger('change');
}
$("#add_products").on("change", function () {
	$modal = $('#myModal');
	if ($(this).val()) {
		$modal.modal('show');
	}
});
//previously saved cart data
load_cart_data();
function load_cart_data() {
	$.ajax({
		url: "phpScripts/whoseSaleAction.php",
		method: "POST",
		data: { fetchCart: 'action' },
		dataType: "json",
		success: function (data) {
			$('#cart_details').html(data.cart_details);
			$('.total_price').text(data.total_price);
			$('.badge').text(data.total_item);
			//checkSerializProductTotalQuantity();
			showSerializTable(0, 0, "checkSerializeTotalQuantity", "");
		},
		error: function (xhr) {
			alert(xhr.responseText);
		}
	});
}
//Clear all cart data
$(document).on('click', '#clear_cart', function () {
	var action = 'empty';
	$.ajax({
		url: "phpScripts/whoseSaleAction.php",
		method: "POST",
		data: { action: action },
		beforeSend: function () {
			$('#loading').show();
		},
		success: function () {
			load_cart_data();
			$('#cart-popover').popover('hide');
			$("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> Your Cart has been clear");
			$("#divMsg").show().delay(2000).fadeOut().queue(function (n) {
				$(this).hide(); n();
			});
		}, complete: function () {
			$('#loading').hide();
		},
		error: function (xhr) {
			alert(xhr.responseText);
		}
	});
});
//Cart data added
$(document).on('click', '.add_to_cart', function () {
	if ($("#customers").val() == "") {
		$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> select customer first then change credit information");
		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function (n) {
			$(this).hide(); n();
		});
	} else {
		var product_id = $(this).attr("id");
		var totalQuantity = $('#totalStock' + product_id).html();
		/*$("#availableQuantity"+product_id).val($('#totalStock'+product_id).html());
		var totalQuantity = $("#availableQuantity"+product_id).val();*/
		var sale_quantity = $('#productQuantity' + product_id).val();
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
			var min_price = $('#min_price' + product_id + '').val();
			var max_price = $('#max_price' + product_id + '').val();
			var warehouse_id = $('#wareHouse').val();
			var warehouse_name = $("#wareHouse option:selected").text();
			var product_quantity = $('#quantity' + product_id).val();
			var product_discount = '0';
			var action = "add";
			var grandTotal = 0;
			if ($(".grandTotal").html() == undefined) {
				grandTotal = 0;
			} else {
				grandTotal = parseFloat($(".grandTotal").html());
			}
			grandTotal += parseFloat(product_price) * parseFloat(product_quantity);
			var customerCreditLimit = parseFloat($("#customerCreditLimit").val());
			if (parseFloat(customerCreditLimit) >= parseFloat(grandTotal)) {
				if (product_quantity > 0) {
					$.ajax({
						url: "phpScripts/whoseSaleAction.php",
						method: "POST",
						data: { product_id: product_id, product_name: product_name, product_discount: product_discount, product_price: product_price, min_price: min_price, max_price: max_price, product_quantity: product_quantity, product_limit: totalQuantity, warehouse_id: warehouse_id, warehouse_name: warehouse_name, action: action },
						dataType: 'json',
						success: function (data) {
							if (data.count > 0) {
								$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Quantity must be less then available 2");
								$("#divErrorMsg").show().delay(10000).fadeOut().queue(function (n) {
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
				}
			} else {
				$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Credit limit already over CCT: " + customerCreditLimit + " GT: " + grandTotal);
				$("#divErrorMsg").show().delay(10000).fadeOut().queue(function (n) {
					$(this).hide(); n();
				});
			}
		} else {
			$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong>Product: " + product_name + " Sale quantity [" + sale_quantity + "] cannot be greater then total available stock quantity[" + totalQuantity + "]");
			$("#divErrorMsg").show().delay(10000).fadeOut().queue(function (n) {
				$(this).hide(); n();
			});
		}
	}
});
function add_to_cart(product_id, product_name, product_price, min_price, max_price, product_quantity, warehouse_id, warehouse_name) {
	var msg = 0;
	/*if(parseInt($("#maxRowId").val()) > 0){
		countCartProduct = parseInt($("#maxRowId").val());
		countCartProduct++;
	}else{
		countCartProduct = 1;
	}
	var id = countCartProduct++;*/
	var totalQuantity = $('#totalStock' + product_id).html();
	var sale_quantity = $('#productQuantity' + product_id).val();
	if ($('#productQuantity' + product_id).val() == undefined) {
		sale_quantity = 0;
	}
	if ($("#customers").val() == "") {
		$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> select customer first then change credit information");
		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function (n) {
			$(this).hide(); n();
		});
		$("#divASErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> select customer first then change credit information");
		$("#divASErrorMsg").show().delay(10000).fadeOut().queue(function (n) {
			$(this).hide(); n();
		});
	}
	else {
		$("#divErrorMsg").hide();
		$("#divASErrorMsg").hide();
		var product_discount = '0';
		var action = "add";
		var grandTotal = 0;
		if ($(".grandTotal").html() == undefined) {
			grandTotal = 0;
		} else {
			grandTotal = parseFloat($(".grandTotal").html()); //Before adding new product
		}
		grandTotal += parseFloat(product_price) * parseFloat(product_quantity); // Adding new product
		var customerCreditLimit = parseFloat($("#customerCreditLimit").val());
		if (parseFloat(customerCreditLimit) >= parseFloat(grandTotal)) {
			if (product_quantity > 0) {
				$.ajax({
					url: "phpScripts/whoseSaleAction.php",
					method: "POST",
					data: { product_id: product_id, product_name: product_name, product_discount: product_discount, product_price: product_price, min_price: min_price, max_price: max_price, product_quantity: product_quantity, grandTotal: grandTotal, warehouse_id: warehouse_id, warehouse_name: warehouse_name, action: action },
					dataType: 'json',
					success: function (data) {
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
								showSerializTable(productId, warehouseId, '', currentStockId);
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
		} else {
			$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Credit limit already over CCT: " + customerCreditLimit + " GT: " + grandTotal);
			$("#divErrorMsg").show().delay(10000).fadeOut().queue(function (n) {
				$(this).hide(); n();
			});

			$("#divASErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Credit limit already over CCT: " + customerCreditLimit + " GT: " + grandTotal);
			$("#divASErrorMsg").show().delay(2000).fadeOut().queue(function (n) {
				$(this).hide(); n();
			});
		}
	}

}
var serializeProductsId = 0;
var serializeSaleQuantity = 0;
var countLen = 0;
var checkSerializeProductQuantity = false;

/* function checkSerializProductTotalQuantity() {
	countLen = $('input[name*="checkSerialize"]').length;
	if (countLen > 0) {
		showSerializTable(0, 0, "checkSerializeTotalQuantity", "");
	}
} */

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
		url: "phpScripts/whoseSaleAction.php",
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


$(document).on('click', '.delete', function () {
	var conMsg = confirm("Are you sure to delete??");
	if (conMsg) {
		var id = $(this).attr("id");
		//var id=product_id;
		//alert("Id = "+id);
		var action = "remove";
		$.ajax({
			url: "phpScripts/whoseSaleAction.php",
			method: "POST",
			data: { id: id, /*product_id:product_id, */action: action },
			beforeSend: function () {
				$('#loading').show();
			},
			success: function (data) {
				load_cart_data();
			}, complete: function () {
				$('#loading').hide();
			},
			error: function (xhr) {
				alert(xhr.responseText);
			}
		});
	}
});

//Calculate total amount
function calculateTotal(id) {
	if ($("#customers").val() == "") {
		$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> select customer first then change credit information");
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
	} else if (parseFloat($("#productQuantity" + id).val()) == 0 || $("#productQuantity" + id).val() == "") {
		$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Product Quantity Cannot be 0(zero) or blank");
		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function (n) {
			$(this).hide(); n();
		});
		load_cart_data();
	}
	else {
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
			//alert(stringId);
			len = $("#productDiscount" + stringId).val().length;
			//var productId = $(this).attr("id").substring(15);
			total = ($("#productQuantity" + stringId).val() * $("#productPrice" + stringId).val());
			if ($("#productDiscount" + stringId).val().substring(len - 1, len) == "%") {
				totalProductDiscount += parseFloat(total * parseFloat($("#productDiscount" + stringId).val()) / 100);
			} else {
				totalProductDiscount += parseFloat($("#productDiscount" + stringId).val());
			}
			// alert(totalProductDiscount);
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
		var carringCost = $("#carringCost").val();
		if (carringCost == "") {
			carringCost = 0;
		}
		var vat = $("#vat").val();
		if (vat == "") {
			vat = 0;
		}
		var ait = $("#ait").val();
		if (ait == "") {
			ait = 0;
		}
		$(".grandTotal").html((totalAmount + parseFloat(vat) + parseFloat(ait) + parseFloat(carringCost) - discountAmount).toFixed(2));

		//Calculate GrandTotal
		//$(".grandTotal").html((totalAmount+parseFloat(carringCost)-discountAmount).toFixed(2));
		//Calculate Credit Limit
		var customerCreditLimit = parseFloat($("#customerCreditLimit").val());
		var grandTotal = parseFloat($(".grandTotal").html());
		if (customerCreditLimit < grandTotal) {
			$("#check_out_cart").attr('disabled', true);
			$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> credit limit is already over. Change the quantity or update the limit to complete this voucher");
			$("#divErrorMsg").show().delay(10000).fadeOut().queue(function (n) {
				$(this).hide(); n();
			});
		} else if (parseFloat($("#productTotal" + id).html()) < 0 || parseFloat($(".grandTotal").html()) < 0) {
			$("#check_out_cart").attr('disabled', true);
			$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Discount cannot be greater then product price");
			$("#divErrorMsg").show().delay(10000).fadeOut().queue(function (n) {
				$(this).hide(); n();
			});
		}
		else {
			$("#check_out_cart").attr('disabled', false);
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
	var carringCost = $("#carringCost").val();
	if (carringCost == "") {
		carringCost = 0;
	}
	var vat = $("#vat").val();
	if (vat == "") {
		vat = 0;
	}
	var ait = $("#ait").val();
	if (ait == "") {
		ait = 0;
	}
	$(".grandTotal").html((totalAmount + parseFloat(vat) + parseFloat(ait) + parseFloat(carringCost) - discountAmount).toFixed(2));

	var customerCreditLimit = parseFloat($("#customerCreditLimit").val());
	var grandTotal = parseFloat($(".grandTotal").html());
	if (parseFloat(customerCreditLimit) < parseFloat(grandTotal)) {
		$("#check_out_cart").attr('disabled', true);
		$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> credit limit is already over. Change the quantity or update the limit to complete this voucher");
		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function (n) {
			$(this).hide(); n();
		});
	} else if (parseFloat(grandTotal) < 0) {
		$("#check_out_cart").attr('disabled', true);
		$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Discount cannot be greater then product price");
		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function (n) {
			$(this).hide(); n();
		});
	} else if (parseFloat(vat) > parseFloat(totalAmount)) {
		$("#check_out_cart").attr('disabled', true);
		$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> VAT cannot be greater then Total price");
		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function (n) {
			$(this).hide(); n();
		});
	} else if (parseFloat(ait) > parseFloat(totalAmount)) {
		$("#check_out_cart").attr('disabled', true);
		$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> AIT cannot be greater then Total price");
		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function (n) {
			$(this).hide(); n();
		});
	}
	else {
		$("#check_out_cart").attr('disabled', false);
	}
}

//Split whole sale customer credit limit
$("#customers").change(function () {
	$("#customersLimit").val($("#customers").val());
	$("#customersInitCreditLimit").val($("#customers").val());
	var str = $("#customersLimit option:selected").text();
	var strInitial = $("#customersInitCreditLimit option:selected").text();
	//var res = str.split("__");
	if (str != "~~ Select Supplier to credit limit ~~") {
		$("#customerCreditLimit").val(str);
	} else {
		$("#customerCreditLimit").val("0");
	}
	if (strInitial != "~~ Select Supplier to credit limit ~~") {
		$("#customerInitialLimit").val(strInitial);
	} else {
		$("#customerInitialLimit").val("0");
	}
	if (parseFloat($("#customerCreditLimit").val()) < parseFloat($(".grandTotal").html())) {
		$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> This customer doesnot have enough credit.");
		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function (n) {
			$(this).hide(); n();
		});
		$("#check_out_cart").attr('disabled', true);
	} else {
		$("#check_out_cart").attr('disabled', false);
	}
});

//Load Stock for warehouse change
$("#wareHouse").change(function () {
	loadWarehouseWiseProductsWithStock();
});
function loadWarehouseWiseProductsWithStock() {
	var dataString = "wareHouseId=" + $("#wareHouse").val() + "&type=Party";
	$.ajax({
		type: 'POST',
		url: 'phpScripts/manageSaleProductsView.php',
		data: dataString,
		beforeSend: function () {
			$('#loading').show();
		},
		success: function (response) {
			$("#results").html(response);
		}, complete: function () {
			$('#loading').hide();
		}, error: function (xhr) {
			alert(xhr.responseText);
		}
	});
}

//Update Session
function updateSession(id, product_type) {
	var product_price = $('#productPrice' + id + '').val();
	var max_price = $('#productMaxPrice' + id).val();
	var min_price = $('#productMinPrice' + id).val();
	var product_quantity = $('#productQuantity' + id).val();
	var product_discount = $('#productDiscount' + id).val();
	var productName = "";
	/* var productName = $('#productName'+product_id).val();
		 alert(productName);*/
	var action = "adjust";
	var grandTotal = 0;
	if ($(".grandTotal").html() == undefined) {
		grandTotal = 0;
	} else {
		grandTotal = parseFloat($(".grandTotal").html());
	}

	// Serialize Product
	if (product_type == true) {
		product_type = true;
	}

	//alert(grandTotal);
	//grandTotal += parseFloat(product_quantity)*parseFloat(product_price);
	//alert("PQ: "+product_quantity+" PP: "+product_price+" ="+grandTotal);
	var customerCreditLimit = parseFloat($("#customerCreditLimit").val());
	var totalQuantity = $('#availableQuantity' + id).val();
	//alert(totalQuantity+ " " +product_quantity);
	if (parseFloat(totalQuantity) < parseFloat(product_quantity)) {
		$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong>Product: " + productName + " Sale quantity [" + product_quantity + "] cannot be greater then total available stock quantity[" + totalQuantity + "]");
		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function (n) {
			$(this).hide(); n();
		});
	} else if (parseFloat(product_quantity) == 0 || product_quantity == "") {
		$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Product Quantity cannot be 0(zero)");
		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function (n) {
			$(this).hide(); n();
		});
		$('#productQuantity' + id + '').val(1);
		calculateTotal(id);
	}
	else if (parseFloat(customerCreditLimit) >= parseFloat(grandTotal)) {
		if (parseFloat(product_price) < parseFloat(max_price) && userType.toLowerCase() != "super admin" && userType.toLowerCase() != "admin support" && userType.toLowerCase() != "admin support plus") {
			$('#productPrice' + id + '').val(max_price);
			$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Sale price [" + product_price + "] cannot be smaller then minimum price [" + min_price + "]");
			$("#divErrorMsg").show().delay(10000).fadeOut().queue(function (n) {
				$(this).hide(); n();
			});
			product_price = max_price;
			calculateTotal(id);
		}
		$.ajax({
			url: "phpScripts/whoseSaleAction.php",
			method: "POST",
			data: { id: id, product_discount: product_discount, product_price: product_price, product_quantity: product_quantity, product_limit: totalQuantity, action: action, product_type: product_type, serializeProductsId: serializeProductsId, serializeSaleQuantity: serializeSaleQuantity },
			dataType: 'json',
			beforeSend: function () {
				//$('#loading').show();
				adjCounter++;
			}, success: function (data) {
				//alert(JSON.stringify(data.productType));
				//load_cart_data();
				//alert(data.ddd);
				if (data.productType == "serialize") {
					//showSerializTable(data.productId, data.warehouseId, '',data.currentStockId);
				}
			}, complete: function () {
				adjCounter--;
				//$('#loading').hide();
			},
			error: function (xhr) {
				alert(JSON.stringify(xhr));
			}
		});
		//alert(product_quantity);

	} else {
		$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Credit limit already over CCT: " + customerCreditLimit + " GT: " + grandTotal);
		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function (n) {
			$(this).hide(); n();
		});
	}

}

//Order to whole sale process

$(document).on('click', '#finalSaleConfirm', function () {
	var rowId = [];
	var updateWarehouse = [];
	var sales = $("#sales").val();
	var salesProduct = $("#salesProduct").val();
	var vouchers = $("#vouchers").val();
	var i = 0;
	$('input[id^="rowId"]').each(function () {
		rowId[i] = $(this).val();
		updateWarehouse[i] = $("#updateWarehouse" + rowId[i]).val();
		i++;
	});
	var fd = new FormData();
	fd.append('sales', sales);
	fd.append('salesProduct', salesProduct);
	fd.append('vouchers', vouchers);
	fd.append('rowId', rowId);
	fd.append('updateWarehouse', updateWarehouse);
	fd.append('action', "FinalSalesConfirmation");
	$.ajax({
		url: "phpScripts/whoseSaleAction.php",
		method: "POST",
		data: fd,
		contentType: false,
		processData: false,
		dataType: 'json',
		beforeSend: function () {
			$('#loading').show();
		},
		success: function (data) {
			if (data.msg == "Success") {
				salesReport(data.salesId, 'PartySale');
				//window.open('https://jafree.alitechbd.com/wholesalesViewDetails.php?id='+data.salesId, '_blank');
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
				$("#salesconfirmation").modal('hide');
			} else {
				$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> " + data);
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
})


$(document).on('click', '#check_out_cart', function () {
	if ($("#customers").val() == "") {
		$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> select customer first then change credit information");
		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function (n) {
			$(this).hide(); n();
		});
	} else if (adjCounter > 0) {
		$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Please try again. Click check out cart button again");
		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function (n) {
			$(this).hide(); n();
		});
	} else if ($('#check_out_cart').attr('disabled')) {
		$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Disabled button cannot be accessed");
		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function (n) {
			$(this).hide(); n();
		});
	}
	else {
		var salesDate = $("#salesDate").val();
		var customers = $("#customers").val();
		var salesMan = $("#salesMan").val();
		var totalAmount = $(".totalAmount").html();
		if (totalAmount == "") {
			totalAmount = 0;
		}
		var totalProductDiscount = $(".totalProductDiscount").html();
		if (totalProductDiscount == 0) {
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
		var projectName = $("#projectName").val();
		var remarks = $("#remarks").val();
		var transport = $("#transportName").val();
		var carringCost = $("#carringCost").val();
		var requisitionNo = $("#requisitionNo").val();
		var wareHouse = $("#wareHouse").val();
		var productId = [];
		var productQuantity = [];
		var productPrice = [];
		var productDiscount = [];
		var productTotal = [];
		var warehouse_id = [];
		var i = 0;
		var checkProduct = 0;
		var errorProductAvailableQuantity = 0;
		var errorMsgProductAvailability = "";
		$('input[id^="productId"]').each(function () {
			productId[i] = $(this).val() + "@!@";
			var product_idData = $(this).val();
			if (parseFloat($('#totalStock' + product_idData).html()) < parseFloat($('#productQuantity' + product_idData).val())) {
				errorProductAvailableQuantity++;
				errorMsgProductAvailability += $('#productName' + product_idData).val() + ", ";
			}
			i = i + 1;
			checkProduct++;
		});
		i = 0;
		$('input[id^="warehouseId"]').each(function () {
			warehouse_id[i] = $(this).val() + "@!@";
			i = i + 1;
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
		i = 0;
		if (parseFloat(checkProduct) == 0) {
			$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Select atleast one product to sale");
			$("#divErrorMsg").show().delay(10000).fadeOut().queue(function (n) {
				$(this).hide(); n();
			});
		} else if (parseFloat(errorProductTotal) > 0 || parseFloat(grandTotal) < 0) {
			$("#check_out_cart").attr('disabled', true);
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
		} else if (parseFloat(errorProductAvailableQuantity) > 0) {
			$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong>" + errorMsgProductAvailability + " Sale quantity cannot be greater then total available stock quantity");
			$("#divErrorMsg").show().delay(10000).fadeOut().queue(function (n) {
				$(this).hide(); n();
			});
		}
		else {
			var action = "check_out_cart";
			var fd = new FormData();
			fd.append('salesDate', salesDate);
			fd.append('customers', customers);
			fd.append('wareHouse', wareHouse);
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
			fd.append('projectName', projectName);
			fd.append('remarks', remarks);
			fd.append('transport', transport);
			fd.append('carringCost', carringCost);
			fd.append('requisitionNo', requisitionNo);
			fd.append('productId', productId);
			fd.append('warehouse_id', warehouse_id);
			fd.append('productQuantity', productQuantity);
			fd.append('productPrice', productPrice);
			fd.append('productDiscount', productDiscount);
			fd.append('productTotal', productTotal);
			fd.append('type', "PartySale");
			fd.append('action', action);
			// Start Check Serialize Product Quantity 
			countLen = $('input[name*="checkSerialize"]').length;
			if (countLen > 0) {
				showSerializTable(0, 0, "checkSerializeTotalQuantity", "");
				/* if (checkSerializeProductQuantity == false) {
					$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Please select serilize product qty properly.");
					$("#divErrorMsg").show().delay(10000).fadeOut().queue(function (n) {
						$(this).hide(); n();
					});
					return;
				} */
			}
			// End Check Serialize Product Quantity 
			$.ajax({
				url: "phpScripts/whoseSaleAction.php",
				method: "POST",
				data: fd,
				contentType: false,
				processData: false,
				dataType: 'json',
				beforeSend: function () {
					$('#loading').show();
				},
				success: function (data) {
					if (data.msg == "Success") {
						$("#sales").val(data.sales);
						$("#salesProduct").val(data.salesProduct);
						$("#vouchers").val(data.vouchers);
						var offerProductWarehouseData = "";
						for (var i = 0; i < data.salesProduct.length; i++) {
							if (data.salesProduct[i][9] == '0') {
								offerProductWarehouseData += "<table class='table table-bordered'><tr><th>Product name</th><th> Quantity</th><th>Wharehouse</th></tr><tr><td><input type='hidden' name='rowId" + data.salesProduct[i][10] + "' id='rowId" + data.salesProduct[i][10] + "' value='" + data.salesProduct[i][10] + "'>" + data.salesProduct[i][11] + "</td><td>" + data.salesProduct[i][1] + "</td><td><select class='form-control' id='updateWarehouse" + data.salesProduct[i][10] + "' name='updateWarehouse" + data.salesProduct[i][10] + "'>" + data.salesProduct[i][12] + "</td></tr></table>";
							}
						}
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
						$("#offerProductWarehouse").html(offerProductWarehouseData);
						$("#salesconfirmation").modal('show');

					} else {
						$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> " + data);
						$("#divErrorMsg").show().delay(10000).fadeOut().queue(function (n) {
							$(this).hide(); n();
						});
						//alert(data);
					}
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

$(document).on('click', '#btn_previousPrice', function () {
	if ($("#customers").val() == "") {
		$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> select customer first then change credit information");
		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function (n) {
			$(this).hide(); n();
		});
	} else {
		$("#divErrorMsg").hide();
		var customers = $("#customers").val();
		var productId = [];
		var i = 0;
		var checkProduct = 0;
		$('input[id^="productId"]').each(function () {
			productId[i] = $(this).val() + "@!@";
			i = i + 1;
			checkProduct++;
		});
		if (parseFloat(checkProduct) == 0) {
			$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Select atleast one product to sale");
			$("#divErrorMsg").show().delay(10000).fadeOut().queue(function (n) {
				$(this).hide(); n();
			});
		}
		else {
			var action = "previousPrice";
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

$(document).on('click', '.previousPriceSingle', function () {
	if ($("#customers").val() == "") {
		$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> select customer first then change credit information");
		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function (n) {
			$(this).hide(); n();
		});
	} else {
		$("#divErrorMsg").hide();
		var customers = $("#customers").val();
		var productId = $(this).attr("id").substring(1);
		var action = "previousPriceSingle";
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

$(document).on('click', '.discountOffer12', function () {

	var productId = $(this).attr("id").substring(1);
	var action = "discountOffer";
	var fd = new FormData();
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
});

function discountOffer(type, productId) {
	var action = "discountOffer";
	var fd = new FormData();
	fd.append('productId', productId);
	fd.append('type', type);
	fd.append('action', action);
	$.ajax({
		url: "phpScripts/discountOfferAction.php",
		method: "POST",
		data: fd,
		contentType: false,
		processData: false,
		beforeSend: function () {
			$('#loading').show();
		},
		success: function (data) {
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

function discountOfferPreview() {
	var action = "discountOfferPreview";
	var fd = new FormData();
	fd.append('action', action);
	fd.append('type', 'Party');
	$.ajax({
		url: "phpScripts/discountOfferAction.php",
		method: "POST",
		data: fd,
		contentType: false,
		processData: false,
		beforeSend: function () {
			$('#loading').show();
		},
		success: function (data) {
			//alert(data);
			$("#discountOfferModalDetails").modal('show');
			$("#discountOfferDetailsPreview").html(data);
		},
		complete: function () {
			$('#loading').hide();
		},
		error: function (xhr) {
			alert("xhr error: " + xhr.responseText);
		}
	});

}

/*------------------ Start Delete walkin sales, sales products and sales voucher (if exists) ---------------------- */
/*function deleteSales(salesId){
	var conMsg = confirm("Are you sure to delete??");
	if(conMsg){
		$.ajax({
			type: 'POST',
			url: 'phpScripts/whoseSaleAction.php',
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
	}, 2500); // <-- time in milliseconds*/

