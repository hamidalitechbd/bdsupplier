var managePaymentVoucherTable
$(document).ready(function() {
	managePaymentVoucherTable = $("#managePaymentVoucherTable").DataTable({
		'ajax': 'phpScripts/paymentVoucherAction.php?voucherType='+$("#voucherType").val()+'&sortData='+$("#sortData").val(),
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
	$( "#sortData" ).change(function() {
        manageSalesTable.ajax.url('phpScripts/paymentVoucherAction.php?voucherType='+$("#voucherType").val()+'&sortData='+$("#sortData").val()).load();
    }); 
	$("#paymentMethod").select2( {
		placeholder: "Select Payment Method",
		allowClear: true
	} );
	$("#partyName").select2({
		placeholder: "Select Party",
		allowClear: true
	});
	$("#accountNo").select2({
		placeholder: "Select Account Number",
		allowClear: true
	});
	$("#chequeBank").select2({
		placeholder: "Select Account Number",
		allowClear: true
	});
	//alert($("#voucherType").val());
	if($("#voucherType").val() == "payment"){
	    $(".divVoucherTypePR").hide();
	    $(".divVoucherTypeADJ").hide();
	    $(".divVoucherTypeP").show();
	    $("#localPurchase").prop("checked",true);
	    loadParty("Local Purchase");
	}else if($("#voucherType").val() == "paymentReceived"){
	    $(".divVoucherTypeP").hide();
	    $(".divVoucherTypeADJ").hide();
	    $(".divVoucherTypePR").show();
	    $("#PartySale").prop("checked",true);
	    loadParty("PartySale");
	}else if($("#voucherType").val() == "discount"){
	    $(".divVoucherTypeP").hide();
	    $(".divVoucherTypeADJ").hide();
	    $(".divVoucherTypePRD").show();
	    $("#PartySaleD").prop("checked",true);
	    loadParty("PartySale");
	}else if($("#voucherType").val() == "adjustment"){
	    $(".divVoucherTypePR").hide();
	    $(".divVoucherTypeP").hide();
	    $(".divVoucherTypeADJ").show();
	    $("#adjustment").prop("checked",true);
	    loadParty("adjustment");
	}
});
$("#partyName").change(function() {
    if($("#partyName").val() != ""){
        var action = 'loadPartyDue';
        var partyId = $(this).val();
        var voucherType = $("#voucherType").val();
        var entryVoucherType = "";
        if(voucherType == "payment"){
            entryVoucherType = $("input[name='entryVoucherTypeP']:checked").val();
        }else if(voucherType == "paymentReceived"){
            entryVoucherType = $("input[name='entryVoucherTypePR']:checked").val();
        }else if(voucherType == "discount"){
            entryVoucherType = $("input[name='entryVoucherTypePRD']:checked").val();
        }else if(voucherType == "adjustment"){
            entryVoucherType = $("input[name='entryVoucherTypeADJ']:checked").val();
        }
        var dataString = "partyType="+entryVoucherType+"&voucherType="+voucherType+"&partyId="+partyId+"&action="+action;
        $.ajax({
            type: 'POST',
            url: 'phpScripts/paymentVoucherAction.php',
            data: dataString,
            dataType: 'json',
            beforeSend: function(){
                    // Show image container
                    $("#loading").show();
               },
            success: function(response){
                $("#book").html(response.previousDue);
                $("#previousDue").val(parseFloat(response.totalDue));
            },
            complete:function(data){
                // Hide image container
                $("#loading").hide();
            },error: function (xhr) {
                alert(xhr.responseText);
            }
        });
    }
});
$( "#paymentMethod" ).change(function() {
    if($("#paymentMethod option:selected").text().toLowerCase() == "cheque"){
        $('.trEft').each(function() {
            $(this).hide();
        });
        $('.trCheque').each(function() {
            $(this).show();
        });
    }else if($("#paymentMethod option:selected").text().toLowerCase() == "eft"){
        $('.trCheque').each(function() {
            $(this).hide();
        });
        $('.trEft').each(function() {
            $(this).show();
        });
    }else{
        $('.trCheque').each(function() {
            $(this).hide();
        });
        $('.trEft').each(function() {
            $(this).hide();
        });
    }
});

$('input[type=radio][name=entryVoucherTypePR]').change(function() {
    $("#partyName").val("").trigger('change');
    loadParty(this.value);
});
$('input[type=radio][name=entryVoucherTypePRD]').change(function() {
    $("#partyName").val("").trigger('change');
    loadParty(this.value);
});
$('input[type=radio][name=entryVoucherTypeP]').change(function() {
    $("#partyName").val("").trigger('change');
    loadParty(this.value);
});
$('input[type=radio][name=entryVoucherTypeADJ]').change(function() {
    $("#partyName").val("").trigger('change');
    loadParty(this.value);
});
$("#sortData").change(function() {
    managePaymentVoucherTable.ajax.url("phpScripts/paymentVoucherAction.php?voucherType="+$("#voucherType").val()+"&sortData="+$("#sortData").val()).load();
});
function loadParty(entryVoucherType){
    
    var action="loadParty";
    var partyType = entryVoucherType;
    var voucherType = $("#voucherType").val();
   // alert(voucherType+ entryVoucherType);
    var dataString = "partyType="+entryVoucherType+"&voucherType="+voucherType+"&action="+action;
    $.ajax({
        type: 'POST',
        url: 'phpScripts/paymentVoucherAction.php',
        data: dataString,
        beforeSend: function(){
                // Show image container
                $("#loading").show();
           },
        success: function(response){
            $("#partyName").html(response);
        },error: function (xhr) {
            alert(xhr.responseText);
        },
        complete:function(data){
                // Hide image container
                $("#loading").hide();
            }
    });   
}
/*---------------------------- Start Voucher save portion ----------------------------------------------------*/
//$("#form_voucher").submit(function(event) {
    
    $(document).ready(function() {
		$('#form_voucher').bootstrapValidator({
		live:'enabled',
		message:'This value is not valid',
		submitButton:'$form_voucher button [type="Submit"]',
		submitHandler: function(validator, form, submitButton){
    //event.preventDefault();
    var partyName=$('#partyName').val();
    var voucherDate = $("#date").val();
    var amount=$('#amount').val();
    var paymentMethod=$('#paymentMethod option:selected').text();
    var accountNo=$('#accountNo').val();
    var chequeNumber=$('#chequeNumber').val();
    var chequeBank=$('#chequeBank').val();
    //var depositBank=$('#depositBank').val();
    var remarks=$('#remarks').val();
    var transitDate=$('#transitDate').val();
    var voucherType = $("#voucherType").val();
    var book = $("#book").val();
    var entryVoucherType = "";
    if(voucherType == "payment"){
        entryVoucherType = $("input[name='entryVoucherTypeP']:checked").val();
    }else if(voucherType == "paymentReceived"){
        entryVoucherType = $("input[name='entryVoucherTypePR']:checked").val();
    }else if(voucherType == "discount"){
        entryVoucherType = $("input[name='entryVoucherTypePRD']:checked").val();
    }
    else{
        entryVoucherType = $("input[name='entryVoucherTypeADJ']:checked").val();
    }
    var fd = new FormData();
    fd.append('partyName',partyName);
    fd.append('voucherDate',voucherDate);
    fd.append('amount',amount);
    fd.append('paymentMethod',paymentMethod);
    fd.append('accountNo',accountNo);
    fd.append('chequeNumber',chequeNumber);
    fd.append('chequeBank',chequeBank);
    fd.append('book',book);
    //fd.append('depositBank',depositBank);
    fd.append('remarks',remarks);
    fd.append('transitDate',transitDate);
    fd.append('voucherType',voucherType);
    fd.append('entryVoucherType',entryVoucherType);
    fd.append('action','saveVoucher');
    $.ajax({
        type: 'POST',
        url: 'phpScripts/paymentVoucherAction.php',
        data: fd,
        contentType: false,
        processData: false,
        dataType: 'json',
        beforeSend: function(){
                // Show image container
                $("#loading").show();
           },
        success: function(response){
            if(response == "Success"){
                $("#partyName").val("").trigger('change');
                $("#book").empty();
                $("#amount").val("");
                $("#previousDue").val("");
                $("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> Saved Successfully");
				$("#divMsg").show().delay(2000).fadeOut().queue(function(n) {
				  $(this).hide(); n();
			    });
	            managePaymentVoucherTable.ajax.reload(null, false);
            }else{
                alert("Error: "+response);
            }
        },
        complete:function(data){
                // Hide image container
                $("#loading").hide();
            },error: function (xhr) {
            alert(xhr.responseText);
        }
    });
	//$('#myModal').modal('hide');
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
							notEmpty: {
							message: 'Please Select Party Name'
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
							regexp: /^[0-9]+(?:\.[0-9]+)?$/,
							message: 'Please insert numeric value only'
						}
					}
				},
				paymentMethod: {
					validators: {
							notEmpty: {
							message: 'Please Select Payment Method'
						},
						
					}
				},
				/*date: {
                validators: {
                    date: {
                        message: 'The date is not valid',
                        format: 'YYYY/MM/DD'
                    },
                }
				},*/
				chequeNumber: {
					validators: {
					    	stringLength: {
							max: 13,
							message: 'Please write within Maximum 50 Digits only'
						},
						regexp: {
							regexp: /^[0-9]+(?:\.[0-9]+)?$/,
							message: 'Please insert Digits only'
						}
					}
				},
				chequeBank: {
					validators: {
						stringLength: {
							max: 250,
							message: 'Please write within Maximum 250 Digits only'
						},
						regexp: {
							regexp: /^[a-zA-Z ]+$/,
							message: 'Please insert Character value only'
						}
					}
				},
				remarks: {
					validators: {
							stringLength: {
							max: 150,
						},
						regexp: {
							regexp: /^([a-zA-Z0-9_ '\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ '\.\-\s\,\;\:\/\&\$\%\(\)]+$/,
							message: 'Please write within Maximum 150  Character only'
						}
					}
				}
			}
			});
		});
/*---------------------------- end Voucher save portion ----------------------------------------------------*/
function deleteVoucher(voucherId){
    var conMsg = confirm("Are you sure to delete??");
	if(conMsg){
    	var action = "deleteVoucher";
    	$.ajax({
    		url:"phpScripts/paymentVoucherAction.php",
    		method:"POST",
    		data:{id:voucherId, action:action},
    		dataType: 'json',
    		beforeSend: function(){
                // Show image container
                $("#loading").show();
           },
    		success:function(data)
    		{
    		    if(data == "Success"){
    		    $("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> Deleted Successfully");
				$("#divMsg").show().delay(2000).fadeOut().queue(function(n) {
				  $(this).hide(); n();
			    });
    			managePaymentVoucherTable.ajax.reload(null, false);
    		    }else{
    		        alert(data);     
    		    }
    		},
            complete:function(data){
                // Hide image container
                $("#loading").hide();
            },
    		error: function (xhr) {
    			alert(xhr.responseText);
    		}
    	});
	}
}

