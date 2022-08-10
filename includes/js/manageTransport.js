var manageTransportTable;
$(document).ready(function() {
	// manage Shop table
	manageTransportTable = $("#manageTransportTable").DataTable({
		'ajax': 'phpScripts/manageTransportView.php',
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
	$('#form_addTransportInfo').bootstrapValidator({
	live:'enabled',
	message:'This value is not valid',
	submitButton:'$form_addTransportInfo button [type="Submit"]',
	submitHandler: function(validator, form, submitButton){
			  var transportName = $("#add_transportName").val();
			  var contactPerson = $("#add_contactPerson").val();
			  var contactNo=$("#add_contactNo").val();
			  var contactEmail=$("#add_contactEmail").val();
			  var address=$("#add_address").val();
			  var remarks=$("#add_remarks").val();
			  var dataString = "transportName="+transportName+"&contactPerson="+contactPerson+"&contactNo="+contactNo+"&contactEmail="+contactEmail+"&address="+address+"&remarks="+remarks+"&addTransportInfo=1";
			  $.ajax({
					type: 'POST',
					url: 'phpScripts/manageTransportInfo-add.php',
					data: dataString,
					dataType: 'json',
					success: function(response){
						if(response=='Success'){
						    $('#addnew').modal('hide');
						   manageTransportTable.ajax.reload(null, false);
						   $("#add_transportName").val('');
						   $("#add_contactPerson").val('');
						   $("#add_contactNo").val('');
						   $("#add_contactEmail").val('');
						   $("#add_remarks").val('');
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
			transportName: {
				validators: {
						stringLength: {
						min: 1,
					},
						notEmpty: {
						message: 'Please Insert Only Transport Name'
					},
					regexp: {
						regexp: /^([a-zA-Z0-9_ '\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ '\.\-\s\,\;\:\/\&\$\%\(\)]+$/,
						message: 'Please insert alphanumeric value only'
					}
				}
			},
			contactPerson: {
				validators: {
						stringLength: {
						min: 1,
					},
					    notEmpty: {
						message: 'Please Insert Only Name of Contact Person'
					},
					regexp: {
						regexp: /^([a-zA-Z0-9_ '\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ '\.\-\s\,\;\:\/\&\$\%\(\)]+$/,
						message: 'Please insert alphanumeric value only'
					}
				}
			},
			contactNo: {
				validators: {
						stringLength: {
						min: 1,
					},
						notEmpty: {
						message: 'Please Insert Only Contact No'
					},
					regexp: {
						regexp: /^(?:\+?88)?01[11-9]\d{8}$/,
						message: 'Please insert 11 digits value only'
					}
				}
			},
			contactEmail: {
				validators: {
						stringLength: {
						min: 1,
					},
					regexp: {
						regexp: /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
						message: 'Please insert alphanumeric value only'
					}
				}
			},
			address: {
				validators: {
						stringLength: {
						min: 1,
					},
						notEmpty: {
						message: 'Please Insert Only address'
					},
					regexp: {
						regexp: /^([a-zA-Z0-9_ '\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ '\.\-\s\,\;\:\/\&\$\%\(\)]+$/,
						message: 'Please insert alphanumeric value only'
					}
				}
			},
			remarks: {
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

/*----------------Start Edit Incormation---------------*/
function editTransportInfo(transportInfoId){
    $('#editTransportInfo').modal('show');
    var dataString = "id="+transportInfoId;
    $.ajax({
        type: 'POST',
        url: 'phpScripts/manageTransportView.php',
        data: dataString,
        dataType: 'json',
        success: function(response){
          $('#transportInfoId').val(response.id);
          $('#edit_transportName').val(response.transportName);
          $('#edit_contactPerson').val(response.contactPerson);
          $('#edit_contactNo').val(response.contactNo);
          $('#edit_contactEmail').val(response.email);
          $('#edit_remarks').val(response.remarks);
          $('#edit_status').val(response.status).trigger('change');
          $('#edit_address').val(response.address);
        }
        ,error: function (xhr) {
            alert(xhr.responseText);
        }
    });
}
/*----------------End Edit Transport Information Incormation---------------*/

	/*----------------Start Transport Information Update & validation parts----------------------*/
	$(document).ready(function() {
		$('#form_updateTransportInfo').bootstrapValidator({
		live:'enabled',
		message:'This value is not valid',
		submitButton:'$form_updateTransportInfo button [type="Submit"]',
		submitHandler: function(validator, form, submitButton){
				//alert('Calling');
			var id = $("#transportInfoId").val();
			var transportName = $("#edit_transportName").val();
			  var contactPerson = $("#edit_contactPerson").val();
			  var contactNo=$("#edit_contactNo").val();
			  var contactEmail=$("#edit_contactEmail").val();
			  var address=$("#edit_address").val();
			  var remarks=$("#edit_remarks").val();
			  var status=$("#edit_status").val();
			  var dataString = "transportName="+transportName+"&contactPerson="+contactPerson+"&contactNo="+contactNo+"&contactEmail="+contactEmail+"&address="+address+"&remarks="+remarks+"&id="+id+"&status="+status+"&updateTransportInfo=1";
		  $.ajax({
				type: 'POST',
				url: 'phpScripts/manageTransportInfo-add.php',
				data: dataString,
				dataType: 'json',
				success: function(response){
					if(response=='Success'){
					    $('#editTransportInfo').modal('hide');
						$('button[type="submit"]').prop('disabled', false);
						manageTransportTable.ajax.reload(null, false);
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
				transportName: {
				validators: {
						stringLength: {
						min: 1,
					},
						notEmpty: {
						message: 'Please Insert Only Transport Name'
					},
					regexp: {
						regexp: /^([a-zA-Z0-9_ '\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ '\.\-\s\,\;\:\/\&\$\%\(\)]+$/,
						message: 'Please insert alphanumeric value only'
					}
				}
			},
			contactPerson: {
				validators: {
						stringLength: {
						min: 1,
					},
					    notEmpty: {
						message: 'Please Insert Only Transport Name'
					},
					regexp: {
						regexp: /^([a-zA-Z0-9_ '\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ '\.\-\s\,\;\:\/\&\$\%\(\)]+$/,
						message: 'Please insert alphanumeric value only'
					}
				}
			},
			contactNo: {
				validators: {
						stringLength: {
						min: 1,
					},
						notEmpty: {
						message: 'Please Insert Only Transport Name'
					},
					regexp: {
						regexp: /^(?:\+?88)?01[11-9]\d{8}$/,
						message: 'Please insert 11 digits value only'
					}
				}
			},
			contactEmail: {
				validators: {
						stringLength: {
						min: 1,
					},
					regexp: {
						regexp: /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
						message: 'Please insert alphanumeric value only'
					}
				}
			},
			address: {
				validators: {
						stringLength: {
						min: 1,
					},
						notEmpty: {
						message: 'Please Insert Only Transport Name'
					},
					regexp: {
						regexp: /^([a-zA-Z0-9_ '\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ '\.\-\s\,\;\:\/\&\$\%\(\)]+$/,
						message: 'Please insert alphanumeric value only'
					}
				}
			},
			remarks: {
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
	
	
	/*----------------Start Edit Incormation---------------*/
    function editTransportInfoBangla(transportInfoId){
        $('#editTransportInfoBangla').modal('show');
        var dataString = "id="+transportInfoId;
        $.ajax({
            type: 'POST',
            url: 'phpScripts/manageTransportView.php',
            data: dataString,
            dataType: 'json',
            success: function(response){
              $('#transportInfoIdBangla').val(response.id);
              $('#edit_transportNameBangla').val(response.transport_name_bangla);
              $('#edit_contactPersonBangla').val(response.contact_person_bangla);
              $('#edit_contactNoBangla').val(response.contact_number_bangla);
              $('#edit_address_Bangla').val(response.address_bangla);
            $('#edit_transportNameB').html(response.transportName);
            $('#edit_contactPersonB').html(response.contactPerson);
            $('#edit_contactNoB').html(response.contactNo);
            $('#edit_addressB').html(response.address);
              
            }
            ,error: function (xhr) {
                alert(xhr.responseText);
            }
        });
    }
/*----------------End Edit Transport Information Incormation---------------*/
	
	/*----------------Start Bangla Transport Information Update & validation parts----------------------*/
	$(document).ready(function() {
		$('#form_updateTransportInfoBangla').bootstrapValidator({
		live:'enabled',
		message:'This value is not valid',
		submitButton:'$form_updateTransportInfoBangla button [type="Submit"]',
		submitHandler: function(validator, form, submitButton){
				//alert('Calling');
			    var id = $("#transportInfoIdBangla").val();
			    var transportName = $("#edit_transportNameBangla").val();
			    var contactPerson = $("#edit_contactPersonBangla").val();
			    var contactNo = $("#edit_contactNoBangla").val();
			    var address=$("#edit_address_Bangla").val();
			  
			  var dataString = "transport_name_bangla="+transportName+"&contact_person_bangla="+contactPerson+"&edit_contactNoBangla="+contactNo+"&address_bangla="+address+"&id="+id+"&updateTransportInfoBangla=1";
		   $.ajax({
				type: 'POST',
				url: 'phpScripts/manageTransportInfo-add.php',
				data: dataString,
				dataType: 'json',
				success: function(response){
					if(response=='Success'){
					    $('#editTransportInfoBangla').modal('hide');
						$('button[type="submit"]').prop('disabled', false);
						manageTransportTable.ajax.reload(null, false);
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
				edit_transportNameBangla: {
				validators: {
						
				}
			}
				
				
			}
			});
		}); 
	/*----------------End Bangla Transport Information Update & validation parts----------------------*/