var manageWalkInCustomerTable;
$(document).ready(function() {
	// manage Shop table
	manageWalkInCustomerTable = $("#manageWalkInCustomerTable").DataTable({
		'ajax': 'phpScripts/manageWalkInCustomerAction.php',
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

	/*--------- Walkin Customer Data Retraive View customer datatable ---------------*/

    //Edit Unit
    function editWalkInCustomer(walkinId){
        //alert(walkinId);
        $('#editWalkInCustomer').modal('show');
        //var dataString = "id="+unitId+"&type="+type;
    	var dataString = "id="+walkinId;
        $.ajax({
            type: 'POST',
            url: 'phpScripts/manageWalkinCustomer-row.php',
            data: dataString,
            dataType: 'json',
            beforeSend: function(){
                // Show image container
                $("#editLoader").show();
           },
            success: function(response){
              $('#Uid').val(response.id);
              $('#edit_customerName').val(response.customerName);
              $('#edit_customerAddress').val(response.customerAddress);
              $('#edit_phoneNo').val(response.phoneNo);
              $('#edit_contactEmail').val(response.contactEmail);
              $('#edit_status').val(response.status);
              
            },error: function (xhr) {
                alert(xhr.responseText);
            },
            complete:function(data){
                // Hide image container
                $("#editLoader").hide();
            }
        });
    }
    /*----------------Start Walkin Customer Edit & validation parts----------------------*/
		$(document).ready(function() {
		$('#form_editWalkinCustomer').bootstrapValidator({
		live:'enabled',
		message:'This value is not valid',
		submitButton:'#form_editWalkinCustomer button [type="Submit"]',
		submitHandler: function(validator, form, submitButton){
			
			  var tblUid = $("#Uid").val();
			  var customerName = $("#edit_customerName").val();
			  var phoneNumber=$("#edit_phoneNo").val();
			  var emailAddress=$("#edit_contactEmail").val();
			  var customerStatus=$("#edit_status").val();
			  var address=$("#edit_customerAddress").val();
			  
			  
			  var dataString = "TblUid="+tblUid+"&CustomerName="+customerName+"&PhoneNumber="+phoneNumber+"&EmailAddress="+emailAddress+"&CustomerStatus="+customerStatus+"&Address="+address+"&updateWalkinCustomer=2";
			  
			  $.ajax({
					type: 'POST',
					url: 'phpScripts/manageWalkinCustomer-add.php',
					data: dataString,
					dataType: 'json',
					success: function(response){
						if(response=='Success'){
					  manageWalkInCustomerTable.ajax.reload(null, false);
					  $("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> Updated Successfully");
							$("#divMsg").show().delay(2000).fadeOut().queue(function(n) {
							  $(this).hide(); n();
							});
						}
					},error: function (xhr) {
						alert(xhr.responseText);
					}
				  });
			  
			  $('#editWalkInCustomer').modal('hide');
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
							regexp: /^([a-zA-Z0-9_ '\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ '\.\-\s\,\;\:\/\&\$\%\(\)]+$/,
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
				CustomerStatus: {
					validators: {
						notEmpty: {
							message: 'Please Insert Customer Status'
						},
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
							regexp: /^([a-zA-Z0-9_ '\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ '\.\-\s\,\;\:\/\&\$\%\(\)]+$/,
							message: 'Please insert alphanumeric value only'
						}
					}
				}
				
				
			}
			});
		}); 
	/*----------------End CRM Edit & validation parts----------------------*/