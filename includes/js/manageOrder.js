var checkLoadParty = 'No';
var manageProductsTable;
var manageOrderTableView;
$(document).ready(function() {
     var brandId = $("#brandId").val();
     if(brandId == ""){
         brandId = "0";
     }
	manageProductsTable = $("#manageOrderTable").DataTable({
		'ajax': 'phpScripts/productsOrderAction.php?id='+brandId,
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
	   //manageProductsTable.search(this.value).draw();
	   var brandId = $("#table-filter").val();
	   location.href = 'orderPanel.php?id='+brandId;
	});
});

$(document).ready(function () {             
  $('.manageOrderTable input[type="search"]').css(
     {'width':'500px','display':'inline-block'}
  );
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
        //manageSalesTable.ajax.url("phpScripts/whoseSaleAction.php?sortData="+$("#sortData").val()).load();
        manageOrderTableView.ajax.url('phpScripts/productsOrderViewAction.php?page='+$("#type").val()+'&sortData='+$("#sortData").val()).load();
        
    }
});

$("#customers").change(function() {
    if($("#sortData").val() == "-1,-1"){
        manageOrderTableView.ajax.url('phpScripts/productsOrderViewAction.php?page='+$("#type").val()+'&customerId='+$("#customers").val()).load();
        $("#customer_div").show();
    }
});


$(document).ready(function() {
   
    manageOrderTableView = $("#orderSalesTableView").DataTable({
		'ajax': 'phpScripts/productsOrderViewAction.php?page='+$("#type").val()+'&sortData='+$("#sortData").val()+'',
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

/*------------------ Start cancel Order sales...................................*/
function cancelOrder(orderId){
    //alert(orderId);
    var conMsg = confirm("Are you sure to Cancel Order ??");
	if(conMsg){
	    $.ajax({
			type: 'POST',
			url: 'phpScripts/receivedAmountSave.php',
			data: "action=cancelOrder&id="+orderId,
			dataType: 'json',
			success: function(response){
				if(response == "Success"){
				    manageOrderTableView.ajax.reload(null, false);
					$("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> Successfully Canceled");
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
/*------------------ End cancel Order sales...................................*/

/*------------------ Start Check to Pending Status Change Order sales ...................................*/
function changeCheckStatus(orderId){
    //alert(orderId);
    var conMsg = confirm("Are you sure to Status Change ??");
	if(conMsg){
	    $.ajax({
			type: 'POST',
			url: 'phpScripts/receivedAmountSave.php',
			data: "action=changeCheckStatus&id="+orderId,
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
/*------------------ End Status Check to Pending Change Order sales...................................*/


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
			beforeSend: function () {
                $('#loading').show();
            },
			success:function(data)
			{
				load_cart_icon();
    			$("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> Add To Cart");
    			$("#divMsg").show().delay(500000).fadeOut().queue(function(n) {
    			  $(this).hide(); n();
    			});
			},
            complete: function () {
                $('#loading').hide();
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

function order_regenerate(order_id){
    //var conMsg = confirm("Are you sure to re-generate the order?? This step will remove the previously saved cart data.");
	//if(conMsg){
	
        var action = "orderRegenerate";
        $.ajax({
    		url:"phpScripts/productsOrderAction.php",
    		method:"POST",
    		data:{order_id:order_id,action:action},
    		success:function(data)
    		{
    		    if(data == "Success"){
    		        location.href = 'orderCheckOutView.php';
    		    }
    			//$("#cartCount").html(data);
    			
    		},
    		error: function (xhr) {
    			alert(xhr.responseText);
    		}
    	});
	//}
}

// Discount Offer modal view
function discountOffer(type,productId){
    //alert(productId);
    	var action = "discountOffer";
    	var fd = new FormData();
    	fd.append('productId',productId);
    	fd.append('type',type);
    	fd.append('action',action);
        $.ajax({
    		url:"phpScripts/discountOfferAction.php",
    		method:"POST",
    		data:fd,
    		contentType: false,
    		processData: false,
    		beforeSend: function () {
                $('#loading').show();
            },
    		success:function(data)
    		{
    		   //alert(data);
                $("#discountOfferModal").modal('show');
                $("#discountOfferDetails").html(data);
    		},
            complete: function () {
                $('#loading').hide();
            },
    		error: function (xhr) {
    			alert(xhr.responseText);
    		}
    	});
    
}
