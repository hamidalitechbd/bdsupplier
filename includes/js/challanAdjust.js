var manageChallanAdjustTable;
load_adjustment();
function load_adjustment(){
    var challanId = $("#challanId").val();
    
	$.ajax({
		url:'phpScripts/challanAdjustmentAction.php?challanId='+challanId,
		method:"GET",
		beforeSend: function () {
			$('#loading').show();
		},
		success:function(data)
		{
			$("#manageChallanAdjustTable").html(data);
		},
		complete: function () {
			$('#loading').hide();
		},
		error: function (xhr) {
			alert(xhr.responseText);
		}
	});
    
}

function cartoonCalculation(id){
	var total_qty = $("#total_qty_"+id).val();
	var unit_qty = $("#cartoon_unit_"+id).val();
	var remaining = total_qty - unit_qty;
	$("#restQuantity_"+id).val(remaining);
}

$("#form_challanAdjust").submit(function (e) {
	e.preventDefault();
	var totalRemainingProducts = 0;
	//var salesProductId = [];
	var productIds = [];
	var totalQuantity = [];
	var cartoonQuantity = [];
	var remainingProducts = [];
	var i = 0;
	/*$('input[id^="sale_products_id_"]').each(function() {
		salesProductId[i] = $(this).val()+"@!@";
		i = i + 1;
	});
	i = 0;*/
	$('input[id^="total_qty_"]').each(function() {
		totalQuantity[i] = $(this).val()+"@!@";
		i = i + 1;
	});
	i = 0;
	$('input[id^="cartoon_unit_"]').each(function() {
		cartoonQuantity[i] = $(this).val()+"@!@";
		i = i + 1;
	});
	i = 0;
	$('input[id^="restQuantity_"]').each(function() {
		remainingProducts[i] = $(this).val()+"@!@";
		totalRemainingProducts += parseFloat(remainingProducts[i]);
		i = i + 1;
	});
	i = 0;
	$('input[id^="productId_"]').each(function() {
		productIds[i] = $(this).val()+"@!@";
		i = i + 1;
	});
	var challanId = $("#challanId").val();
	var warehouseId = $("#warehouseId").val();
	var transportId = $("#transport").val();
	var challanDate = $("#challanDate").val();
	var type = $("#type").val();
	var action = "challanAdjust";
	var fd = new FormData();
	fd.append('challanDate',challanDate);
	fd.append('challanId',challanId);
	fd.append('warehouseId',warehouseId);
	fd.append('transportId',transportId);
	//fd.append('salesProductId',salesProductId);
	//alert(salesProductId);
	fd.append('totalQuantity',totalQuantity);
	fd.append('cartoonQuantity',cartoonQuantity);
	fd.append('remainingProducts',remainingProducts);
	fd.append('productIds',productIds);
	fd.append('action',action);
	$.ajax({
		url:"phpScripts/challanAdjustmentAction.php",
		method:"POST",
		data:fd,
		contentType: false,
		processData: false,
		dataType: 'json',
		beforeSend: function () {
			$('#loading').show();
		},
		success:function(response)
		{
			if(response.msg == "Success"){
				$("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> New carton created successfully");
				$("#divMsg").show().delay(2000).fadeOut().queue(function(n) {
				  $(this).hide(); n();
				});
				if(totalRemainingProducts > 0){
				    //manageChallanAdjustTable.ajax.reload(null, false);
				    load_adjustment();
				}else{
				    location.href='create-challan.php?salesId='+response.salesId+'&salesType='+type;
				    //window.location='create-challan.php?salesId='+response.salesId+'&salesType='+type;
				    //window.open('https://jafreeuat.alitechbd.com/create-challan.php?salesId='+response.salesId+'&salesType='+type,'_self');
				}
			}else{
				alert(response);
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