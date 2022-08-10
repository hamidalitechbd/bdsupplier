var checkLoadParty = 'No';
var manageSalesTable;
$(document).ready(function() {
	// manage Shop table
	manageSalesTable = $("#manageSalesTable").DataTable({
		'ajax': 'phpScripts/whoseSaleAction.php?sortData='+$("#sortData").val(),
		'order': [],
		'dom': 'Bfrtip',
        'buttons': ['pageLength','copy', 'csv', 'pdf', 'print'],
		language: {
            processing: "<img src='../images/loader.gif'>"
        },
        processing: true
	});
});
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
        manageSalesTable.ajax.url("phpScripts/whoseSaleAction.php?sortData="+$("#sortData").val()).load();
        
    }
});
$("#customers").change(function() {
    if($("#sortData").val() == "-1,-1"){
        manageSalesTable.ajax.url("phpScripts/whoseSaleAction.php?customerId="+$("#customers").val()).load();
        $("#customer_div").show();
    }
});
/*------------------ Start Delete walkin sales, sales products and sales voucher (if exists) ---------------------- */
function deleteSales(salesId){
    var conMsg = confirm("Are you sure to delete??");
	if(conMsg){
	    $.ajax({
			type: 'POST',
			url: 'phpScripts/whoseSaleAction.php',
			data: "action=deleteSales&id="+salesId,
			dataType: 'json',
			success: function(response){
				if(response == "Success"){
					manageSalesTable.ajax.reload(null, false);
					$("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> Successfully Deleted");
					$("#divMsg").show().delay(2000).fadeOut().queue(function(n) {
					  $(this).hide(); n();
					});
				}else{
					$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> "+response);
					$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
					  $(this).hide(); n();
					});
				}
			},error: function (xhr) {
				alert(xhr.responseText);
			}
	    });
	}
}

function createChallan(salesId, salesType){
	var dataString = "salesId="+salesId+"&salesType="+salesType+"&action=createChallanAccess";
	$.ajax({
        type: 'POST',
        url: 'phpScripts/createChallanAction.php',
        data: dataString,
        beforeSend: function () {
            $('#loading').show();
        },
		success: function(response){
			if(response > 0){
				location.href = 'create-challan.php?salesId='+salesId+'&salesType='+salesType;
			}else{
			    $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Challan already created. So not possible to create more challan from this invoice.");
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

/*------------------ End Delete walkin sales, sales products and sales voucher (if exists) ---------------------- */