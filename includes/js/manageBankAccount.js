var manageBankAccountTable;
$(document).ready(function() {
	// manage Shop table
	manageBankAccountTable = $("#manageBankAccountTable").DataTable({
		'ajax': 'phpScripts/manageBankAccountView.php',
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
/*----------------Start bank Account save & validation parts----------------------*/
    $(document).ready(function() {
	$('#form_addBankAccount').bootstrapValidator({
	live:'enabled',
	message:'This value is not valid',
	submitButton:'$form_addBankAccount button [type="Submit"]',
	submitHandler: function(validator, form, submitButton){
			  var accountNo = $("#add_accountNo").val();
			  var accountName = $("#add_accountName").val();
			  var bankName=$("#add_bankName").val();
			  var branchName=$("#add_branchName").val();
			  var swiftCode=$("#add_swiftCode").val();
			  var address=$("#add_address").val();
			  var dataString = "accountNo="+accountNo+"&accountName="+accountName+"&bankName="+bankName+"&branchName="+branchName+"&swiftCode="+swiftCode+"&address="+address+"&addBankAccount=1";
			  $.ajax({
					type: 'POST',
					url: 'phpScripts/manageBankAccount-add.php',
					data: dataString,
					dataType: 'json',
					success: function(response){
						if(response=='Success'){
						    $('#addnew').modal('hide');
						   manageBankAccountTable.ajax.reload(null, false);
						   $("#add_accountNo").val('');
						   $("#add_accountName").val('');
						   $("#add_bankName").val('');
						   $("#add_branchName").val('');
						   $("#add_swiftCode").val('');
						   $("#add_address").val('');
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
			accountNo: {
				validators: {
						stringLength: {
						min: 13,
					},
						notEmpty: {
						message: 'Please Insert MICR 13 Digit Accounts Number '
					},
					regexp: {
						regexp: /^[0-9]+$/,
						message: 'Please insert valid MICR 13 Digit Accounts Number'
					}
				}
			},
			accountName: {
				validators: {
						stringLength: {
						min: 1,
					},
						notEmpty: {
						message: 'Please Insert Account Name'
					},
					regexp: {
						regexp: /^([a-zA-Z0-9_ '\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ '\.\-\s\,\;\:\/\&\$\%\(\)]+$/,
						message: 'Please insert alphanumeric value only'
					}
				}
			},
			bankName: {
				validators: {
						stringLength: {
						min: 1,
					},
						notEmpty: {
						message: 'Please Insert Only Bank Name'
					},
					regexp: {
						regexp: /^([a-zA-Z0-9_ '\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ '\.\-\s\,\;\:\/\&\$\%\(\)]+$/,
						message: 'Please insert alphanumeric value only'
					}
				}
			},
			branchName: {
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
			swiftCode: {
				validators: {
						stringLength: {
						min: 1,
					},
					
					regexp: {
						regexp: /^[a-zA-Z0-9 \-\ ]+$/,
						message: 'Please insert swiftCode value only'
					}
				}
			},
			address: {
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

/*----------------Start Edit Bank Account Incormation---------------*/
function editBankAccount(bankAccountId){
    $('#editBankAccount').modal('show');
    var dataString = "id="+bankAccountId;
    $.ajax({
        type: 'POST',
        url: 'phpScripts/manageBankAccountView.php',
        data: dataString,
        dataType: 'json',
        beforeSend: function(){
            // Show image container
            $("#editLoader").show();
       },
        success: function(response){
          $('#bankAccountId').val(response.id);
          $('#edit_accountNo').val(response.accountNo);
          $('#edit_accountName').val(response.accountName);
          $('#edit_bankName').val(response.bankName);
          $('#edit_branchName').val(response.branchName);
          $('#edit_swiftCode').val(response.swiftCode);
          $('#edit_status').val(response.status).trigger('change');
          $('#edit_address').val(response.address);
        }
        ,error: function (xhr) {
            alert(xhr.responseText);
        },
        complete:function(data){
            // Hide image container
            $("#editLoader").hide();
        }
    });
}
/*----------------End Edit Bank Account Incormation---------------*/

	/*----------------Start Bank Account Update & validation parts----------------------*/
	$(document).ready(function() {
		$('#form_updateBankAccount').bootstrapValidator({
		live:'enabled',
		message:'This value is not valid',
		submitButton:'$form_editUnit button [type="Submit"]',
		submitHandler: function(validator, form, submitButton){
				//alert('Calling');
			var accountNo = $("#edit_accountNo").val();
			  var accountName = $("#edit_accountName").val();
			  var bankName=$("#edit_bankName").val();
			  var branchName=$("#edit_branchName").val();
			  var swiftCode=$("#edit_swiftCode").val();
			  var address=$("#edit_address").val();
			  var id = $("#bankAccountId").val();
			  var status = $("#edit_status").val();
			  var dataString = "accountNo="+accountNo+"&accountName="+accountName+"&bankName="+bankName+"&branchName="+branchName+"&swiftCode="+swiftCode+"&address="+address+"&status="+status+"&id="+id+"&updateBankAccount=1";
		  $.ajax({
				type: 'POST',
				url: 'phpScripts/manageBankAccount-add.php',
				data: dataString,
				dataType: 'json',
				success: function(response){
					if(response=='Success'){
					    $('#editBankAccount').modal('hide');
						$('button[type="submit"]').prop('disabled', false);
						manageBankAccountTable.ajax.reload(null, false);
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
				accountNo: {
				validators: {
						stringLength: {
						min: 13,
					},
						notEmpty: {
						message: 'Please Insert MICR 13 Digit Accounts Number '
					},
					regexp: {
						regexp: /^[0-9]+$/,
						message: 'Please insert valid MICR 13 Digit Accounts Number'
					}
				}
			},
			accountName: {
				validators: {
						stringLength: {
						min: 1,
					},
						notEmpty: {
						message: 'Please Insert Account Name'
					},
					regexp: {
						regexp: /^([a-zA-Z0-9_ '\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ '\.\-\s\,\;\:\/\&\$\%\(\)]+$/,
						message: 'Please insert alphanumeric value only'
					}
				}
			},
			bankName: {
				validators: {
						stringLength: {
						min: 1,
					},
						notEmpty: {
						message: 'Please Insert Only Unit Name'
					},
					regexp: {
						regexp: /^([a-zA-Z0-9_ '\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ '\.\-\s\,\;\:\/\&\$\%\(\)]+$/,
						message: 'Please insert alphanumeric value only'
					}
				}
			},
			branchName: {
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
			swiftCode: {
				validators: {
						stringLength: {
						min: 1,
					},
					
					regexp: {
						regexp: /^[a-zA-Z0-9 \-\ ]+$/,
						message: 'Please insert swiftCode value only'
					}
				}
			},
			address: {
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
	/*----------------End Bank Account Update & validation parts----------------------*/