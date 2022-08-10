var manageChallanTable;
var checkLoadParty = 'No';

$("#customer_div").hide();
function loadCustomersSuppliers(tblType){
	var dataString = "tblType="+tblType;
	$.ajax({
        type: 'GET',
        url: 'phpScripts/loadCustomerSupplier.php',
        data: dataString,
        dataType: 'json',
        beforeSend: function () {
            $('#loading').show();
        },
		success: function(response){
          var len = response.length;
			$("#customers").empty();
			for( var i = 0; i<len; i++){
				var id = response[i]['id'];
				var partyName = response[i]['partyName'];
				$("#customers").append("<option value='"+id+"'>"+partyName+"</option>");
			}
			checkLoadParty = 'Yes';
        },
        complete: function () {
            $('#loading').hide();
        },
        error: function (xhr) {
            alert(xhr.responseText);
        }
      });
}

$("#sortData").change(function() {
   
    if($("#sortData").val() == "-1,-1"){
        if(checkLoadParty == 'No'){
            loadCustomersSuppliers("Party");
            $('#customer_div').append('<script>$("#customers").select2({placeholder: "~~ Select Party Name ~~",allowClear: true,width: "100%"});</script>');
        }else{
            $("#customers").val("").trigger('change');
        }
        $("#customer_div").show();
    }else{
        $("#customer_div").hide();
        //$("#customers").val("").trigger('change');
        //manageSalesTable.ajax.url("phpScripts/whoseSaleAction.php?sortData="+$("#sortData").val()).load();
        manageChallanTable.ajax.url('phpScripts/createChallanAction.php?sortData='+$("#sortData").val()).load();
        
    }
});

$("#customers").change(function() {
    
        
    if($("#sortData").val() == "-1,-1"){
        manageChallanTable.ajax.url('phpScripts/createChallanAction.php?customerId='+$("#customers").val()).load();
        $("#customer_div").show();
    }
});

$(document).ready(function() {
	// alert('col');
	manageChallanTable = $("#manageChallanTable").DataTable({
		'ajax': 'phpScripts/createChallanAction.php?sortData='+$("#sortData").val()+'',
		'order': [],
		'dom': 'Bfrtip',
        'buttons': [
            'pageLength','copy', 'csv', 'pdf', 'print'
        ],
		language: {
            processing: "<img src='../images/loader.gif'>"
        },
        processing: true
	})
	$('#table-filter').on('change', function(){
	   manageChallanTable.search(this.value).draw();   
	});
});
$("#Tid").select2( {
	placeholder: "Select Transport",
	dropdownParent: $("#editTransport"),
	allowClear: true
} );

//Challan id pass to Update transport modal



	function updateChallan(challanId){
	    $('#editTransport').modal('show');
		var dataString = "id="+challanId;
		$.ajax({
			type: 'POST',
			url: 'phpScripts/manageTransport-row.php',
			data: dataString,
			dataType: 'json',
			beforeSend: function(){
                // Show image container
                $("#editLoader").show();
           },
			success: function(response){
			   
			  $('#code').val(response.id);
			  $('#Tid').val(response.tbl_transportinfo_id);
			  $('#transportChallanNo').val(response.transport_challan_no);
			  $('#challanDate').val(response.challan_date);
			   
			},
            complete:function(data){
                // Hide image container
                $("#editLoader").hide();
            }
		});
	}



/*----------------Start Transport Information Update & validation parts----------------------*/

$(document).ready(function() {
		$('#form_updateTransport').bootstrapValidator({
		live:'enabled',
		message:'This value is not valid',
		submitButton:'form_updateTransport button [type="Submit"]',
		submitHandler: function(validator, form, submitButton){
				//alert('Calling');
			var challanCode = $("#code").val();
            var Tid = $("#Tid").val();
            var transportChallanNo = $("#transportChallanNo").val();
            var transportDate = $("#transportDate").val();
            
			var dataString = "challanCode="+challanCode+"&Tid="+Tid+"&transportChallanNo="+transportChallanNo+"&transportDate="+transportDate+"&updateTransportInfo=1";
		  $.ajax({
				type: 'POST',
				url: 'phpScripts/manageTransport-add.php',
				data: dataString,
				dataType: 'json',
				success: function(response){
					if(response=='Success'){
					    $('#editTransport').modal('hide');
						$('button[type="submit"]').prop('disabled', false);
						manageChallanTable.ajax.reload(null, false);
						$("#divMsg").html("<strong><i class='icon fa fa-check'></i>Transport ! </strong> Updated Successfully");
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
			}	
			}
			});
		}); 
	/*----------------End Transport Information Update & validation parts----------------------*/





function deleteChallan(id){
	var conMsg = confirm("Are you sure to delete??")
	if(conMsg){
		var action = "deleteChallan";
		$.ajax({
			url:"phpScripts/createChallanAction.php",
			method:"POST",
			data:{id:id, action:action},
			beforeSend: function () {
				$('#loading').show();
			},
			success:function(response)
			{
				if(response == "Success"){
					$("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> Deleted Successfully");
					$("#divMsg").show().delay(2000).fadeOut().queue(function(n) {
					  $(this).hide(); n();
					});
					manageChallanTable.ajax.reload(null, false);
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




function adjustRemaining(id,saleType){
	var action = "adjustRemainingCheck";
	$.ajax({
		url:"phpScripts/createChallanAction.php",
		method:"POST",
		data:{id:id, action:action},
		beforeSend: function () {
			$('#loading').show();
		},
		success:function(response)
		{
			if(response > 0){
				location.href="challanAdjust.php?challanId="+id+"&type="+saleType;
			}else{
				$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i> This challan is fully adjusted </strong> ");
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
function margeLastCell(){
    
  
  //assumption: the column that you wish to rowspan is sorted.
  
  //this is where you put in your settings
  var indexOfColumnToRowSpan = 4;
  var $table = $('#tbl_challanChoice');
  
  
  //this is the code to do spanning, should work for any table
  var rowSpanMap = {};
  $table.find('tr').each(function(){
    var valueOfTheSpannableCell = $($(this).children('td')[indexOfColumnToRowSpan]).text();
    $($(this).children('td')[indexOfColumnToRowSpan]).attr('data-original-value', valueOfTheSpannableCell);
    rowSpanMap[valueOfTheSpannableCell] = true;
  });
  
  for(var rowGroup in rowSpanMap){
    var $cellsToSpan = $('td[data-original-value="'+rowGroup+'"]');
    var numberOfRowsToSpan = $cellsToSpan.length;
    $cellsToSpan.each(function(index){
      if(index==0){
        $(this).attr('rowspan', numberOfRowsToSpan);
      }else{
        $(this).hide();
      }
    });
  }
}
function transportChallan(id){
    var action = "openSalesChallan";
	$.ajax({
		url:"phpScripts/createChallanAction.php",
		method:"POST",
		dataType: 'json',
		data:{id:id, action:action},
		beforeSend: function () {
			$('#loading').show();
		},
		success:function(response)
		{
		    $("#tbl_challanChoice").html(response.data);
		    $("#partyName").html(response.partyName);
		    $("#partyPhone").html(response.partyPhone);
		    $("#partyAddress").html(response.partyAddress);
		    $("#orderNo").html(response.sales);
		    if(response.print==0){
		        $("#createGroup").attr('Disabled',true);
		    }else{
		        $("#createGroup").attr('Disabled',false);
		    }
		    
			$("#challanViewModal").modal('show');
			margeLastCell();
		},
		complete: function () {
			$('#loading').hide();
		},
		error: function (xhr) {
			alert(xhr.responseText);
		}
	});
}

$('#createGroup').click(function() {
   $('#challanViewModal').modal('hide');
});
