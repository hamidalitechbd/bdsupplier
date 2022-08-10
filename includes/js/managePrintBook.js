var manageUserTable;
$(document).ready(function() {
    // manage PrintBook table
    managePrintBookTable = $("#managePrintBookTable").DataTable({
        'ajax': 'phpScripts/managePrintBookView.php',
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


/*----------------Start PrintBook Information save & validation parts----------------------*/
$(document).ready(function() {
    $('#form_addPrintBook').bootstrapValidator({
        live: 'enabled',
        message: 'This value is not valid',
        submitButton: '$form_addPrintBook button [type="Submit"]',
        submitHandler: function(validator, form, submitButton) {
            var addPrintBookName = $("#add_printBookName").val();
            var addPrintBookDate = $("#add_printBookDate").val();
            var addPrintBookStatus = $("#add_printBookStatus").val();
            //var userPhoto = $('#add_userPhoto')[0].files[0];
            var fd = new FormData();
            fd.append('addPrintBookName', addPrintBookName);
            fd.append('addPrintBookDate', addPrintBookDate);
            fd.append('addPrintBookStatus', addPrintBookStatus);
            //fd.append('file', userPhoto);
            fd.append('savePrintBook', '1');
            $.ajax({
                type: 'POST',
                url: 'phpScripts/managePrintBook-add.php',
                data: fd,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(response) {
                    if (response == 'Success') {
                        $('#addNewBook').modal('hide');
                        managePrintBookTable.ajax.reload(null, false);

                        $("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> Successfully Saved");
                        $("#divMsg").show().delay(2000).fadeOut().queue(function(n) {
                            $(this).hide();
                            n();
                        });
                        $("#form_addPrintBook")[0].reset();
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
            printBookName: {
                validators: {
                    stringLength: {
                        min: 1,
                    },
                    notEmpty: {
                        message: 'Please Insert PrintBook Name'
                    },
                    // regexp: {
                    //     regexp: /^([a-zA-Z0-9_ '\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ '\.\-\s\,\;\:\/\&\$\%\(\)]+$/,
                    //     message: 'Please insert alphanumeric value only'
                    // }
                }
            },
        }
    });
});
/*----------------End save & validation parts----------------------*/

/*------------------ Start select2 for reset password panel ---------------------- */

$("#reset_userName").select2({
    placeholder: "Select user name",
    dropdownParent: $("#ChangePassword"),
    allowClear: true
});
/*------------------ end select2 for reset password panel ---------------------- */

/*----------------Start Edit Incormation---------------*/
function editPrintBook(printBookId) {
    $('#editPrintBook').modal('show');
    var dataString = "id=" + printBookId;
    $.ajax({
        type: 'POST',
        url: 'phpScripts/managePrintBookView.php',
        data: dataString,
        dataType: 'json',
        success: function(response) {
            $("#edit_printBookId").val(response.id);
            $("#edit_printBookName").val(response.book_name);
            $("#edit_printBookDate").val(response.book_date);
            $("#edit_printBookStatus").val(response.status).trigger('change');
        },
        error: function(xhr) {
            alert(xhr.responseText);
        }
    });
}
/*----------------End Edit PrintBook Information Incormation---------------*/

/*----------------Start PrintBook Information Update & validation parts----------------------*/
$(document).ready(function() {
    $('#form_updatePrintBook').bootstrapValidator({
        live: 'enabled',
        message: 'This value is not valid',
        submitButton: '$form_updatePrintBook button [type="Submit"]',
        submitHandler: function(validator, form, submitButton) {
            var id = $("#edit_printBookId").val();
            var editPrintBookName = $("#edit_printBookName").val();
            var editPrintBookDate = $("#edit_printBookDate").val();
            var editPrintBookStatus = $("#edit_printBookStatus").val();
            var fd = new FormData();
            fd.append('id', id);
            fd.append('editPrintBookName', editPrintBookName);
            fd.append('editPrintBookDate', editPrintBookDate);
            fd.append('editPrintBookStatus', editPrintBookStatus);
            fd.append('updatePrintBook', '1');
            $.ajax({
                type: 'POST',
                url: 'phpScripts/managePrintBook-add.php',
                data: fd,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(response) {
                    if (response == 'Success') {
                        $('#editPrintBook').modal('hide');
                        $('button[type="submit"]').prop('disabled', false);
                        managePrintBookTable.ajax.reload(null, false);
                        $("#divMsg").html("<strong><i class='icon fa fa-check'></i>Success ! </strong> Updated Successfully");
                        $("#divMsg").show().delay(2000).fadeOut().queue(function(n) {
                            $(this).hide();
                            n();
                        });
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
            editPrintBookName: {
                validators: {
                    stringLength: {
                        min: 1,
                    },
                    notEmpty: {
                        message: 'Please Insert PrintBook Name'
                    },
                    // regexp: {
                    //     regexp: /^([a-zA-Z0-9_ '\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ '\.\-\s\,\;\:\/\&\$\%\(\)]+$/,
                    //     message: 'Please insert alphanumeric value only'
                    // }
                }
            },
            editPrintBookDate: {
                validators: {
                    stringLength: {
                        min: 1,
                    },
                    notEmpty: {
                        message: 'Please Select PrintBook Date'
                    },
                    // regexp: {
                    //     regexp: /^(?:\+?88)?01[15-9]\d{8}$/,
                    //     message: 'Please insert Phone Number only'
                    // }
                }
            },
            editPrintBookStatus: {
                validators: {
                    notEmpty: {
                        message: 'Please Select PrintBook Status'
                    },
                }
            },
        }
    });
});
/*----------------End PrintBook Information Update & validation parts----------------------*/