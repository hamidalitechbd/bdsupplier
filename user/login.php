<?php
session_start();
include '../includes/conn.php';
$toDate = (new DateTime())->format("Y-m-d");

if (isset($_POST['login'])) {
        $captchaResult = $_POST["captchaResult"];
		$firstNumber = $_POST["firstNumber"];
		$secondNumber = $_POST["secondNumber"];
		$checkTotal = $firstNumber + $secondNumber;
		
    if ($captchaResult == $checkTotal) { 
        $username = $_POST['username'];
        $password = $_POST['password'];
    
        $sql = "SELECT tbl_users.id, tbl_users.fname, tbl_users.username, tbl_accountType.accountType, tbl_users.password 
                FROM 
                tbl_users INNER JOIN tbl_accountType ON tbl_accountType.id=tbl_users.tbl_accountTypeId 
                WHERE tbl_users.username = '$username' 
                AND tbl_users.accountStatus= 'approved'
                AND tbl_users.deleted='No'
                AND tbl_accountType.deleted='No'";
        $query = $conn->query($sql);
    
        if ($query->num_rows < 1) {
            $_SESSION['error'] = 'Cannot find account with the username';
        } else {
            $row = $query->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['user'] = $row['id'];
                $_SESSION['userType'] = $row['accountType'];
                
                //Check Offer exists or not
                /*$sql = "SELECT tbl_products_id 
                        FROM tbl_discount_offer
                        WHERE deleted = 'No'";*/
                $sql = "SELECT DISTINCT dbt.tbl_products_id
                        FROM(SELECT id AS tbl_products_id
                        FROM tbl_products
                        WHERE isdiscount > 0 AND deleted='No'
                        UNION
                        SELECT tbl_products_id 
                        FROM tbl_discount_offer) AS dbt";
                $result_offer = $conn->query($sql);
                while ($row_product = $result_offer->fetch_array()) {
                    $products = $row_product['tbl_products_id'];
                    $sql = "SELECT * FROM(
                                SELECT offer_applicable, 'Off' AS status, id
                                                    FROM tbl_discount_offer
                                                    WHERE tbl_discount_offer.deleted = 'No' AND tbl_products_id = '$products' AND ('$toDate' < tbl_discount_offer.date_from OR tbl_discount_offer.date_to < '$toDate')
                                                    UNION
                                SELECT offer_applicable, 'On' AS status, id
                                                    FROM tbl_discount_offer AS tbl_discount_offer1
                                                    WHERE tbl_discount_offer1.deleted = 'No' AND tbl_products_id = '$products' AND '$toDate' BETWEEN tbl_discount_offer1.date_from AND tbl_discount_offer1.date_to) as dbt
                                                    ORDER BY dbt.status";
                    $result = $conn->query($sql);
                    $isdiscount_wi = '0';
                    $isdiscount_party = '0';
                    $isdiscount_ts = '0';
                    while ($row = $result->fetch_array()) {
                        if($row['offer_applicable'] == 'wiCustomer'){
                            if($row['status'] == "Off"){
                                $isdiscount_wi = '0';
                            }else if($row['status'] == "On"){
                                $isdiscount_wi = '1';
                            }
                        }else if($row['offer_applicable'] == 'Party'){
                            if($row['status'] == "Off"){
                                $isdiscount_party = '0';
                            }else if($row['status'] == "On"){
                                $isdiscount_party = '1';
                            }
                            
                        }else if($row['offer_applicable'] == 'TS'){
                            if($row['status'] == "Off"){
                                $isdiscount_ts = '0';
                            }else if($row['status'] == "On"){
                                $isdiscount_ts = '1';
                            }
                        }
                    }
                    $isdiscount = $isdiscount_wi.$isdiscount_party.$isdiscount_ts;
                    $sql = "UPDATE tbl_products
                            SET isdiscount = '$isdiscount'
                            WHERE id='$products' AND deleted='No'";
                    $conn->query($sql);
                }
            } else {
                $_SESSION['error'] = 'Incorrect password';
            }
        }
    }else{// Captcha verification is Correct. Final Code Execute here!	
	$_SESSION['error'] = 'Captcha verification is incorrect';
    }
} else {
    $_SESSION['error'] = 'Input User credentials first';
}

header('location: ../index.php');
?>