	var manageCustomerSupplierTable;
	/*--------- Start View customer /supplier from databse to datatable ---------------*/
		$(document).ready(function() {
			// retrive customer or supplier data
			manageCustomerSupplierTable = $("#manageCustomerSupplierTable").DataTable({
				'ajax': 'phpScripts/customerSupplierView.php?page='+$("#type").val()+'',
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
	/*--------- End View customer /supplier from databse to datatable ---------------*/

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
            beforeSend: function(){
                // Show image container
                $("#editLoader").show();
           },
            success: function(response){
              $('#Uid').val(response.id);
              $('#edit_partyName').val(response.partyName);
              $('#edit_tblCountry').val(response.tblCountry);
              $('#edit_tblCity').val(response.tblCity);
              $('#edit_locationArea').val(response.locationArea);
              $('#edit_partyAddress').val(response.partyAddress);
              $('#edit_partyType').val(response.partyType);
              $('#edit_contactPerson').val(response.contactPerson);
              $('#edit_partyPhone').val(response.partyPhone);
              $('#edit_altphoneNumber').val(response.partyAltPhone);
              $('#edit_partyEmail').val(response.partyEmail);
              $('#edit_remarks').val(response.remarks);
              $('#edit_status').val(response.status);
              $('#edit_creditLimit').val(response.creditLimit);
              $('#edit_tblType').val(response.tblType);
    		  $('#EdiCustomerSalesType').val(response.customerSalesType);
              $('#edit_Unitstatus').val(response.status);
            },error: function (xhr) {
                alert(xhr.responseText);
            },
            complete:function(data){
                // Hide image container
                $("#editLoader").hide();
            }
        });
    }
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
    

	/*----------------Start CRM Save & validation parts----------------------*/
		$(document).ready(function() {
		$('#form_addCustomer').bootstrapValidator({
		live:'enabled',
		message:'This value is not valid',
		submitButton:'$form_addCustomer button [type="Submit"]',
		submitHandler: function(validator, form, submitButton){
			var tblType = $("#add_tblType").val();
			var customerName =$("#add_customerName").val();
			var emailAddress = $("#add_emailAddress").val();
			var contactPerson=$("#add_contactPerson").val();
			var phoneNumber=$("#add_phoneNumber").val();
			var altphoneNumber=$("#add_altphoneNumber").val();
			var countryName=$("#add_countryName").val();
			var cityName=$("#add_cityName").val();
			var locationArea=$("#add_locationArea").val();
			var customerType=$("#add_customerType").val();
			var customerStatus=$("#add_customerStatus").val();
			var creditLimit=$("#add_creditLimit").val();
			var address=$("#add_address").val();
			var add_customerSalesType=$("#add_customerSalesType").val();
			//var dataString = "CityName="+cityName+"&LocationArea="+locationArea+"&CountryName="+countryName+"&TblType="+tblType+"&CustomerName="+customerName+"&EmailAddress="+emailAddress+"&ContactPerson="+contactPerson+"&PhoneNumber="+phoneNumber+"&altPhoneNumber="+altphoneNumber+"&CountryName="+countryName+"&CityName="+cityName+"&CustomerType="+customerType+"&CustomerStatus="+customerStatus+"&CreditLimit="+creditLimit+"&Address="+address+"&saveCustomerSupplier=1";
			
			var fd = new FormData();
			fd.append('TblType',tblType);
			fd.append('CustomerName',customerName);
			fd.append('EmailAddress',emailAddress);
			fd.append('ContactPerson',contactPerson);
			fd.append('PhoneNumber',phoneNumber);
			fd.append('altPhoneNumber',altphoneNumber);
			fd.append('CountryName',countryName);
			fd.append('CityName',cityName);
			fd.append('LocationArea',locationArea);
			fd.append('CustomerType',customerType);
			fd.append('CustomerStatus',customerStatus);
			fd.append('CreditLimit',creditLimit);
			fd.append('Address',address);
			fd.append('CustomerSalesType',add_customerSalesType);
			fd.append('saveCustomerSupplier','1');
			 
			$.ajax({
				type: 'POST',
				url: 'phpScripts/manageCustomerSupplier-add.php',
				data: fd,
				contentType: false,
				processData: false,
				dataType: 'json',
				success: function(response){
					if(response=='Success'){
						manageCustomerSupplierTable.ajax.reload(null, false);
						$("#add_tblType").val('');
						$("#add_customerName").val('');
						$("#add_emailAddress").val('');
						$("#add_contactPerson").val('');
						$("#add_phoneNumber").val('');
						$("#add_altphoneNumber").val('');
						$("#add_countryName").val('');
						$("#add_cityName").val('');
						$("#add_locationArea").val('');
						$("#add_customerType").val('');
						$("#add_customerStatus").val('');
						$("#add_creditLimit").val('');
						$("#add_address").val('');
						$("#add_customerSalesType").val('');
						if($("#add_pageName").val() == 'Purchase'){
							loadCustomersSuppliers('Customers');
						}
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
			  
					$('#addnew').modal('hide');
		},
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
		excluded: [':disabled'],
        fields: {
				CustomerName: {
					validators: {
							stringLength: {
							min: 3,
						},
							notEmpty: {
							message: 'Please Insert Only Name'
						},
						regexp: {
							regexp: /^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\%\(\)]+$/,
							message: 'Please insert alphanumeric value only'
						}
					}
				},
				EmailAddress: {
					validators: {
							stringLength: {
							min: 3,
						},
						regexp: {
							regexp: /^[a-zA-Z0-9.!$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/,
							message: 'Please insert eMail value only'
						}
					}
				},
				ContactPerson: {
					validators: {
							stringLength: {
							min: 3,
						},
							notEmpty: {
							message: 'Please Insert Contact Person'
						},
						regexp: {
							regexp: /^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/,
							message: 'Please insert alphanumeric value only'
						}
					}
				},
				PhoneNumber: {
					validators: {
						stringLength: {
							min: 11,
						},
						notEmpty: {
							message: 'Please Insert Phone Number'
						},
						regexp: {
							regexp: /^(?:\+?88)?01[11-9]\d{8}$/,
							message: 'Mobile Ex: 01000000000'
						}
					}
				},
				altPhoneNumber: {
					validators: {
						regexp: {
							regexp: /^[0-9 , - ]+$/,
							message: 'Mobile Ex: 01000000000'
						}
					}
				},
				CountryName: {
					validators: {
						notEmpty: {
							message: 'Please Insert Country Name'
						},
						regexp: {
							regexp: /^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/,
							message: 'Please insert alphanumeric value only'
						}
					}
				},
				CityName: {
					validators: {
						notEmpty: {
							message: 'Please Insert City Name'
						},
					}
				},
				LocationArea:{
					validators: {
						notEmpty: {
							message: 'Please Insert Location Area'
						},
						regexp: {
							regexp: /^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/,
							message: 'Please insert alphanumeric value only'
						}
					}
				},
				CreditLimit: {
					validators: {
						regexp: {
							regexp: /^[0-9]+$/,
							message: 'Only Amount : 20000'
						}
					}
				},
				CustomerType: {
					validators: {
                        notEmpty: { // <=== Use notEmpty instead of Callback validator
                           message: 'Please select CustomerType.'
                        }
                     }
				},
				Address: {
					validators: {
							stringLength: {
							min: 3,
						},
							notEmpty: {
							message: 'Please Insert Address'
						},
						regexp: {
							regexp: /^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/,
							message: 'Please insert alphanumeric value only'
						}
					}
				}
				
				
			}
			});
		}); 
	/*----------------End CRM Save & validation parts----------------------*/	
	
	/*----------------Start CRM Edit & validation parts----------------------*/
		$(document).ready(function() {
		$('#form_editCustomer').bootstrapValidator({
		live:'enabled',
		message:'This value is not valid',
		submitButton:'$form_editCustomer button [type="Submit"]',
		submitHandler: function(validator, form, submitButton){
			var tblType = $("#edit_tblType").val();
			  var tblUid = $("#Uid").val();
			  var customerName = $("#edit_partyName").val();
			  var emailAddress = $("#edit_partyEmail").val();
			  var contactPerson=$("#edit_contactPerson").val();
			  var phoneNumber=$("#edit_partyPhone").val();
			  var altphoneNumber=$("#edit_altphoneNumber").val();
			  var countryName=$("#edit_tblCountry").val();
			  var cityName=$("#edit_tblCity").val();
			  var locationArea=$("#edit_locationArea").val();
			  var customerType=$("#edit_partyType").val();
			  var customerStatus=$("#edit_status").val();
			  var creditLimit=$("#edit_creditLimit").val();
			  var address=$("#edit_partyAddress").val();
			  var edittblType=$("#edit_tblType").val();
			  var customerSalesType=$("#EdiCustomerSalesType").val();
			 // var dataString = "TblUid="+tblUid+"&CityName="+cityName+"&LocationArea="+locationArea+"&CountryName="+countryName+"&TblType="+tblType+"&CustomerName="+customerName+"&EmailAddress="+emailAddress+"&ContactPerson="+contactPerson+"&PhoneNumber="+phoneNumber+"&altPhoneNumber="+altphoneNumber+"&CountryName="+countryName+"&CityName="+cityName+"&CustomerType="+customerType+"&CustomerStatus="+customerStatus+"&CreditLimit="+creditLimit+"&Address="+address+"&EdittblType="+edittblType+"&updateCustomerSupplier=2";
			  var fd = new FormData();
			fd.append('TblType',tblType);
			fd.append('TblUid',tblUid);
			fd.append('CustomerName',customerName);
			fd.append('EmailAddress',emailAddress);
			fd.append('ContactPerson',contactPerson);
			fd.append('PhoneNumber',phoneNumber);
			fd.append('altPhoneNumber',altphoneNumber);
			fd.append('CountryName',countryName);
			fd.append('CityName',cityName);
			fd.append('LocationArea',locationArea);
			fd.append('CustomerType',customerType);
			fd.append('CustomerStatus',customerStatus);
			fd.append('CreditLimit',creditLimit);
			fd.append('Address',address);
			fd.append('CustomerSalesType',customerSalesType);
			fd.append('updateCustomerSupplier','1');
			  
			  $.ajax({
					type: 'POST',
					url: 'phpScripts/manageCustomerSupplier-add.php',
					data: fd,
				    contentType: false,
				    processData: false,
					dataType: 'json',
					success: function(response){
						if(response=='Success'){
    					  manageCustomerSupplierTable.ajax.reload(null, false);
    					  $("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> Updated Successfully");
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
			  
			  $('#editCustomerSupplier').modal('hide');
		},
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
		excluded: [':disabled'],
        fields: {
				CustomerName: {
					validators: {
							stringLength: {
							min: 3,
						},
							notEmpty: {
							message: 'Please Insert Only Name'
						},
						regexp: {
							regexp: /^([a-zA-Z0-9_ \.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ \.\-\s\,\;\:\/\&\$\%\(\)]+$/,
							message: 'Please insert alphanumeric value only'
						}
					}
				},
				EmailAddress: {
					validators: {
							stringLength: {
							min: 3,
						},
						regexp: {
							regexp: /^[a-zA-Z0-9.!$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/,
							message: 'Please insert eMail value only'
						}
					}
				},
				ContactPerson: {
					validators: {
							stringLength: {
							min: 3,
						},
							notEmpty: {
							message: 'Please Insert Contact Person'
						},
						regexp: {
							regexp: /^([a-zA-Z0-9_ \.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ \.\-\s\,\;\:\/\&\$\%\(\)]+$/,
							message: 'Please insert alphanumeric value only'
						}
					}
				},
				PhoneNumber: {
					validators: {
						stringLength: {
							min: 11,
						},
						notEmpty: {
							message: 'Please Insert Phone Number'
						},
						regexp: {
							regexp: /^(?:\+?88)?01[11-9]\d{8}$/,
							message: 'Mobile Ex: 01000000000'
						}
					}
				},
				altPhoneNumber: {
					validators: {
						regexp: {
							regexp: /^[0-9 , ]+$/,
							message: 'Mobile Ex: 01000000000'
						}
					}
				},
				CountryName: {
					validators: {
						notEmpty: {
							message: 'Please Insert Country Name'
						},
					}
				},
				CityName: {
					validators: {
						notEmpty: {
							message: 'Please Insert City Name'
						},
					}
				},
				CustomerStatus: {
					validators: {
						notEmpty: {
							message: 'Please Insert Customer Status'
						},
					}
				},
				CreditLimit: {
					validators: {
						
						regexp: {
							regexp: /^[+-]?([0-9]*[.])?[0-9]+$/,
							message: 'Only Amount : 20000.00'
						}
					}
				},
				Address: {
					validators: {
							stringLength: {
							min: 3,
						},
							notEmpty: {
							message: 'Please Insert Address'
						},
						regexp: {
							regexp: /^([a-zA-Z0-9_ \.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ \.\-\s\,\;\:\/\&\$\%\(\)]+$/,
							message: 'Please insert alphanumeric value only'
						}
					}
				},
				LocationArea: {
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
	/*----------------End CRM Edit & validation parts----------------------*/
	
	/*------------------ Start Save & Edit Item or Products select2 panel ---------------------- */
	
		$("#add_cityName").select2( {
			placeholder: "Select District Name",
			dropdownParent: $("#addnew"),
			allowClear: true
		} );
		$("#edit_tblCity12").select2( {
			placeholder: "Select District Name",
			dropdownParent: $("#editCustomerSupplier"),
			allowClear: true
		} );
		
		
	/*------------------ End Save Item or Products select2 panel ---------------------- */
	
	//Edit Unit
    function editCustomerSupplierBangla(partyId,tblType){
        $('#editCustomerSupplierBangla').modal('show');
        //var dataString = "id="+unitId+"&type="+type;
    	var dataString = "id="+partyId+"&type="+tblType;
        $.ajax({
            type: 'POST',
            url: 'phpScripts/manageCustomerSupplier-row.php',
            data: dataString,
            dataType: 'json',
            beforeSend: function(){
                // Show image container
                $("#editLoader").show();
           },
            success: function(response){
              $('#Uid').val(response.id);
              $('#UidB').val(response.id);
              
              $('#edit_partyNameBangla').val(response.party_name_bangla);
              $('#edit_contactPersonBangla').val(response.contact_person_bangla);
              $('#edit_partyPhoneBangla').val(response.contact_number_bangla);
              $('#edit_locationAreaBangla').val(response.location_bangla);
              $('#edit_partyAddressBangla').val(response.party_address_bangla);
              
              $('#edit_partyNameB').html(response.partyName);
              $('#edit_locationAreaB').html(response.locationArea);
              $('#edit_partyAddressB').html(response.partyAddress);
              $('#edit_contactPersonB').html(response.contactPerson);
              $('#edit_partyPhoneB').html(response.partyPhone);
              
            },error: function (xhr) {
                alert('coll');
            },
            complete:function(data){
                // Hide image container
                $("#editLoader").hide();
            }
        });
    }
    
    
    /*----------------Start CRM Edit & validation parts----------------------*/
		$(document).ready(function() {
		$('#form_editCustomerBangla').bootstrapValidator({
		live:'enabled',
		message:'This value is not valid',
		submitButton:'$form_editCustomerBangla button [type="Submit"]',
		submitHandler: function(validator, form, submitButton){
			
			  var tblUid = $("#UidB").val();
			  var edit_partyNameBangla = $("#edit_partyNameBangla").val();
			  var edit_contactPersonBangla = $("#edit_contactPersonBangla").val();
			  var edit_partyPhoneBangla=$("#edit_partyPhoneBangla").val();
			  var edit_locationAreaBangla=$("#edit_locationAreaBangla").val();
			  var edit_partyAddressBangla=$("#edit_partyAddressBangla").val();
			  
			 
			
			var fd = new FormData();
			fd.append('TblUid',tblUid);
			fd.append('edit_partyNameBangla',edit_partyNameBangla);
			fd.append('edit_contactPersonBangla',edit_contactPersonBangla);
			fd.append('edit_partyPhoneBangla',edit_partyPhoneBangla);
			fd.append('edit_locationAreaBangla',edit_locationAreaBangla);
			fd.append('edit_partyAddressBangla',edit_partyAddressBangla);
			fd.append('updateCustomerSupplierBangla','1');
			  
			  $.ajax({
					type: 'POST',
					url: 'phpScripts/manageCustomerSupplier-add.php',
					data: fd,
				    contentType: false,
				    processData: false,
					dataType: 'json',
					success: function(response){
						if(response=='Success'){
    					  manageCustomerSupplierTable.ajax.reload(null, false);
    					  $("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> Updated Successfully");
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
			  
			  $('#editCustomerSupplierBangla').modal('hide');
		},
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
		excluded: [':disabled'],
        fields: {
				edit_partyNameBangla: {
					validators: {
						
				}
				}
				
				
			}
			});
		}); 
    