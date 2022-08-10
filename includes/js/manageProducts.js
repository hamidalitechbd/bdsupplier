var manageProductsTable;

$(document).ready(function() {
// retrive customer or supplier data
	manageProductsTable = $("#manageProductsTable").DataTable({
		'ajax': 'phpScripts/productsView.php',
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
//Delete Specification
function deleteSpec(specId){
	var conMsg = confirm("Are you sure to delete??")
	if(conMsg){
		var specificationId = specId;
		var fd = new FormData();
		fd.append('specificationId',specificationId);
		fd.append('deleteSpecification',1);
		$.ajax({
		type: 'POST',
		url: 'phpScripts/manageProduct-add.php',
		data: fd,
		contentType: false,
		processData: false,
		dataType: 'json',
		success: function(response){
			var specificationHTML="";
			if(response != ''){
				for(var i=0;i<response.length;i++){
				  specificationHTML += "<tr><td class='col-sm-6'><input type='hidden' id='edit_spacId' name='edit_spacId["+i+"]' value = '"+response[i].id+"' /><input type='text' class='form-control' name='edit_spacName["+i+"]' placeholder='Name' value='"+response[i].specificationName+"'></td><td class='col-sm-6'><input type='text' class='form-control' name='edit_spacValue["+i+"]' placeholder='value' value='"+response[i].specificationValue+"'></td><td><a href='#' onclick='deleteSpec("+response[i].id+")'><span class=' btn btn-danger btn_remove'>X</span></a></td></tr>";
				}
			}else{
				specificationHTML += "<tr><td class='col-sm-6'><input type='hidden' id='edit_spacId' name='edit_spacId[0]' value = '0' /><input type='text' class='form-control' name='edit_spacName[0]' placeholder='Name' value=''></td><td class='col-sm-6'><input type='text' class='form-control' name='edit_spacValue[0]' placeholder='value' value=''></td><td><a href='#'><span class=' btn btn-danger btn_remove'>X</span></a></td></tr>";
			}
			
			$('#itemSpecifications').html(specificationHTML);
		},error: function (xhr) {
			alert(xhr.responseText);
		}
	  });
	}
}
		
// image view
var loadFile = function(event) {
var output = document.getElementById('output');
	output.src = URL.createObjectURL(event.target.files[0]);
};

// image view
var loadFile1 = function(event) {
var output1 = document.getElementById('editViewImage');
	output1.src = URL.createObjectURL(event.target.files[0]);
};
	
	/*------------------ Start Save Item or Products & validation panel ---------------------- */
		//Add Specification of Products Start
		$(document).ready(function(){  
		  var i=1;  
		  $('#add').click(function(){  
			   i++;  
			   $('#dynamic_field').append('<tr id="row'+i+'"><td class="col-sm-6"><input type="text" class="form-control" name="spacName[]" placeholder="Name" required></td><td class="col-sm-6"><input type="text" class="form-control" name="spacValue[]" placeholder=" Value" ></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
		  });
		  $('#editSpac').click(function(){  
			   i++;  
			   $('#itemSpecifications').append('<tr id="row'+i+'"><td class="col-sm-6"><input type="hidden" id="spacId" name="edit_spacId[]" value = "0" /><input type="text" class="form-control" name="edit_spacName[]" placeholder="Name" required></td><td class="col-sm-6"><input type="text" class="form-control" name="edit_spacValue[]" placeholder=" Value" ></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
		  });	  
		  $(document).on('click', '.btn_remove', function(){  
			   var button_id = $(this).attr("id");   
			   $('#row'+button_id+'').remove();  
		  });  
		  $('#submit').click(function(){            
			   $.ajax({ 
					url:"EmMoSlip-add12.php",  
					method:"POST",
					data:$('#add_name').serialize(),  
					success:function(data)  
					{  
						 alert(data);  
						 $('#add_name')[0].reset();  
					}  
			   });  
		  });  
		}); 
		//Add Specification of Products End
		
		$(document).ready(function() {
		$('#form_addProduct').bootstrapValidator({
		live:'enabled',
		message:'This value is not valid',
		submitButton:'$form_addProduct button [type="Submit"]',
		submitHandler: function(validator, form, submitButton){
		//	var wareHouseId = $("#add_wereHouse").val();
		  var productName = $("#add_ProductName").val();
		  var productCode = $("#add_productCode").val();
		  //var openStock=$("#add_openStock").val();
		  var minimumStock=$("#add_lowQuantity").val();
		  var brandId=$("#add_Brand").val();
		  var categoryId=$("#add_category").val();
		  var units=$("#add_units").val();
		  var carton_unit = $("#add_cartonUnit").val();
		  var carton_type = $("#add_cartonUnit").val();
		  var package_name = $("#package_unit").val();
		  var carton_name = "";
		  for(var i = 0; i < carton_unit; i++){
              carton_name += $("#carton_name_"+i).val()+";";
          }
          carton_name = carton_name.slice(0, -1);
		  //var manufacturingDate=$("#add_mfgDate").val();
		  //var expiryDate=$("#add_expDate").val();
		  var standardSalesUnit=$("#add_saleUnit").val();
		  var purchasePrice=$("#add_purchasePrice").val();
		  var minimumSalePrice=$("#add_minSalePrice").val();
		  var maximumSalePrice=$("#add_maxSalePrice").val();
		  var productNote=$("#add_itemNote").val();
		  //var lotNo = $("#add_lotno").val();
		  var modelNo = $("#add_modelNumber").val();
		  var type = $("#product_type").val();
            var stockCheck = $("#stockCheck").val();
            var itemsInBox = $("#itemsInBox").val();
		  //var productImage = $("#add_itemImage").val();
		  var spacName = [];
		  var spacValue=[];
		  var i = 0;
		   $('input[name^="spacName"]').each(function() {
			   spacName[i] = $(this).val()+"@!@";
			   i = i + 1;
			});
			i = 0;
		   $('input[name^="spacValue"]').each(function() {
			   spacValue[i] = $(this).val()+"@!@";
			   i = i + 1;
			});
			
		  var fd = new FormData();
		  var productImage = $('#add_itemImage')[0].files[0];
		  //fd.append('wareHouseId',wareHouseId);
		  fd.append('productName',productName);
		  fd.append('productCode',productCode);
		  //fd.append('openStock',openStock);
		  fd.append('minimumStock',minimumStock);
		  fd.append('brandId',brandId);
		  fd.append('categoryId',categoryId);
		  fd.append('units',units);
		  fd.append('carton_unit',carton_unit);
		  fd.append('carton_type',carton_type);
		  fd.append('package_name',package_name);
		  fd.append('carton_name',carton_name);
		  //fd.append('manufacturingDate',manufacturingDate);
		  //fd.append('expiryDate',expiryDate);
		  fd.append('standardSalesUnit',standardSalesUnit);
		  fd.append('purchasePrice',purchasePrice);
		  fd.append('minimumSalePrice',minimumSalePrice);
		  fd.append('maximumSalePrice',maximumSalePrice);
		  fd.append('productNote',productNote);
		  fd.append('file',productImage);
		  fd.append('saveProduct','1');
		  fd.append('spacName',spacName);
		  fd.append('spacValue',spacValue);  
		  //fd.append('lotNo',lotNo);  
		  fd.append('modelNo',modelNo);
            // Serialize Product
    	    fd.append('type', type);
            fd.append('stockCheck', stockCheck);
            if (type == "serialize") {
                fd.append('itemsInBox', itemsInBox);
            } else {
                fd.append('itemsInBox', 0);
            }
            // End Serialize Product

		  $.ajax({
				type: 'POST',
				url: 'phpScripts/manageProduct-add.php',
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
						manageProductsTable.ajax.reload(null, false);
					//	$("#add_wereHouse").val('').trigger('change');
						$("#add_ProductName").val('');
						$("#add_productCode").val('');
						//$("#add_openStock").val('');
						$("#add_lowQuantity").val('0');
						$("#add_Brand").val('').trigger('change');
						$("#add_category").val('').trigger('change');
						$("#add_units").val('').trigger('change');
						$("#add_cartonUnit").val('0');
						//$("#add_mfgDate").val('');
						//$("#add_expDate").val('');
						$("#add_saleUnit").val('');
						//$("#add_purchasePrice").val('');
						$("#add_minSalePrice").val('');
						$("#add_maxSalePrice").val('');
						$("#add_itemNote").val('');
						//$("#add_lotno").val('');
						$("#add_modelNumber").val('');
						specificationHTML = "<tr><td class='col-sm-6'><input type='text' class='col-sm-6 form-control' name='spacName[]' placeholder='Name' value='' /></td><td class='col-sm-6'><input type='text' class='form-control' name='spacValue[]' placeholder='value' value='' /></td><td><button type='button' name='add' id='add' class='btn btn-success btn-sm add'><span class='glyphicon glyphicon-plus'></span></button></td></tr>";
						$("#dynamic_field").html(specificationHTML);
					}
				},error: function (xhr) {
					alert(xhr.responseText);
				}
			  });
		  
			$('#addnew').modal('hide');
		},
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
		excluded: [':disabled'],
        fields: {
				
				ItemName: {
					validators: {
							stringLength: {
							min: 3,
						},
							notEmpty: {
							message: 'Please Insert Item Name'
						},
						regexp: {
							regexp: /^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/,
							message: 'Please insert alphanumeric value only'
						}
					}
				},
				productCode: {
					validators: {
							stringLength: {
							min: 3,
						},
							notEmpty: {
							message: 'Please Insert Product Name'
						},
						regexp: {
							regexp: /^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/,
							message: 'Please insert alphanumeric value only'
						}
					}
				},
				
				lowQuantity: {
					validators: {
						
						regexp: {
							regexp: /^[0-9]+$/,
							message: 'Only Amount : 200 '
						}
					}
				},
				add_Brand: {
					validators: {
						notEmpty: {
							message: 'Please Select Brand'
						},
					}
				},
				categories: {
					validators: {
						notEmpty: {
							message: 'Please Select categories'
						},
					}
				},
				units: {
					validators: {
						notEmpty: {
							message: 'Please Select units'
						},
					}
				},
				
				minPrice: {
					validators: {
						
						regexp: {
							regexp: /^((-)?(0|([1-9][0-9]*))(\.[0-9]+)?)$/,
							message: 'Insert Price like : 200 '
						}
					}
				},
				
				maxPrice: {
					validators: {
						
						regexp: {
							regexp: /^((-)?(0|([1-9][0-9]*))(\.[0-9]+)?)$/,
							message: 'Insert Price like : 200 '
						}
					}
				},
				add_purchasePrice: {
					validators: {
						
						regexp: {
							regexp: /^((-)?(0|([1-9][0-9]*))(\.[0-9]+)?)$/,
							message: 'Insert Only Price : 200 '
						}
					}
				},
				itemNote: {
					validators: {
							stringLength: {
							min: 3,
						},
						
						regexp: {
							regexp: /^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/,
							message: 'Please insert alphanumeric value only'
						}
					}
				},
				ItemImage: {
					validators: {
						regexp: {
							regexp: /^.*\.(jpg|JPG|jpeg|png)$/,
							message: 'Please insert (jpg|JPG|jpeg|png) only'
						}
					}
				},
				modelNumber: {
					validators: {
						
						regexp: {
						regexp: /^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/,
							message: 'Insert Model Number Only : P-200/ P200 '
						}
					}
				}
			}
			});
		}); 
		
		function MinimumNValidate(){
				var min = parseInt(document.getElementById("add_minSalePrice").value);
			   var max = parseInt(document.getElementById("add_maxSalePrice").value);
			   if(min > max) {
				   alert("Min. price must be lesser than max. price " + min + " > " + max );
			   } 
		 }    

			function MaximumNValidate(){
			   var min = parseInt(document.getElementById("add_minSalePrice").value);
			   var max = parseInt(document.getElementById("add_maxSalePrice").value);
			   if(max<min) {
				   alert("Max. price must be greater than min. price"  + min + " > " + max );
				   
			   } 
		  }
		function add_cartonName(){
		    var carton_name_value = "";
		    var cartonUnit = parseFloat($("#add_cartonUnit").val());
		    if(cartonUnit > 0){
		        var carton_type = $("#add_carton_type").val();
		        if(carton_type == "Carton"){
		            for(var i = 0; i < cartonUnit; i++){
		                carton_name_value += "<input type='text' class='form-control' id='carton_name_"+i+"' name='carton_name' required/> ";
		            }
		            
		        }else{
		            carton_name_value = "";
		        }
		    }else{
		        carton_name_value = "";
		    }
		    $("#add_carton_name").html(carton_name_value);
		}
	/*------------------ End Save Item or Products & validation panel ---------------------- */
	
	/*------------------ Start Edit Item or Products & validation panel ---------------------- */
		
		// start data retraive during Edit Items activities
		
		function editItem(itemId){
			$('#editItem').modal('show');
			//var dataString = "id="+unitId+"&type="+type;
			var dataString = "id="+itemId;
			$.ajax({
				type: 'POST',
				url: 'phpScripts/manageItem-row.php',
				data: dataString,
				dataType: 'json',
				beforeSend: function(){
                    // Show image container
                    $("#editLoader").show();
               },
				success: function(response){
				    //editProductId
				  $('#editId').val(response[0].id);
				  //$('#edittbl_wareHouseID').val(response[0].tbl_wareHouseID).trigger('change');
				  $('#edittbl_brandsId ').val(response[0].tbl_brandsId).trigger('change');
				  $('#editproductCode').val(response[0].productCode);
				  $('#editproductName').val(response[0].productName);
				  //$('#editlotNumber').val(response[0].lotNumber);
				  $('#edit_modelNumber').val(response[0].modelNo);
				  //$('#editmanufacturingDate').val(response[0].manufacturingDate);
				  //$('#editexpireDate').val(response[0].expireDate);
				  $('#editcategoryId').val(response[0].categoryId).trigger('change');
				  $('#editunits').val(response[0].units).trigger('change');
				  var cartonUnit = parseFloat(response[0].carton_unit);
				  $('#editcartonUnit').val(cartonUnit);
				    var carton_type = response[0].carton_type;
				    $('#edit_carton_type').val(carton_type);
				    var carton_name = response[0].carton_name;
				    
				    const cartonNameArray = carton_name.split(";");
				    var cartonNameData = "";
				    var carton_name_value = "";
    		        if(carton_type == "Carton"){
    		            for(var i = 0; i < cartonUnit; i++){
    		                try{
    		                    cartonNameData = cartonNameArray[i];
    		                }catch{
    		                    cartonNameData = "";
    		                }
    		                carton_name_value += "<input type='text' class='form-control' id='editcarton_name_"+i+"' name='carton_name' value='"+cartonNameData+"'/> ";
    		            }
    		            
    		        }else{
    		            carton_name_value = "";
    		        }
				  $('#editcartonName').html(carton_name_value);
				  $('#editpackage_unit').val(response[0].package_unit);
				  $('#edit_creditLimit').val(response[0].creditLimit);
				  $('#editproductDescriptions').val(response[0].productDescriptions);
				  $('#editminimumSalePrice').val(response[0].minSalePrice);
				  $('#editmaxSalePrice').val(response[0].maxSalePrice);
				  $('#editopenStock').val(response[0].openStock);
				  $('#editcurrentStock').val(response[0].currentStock);
				  $('#edit_lowQuantity').val(response[0].minimumStock);
				  $('#editstandardSalesUnit').val(response[0].standardSalesUnit);
				  $('#editpurchasePrice').val(response[0].purchasePrice);
				  $('#edit_status').val(response[0].status);
				  if(response[0].productImage == '' || response[0].productImage == null){
					  $('#editViewImage').attr("src","images/broken_image.png");
				  }else{
					  $('#editViewImage').attr("src","images/products/thumb/"+response[0].productImage+"");
				  }
				  
				  $('#edit_Unitstatus').val(response.status);
					//Load Item Specification
					var specificationHTML="";
				    for(var i=0;i<response.length;i++){
						if(response[i].specificationId != null){
							specificationHTML += "<tr><td class='col-sm-6'><input type='hidden' id='edit_spacId' name='edit_spacId["+i+"]' value = '"+response[i].specificationId+"' /><input type='text' class='col-sm-6 form-control' name='edit_spacName["+i+"]' placeholder='Name' value='"+response[i].specificationName+"' required></td><td class='col-sm-6'><input type='text' class='form-control' name='edit_spacValue["+i+"]' placeholder='value' value='"+response[i].specificationValue+"' required></td><td><a href='#' onclick='deleteSpec("+response[i].specificationId+")'><span class=' btn btn-danger btn_remove'>X</span></a></td></tr>";
						}else{
							specificationHTML += "<tr><td class='col-sm-6'><input type='hidden' id='edit_spacId' name='edit_spacId["+i+"]' value = '0' /><input type='text' class='col-sm-6 form-control' name='edit_spacName["+i+"]' placeholder='Name' value='' required></td><td class='col-sm-6'><input type='text' class='form-control' name='edit_spacValue["+i+"]' placeholder='value' value='' required></td><td><a href='#' onclick='deleteSpec("+response[i].specificationId+")'><span class=' btn btn-danger btn_remove'>X</span></a></td></tr>";
						}
					}
					$('#itemSpecifications').html(specificationHTML);
				  
				},error: function (xhr) {
					alert(xhr.responseText);
				},
                complete:function(data){
                    // Hide image container
                    $("#editLoader").hide();
                }
			});
		}
		// End data retraive during Edit Items activities
		
		function edit_cartonName(){
		    var carton_name_value = "";
		    var cartonUnit = parseFloat($("#editcartonUnit").val());
		    if(cartonUnit > 0){
		        var carton_type = $("#edit_carton_type").val();
		        if(carton_type == "Carton"){
		            for(var i = 0; i < cartonUnit; i++){
		                var carton_name_data = $("#editcarton_name_"+i).val();
		                if(carton_name_data == 'undefined'){
		                    carton_name_data = "";
		                }
		                carton_name_value += "<input type='text' class='form-control' id='editcarton_name_"+i+"' name='carton_name' value='"+carton_name_data+"'/> ";
		            }
		        }else{
		            carton_name_value = "";
		        }
		    }else{
		        carton_name_value = "";
		    }
		    $("#editcartonName").html(carton_name_value);
		}
		
		$(document).ready(function() {
		$('#form_updateProduct').bootstrapValidator({
		live:'enabled',
		message:'This value is not valid',
		submitButton:'$form_updateProduct button [type="Submit"]',
		submitHandler: function(validator, form, submitButton){
		//	var wareHouseId = $("#edittbl_wareHouseID").val();
		  var productName = $("#editproductName").val();
		  var productCode = $("#editproductCode").val();
		 // var openStock = $("#editopenStock").val();
		  var minimumStock = $("#edit_lowQuantity").val();
		  var brandId = $("#edittbl_brandsId").val();
		  var categoryId = $("#editcategoryId").val();
		  var units = $("#editunits").val();
		  var carton_unit = $("#editcartonUnit").val();
		   var carton_type = $("#edit_carton_type").val();
		  var package_name = $("#editpackage_unit").val();
		  var carton_name = "";
		  if(carton_type == 'Carton'){
    		  for(var i = 0; i < carton_unit; i++){
                  carton_name += $("#editcarton_name_"+i).val()+";";
              }
		  }
          carton_name = carton_name.slice(0, -1);
		  //var manufacturingDate = $("#editmanufacturingDate").val();
		  //var expiryDate = $("#editexpireDate").val();
		  var standardSalesUnit = $("#add_saleUnit").val();
		  var purchasePrice = $("#editpurchasePrice").val();
		  var minimumSalePrice = $("#editminimumSalePrice").val();
		  var maximumSalePrice = $("#editmaxSalePrice").val();
		  var productNote = $("#editproductDescriptions").val();
		 // var lotNo = $("#editlotNumber").val();
		  var modelNo = $("#edit_modelNumber").val();
		  var productId = $("#editId").val();
		  var status = $("#edit_status").val();
	         var type = $("#product_type").val();
            var stockCheck = $("#stockCheck").val();
            var itemsInBox = $("#itemsInBox").val();
		  var spacId = [];
		  var spacName = [];
		  var spacValue=[];
		  var i = 0;
		  $('input[name^="edit_spacName"]').each(function() {
		       spacName[i] = $(this).val()+"@!@";
			   i = i + 1;
		  });
		  i = 0;
		  $('input[name^="edit_spacId"]').each(function() {
		       spacId[i] = $(this).val()+"@!@";
			   i = i + 1;
		  });
			i = 0;
		  $('input[name^="edit_spacValue"]').each(function() {
			   spacValue[i] = $(this).val()+"@!@";
			   i = i + 1;
		  });
			
		  var fd = new FormData();
		  var productImage = $('#editproductImage')[0].files[0];
		  
		 // fd.append('wareHouseId',wareHouseId);
		  fd.append('productId',productId);
		  fd.append('productName',productName);
		  fd.append('productCode',productCode);
		  //fd.append('openStock',openStock);
		  fd.append('minimumStock',minimumStock);
		  fd.append('brandId',brandId);
		  fd.append('categoryId',categoryId);
		  fd.append('units',units);
		  fd.append('carton_unit',carton_unit);
		  fd.append('carton_type',carton_type);
		  fd.append('package_name',package_name);
		  fd.append('carton_name',carton_name);
		  //fd.append('manufacturingDate',manufacturingDate);
		  //fd.append('expiryDate',expiryDate);
		  fd.append('standardSalesUnit',standardSalesUnit);
		  fd.append('purchasePrice',purchasePrice);
		  fd.append('minimumSalePrice',minimumSalePrice);
		  fd.append('maximumSalePrice',maximumSalePrice);
		  fd.append('productNote',productNote);
		  fd.append('file',productImage);
		  fd.append('updateProduct','1');
		  fd.append('spacId',spacId);
		  fd.append('spacName',spacName);
		  fd.append('spacValue',spacValue);  
		  //fd.append('lotNo',lotNo);  
		  fd.append('modelNo',modelNo);
		  fd.append('status',status);
    	    // Serialize Product
    	    fd.append('type', type);
            fd.append('stockCheck', stockCheck);
            if (type == "serialize") {
                /*storeSerialNumbers();
                storeStockQuantity();
                let totalStockQuantity = parseFloat($("#totalStockQuantity").text());*/
                fd.append('itemsInBox', itemsInBox);
                /*fd.append('serialNumbers', serialNumbers);
                fd.append('stockQuantities', stockQuantities);
                fd.append('totalStockQuantity', totalStockQuantity);
                if (opening_stock != totalStockQuantity) {
                    Swal.fire("Warning: ", "Openning Stock Must Be Equal To Total Quantity! ", "warning");
                    return 0;
                }*/
            } else {
                fd.append('itemsInBox', 0);
            }
            // End Serialize Product
		  
		  $.ajax({
			type: 'POST',
			url: 'phpScripts/manageProduct-add.php',
			data: fd,
			contentType: false,
			processData: false,
			dataType: 'json',
           success: function(msg) {
              $("#loading-image").hide();
           },
			success: function(response){
				if(response == "Success"){
						$("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> Update Successfully");
					   $("#divMsg").show().delay(2000).fadeOut().queue(function(n) {
						  $(this).hide(); n();
						});
					manageProductsTable.ajax.reload(null, false);
					$("#add_wereHouse").val('');
				}else{
				    alert(response);
				}
			},error: function (xhr) {
				alert(xhr.responseText);
			}
		  });
		  
		  $('#editItem').modal('hide');
		},
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
		excluded: [':disabled'],
        fields: {
			
				ItemName: {
					validators: {
							stringLength: {
							min: 3,
						},
							notEmpty: {
							message: 'Please Insert Product Name'
						},
						regexp: {
							regexp: /^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/,
							message: 'Please insert alphanumeric value only'
						}
					}
				},
				productCode: {
					validators: {
							stringLength: {
							min: 3,
						},
							notEmpty: {
							message: 'Please Insert Product Code'
						},
						regexp: {
							regexp: /^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/,
							message: 'Please insert alphanumeric value only'
						}
					}
				},
				
				lowQuantity: {
					validators: {
						
						regexp: {
							regexp: /^[0-9]+$/,
							message: 'Only Amount : 200 '
						}
					}
				},
				add_Brand: {
					validators: {
						notEmpty: {
							message: 'Please Select Brand'
						},
					}
				},
				categories: {
					validators: {
						notEmpty: {
							message: 'Please Select categories'
						},
					}
				},
				units: {
					validators: {
						notEmpty: {
							message: 'Please Select units'
						},
					}
				},
				
				itemNote: {
					validators: {
							stringLength: {
							min: 3,
						},
						
						regexp: {
							regexp: /^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/,
							message: 'Please insert alphanumeric value only'
						}
					}
				},
				ItemImage: {
					validators: {
						regexp: {
							regexp: /^.*\.(jpg|JPG|jpeg|png)$/,
							message: 'Please insert (jpg|JPG|jpeg|png) only'
						}
					}
				},
				modelNumber: {
					validators: {
						
						regexp: {
							regexp: /^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/,
							message: 'Insert Model Number Only : P-200/ P200 '
						}
					}
				}
			}
			});
		}); 
		
		function editOpenStock(id) {
		    var dataString = "page=editOpeningStock&id="+id;
            $.ajax({
                method: "GET",
                url: 'phpScripts/manageProduct-add.php',
                data: dataString,
                dataType: "json",
                success: function(result) {
                    $("#editOpenStockModal").modal('show');
                    $("#editOpenStockId").val(result.row_product.id);
                    $("#editOpenStockName").val(result.row_product.productName);
                    $("#editOpenStockInsert").val(result.row_product.openStock);
                    $("#editOSProductType").val(result.row_product.type);
                    $("#editOSItemsInBox").val(result.row_product.items_in_box);
                    $("#initialStockData").html(result.initialStockData);

                },
                error: function(response) {
                    alert(JSON.stringify(response));

                },
                beforeSend: function() {
                    $('#loading').show();
                },
                complete: function() {
                    $('#loading').hide();
                }
            });
        }
	/*------------------ End Edit Item or Products & validation panel ---------------------- */
	//=========== Start Serialize Product ===========//
        var serialNumbers = [];
        var stockQuantities = [];
        var tempOpeningStock = 0;
        var tempitemsInBox = 0;

        function checkType() {
            
            $("#showBtn").hide();
            let itemsInBox = $("#itemsInBox").val();
            //let openingStock = $("#opening_stock").val();
            var type = $("#product_type").val();
            if (type == "serialize") {
                $("#itemsInBox").prop("disabled", false);
                
                $("#stockCheck").val('Yes');
                //$("#stockCheck").prop("disabled", true);
                /*if (openingStock > 0 && itemsInBox > 0) {
                    if (tempOpeningStock != openingStock || tempitemsInBox != itemsInBox) {
                        tempOpeningStock = openingStock;
                        tempitemsInBox = itemsInBox;
                        serialNumbers = [];
                        stockQuantities = [];
                    } else {
                        storeSerialNumbers();
                        storeStockQuantity();
                    }
                    addProductSerial(type, openingStock, itemsInBox);
                }*/
            } else {
                //$("#stockCheck").prop("disabled", false);
                $("#showBtn").hide();
                $("#itemsInBox").val('');
                $("#itemsInBox").prop("disabled", true);
                serialNumbers = [];
                stockQuantities = [];
            }
        }
        function calculate_openingStock(){
            $("#OSserialize_data").hide();
            var type = $("#editOSProductType").val();
            let itemsInBox = $("#editOSItemsInBox").val();
            let openingStock = $("#editOpenStockInsert").val();
            if (type == "serialize") {
                $("#OSserialize_data").show();
                if (openingStock > 0 && itemsInBox > 0) {
                    if (tempOpeningStock != openingStock || tempitemsInBox != itemsInBox) {
                        tempOpeningStock = openingStock;
                        tempitemsInBox = itemsInBox;
                        serialNumbers = [];
                        stockQuantities = [];
                    } else {
                        storeSerialNumbers();
                        storeStockQuantity();
                    }
                    addProductSerial(type, openingStock, itemsInBox);
                }
            }else{
                $("#OSserialize_data").hide();
            }
        }
        function storeSerialNumbers() {
            serialNumbers = $('input[id^=serialNo]').map(function(index, serialNo) {
                return $(serialNo).val();
            }).get();
        }

        function storeStockQuantity() {
            stockQuantities = $('input[name^=stockQuantity]').map(function(index, quantity) {
                return $(quantity).val();
            }).get();
        }

        function addProductSerial(type, openingStock, itemsInBox) {
            var carton = openingStock / itemsInBox;
            if (stockQuantities.length > 0) {
                carton = stockQuantities.length;
            }
            let rows = '';
            var remainingStock = openingStock;
            if (type == "serialize" && carton > 0) {
                for (let i = 0; i < carton; i++) {
                    let serialNo = i + 1;
                    remainingStock = remainingStock - itemsInBox;
                    if (remainingStock < 0) {
                        itemsInBox = parseFloat(itemsInBox) + parseFloat(remainingStock);
                    }
                    if (stockQuantities.length > 0) {
                        itemsInBox = stockQuantities[i];
                        serialNo = serialNumbers[i];
                    }
                    rows += '<tr id="row' + i + '">' +
                        '<td>' + (i + 1) + '</td>' +
                        '<td><input class="form-control input-sm stockQuantity' + i +
                        '" id="stockQuantity" value="' +
                        itemsInBox +
                        '" type="number" name="stockQuantity" placeholder=" Quantity... " required oninput="calculateTotalQuantity()"></td>';
                    if (i == 0) {
                        rows +=
                            '<td><input class="form-control input-sm serialNo0" id="serialNo" type="text" name="serialNo" placeholder=" Serial... " required oninput="generateSerialNo(this.value);" value="' +
                            serialNo + '"><td><a href="#" onclick="removeRow(' +
                            i + ')" style="color:red;"><i class="fa fa-trash"> </i> </a></td></td>';
                    } else {
                        rows +=
                            '<td><input class="form-control input-sm serialNo' + i +
                            '" id="serialNo" type="text" name="serialNo" placeholder=" Serial... " value="' + serialNo +
                            '" required><td><a href="#" onclick="removeRow(' +
                            i + ')" style="color:red;"><i class="fa fa-trash"> </i> </a></td></td>';
                    }
                    rows += '</tr>';
                }
                $("#OSserialize_data_table").html('');
                $("#OSserialize_data_table").html(rows);
                $("#editOpenStockInsert").text(openingStock);
                //$("#showBtn").removeClass("d-none");
                //$("#serializeProductModal").modal("show");
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
            storeSerialNumbers();
        }

        function addRow() {
            var trId = $('#OSserialize_data_table tr:last').attr('id');
            trId = parseInt(trId.substring(3)) + 1;
            let serialNum = parseInt($(".serialNo" + (trId - 1)).val()) + 1;
            let rows = '';
            rows += '<tr id="row' + trId + '">' +
                '<td>' + (trId + 1) + '</td>' +
                '<td><input class="form-control input-sm stockQuantity' + trId +
                '" id="stockQuantity" type="number" name="stockQuantity" placeholder=" Quantity... " required oninput="calculateTotalQuantity()"></td>';
            rows += '<td><input class="form-control input-sm serialNo' + trId +
                '" id="serialNo" type="text" name="serialNo" placeholder=" Serial... " required value="' + serialNum +
                '" ><td><a href="#" onclick="removeRow(' +
                trId + ')" style="color:red;"><i class="fa fa-trash"> </i> </a></td></td></tr>';
            $("#OSserialize_data_table").append(rows);
        }

        function removeRow(rowNumber) {
            $('#row' + rowNumber).remove();
            $("#OSserialize_data_table").find('tr').each(function(i, el) {
                $(el).find("td").eq(0).text(i + 1);
            });
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Confirmed',
                showConfirmButton: false,
                timer: 200,
            });
            calculateTotalQuantity();
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
            $("#editOpenStockInsert").val(totalStockQuantity);
            tempOpeningStock = totalStockQuantity;
            storeStockQuantity();
            storeSerialNumbers();
        }
        
        $("#editOpenStockProductForm").submit(function(e) {
            e.preventDefault();
            var productId = $("#editOpenStockId").val();
            var openingStock = $("#editOpenStockInsert").val();
            var warehouseId = $("#edit_open_stock_warehouse").val();
            var editOSProductType = $("#editOSProductType").val();
            var id = $("#editId").val();
            var stockQuantities = $('input[name^=stockQuantity]').map(function(index, quantity) {
                                        return $(quantity).val();
                                    }).get();
            var serialNumbers = $('input[id^=serialNo]').map(function(index, serialNo) {
                                    return $(serialNo).val();
                                }).get();
            var fd = new FormData();
            fd.append('productId', productId);
            fd.append('openingStock', openingStock);
            fd.append('warehouseId', warehouseId);
            fd.append('editOSProductType', editOSProductType);
            fd.append('stockQuantities', stockQuantities);
            fd.append('serialNumbers', serialNumbers);
            fd.append('action', 'updateOpeningStock');
            $.ajax({
                url: "phpScripts/manageItem-row.php",
                method: "POST",
                data: fd,
                contentType: false,
                processData: false,
                dataType:'json',
                success: function(result) {
                    $("#editOpenStockModal").modal('hide');
                    if(result == "Success"){
						$("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> Update Opening Stock Successfully");
					   $("#divMsg").show().delay(2000).fadeOut().queue(function(n) {
						  $(this).hide(); n();
						});
					    manageProductsTable.ajax.reload(null, false);
    				}else{
    				    alert(result);
    				}
                    
                },
                error: function(response) {
                       alert(JSON.stringify(response));
                },
                beforeSend: function() {
                    $('#loading').show();
                },
                complete: function() {
                    $('#loading').hide();
                }
            })
        })
        /**/
        //=========== End Serialize Product ===========//
	/*------------------ Start Save & Edit Item or Products select2 panel ---------------------- */
	    
	    $("#add_Brand").select2( {
			placeholder: "Select Brand",
			dropdownParent: $("#addnew"),
			allowClear: true
		});
		$("#add_category").select2( {
			placeholder: "Select Category",
			dropdownParent: $("#addnew"),
			allowClear: true
		} );
		$("#add_units").select2( {
			placeholder: "Select Units",
			dropdownParent: $("#addnew"),
			allowClear: true
		} );
		// edit panel bellow
	
		$("#edittbl_brandsId").select2( {
			placeholder: "Select Brand",
			dropdownParent: $("#editItem"),
			allowClear: true
		} );
		$("#editcategoryId").select2( {
			placeholder: "Select Category",
			dropdownParent: $("#editItem"),
			allowClear: true
		} );
		$("#editunits").select2( {
			placeholder: "Select Units",
			dropdownParent: $("#editItem"),
			allowClear: true
		} );
		
	/*------------------ End Save Item or Products select2 panel ---------------------- */