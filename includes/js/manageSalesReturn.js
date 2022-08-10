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
			    var remainingQuantity = response[i].quantity - response[i].returnedQuantity;
				productHTML += "<tr id='productRow"+i+"'><td>"+(i+1)+"<input type='hidden' name='salesProductsId["+i+"]' value="+response[i].salesProductId+" /></td><td>"+response[i].productName+" - "+response[i].productCode+"</td><td>"+response[i].wareHouseName+"</td><td style='text-align: center;'><span id='quantity"+i+"' name='quantity["+i+"]'>"+response[i].quantity+"</td><td style='text-align: center;'><span id='returnedQuantity"+i+"' name='returnedQuantity["+i+"]'>"+response[i].returnedQuantity+"</span></td><td><input style='width: 100%;' type='text' id='returnQuantity"+i+"' name='returnQuantity["+i+"]' value='0'  onkeyup='calculateTotal("+i+")'/></td><td style='text-align: center;'><span id='remainingQuantity"+i+"' name='remainingQuantity["+i+"]'>"+remainingQuantity+"</span></td><td>"+response[i].salesAmount+"</td><td>"+response[i].discount+"</td><td>"+response[i].grandTotal+"</td><td><a href='#'  class='btn btn-danger btn-sm btn-flat' onclick='removeRows(" + i + ")'><i class='fa fa-trash'></i></a></td></tr>";
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