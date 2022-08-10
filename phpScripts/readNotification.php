<?php
$conPrefix = '../';
include $conPrefix . 'includes/session.php';
date_default_timezone_set('Asia/Dhaka');
$toDay = (new DateTime($test))->format("Y-m-d H:i:s");
if(strtolower($_SESSION['userType']) == 'super admin' || strtolower($_SESSION['userType']) == 'admin coordinator' ){
    $sql = "SELECT * 
            FROM `tbl_notification`
            WHERE notify_for = 'createOrder' OR notify_for = 'confirmOrder'
            ORDER BY status, created_time DESC";
    $query = $conn->query($sql);
    $pending = 0;
    $databody = '';
    if($query->num_rows > 0){
    	while ($prow = $query->fetch_assoc()) {
            if($prow['status'] == "Pending"){
                $pending++;
                $databody .= '<li class="list-group-itemNotice">
                    <p>
                    <a href="'.$prow['notification_link'].'&notId='.$prow['id'].'">
                      <b>'.$prow['notification_title'].'</b><br>
                      <small>'.$prow['notification'].'</small>
                    </a>
                    </p>
                </li>';
            }else{
                $databody .= '<li class="list-group-itemNotice">
                    <p>
                    <a href="'.$prow['notification_link'].'&notId='.$prow['id'].'">
                      '.$prow['notification_title'].'<br>
                      <small>'.$prow['notification'].'</small>
                      </a>
                    </p>
                </li>';
            }		
    	}
    }else{
        $databody = 'No Notification';
    }
    $data = array('msg'=>'Success', 
                'notification'=>$databody,
                'pending'=>$pending);
	echo json_encode($data);
}else if(strtolower($_SESSION['userType']) == 'sales executive'){
    $sql = "SELECT * 
            FROM `tbl_notification`
            WHERE notify_for = 'checkOrder' 
            ORDER BY status, created_time DESC
            LIMIT 0, 5";
    $query = $conn->query($sql);
    $pending = 0;
    $databody = '';
    if($query->num_rows > 0){
    	while ($prow = $query->fetch_assoc()) {
            if($prow['status'] == "Pending"){
                $pending++;
                $databody .= '<li class="list-group-itemNotice">
                    <p>
                    <a href="'.$prow['notification_link'].'&notId='.$prow['id'].'">
                      <b>'.$prow['notification_title'].'</b><br>
                      <small>'.$prow['notification'].'</small>
                    </a>
                    </p>
                </li>';
            }else{
                $databody .= '<li class="list-group-itemNotice">
                    <p>
                    <a href="'.$prow['notification_link'].'&notId='.$prow['id'].'">
                      '.$prow['notification_title'].'<br>
                      <small>'.$prow['notification'].'</small>
                      </a>
                    </p>
                </li>';
            }		
    	}
    }else{
        $databody = 'No Notification';
        $pending = 0;
    }
    $data = array('msg'=>'Success', 
                'notification'=>$databody,
                'pending'=>$pending);
	echo json_encode($data);
}
else{
    $data = '<a class="dropdown-toggle" data-toggle="dropdown">Not else</a>
    <ul class="dropdown-menu">
    <!-- User image -->
    <li class="user-header">
    <p>
      a else
      <small>Body</small>
    </p>
    </li>
    <li class="user-header">
    <p>
      b else
      <small>Body B</small>
    </p>
    </li>';
    $data = array('msg'=>'Else', 
                'notification'=>$data,
                'pending'=>0);
    echo json_encode($data);
}
//echo $data;
?>