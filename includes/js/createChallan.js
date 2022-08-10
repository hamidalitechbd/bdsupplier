$("#warehouse").change(function() {
	var warehouseId = $("#warehouse").val();
	var salesId = $("#salesId").val();
	var action = 'warehouseWiseProducts';
	$.ajax({
		url:"phpScripts/createChallanAction.php",
		method:"POST",
		data:{warehouse_id:warehouseId, sales_id:salesId, action:action},
		beforeSend: function () {
			$('#loading').show();
		},
		success:function(data)
		{
			$("#warehouseWiseProducts").html(data);
		},
		complete: function () {
			$('#loading').hide();
		},
		error: function (xhr) {
			alert(xhr.responseText);
		}
	});
})


    $("#warehouse").select2( {
		placeholder: "Select warehouse",
		allowClear: true
	});

    $("#transport").select2( {
		placeholder: "Select transport",
		allowClear: true
	});



function cartoonCalculation(id){
	var totalQty = $("#total_qty_"+id).val();
	var cartoonUnit = $("#cartoon_unit_"+id).val();
	if(parseFloat(cartoonUnit) <= parseFloat(totalQty)){
	    $("#cartoon_unit_"+id).css("color","black");
    	if(cartoonUnit == 0){
    	    $("#noOfCartoon_"+id).val(0);
    	    $("#restQuantity_"+id).val(totalQty);
    	}else{
        	var noofCartoon = Math.floor(totalQty / cartoonUnit);
        	$("#noOfCartoon_"+id).val(noofCartoon);
        	var cartoonQuantity = noofCartoon*cartoonUnit;
        	var restQuantity = totalQty - cartoonQuantity;
        	if(restQuantity >= 0){
        		$("#restQuantity_"+id).val(restQuantity);
        	}
    	}
	}else{
	    $("#cartoon_unit_"+id).css("color","red");
	    $("#noOfCartoon_"+id).val(0);
	    $("#restQuantity_"+id).val(totalQty);
	}
}
function cartoonChange(id){
	var totalQty = $("#total_qty_"+id).val();
	var cartoonUnit = $("#cartoon_unit_"+id).val();
	var noofCartoon = $("#noOfCartoon_"+id).val();
	
    	var cartoonQuantity = noofCartoon*cartoonUnit;
    	var restQuantity = totalQty - cartoonQuantity;
    	if(restQuantity >= 0){
    		$("#restQuantity_"+id).val(restQuantity);
    	}

}

$("#form_challan").submit(function (e){
	e.preventDefault();
	var salesProductId = [];
	var productIds = [];
	var totalQuantity = [];
	var cartoonQuantity = [];
	var noofCartoon = [];
	var remainingProducts = [];
	var totalRemainingProducts = 0;
	var totalCartonNo= 0;
	var noofCartoonCount = 0;
	var i = 0;
	$('input[id^="sale_products_id_"]').each(function() {
		salesProductId[i] = $(this).val()+"@!@";
		i = i + 1;
	});
	i = 0;
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
	$('input[id^="noOfCartoon_"]').each(function() {
		noofCartoon[i] = $(this).val()+"@!@";
		totalCartonNo+=parseFloat(noofCartoon[i]);
		i = i + 1;
		noofCartoonCount++;
		//alert(noofCartoonCount);
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
	if(noofCartoonCount > 0){
    	var challanDate = $("#challanDate").val();
    	var warehouseId = $("#warehouse").val();
    	var transportId = $("#transport").val();
    	var salesId = $("#salesId").val();
    	var type = $("#type").val();
    	var partyId = $("#partyId").val();
    	var action = "createChallan";
    	var fd = new FormData();
    	fd.append('challanDate',challanDate);
    	fd.append('warehouseId',warehouseId);
    	fd.append('transportId',transportId);
    	fd.append('salesId',salesId);
    	fd.append('type',type);
    	fd.append('partyId',partyId);
    	fd.append('salesProductId',salesProductId);
    	fd.append('totalQuantity',totalQuantity);
    	fd.append('cartoonQuantity',cartoonQuantity);
    	fd.append('noofCartoon',noofCartoon);
    	fd.append('remainingProducts',remainingProducts);
    	fd.append('productIds',productIds);
    	fd.append('action',action);
    	$.ajax({
    		url:"phpScripts/createChallanAction.php",
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
    				$("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> Update Successfully");
    				$("#divMsg").show().delay(2000).fadeOut().queue(function(n) {
    				  $(this).hide(); n();
    				});
    				//location.href='challanView.php';
    				if(totalRemainingProducts > 0){
    				    location.href='challanAdjust.php?challanId='+response.challanId+"&type="+response.type;    
    				}else if(response.challanExists == 'Yes'){
    				    location.reload();
				        //location.href='create-challan.php?salesId='+response.salesId+'&salesType=PartySale';    
				    }else{
    				    location.href='challanView.php';
    				}
    				
    			}else{
    				alert(JSON.stringify(response));
    			}			
    		},
    		complete: function () {
    			$('#loading').hide();
    		},
    		error: function (xhr) {
    			alert(xhr.responseText);
    		}
    	});
	}else{
	    $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> 0(Zero) quantity not possible to create challan");
		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
		  $(this).hide(); n();
		});
	}
	
})