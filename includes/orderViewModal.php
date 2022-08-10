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

tr:nth-child(even) {
  background-color: #dddddd;
}
</style>
<!-- Order View panel -->
<div class="modal fade" id="viewOrderFinal">
    <div class="modal-dialog">
        <div class="modal-content">
          	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              		<span aria-hidden="true">&times;</span></button>
            	<h4 class="modal-title"><b>Order ID # <span id="orderNo"></spna></b></h4>
            	<p class="modal-title">Bank Info # <span id="bankName"></span><p>
            	<b>A/C Number :</b> <span id="accountNo"></span> <b>A/C Name :</b> <span id="accountName"></span> <b>Branch :</b> <span id="branchName"></span><br>
            	
            	<b id="bkash_number"></b> <span id="bkash_amount"></span>
            	<div class="alert alert-success alert-dismissible" id="success" style="display:none;"></div>
          	</div>
          	<div class="modal-body">
            	<form class="form-horizontal" method="POST" action="company_delete.php">
            		<div class="text-center">
    	                <table>
                          <tr>
                            <th>Details</th>
                            <th>Amount</th>
                            <th>Received Amount</th>
                          </tr>
                          <tr>
                            <td>Order Grand Total</td>
                            <td id="grandTotal"></td>
                            <td></td>
                          </tr>
                          <tr>
                            <td>Paid Amount</td>
                            <td id="paidAmount"></td>
                            <td>
                                <?php
                                    /*$orID = $_POST['orderId'];
            						$sql = "SELECT id,received_amount FROM `tbl_orders` WHERE id='$orID'";
            						$query = $conn->query($sql);
            						while ($prow = $query->fetch_assoc()) {
            							$received_amount=$prow['received_amount'];
            						}
            						if($received_amount!=''){*/
                                ?>
                                 <span id="received_amount"></span>  
                                <?php /*}else{*/ ?>
                                <form name="form1" method="post">
                                    <div class="form-group" id='received_form'>
					                    <div class="col-sm-8">
                                        <input type="hidden"  id="orderId">
                                        <input type="text" name="recvAmount" id="recvAmount" class="form-control" palacholder="Received Amount">
                                        </div>
					                    <div class="col-sm-2">
                                        <input type="button" name="save" class="btn btn-primary" value="Save" id="butsave">
                                        </div>
                                    </div>
                                </form>
                                <?php /*}*/ ?>
                            </td>
                          </tr>
                          <tr>
                            <td>Due Amount</td>
                            <td id="dueAmount"></td>
                            <td id="rcvDue"></td>
                          </tr>
                          
                        </table>
	            	</div>
          	</div>
          	<div class="modal-footer">
            	<button type="button" class="btn btn-default btn-flat pull-right" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
            	</form>
          	</div>
        </div>
    </div>
</div>

<!-- Order View panel -->
<div class="modal fade" id="paymentBkash">
    <div class="modal-dialog">
        <div class="modal-content">
          	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              		<span aria-hidden="true">&times;</span></button>
            	<h4 class="modal-title"><b>Order ID # <span id="orderNo2"></spna></b></h4>
            	<p class="modal-title">Bank Info # <span id="bankName2"></span><p>
            	<b>A/C Number :</b> <span id="accountNo2"></span> <b>A/C Name :</b> <span id="accountName2"></span> <b>Branch :</b> <span id="branchName2"></span><br>
            	<span id="methodName2"></span><br>
            	<b id="bkash_number2"></b> <span id="bkash_amount2"></span>
            	<div class="alert alert-success alert-dismissible" id="successBkash" style="display:none;"></div>
          	</div>
          	<div class="modal-body">
          	    <div class="form-group">
              	    <div class="col-sm-6">
              	        Total Order Value : <span id='grandTotal2'></span>
              	    </div>
              	    <div class="col-sm-6">
              	        Paid By A/c : <span id='paidAmount2'></span><br>
              	        Received Amount : <span id='received_amount2'></span><br>
              	        Due Amount : <span id='rcvDue2'></span><br>
              	    </div>
              	</div>
            	<form class="form-horizontal" method="POST">
            		<div class="text-center">
    	                <form id="fupFormBkash" name="form1" method="post">
    	                    <input type="hidden"  id="oId2">
                            <div class="form-group">
			                    <div class="col-sm-4">
                                    <select class="form-control" id="bkashid" name="bkashid">
                                        <?php
                                        $sql = "SELECT id,methodName FROM `tbl_paymentMethod` WHERE id='2' AND status='Active'";
                                        $query = $conn->query($sql);
                                        while ($prow = $query->fetch_assoc()) {
                                            echo "<option value='" . $prow['methodName'] . "'>" . $prow['methodName'] ."</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-sm-5">
                                     <input type="text" class="form-control" name="bkashAmount" id="bkashAmount2" readonly>
                                </div>
			                    <div class="col-sm-3">
                                    <input type="button" name="save" class="btn btn-primary" value="Bkash Payment" id="butsaveBkash">
                                </div>
                            </div>
                        </form>
	            	</div>
          	</div>
          	<div class="modal-footer">
            	<button type="button" class="btn btn-default btn-flat pull-right" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
            	</form>
          	</div>
        </div>
    </div>
</div>