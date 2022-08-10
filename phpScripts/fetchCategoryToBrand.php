<?php
$conPrefix = '../';
include $conPrefix . 'includes/session.php';
if(isset($_POST['action'])){
    if($_POST['action'] == "fetchCategoryToBrand"){
        $catId = $_POST['catId'];
        $sql = "SELECT tbl_category.id,tbl_products.tbl_brandsId,tbl_brands.brandName,tbl_products.categoryId,tbl_category.categoryName FROM `tbl_products` 
                LEFT JOIN tbl_category ON tbl_category.id=tbl_products.categoryId AND tbl_category.status='Active' AND tbl_category.deleted='no'
                LEFT JOIN tbl_brands ON tbl_brands.id=tbl_products.tbl_brandsId AND tbl_brands.deleted='No'
                WHERE tbl_products.categoryId='".$catId."' GROUP BY tbl_products.tbl_brandsId";
        $result = $conn->query($sql);
        
        while ($row = $result->fetch_array()) {
            $rows[] = $row;    
        }
        echo json_encode($rows);
    }
}
?>    