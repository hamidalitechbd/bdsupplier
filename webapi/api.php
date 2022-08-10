<?php
header("Content-Type:application/json");
if(isset($_GET['passcode']) || isset($_POST['passcode'])){
    if (isset($_GET['page'])) {
        $page = $_GET['page'];
        $passCode = $_GET['passcode'];
        if($passCode == "JafreeApi22"){
            if($page == 'getCategory'){
            	include('../includes/conn.php');
            	$sql = "SELECT tbl_category.id, tbl_category.categoryName
                                                FROM tbl_category
                                                WHERE tbl_category.deleted='No' AND tbl_category.id IN (SELECT DISTINCT tbl_category_id
                                                FROM tbl_printbook_category
                                                WHERE tbl_printbook_category.is_website='Yes' AND tbl_printbook_category.deleted='No')
                                                 ORDER BY tbl_category.categoryName ASC";
            	$query = $conn->query($sql);
            	if(mysqli_num_rows($query)>0){
            	    $response['statusCode'] = 0;
            	    $response['message'] ="Success"; 
                	while($row =  $query->fetch_assoc())
                	{
                		$rows[]= $row;
                	}
            	    mysqli_close($conn);
            	    $response['data'] =$rows;
                    
            	}
            	else{
            	    $response['statusCode'] = 200;
            	    $response['message'] ="No record found"; 
            	    //echo json_encode($response);
            	}
            }else if($page == 'getAllBrands'){
            	include('../includes/conn.php');
            	$agent = $_GET['agent'];
            	if($agent == 'Yes'){
                	$sql = "SELECT DISTINCT tbl_brands.id, tbl_brands.brandName, tbl_brands.brand_logo, tbl_brands.is_agent
                                FROM tbl_brands 
                                WHERE tbl_brands.deleted='No' AND  tbl_brands.is_agent = 'Yes'";
            	}else if($agent == 'No'){
                	$sql = "SELECT DISTINCT tbl_brands.id, tbl_brands.brandName, tbl_brands.brand_logo, tbl_brands.is_agent
                                FROM tbl_printbook_category
                                iNNER JOIN tbl_brands ON tbl_printbook_category.tbl_brand_id = tbl_brands.id
                                WHERE tbl_printbook_category.is_website='Yes' AND tbl_printbook_category.deleted='No' AND tbl_brands.is_agent = 'No'";
            	}else{
            	    $sql = "SELECT DISTINCT tbl_brands.id, tbl_brands.brandName, tbl_brands.brand_logo, tbl_brands.is_agent
                                FROM tbl_printbook_category
                                iNNER JOIN tbl_brands ON tbl_printbook_category.tbl_brand_id = tbl_brands.id
                                WHERE tbl_printbook_category.is_website='Yes' AND tbl_printbook_category.deleted='No'";
            	}
            	$query = $conn->query($sql);
            	if(mysqli_num_rows($query)>0){
            	    $response['statusCode'] = 0;
            	    $response['message'] ="Success"; 
                	while($row =  $query->fetch_assoc())
                	{
                	    if($row['brand_logo'] == ''){
            	            $row['brand_logo'] = 'https://jafree.alitechbd.com/images/brand/big_brand_img/broken_image.png';
            	        }else{
            	            $row['brand_logo'] = 'https://jafree.alitechbd.com/images/brand/big_brand_img/'.$row['brand_logo'];
            	        }
                		$rows[]= $row;
                	}
            	    mysqli_close($conn);
            	    $response['data'] =$rows;
            	}
            	else{
            	    $response['statusCode'] = 200;
            	    $response['message'] ="No record found"; 
            	    //echo json_encode($response);
            	}
            }
            
            else if($page == 'getCategorywithBrands'){
                include('../includes/conn.php');
                $sql = "SELECT tbl_category.id, tbl_category.categoryName
                        FROM tbl_category
                        WHERE tbl_category.deleted='No' AND tbl_category.id IN (SELECT DISTINCT tbl_category_id
                                                                                FROM tbl_printbook_category
                                                                                WHERE tbl_printbook_category.is_website='Yes' AND tbl_printbook_category.deleted='No')
                        ORDER BY tbl_category.categoryName ASC";
                $query = $conn->query($sql);
            	if(mysqli_num_rows($query)>0){
            	    $response['statusCode'] = 0;
            	    $response['message'] ="Success"; 
                	while($row =  $query->fetch_assoc())
                	{
                	    
                	    $categoryId = $row['id'];
                	    $sql_brands = "SELECT tbl_brands.id, tbl_brands.brandName,  tbl_brands.brand_logo
                                        FROM tbl_brands
                                        WHERE tbl_brands.deleted='No' AND tbl_brands.id IN (SELECT DISTINCT tbl_printbook_category.tbl_brand_id
                                                                                            FROM tbl_printbook_category
                                                                                            WHERE tbl_printbook_category.is_website='Yes' AND tbl_printbook_category.deleted='No' AND tbl_printbook_category.tbl_category_id = '$categoryId')
                                        ORDER BY tbl_brands.brandName ASC";
                        $query_brand = $conn->query($sql_brands);
                        while($row_brand =  $query_brand->fetch_assoc())
                	    {
                	        if($row_brand['brand_logo'] == ''){
                	            $row_brand['brand_logo'] = 'https://jafree.alitechbd.com/images/brand/thumb/broken_image.png';
                	        }else{
                	            $row_brand['brand_logo'] = 'https://jafree.alitechbd.com/images/brand/thumb/'.$row_brand['brand_logo'];
                	        }
                	       $row['brand'][] = $row_brand;
                	    }
                	    $category[] = $row;
                	}
                	$response['data'] = $category;
                	mysqli_close($conn);
            	}else{
            	    $response['statusCode'] = 200;
            	    $response['message'] ="No record found"; 
            	}
            }else if($page == 'getCategorywiseBrands'){
                include('../includes/conn.php');
                /*$sql = "SELECT tbl_category.id, tbl_category.categoryName
                        FROM tbl_category
                        WHERE tbl_category.deleted='No' AND tbl_category.id IN (SELECT DISTINCT tbl_category_id
                                                                                FROM tbl_printbook_category
                                                                                WHERE tbl_printbook_category.is_website='Yes' AND tbl_printbook_category.deleted='No')
                        ORDER BY tbl_category.categoryName ASC";
                $query = $conn->query($sql);*/
            	/*if(mysqli_num_rows($query)>0){
            	    $response['statusCode'] = 0;
            	    $response['message'] ="Success"; 
                	while($row =  $query->fetch_assoc())
                	{*/
                	    
                	    $categoryId = $_GET['id'];
                	    $sql_brands = "SELECT tbl_brands.id, tbl_brands.brandName, tbl_brands.brand_logo
                                        FROM tbl_brands
                                        WHERE tbl_brands.deleted='No' AND tbl_brands.id IN (SELECT DISTINCT tbl_printbook_category.tbl_brand_id
                                                                                            FROM tbl_printbook_category
                                                                                            WHERE tbl_printbook_category.is_website='Yes' AND tbl_printbook_category.deleted='No' AND tbl_printbook_category.tbl_category_id = '$categoryId')
                                        ORDER BY tbl_brands.brandName ASC";
                        $query_brand = $conn->query($sql_brands);
                        if(mysqli_num_rows($query_brand)>0){
                            $response['statusCode'] = 0;
            	            $response['message'] ="Success"; 
                            while($row_brand =  $query_brand->fetch_assoc())
                    	    {
                    	        if($row_brand['brand_logo'] == ''){
                    	            $row_brand['brand_logo'] = 'https://jafree.alitechbd.com/images/brand/thumb/broken_image.png';
                    	        }else{
                    	            $row_brand['brand_logo'] = 'https://jafree.alitechbd.com/images/brand/thumb/'.$row_brand['brand_logo'];
                    	        }
                    	       $rows[] = $row_brand;
                    	    }
                	        //$category[] = $row;
                        }else{
                    	    $response['statusCode'] = 200;
                    	    $response['message'] ="No record found"; 
                    	}
                	//}
                	$response['data'] = $rows;
                	mysqli_close($conn);
            	/*}else{
            	    $response['statusCode'] = 200;
            	    $response['message'] ="No record found"; 
            	}*/
            }
            else if($page == 'printBookView'){
                include('../includes/conn.php');
                if(isset($_GET['website'])){
                    $website = $_GET['website'];
                    $sql = "SELECT tbl_printbook_category.id, tbl_printbook.book_name, tbl_printbook_category.viewtype, tbl_brands.brandName, tbl_category.categoryName, tbl_printbook_category.is_website
                            FROM tbl_printbook 
                            INNER JOIN tbl_printbook_category ON tbl_printbook.id = tbl_printbook_category.tbl_printbook_id
                            INNER JOIN tbl_category ON tbl_printbook_category.tbl_category_id = tbl_category.id
                            INNER JOIN tbl_brands ON tbl_printbook_category.tbl_brand_id = tbl_brands.id
                            WHERE tbl_printbook.deleted = 'No' AND tbl_printbook.status='Active' AND tbl_printbook_category.deleted='No' AND tbl_printbook_category.status='Active' AND tbl_printbook_category.is_website='$website'
                            ORDER BY id DESC";
                }else{
                    
                    $sql = "SELECT tbl_printbook_category.id, tbl_printbook.book_name, tbl_printbook_category.viewtype, tbl_brands.brandName, tbl_category.categoryName, tbl_printbook_category.is_website
                            FROM tbl_printbook 
                            INNER JOIN tbl_printbook_category ON tbl_printbook.id = tbl_printbook_category.tbl_printbook_id
                            INNER JOIN tbl_category ON tbl_printbook_category.tbl_category_id = tbl_category.id
                            INNER JOIN tbl_brands ON tbl_printbook_category.tbl_brand_id = tbl_brands.id
                            WHERE tbl_printbook.deleted = 'No' AND tbl_printbook.status='Active' AND tbl_printbook_category.deleted='No' AND tbl_printbook_category.status='Active'
                            ORDER BY id DESC";
                }
                $query = $conn->query($sql);
            	if(mysqli_num_rows($query)>0){
            	    $response['statusCode'] = 0;
            	    $response['message'] ="Success"; 
                	while($row =  $query->fetch_assoc())
                	{
                	    $rows[] = $row;
                	}
                	$response['data'] =$rows;
                	mysqli_close($conn);
            	}else{
            	    $response['statusCode'] = 200;
            	    $response['message'] ="No record found"; 
            	}
            }
            else if($page == 'cateloguePriceListViewPDF'){
                include('../includes/conn.php');
                if(isset($_GET['website'])){
                    $website = $_GET['website'];
                    $sql = "SELECT tbl_pdf.id, tbl_pdf.pdf_link, tbl_pdf.status, tbl_pdf.is_website, tbl_printbook.book_name, tbl_brands.brandName, tbl_category.categoryName
                            FROM `tbl_pdf` 
                            INNER JOIN tbl_printbook_category ON tbl_pdf.tbl_printbook_category_id = tbl_printbook_category.id
                            INNER JOIN tbl_printbook ON tbl_pdf.tbl_printbook_id = tbl_printbook.id
                            INNER JOIN tbl_category ON tbl_printbook_category.tbl_category_id = tbl_category.id
                            INNER JOIN tbl_brands ON tbl_printbook_category.tbl_brand_id = tbl_brands.id
                            WHERE tbl_pdf.status='Active' AND tbl_printbook_category.deleted='No' AND tbl_printbook.deleted='No' AND tbl_pdf.pdf_link <> '' AND tbl_pdf.is_website='$website' AND tbl_pdf.deleted='No'";
                }else{
                    
                    $sql = "SELECT tbl_pdf.id, tbl_pdf.pdf_link, tbl_pdf.status, tbl_pdf.is_website, tbl_printbook.book_name, tbl_brands.brandName, tbl_category.categoryName
                            FROM `tbl_pdf` 
                            INNER JOIN tbl_printbook_category ON tbl_pdf.tbl_printbook_category_id = tbl_printbook_category.id
                            INNER JOIN tbl_printbook ON tbl_pdf.tbl_printbook_id = tbl_printbook.id
                            INNER JOIN tbl_category ON tbl_printbook_category.tbl_category_id = tbl_category.id
                            INNER JOIN tbl_brands ON tbl_printbook_category.tbl_brand_id = tbl_brands.id
                            WHERE tbl_pdf.status='Active' AND tbl_printbook_category.deleted='No' AND tbl_printbook.deleted='No' AND tbl_pdf.pdf_link <> '' AND tbl_pdf.deleted='No'";
                }
                $query = $conn->query($sql);
            	if(mysqli_num_rows($query)>0){
            	    $response['statusCode'] = 0;
            	    $response['message'] ="Success"; 
                	while($row =  $query->fetch_assoc())
                	{
                	    $row['pdf_link'] = 'https://jafree.alitechbd.com/images/pdf/'.$row['pdf_link'];
                	    $rows[] = $row;
                	}
                	$response['data'] =$rows;
                	mysqli_close($conn);
            	}else{
            	    $response['statusCode'] = 200;
            	    $response['message'] ="No record found"; 
            	}
            }
            else if($page == 'featuredProducts'){
                include('../includes/conn.php');
                if(isset($_GET['featured'])){
                    $featured = $_GET['featured'];
                    $sql = "SELECT DISTINCT tbl_products.*, is_featured, tbl_print_book_product.id as book_product_id, tbl_brands.brandName, tbl_brands.brand_logo, tbl_category.categoryName
                            FROM tbl_print_book_product
                            INNER JOIN tbl_printbook_category on tbl_print_book_product.tbl_print_book_category_id=tbl_printbook_category.id
                            INNER JOIN tbl_printbook ON tbl_printbook_category.tbl_printbook_id = tbl_printbook.id
                            INNER JOIN tbl_brands ON tbl_printbook_category.tbl_brand_id = tbl_brands.id
                            INNER JOIN tbl_category ON tbl_printbook_category.tbl_category_id = tbl_category.id
                            INNER JOIN tbl_products ON tbl_print_book_product.tbl_product_id = tbl_products.id
                            WHERE tbl_products.deleted='No' AND tbl_print_book_product.status='Active' AND tbl_print_book_product.deleted='No' AND tbl_printbook_category.is_website='Yes' AND tbl_printbook_category.status='Active' AND tbl_printbook_category.deleted='No' AND tbl_printbook.status='Active' AND tbl_printbook.deleted='No' AND is_featured='$featured'";
                    /*$sql = "SELECT DIStINCT tbl_products.*,is_featured, tbl_print_book_product.id as book_product_id, tbl_brands.brandName, tbl_brands.brand_logo, tbl_category.categoryName
                            FROM tbl_products
                            INNER JOIN tbl_print_book_product on tbl_print_book_product.tbl_product_id=tbl_products.id
                            INNER JOIN tbl_brands ON tbl_products.tbl_brandsId = tbl_brands.id
                            INNER JOIN tbl_category ON tbl_products.categoryId = tbl_category.id
                            INNER JOIN tbl_printbook_category ON tbl_print_book_product.tbl_print_book_category_id = tbl_printbook_category.id
                            WHERE tbl_products.deleted='No' AND tbl_print_book_product.status='Active' AND tbl_print_book_product.deleted='No' AND is_featured='$featured' AND tbl_printbook_category.is_website='Yes'";*/
                }else{
                    /*$sql = "SELECT DIStINCT tbl_products.*,is_featured, tbl_print_book_product.id as book_product_id, tbl_brands.brandName, tbl_brands.brand_logo, tbl_category.categoryName, tbl_printbook_category.*
                            FROM tbl_products
                            INNER JOIN tbl_print_book_product on tbl_print_book_product.tbl_product_id=tbl_products.id
                            INNER JOIN tbl_brands ON tbl_products.tbl_brandsId = tbl_brands.id
                            INNER JOIN tbl_category ON tbl_products.categoryId = tbl_category.id
                            INNER JOIN tbl_printbook_category ON tbl_print_book_product.tbl_print_book_category_id = tbl_printbook_category.id
                            WHERE tbl_products.deleted='No' AND tbl_print_book_product.status='Active' AND tbl_print_book_product.deleted='No' AND tbl_printbook_category.is_website='Yes'";*/
                    $sql = "SELECT DISTINCT tbl_products.*, is_featured, tbl_print_book_product.id as book_product_id, tbl_brands.brandName, tbl_brands.brand_logo, tbl_category.categoryName
                            FROM tbl_print_book_product
                            INNER JOIN tbl_printbook_category on tbl_print_book_product.tbl_print_book_category_id=tbl_printbook_category.id
                            INNER JOIN tbl_printbook ON tbl_printbook_category.tbl_printbook_id = tbl_printbook.id
                            INNER JOIN tbl_brands ON tbl_printbook_category.tbl_brand_id = tbl_brands.id
                            INNER JOIN tbl_category ON tbl_printbook_category.tbl_category_id = tbl_category.id
                            INNER JOIN tbl_products ON tbl_print_book_product.tbl_product_id = tbl_products.id
                            WHERE tbl_products.deleted='No' AND tbl_print_book_product.status='Active' AND tbl_print_book_product.deleted='No' AND tbl_printbook_category.is_website='Yes' AND tbl_printbook_category.status='Active' AND tbl_printbook_category.deleted='No' AND tbl_printbook.status='Active' AND tbl_printbook.deleted='No'";
                }
                $query = $conn->query($sql);
            	if(mysqli_num_rows($query)>0){
            	    $response['statusCode'] = 0;
            	    $response['message'] ="Success"; 
                	while($row =  $query->fetch_assoc())
                	{
                	    $productId = $row['id'];
                	    if($row['productImage'] == ''){
            	            $row['productImage'] = 'https://jafree.alitechbd.com/images/broken_image.png';
            	        }else{
            	            $row['productImage'] = 'https://jafree.alitechbd.com/images/products/big_product_img/'.$row['productImage'];
            	        }
                	    $sql_productSpec = "SELECT * FROM `tbl_print_book_spec_display` WHERE tbl_product_id='$productId' AND deleted='No' AND status='Active' ORDER BY id";
                        $query_productSpec = $conn->query($sql_productSpec);
                        while($row_productSpec =  $query_productSpec->fetch_assoc())
                	    {
                	       $row['spec'][] = $row_productSpec;
                	    }
                	    //$product[] = $row;
                	    $rows[] = $row;
                	}
                	$response['data'] =$rows;
                	mysqli_close($conn);
            	}else{
            	    $response['statusCode'] = 200;
            	    $response['message'] ="No record found"; 
            	}
            }else if($page == 'brandCategorywiseProducts'){
                include('../includes/conn.php');
                $brandId = $_GET['brandId'];
                $categoryId = $_GET['categoryId'];
                    $sql = "SELECT tbl_products.id,tbl_products.productCode,tbl_products.productName,tbl_products.productImage,tbl_products.modelNo,tbl_products.productDescriptions, tbl_brands.brandName, tbl_brands.brand_logo, tbl_category.categoryName
                            FROM tbl_products
                            INNER JOIN tbl_brands ON tbl_products.tbl_brandsId = tbl_brands.id
                            INNER JOIN tbl_category ON tbl_products.categoryId = tbl_category.id
                            WHERE tbl_products.id in (SELECT DISTINCT tbl_product_id 
                                                      FROM `tbl_print_book_product` 
                                                      INNER JOIN tbl_printbook_category ON tbl_print_book_product.tbl_print_book_category_id = tbl_printbook_category.id
                                                      WHERE tbl_printbook_category.is_website='Yes' AND tbl_printbook_category.tbl_category_id='$categoryId' AND tbl_printbook_category.tbl_brand_id='$brandId') AND tbl_products.deleted='No' AND tbl_products.status='Active'";
                
                $query = $conn->query($sql);
            	if(mysqli_num_rows($query)>0){
            	    $response['statusCode'] = 0;
            	    $response['message'] ="Success"; 
                	while($row =  $query->fetch_assoc())
                	{
                	    if($row['productImage'] == ''){
            	            $row['productImage'] = 'https://jafree.alitechbd.com/images/broken_image.png';
            	        }else{
            	            $row['productImage'] = 'https://jafree.alitechbd.com/images/products/thumb/'.$row['productImage'];
            	        }
                	    $productId = $row['id'];
                	    $sql_productSpec = "SELECT * FROM `tbl_print_book_spec_display` WHERE tbl_product_id=588 AND deleted='No' AND status='Active' ORDER BY id";
                        $query_productSpec = $conn->query($sql_productSpec);
                        while($row_productSpec =  $query_productSpec->fetch_assoc())
                	    {
                	       $row['spec'][] = $row_productSpec;
                	    }
                	    //$product[] = $row;
                	    $rows[] = $row;
                	}
                	$response['data'] =$rows;
                	mysqli_close($conn);
            	}else{
            	    $response['statusCode'] = 200;
            	    $response['message'] ="No record found"; 
            	}
            }
            else if($page == 'brandwiseProducts'){
                include('../includes/conn.php');
                $brandId = $_GET['brandId'];
                    $sql = "SELECT tbl_products.id,tbl_products.productCode,tbl_products.productName,tbl_products.productImage,tbl_products.modelNo,tbl_products.productDescriptions, tbl_brands.brandName, tbl_brands.brand_logo, tbl_category.categoryName
                            FROM tbl_products
                            INNER JOIN tbl_brands ON tbl_products.tbl_brandsId = tbl_brands.id
                            INNER JOIN tbl_category ON tbl_products.categoryId = tbl_category.id
                            WHERE tbl_products.id in (SELECT DISTINCT tbl_product_id 
                                                      FROM `tbl_print_book_product` 
                                                      INNER JOIN tbl_printbook_category ON tbl_print_book_product.tbl_print_book_category_id = tbl_printbook_category.id
                                                      WHERE tbl_printbook_category.is_website='Yes' AND tbl_printbook_category.tbl_brand_id='$brandId') AND tbl_products.deleted='No' AND tbl_products.status='Active'";
                
                $query = $conn->query($sql);
            	if(mysqli_num_rows($query)>0){
            	    $response['statusCode'] = 0;
            	    $response['message'] ="Success"; 
                	while($row =  $query->fetch_assoc())
                	{
                	    if($row['productImage'] == ''){
            	            $row['productImage'] = 'https://jafree.alitechbd.com/images/broken_image.png';
            	        }else{
            	            $row['productImage'] = 'https://jafree.alitechbd.com/images/products/thumb/'.$row['productImage'];
            	        }
                	    $productId = $row['id'];
                	    $sql_productSpec = "SELECT * FROM `tbl_print_book_spec_display` WHERE tbl_product_id=588 AND deleted='No' AND status='Active' ORDER BY id";
                        $query_productSpec = $conn->query($sql_productSpec);
                        while($row_productSpec =  $query_productSpec->fetch_assoc())
                	    {
                	       $row['spec'][] = $row_productSpec;
                	    }
                	    //$product[] = $row;
                	    $rows[] = $row;
                	}
                	$response['data'] =$rows;
                	mysqli_close($conn);
            	}else{
            	    $response['statusCode'] = 200;
            	    $response['message'] ="No record found"; 
            	}
            }
            else if($page == 'productDetails'){
                include('../includes/conn.php');
                $productId = $_GET['id'];
                 $sql = "SELECT *
                        FROM tbl_products
                        WHERE id = '$productId' AND deleted='No'";
                $query = $conn->query($sql);
            	if(mysqli_num_rows($query)>0){
            	    $response['statusCode'] = 0;
            	    $response['message'] ="Success"; 
                	while($row =  $query->fetch_assoc())
                	{
                	    if($row['productImage'] == ''){
            	            $row['productImage'] = 'https://jafree.alitechbd.com/images/products/thumb/broken_image.png';
            	        }else{
            	            $row['productImage'] = 'https://jafree.alitechbd.com/images/products/thumb/'.$row['productImage'];
            	        }
                	    $sql_productSpec = "SELECT * FROM `tbl_print_book_spec_display` WHERE tbl_product_id='$productId' AND deleted='No' AND status='Active' ORDER BY id";
                        $query_productSpec = $conn->query($sql_productSpec);
                        while($row_productSpec =  $query_productSpec->fetch_assoc())
                	    {
                	       $row['spec'][] = $row_productSpec;
                	    }
                	    //$rows[] = $row;
                	    $productName = $row['productName'];
                	    $sql_SimilarProducts = "SELECT DISTINCT tbl_products.*, is_featured, tbl_print_book_product.id as book_product_id, tbl_brands.brandName, tbl_brands.brand_logo, tbl_category.categoryName, tbl_printbook_category.*
                            FROM tbl_print_book_product
                            INNER JOIN tbl_printbook_category on tbl_print_book_product.tbl_print_book_category_id=tbl_printbook_category.id
                            INNER JOIN tbl_printbook ON tbl_printbook_category.tbl_printbook_id = tbl_printbook.id
                            INNER JOIN tbl_brands ON tbl_printbook_category.tbl_brand_id = tbl_brands.id
                            INNER JOIN tbl_category ON tbl_printbook_category.tbl_category_id = tbl_category.id
                            INNER JOIN tbl_products ON tbl_print_book_product.tbl_product_id = tbl_products.id
                            WHERE tbl_products.deleted='No' AND tbl_print_book_product.status='Active' AND tbl_print_book_product.deleted='No' AND tbl_printbook_category.is_website='Yes' AND tbl_printbook_category.status='Active' AND 
                                    tbl_printbook_category.deleted='No' AND tbl_printbook.status='Active' AND tbl_printbook.deleted='No' AND tbl_products.productName like '%$productName%'";
                        $query_SimilarProducts = $conn->query($sql_SimilarProducts);
                        while($row_SimilarProducts =  $query_SimilarProducts->fetch_assoc())
                	    {
                	       $row['similarProducts'][] = $row_SimilarProducts;
                	    }
                	    $rows[] = $row;
                	}
                	$response['data'] =$rows;
                	mysqli_close($conn);
            	}else{
            	    $response['statusCode'] = 200;
            	    $response['message'] ="No record found"; 
            	}
            }
            else if($page == 'searchProducts'){
                include('../includes/conn.php');
                $searchParams = $_GET['searchText'];
                 $sql = "SELECT DISTINCT tbl_products.*, is_featured, tbl_print_book_product.id as book_product_id, tbl_brands.brandName, tbl_brands.brand_logo, tbl_category.categoryName
                            FROM tbl_print_book_product
                            INNER JOIN tbl_printbook_category on tbl_print_book_product.tbl_print_book_category_id=tbl_printbook_category.id
                            INNER JOIN tbl_printbook ON tbl_printbook_category.tbl_printbook_id = tbl_printbook.id
                            INNER JOIN tbl_brands ON tbl_printbook_category.tbl_brand_id = tbl_brands.id
                            INNER JOIN tbl_category ON tbl_printbook_category.tbl_category_id = tbl_category.id
                            INNER JOIN tbl_products ON tbl_print_book_product.tbl_product_id = tbl_products.id
                            InNER JOIN tbl_print_book_spec_display ON tbl_print_book_spec_display.tbl_product_id = tbl_products.id
                            WHERE tbl_products.deleted='No' AND tbl_print_book_product.status='Active' AND tbl_print_book_product.deleted='No' AND tbl_printbook_category.is_website='Yes' AND tbl_printbook_category.status='Active' AND 
                                    tbl_printbook_category.deleted='No' AND tbl_printbook.status='Active' AND tbl_printbook.deleted='No' AND 
                                    (tbl_products.productName like '%$searchParams%' OR tbl_brands.brandName like '%$searchParams%' OR tbl_category.categoryName like '%$searchParams%' OR tbl_products.modelNo like '%$searchParams%' OR 
                                    tbl_print_book_spec_display.spec_value like '%$searchParams%' OR tbl_print_book_spec_display.spec_name like '%$searchParams%')";
                $query = $conn->query($sql);
            	if(mysqli_num_rows($query)>0){
            	    $response['statusCode'] = 0;
            	    $response['message'] ="Success"; 
                	while($row =  $query->fetch_assoc())
                	{
                	    $productId = $row['id'];
                	    if($row['productImage'] == ''){
            	            $row['productImage'] = 'https://jafree.alitechbd.com/images/products/thumb/broken_image.png';
            	        }else{
            	            $row['productImage'] = 'https://jafree.alitechbd.com/images/products/thumb/'.$row['productImage'];
            	        }
                	    $sql_productSpec = "SELECT * FROM `tbl_print_book_spec_display` WHERE tbl_product_id='$productId' AND deleted='No' AND status='Active' ORDER BY id";
                        $query_productSpec = $conn->query($sql_productSpec);
                        while($row_productSpec =  $query_productSpec->fetch_assoc())
                	    {
                	       $row['spec'][] = $row_productSpec;
                	    }
                	    $rows[] = $row;
                	}
                	$response['data'] =$rows;
                	mysqli_close($conn);
            	}else{
            	    $response['statusCode'] = 200;
            	    $response['message'] ="No record found"; 
            	}
            }
            else{
                $response['statusCode'] = 201;
        	    $response['message'] ="Invalid parameters"; 
            	    //echo json_encode($response);
            }
        }else{
            $response['statusCode'] = 204;
    	    $response['message'] ="Credential mismatch"; 
        }
    }
    else if(isset($_POST['request'])){
        $page = $_POST['request'];
        $passCode = $_POST['passcode'];
        if($passCode == "JafreePostApi22"){
            if($page == 'updateWebsiteStatus'){
            	include('../includes/conn.php');
            	$id = $_POST['printbookId'];
            	$status = $_POST['status'];
            	$sql = "UPDATE tbl_printbook_category
                        SET tbl_printbook_category.is_website='$status'
                        WHERE tbl_printbook_category.id='$id' AND tbl_printbook_category.deleted = 'No' AND tbl_printbook_category.status='Active'";
                $conn->query($sql);
                $response['statusCode'] = 0;
        	    $response['message'] ="Success";
            }
            else if($page == 'updateProductFeaturedStatus'){
            	include('../includes/conn.php');
            	$id = $_POST['product_id'];
            	$featured = $_POST['featured'];
            	$sql = "UPDATE `tbl_print_book_product` SET is_featured='$featured' WHERE id='$id' AND deleted='No'";
                $conn->query($sql);
                $response['statusCode'] = 0;
        	    $response['message'] ="Success";
            }
            else if($page == 'updateSoleAgent'){
            	include('../includes/conn.php');
            	$id = $_POST['brand_id'];
            	$agent = $_POST['agent'];
            	$sql = "UPDATE `tbl_brands` SET is_agent='$agent' WHERE id='$id' AND deleted='No'";
                $conn->query($sql);
                $response['statusCode'] = 0;
        	    $response['message'] ="Success";
            }
            else if($page == 'updateCateloguePDFStatus'){
            	include('../includes/conn.php');
            	$id = $_POST['pdf_id'];
            	$status = $_POST['status'];
            	$sql = "UPDATE `tbl_pdf` SET is_website='$status' WHERE id='$id' AND deleted='No'";
                $conn->query($sql);
                $response['statusCode'] = 0;
        	    $response['message'] ="Success";
            }
            else{
            	$response['statusCode'] = 202;
        	    $response['message'] ="Invalid request"; 
                	    //echo json_encode($response);
            }
        }
        else{
            $response['statusCode'] = 204;
    	    $response['message'] ="Credential mismatch"; 
        }
    }
    else{
    	$response['statusCode'] = 202;
	    $response['message'] ="Invalid request"; 
        	    //echo json_encode($response);
    }
}else{
    $response['statusCode'] = 203;
    $response['message'] ="Security violation"; 
}
echo json_encode($response);
/*function response($order_id,$amount,$response_code,$response_desc){
	$response['order_id'] = $order_id;
	$response['amount'] = $amount;
	$response['response_code'] = $response_code;
	$response['response_desc'] = $response_desc;
	
	$json_response = json_encode($response);
	echo $json_response;
}*/
?>