<?php
$conPrefix = '../';
include $conPrefix . 'includes/session.php';
if(isset($_POST['action'])){
    if($_POST['action'] == "fetchCategory"){
        $brandId = $_POST['brandId'];
        $sql = "SELECT tbl_category.id,tbl_products.tbl_brandsId,tbl_products.categoryId,tbl_category.categoryName FROM `tbl_products` 
                LEFT JOIN tbl_category ON tbl_category.id=tbl_products.categoryId AND tbl_category.status='Active' AND tbl_category.deleted='no'
                WHERE tbl_brandsId='".$brandId."' GROUP by tbl_products.categoryId";
        $result = $conn->query($sql);
        
        while ($row = $result->fetch_array()) {
            $rows[] = $row;    
        }
        echo json_encode($rows);
    }
}
?>    