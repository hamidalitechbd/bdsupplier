var manageDiscountOffer;
$(document).ready(function() {
	// manage Shop table
	manageDiscountOffer = $("#manageDiscountOffer").DataTable({
		'ajax': 'phpScripts/discountOfferAction.php',
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


/*----------------Start Offer Information save & validation parts----------------------*/
$(document).ready(function() {
	$('#form_discountOffer').bootstrapValidator({
	live:'enabled',
	message:'This value is not valid',
	submitButton:'$form_discountOffer button [type="Submit"]',
	submitHandler: function(validator, form, submitButton){
			 var offerName = $("#add_offerName").val();
			 var partyType = $("#add_partyType").val();
			 var startDate=$("#add_startDate").val();
			 var remainderDate=$("#add_remainder").val();
			 var endDate=$("#add_endDate").val();
			 var products=$("#add_products").val();
			 var priority=$("#add_priority").val();
			 var offerFor=$("#add_offerFor").val();
			 var offerForType=$("#add_offerForType").val();
			 var discountAmount = $('#add_discount').val();
			 var discountType=$("#add_discountType").val();
			 var discountAmount_2 = $('#add_and_discount').val();
			 var discountUnit_2=$("#add_and_discountType").val();
			 var remarks=$("#add_remarks").val();
		     var fd = new FormData();
            fd.append('offerName',offerName);
            fd.append('partyType',partyType);
            fd.append('startDate',startDate);
            fd.append('remainderDate',remainderDate);
            fd.append('endDate',endDate);
            fd.append('products',products);
            fd.append('priority',priority);
            fd.append('offerFor',offerFor);
            fd.append('offerForType',offerForType);
            fd.append('discountAmount',discountAmount);
            fd.append('discountType',discountType);
            fd.append('discountAmount_2',discountAmount_2);
            fd.append('discountUnit_2',discountUnit_2);
            fd.append('remarks',remarks);  
            fd.append('action','save');
            
			  $.ajax({
					type: 'POST',
					url: 'phpScripts/discountOfferAction.php',
					data: fd,
					contentType: false,
				    processData: false,
					dataType: 'json',
					success: function(response){
						if(response=='Success'){
						    manageDiscountOffer.ajax.reload(null, false);
						    	$("#add_offerName").val('');
						    	$("#add_partyType").val('');
						    	$("#add_products").val('').trigger('change');
						    	$("#add_priority").val('');
						    	$("#add_offerFor").val('');
						    	$("#add_offerForType").val('');
						    	$("#add_discount").val('');
						    	$("#add_discountType").val('');
						    	$("#add_and_discount").val('');
						    	$("#add_and_discountType").val('');
						    	$("#add_remarks").val('');
						    	
						   $("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> Successfully Saved");
						   $("#divMsg").show().delay(2000).fadeOut().queue(function(n) {
							  $(this).hide(); n();
							});
						}else{
						    alert(response);
						}
					  
					},error: function (xhr) {
						alert(xhr.responseText);
					}
				  });
				},
			feedbackIcons: {
				valid: 'glyphicon glyphicon-ok',
				invalid: 'glyphicon glyphicon-remove',
				validating: 'glyphicon glyphicon-refresh'
			},
			excluded: [':disabled'],
			fields: {
				offerName: {
					validators: {
							stringLength: {
							min: 3,
						},
							notEmpty: {
							message: 'Please Insert Offer Name'
						},
						regexp: {
							regexp: /^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\%\(\)]+$/,
							message: 'Please insert alphanumeric value only'
						}
					}
				},
				partyType: {
					validators: {
						notEmpty: {
							message: 'Please Insert Party Type'
						},
					}
				},
				sdate: {
					validators: {
						notEmpty: {
							message: 'Please Insert date'
						},
					}
				},
				edate: {
					validators: {
						notEmpty: {
							message: 'Please Insert City Name'
						},
					}
				},
				products: {
					validators: {
						notEmpty: {
							message: 'Please Insert Products Name'
						},
					}
				},
				priority: {
					validators: {
						notEmpty: {
							message: 'Please Select Priority'
						}
					}
				},
				offerFor: {
					validators: {
						notEmpty: {
							message: 'Please Insert Number Only'
						},
						regexp: {
							regexp: /^[0-9]+$/,
							message: 'Please Insert Number Only '
						}
					}
				},
				offerForType: {
					validators: {
						notEmpty: {
							message: 'Please Insert Products Name'
						},
					}
				},
				discount: {
					validators: {
						notEmpty: {
							message: 'Please Insert Number Only'
						},
						regexp: {
							regexp: /^[0-9]+$/,
							message: 'Please Number Only '
						}
					}
				},
				discountType: {
					validators: {
						notEmpty: {
							message: 'Please Insert Number Only'
						}
						
					}
				},
				andDiscount: {
					validators: {
					
						regexp: {
							regexp: /^[0-9]+$/,
							message: 'Please Number Only '
						}
					}
				},
				andDiscountType: {
					validators: {
						message: 'Pls Select Discount Type'
					}
				},
				remarks: {
					validators: {
							stringLength: {
							min: 3,
						},
						regexp: {
							regexp: /^([a-zA-Z0-9_ \.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ \.\-\s\,\;\:\/\&\$\%\(\)]+$/,
							message: 'Please insert alphanumeric value only'
						}
					}
				}
			}
			});
		}); 
/*----------------End save & validation parts----------------------*/

/*---------------------------- Delete Discount Offer ----------------------------------------------------*/
function deleteDiscountOffer(offerId){
    var conMsg = confirm("Are you sure to delete??");
	if(conMsg){
    	var action = "deleteOffer";
    	$.ajax({
    		url:"phpScripts/discountOfferAction.php",
    		method:"POST",
    		data:{'id':offerId, 'action':action},
    		dataType: 'json',
    		success:function(data)
    		{
    		    if(data == "Success"){
    		        manageDiscountOffer.ajax.reload(null, false);
        		    $("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> Deleted Successfully");
    				$("#divMsg").show().delay(2000).fadeOut().queue(function(n) {
    				  $(this).hide(); n();
    			    });
    			
    		    }else{
    		        alert("Else "+data);     
    		    }
    		},
    		error: function (xhr) {
    			alert("Page not found "+xhr.responseText);
    		}
    	});
	}
}

/*---------------------------- Status Update Discount Offer ----------------------------------------------------*/
function statusUpdateOffer(statusId,status){
    var conMsg = confirm("Are you sure to status change??");
    if(conMsg){
    	var action = "statusOffer";
    	//alert(status);
    	$.ajax({
    		url:"phpScripts/discountOfferAction.php",
    		method:"POST",
    		data:{'id':statusId,'status':status, 'action':action},
    		dataType: 'json',
    		success:function(data)
    		{
    		    if(data == "Success"){
    		        manageDiscountOffer.ajax.reload(null, false);
        		    $("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> Status Changed Successfully");
    				$("#divMsg").show().delay(2000).fadeOut().queue(function(n) {
    				  $(this).hide(); n();
    			    });
    			
    		    }else{
    		        alert("Else "+data);     
    		    }
    		},
    		error: function (xhr) {
    			alert("Page not found "+xhr.responseText);
    		}
    	});
	}
}