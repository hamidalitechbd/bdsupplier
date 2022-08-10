var manageTable;
$(document).ready(function() {
	// manage Shop table
	manageTable = $("#manageFeatureTable").DataTable({
		'ajax': 'phpScripts/manageFeatureView.php',
		'order': [],
		'dom': 'Bfrtip',
        'buttons': [
            'pageLength','copy', 'csv', 'pdf', 'print'
        ]
	});
});
//Edit Unit
function editUnit(unitId,type){
    $('#editUnit').modal('show');
    var dataString = "id="+unitId+"&type="+type;
    $.ajax({
        type: 'POST',
        url: 'phpScripts/manage-row.php',
        data: dataString,
        dataType: 'json',
        success: function(response){
          $('#Uid').val(response.id);
          $('#edit_UnitName').val(response.unitName);
          $('#edit_UnitunitType').val(response.unitType);
          $('#edit_UnitDescription').val(response.unitDesc);
          $('#edit_Unitstatus').val(response.status);
        }
        ,error: function (xhr) {
            alert(xhr.responseText);
        }
    });
}
//Save Unit
$("#form_addUnit").submit(function(event) {
  event.preventDefault();
  var unitName = $("#add_unitName").val();
  var unitDescription = $("#add_unitDescription").val();
  var type=$("#add_type").val();
  var dataString = "type="+type+"&UnitName="+unitName+"&UnitDescription="+unitDescription+"&addUnit=1";
  $.ajax({
        type: 'POST',
        url: 'phpScripts/manage-add.php',
        data: dataString,
        dataType: 'json',
        success: function(response){
          manageShopTable.ajax.reload(null, false);
          $("#add_unitName").val('');
          $("#add_unitDescription").val('');
        },error: function (xhr) {
            alert(xhr.responseText);
        }
      });
  
  $('#addnew').modal('hide');
});
//Update Unit
$("#form_editUnit").submit(function(event) {
  event.preventDefault();
  var unitName = $("#edit_UnitName").val();
  var unitDescription = $("#edit_UnitDescription").val();
  var unitStatus = $("#edit_Unitstatus").val();
  var type=$("#edit_type").val();
  var id = $("#Uid").val();
  var dataString = "id="+id+"&type="+type+"&UnitName="+unitName+"&UnitDescription="+unitDescription+"&Ustatus="+unitStatus+"&editUnit=1";
  $.ajax({
        type: 'POST',
        url: 'phpScripts/manage-add.php',
        data: dataString,
        dataType: 'json',
        success: function(response){
          manageShopTable.ajax.reload(null, false);
        },error: function (xhr) {
            alert(xhr.responseText);
        }
      });
  
  $('#editUnit').modal('hide');
});