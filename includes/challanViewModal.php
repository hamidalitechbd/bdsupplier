<style>
table {
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

/*tr:nth-child(even) {
  background-color: #dddddd;
}*/
</style>
<!-- Order View panel -->
<div class="modal fade" id="challanViewModal">
    <div class="modal-dialog">
        <div class="modal-content">
          	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            	<h4 class="modal-title"><b>Sales Order No # <span id="orderNo"></span></b></h4>
            	<b>Party Info # </b><span id="partyName"></span> Phone # <span id="partyPhone"></span><br>
            	<b>Address :</b> <span id="partyAddress"></span>
          	</div>
          	<div class="modal-body">
            	<form class="form-horizontal" method="GET" action="htmlMultiChallanViewDetails.php" target="_blank">
            		<div class="text-center">
    	                <table id="tbl_challanChoice"></table>
                    </div>
          	</div>
          	<div class="modal-footer">
            	<button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
            	<button type="submit" id="createGroup" class="btn btn-primary" data-dismiss="modal"> Create Group Challan </button>
            	</form>
          	</div>
        </div>
    </div>
</div>