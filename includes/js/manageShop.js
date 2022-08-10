var manageShopTable;
var errorCount=0;
$(document).ready(function() {
	// manage Shop table
	manageShopTable = $("#manageShopTable").DataTable({
		'ajax': 'phpScripts/manageShopView.php?page='+$("#type").val()+'',
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
//Edit Unit
function editUnit(unitId,type){
    //$("#loader").show();
    $('#editUnit').modal('show');
    var dataString = "id="+unitId+"&type="+type;
    $.ajax({
        type: 'POST',
        url: 'phpScripts/manage-row.php',
        data: dataString,
        dataType: 'json',
        beforeSend: function(){
            // Show image container
            $("#editLoader").show();
       },
        success: function(response){
          $('#Uid').val(response.id);
          $('#edit_UnitName').val(response.unitName);
          $('#edit_UnitunitType').val(response.unitType);
          $('#edit_UnitDescription').val(response.unitDesc);
          $('#edit_Unitstatus').val(response.status);
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

// Brand image portion

// image view
var loadFile = function(event) {
var output = document.getElementById('output');
	output.src = URL.createObjectURL(event.target.files[0]);
};

function editImage(unitId,type){
    
    var dataString = "id="+unitId+"&type="+type;
    $.ajax({
        type: 'POST',
        url: 'phpScripts/manage-row.php',
        data: dataString,
        dataType: 'json',
        beforeSend: function(){
            // Show image container
            $("#editImageLoader").show();
       },
        success: function(response){
          $('#brand_id').val(response.id);
          $('#brand_type').val(response.unitType);
          $('#editImage').modal('show');
        }
        ,error: function (xhr) {
            alert(xhr.responseText);
        },
        complete:function(data){
            // Hide image container
            $("#editImageLoader").hide();
        }
    });
}


    $(document).on("submit", "#form_editImage", function() {
    
    event.preventDefault(); 
    var brand_id = $("#brand_id").val();
    var brand_type = $("#brand_type").val();
	var userPhoto = $('#brand_logo')[0].files[0];
	var fd = new FormData();
    fd.append('id',brand_id);
    fd.append('bType',brand_type);
    fd.append('file',userPhoto);
    fd.append('saveEditImage','1');
    $.ajax({
        type: 'POST',
        url: 'phpScripts/manage-add.php',
		data: fd,
		contentType: false,
	    processData: false,
		dataType: 'json',
		success: function(response){
			if(response=='Success'){
			    $('#editImage').modal('hide');
			    manageShopTable.ajax.reload(null, false);
			    $("#brand_logo").val('').trigger('change');
			    $("#brand_id").val("");
			    $("#brand_type").val("");
                $("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> Successfully Saved");
			    $("#divMsg").show().delay(2000).fadeOut().queue(function(n) {
				  $(this).hide(); n();
				});
			}else{
			    
			    alert(JSON.stringify(response));
			}
		  
		},error: function (xhr) {
			alert('Error: '+JSON.stringify(xhr));
		}
	  });
    }); 



//Save Unit
/*$("#form_addUnit").submit(function(event) {
  event.preventDefault();
  var unitName = $("#add_unitName").val();
  var unitDescription = $("#add_unitDescription").val();
  var type=$("#add_type").val();
  var dataString = "type="+type+"&UnitName="+unitName+"&UnitDescription="+unitDescription+"&addUnit=1";
  $.ajax({
        type: 'POST',
        url: 'phpScripts/manage-add.php',
        data: dataString,
        dataType: 'json',
        success: function(response){
			if(response=='Success'){
			   manageShopTable.ajax.reload(null, false);
			   $("#add_unitName").val('');
			   $("#add_unitDescription").val('');
			   $("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> Successfully Saved");
			   $("#divMsg").show().delay(2000).fadeOut().queue(function(n) {
				  $(this).hide(); n();
				});
			}
          
        },error: function (xhr) {
            alert(xhr.responseText);
        }
      });
  
  $('#addnew').modal('hide');
});*/
//Update Unit
/*$("#form_editUnit").submit(function(event) {
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
			if(response=='Success'){
			manageShopTable.ajax.reload(null, false);
				$("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> Updated Successfully");
				$("#divMsg").show().delay(2000).fadeOut().queue(function(n) {
				  $(this).hide(); n();
				});
			}
        },error: function (xhr) {
            alert(xhr.responseText);
        }
      });
  
  $('#editUnit').modal('hide');
});*/


/*----------------Start Unit save & validation parts----------------------*/
	$(document).ready(function() {
		$('#form_addUnit').bootstrapValidator({
		live:'enabled',
		message:'This value is not valid',
		submitButton:'$form_addUnit button [type="Submit"]',
		submitHandler: function(validator, form, submitButton){
				//alert('Calling');
				  var unitName = $("#add_unitName").val();
				  var unitDescription = $("#add_unitDescription").val();
				  var type=$("#add_type").val();
				  var dataString = "type="+type+"&UnitName="+unitName+"&UnitDescription="+unitDescription+"&addUnit=1";
				  $.ajax({
						type: 'POST',
						url: 'phpScripts/manage-add.php',
						data: dataString,
						dataType: 'json',
						success: function(response){
							if(response=='Success'){
							    $('#addnew').modal('hide');
							   manageShopTable.ajax.reload(null, false);
							   $("#add_unitName").val('');
							   $("#add_unitDescription").val('');
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
				UnitName: {
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
				UnitDescription: {
					validators: {
							stringLength: {
							min: 3,
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
	
	// check avilability diuring save
		function manageAvailability() {
		$("#loaderIcon").show();
		jQuery.ajax({
		url: "phpScripts/checkManageAvilability.php",
		data:'name='+$("#add_unitName").val()+"&page="+$("#type").val(),
		type: "GET",
		success:function(data){
		$("#manage-availability-status").html(data);
			if(data=="OK") {
				
				$('#btn_saveUnit').prop('disabled', false);
				
				//return true;    
			} else {
				
				$('#btn_saveUnit').prop('disabled', true);
				//return false;   
			}
		$("#loaderIcon").hide();
		},
		error:function (){}
		});
	}
	/*----------------End Unit save & validation parts----------------------*/
	
	/*----------------Start Unit Edit & validation parts----------------------*/
	$(document).ready(function() {
		$('#form_editUnit').bootstrapValidator({
		live:'enabled',
		message:'This value is not valid',
		submitButton:'$form_editUnit button [type="Submit"]',
		submitHandler: function(validator, form, submitButton){
				//alert('Calling');
		
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
					if(response=='Success'){
					    $('#editUnit').modal('hide');
						//$('button[type="submit"]').prop('disabled', false);
						manageShopTable.ajax.reload(null, false);
						$("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> Updated Successfully");
						$("#divMsg").show().delay(2000).fadeOut().queue(function(n) {
						  $(this).hide(); n();
						});
						$('#form_editUnit').triggrt('reset');
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
				UnitName: {
					validators: {
							stringLength: {
							min: 1,
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
				,
				editUnitDescription: {
				    enabled: false,
					validators: {
							stringLength: {
							min: 0
						},
						notEmpty: {
							message: 'Please Insert Only Description'
						},
						regexp: {
							regexp: /^([a-zA-Z0-9_ '\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ '\.\-\s\,\;\:\/\&\$\%\(\)]+$/,
							message: 'Please insert alphanumeric value only'
						}
					}
				}
				
				
				
			}
			})
			
		}); 

		$('input[name="editUnitDescription"]').keyup(function(){
            var data = $(this).val();
            var regx = /^([a-zA-Z0-9_ '\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ '\.\-\s\,\;\:\/\&\$\%\(\)]+$/;
        
            //console.log( data + ' patt:'+ data.match(regx));
        
            if ( data === '' || data.match(regx) ){
                $('.descriptionErr').fadeOut('slow');
                $('button[type="submit"]').prop('disabled', false);
            }
            else {
                $('.descriptionErr')
                    .text('only Numeric Digits(0 to 9) allowed!')
                    .css({'color':'#fff', 'background':'#990000', 'padding':'3px'})
                    .fadeIn('fast');
                $('button[type="submit"]').prop('disabled', true);
            }
        });
		
		
		// check avilability diuring save
		function manageEditAvailability() {
		$("#loaderIcon").show();
		jQuery.ajax({
		url: "phpScripts/checkManageAvilability.php",
		data:'name='+$("#edit_UnitName").val()+"&page="+$("#type").val()+"&id="+$("#Uid").val(),
		type: "GET",
		success:function(data){
		    if(data == "Already used"){
		        $("#manageEdit-availability-status").html("<span class='status-not-available' style='color: red;'> "+data+".</span>");
		        $('button[type="submit"]').prop('disabled', true);
		    }else{
		        $("#manageEdit-availability-status").html("<span class='status-available' style='color: green;'> "+data+".</span>");
		         
		        $('button[type="submit"]').prop('disabled', false);
		    }
    		
    		$("#loaderIcon").hide();
		},
		error:function (){}
		});
		}
	/*----------------End Unit Edit & validation parts----------------------*/