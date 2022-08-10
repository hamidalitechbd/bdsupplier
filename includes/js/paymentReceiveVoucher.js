$(document).ready(function() {
// retrive customer or supplier data

    var rowNum=1;
    var s=1;	  
    $('#add').click(function(){  
    	rowNum++;
    	s++;
       if($("#add_payMode").val() == 'EFT'){
           $('#dynamic_field').append('<tr id="row'+rowNum+'"><td class="col-sm-3"><select class="form-control" name="partyName[]" id="name_'+s+'" onchange="calculateDue(this.value, '+s+')">'+$('#name_0').html()+'</select><br><input type="text" class="form-control" name="remarks[]" style="margin-top: 3%;" placeholder=" Remarks " ></td><td class="col-sm-2"><input type="text" class="form-control" name="amounts['+s+']" id="amounts_'+s+'" placeholder="Amount" ><div style="margin-top: 3%;" ><select class="form-control" name="depositBank[]" id="depositBank_'+s+'">'+$('#depositBank_0').html()+'</select></div></td><td class="col-sm-3"><input type="text" class="form-control" name="discounts'+s+'" id="discounts_'+s+'" placeholder=" Discounts " value="0" ></td><td class="col-sm-2"><input type="text" style="margin-top: 3%;" class="form-control" placeholder=" Due " name="due['+s+']" id="due_'+s+'" Readonly></td><td class="col-sm-2"><select style="margin-top: 3%;" class="form-control" name="book['+s+']" id="book_'+s+'"></select></td><td><button type="button" name="remove" id="'+rowNum+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
       }else if($("#add_payMode").val() == 'CHEQUE'){
            $('#dynamic_field').append('<tr id="row'+rowNum+'"><td class="col-sm-3"><select class="form-control" name="partyName[]" id="name_'+s+'" onchange="calculateDue(this.value, '+s+')">'+$('#name_0').html()+'</select><br><input type="text" class="form-control" style="margin-top: 3%;" name="remarks[]" placeholder=" Remarks " ></td><td class="col-sm-2"><input type="text" class="form-control" name="amounts['+s+']" id="amounts_'+s+'" placeholder="Amount" ><div style="margin-top: 3%;"><select class="form-control" name="depositBank[]" id="depositBank_'+s+'">'+$('#depositBank_0').html()+'</select></div></td><td class="col-sm-3"><input type="text" class="form-control" name="discounts'+s+'" id="discounts_'+s+'" placeholder=" Discounts " value="0" ><div style="margin-top: 3%;"><input type="text" style="margin-top: 3%;" class="form-control" name="chequeNo['+s+']" id="chequeN_'+s+'" placeholder=" Cheque No " ></div></td><td class="col-sm-2"><input type="text" class="form-control" placeholder=" Due " name="due['+s+']" id="due_'+s+'" Readonly><div style="margin-top: 3%;"><select class="form-control"  name="chequeBankName[0]" class="form-control" id="chequeBank_'+s+'"><option value="" selected>~~ Cheque Bank  ~~</option><option value="AB Bank Limited"> AB Bank Limited </option><option value="Bangladesh Commerce Bank Limited"> Bangladesh Commerce Bank Limited </option><option value="Bank Asia Limited"> Bank Asia Limited </option><option value="BRAC Bank Limited"> BRAC Bank Limited </option><option value="City Bank Limited"> City Bank Limited </option><option value="Dhaka Bank Limited"> Dhaka Bank Limited </option><option value="Dutch-Bangla Bank Limited"> Dutch-Bangla Bank Limited </option><option value="Eastern Bank Limited"> Eastern Bank Limited </option><option value="HSBC Bank Bangladesh"> HSBC Bank Bangladesh </option><option value="IFIC Bank Limited"> IFIC Bank Limited </option><option value="Jamuna Bank Limited"> Jamuna Bank Limited </option><option value="Meghna Bank Limited"> Meghna Bank Limited </option><option value="Mercantile Bank Limited"> Mercantile Bank Limited </option><option value="Mutual Trust Bank Limited"> Mutual Trust Bank Limited </option><option value="National Bank Limited"> National Bank Limited </option><option value="National Credit & Commerce Bank Limited"> National Credit & Commerce Bank Limited </option><option value="NRB Bank Limited"> NRB Bank Limited </option><option value="NRB Commercial Bank Ltd"> NRB Commercial Bank Ltd </option><option value="NRB Global Bank Ltd"> NRB Global Bank Ltd </option><option value="One Bank Limited"> One Bank Limited </option><option value="Premier Bank Limited"> Premier Bank Limited </option><option value="Prime Bank Limited"> Prime Bank Limited </option><option value="Pubali Bank Limited"> Pubali Bank Limited </option><option value="Standard Bank Limited"> Standard Bank Limited </option><option value="Shimanto Bank Ltd"> Shimanto Bank Ltd </option><option value="Southeast Bank Limited"> Southeast Bank Limited </option><option value="South Bangla Agriculture and Commerce Bank Limited"> South Bangla Agriculture and Commerce Bank Limited </option><option value="Trust Bank Limited"> Trust Bank Limited </option><option value="United Commercial Bank Ltd"> United Commercial Bank Ltd </option><option value="Uttara Bank Limited"> Uttara Bank Limited </option><option value="Bengal Commercial Bank Ltd"> Bengal Commercial Bank Ltd </option><option value="Islami Bank Bangladesh Limited"> Islami Bank Bangladesh Limited </option><option value="Al-Arafah Islami Bank Limited"> Al-Arafah Islami Bank Limited </option><option value="EXIM Bank Limited"> EXIM Bank Limited </option><option value="First Security Islami Bank Limited"> First Security Islami Bank Limited </option><option value="Shahjalal Islami Bank Limited"> Shahjalal Islami Bank Limited </option><option value="Social Islami Bank Limited"> Social Islami Bank Limited </option><option value="Union Bank Limited"> Union Bank Limited  </option><option value="Sonali Bank Limited"> Sonali Bank Limited </option><option value="Janata Bank Limited"> Janata Bank Limited </option><option value="Agrani Bank Limited"> Agrani Bank Limited </option><option value="Rupali Bank Limited"> Rupali Bank Limited </option>]<option value="BASIC Bank Limited"> BASIC Bank Limited </option></select></div></td><td class="col-sm-2"><select class="form-control" name="book['+s+']" id="book_'+s+'"></select><input type="date" style="margin-top: 3%;" class="form-control" name="transitDate[]" id="transitDate_'+s+'" style="padding: initial;"></td><td><button type="button" name="remove" id="'+rowNum+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
       }else{
           $('#dynamic_field').append('<tr id="row'+rowNum+'"><td class="col-sm-3"><select class="form-control" name="partyName[]" id="name_'+s+'" onchange="calculateDue(this.value, '+s+')">'+$('#name_0').html()+'</select><br><input type="text" class="form-control" style="margin-top: 3%;" name="remarks[]" placeholder=" Remarks " ></td><td class="col-sm-2"><input type="text" class="form-control" name="amounts['+s+']" id="amounts_'+s+'" placeholder="Amount" ></td><td class="col-sm-3"><input type="text" class="form-control" name="discounts'+s+'" id="discounts_'+s+'" placeholder=" Discounts " value="0" ></td><td class="col-sm-2"><input type="text" style="margin-top: 3%;" class="form-control" placeholder=" Due " name="due['+s+']" id="due_'+s+'" Readonly></td><td class="col-sm-2"><select style="margin-top: 3%;" class="form-control" name="book['+s+']" id="book_'+s+'"></select></td><td><button type="button" name="remove" id="'+rowNum+'" class="btn btn-danger btn_remove">X</button></td></tr>');
       }
        $('#dynamic_field').append('<script>$("#name_'+s+'").select2({placeholder: "~~ Party Name ~~",allowClear: true,width: "100%"});$("#chequeBank_'+s+'").select2({placeholder: "~~ Cheque Bank ~~",allowClear: true,width: "100%"});$("#depositBank_'+s+'").select2({placeholder: "~~ Deposit Bank ~~",allowClear: true,width: "100%"})</script>');
    });  
    $(document).on('click', '.btn_remove', function(){  
       var button_id = $(this).attr("id");   
       $('#row'+button_id+'').remove();  
    });  
    $('#submit').click(function(){            
       $.ajax({ 
            url:"EmMoSlip-add12.php",  
            method:"POST",
    		data:$('#add_name').serialize(),  
            success:function(data)  
            {  
                 //alert(data);  
                 $('#add_name')[0].reset();  
            }  
       });  
    });  
    //--------------user for EFT------------------------------------//
    
    $('#addEft').click(function(){  
    	rowNum++;
    	s++;
       $('#dynamic_field2').append('<tr id="row'+rowNum+'"><td class="col-sm-3"><select class="form-control" name="partyNameEft[]" id="nameEft_'+s+'" onchange="calculateDue(this.value, '+s+')">'+$('#nameEft_0').html()+'</select></td><td class="col-sm-1"><input type="text" class="form-control" name="dueEft['+s+']" id="dueEft_'+s+'" Readonly></td><td class="col-sm-1"><input type="text" class="form-control" name="discountEft['+s+']" id="discountEft_'+s+'" placeholder=" Discount " ></td></td><td class="col-sm-2"><input type="text" class="form-control" name="amountsEft['+s+']" id="amountsEft_'+s+'" placeholder="Amount" ></td><td class="col-sm-3"><select class="form-control" name="depositBankEft[]" id="depositBankEft_'+s+'">'+$('#depositBankEft_0').html()+'</select></td><td class="col-sm-2"><input type="text" class="form-control" name="remarksEft[]" placeholder=" Remarks " ><select class="form-control" name="book['+s+']" id="book_'+s+'"></select></td><td><button type="button" name="remove" id="'+rowNum+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
       $('#dynamic_field2').append('<script>$("#name_'+s+'").select2({placeholder: "~~ Party Name ~~",allowClear: true,width: "100%"});$("#chequeBank_'+s+'").select2({placeholder: "~~ Cheque Bank ~~",allowClear: true,width: "100%"})</script>');
    });  
    $(document).on('click', '.btn_remove', function(){  
       var button_id = $(this).attr("id");   
       $('#row'+button_id+'').remove();  
    });  
    $('#submit').click(function(){            
       $.ajax({ 
            url:"EmMoSlip-add12.php",  
            method:"POST",
    		data:$('#add_name').serialize(),  
            success:function(data)  
            {  
                 //alert(data);  
                 $('#add_name')[0].reset();  
            }  
       });  
    });  
    
    
 });
 
 function yesnoCheck(that) {
    
    $("tr[id^='row']").remove();
    document.getElementById("dynamic_field").style.display = "block";
    alert(that.value.toLowerCase());
    if(that.value.toLowerCase() == "cheque"){
        $('.trEft').each(function() {
            $(this).hide();
        });
        $('.trOther').each(function() {
            $(this).show();
        });
        $('.trCheque').each(function() {
            $(this).show();
        });
    }else if(that.value.toLowerCase() == "eft"){
        $('.trCheque').each(function() {
            $(this).hide();
        });
        $('.trEft').each(function() {
            $(this).show();
        });
        $('.trOther').each(function() {
            $(this).show();
        });
    }else{
        $('.trCheque').each(function() {
            $(this).hide();
        });
        $('.trOther').each(function() {
            $(this).hide();
        });
        $('.trEft').each(function() {
            $(this).show();
        });
        //document.getElementById("dynamic_field").style.display = "none"; 
    }
}
 
 
 
 function calculateDue(partyId, rowId){
    var action = 'loadPartyDue';
    var voucherType = "paymentReceived";
    var entryVoucherType = "PartySale";
    var dataString = "partyType="+entryVoucherType+"&voucherType="+voucherType+"&partyId="+partyId+"&action="+action;
    $.ajax({
        type: 'POST',
        url: 'phpScripts/paymentVoucherAction.php',
        data: dataString,
        dataType: 'json',
        beforeSend: function () {
            $('#loading').show();
        },
        success: function(response){
            $("#book_"+rowId).html(response.previousDue);
            $("#due_"+rowId).val(parseFloat(response.totalDue));
        },
        complete: function () {
            $('#loading').hide();
        },
        error: function (xhr) {
            alert(xhr.responseText);
        }
    });
 }
 $("#form_paymentReceivedVoucher").submit(function(event) {
    event.preventDefault();
    var voucherDate = $("#add_date").val();
    var paymentMethod = $("#add_payMode").val();
    var voucherType = "paymentReceived";
    var entryVoucherType = "PartySale";
    var book = [];
    var partyName = [];
    var amount=[];
    var accountNo = [];
    var chequeNumber=[];
    var chequeBank = [];
    var depositBank=[];
    var remarks = [];
    var transitDate=[];
    var discounts=[];
    var count = 0;
    var error = 0;
    var $regexpAlphaNeumeric = /^([a-zA-Z0-9_ '\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ '\.\-\s\,\;\:\/\&\$\%\(\)]+$/;
	var $regexpNumber = /^([0-9_ '\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[0-9_ '\.\-\s\,\;\:\/\&\$\%\(\)]+$/;
	
    $('select[name^="partyName"]').each(function() {
        if($(this).val() != "") {
            $('#'+$(this).attr('id')).removeClass('has-error');
        }
        else{
            $('#'+$(this).attr('id')).addClass('has-error');
            error = 1;
        }
        partyName[count] = $(this).val()+"@!@";
        count = count + 1;
    });
    count = 0;
    $('select[name^="book"]').each(function() {
        if($(this).val().match($regexpNumber)) {
            $('#'+$(this).attr('id')).removeClass('has-error');
        }
        else{
            $('#'+$(this).attr('id')).addClass('has-error');
            error = 1;
        }
        
        book[count] = $(this).val()+"@!@";
        count = count + 1;
    });
    count = 0;
    $('input[name^="amounts"]').each(function() {
        if($(this).val().match($regexpNumber)) {
            $('#'+$(this).attr('id')).removeClass('has-error');
        }
        else{
            $('#'+$(this).attr('id')).addClass('has-error');
            error = 1;
        }
        amount[count] = $(this).val()+"@!@";
        count = count + 1;
    });
    
    if(paymentMethod == 'CHEQUE')
    {
    	count = 0;
        $('input[name^="chequeNo"]').each(function() {
            if($(this).val().match($regexpNumber)) {
                $('#'+$(this).attr('id')).removeClass('has-error');
            }
            else{
                //$('#'+$(this).attr('id')).addClass('has-error');
                //error = 1;
            }
            chequeNumber[count] = $(this).val()+"@!@";
            count = count + 1;
        });
        count = 0;
        $('select[name^="checqueBankName"]').each(function() {
            if($(this).val() != "") {
                $('#'+$(this).attr('id')).removeClass('has-error');
            }
            else{
                //$('#'+$(this).attr('id')).addClass('has-error');
                //error = 1;
            }
            chequeBank[count] = $(this).val()+"@!@";
            count = count + 1;
        });
        count = 0;
        $('input[name^="transitDate"]').each(function() {
            if($(this).val() != "") {
                $('#'+$(this).attr('id')).removeClass('has-error');
            }
            else{
                //$('#'+$(this).attr('id')).addClass('has-error');
                //error = 1;
            }
            transitDate[count] = $(this).val()+"@!@";
            count = count + 1;
        });
    }
    count = 0;
    $('select[name^="depositBank"]').each(function() {
        if($(this).val() != "") {
            $('#'+$(this).attr('id')).removeClass('has-error');
        }
        else{
            //$('#'+$(this).attr('id')).addClass('has-error');
            //error = 1;
        }
        accountNo[count] = $(this).val()+"@!@";
        count = count + 1;
    });
    count = 0;
    $('input[name^="remarks"]').each(function() {
         if($(this).val().match($regexpAlphaNeumeric)) {
            $('#'+$(this).attr('id')).removeClass('has-error');
        }
        else{
            //$('#'+$(this).attr('id')).addClass('has-error');
            //error = 1;
        }
        remarks[count] = $(this).val()+"@!@";
        count = count + 1;
    });
    count = 0;
    $('input[name^="discounts"]').each(function() {
        if($(this).val().match($regexpNumber)) {
            $('#'+$(this).attr('id')).removeClass('has-error');
        }
        else{
            $('#'+$(this).attr('id')).addClass('has-error');
            error = 1;
        }
        discounts[count] = $(this).val()+"@!@";
        count = count + 1;
    });
    if(error == 0){
        var fd = new FormData();
        fd.append('partyName',partyName);
        //alert(partyName);
        fd.append('voucherDate',voucherDate);
        fd.append('book',book);
        fd.append('amount',amount);
        fd.append('paymentMethod',paymentMethod);
        fd.append('accountNo',accountNo);
        fd.append('chequeNumber',chequeNumber);
        fd.append('chequeBank',chequeBank);
        //fd.append('depositBank',depositBank);
        fd.append('remarks',remarks);
        fd.append('transitDate',transitDate);
        fd.append('voucherType',voucherType);
        fd.append('entryVoucherType',entryVoucherType);
        fd.append('discounts',discounts);
        fd.append('action','saveBulkVoucher');
       
        $.ajax({
            type: 'POST',
            url: 'phpScripts/paymentVoucherAction.php',
            data: fd,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response){
                if(response == "Success"){
                    $("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> Saved Successfully");
            		$("#divMsg").show().delay(2000).fadeOut().queue(function(n) {
            		  $(this).hide(); n();
            	    });
                    location.href = "https://jafree.alitechbd.com/manageVoucher.php?voucherType=paymentReceived&viewFrom=bulkPaymentReceived";
                }else{
                    alert("Error: "+response);
                }
            },error: function (xhr) {
                alert(xhr.responseText);
            }
        });
    }else{
        $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Fill up all the fields. otherwise not possible to save");
		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
		  $(this).hide(); n();
		});
    }
 })

		/*------------------------------------- Select 2 portion ------------------------------------*/
$("#depositBank_0").select2( {
	placeholder: "~~ deposit Bank ~~",
	allowClear: true,
    width: '100%'
} );
$("#checqueBank_0").select2( {
	placeholder: "~~ Cheque Bank ~~",
	allowClear: true,
	width: '100%'
} );
$('#name_0').select2({
    placeholder: "~~ Party Name ~~",
	allowClear: true,
	width: '100%'
})
$("#damageProducts").select2({
	placeholder: "~~ Select Products ~~",
	allowClear: true
});