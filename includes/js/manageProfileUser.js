/*----------------Start ProfileUser Edit & validation parts----------------------*/
		$(document).ready(function() {
		$('#form_EditProfile').bootstrapValidator({
		live:'enabled',
		message:'This value is not valid',
		submitButton:'#form_EditProfile button [type="Submit"]',
		submitHandler: function(validator, form, submitButton){
            var username = $("#add_username").val();
            var password=$("#add_password").val();
            var curr_password=$("#curr_password").val();
            var fd = new FormData();
            var userImage = $('#add_photo')[0].files[0];
            fd.append('file',userImage);
            fd.append('username',username);
            fd.append('password',password);
            fd.append('curr_password',curr_password);
            fd.append('action','updateProfile');
		    $.ajax({
                type: 'POST',
                url: 'phpScripts/profile_update.php',
                data: fd,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(response){
                	if(response=='Success'){
                	    $('#profile').modal('hide');
                	    $("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> Updated Successfully");
                		$("#divMsg").show().delay(2000).fadeOut().queue(function(n) {
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
				}
				
				
				
			}
			});
		}); 
	/*----------------End User Profile Edit & validation parts----------------------*/
	/*----------------Start Reset Password Information save & validation parts----------------------*/
$(document).ready(function() {
	$('#form_ReSetPassword').bootstrapValidator({
	live:'enabled',
	message:'This value is not valid',
	submitButton:'$form_ReSetPassword button [type="Submit"]',
	submitHandler: function(validator, form, submitButton){
			 var userName = $("#reset_userName").val();
			 var userPassword = $("#add_userPassword").val();
			 
		     var fd = new FormData();
            fd.append('userName',userName);
            fd.append('userPassword',userPassword);
			fd.append('resetPassword','1');
              
			  $.ajax({
					type: 'POST',
					url: 'phpScripts/manageUser-add.php',
					data: fd,
					contentType: false,
				    processData: false,
					dataType: 'json',
					success: function(response){
						//alert('shoaib');
						if(response=='Success'){
						    $('#ChangePassword').modal('hide');
						    manageUserTable.ajax.reload(null, false);
						    $("#add_userName").val('');
                            $("#add_userPassword").val('');
                            $("#divMsg").html("<strong><i class='icon fa fa-check'></i>Password Reset ! </strong> Successfully Saved");
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
					userPassword: {
						validators: {
								stringLength: {
								min: 1,
							},
								notEmpty: {
								message: 'Please Insert User Password'
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
/*----------------End Reset Password Information save & validation parts----------------------*/