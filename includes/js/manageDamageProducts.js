var manageDamageProductsTable
$(document).ready(function() {
	manageDamageProductsTable = $("#manageDamageProductsTable").DataTable({
		'ajax': 'phpScripts/productDamageAction.php?sortData='+$("#sortData").val(),
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
$( "#damageProducts" ).change(function() {
    var productsId = $(this).val();
    var action = "fetchWareHouse";
    $.ajax({
		url:"phpScripts/wareHouseTransferaction.php",
		method:"POST",
		data:{action:action, productsId:productsId},
		dataType:"json",
		success:function(data)
		{
		    $("#damageWareHouse").empty().trigger('change');
		    $("#wareHouseStock").empty();
		    $("#damageWareHouse").append("<option value=''>~~ Select Warehouse ~~</option>");
		    $("#wareHouseStock").append("<option value=''>~~ Select Warehouse Stock ~~</option>");
		    for(var i=0; i<data.length; i++){
			    $("#damageWareHouse").append("<option value='"+data[i].id+"'>"+data[i].wareHouseName+"</option>");
			    $("#wareHouseStock").append("<option value='"+data[i].id+"'>"+data[i].currentStock+"</option>");
			}
		}
	});
});
$("#damageWareHouse").change(function(){
    $("#wareHouseStock").val($(this).val());
    var str = $( "#wareHouseStock option:selected" ).text();
    if(str != "~~ Select Warehouse Stock ~~"){
        $("#currentStock").val(str);
    }else{
        $("#currentStock").val("0");
    }
    $("#damageQuantity").val("0");
});

$("#damageQuantity").on("keyup", function (){
    var currentStock = $("#currentStock").val();
    var damageQuantity = $(this).val();
    if(parseFloat(damageQuantity) > parseFloat(currentStock)){
        $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Damage quantity can not be greater then current stock.");
		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
		  $(this).hide(); n();
		});	    
    }
});
/*---------------------------- Start Voucher save portion ----------------------------------------------------*/
    $(document).ready(function() {
		$('#form_damageProducts').bootstrapValidator({
		live:'enabled',
		message:'This value is not valid',
		submitButton:'$form_damageProducts button [type="Submit"]',
		submitHandler: function(validator, form, submitButton){
    //event.preventDefault();
    var damageDate=$('#damageDate').val();
    var damageProducts=$("#damageProducts").val();
    var damageWareHouse=$('#damageWareHouse').val();
    var currentStock=$('#currentStock').val();
    var damageQuantity=$('#damageQuantity').val();
    var damageRemarks=$('#damageRemarks').val();
    if(parseFloat(damageQuantity) <= parseFloat(currentStock) && parseFloat(damageQuantity) > 0){
        var fd = new FormData();
        fd.append('damageDate',damageDate);
        fd.append('damageProducts',damageProducts);
        fd.append('damageWareHouse',damageWareHouse);
        fd.append('currentStock',currentStock);
        fd.append('damageQuantity',damageQuantity);
        fd.append('damageRemarks',damageRemarks);
        fd.append('action','saveDamage');
        $.ajax({
            type: 'POST',
            url: 'phpScripts/productDamageAction.php',
            data: fd,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response){
                if(response == "Success"){
                    /*BootstrapDialog.show({
                        title: 'Duronto damage-products says',
                        message: 'Saved Successfully.'
                    });*/
                    $("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> Saved Successfully");
    				$("#divMsg").show().delay(2000).fadeOut().queue(function(n) {
    				  $(this).hide(); n();
    			    });
    	            manageDamageProductsTable.ajax.reload(null, false);
    	            $("#damageProducts").val('').trigger("change");
                    $('#damageWareHouse').empty();
                    $('#currentStock').val('');
                    $('#damageQuantity').val('');
                    $('#damageRemarks').val('');
                    $("#btn_saveVoucher").attr("disabled",false);
                }else{
                    $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> "+response);
            		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
            		    $(this).hide(); n();
            		});
            		$("#btn_saveVoucher").attr("disabled",false);
                }
            },error: function (xhr) {
                alert(xhr.responseText);
            }
        });
	}else{
	    $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Damage quantity can not be greater then current stock and damage quantity must be greater then 0.");
		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
		    $(this).hide(); n();
		});
	}
	//$('#myModal').modal('hide');
		},
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
		excluded: [':disabled'],
        fields: {
				damageQuantity: {
					validators: {
							stringLength: {
							min: 1,
						},
							notEmpty: {
							message: 'Please Insert Amount'
						},
						regexp: {
							regexp: /^[0-9]+(?:\.[0-9]+)?$/,
							message: 'Please insert numeric value only'
						}
					}
				},
				damageRemarks: {
					validators: {
							stringLength: {
							min: 1,
						},
						regexp: {
							regexp: /^([a-zA-Z0-9_ '\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ '\.\-\s\,\;\:\/\&\$\%\(\)]+$/,
							message: 'Please insert alphanumeric value only'
						}
					}
				}
			}
			});
		});
/*---------------------------- end Voucher save portion ----------------------------------------------------*/
function deleteDamage(id){
    var conMsg = confirm("Are you sure to delete??");
	if(conMsg){
    	var action = "deleteDamage";
    	$.ajax({
    		url:"phpScripts/productDamageAction.php",
    		method:"POST",
    		data:{id:id, action:action},
    		dataType: 'json',
    		success:function(data)
    		{
    		    if(data == "Success"){
    		        $("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> Deleted Successfully");
				    $("#divMsg").show().delay(2000).fadeOut().queue(function(n) {
    				    $(this).hide(); n();
    			    });
        			manageDamageProductsTable.ajax.reload(null, false);
        			$("#damageProducts").val('').trigger("change");
                    $('#damageWareHouse').empty();
                    $('#currentStock').val('');
                    $('#damageQuantity').val('');
                    $('#damageRemarks').val('');
                    $("#btn_saveVoucher").attr("disabled",false);
    		    }else{
    		        $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> "+data);
            		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
            		    $(this).hide(); n();
            		});
    		    }
    		},
    		error: function (xhr) {
    			alert(xhr.responseText);
    		}
    	});
	}
}

/*------------------------------------- Select 2 portion ------------------------------------*/

$("#damageWareHouse").select2( {
	placeholder: "~~ Select Warehouse ~~",
	allowClear: true
} );
$("#damageProducts").select2({
	placeholder: "~~ Select Products ~~",
	allowClear: true
});
