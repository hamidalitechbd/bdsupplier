<?php
$conPrefix = '../';
include $conPrefix . 'includes/session.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];
   
    $sql = "SELECT tbl_orders.id,tbl_orders.orderNo,tbl_orders.orderDate,tbl_orders.grandTotal,tbl_orders.paidAmount,sum(tbl_orders.received_amount+tbl_orders.bkash_amount) as received_amount,tbl_orders.bkash_number,tbl_orders.bkash_amount,sum(tbl_orders.paidAmount-tbl_orders.received_amount-tbl_orders.bkash_amount) as rcvDue,sum(tbl_orders.total_after_discount-tbl_orders.paidAmount) AS dueAmount,tbl_orders.tbl_bank_id,tbl_orders.bank_reference,tbl_orders.tbl_paymentMethod_id,tbl_paymentMethod.methodName,
            tbl_bank_account_info.accountNo,tbl_bank_account_info.accountName,tbl_bank_account_info.bankName,tbl_bank_account_info.branchName, tbl_orders.total_after_discount
        FROM `tbl_orders` 
        LEFT JOIN tbl_bank_account_info ON tbl_bank_account_info.id=tbl_orders.tbl_bank_id
        LEFT JOIN tbl_paymentMethod ON tbl_paymentMethod.id=tbl_orders.tbl_paymentMethod_id
        WHERE tbl_orders.status='Processing' and tbl_orders.id='".$id."'";
    
	if ($conn->query($sql)) {
        $_SESSION['success'] = $id . ' Updated successfully';
    } else {
        $_SESSION['error'] = $conn->error;
    }
    
    $query = $conn->query($sql);
    $row = $query->fetch_assoc();

    echo json_encode($row);
}
?>