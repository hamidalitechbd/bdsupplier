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
		},
        error: function (response) {
			alert("Error! Try again please");
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

	//=========== Start Serialize Product ===========//
	var serializeProductsId = 0;
	var serializeSaleQuantity = 0;
	var countLen = 0;
	var checkSerializeProductQuantity = false;
	var TSProductsId = ''; 
	var checkSaleOrReturnTxt = '';  
	var tbl_serialize_productsIdArray = [];
	var stockQuantitiesSet = new Set();
	var tbl_serialize_productsIdSet = new Set();
	var newStockQuantitiesSet = new Set();

	function showSerializTable(id,TSProductId, warehouse_id, temTxt, product_id) {
		// id => tbl_tSalesId
		// warehouseId => warehouseId
		// product_id => salesProductId
		// TSProductId => TSProductsId
		TSProductsId = TSProductId
		var warehouseId = $("#wareHouse").val();
		$("#totalRemainingQuantity").text($("#totalRemainingQuantity_"+TSProductsId).val());
		$("#tbl_tSalesId").val(id);
		$("#tbl_product_id").val(product_id);
		$("#serializProductWarehouseId").val(warehouseId);
		let fd = new FormData();
		fd.append('matchQuantity', 0);
		fd.append('tbl_tSalesId', id);
		fd.append('product_id', product_id);
		fd.append('warehouseId', warehouseId);
		fd.append('action', "showSerializTable");
		$.ajax({
			url: "phpScripts/temporarySaleAdjustmentAction.php",
			method: "POST",
			data: fd,
			contentType: false,
			processData: false,
			dataType: "json",
			success: function (result) {
					// New
                    let uniqueId = id+"@"+product_id+"@"+warehouseId;
					let myData = [];
					let myData2 = [];
					
					// Check If Exist 
					for (item of stockQuantitiesSet.values()){
						if (item[0] == uniqueId) {
							 myData = item;
						}
					}
					for (newItem of newStockQuantitiesSet.values()){
						if (newItem[0] == uniqueId) {
							 myData2 = newItem;
							}
					}

					let len = myData.length;
					let len2 = myData2.length;
					// End Check If Exist
					let serializeProducts = result.serializeProducts;
					let rows = '';
					let newRows = '';
					let key = 0;
					let rowCount = 0;
					let totalQuantityForReturn = 0;
					let returnQuantity = 0;
					tbl_serialize_productsIdArray = [];
					serializeProducts.forEach(serializeProduct => {
						remainingQty = serializeProduct.quantity - serializeProduct.used_quantity;
						tbl_serialize_products_id = serializeProduct.id;
						tbl_serialize_productsIdArray[key] = serializeProduct.id;
						if (len > 0 && typeof myData[key+1] != 'undefined') {
							returnQuantity = myData[key+1];
						}
						totalQuantityForReturn +=  parseInt((returnQuantity));
						rows += '<tr><td>' + (key + 1) + '</td>' +
							'<td id="serializeRemainingQty_' + tbl_serialize_products_id + '">' + remainingQty + '</td>'+
                            '<td><input class="form-control only-number input-sm stockQuantity' + key +
							'" id="stockQuantity_' + tbl_serialize_products_id + '" type="text" name="stockQuantity" placeholder=" ... " required oninput="calculateTotalQuantity(this.value,' + product_id + ',' + warehouseId + ',' + tbl_serialize_products_id + ','+"'addReturnQty'"+')" value="' +returnQuantity + '"></td></tr>';
						key++;
						rowCount++;
					});

					// Check If New Data Exist
					if (len2 > 0) {
						myData2.forEach((mytempQty, index) => {
							rowCount++;
							if (index > 0) {
								totalQuantityForReturn += parseInt((mytempQty));
								newRows += '<tr class="bg-info" id="row' + rowCount + '">' +
										'<td>' + rowCount + '</td>' +
										'<td><input class="form-control input-sm stockQuantity' + rowCount +
										'" id="stockQuantity'+rowCount+'" type="text" name="newStockQuantity" placeholder=" Quantity... " required oninput="calculateTotalQuantity(this.value,'+rowCount+',0,0)" value="' + mytempQty + '"></td>'
										+'<td><td><a href="#" onclick="removeRow('+rowCount+')" style="color:red;"><i class="fa fa-trash"> </i> </a></td></td></tr>';
						}
						});
					}
					// End Check If New Data Exist
					$("#serializeProductReturnTable").html('');
					$("#serializeProductReturnTableNew").html('');
					$("#serializeProductReturnTable").html(rows);
					$("#serializeProductReturnTableNew").html(newRows);
					$("#serializeProductReturnModal").modal("show");
					$("#totalStockQuantity").text(totalQuantityForReturn);
			},
			beforeSend: function () {
				$('#loading').show();
			},
			complete: function () {
				$('#loading').hide();
			},
			error: function (response) {
				alert("ERRRROOOORRR");
				$("#serializeProductTable").text("Something Went Wrong.Please Try Again");
			}
		});
	}

    var stockQuantities = [];
	var newStockQuantities = [];
	var saleStockQuantities = [];
	var totalQuantity = 0; // Return Quantity + Sale Quantity
	var totalStockQuantity = 0; // Return Quantity
	var totalStockSaleQuantity = 0; // Sale Quantity
	function calculateTotalQuantity(returnedQty, product_id, warehouse_id, tblSerializeProductsId, saleOrReturn) {
		totalStockSaleQuantity = 0;
		totalStockQuantity = 0;
		var totalStockRemainingQuantity = $("#totalRemainingQuantity_"+TSProductsId).val() - ($("#saleQuantity_"+TSProductsId).val());
		var totalStockSaleQuantity = ($("#saleQuantity_"+TSProductsId).val());
		
		stockQuantities = $('input[name^=stockQuantity]').map(function(index, quantity) {
			return parseInt($(quantity).val()) ? parseInt($(quantity).val()) : 0 ;
		}).get();
		newStockQuantities = $('input[name^=newStockQuantity]').map(function(index, quantity) {
			return parseInt($(quantity).val()) ? parseInt($(quantity).val()) : 0 ;
		}).get();

		totalStockSaleQuantity = saleStockQuantities.reduce((a, b) => a + b, 0);
		totalStockQuantity = newStockQuantities.reduce((a, b) => a + b, 0) + stockQuantities.reduce((a, b) => a + b, 0);
        totalQuantity = totalStockSaleQuantity + totalStockQuantity;

		if (totalQuantity > totalStockRemainingQuantity) {
			if (tblSerializeProductsId > 0) {
                if (saleOrReturn == "addSaleQty") {
                    $("#saleStockQuantity_"+tblSerializeProductsId).val(0);
				}else{
                    $("#stockQuantity_"+tblSerializeProductsId).val(0); // addReturnQty
                } 
			} else{
				let serialNum = product_id;
				$("#stockQuantity"+serialNum).val(0);
			}
			stockQuantities = $('input[name^=stockQuantity]').map(function(index, quantity) {
				return parseInt($(quantity).val()) ? parseInt($(quantity).val()) : 0 ;
			}).get();
			newStockQuantities = $('input[name^=newStockQuantity]').map(function(index, quantity) {
				return parseInt($(quantity).val()) ? parseInt($(quantity).val()) : 0 ;
			}).get();

            totalStockSaleQuantity = saleStockQuantities.reduce((a, b) => a + b, 0);
		    totalStockQuantity = newStockQuantities.reduce((a, b) => a + b, 0) + stockQuantities.reduce((a, b) => a + b, 0);
            totalQuantity = totalStockSaleQuantity + totalStockQuantity;

			$("#divErrorMsgSerialize").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Quantity Not Avaiable!");
			$("#divErrorMsgSerialize").show().delay(3000).fadeOut().queue(function(n) {
			$(this).hide(); n();
			});
		}
		$("#totalStockQuantity").text(totalQuantity);
	}

	function confirmSerialzeProduct() {
		let tbl_tSalesId = $("#tbl_tSalesId").val();
		let tbl_product_id = $("#tbl_product_id").val();
		let warehouseId = $("#serializProductWarehouseId").val();
		//let uniqueId = tbl_product_id+"@"+warehouseId;
		let uniqueId = tbl_tSalesId+"@"+tbl_product_id+"@"+warehouseId;
		let tempStockQuantities = [uniqueId].concat(stockQuantities)
		let temptbl_serialize_productsIds = [uniqueId].concat(tbl_serialize_productsIdArray)
		let tempNewStockQuantities = [uniqueId].concat(newStockQuantities)

		for (item of stockQuantitiesSet.values()){
			if (item[0] == uniqueId) {
				stockQuantitiesSet.delete(item);
				newStockQuantitiesSet.delete(item);
			}
		}
		// For tbl_serialize_product ids
		for (idItem of tbl_serialize_productsIdSet.values()){
			if (idItem[0] == uniqueId) {
				tbl_serialize_productsIdSet.delete(idItem);
			}
		}
		// For New Insert
		for (item of newStockQuantitiesSet.values()){
			if (item[0] == uniqueId) {
				newStockQuantitiesSet.delete(item);
			}
		}

		stockQuantitiesSet.add(tempStockQuantities);
		tbl_serialize_productsIdSet.add(temptbl_serialize_productsIds);
		newStockQuantitiesSet.add(tempNewStockQuantities);
        $("#saleQuantity_"+TSProductsId).val(totalStockSaleQuantity);
        $("#returnQuantity_"+TSProductsId).val(totalStockQuantity);
		$("#serializeProductReturnModal").modal("hide");
        totalCalculation(TSProductsId);
	}

	function addRow() {
		let td = $('#serializeProductReturnTable tr:last-child td:first-child').html();
		let tdNew = $('#serializeProductReturnTableNew tr:last-child td:first-child').html();
		if (tdNew == undefined) {
			trId = isNaN(td) ? 1 : parseInt(td) + 1;
		}else{
			trId = parseInt(tdNew) + 1;
		}
		id = $("#serializProductId").val();
		warehouseId = $("#serializProductWarehouseId").val();
		let rows = '';
		rows += '<tr class="bg-info" id="row' + trId + '">' +
			'<td>' + trId + '</td>' +
			'<td><input class="form-control input-sm stockQuantity' + trId +
			'" id="stockQuantity'+trId+'" type="text" name="newStockQuantity" placeholder=" Quantity... " required oninput="calculateTotalQuantity(this.value,'+trId+',0,0)"></td>';
		rows +=
			'<td><a href="#" onclick="removeRow(' +
			trId + ')" style="color:red;"><i class="fa fa-trash"> </i> </a></td></tr>';
		$("#serializeProductReturnTableNew").append(rows);
	}

	function removeRow(rowNumber) {
		$('#row' + (rowNumber)).remove();
		$("#serializeProductReturnTable").find('tr').each(function(i, el) {
			$(el).find("td").eq(0).text(i + 1);
		});
		calculateTotalQuantity();
	}
//=========== End Serialize Product ===========//


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
		// Serialize Product
		 // Convert Set to Array
		 let stockQuantities = [];
		 let tbl_serialize_productsIds = [];
		 let newStockQuantities = [];
		 stockQuantitiesSet.forEach(stockQty => stockQuantities.push(stockQty));
		 tbl_serialize_productsIdSet.forEach(productId => tbl_serialize_productsIds.push(productId));
		 newStockQuantitiesSet.forEach(newQty => newStockQuantities.push(newQty));
		// End Convert Set to Array
		fd.append('stockQuantities',stockQuantities);
		fd.append('tbl_serialize_productsIds',tbl_serialize_productsIds);
		fd.append('newStockQuantities',newStockQuantities);
		// End Serialize Product
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
				console.log(data);
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