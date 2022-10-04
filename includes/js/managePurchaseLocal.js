var managePurchaseTable;
loadCustomersSuppliers("Customers");
$(document).ready(function() {
	managePurchaseTable = $("#managePurchaseTable").DataTable({
		'ajax': 'phpScripts/purchaseView.php?sortData='+$("#sortData").val(),
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
		url: 'phpScripts/purchaseProductView.php?userId=1&newSessionId='+$('#add_sessionId').val(),
		success: function(response){
			var res = response.split("@!@");
			$('#managePurchaseProductTable').html(res[0]);
			if(res.length > 1){
    			$('#add_grandTotal').val(res[1]);
    			if($('#add_paid').val() == ''){
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
});

$( "#sortData" ).change(function() {
    managePurchaseTable.ajax.url("phpScripts/purchaseView.php?sortData="+$("#sortData").val()).load();
});
 
//Temp Purchase Products Table
function loadPurchaseProductTable(){
    $.ajax({ 
      type: 'GET', 
      url: 'phpScripts/purchaseProductView.php?sessionId='+$('#add_sessionId').val(), 
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
      url: 'phpScripts/purchaseProductView.php?id='+$('#edit_purchaseId').val(), 
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
        var dataString = "id="+tempPurchaseProductsId+"&deleteTemporaryPurchaseProducts=1";
        $.ajax({
            type: 'POST',
            url: 'phpScripts/manageTemporaryPurchaseProducts-add.php',
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
		var dataString = "id="+tempPurchaseProductsId+"&deletePurchaseProducts=1";
		$.ajax({
			type: 'POST',
			url: 'phpScripts/manageTemporaryPurchaseProducts-add.php',
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
//Edit Unit
function editCustomerSupplier(partyId,tblType){
    $('#editCustomerSupplier').modal('show');
    //var dataString = "id="+unitId+"&type="+type;
	var dataString = "id="+partyId+"&type="+tblType;
    $.ajax({
        type: 'POST',
        url: 'phpScripts/manageCustomerSupplier-row.php',
        data: dataString,
        dataType: 'json',
        success: function(response){
          $('#Uid').val(response.id);
          $('#edit_partyName').val(response.partyName);
          $('#edit_tblCountry').val(response.tblCountry);
          $('#edit_tblCity').val(response.tblCity);
          $('#edit_partyAddress').val(response.partyAddress);
          $('#edit_partyType').val(response.partyType);
          $('#edit_contactPerson').val(response.contactPerson);
          $('#edit_partyPhone').val(response.partyPhone);
          $('#edit_partyEmail').val(response.partyEmail);
          $('#edit_remarks').val(response.remarks);
          $('#edit_status').val(response.status);
          $('#edit_creditLimit').val(response.creditLimit);
		  
          $('#edit_Unitstatus').val(response.status);
        },error: function (xhr) {
            alert(xhr.responseText);
        }
    });
}

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
      
      fd.append('editPurchase','1');
      
    $.ajax({
        type: 'POST',
        url: 'phpScripts/manageTemporaryPurchaseProducts-add.php',
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




//Update Unit
$("#form_editUnit").submit(function(event) {
  event.preventDefault();
  var unitName = $("#edit_UnitName").val();
  var unitDescription = $("#edit_UnitDescription").val();
  var unitStatus = $("#edit_Unitstatus").val();
  var type=$("#edit_type").val();
  var id = $("#Uid").val();
  var dataString = "id="+id+"&type="+type+"&UnitName="+unitName+"&UnitDescription="+unitDescription+"&Ustatus="+unitStatus+"&editUnit=1";
  $.ajax({
        type: 'POST',
        url: 'phpScripts/manage-add.php',
        data: dataString,
        dataType: 'json',
        success: function(response){
          manageCustomerSupplierTable.ajax.reload(null, false);
        },error: function (xhr) {
            alert(xhr.responseText);
        }
      });
  
  $('#editUnit').modal('hide');
});
$("#form_editCustomer").submit(function(event) {
  event.preventDefault();
  var tblType = $("#add_tblType").val();
  var tblUid = $("#Uid").val();
  var customerName = $("#edit_partyName").val();
  var emailAddress = $("#edit_partyEmail").val();
  var contactPerson=$("#edit_contactPerson").val();
  var phoneNumber=$("#edit_partyPhone").val();
  var countryName=$("#edit_tblCountry").val();
  var cityName=$("#edit_tblCity").val();
  var customerType=$("#edit_partyType").val();
  var customerStatus=$("#edit_status").val();
  var creditLimit=$("#edit_creditLimit").val();
  var address=$("#edit_partyAddress").val();
  
  var dataString = "TblUid="+tblUid+"&CityName="+cityName+"&CountryName="+countryName+"&TblType="+tblType+"&CustomerName="+customerName+"&EmailAddress="+emailAddress+"&ContactPerson="+contactPerson+"&PhoneNumber="+phoneNumber+"&CountryName="+countryName+"&CityName="+cityName+"&CustomerType="+customerType+"&CustomerStatus="+customerStatus+"&CreditLimit="+creditLimit+"&Address="+address+"&updateCustomerSupplier=2";
  
  $.ajax({
        type: 'POST',
        url: 'phpScripts/manageCustomerSupplier-add.php',
        data: dataString,
        dataType: 'json',
        success: function(response){
          manageCustomerSupplierTable.ajax.reload(null, false);
        },error: function (xhr) {
            alert(xhr.responseText);
        }
      });
  
  $('#editCustomerSupplier').modal('hide');
});
function addSupplier(tblType){
	$('#addnew').modal('show');
	$('#add_tblType').val(tblType);
	$('#typeHeading').html(tblType);
	$('#add_pageName').val('Purchase');
}
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
				$("#add_supplier").append("<option value='"+id+"'>"+partyName+"</option>");
                //$("#edit_supplier").append("<option value='"+id+"'>"+partyName+"</option>");
			}
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
	  
	  fd.append('saveTemporaryPurchaseProducts','1');
	  let productType = $("#add_productType").val();
	  if(productType == "serialize"){
    	  var stockQuantities = new Array();
            let i = 0;
            $('[name="stockQuantity"]').each(function() {
                let quantity = $(this).val();
                if (quantity != '') {
                    stockQuantities[i] = quantity;
                    i++;
                }
            });
            var serialNumbers = $('input[id^=serialNo]').map(function(index, serialNo) {
                return $(serialNo).val();
            }).get();
            fd.append('productType', productType);
            fd.append('serialNumbers', serialNumbers);
            fd.append('stockQuantities', stockQuantities);
	  }


	  $.ajax({
			type: 'POST',
			url: 'phpScripts/manageTemporaryPurchaseProducts-add.php',
			data: fd,
			contentType: false,
			processData: false,
			dataType: 'json',
			success: function(response){
				if(response == "Success"){
				    //updateCart(productId,wareHouseId,true);
					if($("#edit_purchaseId").val() == "" || $("#edit_purchaseId").val() == "undefined"){
						loadPurchaseProductTable();
					}else{
						loadRealPurchaseProductsTable();
					}
				}else{
					alert(response);
				}
			},error: function (xhr) {
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
				mfgDate: {
                validators: {
                    date: {
                        message: 'The date is not valid sdf',
                        format: 'YYYY/MM/DD'
                    },
                }
				},
				expDate: {
                validators: {
                    date: {
                        message: 'The date is not valid sdf',
                        format: 'YYYY/MM/DD'
                    },
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
	
	// Save purchase form
	$("#form_addPurchase").submit(function(event) {
	  event.preventDefault();
	  var purchaseDate = $("#add_purchaseDate").val();
	  var supplier = $("#add_supplier").val();
	  var chalanNumber = $("#add_chalanNumber").val();
	  var sessionId=$('#add_sessionId').val();
	  var totalAmount=$('#add_grandTotal').val();
	  var paidAmount=$('#add_paid').val();
	  var dueAmount=$('#add_due').val();
		
	  var fd = new FormData();
	  fd.append('purchaseDate',purchaseDate);
	  fd.append('supplier',supplier);
	  fd.append('chalanNumber',chalanNumber);
	  fd.append('totalAmount',totalAmount);
	  fd.append('paidAmount',paidAmount);
	  fd.append('dueAmount',dueAmount);
	  fd.append('sessionId',sessionId);
	  
	  fd.append('savePurchase','1');
	  
	  $.ajax({
			type: 'POST',
			url: 'phpScripts/manageTemporaryPurchaseProducts-add.php',
			data: fd,
			contentType: false,
			processData: false,
			dataType: 'json',
			success: function(response){
				if(response.msg == "Success"){
				    window.open(window.location.origin+'/productViewDetails.php?id='+response.purchaseId, '_blank');
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
	});
	    /* min max check for price*/
		function MinimumNValidate(){
				var min = parseInt(document.getElementById("add_minPrice").value);
			   var max = parseInt(document.getElementById("add_maxPrice").value);
			   if(min > max) {
				   alert("Min. price must be lesser than max. price " + min + " > " + max );
			   } 
		 }    

		function MaximumNValidate(){
			   var min = parseInt(document.getElementById("add_minPrice").value);
			   var max = parseInt(document.getElementById("add_maxPrice").value);
			   if(max<min) {
				   alert("Max. price must be greater than min. price"  + min + " > " + max );
				   
			   } 
		  }
		  
		 /* min max check for mfg & exp date*/ 
		  function mfgValidate(){
				var min = document.getElementById("add_mfgDate").value;
			    var max = document.getElementById("add_expDate").value;
			   if(max > min) {
				   alert("MFG. Date must be lesser than EXP. Date  " + min + " > " + max );
				   
			   } 
		 }    

		function expValidate(){
			   var min = document.getElementById("add_mfgDate").value;
			   var max = document.getElementById("add_expDate").value;
			   if(max<min) {
				   alert("EXP. Date must be greater than MFG. Date  "  + min + " > " + max );
				   document.getElementById('add_expDate').value = "";
				   
			   } 
		  }
/*------------------ End Save Purchase or purchase products & validation panel ---------------------- */

/*------------------ Start Delete Purchase or purchase products ---------------------- */
function deletePurchase(id){
    var conMsg = confirm("Are you sure to delete??")
	if(conMsg){
	    $.ajax({
			type: 'POST',
			url: 'phpScripts/manageTemporaryPurchaseProducts-add.php',
			data: "deletePurchase=1&id="+id,
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
	}else{
	    $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Cancelled by user.");
		$("#divErrorMsg").show().delay(2000).fadeOut().queue(function(n) {
		  $(this).hide(); n();
		});
	}
}
/*------------------ End Delete Purchase or purchase products ---------------------- */

   //=========== Start Serialize Product ===========//

        function showSerializTable() {
            let type = $("#add_productType").val();
            if(type == "serialize"){
                let id=$("#add_products").val();
                let warehouseId = $("#add_wereHouse").val();
                let quantity = $("#add_quantity").val();
                let items_in_box = $("#add_items_in_box").val();
                if(id > 0 && warehouseId > 0){
                    $("#serializProductId").val(id);
                    $("#serializProductWarehouseId").val(warehouseId);
                    var fd = new FormData();
                    fd.append('id', id);
                    fd.append('warehouseId', warehouseId);
                    fd.append('quantity', quantity);
                    fd.append('items_in_box', items_in_box);
                    fd.append('action', "showSerializTable");
                    $.ajax({
                        url: "phpScripts/manageTemporaryPurchaseProducts-add.php",
                        method: "POST",
                        data: fd,
                        contentType: false,
                        processData: false,
                        dataType: "json",
                        success: function(result) {
                            if (result.displayTable) {
                                $("#serializeProductTable").html('');
                                $("#serializeProductTable").html(result.displayTable);
                                fetchCart(id, warehouseId)
                                calculateTotalQuantity();
                            }
                        },
                        beforeSend: function() {
                            $('#loading').show();
                        },
                        complete: function() {
                            $('#loading').hide();
                        },
                        error: function(response) {
                            $("#serializeProductTable").text("Something Went Wrong.Please Try Again"+JSON.stringify(response));
                        }
                    });
                }
            }else{
                $("#serializeProductTable").html('');
            }
        }

        function generateSerialNo(num) {
            let len = $('input[name^=serialNo]').length;
            serialNo = parseInt(num);
            if (num > 0) {
                for (let i = 1; i <= len; i++) {
                    $(".serialNo" + i).val((serialNo + i));
                }
            }
        }
        function updateCart(id, warehouse_id, product_type = null) {
            var fd = new FormData();
            if (product_type == true) {
                var stockQuantities = new Array();
                let i = 0;
                $('[name="stockQuantity"]').each(function() {
                    let quantity = $(this).val();
                    if (quantity != '') {
                        stockQuantities[i] = quantity;
                        i++;
                    }
                });
                var serialNumbers = $('input[id^=serialNo]').map(function(index, serialNo) {
                    return $(serialNo).val();
                }).get();
                fd.append('product_type', product_type);
                fd.append('serialNumbers', serialNumbers);
                fd.append('stockQuantities', stockQuantities);
            }
            fd.append('warehouseId', warehouse_id);
            fd.append('id', id);
            fd.append('action', 'updateCart');
            $.ajax({
                url: "phpScripts/manageTemporaryPurchaseProducts-add.php",
                method: "POST",
                data: fd,
                contentType: false,
                processData: false,
                dataType: "json",
                success: function(result) {
                    if (result == "Success") {
                        
                    } else {
                        alert("Error To update cart");
                    }
                },
                error: function(response) {
                    alert(JSON.stringify(response));
                }
            });
        }
        function fetchCart(id, warehouse_id) {
            var fd = new FormData();
            fd.append('action', "fetchCart");
            fd.append('id', id);
            fd.append('warehouse_id', warehouse_id);
            $.ajax({
                
                url: "phpScripts/manageTemporaryPurchaseProducts-add.php",
                method: "POST",
                data: fd,
                contentType: false,
                processData: false,
                dataType: "json",
                success: function(result) {
                    $("#manageCartTable").html(result.data.cart);
                    $("#totalAmount").text(result.data.totalAmount);
                    calculateTotal();
                },
                beforeSend: function() {
                    $('#loading').show();
                },
                complete: function() {
                    $('#loading').hide();
                },
                error: function(response) {
                    $("#products").text("No such product available in your system 1 " + JSON
                        .stringify(
                            response));
                }
            })
        }
        function calculateTotalQuantity() {
            var totalStockQuantity = 0;
            $('[name="stockQuantity"]').each(function() {
                var currentTxtQuantity = $(this).val();
                if (currentTxtQuantity == '') {
                    currentTxtQuantity = 0;
                }
                totalStockQuantity += parseFloat(currentTxtQuantity);
            });
            $("#totalStockQuantity").text(totalStockQuantity);
            id = $("#serializProductId").val();
            warehouseId = $("#serializProductWarehouseId").val();
            $("#add_quantity").val(totalStockQuantity);
        }

        function addRow() {
            var trId = $('#serializeProductTable tr:last').attr('id');
            trId = parseInt(trId.substring(3)) + 1;
            id = $("#serializProductId").val();
            warehouseId = $("#serializProductWarehouseId").val();
            let serialNo = parseInt($(".serialNo" + (trId - 1)).val()) + 1;
            let rows = '';
            rows += '<tr id="row' + trId + '">' +
                '<td>' + (trId + 1) + '</td>' +
                '<td><input class="form-control input-sm stockQuantity' + trId +
                '" id="stockQuantity" type="text" name="stockQuantity" placeholder=" Quantity... " required oninput="calculateTotalQuantity()" onblur="loadCartandUpdate(' +
                id + ',' + warehouseId + ',' + true + ')"></td>';
            rows +=
                '<td><input class="form-control input-sm serialNo' + trId +
                '" id="serialNo" type="text" name="serialNo" placeholder=" Serial... " required value=' + serialNo +
                '><td><a href="#" onclick="removeRow(' +
                trId + ')" style="color:red;"><i class="fa fa-trash"> </i> </a></td></td></tr>';
            $("#serializeProductTable").append(rows);
        }

        function removeRow(rowNumber) {
            $('#row' + (rowNumber)).remove();
            $("#serializeProductTable").find('tr').each(function(i, el) {
                $(el).find("td").eq(0).text(i + 1);
            });
            id = $("#serializProductId").val();
            warehouseId = $("#serializProductWarehouseId").val();
            let product_type = true;
            calculateTotalQuantity();
            updateCart(id, warehouseId, product_type);
        }
        //=========== End Serialize Product ===========//
