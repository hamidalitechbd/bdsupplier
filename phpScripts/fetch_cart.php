<?php
//fetch_cart.php
session_start();
$total_price = 0;
$total_item = 0;
$totalProductDiscount = 0;
$discountDisable = '';
$amountDisable = '';
$maxRowId = 1;
$output = '
<div class="table-responsive" id="order_table">
	<table class="table table-bordered table-striped">
		<tr style="background-color: #e1e1e1;font-size: 12px;">  
            <th style="width:25%;text-align: center;">Product Name</th>
            <th style="width:20%;text-align: center;">Warehouse</th>
            <th style="width:10%;text-align: center;">Quantity</th>  
            <th style="width:8%;text-align: center;">Available</th>
            <th style="width:17%;text-align: center;">Price</th>  
            <th style="width:10%;text-align: center;">Discount</th>  
            <th style="width:20%;text-align: center;">Total</th>  
            <th style="width:3%;text-align: center;">Action</th>  
        </tr>
';
if(!empty($_SESSION["shopping_cart"]))
{
	foreach($_SESSION["shopping_cart"] as $keys => $values)
	{
	    $productDiscount = 0;
	    if(strtolower($_SESSION['userType']) == 'super admin' || strtolower($_SESSION['userType']) == 'admin support' || strtolower($_SESSION['userType']) == 'admin support plus'){
            $discountDisable = '';
            $amountDisable = '';
        }else if(strtolower($_SESSION['userType']) == 'admin coordinator'){
            $discountDisable = 'Disabled';
            $amountDisable = '';
        }else{
            $discountDisable = 'Disabled';
            $amountDisable = '';
        }
        $maxRowId = $values["id"];
		$output .= '
    		<tr style="background-color: #e1e1e1;font-size: 12px;">
    			<td style="font-size: 12px;">'.$values["product_name"].'<input type="hidden" id="productId'.$values["id"].'" name="productId" value="'.$values["product_id"].'"/><input type="hidden" id="id'.$values["product_id"].'" name="id" value="'.$values["id"].'"/></td>
    			<td>'.$values["warehouse_name"].'<input type="hidden" id="warehouseId'.$values["id"].'" name="warehouse_id" value="'.$values["warehouse_id"].'"/><input type="hidden" id="warehouseName'.$values["id"].'" name="warehouse_name" value="'.$values["warehouse_name"].'"/></td>
    			<td><input type="text" id="productQuantity'.$values["id"].'" name="productQuantity" value="'.$values["product_quantity"].'" onkeyup="calculateTotal('.$values["id"].')" onblur="updateSession('.$values["id"].')" style="width: 100%;text-align: center;"/></td>
    			<td><input type="text" id="availableQuantity'.$values["id"].'" name="availableQuantity" value="'.$values["product_limit"].'" style="width: 100%;text-align: center;" Readonly/></td>;
                <td><input type="text" id="productQuantity'.$values["id"].'" name="productQuantity" value="'.$values["product_quantity"].'" onkeyup="calculateTotal('.$values["id"].')" onblur="updateSession('.$values["id"].',true)" style="width: 100%;text-align: center;"/>';
    		if ($values["product_type"] == "serialize") {
				$output .= '<a href="#" onclick="showSerializTable(' . $values["product_id"] . ', ' . $values["warehouse_id"] . ', ' . $values["product_quantity"] . ', ' . $values["id"] . ')"> <i class="fa fa-edit"> </i> </a>';
				$output .= '<input type="hidden" name="checkSerialize" value="' . $values["id"] . ',' . $values["warehouse_id"] . '" />';
			} else {
				$output .= '';
			}	
			$output .='</td>
    			<td align="right">
    			<td align="right">
    			    <input type="text" id="productPrice'.$values["id"].'" name="productPrice" value="'.$values["product_price"].'" onkeyup="calculateTotal('.$values["id"].')" onblur="updateSession('.$values["id"].')" style="width: 100%;text-align: center;" '.$amountDisable.'/>
    			    <input type="hidden" id="productMaxPrice'.$values["id"].'" name="productMaxPrice" value="'.$values["max_price"].'"/>
    			    <input type="hidden" id="productMinPrice'.$values["id"].'" name="productMinPrice" value="'.$values["min_price"].'"/>
    			</td>';
    	$productTotal = $values["product_quantity"] * $values["product_price"];
	    if($values["product_discount"] != ""){
		    $lastValue = substr($values["product_discount"], -1);
		    if($lastValue == "%"){
		        $productDiscount = $productTotal * (substr($values["product_discount"], 0, -1)/100);
		    }else{
		        $productDiscount = $values["product_discount"];
		    }
		    $productTotal = $productTotal - $productDiscount;
	    }		
    	$output .= '
    	<td align="right"><input type="text" id="productDiscount'.$values["id"].'" name="productDiscount" value="0" onkeyup="calculateTotal('.$values["id"].')" onblur="updateSession('.$values["id"].')" style="width: 100%;text-align: center;" '.$discountDisable.'/></td>
    			<td align="right"><span id="productTotal'.$values['id'].'"> '.sprintf("%.2f", $values["product_quantity"] * $values["product_price"]).'</span></td>
    			<td>
    			    <div class="btn-group">
                    	<button type="button" class="btn btn-deafult dropdown-toggle" data-toggle="dropdown"style="border: 1px solid gray;">
                    	<i class="glyphicon glyphicon-option-horizontal" style="color: #000cbd;"></i></button>
                    	<ul class="dropdown-menu dropdown-menu-right" style="border: 1px solid gray;min-width: 100%;" role="menu">
                    		<li style="margin-left: 0px;"><a class="btn btn-secondary btn-xs delete" id="'. $values["id"].'" href="#"><span class="glyphicon glyphicon-trash" style="color: red;"></span></a></li>
                    		<li style="margin-left: 0px;"><a class="btn btn-xs previousPriceSingle" id="-'. $values["product_id"].'" href="#"><span class="glyphicon glyphicon-th" style="color: #000cbd;"></span></a></li>
    			            <li style="margin-left: 0px;"><a class="btn btn-xs productSpecification" id="-'. $values["product_id"].'" href="#"><span class="glyphicon glyphicon-check" style="color: #000cbd;"></span></a></li>
                    	</ul>
                    </div>
    			</td>
    			
    		</tr>
    		';
		$total_price = $total_price + ($values["product_quantity"] * $values["product_price"]);
		$total_item = $total_item + 1;
		$totalProductDiscount = $totalProductDiscount + $productDiscount;
	}
	$output .= '
    	<tr>  
            <td colspan="6" align="right">Total
            <br>Product Discount</td>  
            <td align="right"><span class="totalAmount">'.sprintf("%.2f",$total_price).'</span>
            <br><span class="totalProductDiscount" style="width: 100%;">'.$totalProductDiscount.'</span></td>  
            <td></td>  
        </tr>
    	<tr>  
            <td colspan="6" align="right">Sales Discount
            <br>Total Discount</td>
            <td align="right"><input type="text" id="salesDiscount" style="width: 100%;text-align: right;" onkeyup="calculateTotalDiscount()" value="0"/>
            <br><span class="totalDiscount" style="width: 100%;">'.$totalProductDiscount.'</span></td>  
             <td></td>
        </tr>
        <tr>
            <td colspan="6" align="right">VAT</td>
            <td align="right"style="width: 100%;"><input type="text" id="vat" name="vat"  onkeyup="calculateTotalDiscount()" value="0" style="width:100%;text-align: right;" /></td>  
            <td></td>
        </tr>
        <tr>
            <td colspan="6" align="right">AIT</td>
            <td align="right"><input type="text" id="ait" name="ait" onkeyup="calculateTotalDiscount()" value="0" style="width:100%;text-align: right;" /></td>  
            <td></td>
        </tr>
        <tr>
            <td colspan="6" align="right">Grand Total</td>
            <td align="right"><span class="grandTotal" style="width: 100%;">'.sprintf("%.2f",$total_price).'</span></td>  
            <td></td>
        </tr>
        <tr>
            <td colspan="6" align="right">Payment Method</td>
            <td align="right"><select id="paymentMethod" name="paymentMethod" style="width: 100%;">
                <option value="Cash" selected>Cash</option>
            </select></td>  
            <td></td>
        </tr>
        <tr>
            <td colspan="6" align="right">Cash Amount</td>
            <td align="right"><input type="text" id="paid" name="paid" autocomplete="off" value="0" style="width:100%;text-align: right;"/>
            <input type="hidden" id="maxRowId" name="maxRowId" style="width:100%;text-align: right;" autocomplete="off" value="'.$maxRowId.'" Readonly/></td>  
            <td></td>
        </tr>
    	';
}
else
{
	$output .= '
    <tr>
    	<td colspan="5" align="center">
    		Your Cart is Empty!
    	</td>
    </tr>
    ';
}
$output .= '</table></div>';
$data = array(
	'cart_details'		=>	$output,
	'total_price'		=>	'$' . sprintf("%.2f",$total_price),
	'total_item'		=>	$total_item
);
echo json_encode($data);
?>