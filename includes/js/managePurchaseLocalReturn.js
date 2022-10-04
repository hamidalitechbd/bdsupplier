loadCustomersSuppliers("Customers");
var managePurchaseReturnTable;
$(document).ready(function() {
	managePurchaseReturnTable = $("#managePurchaseReturnTable").DataTable({
		'ajax': 'phpScripts/loadPurchaseReturnView.php?sortData='+$("#sortData").val(),
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
	
	$("#supplierId").select21({
		placeholder: "Select Supplier Name",
		allowClear: true
	});
	
});
$( "#sortData" ).change(function() {
    managePurchaseReturnTable.ajax.url("phpScripts/loadPurchaseReturnView.php?sortData="+$("#sortData").val()).load();
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
			$("#supplierId").empty();
			for( var i = 0; i<len; i++){
				var id = response[i]['id'];
				var partyName = response[i]['partyName'];
				$("#supplierId").append("<option value='"+id+"'>"+partyName+"</option>");
			}
        },error: function (xhr) {
            alert(xhr.responseText);
        }
    });
}
function loadPurchase(){
	var purchaseCode = $('#purchaseCode').val();
	var dataString = 'loadPurchaseByPurchaseCode=1&purchaseCode='+purchaseCode;
	$.ajax({
		type: 'GET',
		url: 'phpScripts/purchaseReturnView.php',
		data: dataString,
		dataType: 'json',
		success: function(response){
			$('#supplierId').val(response[0].tbl_supplierId).trigger('change');
			$('#purchaseDate').val(response[0].purchaseDate);
			$('#chalanNo').val(response[0].chalanNo);
			$('#purchaseId').val(response[0].purchaseId);
			var specificationHTML="<thead style='background-color: #e1e1e1;'><th>SN</th><th>Item Name(Code)</th><th>Unit Price</th><th>Quantity</th><th>Returned Quantity</th><th>Return Quantity</th><th>Remaining Quantity</th><th>Total</th><th>Warehouse Name</th><th style='width:6%;'>Action</th></thead>";
			if(response != ''){
				for(var i=0;i<response.length;i++){
				// For Serialize 
				let showBtn = '';
				let disabled = '';
				let input = '';
				var remainingQuantity = response[i].quantity - response[i].returnQuantity;
				if (response[i].type == "serialize") {
					disabled = 'disabled';
					input = '<input type="hidden" id="totalRamainingQuanitity_'+i+'" name="totalRamainingQuanitity" value='+remainingQuantity+'>';
					showBtn = "<i style='cursor: pointer;' class='fa fa-eye btn-primary btn-xs ' "+'onclick="showSerializTable('+response[i].purchaseId+','+response[i].tbl_wareHouseId+','+i+','+response[i].tbl_productsId+')"'+"></i> ";
				}
				// End
					specificationHTML += "<tr id='productRow"+i+"'><td>"+input+""+(i+1)+"<input type='hidden' name='purchaseProductsId["+i+"]' value="+response[i].purchaseProductsId+" /></td><td>"+response[i].productName+"</td><td>"+response[i].purchaseAmount+"</td><td><span id='quantity"+i+"' name='quantity["+i+"]'>"+response[i].quantity+"</td><td><span id='returnedQuantity"+i+"' name='returnedQuantity["+i+"]'>"+response[i].returnQuantity+"</span></td><td><input type='text' style='width: 60%;text-align: center;' id='returnQuantity"+i+"' name='returnQuantity["+i+"]' value='0' onkeyup='calculateTotal("+i+")' "+disabled+" />"+showBtn+"</td><td><span id='remainingQuantity"+i+"' name='remainingQuantity["+i+"]'>"+remainingQuantity+"</span></td><td>"+response[i].totalAmount+"</td><td>"+response[i].wareHouseName+"</td><td><a href='#'  class='btn btn-danger btn-sm btn-flat' onclick='removeRows(" + i + ")'><i class='fa fa-trash'></i></a></td></tr>";
				}
			}
			
			$('#managePurchaseProductTable').html(specificationHTML);
		},
		error: function (xhr) {
			//alert("3="+xhr.responseText);
			alert(xhr.responseText);
		}
	});
}

//=========== Start Serialize Product ===========//
var productRowNum = '';  
var tbl_serialize_productsIdArray = [];
var stockQuantitiesSet = new Set();
var tbl_serialize_productsIdSet = new Set();
function showSerializTable(id, warehouseId, temTxt, product_id) {
	$("#btn_addRow").addClass("hidden");
	// id => purchaseId
	// warehouseId => warehouseId

	
	// product_id => product_id
	productRowNum = temTxt // tr number
	$("#totalRemainingQuantity").text($("#totalRamainingQuanitity_"+productRowNum).val());
	$("#tbl_sales_id").val(id); // purchaseId
	$("#tbl_product_id").val(product_id);
	$("#serializProductWarehouseId").val(warehouseId);
	let fd = new FormData();
	fd.append('matchQuantity', 0);
	fd.append('tbl_purchase_id', id);
	fd.append('product_id', product_id);
	fd.append('warehouseId', warehouseId);
	fd.append('action', "showSerializTable");
	$.ajax({
		url: "phpScripts/purchaseReturnView.php",
		method: "POST",
		data: fd,
		contentType: false,
		processData: false,
		dataType: "json",
		success: function (result) {
				let uniqueId = product_id+"@"+warehouseId;
				let myData = [];
				// Check If Exist 
				for (item of stockQuantitiesSet.values()){
					if (item[0] == uniqueId) {
						 myData = item;
					}
				}
				let len = myData.length;
				// End Check If Exist
				let serializeProducts = result.serializeProducts;
				let rows = '';
				let key = 0;
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
					totalQuantityForReturn += parseInt((returnQuantity));
					rows += '<tr><td>' + (key + 1) + '</td>' +
						'<td id="serializeRemainingQty_' + tbl_serialize_products_id + '">' + remainingQty + '</td><td><input class="form-control only-number input-sm stockQuantity' + key +
						'" id="stockQuantity_' + tbl_serialize_products_id + '" type="text" name="stockQuantity" placeholder=" ... " required oninput="calculateTotalQuantity(this.value,' + product_id + ',' + warehouseId + ',' + tbl_serialize_products_id + ')" value="' + returnQuantity + '"></td></tr>';
					key++;
				});
				$("#serializeProductReturnTable").html('');
				$("#serializeProductReturnTable").html(rows);
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
var totalStockQuantity = 0;
function calculateTotalQuantity(returnedQty, product_id, warehouse_id, tblSerializeProductsId) {
	let serializeRemainingQty = parseFloat($("#serializeRemainingQty_"+tblSerializeProductsId).text());
	if (returnedQty>serializeRemainingQty) {
		$("#stockQuantity_"+tblSerializeProductsId).val(0);
		$("#divErrorMsgSerialize").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Quantity Not Avaiable!");
		$("#divErrorMsgSerialize").show().delay(3000).fadeOut().queue(function(n) {
		$(this).hide(); n();
		});
	}
	totalStockQuantity = 0;
	stockQuantities = $('input[name^=stockQuantity]').map(function(index, quantity) {
		return parseInt($(quantity).val()) ? parseInt($(quantity).val()) : 0 ;
	}).get();
	totalStockQuantity = stockQuantities.reduce((a, b) => a + b, 0);
	$("#totalStockQuantity").text(totalStockQuantity);
}

function confirmSerialzeProduct() {
	// let tbl_sales_id = $("#tbl_sales_id").val(); // purchaseId
	let tbl_product_id = $("#tbl_product_id").val();
	let warehouseId = $("#serializProductWarehouseId").val();
	let uniqueId = tbl_product_id+"@"+warehouseId;
	let tempStockQuantities = [uniqueId].concat(stockQuantities);
	let temptbl_serialize_productsIds = [uniqueId].concat(tbl_serialize_productsIdArray);

	for (item of stockQuantitiesSet.values()){
		if (item[0] == uniqueId) {
			stockQuantitiesSet.delete(item);
		}
	}
	// For tbl_serialize_product ids
	for (idItem of tbl_serialize_productsIdSet.values()){
		if (idItem[0] == uniqueId) {
			tbl_serialize_productsIdSet.delete(idItem);
		}
	}
	stockQuantitiesSet.add(tempStockQuantities);
	tbl_serialize_productsIdSet.add(temptbl_serialize_productsIds);
	$("#returnQuantity"+productRowNum).val(totalStockQuantity);
	let totalRamainingQuanitity = parseInt($("#totalRamainingQuanitity_"+productRowNum).val());
	$("#remainingQuantity"+productRowNum).text(totalRamainingQuanitity-totalStockQuantity);
	$("#serializeProductReturnModal").modal("hide");
}
//=========== End Serialize Product ===========//

function removeRows(rowId){
	var conMsg = confirm("Are you sure to delete??")
	if(conMsg){
		$("#productRow"+rowId).remove();
	}
}
//Save Purchase local return entry
$("#form_purchaseReturn").submit(function(event) {
    event.preventDefault();
    var returnDate = $("#returnDate").val();
    var purchaseId=$('#purchaseId').val();
    var supplierId=$('#supplierId').val();
    var quantity = [];  
    var purchaseProductsId = [];  
	var i = 0;
    $('input[name^="returnQuantity"]').each(function() {
        quantity[i] = $(this).val()+"@!@";
    	i = i + 1;
    });
    i = 0;
    $('input[name^="purchaseProductsId"]').each(function() {
        purchaseProductsId[i] = $(this).val()+"@!@";
    	i = i + 1;
    });
    if(i > 0){
        var fd = new FormData();
        fd.append('returnDate',returnDate);
        fd.append('purchaseId',purchaseId);
        fd.append('quantity',quantity);
        fd.append('supplierId',supplierId);
        fd.append('purchaseProductsId',purchaseProductsId);
		//=== Serialize Product ===//
		 // Convert Set to Array
		 let stockQuantities = [];
		 let tbl_serialize_productsIds = [];
		 stockQuantitiesSet.forEach(stockQty => stockQuantities.push(stockQty));
		 tbl_serialize_productsIdSet.forEach(productId => tbl_serialize_productsIds.push(productId));
		// End Convert Set to Array
		fd.append('stockQuantities',stockQuantities);
		fd.append('tbl_serialize_productsIds',tbl_serialize_productsIds);
		//=== End Serialize Product ===//
        fd.append('purchaseReturn','1');
        $.ajax({
            type: 'POST',
            url: 'phpScripts/managePurchaseReturn.php',
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
    			    window.open(window.location.origin+'/productReturnViewDetails.php?prid='+response.returnId, '_blank');
    			    location.href = 'purchaseLocalViewreturn.php';
                }else{
                    alert(response);
                }
            },error: function (xhr) {
                alert(xhr.responseText);
            }
        });   
    }else{
        alert("Return Products must be added");
    }
})
//Delete purchase Return
function deletePurchaseReturn(purchaseReturnId){
    var conMsg = confirm("Are you sure to delete??");
	if(conMsg){
		var dataString = "id="+purchaseReturnId+"&deletePurchaseReturn=1";
		$.ajax({
			type: 'POST',
			url: 'phpScripts/managePurchaseReturn.php',
			data: dataString,
			dataType: 'json',
			success: function(response){
				//alert("1="+response);
				if(response == "Success"){
					managePurchaseReturnTable.ajax.reload(null, false);
				}else{
					alert(response);
				}
			},
			error: function (xhr) {
				//alert("3="+xhr.responseText);
				alert(xhr.responseText);
			}
	    });  
	}
}

function calculateTotal(purchaseProductId){
    var purchaseQuantity = $("#quantity"+purchaseProductId).html();
    var returnedQuantity = $("#returnedQuantity"+purchaseProductId).html();
    var returnQuantity = $("#returnQuantity"+purchaseProductId).val();
    var totalreturn = parseFloat(returnQuantity)+parseFloat(returnedQuantity);
    if(totalreturn <= purchaseQuantity){
        var remainingQuantity = parseFloat(purchaseQuantity) - parseFloat(totalreturn);
        $("#remainingQuantity"+purchaseProductId).html(remainingQuantity);
    }else{
        $("#returnQuantity"+purchaseProductId).val(purchaseQuantity-returnedQuantity);
        $("#remainingQuantity"+purchaseProductId).html('0');
        alert("Return Quantity cannot be larger then sales quantity"+returnQuantity+" - "+purchaseQuantity);
    }
}