var managePurchaseTable;
loadCustomersSuppliers("Customers");
$("#add_bankInformation").select2( {
    placeholder: "Select Bank Infomation",
    allowClear: true
});
$(document).ready(function() {
    
	// retrive customer or supplier data
	managePurchaseTable = $("#managePurchaseTable").DataTable({
		'ajax': 'phpScripts/purchaseViewForeign.php?sortData='+$("#sortData").val(),
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
	
	//Load Saved Data in Temporary Instance
     $.ajax({
		type: 'GET',
		url: 'phpScripts/purchaseProductViewForeign.php?userId=1&newSessionId='+$('#add_sessionId').val(),
		success: function(response){
			var res = response.split("@!@");
			$('#managePurchaseProductTable').html(res[0]);
			if(res.length > 1){
    			$('#add_grandTotal').val(res[1]);
    			if($('#add_paid').val() ==''){
    			  $('#add_paid').val('0');
    			}
    			var paid = parseFloat($('#add_paid').val());
    			var grandTotal = parseFloat($('#add_grandTotal').val());
    			$('#add_due').val(grandTotal - paid);
    			$('#add_sessionId').val(res[2]);
			}
		},
		error: function (xhr) {
			//alert("3="+xhr.responseText);
			alert(xhr.responseText);
		}
	});
	if(parseFloat($("#fpId").val()) > 0){
	    //$("#add_supplier").val($("#editSupplierId").val()).trigger('change');    
	    $("#add_bankInformation").val($("#editBankInfoId").val()).trigger('change');
	}else{
	    //$("#add_supplier").val("").trigger('change');    
	    $("#add_bankInformation").val("").trigger('change');    
	}
	
});

$( "#sortData" ).change(function() {
    managePurchaseTable.ajax.url("phpScripts/purchaseViewForeign.php?sortData="+$("#sortData").val()).load();
}); 
//Temp Purchase Products Table
function loadPurchaseProductTable(){
    $.ajax({ 
      type: 'GET', 
      url: 'phpScripts/purchaseProductViewForeign.php?sessionId='+$('#add_sessionId').val(), 
      success: function (result) {
          var res = result.split("@!@");
          $('#managePurchaseProductTable').html(res[0]);
          $('#add_grandTotal').val(res[1]);
          if($('#add_paid').val() == ''){
              $('#add_paid').val('0');
          }
          var paid = parseFloat($('#add_paid').val());
          var grandTotal = parseFloat($('#add_grandTotal').val());
          $('#add_due').val(grandTotal - paid);
      },error: function (xhr) {
            alert(xhr.responseText);
        }
    
    });
}

//loadRealPurchaseProductsTable
function loadRealPurchaseProductsTable(){
    $.ajax({ 
      type: 'GET', 
      url: 'phpScripts/purchaseProductViewForeign.php?id='+$('#edit_purchaseId').val(), 
      success: function (result) {
          var res = result.split("@!@");
          $('#edit_managePurchaseProductTable').html(res[0]);
          $('#edit_grandTotal').val(res[1]);
          if($('#edit_paid').val() == ''){
              $('#edit_paid').val('0');
          }
          var paid = parseFloat($('#edit_paid').val());
          var grandTotal = parseFloat($('#edit_grandTotal').val());
          $('#edit_due').val(grandTotal - paid);
      },error: function (xhr) {
            alert(xhr.responseText);
        }
    
    });
}

//Delete Temporary Products from purchase table
function deleteTemporaryPurchaseProducts(tempPurchaseProductsId){
    var conMsg = confirm("Are you sure to delete??")
	if(conMsg){
        var dataString = "id="+tempPurchaseProductsId+"&deleteTemporaryForeignPurchaseProducts=1";
        $.ajax({
            type: 'POST',
            url: 'phpScripts/manageForeignPurchaseProducts-add.php',
            data: dataString,
            dataType: 'json',
            success: function(response){
                //alert("1="+response);
                if(response == "Success"){
                    loadPurchaseProductTable();
                }else{
                    alert(response);
                  //  alert("2="+response);
                }
            },
            error: function (xhr) {
                //alert("3="+xhr.responseText);
                alert(xhr.responseText);
            }
        });  
	}
}

//Delete Products from purchase table
function deletePurchaseProducts(tempPurchaseProductsId){
    var conMsg = confirm("Are you sure to delete??")
	if(conMsg){
		var dataString = "id="+tempPurchaseProductsId+"&deleteForeignPurchaseProducts=1";
		$.ajax({
			type: 'POST',
			url: 'phpScripts/manageForeignPurchaseProducts-add.php',
			data: dataString,
			dataType: 'json',
			success: function(response){
				//alert("1="+response);
				if(response == "Success"){
					loadRealPurchaseProductsTable();
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

$('#add_paid').keyup(function() {
    var grandTotal = parseFloat($('#add_grandTotal').val()); // Or parseInt if integers only
    var paid = parseFloat($('#add_paid').val());
    $('#add_due').val(grandTotal - paid);
});

$('#edit_paid').keyup(function() {
    var grandTotal = parseFloat($('#edit_grandTotal').val()); // Or parseInt if integers only
    var paid = parseFloat($('#edit_paid').val());
    $('#edit_due').val(grandTotal - paid);
});

loadPurchaseProductTable();
$("#add_wereHouse").select2( {
    placeholder: "Select Warehouse",
    allowClear: true
});
$("#add_supplier").select2( {
placeholder: "Select Supplier Name",
allowClear: true
} );

$("#edit_supplier").select2( {
placeholder: "Select Supplier Name",
allowClear: true
} );
$("#add_products").select2( {
placeholder: "Select Item Name",
allowClear: true
} );

//Update Purchase
$("#form_editPurchase").submit(function(event) {
    event.preventDefault();
    var purchaseDate = $("#edit_purchaseDate").val();
    var supplier = $("#edit_supplier").val();
    var chalanNumber = $("#edit_chalanNumber").val();
    var purchaseId=$('#edit_purchaseId').val();
    var totalAmount=$('#edit_grandTotal').val();
    var paidAmount=$('#edit_paid').val();
    var dueAmount=$('#edit_due').val();
    var fd = new FormData();
    fd.append('purchaseDate',purchaseDate);
    fd.append('supplier',supplier);
    fd.append('chalanNumber',chalanNumber);
    fd.append('totalAmount',totalAmount);
    fd.append('paidAmount',paidAmount);
    fd.append('dueAmount',dueAmount);
    fd.append('purchaseId',purchaseId);
    fd.append('editForeignPurchase','1');
      
    $.ajax({
        type: 'POST',
        url: 'phpScripts/manageForeignPurchaseProducts-add.php',
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
			    location.href = 'purchaseLocal-view.php';
            }else{
                alert(response);
            }
        },error: function (xhr) {
            alert(xhr.responseText);
        }
      });    
})

function loadCustomersSuppliers(tblType){
	var dataString = "tblType="+tblType;
	$.ajax({
        type: 'GET',
        url: 'phpScripts/loadCustomerSupplier.php',
        data: dataString,
        dataType: 'json',
        success: function(response){
          var len = response.length;
			$("#add_supplier").empty();
			//$("#edit_supplier").empty();
			for( var i = 0; i<len; i++){
				var id = response[i]['id'];
				var partyName = response[i]['partyName'];
				if(parseFloat($("#editSupplierId").val()) > 0){
				    if($("#editSupplierId").val() == id){
				        $("#add_supplier").append("<option value='"+id+"' Selected>"+partyName+"</option>");        
				    }else{
				        $("#add_supplier").append("<option value='"+id+"'>"+partyName+"</option>");        
				    }
				}else{
				    $("#add_supplier").append("<option value='"+id+"'>"+partyName+"</option>");        
				}
				
                //$("#edit_supplier").append("<option value='"+id+"'>"+partyName+"</option>");
			}
			$('#add_supplier').val($("#editSupplierId").val()).trigger('change');
        },error: function (xhr) {
            alert(xhr.responseText);
        }
      });
}

/*------------------ Start Save Purchase or purchase products & validation panel ---------------------- */
	//Save temporary purchase products Modal
	$(document).ready(function() {
		$('#form_addPurchaseProducts').bootstrapValidator({
		live:'enabled',
		message:'This value is not valid',
		submitButton:'$form_addPurchaseProducts button [type="Submit"]',
		submitHandler: function(validator, form, submitButton){
	  var unitPrice = $("#add_unitPrice").val();
	  var quantity = $("#add_quantity").val();
	  var walkInCustomerPrice = $("#add_maxPrice").val();
	  var wholeSalePrice=$("#add_minPrice").val();
	  var manufacturingDate=$("#add_mfgDate").val();
	  var expiryDate=$("#add_expDate").val();
	  var wareHouseId=$("#add_wereHouse").val();
	  var productId=$("#add_products").val();
	  
		
	  var fd = new FormData();
	  fd.append('unitPrice',unitPrice);
	  fd.append('quantity',quantity);
	  fd.append('walkInCustomerPrice',walkInCustomerPrice);
	  fd.append('wholeSalePrice',wholeSalePrice);
	  fd.append('manufacturingDate',manufacturingDate);
	  fd.append('expiryDate',expiryDate);
	  fd.append('wareHouseId',wareHouseId);
	  fd.append('productId',productId);
	  
	  if($("#edit_purchaseId").val() == "" || $("#edit_purchaseId").val() == "undefined"){
		  var sessionId=$('#add_sessionId').val();
		  fd.append('sessionId',sessionId);
	  }else{
		  var purchaseId=$('#edit_purchaseId').val();
		  fd.append('purchaseId',purchaseId);
	  }
	  
	  fd.append('saveForeignPurchaseProducts','1');
	  


	  $.ajax({
			type: 'POST',
			url: 'phpScripts/manageForeignPurchaseProducts-add.php',
			data: fd,
			contentType: false,
			processData: false,
			dataType: 'json',
			beforeSend: function () {
                $('#loading').show();
            },
			success: function(response){
				if(response == "Success"){
					if($("#edit_purchaseId").val() == "" || $("#edit_purchaseId").val() == "undefined"){
						loadPurchaseProductTable();
					}else{
						loadRealPurchaseProductsTable();
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
	  $('#myModal').modal('hide');
		},
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
		excluded: [':disabled'],
        fields: {
				unitPrice: {
					validators: {
							stringLength: {
							min: 1,
						},
						
						regexp: {
							regexp: /^[0-9]+(?:\.[0-9]+)?$/,
							message: 'Please insert numeric value only'
						}
					}
				},
				quantity: {
					validators: {
							stringLength: {
							min: 1,
						},
							notEmpty: {
							message: 'Please Insert quantity'
						},
						regexp: {
							regexp: /^[0-9]+(?:\.[0-9]+)?$/,
							message: 'Please insert alphanumeric value only'
						}
					}
				},
				minPrice: {
					validators: {
							stringLength: {
							min: 1,
						},
							notEmpty: {
							message: 'Please Insert min Price'
						},
						regexp: {
							regexp: /^[0-9]+(?:\.[0-9]+)?$/,
							message: 'Please insert decimal value only'
						}
					}
				},
				maxPrice: {
					validators: {
							stringLength: {
							min: 1,
						},
							notEmpty: {
							message: 'Please Insert maxPrice'
						},
						regexp: {
							regexp: /^[0-9]+(?:\.[0-9]+)?$/,
							message: 'Please insert decimal value only'
						}
					}
				},
				lotNo: {
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
		    
		    
		    
		    /* min max check for mfg & exp date*/ 
    		  function mfgValidate(){
    				var min = document.getElementById("add_mfgDate").value;
    			    var max = document.getElementById("add_expDate").value;
    			   if(min > max) {
    				   alert("MFG. Date must be lesser than EXP. Date  " + min + " > " + max );
    			   } 
    		 }    
    
    		function expValidate(){
    			   var min = document.getElementById("add_mfgDate").value;
    			   var max = document.getElementById("add_expDate").value;
    			   if(max<min) {
    				   alert("EXP. Date must be greater than MFG. Date  "  + min + " > " + max );
    				   
    			   } 
    		  }
	
	// Save foreign purchase form data 
	
	//$("#form_addPurchase").submit(function(event) {
	    
	    $(document).ready(function() {
		$('#form_addPurchase').bootstrapValidator({
		live:'enabled',
		message:'This value is not valid',
		submitButton:'$form_addPurchase button [type="Submit"]',
		submitHandler: function(validator, form, submitButton){
	    
	  //event.preventDefault();
	  var purchaseDate = $("#add_purchaseDate").val();
	  var supplier = $("#add_supplier").val();
	  var lcNo = $("#add_lcNo").val();
	  var lcOpeningDate= $("#add_lcOpeningDate").val();
	  var deliveryDate = $("#add_lcDeliveryDate").val();
	  var fileNo = $("#add_fileNo").val();
	  var blNo = $("#add_blNo").val();
	  var bankInformation = $("#add_bankInformation").val();
	  var sessionId=$('#add_sessionId').val();
	  var totalAmount=$('#add_grandTotal').val();
	  var paidAmount=$('#add_paid').val();
	  var dueAmount=$('#add_due').val();
	  var fpId = $("#fpId").val();
		
	  var fd = new FormData();
	  fd.append('purchaseDate',purchaseDate);
	  fd.append('supplier',supplier);
	  fd.append('lcNo',lcNo);
	  fd.append('lcOpeningDate',lcOpeningDate);
	  fd.append('deliveryDate',deliveryDate);
	  fd.append('fileNo',fileNo);
	  fd.append('blNo',blNo);
	  fd.append('bankInformation',bankInformation);
	  fd.append('totalAmount',totalAmount);
	  fd.append('paidAmount',paidAmount);
	  fd.append('dueAmount',dueAmount);
	  fd.append('sessionId',sessionId);
	  
	  fd.append('saveForeignPurchase','1');
	  
	  $.ajax({
			type: 'POST',
			url: 'phpScripts/manageForeignPurchaseProducts-add.php',
			data: fd,
			contentType: false,
			processData: false,
			dataType: 'json',
			success: function(response){
				if(response.msg == "Success"){
				    if(parseFloat(fpId) > 0){
				        deleteForeignPurchaseforEdit(fpId,'Soumen');
				    }
				    //$('#myModal').modal('hide');
				    window.open(window.location.origin+'/purchaseForeignViewDetails.php?id='+response.purchaseId, '_blank');
					loadPurchaseProductTable();
					$('#add_supplier').val('').trigger('change');
					$("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> Successfully Saved");
					$("#divMsg").show().delay(2000).fadeOut().queue(function(n) {
					  $(this).hide(); n();
					});
				}else{
					$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> At least one product have to add");
					$("#divErrorMsg").show().delay(2000).fadeOut().queue(function(n) {
					  $(this).hide(); n();
					});
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
				lcno: {
					validators: {
							stringLength: {
							min: 1,
						},
						
						regexp: {
						    regexp: /^\d+$/,
							message: 'Please insert numeric maximum 13 value only'
						}
					}
				},
				lcOpeningDate: {
                validators: {
                    date: {
                        message: 'The date is not valid',
                        format: 'YYYY/MM/DD'
                    },
                }
				},
				lcDeliveryDate: {
                validators: {
                    date: {
                        message: 'The date is not valid',
                        format: 'YYYY/MM/DD'
                    },
                }
				},
				fileNo: {
					validators: {
							stringLength: {
							min: 1,
						},
						
						regexp: {
						    regexp: /^\d+$/,
							message: 'Please insert numeric maximum 13 value only'
						}
					}
				},
				blNo: {
					validators: {
							stringLength: {
							min: 1,
						},
						
						regexp: {
						    regexp: /^\d+$/,
							message: 'Please insert numeric maximum 13 value only'
						}
					}
				},
				supplier: {
					validators: {
						notEmpty: {
							message: 'Please Select One'
						},
					}
				},
				bankInformation: {
					validators: {
						notEmpty: {
							message: 'Please Select One'
						},
					}
				},
				quantity: {
					validators: {
							stringLength: {
							min: 1,
						},
							notEmpty: {
							message: 'Please Insert quantity'
						},
						regexp: {
							regexp: /^[0-9]+(?:\.[0-9]+)?$/,
							message: 'Please insert alphanumeric value only'
						}
					}
				},
				
				lotNo: {
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
		 
		 /* min max check for lcOpeningDate & lcDeliveryDate */ 
		  function LcOpenValidate(){
		     
				var min = document.getElementById("add_lcOpeningDate").value;
			    var max = document.getElementById("add_lcDeliveryDate").value;
			   
			   if(max > min) {
				   alert("LC Open Date must be lesser than LC Delivery Date  " + min + " > " + max );
			   } 
		 }    

		function LcDeliveryValidate(){
			   var min = document.getElementById("add_lcOpeningDate").value;
			   var max = document.getElementById("add_lcDeliveryDate").value;
			   if(max<min) {
				   alert("LC Delivery Date must be greater than LC Open Date  "  + min + " > " + max );
				   document.getElementById('add_lcDeliveryDate').value = "";
				   
			   } 
		  }
		  
	     /* min max check for lcOpeningDate & lcDeliveryDate */ 
		  function mfgValidate(){
		     
				var min = document.getElementById("add_mfgDate").value;
			    var max = document.getElementById("add_expDate").value;
			   
			   if(max > min) {
				   alert("LC Open Date must be lesser than LC Delivery Date  " + min + " > " + max );
			   } 
		 }    

		function expValidate(){
			   var min = document.getElementById("add_mfgDate").value;
			   var max = document.getElementById("add_expDate").value;
			   if(max<min) {
				   alert("Exp Date must be greater than Mfg Date  "  + min + " > " + max );
				   document.getElementById('add_expDate').value = "";
				   
			   } 
		  }
/*------------------ End Save Purchase or purchase products & validation panel ---------------------- */

/*------------------ Start Delete Purchase or purchase products ---------------------- */
function deleteForeignPurchase(id){
    var conMsg = confirm("Are you sure to delete??")
	if(conMsg){
	    $.ajax({
			type: 'POST',
			url: 'phpScripts/manageForeignPurchaseProducts-add.php',
			data: "deleteForeignPurchase=1&id="+id,
			dataType: 'json',
			success: function(response){
				if(response == "Success"){
					managePurchaseTable.ajax.reload(null, false);
					$("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> Successfully Deleted");
					$("#divMsg").show().delay(2000).fadeOut().queue(function(n) {
					  $(this).hide(); n();
					});
				}else{
					$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> "+response);
					$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
					  $(this).hide(); n();
					});
				}
			},error: function (xhr) {
				alert(xhr.responseText);
			}
		  });
	}
}

function deleteForeignPurchaseforEdit(id, newId){
    //alert(newId);
    if(newId == 'Soumen'){
        $.ajax({
    		type: 'POST',
    		url: 'phpScripts/manageForeignPurchaseProducts-add.php',
    		data: "deleteForeignPurchase=1&id="+id,
    		dataType: 'json',
    		success: function(response){
    			if(response == "Success"){
    				managePurchaseTable.ajax.reload(null, false);
    				$("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> Successfully Deleted");
    				$("#divMsg").show().delay(2000).fadeOut().queue(function(n) {
    				  $(this).hide(); n();
    				});
    			}else{
    				$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> "+response);
    				$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
    				  $(this).hide(); n();
    				});
    			}
    		},error: function (xhr) {
    			alert(xhr.responseText);
    		}
	    });
    }
}
/*------------------ End Delete Purchase or purchase products ---------------------- */
