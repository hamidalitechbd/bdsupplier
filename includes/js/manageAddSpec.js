$("#submitData").click(function(){
    var categoryProductsIds = "";
    $(".item").each(function() {$
        categoryProductsIds += $(this).attr('id')+",";
    });
    var fd = new FormData();
    fd.append('categoryProductsIds', categoryProductsIds);
    fd.append('action', 'updateCategoryProductsOrdering');
    $.ajax({
        type: 'POST',
        url: 'phpScripts/managePrintBookCategoryView.php',
        data: fd,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(response) {
            window.location.reload();
        },
        error: function(xhr) {
            alert("Error: " + xhr.responseText);
        }
    });
});
/*----------------Start addNewSpec Incormation---------------*/
function addNewSpec(id) {
    $('#addNewSpec').modal('show');
    var dataString = "id=" + id + "&action=loadPrintBookSpec";
    $("#addSpecProductId").val(id);
    $.ajax({
        type: 'POST',
        url: 'phpScripts/managePrintBookCategoryView.php',
        data: dataString,
        dataType: 'json',
        success: function(response) {
            $("#printBookSpecTable").html(response.modalTable);
            $("#printBookProductViewTbl" + id).html(response.displayTable);
        },
        error: function(xhr) {
            alert("Error: " + xhr.responseText);
        }
    });
}
/*----------------End addNewSpec  Incormation---------------*/


/*----------------Start image Ad---------------*/
function addImageAD(unitId){
    $('#insertImageAd').modal('show');
    $("#catatlougAd").val(unitId);
}

// image view
var loadFile = function(event) {
var output = document.getElementById('output');
	output.src = URL.createObjectURL(event.target.files[0]);
};
/*----------------End image ad---------------*/

/*----------------Start Insert image ad---------------*/
 $(document).on("submit", "#form_adImageAdd", function() {
    
    event.preventDefault(); 
    var catatlougAd = $("#catatlougAd").val();
	var userPhoto = $('#ad_logo')[0].files[0];
	var fd = new FormData();
    fd.append('id',catatlougAd);
    fd.append('file',userPhoto);
    fd.append('saveADImage','1');
    $.ajax({
        type: 'POST',
        url: 'phpScripts/managePrintBookCategoryView.php',
		data: fd,
		contentType: false,
	    processData: false,
		dataType: 'json',
		success: function(response){
			if(response=='Success'){
			    $('#insertImageAd').modal('hide');
			    $("#ad_logo").val('').trigger('change');
			    $("#catatlougAd").val("");
                $("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success AD! </strong> Successfully Saved");
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
/*----------------End Insert image ad---------------*/

/*----------------Start PrintBookSpec Information save & validation parts----------------------*/
$(document).ready(function() {
    $('#form_addPrintBookProductSpec').bootstrapValidator({
        live: 'enabled',
        message: 'This value is not valid',
        submitButton: '$form_addPrintBookProductSpec button [type="Submit"]',
        submitHandler: function(validator, form, submitButton) {
            var addSpecProductId = $("#addSpecProductId").val();
            var addSpecName = $("#add_specName").val();
            var addSpecValue = $("#add_specValue").val();
            var specId = $("#specId").val();
            var addSpecType = $("#add_specType").val();
            var fd = new FormData();
            fd.append('addProductId', addSpecProductId);
            fd.append('addSpecName', addSpecName);
            fd.append('specId', specId);
            fd.append('addSpecValue', addSpecValue);
            fd.append('addSpecType', addSpecType);
            fd.append('action', 'addPrintBookProductSpec');
            $.ajax({
                type: 'POST',
                url: 'phpScripts/managePrintBookCategoryView.php',
                data: fd,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(response) {
                    if (response == 'Success') {
                        $("#specId").val("");
                        $("#add_specType").val("");
                        $("#add_specValue").val("");
                        $("#add_specName").val("");
                        
                        $('#addNewBookSpec').modal('hide');
                        addNewSpec(addSpecProductId);
                        $("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> Successfully Added");
                        $("#divMsg").show().delay(2000).fadeOut().queue(function(n) {
                            $(this).hide();
                            n();
                        });
                        //$("#form_addPrintBookProductSpec")[0].reset();
                    } else {
                        alert(response);
                    }
                },
                error: function(xhr) {
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
            addProductName: {
                validators: {
                    stringLength: {
                        min: 1,
                    },
                    notEmpty: {
                        message: 'Please Insert Product Name'
                    },
                    // regexp: {
                    //     regexp: /^([a-zA-Z0-9_ '\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ '\.\-\s\,\;\:\/\&\$\%\(\)]+$/,
                    //     message: 'Please insert alphanumeric value only'
                    // }
                }
            },
            addSpecName: {
                validators: {
                    stringLength: {
                        min: 1,
                    },
                    notEmpty: {
                        message: 'Please Insert PrintBook Name'
                    },
                }
            },
        }
    });
});
/*----------------End PrintBookSpec Information save & validation parts----------------------*/


/*------------------ Start Remove Product  ---------------------- */

function removeProduct(productId) {
    if (confirm("Are you sure to remove!") == true) {
        var id = productId;
        var dataString = "id=" + id + "&action=removeProductFromBook";
        $.ajax({
            type: 'POST',
            url: 'phpScripts/managePrintBookCategoryView.php',
            data: dataString,
            dataType: 'json',
            success: function(response) {
                window.location.reload();
            },
            error: function(xhr) {
                alert("Error: " + xhr.responseText);
            }
        });
    }
    //$("#" + id).fadeOut(1500);
    /*setTimeout(
        function() {
            $("#" + id).remove();
        }, 1000);*/
}



/*------------------ End Remove Product Load ---------------------- */




/*----------------Start Edit Spec----------------------*/
function editProducSpec(id) {
    var specId = id;
    var fd = new FormData();
        fd.append('id', specId);
        fd.append('action', 'editPrintBookProductSpec');
        $.ajax({
            type: 'POST',
            url: 'phpScripts/managePrintBookCategoryView.php',
            data: fd,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
               // alert(JSON.stringify(response[0].spec_name));
               //$("#addSpecProductId").val(response.tbl_product_id);
                $("#add_specName").val(response.spec_name);
                $("#add_specValue").val(response.spec_value);
                $("#specId").val(response.id);
                $("#add_specType").val(response.spec_type);
            },
            error: function(xhr) {
                alert(xhr.responseText);
            }
        });
    
}

/*----------------End Delete Spec parts----------------------*/

/*----------------Start Delete Spec----------------------*/
function deleProducSpec(id) {

    var addSpecProductId = $("#addSpecProductId").val();
    var specId = id;
    var deleteConfirm = confirm("Do you want to delete!?");

    if (deleteConfirm == true) {
        var fd = new FormData();
        fd.append('productId', addSpecProductId);
        fd.append('id', specId);
        fd.append('action', 'deletePrintBookProductSpec');
        $.ajax({
            type: 'POST',
            url: 'phpScripts/managePrintBookCategoryView.php',
            data: fd,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                if (response == 'Success') {
                    addNewSpec(addSpecProductId);
                } else {
                    alert(response);
                }
            },
            error: function(xhr) {
                alert(xhr.responseText);
            }
        });
    }
}

/*----------------End Delete Spec parts----------------------*/


/*==================== Start Spec For List ====================*/
function addSpecHead(id){
    $('#addSpecHead').modal('show');
    $("#printBookId").val(id);
    var productIds = [];
    let i = 0;
    $(".productIds").each(function(){
        productIds.push($(this).val());
        i++;
    });
    
    getSpecHead(id);
}

function saveSpecHead() {
    let headName = $("#addSpecHeadName").val();
    let editSpecHead = $("#editSpecHead").val();
    let id = $("#printBookId").val();
    if (headName) {
        var productIds = [];
        let i = 0;
        $(".productIds").each(function(){
            productIds.push($(this).val());
            i++;
        });
        var fd = new FormData();
        fd.append('headName', headName);
        fd.append('productIds', productIds);
        fd.append('editSpecHead', editSpecHead);
        fd.append('action', 'addSpecHead');
        $.ajax({
            type: 'POST',
            url: 'phpScripts/managePrintBookCategoryView.php',
            data: fd,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                $("#editSpecHead").val('');
                $("#addSpecHeadName").val('');
                $("#specHeadDisplayTable .action:last").before('<th scope="col">'+headName+'</th>');
                getSpecHead(id);
                $( "#tbl_manageCart" ).load(window.location.href + " #tbl_manageCart" );
            },
            error: function(xhr) {
                alert(xhr.responseText);
            }
        });
   } else{
       alert("Please! Enter Spec Head");
   }
   
}

function getSpecHead(id) {

        var fd = new FormData();
        fd.append('id', id);
        fd.append('action', 'getSpecHead');
        $.ajax({
            type: 'POST',
            url: 'phpScripts/managePrintBookCategoryView.php',
            data: fd,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                $("#manageAddSpecHeadTable").html('');
                $("#manageAddSpecHeadTable").html(response['specHeads']);
            },
            error: function(xhr) {
                alert(xhr.responseText);
            }
        });

}



const editSpecHead=(headName)=>{
           $("#addSpecHeadName").val(headName);
           $("#editSpecHead").val(headName);
}

const deleteSpecHead=(headName)=>{
    if (confirm("Are you sure to remove!") == true) {
        var fd = new FormData();
        fd.append('headName', headName);
        fd.append('action', 'deleteSpecHead');
        $.ajax({
            type: 'POST',
            url: 'phpScripts/managePrintBookCategoryView.php',
            data: fd,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                let id = $("#printBookId").val();
                getSpecHead(id);
                $("#tbl_manageCart" ).load(window.location.href + " #tbl_manageCart" );
            },
            error: function(xhr) {
                alert(xhr.responseText);
            }
        });
    }
}


var ID = 0;
var productID = 0;
var specHeadNames = [];
const addSpecList=(id,productId,numOfHead)=>{
    ID = id;
    productID = productId;
    //let specfield = '';
    specHeadNames = [];
    let i = 0;
    $(".specHeadNames").each(function(){
        specHeadNames.push($(this).val());
        i++;
    });
    // for(let i=0; i<numOfHead; i++){
    //     specfield += '<div class="col-sm-6 specListDiv"><label for="specValueList" class="control-label">'+specHeadNames[i]+' :</label><input type="text" class="form-control specValueList" id="specValueList" name="specValueList" data-specId="54545555" placeholder=" Spec Value " required></div>';
    // }    
    $('.specListDiv').remove();
    $('#addSpecList').modal('show');
    getSpecList(productID);
}

function getSpecList(id) {
    
    var fd = new FormData();
    console.log(id);
    console.log(specHeadNames);
    fd.append('productID', id);
    fd.append('specHeadNames', specHeadNames);
    fd.append('action', 'getSpecList');
    $.ajax({
        type: 'POST',
        url: 'phpScripts/managePrintBookCategoryView.php',
        data: fd,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(response) {
           $('#specListFileds').prepend(response);
        },
        error: function(xhr) {
            alert(xhr.responseText);
        }
    });

}

const saveSpecList=()=>{

    var specValueLists = [];
    let i = 0;
    $(".specValueList").each(function(){
        specValueLists[i] = $(this).val();
        i++;
    });

    var fd = new FormData();
        fd.append('productID', productID);
        fd.append('specValueLists', specValueLists);
        fd.append('specHeadNames', specHeadNames);
        fd.append('action', 'saveSpecList');
        $.ajax({
            type: 'POST',
            url: 'phpScripts/managePrintBookCategoryView.php',
            data: fd,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                alert(JSON.stringify(response));
                $('#addSpecList').modal('hide');
                $("#tbl_manageCart" ).load(window.location.href + " #tbl_manageCart" );
            },
            error: function(xhr) {
                alert(xhr.responseText);
            }
        });

}


/*---------------- Start Spec image For List View---------------*/
function addImage(id){
    $('#addImage').modal('show');
    $("#catatlougId").val(id);
}

// Image View
var loadImage = function(event) {
    var output = document.getElementById('outputImage');
        output.src = URL.createObjectURL(event.target.files[0]);
    };

    

 $(document).on("submit", "#form_image", function() {
    
    event.preventDefault(); 
    var catatlougId = $("#catatlougId").val();
	var specImage = $('#specImage')[0].files[0];
	var fd = new FormData();
    fd.append('id',catatlougId);
    fd.append('file',specImage);
    fd.append('saveImage','1');
    $.ajax({
        type: 'POST',
        url: 'phpScripts/managePrintBookCategoryView.php',
		data: fd,
		contentType: false,
	    processData: false,
		dataType: 'json',
		success: function(response){
			if(response=='Success'){
                alert(JSON.stringify(response));
			    $('#addImage').modal('hide');
			    $("#specImage").val('').trigger('change');
			    $("#specImage").val("");
                $("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success Image! </strong> Successfully Saved");
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
/*----------------End Spec image For List View ---------------*/
function orderingCalculation(productId,categoryId){
    var serial = $("#numberList_"+productId).val();
    alert(serial);
    var fd = new FormData();
    fd.append('categoryId',categoryId);
    fd.append('productId',productId);
    fd.append('serial',serial);
    fd.append('action','updateListOrderingCalculation');
    $.ajax({
        type: 'POST',
        url: 'phpScripts/managePrintBookCategoryView.php',
		data: fd,
		contentType: false,
	    processData: false,
		dataType: 'json',
		success: function(response){
			if(response=='Success'){
                alert("Successfully Updated");
                //$("#numberList_"+productId).val('').trigger('change');
			    window.location.href='dataGridViewTest.php?id='+categoryId+'&type=List';
			}else{
			    
			    alert(JSON.stringify(response));
			}
		  
		},error: function (xhr) {
			alert('Error: '+JSON.stringify(xhr));
		}
	  });
}

/*==================== End Spec For List ====================*/