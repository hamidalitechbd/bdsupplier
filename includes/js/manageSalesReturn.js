var salesType = $('#salesType').val();
if(salesType == 'PartySale'){
    //loadCustomersSuppliers("Suppliers");
    /*$("#customerId").select2({
		placeholder: "Select Customer Name",
		allowClear: true
	});*/
}

$("#returnDate").blur(function() {
    var returnDate = new Date($('#returnDate').val());
    var salesDate = new Date($('#salesDate').val());
    if(returnDate < salesDate){
        $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Sales return date cannot be smaller then sales date");
    	$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
    	  $(this).hide(); n();
    	});
    }else{
        $("#divErrorMsg").hide();
    }
});
var manageSalesReturnTable
$(document).ready(function() {
	manageSalesReturnTable = $("#manageSalesReturnTable").DataTable({
		'ajax': 'phpScripts/salesReturnAction.php?salesType='+salesType+"&sortData="+$("#sortData").val(),
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
});
$("#sortData").change(function() {
    manageSalesReturnTable.ajax.url("phpScripts/salesReturnAction.php?salesType="+salesType+"&sortData="+$("#sortData").val()).load();
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
function loadSales(salesId, salesType){
	var dataString = 'action=loadSales&id='+salesId+'&type='+salesType;
	$.ajax({
		type: 'POST',
		url: 'phpScripts/salesReturnAction.php',
		data: dataString,
		dataType: 'json',
		success: function(response){
			$('#salesCode').val(response[0].salesOrderNo);
			if(salesType == 'PartySale'){
			    $('#customerId').val(response[0].tbl_customerId);
			}else if(salesType == 'WalkinSale'){
			    $('#customerId').val(response[0].tbl_customerId);
			    $('#customerName').val(response[0].partyName);
			}else if(salesType == 'TS'){
			    $('#customerId').val(response[0].tbl_customerId);
			}
			$('#salesDate').val(response[0].salesDate);
			$('#requisitionNo').val(response[0].requisitionNo);
			var productHTML = "<table><tr><th>SN#</th><th>Product Name</th><th>Warehouse</th><th>Quantity</th><th style='width: 8%;'>Returned Quantity</th><th style='width: 11%;'>Return Quantity</th><th style='width: 8%;'>Remaining Quantity</th><th>Price</th><th>Discount</th><th>Total</th><th>Action</th></tr></table>";
			for(var i=0;i<response.length;i++){
				// For Serialize 
				let showBtn = '';
				let disabled = '';
				let input = '';
				var remainingQuantity = response[i].quantity - response[i].returnedQuantity;
				if (response[i].type == "serialize") {
					disabled = 'disabled';
					input = '<input type="hidden" id="totalRamainingQuanitity_'+i+'" name="totalRamainingQuanitity" value='+remainingQuantity+'>';
					//showBtn = "<button class='btn btn-primary btn-sm btn-flat' type='button' "+'onclick="showSerializTable('+response[i].tbl_sales_id+','+response[i].tbl_wareHouseId+','+i+','+response[i].tbl_productsId+')"'+"> <i class='fa fa-eye'></i> </button> ";
					showBtn = "<i style='cursor: pointer;' class='fa fa-eye btn-primary btn-xs ' "+'onclick="showSerializTable('+response[i].tbl_sales_id+','+response[i].tbl_wareHouseId+','+i+','+response[i].tbl_productsId+')"'+"></i> ";
				}
				// End
				productHTML += "<tr id='productRow"+i+"'><td>"+input+""+(i+1)+"<input type='hidden' name='salesProductsId["+i+"]' value="+response[i].salesProductId+" /></td><td>"+response[i].productName+" - "+response[i].productCode+"</td><td>"+response[i].wareHouseName+"</td><td style='text-align: center;'><span id='quantity"+i+"' name='quantity["+i+"]'>"+response[i].quantity+"</td><td style='text-align: center;'><span id='returnedQuantity"+i+"' name='returnedQuantity["+i+"]'>"+response[i].returnedQuantity+"</span></td><td><input style='width: 70%;' type='text' id='returnQuantity"+i+"' name='returnQuantity["+i+"]' value='0'  onkeyup='calculateTotal("+i+")' "+disabled+" />"+showBtn+"</td><td style='text-align: center;'><span id='remainingQuantity"+i+"' name='remainingQuantity["+i+"]'>"+remainingQuantity+"</span></td><td>"+response[i].salesAmount+"</td><td>"+response[i].discount+"</td><td>"+response[i].grandTotal+"</td><td><a href='#'  class='btn btn-danger btn-sm btn-flat' onclick='removeRows(" + i + ")'><i class='fa fa-trash'></i></a></td></tr>";
			}
			$('#manageSalesReturnTable').html(productHTML);
		},
		error: function (xhr) {
			//alert(xhr.responseText);
			$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error 2! </strong>"+xhr.responseText);
        	$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
        	  $(this).hide(); n();
        	});
		}
	});
}

	//=========== Start Serialize Product ===========//
	var productRowNum = '';  
	var tbl_serialize_productsIdArray = [];
	var stockQuantitiesSet = new Set();
	var tbl_serialize_productsIdSet = new Set();
	var newStockQuantitiesSet = new Set();
	function showSerializTable(id, warehouseId, temTxt, product_id) {
		// id => tbl_sales_id
		// warehouseId => warehouseId
		// product_id => salesProductId
		productRowNum = temTxt // tr number
		let totalRamainingQuanitity = parseInt($("#totalRamainingQuanitity_"+productRowNum).val());
		$("#totalRemainingQuantity").text(totalRamainingQuanitity);
		$("#tbl_sales_id").val(id);
		$("#tbl_product_id").val(product_id);
		$("#serializProductWarehouseId").val(warehouseId);
		let fd = new FormData();
		fd.append('matchQuantity', 0);
		fd.append('tbl_sales_id', id);
		fd.append('product_id', product_id);
		fd.append('warehouseId', warehouseId);
		fd.append('action', "showSerializTable");
		$.ajax({
			url: "phpScripts/salesReturnAction.php",
			method: "POST",
			data: fd,
			contentType: false,
			processData: false,
			dataType: "json",
			success: function (result) {
					// New
					let uniqueId = product_id+"@"+warehouseId;
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
					let saleQuantity = 0;
					tbl_serialize_productsIdArray = [];
					serializeProducts.forEach(serializeProduct => {
						remainingQty = serializeProduct.quantity - serializeProduct.used_quantity;
						tbl_serialize_products_id = serializeProduct.id;
						tbl_serialize_productsIdArray[key] = serializeProduct.id;
						if (len > 0 && typeof myData[key+1] != 'undefined') {
							saleQuantity = myData[key+1];
						}
						totalQuantityForReturn += parseInt((saleQuantity));
						rows += '<tr><td>' + (key + 1) + '</td>' +
							'<td id="serializeRemainingQty_' + tbl_serialize_products_id + '">' + remainingQty + '</td><td><input class="form-control only-number input-sm stockQuantity' + key +
							'" id="stockQuantity_' + tbl_serialize_products_id + '" type="text" name="stockQuantity" placeholder=" ... " required oninput="calculateTotalQuantity(this.value,' + product_id + ',' + warehouseId + ',' + tbl_serialize_products_id + ')" value="' + saleQuantity + '"></td></tr>';
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
					$("#serializeProductReturnTable").html(rows);
					$("#serializeProductReturnTableNew").html('');
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
				alert(JSON.stringify(response))
				$("#serializeProductTable").text("Something Went Wrong.Please Try Again");
			}
		});
	}

	var stockQuantities = [];
	var newStockQuantities = [];
	var totalStockQuantity = 0;
	function calculateTotalQuantity(returnedQty, product_id, warehouse_id, tblSerializeProductsId) {
		totalStockQuantity = 0;
		var totalStockRemainingQuantity = parseInt($("#totalRamainingQuanitity_"+productRowNum).val());

		stockQuantities = $('input[name^=stockQuantity]').map(function(index, quantity) {
			return parseInt($(quantity).val()) ? parseInt($(quantity).val()) : 0 ;
		}).get();
		newStockQuantities = $('input[name^=newStockQuantity]').map(function(index, quantity) {
			return parseInt($(quantity).val()) ? parseInt($(quantity).val()) : 0 ;
		}).get();
		
		totalStockQuantity = newStockQuantities.reduce((a, b) => a + b, 0) + stockQuantities.reduce((a, b) => a + b, 0);

		if (totalStockQuantity > totalStockRemainingQuantity) {
			if (tblSerializeProductsId > 0) {
				$("#stockQuantity_"+tblSerializeProductsId).val(0);
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
			totalStockQuantity = newStockQuantities.reduce((a, b) => a + b, 0) + stockQuantities.reduce((a, b) => a + b, 0);

			$("#divErrorMsgSerialize").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Quantity Not Avaiable!");
			$("#divErrorMsgSerialize").show().delay(3000).fadeOut().queue(function(n) {
			$(this).hide(); n();
			});
		}
		$("#totalStockQuantity").text(totalStockQuantity);
	}

	function confirmSerialzeProduct() {
		let tbl_sales_id = $("#tbl_sales_id").val();
		let tbl_product_id = $("#tbl_product_id").val();
		let warehouseId = $("#serializProductWarehouseId").val();
		let uniqueId = tbl_product_id+"@"+warehouseId;
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
		$("#returnQuantity"+productRowNum).val(totalStockQuantity);

		let totalRamainingQuanitity = parseInt($("#totalRamainingQuanitity_"+productRowNum).val());
		$("#remainingQuantity"+productRowNum).text(totalRamainingQuanitity-totalStockQuantity);
		$("#serializeProductReturnModal").modal("hide");
	}

	function addRow() {
		let td = $('#serializeProductReturnTable tr:last-child td:first-child').html();
		let tdNew = $('#serializeProductReturnTableNew tr:last-child td:first-child').html();
		if (tdNew == undefined) {
			trId = isNaN(td) ? 1 : parseInt(td) + 1;
		}else{
			trId = isNaN(tdNew) ? 1 :  parseInt(tdNew) + 1;
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

function removeRows(rowId){
	var conMsg = confirm("Are you sure to delete??")
	if(conMsg){
		$("#productRow"+rowId).remove();
	}
}
function calculateTotal(saleProductId){
    var salesQuantity = $("#quantity"+saleProductId).html();
    var returnedQuantity = $("#returnedQuantity"+saleProductId).html();
    var returnQuantity = $("#returnQuantity"+saleProductId).val();
    if(returnQuantity == "" || parseFloat(returnQuantity) == "0"){
        returnQuantity = "0";
    }
    var totalreturn = parseFloat(returnQuantity)+parseFloat(returnedQuantity);
    if(totalreturn <= salesQuantity){
        if(parseFloat(returnQuantity) > 0){
            $("#btn_purchaseReturn").attr('disabled',false);	  
            var remainingQuantity = parseFloat(salesQuantity) - parseFloat(totalreturn);
            $("#remainingQuantity"+saleProductId).html(remainingQuantity);
        }else{
            $("#remainingQuantity"+saleProductId).html(salesQuantity-returnedQuantity-parseFloat(returnQuantity));
            $("#btn_purchaseReturn").attr('disabled',true);	  
            $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Return Quantity cannot be larger then sales quantity");
        	$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
        	  $(this).hide(); n();
        	});	      
        }
    }else{
        //$("#returnQuantity"+saleProductId).val('0');
        $("#remainingQuantity"+saleProductId).html(salesQuantity-returnedQuantity-parseFloat(returnQuantity));
        $("#btn_purchaseReturn").attr('disabled',true);	  
        $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Return Quantity cannot be larger then sales quantity");
		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
		  $(this).hide(); n();
		});	  
    }
}
//Save Purchase local return entry
$("#form_salesReturn").submit(function(event) {
    event.preventDefault();
    var returnDate = $("#returnDate").val();
    var salesId=$('#salesId').val();
    var salesType=$('#salesType').val();
    var salesCode=$('#salesCode').val();
    var customerId=$('#customerId').val();
    var wareHouseId=$('#wareHouse').val();
    var quantity = [];  
    var salesProductsId = [];  
	var i = 0;
	var countTotalQuantity = 0;
	var checkReturnDate = new Date($('#returnDate').val());
    var checkSalesDate = new Date($('#salesDate').val());
    $('input[name^="returnQuantity"]').each(function() {
        if($(this).val() != ""){
            quantity[i] = $(this).val()+"@!@";
        }else{
            quantity[i] = "0@!@";
        }
        
        countTotalQuantity += parseFloat($(this).val());
    	i = i + 1;
    });
    i = 0;
    $('input[name^="salesProductsId"]').each(function() {
        salesProductsId[i] = $(this).val()+"@!@";
    	i = i + 1;
    });
    if(i <= 0 && parseFloat(countTotalQuantity) <= 0){
        $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Return Products must be added");
		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
		  $(this).hide(); n();
		});	
    }else if(checkReturnDate < checkSalesDate){
        $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Sales return date cannot be smaller then sales date");
    	$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
    	  $(this).hide(); n();
    	});
    }
    else{
        /*alert(salesProductsId);
        alert(quantity);*/
        var fd = new FormData();
        fd.append('returnDate',returnDate);
        fd.append('salesId',salesId);
        fd.append('salesType',salesType);
        fd.append('salesCode',salesCode);
        fd.append('customerId',customerId);
        fd.append('wareHouseId',wareHouseId);
        fd.append('quantity',quantity);
        fd.append('salesProductsId',salesProductsId);
        fd.append('action','saveSalesReturn');
		//=== Serialize Product ===//
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
		//=== End Serialize Product ===//
        $.ajax({
            type: 'POST',
            url: 'phpScripts/salesReturnAction.php',
            data: fd,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response){
                if(response.msg == "Success"){
                    $("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> Return Successfully");
    				$("#divMsg").show().delay(2000).fadeOut().queue(function(n) {
    				  $(this).hide(); n();
    			    });
    			    location.href = 'saleReturnView.php?salesType='+salesType;
    			    if(salesType == "PartySale"){
    			        salesReport(response.returnId, 'partySaleReturn');
    			    }else if(salesType == "WalkinSale"){
    			        salesReport(response.returnId, 'walkinSaleReturn');
    			    }else if(salesType == "FS"){
    			        salesReport(response.returnId, 'FSReturn');
    			    }
                }else{
                    $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong>"+response);
            		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
            		  $(this).hide(); n();
            		});
                }
            },error: function (xhr) {
                //alert(xhr.responseText);
                $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error 3! </strong>"+xhr.responseText);
            	$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
            	  $(this).hide(); n();
            	});
            }
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
			url: 'phpScripts/salesReturnAction.php',
			data: dataString,
			dataType: 'json',
			success: function(response){
				//alert("1="+response);
				if(response == "Success"){
				    $("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> Successfully Deleted");
    				$("#divMsg").show().delay(2000).fadeOut().queue(function(n) {
    				  $(this).hide(); n();
    			    });
					manageSalesReturnTable.ajax.reload(null, false);
				}else{
					//alert(response);
					$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> "+response);
                	$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
                	  $(this).hide(); n();
                	});
				}
			},
			error: function (xhr) {
				//alert(xhr.responseText);
				$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error 4! </strong>"+xhr.responseText);
            	$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
            	  $(this).hide(); n();
            	});
			}
	    });  
	}
}