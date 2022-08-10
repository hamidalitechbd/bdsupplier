<?php
$conPrefix = '../';
include $conPrefix . 'includes/session.php';

if (isset($_POST['action'])) {
    if($_POST['action'] == "updateProfile"){
        $username = $_POST['username'];
        $curr_password = $_POST['curr_password'];
        $password = $_POST['password'];
        $photo = $_FILES['file']['name'];
        echo json_encode($curr_password);
        
        if (password_verify($curr_password, $user['password'])) {
            if (!empty($photo)) {
                move_uploaded_file($_FILES['file']['tmp_name'], '../images/' . $photo);
                $filename = $photo;
            } else {
                $filename = $user['images'];
                
            }
            
            if (!empty($password)) {
                $password = $_POST['password'];
                $password = password_hash($password, PASSWORD_DEFAULT);
            } else {
                $password = $user['password'];
                
                
            }
            /*
            if ($password == $user['password']) {
                $password = $user['password'];
            } else {
                $password = password_hash($password, PASSWORD_DEFAULT);
            }*/
    
            $sql = "UPDATE tbl_users SET fname= '$username',password = '$password', images = '$filename' WHERE id = '" . $user['id'] . "'";
            if ($conn->query($sql)) {
                
                $_SESSION['success'] = 'User Password updated successfully';
            } else {
                $_SESSION['error'] = $conn->error;
            }
        } else {
            $_SESSION['error'] = 'Incorrect password';
        }
        
    }
} else {
    $_SESSION['error'] = 'Illegal use.';
}

echo json_encode($_SESSION['success'].$_SESSION['error']);

//header( 'Location: ../user-home.php' );

?>