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
				    var remainingQuantity = response[i].quantity - response[i].returnQuantity;
					specificationHTML += "<tr id='productRow"+i+"'><td>"+(i+1)+"<input type='hidden' name='purchaseProductsId["+i+"]' value="+response[i].purchaseProductsId+" /></td><td>"+response[i].productName+"</td><td>"+response[i].purchaseAmount+"</td><td><span id='quantity"+i+"' name='quantity["+i+"]'>"+response[i].quantity+"</td><td><span id='returnedQuantity"+i+"' name='returnedQuantity["+i+"]'>"+response[i].returnQuantity+"</span></td><td><input type='text' style='width: 60%;text-align: center;' id='returnQuantity"+i+"' name='returnQuantity["+i+"]' value='0' onkeyup='calculateTotal("+i+")'/></td><td><span id='remainingQuantity"+i+"' name='remainingQuantity["+i+"]'>"+remainingQuantity+"</span></td><td>"+response[i].totalAmount+"</td><td>"+response[i].wareHouseName+"</td><td><a href='#'  class='btn btn-danger btn-sm btn-flat' onclick='removeRows(" + i + ")'><i class='fa fa-trash'></i></a></td></tr>";
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