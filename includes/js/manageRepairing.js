var manageRepairTable;
var errorCount=0;
$(document).ready(function() {
	// manage Shop table
	manageRepairTable = $("#manageRepairTable").DataTable({
		'ajax': 'phpScripts/manageRepairingAction.php',
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


/*----------------Start Unit save & validation parts----------------------*/
	$(document).ready(function() {
		$('#form_addRepair').bootstrapValidator({
		live:'enabled',
		message:'This value is not valid',
		submitButton:'$form_addUnit button [type="Submit"]',
		submitHandler: function(validator, form, submitButton){
    		  var partyName = $("#add_partyName").val();
    		  var referenceBy = $("#add_referenceBy").val();
    		  var date = $("#add_Date").val();
    		  var repairDescription=$("#add_repairDescription").val();
    		  var amount=$("#add_amount").val();
    		  
    		  var fd = new FormData();
    		  fd.append('partyName',partyName);
    		  fd.append('referenceBy',referenceBy);
    		  fd.append('date',date);
    		  fd.append('repairDescription',repairDescription);
    		  fd.append('amount',amount);
    		  fd.append('action','saveRepair');
    		  
    		  $.ajax({
    				type: 'POST',
    				url: 'phpScripts/manageRepairingAction.php',
    				data: fd,
    				contentType: false,
        		    processData: false,
    				dataType: 'json',
    				beforeSend: function () {
                        $('#loading').show();
                    },
    				success: function(response){
    					if(response=='Success'){
    					    $('#addnew').modal('hide');
    					   manageRepairTable.ajax.reload(null, false);
    					   $('#add_partyName').val('').trigger('change');
    					   $('#add_referenceBy').val('').trigger('change');
    					   $("#add_Date").val('');
    					   $("#add_repairDescription").val('');
    					   $("#add_amount").val('');
    					   $("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> Successfully Saved");
    					   $("#divMsg").show().delay(2000).fadeOut().queue(function(n) {
    						  $(this).hide(); n();
    						});
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
		},
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
		excluded: [':disabled'],
        fields: {
				partyName: {
					validators: {
                        message: 'Please select Party Name.'
                    }
				},
				referenceBy: {
					validators: {
                        message: 'Please select Reference By.'
                    }
				},
				repairDescription: {
					validators: {
							stringLength: {
							min: 3,
							message: 'Please Write Repair Description.'
						},
						notEmpty: {
							message: 'Please Insert Repair Description'
						},
						regexp: {
							regexp: /^([a-zA-Z0-9_ '\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ '\.\-\s\,\;\:\/\&\$\%\(\)]+$/,
							message: 'Please insert alphanumeric value only'
						}
					}
				},
				date: {
                    validators: {
                        date: {
                            format: 'MM/DD/YYYY',
                            message: 'The value is not a valid date'
                        },
                        notEmpty: {
							message: 'Please Insert Date'
						},
                    }
                },
				amount: {
					validators: {
							stringLength: {
							min: 1,
						},
						notEmpty: {
							message: 'Please Insert Amount'
						},
						regexp: {
							regexp: /^([0-9]+\s)*[0-9]+$/,
							message: 'Please insert value only'
						}
					}
				}
				
				
			}
			})
		}); 
	
	
	/*----------------Start Delete Repair Data----------------------*/
	
	function deleteRepair(id){
    var conMsg = confirm("Are you sure to delete??");
	if(conMsg){
    	var action = "deleteRepair";
    	$.ajax({
    		url:"phpScripts/manageRepairingAction.php",
    		method:"POST",
    		data:{id:id, action:action},
    		dataType: 'json',
    		beforeSend: function () {
                $('#loading').show();
            },
    		success:function(data)
    		{
    		    if(data == "Success"){
    		        $("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> Deleted Successfully");
				    $("#divMsg").show().delay(2000).fadeOut().queue(function(n) {
    				    $(this).hide(); n();
    			    });
        			manageRepairTable.ajax.reload(null, false);
    		    }else{
    		        $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> "+data);
            		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
            		    $(this).hide(); n();
            		});
    		    }
    		},
    		complete: function () {
                $('#loading').hide();
            },
    		error: function (xhr) {
    			alert(xhr.responseText);
    		}
    	});
	}
}
	/*----------------End Delete Repair Data----------------------*/
	