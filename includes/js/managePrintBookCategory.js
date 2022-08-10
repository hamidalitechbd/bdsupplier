var manageUserTable;
$(document).ready(function() {
    // manage Shop table
    managePrintBookCategoryTable = $("#managePrintBookCategoryTable").DataTable({
        'ajax': 'phpScripts/managePrintBookCategoryView.php?id=' + $("#add_printBookNameView").val(),
        'order': [],
        'dom': 'Bfrtip',
        'buttons': [
            'pageLength', 'copy', 'csv', 'pdf', 'print'
        ],
        language: {
            processing: "<img src='../images/loader.gif'>"
        },
        processing: true
    });
});


$("#add_printBookNameView").change(function() {
    managePrintBookCategoryTable.ajax.url('phpScripts/managePrintBookCategoryView.php?id=' + $("#add_printBookNameView").val()).load();
});


function deleteCataloug(id){
    var conMsg = confirm("Are you sure to In-Active??");
    
	if(conMsg){
        var fd = new FormData();
        fd.append('id',id);
        fd.append('saveDeleteCataloug', '1');
        $.ajax({
    		type: 'POST',
    		url: 'phpScripts/managePrintBookCategory-add.php',
    		data: fd,
    		contentType: false,
    	    processData: false,
    		dataType: 'json',
    		success: function(response){
    			if(response=='Success'){respon
    			    managePrintBookCategoryTable.ajax.reload(null, false);
    			   $("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> In-Active Successfully");
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
	}else{
	    $("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> You are safe to remove this data.");
    	   $("#divMsg").show().delay(2000).fadeOut().queue(function(n) {
    		  $(this).hide(); n();
    		});
	}
}

/*------------------ Start Brand Load ---------------------- */

function loadItemByBrands(id) {
    var flag = id;
    var productIds = [];
    var categoryId = 0;
    var brandId = 0;
    var PrintBookCategoryId = "";
    if (flag == 1) {
        categoryId = $("#add_categoryName").val();
        brandId = $("#add_brandName").val();
    } else {
        categoryId = $("#edit_categoryName").val();
        brandId = $("#edit_brandName").val();
        PrintBookCategoryId =$("#edit_PrintBookCategoryId").val();
    }
    var fd = new FormData();
    fd.append('categoryId', categoryId);
    fd.append('brandId', brandId);
    fd.append('PrintBookCategoryId', PrintBookCategoryId);
    fd.append('flag', flag);
    fd.append('products', '1');
    $.ajax({
        type: 'POST',
        url: 'phpScripts/managePrintBookCategoryView.php',
        data: fd,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(response) {
            productIds = response.productIds;
            //alert(flag);
            if (flag == 1) {
                $("#manageCartTable").html(response.data);
            } else {
                $("#manageEditCartTable").html(response.data);
                $("#manageRemovedCartTable").html(response.removed_data);
            }
        },
        error: function(xhr) {
            alert(xhr.responseText);
        }
    });
}

$("#add_categoryName").change(function() {
    var categoryId = $("#add_categoryName").val();
    var fd = new FormData();
    fd.append('categoryId', categoryId);
    fd.append('action', 'loadBrandByCategory');
    $.ajax({
        type: 'POST',
        url: 'phpScripts/managePrintBookCategoryView.php',
        data: fd,
        contentType: false,
        processData: false,
        success: function(response) {
            $("#add_brandName").html(response);
        },
        error: function(xhr) {
            alert("Error: " + xhr.responseText);
        }
    });
});


$("#edit_categoryName").change(function() {
    var categoryId = $("#edit_categoryName").val();
    var fd = new FormData();
    fd.append('categoryId', categoryId);
    fd.append('action', 'loadBrandByCategory');
    $.ajax({
        type: 'POST',
        url: 'phpScripts/managePrintBookCategoryView.php',
        data: fd,
        contentType: false,
        processData: false,
        success: function(response) {
            $("#edit_brandName").html(response);
        },
        error: function(xhr) {
            alert("Error: " + xhr.responseText);
        }
    });
});
/*------------------ End Brand Load ---------------------- */






/*------------------ Start Remove Product Load ---------------------- */

function removeProduct(productId,type) {
     /*var id = productId;
   var spinner = '<td colspan="4" class="bg-danger text-warning text-center"> Removed   ';
    $("#" + id).html(spinner);
    setTimeout(
        function() {
            $("#" + id).remove();
        }, 1000);*/
    if (confirm("Are you sure to remove!") == true) {
        var id = productId;
        if(type == 0){ //0 for add remove from tr
            $("#" + id).remove();
        }else{
            var dataString = "id=" + id + "&action=removeProductFromBook";
            $.ajax({
                type: 'POST',
                url: 'phpScripts/managePrintBookCategoryView.php',
                data: dataString,
                dataType: 'json',
                success: function(response) {
                    loadItemByBrands(0);
                },
                error: function(xhr) {
                    alert("Error: " + xhr.responseText);
                }
            });
        }
    }
}
function restoreProduct(bookProductId) {
    if (confirm("Are you sure to restore!") == true) {
        var id = bookProductId;
        var dataString = "id=" + id + "&action=restoreProductFromBook";
        $.ajax({
            type: 'POST',
            url: 'phpScripts/managePrintBookCategoryView.php',
            data: dataString,
            dataType: 'json',
            success: function(response) {
                loadItemByBrands(0);
            },
            error: function(xhr) {
                alert("Error: " + xhr.responseText);
            }
        });
    }
}


/*------------------ End Remove Product Load ---------------------- */


/*------------------ Start select2 for PrintBook ---------------------- */

$("#add_categoryName").select2( {
	placeholder: "Select Category Name",
	dropdownParent: $("#addNewPrintBookCategory"),
	allowClear: true
} );
$("#add_printBookName").select2( {
	placeholder: "Select Catalogue Name",
	dropdownParent: $("#addNewPrintBookCategory"),
	allowClear: true
} );
$("#add_brandName").select2( {
	placeholder: "Select Brand Name",
	dropdownParent: $("#addNewPrintBookCategory"),
	allowClear: true
} );


$("#add_printBookNameView").select2({
        placeholder: "Select Catalogue Name",
        allowClear: true,
        width: '100%'
    })
    
/*------------------ end select2 for PrintBook ---------------------- */


/*----------------Start PrintBook Category Information save & validation parts----------------------*/
$(document).ready(function() {
    $('#form_addPrintBookCategory').bootstrapValidator({
        live: 'enabled',
        message: 'This value is not valid',
        submitButton: '$form_addPrintBookCategory button [type="Submit"]',
        submitHandler: function(validator, form, submitButton) {
            var addPageFooter = CKEDITOR.instances['addPageFooter'].getData(); // get CKEDITOR textarea value
            var addPrintBookName = $("#add_printBookName").val();
            var addCategoryName = $("#add_categoryName").val();
            var addBrandName = $("#add_brandName").val();
            var addType = $("#add_Type").val();
            var addViewType = $("#add_viewType").val();
            var addReportFooter = $("#add_reportFooter").val();
            var addBannerImage = $('#add_bannerImage')[0].files[0];
            var addBannerImage2 = $('#add_bannerImage2')[0].files[0];
            var list_offer = $('#list_offer').val();
            var productIdArray = [];
            $("#manageCartTable tr").each(function() {
                productIdArray.push(this.id);
            });

            var fd = new FormData();
            fd.append('addPrintBookId', addPrintBookName);
            fd.append('addCategoryId', addCategoryName);
            fd.append('addBrandId', addBrandName);
            fd.append('addType', addType);
            fd.append('addViewType', addViewType);
            fd.append('file', addBannerImage);
            fd.append('file2', addBannerImage2);
            fd.append('list_offer', list_offer);
            fd.append('addReportFooter', addReportFooter);
            fd.append('addPageFooter', addPageFooter);
            fd.append('productIdArray', productIdArray);
            fd.append('savePrintBookCategory', '1');
            $.ajax({
                type: 'POST',
                url: 'phpScripts/managePrintBookCategory-add.php',
                data: fd,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(response) {
                    if (response == 'Success') {
                        $('#addNewPrintBookCategory').modal('hide');
                        managePrintBookCategoryTable.ajax.reload(null, false);
                        $("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> Successfully Saved");
                        $("#divMsg").show().delay(2000).fadeOut().queue(function(n) {
                            $(this).hide();
                            n();
                        });
                        $("#form_addPrintBookCategory")[0].reset();
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
            add_printBookName: {
                validators: {
                    stringLength: {
                        min: 1,
                    },
                    notEmpty: {
                        message: 'Please Select Print Book Name'
                    }
                }
            },
            add_categoryName: {
                validators: {
                    stringLength: {
                        min: 1,
                    },
                    notEmpty: {
                        message: 'Please Select category Name'
                    }
                }
            },
            add_brandName: {
                validators: {
                    stringLength: {
                        min: 1,
                    },
                    notEmpty: {
                        message: 'Please Select Brand Name'
                    }
                }
            },
            addType: {
                validators: {
                    notEmpty: {
                        message: 'Please Select Type'
                    },
                }
            },
            viewType: {
                validators: {
                    notEmpty: {
                        message: 'Please Select Type'
                    },
                }
            },
            viewType12: {
                validators: {
                    notEmpty: {
                        message: 'Please Select View Type'
                    },
                }
            }
        }
    });
});
/*----------------End save & validation parts----------------------*/



/*----------------Start Edit Incormation---------------*/
var bannerImage = '';
var bannerImage2 = '';

function editPrintBookCategory(printBookCategoryId) {
    var dataString = "id=" + printBookCategoryId;
    $.ajax({
        type: 'POST',
        url: 'phpScripts/managePrintBookCategoryView.php',
        data: dataString,
        dataType: 'json',
        success: function(response) {
            $("#edit_PrintBookCategoryId").val(response.data.id);
            $("#edit_printBookName").val(response.data.tbl_printbookId);
            $("#edit_categoryName").val(response.data.tbl_categoryId);
            $("#edit_brandName").val(response.data.tbl_brandsId);
            $("#edit_Type").val(response.data.type).trigger('change');
            $("#edit_viewType").val(response.data.viewtype).trigger('change');
            bannerImage = response.data.banner;
            bannerImage2 = response.data.banner2;
            let imagePath = "images/categoryBanner/" + response.data.banner;
            let imagePath2 = "images/categoryBanner/" + response.data.banner2;
            $('#editBannerImageView').attr('src', imagePath);
            $('#editBannerImageView2').attr('src', imagePath2);
            $("#edit_reportFooter").val(response.data.report_footer);
            $("#editListOffer").val(response.data.list_offer);
            if(response.data.viewtype == "singleProducts"){
                $("#viewSingleSpec").show();
                CKEDITOR.instances['editApplicationSpecification'].setData(response.data.application_specification);
            }else{
                $("#viewSingleSpec").hide();
                CKEDITOR.instances['editApplicationSpecification'].setData("");
            }
            CKEDITOR.instances['editPageFooter'].setData(response.data.page_foooter);
            $("#manageEditCartTable").html(response.productData);
            $("#manageRemovedCartTable").html(response.removedProductData);
            $('#editPrintBookCategory').modal('show');
        },
        error: function(xhr) {
            alert(xhr.responseText);
        }
    });
}
/*----------------End Edit Transport Information Incormation---------------*/

$(document).ready(function() {
    $('#form_editPrintBookCategory').bootstrapValidator({
        live: 'enabled',
        message: 'This value is not valid',
        submitButton: '$form_editPrintBookCategory button [type="Submit"]',
        submitHandler: function(validator, form, submitButton) {
            var id = $("#edit_PrintBookCategoryId").val();
            // get CKEDITOR textarea value
            var editApplicationSpecification = CKEDITOR.instances['editApplicationSpecification'].getData();
            var editPageFooter = CKEDITOR.instances['editPageFooter'].getData();
            var editPrintBookName = $("#edit_printBookName").val();
            var editCategoryName = $("#edit_categoryName").val();
            var editBrandName = $("#edit_brandName").val();
            var editType = $("#edit_Type").val();
            var editViewType = $("#edit_viewType").val();
            var editListOffer = $("#editListOffer").val();
            var editReportFooter = $("#edit_reportFooter").val();
            var editBannerImage = $('#edit_bannerImage')[0].files[0];
            var editBannerImage2 = $('#edit_bannerImage2')[0].files[0];

            var productIdArray = [];
            $("#manageEditCartTable tr").each(function() {
                productIdArray.push(this.id);
            });

            var fd = new FormData();
            fd.append('id', id);
            fd.append('editPrintBookId', editPrintBookName);
            fd.append('editCategoryId', editCategoryName);
            fd.append('editBrandId', editBrandName);
            fd.append('editType', editType);
            fd.append('editViewType', editViewType);
            fd.append('editListOffer', editListOffer);
            fd.append('file', editBannerImage);
            fd.append('file2', editBannerImage2);
            fd.append('editReportFooter', editReportFooter);
            fd.append('editApplicationSpecification', editApplicationSpecification);
            fd.append('editPageFooter', editPageFooter);
            fd.append('oldBannerImage', bannerImage); // old bannerImage
            fd.append('productIdArray', productIdArray);
            fd.append('updatePrintBookCategory', '1');
            $.ajax({
                type: 'POST',
                url: 'phpScripts/managePrintBookCategory-add.php',
                data: fd,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(response) {
                    if (response == 'Success') {
                        $('#editPrintBookCategory').modal('hide');
                        $('button[type="submit"]').prop('disabled', false);
                        managePrintBookCategoryTable.ajax.reload(null, false);
                        $("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> Updated Successfully");
                        $("#divMsg").show().delay(2000).fadeOut().queue(function(n) {
                            $(this).hide();
                            n();
                        });
                    } else {
                        alert(JSON.stringify(response));
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
            userFullName: {
                validators: {
                    stringLength: {
                        min: 1,
                    },
                    notEmpty: {
                        message: 'Please Insert User Full Name'
                    },
                    regexp: {
                        regexp: /^([a-zA-Z0-9_ '\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ '\.\-\s\,\;\:\/\&\$\%\(\)]+$/,
                        message: 'Please insert alphanumeric value only'
                    }
                }
            },
            userName: {
                validators: {
                    stringLength: {
                        min: 1,
                    },
                    notEmpty: {
                        message: 'Please Insert User Name'
                    },
                    regexp: {
                        regexp: /^([a-zA-Z0-9_ '\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ '\.\-\s\,\;\:\/\&\$\%\(\)]+$/,
                        message: 'Please insert alphanumeric value only'
                    }
                }
            },
            userPhone: {
                validators: {
                    stringLength: {
                        min: 1,
                    },
                    notEmpty: {
                        message: 'Please Insert User Phone'
                    },
                    regexp: {
                        regexp: /^(?:\+?88)?01[15-9]\d{8}$/,
                        message: 'Please insert Phone Number only'
                    }
                }
            },
            userMail: {
                validators: {
                    stringLength: {
                        min: 1,
                    },
                    regexp: {
                        regexp: /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
                        message: 'Please insert valid Email only'
                    }
                }
            },
            userGender: {
                validators: {
                    notEmpty: {
                        message: 'Please Select User Gender'
                    },
                }
            },
            userType: {
                validators: {
                    notEmpty: {
                        message: 'Please Select User Type'
                    },
                }
            }


        }
    });
});

/*----------------End PrintBook Category Information Update & validation parts----------------------*/


 /*==================== Start Add Pdf List ====================*/
 var ID = 0;
 var tbl_printbookID = 0;
 const addPdf = (id, tbl_printbookId) => {
   ID = id;
   tbl_printbookID = tbl_printbookId;
   $('#addPdf').modal('show');

 }

 const savePdf = () => {

   var pdfName = $('#pdfName')[0].files[0];
   var fd = new FormData();
   fd.append('id', ID);
   fd.append('tbl_printbookID', tbl_printbookID);
   fd.append('file', pdfName);
   fd.append('action', 'savePdf');
   $.ajax({
     type: 'POST',
     url: 'phpScripts/managePrintBookCategoryPdfView.php',
     data: fd,
     contentType: false,
     processData: false,
     dataType: 'json',
     success: function(response) {
       alert(response);
       $('#addPdf').modal('hide');
     },
     error: function(xhr) {
       alert(xhr);
     }
   });

 }