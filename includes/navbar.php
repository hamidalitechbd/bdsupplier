<header class="main-header">
    <!-- Logo -->
    <?php
    include 'timezone.php'; 
    $today = date('Y-m-d');
    
    if($_SESSION['userType'] != 'SALES EXECUTIVE' && strtolower($_SESSION['userType']) != "shop executive"){
        echo '<a href="user-home.php" class="logo">';
    }else{
        echo '<a href="home.php" class="logo">';
    }
    $sqlR = "SELECT COUNT(tbl_discount_offer.id) AS totalRemainder
            FROM `tbl_discount_offer` 
            WHERE REPLACE(tbl_discount_offer.remainder_date,'0000-00-00','2099-01-01') <= '$today' AND tbl_discount_offer.date_to >= '$today' AND tbl_discount_offer.deleted='No' AND tbl_discount_offer.status='Active' ";
                                
    $resultR = $conn->query($sqlR);
    $totalRemainder=0;
    while($row=$resultR->fetch_assoc()){
      $totalRemainder=$row['totalRemainder'];
    }
    ?>
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>BD</b>S</span>
      
      <span class="logo-lg"><img src="icons/bd12-small.png" style="width: 50px;" /><b>BD SUPPLIERS</b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- User Account: style can be found in dropdown.less -->
            <li style="border-left: 1px solid white;">
                <a href="discount-list-view.php">Offer % <i class="fa fa-bell-o"></i> <span id="" style="color: red;font-weight: 800;"><?=$totalRemainder?></span></a>
            </li>
            <li style="border-left: 1px solid white;">
                <a href="discountOfferAlert-view.php">Offer Alert <i class="fa fa-bell-o"></i> <span id="" style="color: red;font-weight: 800;"><?=$totalRemainder?></span></a>
            </li>
            <li class="dropdown"  style="border-left: 1px solid white;border-right: 1px solid white;">
              <a class="dropdown-toggle" data-toggle="dropdown">Order <i class="fa fa-bell-o"></i> <span id="UnReadNotify" style="color: red;font-weight: 800;"> </span></a>
                <ul class="dropdown-menu list-group-flush" id="notification" style="height: 400px;width: 320px;overflow-x: scroll;"></ul>
            </li>
            <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="<?php echo (!empty($user['images'])) ? 'images/'.$user['images'] : 'images/profile.jpg'; ?>" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php echo $user['fname'].' '.$user['lname']; ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img id="profileImageIcon" src="<?php echo (!empty($user['images'])) ? 'images/'.$user['images'] : 'images/profile.jpg'; ?>" class="img-circle" alt="User Image">

                <p>
                  <?php echo $user['fname'].' '.$user['lname']; ?>
                  <small>Member since <?php echo date('M. Y', strtotime($user['createdDate'])); ?></small>
                </p>
              </li>
              <li class="user-footer">
                <div class="pull-left">
                  <a href="#profile" data-toggle="modal" class="btn btn-default btn-flat" id="admin_profile">Change Password</a>
                </div>
                <div class="pull-right">
                  <a href="user/logout.php" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <?php 
    include 'includes/profile_modal.php'; 
    include 'includes/adminResetPassword-modal.php'; 
  ?>

  
  