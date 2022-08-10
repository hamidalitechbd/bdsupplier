var manageOrderTableView;
$(document).ready(function() {
    manageOrderTableView = $("#orderSalesTableView").DataTable({
		'ajax': 'phpScripts/orderProcessingListAction.php',
		'order': [],
		'dom': 'Bfrtip',
		'scrollX': true,
        'buttons': [
            'pageLength','copy', 'csv', 'pdf', 'print'
        ],
		language: {
            processing: "<img src='../images/loader.gif'>"
        },
        processing: true
	})
	$('#table-filter').on('change', function(){
	   manageOrderTableView.search(this.value).draw();   
	});
});

load_cart_icon();
function add_to_cart(product_id, product_name, product_price, min_price, max_price, product_quantity){
    $("#divErrorMsg").hide();
	var product_discount = '0';
	var action = "add";
	var grandTotal = 0;
	if(product_quantity > 0)
	{
		$.ajax({
			url:"phpScripts/productsOrderAction.php",
			method:"POST",
			data:{product_id:product_id, product_name:product_name, product_discount:product_discount, product_price:product_price,min_price:min_price, max_price:max_price, product_quantity:product_quantity, grandTotal:grandTotal, action:action},
			success:function(data)
			{
				load_cart_icon();
    			$("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> Add To Cart");
    			$("#divMsg").show().delay(2000).fadeOut().queue(function(n) {
    			  $(this).hide(); n();
    			});
			},
			error: function (xhr) {
				alert(xhr.responseText);
			}
		});
			
		
	}
	else
	{
		$("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error ! </strong> Please Enter Number of Quantity");
		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
		  $(this).hide(); n();
		});
	}
}
function load_cart_icon(){
    var action = "fetchCartIcon";
    $.ajax({
		url:"phpScripts/productsOrderAction.php",
		method:"POST",
		data:{action:action},
		success:function(data)
		{
			$("#cartCount").html(data);
		},
		error: function (xhr) {
			alert(xhr.responseText);
		}
	});
}

function orderFinal(id){
    var conMsg = confirm("Are you sure to Final Order ??");
	if(conMsg){
        var action = "orderFinal";
        $.ajax({
    		url:"phpScripts/orderProcessingListAction.php",
    		method:"POST",
    		data:{action:action, orderId:id},
    		success:function(data)
    		{
    		    if(data == "Success"){
    		       
    		        $("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong>Order Completed Successfully");
    				$("#divMsg").show().delay(2000).fadeOut().queue(function(n) {
    				  $(this).hide(); n();
    			    });
    			    location.href = 'orderList.php?page=Completed';
                }else{
                    $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error 7! </strong>"+response);
            		$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
            		  $(this).hide(); n();
            		});
                }
    		},error: function (xhr) {
    			//alert(xhr.responseText);
                $("#divErrorMsg").html("<strong><i class='icon fa fa-trash'></i>Error 8! </strong>"+xhr.responseText);
            	$("#divErrorMsg").show().delay(10000).fadeOut().queue(function(n) {
            	  $(this).hide(); n();
            	});
    		}
    	});
	}
}

// View order 

//Edit Unit
function viewOrderFinal(orderId){
    //$("#loader").show();
    
    var dataString = "id="+orderId;
    //alert(dataString);
    $.ajax({
        type: 'POST',
        url: 'phpScripts/orderView-row.php',
        data: dataString,
        dataType: 'json',
        beforeSend: function(){
            // Show image container
            $("#editLoader").show();
       },
        success: function(response){
          $('#orderId').val(response.id);
          $('#oId').val(response.id);
          $('#orderNo').html(response.orderNo);
          $('#orderDate').html(response.orderDate);
          $('#grandTotal').html(response.total_after_discount+' tk');
          $('#paidAmount').html(response.paidAmount+' tk');
          $('#dueAmount').html(response.dueAmount+' tk');
          $('#bankName').html(response.bankName);
          $('#accountNo').html(response.accountNo);
          $('#methodName').html(response.methodName+' Payment ');
          $('#accountName').html(response.accountName);
          $('#branchName').html(response.branchName);
          $('#bkash_number').html(response.bkash_number+' Payment');
          $('#bkash_amount').html(response.bkash_amount+ ' tk');
          
          if(response.received_amount > 0){
              $("#received_form").hide();
              $('#received_amount').show();
              $('#received_amount').html(response.received_amount+ ' tk');
          }else{
              $("#received_form").show();
              $('#received_amount').hide();
              $('#received_amount').html('0 tk');
          }
            $('#rcvDue').html(response.rcvDue+ ' tk');
            $('#viewOrderFinal').modal('show');
            //orderSalesTableView.ajax.reload(null, false);
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

// Received Amount portion
$(document).ready(function() {
	$('#butsave').on('click', function() {
		$("#butsave").attr("disabled", "disabled");
		var orderId = $('#orderId').val();
		var recvAmount = $('#recvAmount').val();
		if(recvAmount!=""){
			$.ajax({
				url: "phpScripts/receivedAmountSave.php",
				type: "POST",
				data: {
					orderId: orderId,
					recvAmount: recvAmount
									
				},
				cache: false,
				success: function(dataResult){
					var dataResult = JSON.parse(dataResult);
					if(dataResult.statusCode==200){
						$("#butsave").removeAttr("disabled");
						$('#fupForm').find('input:text').val('');
						$("#success").show();
						$('#success').html('Received Amount added successfully !');
						$("#success").show().delay(2000).fadeOut().queue(function(n) {
            			  $(this).hide(); n();
            			});
            			$('#viewOrderFinal').modal('hide');
                        manageOrderTableView.ajax.reload(null, false);
					}
					else if(dataResult.statusCode==201){
					   alert("Error occured !");
					}
					
				}
			});
		}
		else{
			alert('Please fill all the field !');
		}
	});
});

// Sales excecutive Bkash payment
function paymentBkash(orderId){
    //$("#loader").show();
    var dataString = "id="+orderId;
    //alert(dataString);
    $.ajax({
        type: 'POST',
        url: 'phpScripts/orderView-row.php',
        data: dataString,
        dataType: 'json',
        beforeSend: function(){
            // Show image container
            $("#editLoader").show();
       },
        success: function(response){
          $('#orderId2').val(response.id);
          $('#oId2').val(response.id);
          $('#orderNo2').html(response.orderNo);
          $('#orderDate2').html(response.orderDate);
          $('#grandTotal2').html(response.total_after_discount+' tk');
          $('#paidAmount2').html(response.paidAmount+' tk');
          $('#dueAmount2').html(response.dueAmount+' tk');
          $('#bankName2').html(response.bankName);
          $('#accountNo2').html(response.accountNo);
          $('#methodName2').html(response.methodName+' Payment ');
          $('#accountName2').html(response.accountName);
          $('#branchName2').html(response.branchName);
          $('#received_amount2').html(response.received_amount+ ' tk');
          $('#rcvDue2').html(response.rcvDue+ ' tk');
          $('#bkashAmount2').val(response.rcvDue);
          $('#bkash_number2').html(response.bkash_number+' Payment');
          $('#bkash_amount2').html(response.bkash_amount+ ' tk');
         
        }
        ,error: function (xhr) {
            alert(xhr.responseText);
        },
        complete:function(data){
            // Hide image container
            $("#editLoader").hide();
        }
        
    });$('#paymentBkash').modal('show');
}

$(document).ready(function() {
	$('#butsaveBkash').on('click', function() {
	  
		$("#butsaveBkash").attr("disabled", "disabled");
		var oId2 = $('#oId2').val();
		var bkashid = $('#bkashid').val();
		var bkashAmount = $('#bkashAmount2').val(); 
		alert(bkashAmount);
		if(recvAmount!=""){
			$.ajax({
				url: "phpScripts/receivedAmountSave.php",
				type: "POST",
				data: {
					oId2: oId2,
					bkashid: bkashid,
					bkashAmount: bkashAmount
									
				},
				cache: false,
				success: function(dataResult){
					var dataResult = JSON.parse(dataResult);
					if(dataResult.statusCode==200){
						$("#butsaveBkash").removeAttr("disabled");
						$('#fupFormBkash').find('input:text').val('');
						$("#successBkash").show();
						$('#successBkash').html('Bkash Amount added successfully !');
						$("#successBkash").show().delay(2000).fadeOut().queue(function(n) {
            			  $(this).hide(); n();
            			});
					}
					else if(dataResult.statusCode==201){
					   alert("Error occured !");
					}
					
				}
			});
		}
		else{
			alert('Please fill all the field !');
		}
	});
});

/*------------------ Start process to check Status Change Order sales ...................................*/
function changeProcessStatus(orderId){
    //alert(orderId);
    var conMsg = confirm("Are you sure to Status Change ??");
	if(conMsg){
	    $.ajax({
			type: 'POST',
			url: 'phpScripts/receivedAmountSave.php',
			data: "action=changeProcessStatus&id="+orderId,
			dataType: 'json',
			success: function(response){
				if(response == "Success"){
				    manageOrderTableView.ajax.reload(null, false);
					$("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> Successfully Status Changed");
					$("#divMsg").show().delay(500000).fadeOut().queue(function(n) {
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
/*------------------ End Status process to check Change Order sales...................................*/
