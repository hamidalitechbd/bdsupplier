var manageUserTable;
$(document).ready(function() {
	// manage Shop table
	manageUserTable = $("#manageUserTable").DataTable({
		'ajax': 'phpScripts/manageUserView.php',
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


/*----------------Start Transport Information save & validation parts----------------------*/
$(document).ready(function() {
	$('#form_addUser').bootstrapValidator({
	live:'enabled',
	message:'This value is not valid',
	submitButton:'$form_addUser button [type="Submit"]',
	submitHandler: function(validator, form, submitButton){
			 var userFullName = $("#add_userFullName").val();
			 var userPhone = $("#add_userPhone").val();
			 var userMail=$("#add_userMail").val();
			 var userGender=$("#add_userGender").val();
			 var userName=$("#add_userName").val();
			 var userType=$("#add_userType").val();
			 var nidNumber=$("#add_nidNumber").val();
			 var printPhone=$("#printPhone").val();
			 var printMobile=$("#printMobile").val();
			 var userDesignation=$("#add_userDesignation").val();
			 var userStatus=$("#add_userStatus").val();
			 var userPhoto = $('#add_userPhoto')[0].files[0];
			 var userAddress=$("#add_userAddress").val();
		     var fd = new FormData();
            fd.append('userFullName',userFullName);
            fd.append('userPhone',userPhone);
            fd.append('userMail',userMail);
            fd.append('userGender',userGender);
            fd.append('userName',userName);
            fd.append('userType',userType);
            fd.append('nidNumber',nidNumber);
            fd.append('printPhone',printPhone);
            fd.append('printMobile',printMobile);
            fd.append('userDesignation',userDesignation);
            fd.append('file',userPhoto);
            fd.append('saveUser','1');
            fd.append('userStatus',userStatus);
            fd.append('userAddress',userAddress);  
			  $.ajax({
					type: 'POST',
					url: 'phpScripts/manageUser-add.php',
					data: fd,
					contentType: false,
				    processData: false,
					dataType: 'json',
					success: function(response){
						if(response=='Success'){
						    $('#addnew').modal('hide');
						    manageUserTable.ajax.reload(null, false);
						    $("#add_userFullName").val('');
                            $("#add_userPhone").val('');
                            $("#add_userMail").val('');
                            $("#add_userGender").val('').trigger('change');
                            $("#add_userName").val('');
                            $("#add_userType").val('');
                            $("#add_nidNumber").val('');
                            $("#printPhone").val('');
                            $("#printMobile").val('');
                            $("#add_userDesignation").val('');
                            $("#add_userStatus").val('').trigger('change');
                            $("#add_userAddress").val('');
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
								regexp: /^(?:\+?88)?0[3-9]\d{7}$/,
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

/*----------------Start Edit Incormation---------------*/
function editUser(userId){
    $('#editUser').modal('show');
    var dataString = "id="+userId;
    $.ajax({
        type: 'POST',
        url: 'phpScripts/manageUserView.php',
        data: dataString,
        dataType: 'json',
        success: function(response){
            $("#edit_userId").val(response.id);
            $("#edit_userFullName").val(response.fname);
            $("#edit_userPhone").val(response.mobile);
            $("#edit_printPhone").val(response.print_phone);
            $("#edit_printMobile").val(response.print_mobile);
            $("#edit_userMail").val(response.email);
            $("#edit_userGender").val(response.gender).trigger('change');
            $("#edit_userName").val(response.username);
            $("#edit_userType").val(response.tbl_accountTypeId);
            $("#edit_nidNumber").val(response.nid);
            $("#edit_userDesignation").val(response.designation);
            $("#edit_userStatus").val(response.accountStatus).trigger('change');
            $("#edit_userAddress").val(response.address);  
        }
        ,error: function (xhr) {
            alert(xhr.responseText);
        }
    });
}
/*----------------End Edit Transport Information Incormation---------------*/

	/*----------------Start user Information Update & validation parts----------------------*/
	$(document).ready(function() {
		$('#form_updateUser').bootstrapValidator({
		live:'enabled',
		message:'This value is not valid',
		submitButton:'$form_updateUser button [type="Submit"]',
		submitHandler: function(validator, form, submitButton){
            var id = $("#edit_userId").val();
            var userFullName = $("#edit_userFullName").val();
            var userPhone = $("#edit_userPhone").val();
            var userMail=$("#edit_userMail").val();
            var userGender=$("#edit_userGender").val();
            var userName=$("#edit_userName").val();
            var userType=$("#edit_userType").val();
            var nidNumber=$("#edit_nidNumber").val();
            var printPhone=$("#edit_printPhone").val();
            var printMobile=$("#edit_printMobile").val();
            var userDesignation=$("#edit_userDesignation").val();
            var userStatus=$("#edit_userStatus").val();
            var userPhoto = $('#edit_userPhoto')[0].files[0];
            var userAddress=$("#edit_userAddress").val();
            var fd = new FormData();
            fd.append('id',id);
            fd.append('userFullName',userFullName);
            fd.append('userPhone',userPhone);
            fd.append('userMail',userMail);
            fd.append('userGender',userGender);
            fd.append('userName',userName);
            fd.append('userType',userType);
            fd.append('nidNumber',nidNumber);
            fd.append('printPhone',printPhone);
            fd.append('printMobile',printMobile);
            fd.append('userDesignation',userDesignation);
            fd.append('file',userPhoto);
            fd.append('updateUser','1');
            fd.append('userStatus',userStatus);
            fd.append('userAddress',userAddress);  
		  $.ajax({
				type: 'POST',
				url: 'phpScripts/manageUser-add.php',
				data: fd,
				contentType: false,
			    processData: false,
				dataType: 'json',
				success: function(response){
					if(response=='Success'){
					    $('#editUser').modal('hide');
						$('button[type="submit"]').prop('disabled', false);
						manageUserTable.ajax.reload(null, false);
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
	/*----------------End Transport Information Update & validation parts----------------------*/