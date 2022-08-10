var manageTsTable;
$(document).ready(function() {
	// manage Shop table
	manageTsTable = $("#manageTSTable").DataTable({
		'ajax': "phpScripts/temporarySaleAction.php?sortData="+$("#sortData").val(),
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
$("#sortData").change(function() {
    manageTsTable.ajax.url("phpScripts/temporarySaleAction.php?sortData="+$("#sortData").val()).load();
});
/*------------------ Start Delete walkin sales, sales products and sales voucher (if exists) ---------------------- */
function deleteSales(salesId){
    var conMsg = confirm("Are you sure to delete??");
	if(conMsg){
	    $.ajax({
			type: 'POST',
			url: 'phpScripts/temporarySaleAction.php',
			data: "action=deleteSales&id="+salesId,
			dataType: 'json',
			success: function(response){
				if(response == "Success"){
					manageTsTable.ajax.reload(null, false);
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
/*------------------ End Delete walkin sales, sales products and sales voucher (if exists) ---------------------- */