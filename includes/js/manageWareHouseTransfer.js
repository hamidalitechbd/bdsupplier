var manageWareHouseTransferTable;
$(document).ready(function() {
	// manage Shop table
	manageWareHouseTransferTable = $("#manageWareHouseTransferTable").DataTable({
		'ajax': 'phpScripts/wareHouseTransferaction.php',
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
});
$("#transferDate").val(new Date().toISOString().substring(0, 10));
$("#products").select2({
	placeholder: "Select Item Name",
	allowClear: true
});

$("#wareHouseID").select2({
	placeholder: "Select Warehouse",
	allowClear: true
});
$("#transferWareHouse").select2({
	placeholder: "Select Warehouse",
	allowClear: true
});

$("#products").change(function(){
    var productsId = $(this).val();
    var action = "fetchWareHouse";
    $.ajax({
		url:"phpScripts/wareHouseTransferaction.php",
		method:"POST",
		data:{action:action, productsId:productsId},
		dataType:"json",
		success:function(data)
		{
		    $("#wareHouseID").empty().trigger('change');
		    $("#wareHouseStock").empty();
		    $("#wareHouseID").append("<option value=''>~~ Select Warehouse ~~</option>");
		    $("#wareHouseStock").append("<option value=''>~~ Select Warehouse Stock ~~</option>");
		    for(var i=0; i<data.length; i++){
			    $("#wareHouseID").append("<option value='"+data[i].id+"'>"+data[i].wareHouseName+"</option>");
			    $("#wareHouseStock").append("<option value='"+data[i].id+"'>"+data[i].currentStock+"</option>");
			}
		}
	});
});

$("#wareHouseID").change(function(){
    /* wareHouse = $.trim($("#wareHouseID option:selected").text());
    var res = wareHouse.split("-");
    $("#currentStock").val($.trim(res[res.length-1]));
    $("#remainingStock").val($.trim(res[res.length-1]));*/
    $("#wareHouseStock").val($("#wareHouseID").val());
    var str = $( "#wareHouseStock option:selected" ).text();
    //var res = str.split("__");
    if(str != "~~ Select Warehouse Stock ~~"){
        $("#currentStock").val(str);
        $("#remainingStock").val(str);
    }else{
        $("#currentStock").val("0");
        $("#remainingStock").val("0");
    }
    $("#transferStock").val("0");
});

$("#transferStock").on("keyup", function (){
    var currentStock = $("#currentStock").val();
    var transferStock = $(this).val();
    if(parseFloat(transferStock) <= parseFloat(currentStock)){
        $("#remainingStock").val(currentStock-transferStock);
    }else{
        $(this).val(currentStock);
        $("#remainingStock").val(0);
        alert("Transfer quantity can not be greater then current stock");
    }
});

$(document).ready(function() {
	$('#form_transferWarehouse').bootstrapValidator({
    	live:'enabled',
    	message:'This value is not valid',
    	submitButton:'$form_transferWarehouse button [type="Submit"]',
    	submitHandler: function(validator, form, submitButton){
            var transferDate = $("#transferDate").val();
            var products = $("#products").val();
            var wareHouseID=$("#wareHouseID").val();
            var currentStock=$("#currentStock").val();
            var remainingStock=$("#remainingStock").val();
            var transferWareHouse=$("#transferWareHouse").val();
            var transferStock=$("#transferStock").val();
            var action = "saveTransfer";
            if(wareHouseID != transferWareHouse && parseFloat(remainingStock) >= 0 && parseFloat(transferStock) > 0){
                var fd = new FormData();
                fd.append('transferDate',transferDate);
                fd.append('products',products);
                fd.append('wareHouseID',wareHouseID);
                fd.append('currentStock',currentStock);
                fd.append('remainingStock',remainingStock);
                fd.append('transferWareHouse',transferWareHouse);
                fd.append('transferStock',transferStock);
                fd.append('action',action);
                $.ajax({
                    type: 'POST',
                    url: 'phpScripts/wareHouseTransferaction.php',
                    data: fd,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(response){
                    	if(response == "Success"){
                    		$("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> Successfully Saved");
                    	    $("#divMsg").show().delay(2000).fadeOut().queue(function(n) {
                    		    $(this).hide(); n();
                    		});
                    		manageWareHouseTransferTable.ajax.reload(null, false);
                    		$("#products").val('').trigger('change');
                    		$("#wareHouseID").empty().trigger('change');
                    		$("#currentStock").val('');
                    		$("#remainingStock").val('');
                    		$("#transferWareHouse").val('').trigger('change');
                    		$("#transferStock").val('');
                    	}else{
                    	    alert(response);
                    	}
                    },error: function (xhr) {
                    	alert(xhr.responseText);
                    }
                });
                $('#warehouseTransfer').modal('hide');
            }else{
                $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Product warehouse and transfer warehouse cannot be same. Remaining Quantity must be greater then negative or transfer quantity must be greater then 0 ");
        		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
        		  $(this).hide(); n();
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
    			
    			currentStock: {
    				validators: {
    						stringLength: {
    						min: 1,
    					},
    						notEmpty: {
    						message: 'Please Insert Item Name'
    					},
    					regexp: {
    						regexp: /^([a-zA-Z0-9_ '\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ '\.\-\s\,\;\:\/\&\$\%\(\)]+$/,
    						message: 'Please insert alphanumeric value only'
    					}
    				}
    			},
    			
    			transferStock: {
    				validators: {
    					
    					regexp: {
    						regexp: /^[1-9]\d*$/,
    						message: 'Only Amount : 200 '
    					}
    				}
    			}
    		}
		});
	}); 

function deleteTransfer(transferId){
    var conMsg = confirm("Are you sure to delete??");
	if(conMsg){
        var action = "deleteTransfer";
        $.ajax({
    	    url: 'phpScripts/wareHouseTransferaction.php',
    		method:"POST",
    		data:{action:action, id:transferId},
    		success:function(data)
    		{
    		    if(data=="Success"){
    		        $("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> Successfully Deleted");
            	    $("#divMsg").show().delay(2000).fadeOut().queue(function(n) {
            		    $(this).hide(); n();
            		});
    		        manageWareHouseTransferTable.ajax.reload(null, false);
    		    }else{
    		        alert(data);
    		    }
    		}
    	});
	}
}