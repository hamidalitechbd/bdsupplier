var createIncentiveAction;
$(document).ready(function() {
	// manage Shop table
	createIncentiveAction = $("#manageIncentiveTable").DataTable({
		'ajax': 'phpScripts/createIncentiveAction.php',
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


/*----------------Start Incentive save & validation parts----------------------*/
$(document).ready(function() {
	$('#form_addIncentive').bootstrapValidator({
	live:'enabled',
	message:'This value is not valid',
	submitButton:'$form_addIncentive button [type="Submit"]',
	submitHandler: function(validator, form, submitButton){
			 var name = $("#add_name").val();
			 var dateFrom = $("#add_dateFrom").val();
			 var dateTo = $("#add_dateTo").val();
			 var buyAmount=$("#add_buyAmount").val();
			 var restAmount=$("#add_restAmount").val();
			 var ownerIncentive=$("#add_ownerIncentive").val();
			 var employeeIcentive=$("#add_employeeIcentive").val();
			 var applayDate=$("#add_applayDate").val();
			 var customerSalesType=$("#add_customerSalesType").val();
		     var fd = new FormData();
            fd.append('name',name);
            fd.append('dateFrom',dateFrom);
            fd.append('dateTo',dateTo);
            fd.append('buyAmount',buyAmount);
            fd.append('restAmount',restAmount);
            fd.append('ownerIncentive',ownerIncentive);
            fd.append('employeeIcentive',employeeIcentive);
            fd.append('applayDate',applayDate);
            fd.append('customerSalesType',customerSalesType);
            fd.append('action','saveIncentive');  
			  $.ajax({
					type: 'POST',
					url: 'phpScripts/createIncentiveAction.php',
					data: fd,
					contentType: false,
				    processData: false,
					dataType: 'json',
					success: function(response){
						if(response=='Success'){
						    $('#addnew').modal('hide');
						    createIncentiveAction.ajax.reload(null, false);
						    $("#add_name").val('');
                            $("#add_dateFrom").val('');
                            $("#add_dateTo").val('');
                            $("#add_buyAmount").val('');
                            $("#add_restAmount").val('');
                            $("#add_ownerIncentive").val('');
                            $("#add_employeeIcentive").val('');
                            $("#add_applayDate").val('');
                            $("#add_customerSalesType").val('');
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
					userFullName: {
						validators: {
								stringLength: {
								min: 1,
							},
								notEmpty: {
								message: 'Please Insert User Full Name'
							},
							regexp: {
								regexp: /^([a-zA-Z0-9_ '\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ '\.\-\s\,\;\:\/\&\$\%\(\)]+$/,
								message: 'Please insert alphanumeric value only'
							}
						}
					},
					userName: {
						validators: {
								stringLength: {
								min: 1,
							},
								notEmpty: {
								message: 'Please Insert User Name'
							},
							regexp: {
								regexp: /^([a-zA-Z0-9_ '\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ '\.\-\s\,\;\:\/\&\$\%\(\)]+$/,
								message: 'Please insert alphanumeric value only'
							}
						}
					},
					userPhone: {
						validators: {
								stringLength: {
								min: 1,
							},
								notEmpty: {
								message: 'Please Insert User Phone'
							},
							regexp: {
								regexp: /^(?:\+?88)?01[13-9]\d{8}$/,
								message: 'Please insert Phone Number only'
							}
						}
					},
					printPhone: {
						validators: {
								stringLength: {
								min: 1,
							},
							regexp: {
								regexp: /^(?:\+?88)?01[13-9]\d{8}$/,
								message: 'Please insert Phone Number only'
							}
						}
					},
					printMobile: {
						validators: {
								stringLength: {
								min: 1,
							},
							regexp: {
								regexp: /^(?:\+?88)?01[13-9]\d{8}$/,
								message: 'Please insert Mobile Number only'
							}
						}
					},
					userMail: {
						validators: {
								stringLength: {
								min: 1,
							},
							regexp: {
								regexp: /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
								message: 'Please insert valid Email only'
							}
						}
					},
					userGender: {
					validators: {
						notEmpty: {
							message: 'Please Select User Gender'
						},
					}
					},
					userType: {
					validators: {
						notEmpty: {
							message: 'Please Select User Type'
						},
					}
					},
					userStatus: {
					validators: {
						notEmpty: {
							message: 'Please Select User Status'
						},
					}
					},
					nidNumber: {
						validators: {
								stringLength: {
								min: 1,
							},
								notEmpty: {
								message: 'Please Insert Nid Number'
							},
							regexp: {
							regexp: /^(\(?\+?[0-9]*\)?)?[0-9_\- \(\)]*$/,
								message: 'Please insert Nid Number only'
							}
						}
					},
					userDesignation: {
						validators: {
								stringLength: {
								min: 1,
							},
							regexp: {
								regexp: /^([a-zA-Z0-9_ '\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ '\.\-\s\,\;\:\/\&\$\%\(\)]+$/,
								message: 'Please insert alphanumeric value only'
							}
						}
					},
					userPhoto: {
					validators: {
						regexp: {
							regexp: /^.*\.(jpg|JPG|jpeg|png)$/,
							message: 'Please insert (jpg|JPG|jpeg|png) only'
						}
					}
					},
					userAddress: {
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
/*----------------End save & validation parts----------------------*/

/*------------------ Start select2 for reset password panel ---------------------- */
	
		$("#reset_userName").select2( {
			placeholder: "Select user name",
			dropdownParent: $("#ChangePassword"),
			allowClear: true
		});
/*------------------ end select2 for reset password panel ---------------------- */

function deleteIncentive(id){
    var conMsg = confirm("Are you sure to delete??");
	if(conMsg){
        var fd = new FormData();
        fd.append('id',id);
        fd.append('action','deleteIncentive');
        $.ajax({
    		type: 'POST',
    		url: 'phpScripts/createIncentiveAction.php',
    		data: fd,
    		contentType: false,
    	    processData: false,
    		dataType: 'json',
    		success: function(response){
    			if(response=='Success'){
    			    createIncentiveAction.ajax.reload(null, false);
    			   $("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> Deleted Successfully");
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
	}else{
	    $("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> You are safe to remove this data.");
    	   $("#divMsg").show().delay(2000).fadeOut().queue(function(n) {
    		  $(this).hide(); n();
    		});
	}
}