<?php
function resize($width,$path_100){
//$id=$_POST['id'];
	/*$id='';
	$sql="SELECT id FROM tbl_products ORDER BY `id` DESC limit 1 ";
	$query = $conn->query($sql);
		while ($prow = $query->fetch_assoc()) {
		$id=($row['id'])+1;
	}*/
							
	/* Get original image x y*/
	list($w, $h) = getimagesize($_FILES["file"]["tmp_name"]);
	/* calculate new image size with ratio */
	$aspect_ratio = $h/$w;
	$height = $aspect_ratio*$width;
	$ratio = min($width/$w, $height/$h);
	$h = ceil($height / $ratio);
	$x = ($w - $width / $ratio) / 2;
	$w = ceil($width / $ratio);
	/* new file name */
	//$path = '../uploads/thumb/'.$id.'_'.$_FILES["fileToUpload"]["name"];
	/* read binary data from image file */
	$imgString = file_get_contents($_FILES["file"]["tmp_name"]);
	/* create image from string */
	$image = imagecreatefromstring($imgString);
	$tmp = imagecreatetruecolor($width, $height);
	imagecopyresampled($tmp, $image,
  	0, 0,
  	$x, 0,
  	$width, $height,
  	$w, $h);
	/* Save image */
	switch ($_FILES["file"]['type']) {
		case 'image/jpeg':
			imagejpeg($tmp, $path_100, 200);
			break;
		case 'image/png':
			imagepng($tmp, $path_100, 0);
			break;
		case 'image/gif':
			imagegif($tmp, $path_100);
			break;
		default:
			exit;
			break;
	}
	return $path_100;
	/* cleanup memory */
	imagedestroy($image);
	imagedestroy($tmp);
}
function resize360($width,$path_360){
//$id=$_POST['id'];
	/*$sql="SELECT id FROM tbl_products ORDER BY `id` DESC limit 1 ";
	$query = $conn->query($sql);
		while ($prow = $query->fetch_assoc()) {
		$id=($row['id'])+1;
		}*/
	/* Get original image x y*/
	list($w, $h) = getimagesize($_FILES["file"]["tmp_name"]);
	/* calculate new image size with ratio */
	$aspect_ratio = $h/$w;
	$height = $aspect_ratio*$width;
	
	$ratio = min($width/$w, $height/$h);
	$h = ceil($height / $ratio);
	$x = ($w - $width / $ratio) / 2;
	$w = ceil($width / $ratio);
	/* new file name */
	//$path = '../uploads/big_product_img/'.$id.'_'.$_FILES["fileToUpload"]["name"];
	/* read binary data from image file */
	$imgString = file_get_contents($_FILES["file"]["tmp_name"]);
	/* create image from string */
	$image = imagecreatefromstring($imgString);
	$tmp = imagecreatetruecolor($width, $height);
	imagecopyresampled($tmp, $image,
  	0, 0,
  	$x, 0,
  	$width, $height,
  	$w, $h);
	/* Save image */
	switch ($_FILES["file"]['type']) {
		case 'image/jpeg':
			imagejpeg($tmp, $path_360, 100);
			break;
		case 'image/png':
			imagepng($tmp, $path_360, 0);
			break;
		case 'image/gif':
			imagegif($tmp, $path_360);
			break;
		default:
			exit;
			break;
	}
	return $path_360;
	/* cleanup memory */
	imagedestroy($image);
	imagedestroy($tmp);
}
?>