<?php
  session_start();
  if(isset($_SESSION['user'])){
      if(strtolower($_SESSION['userType']) == 'sales executive' || strtolower($_SESSION['userType']) == "shop executive"){
          header('location:home.php');
      }else{
        header('location:user-home.php');
      }
  }
?>
<?php include 'header.php'; ?>
<?php include 'includes/conn.php'; ?>

<body class="hold-transition login-page">
<style>
	h2 {margin: 0;padding: 4px 0px;text-align: center;border-radius: 5px 5px 0 0;-webkit-border-radius: 5px 5px 0 0;
    color:#fff;font-size: 24px;text-transform: uppercase;font-weight: 300;font-family: 'Open Sans',sans-serif;border-bottom: 6px solid #02A0C7;
	}
</style>
<?php
	// init variables
	$min_number = 1;
	$max_number = 15;

	// generating random numbers
	$random_number1 = mt_rand($min_number, $max_number);
	$random_number2 = mt_rand($min_number, $max_number);
?>
<div class='container'>
<div class='row'>
<div class='col-md-12'><div class="col-lg-4"></div>
<div class="col-lg-5 login-box" style="border: 0.002px solid #ddd;padding: 0px;">
  	<div class="login-logo">
		<h2><img src='icons/bd-small.png' style="height: 70px;"></img></h2>
  		
  	</div>
  
  	<div class="login-box-body" style="background-color: #eaeaec;border: 1px solid #ccc;">
    	<p class="login-box-msg">User Login to start your session</p>

    	<form action="user/login.php" method="POST">
      		<div class="form-group has-feedback">
        		<input type="text" class="form-control" name="username" placeholder="input Username" required autofocus>
        		<span class="glyphicon glyphicon-user form-control-feedback"></span>
      		</div>
          <div class="form-group has-feedback">
            <input type="password" class="form-control" name="password" autocomplete="off" placeholder="input Password" required>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
		  
			<div class="form-group has-feedback">
				<?php if(isset($msg)){?>
				<ul>
				  <li colspan="2" align="center" valign="top"><?php echo $msg;?></li>
				</ul>
				<?php } ?>
			</div>
			
			    <h3 align="left" valign="top"> Validation code:  <b style="color: black;"><?php echo $random_number1 . ' + ' . $random_number2;?></b></h3><br>
			<div class="form-group has-feedback">  
			    <input name="captchaResult" class="form-control" type="text" size="2" placeholder="I'm not a robot !" required>
			    <span class="glyphicon glyphicon-log-in form-control-feedback"></span>

			    <input name="firstNumber" type="hidden" value="<?php echo $random_number1; ?>" />
			    <input name="secondNumber" type="hidden" value="<?php echo $random_number2; ?>" />
				<br>
				
			</div>
		  
      		<div class="row">
    			<div class="col-xs-12">
          			<button type="submit" style="background-color: #02A0C7;color: white;" class="btn btn-block btn-flat" name="login"><i class="fa fa-sign-in"></i> Sign In</button>
        		</div>
				<!--div class="col-xs-6">
          			<a href="admin" type="text" style="background-color: #586bf3;color: white;" class="btn btn-block btn-flat" name="login"><i class="fa fa-sign-in"></i> As Admin</a>
        		</div-->
				<h5 style="text-align: center;margin-top: 40px;color:#2b3254;"> ©&nbsp;Powered by <b style="font-variant: small-caps;">AliTechnology</b></h5>
      		</div>
    	</form>
  	</div>
	<?php
  		if(isset($_SESSION['error'])){
  			echo "
  				<div class='callout callout-danger text-center mt20'>
			  		<p>".$_SESSION['error']."</p> 
			  	</div>
  			";
  			unset($_SESSION['error']);
  		}
		?>
  	
</div>
</div>
</div>
</div>
<script type="text/javascript">
$(function() {
  var interval = setInterval(function() {
    var momentNow = moment();
    $('#date').html(momentNow.format('dddd').substring(0,3).toUpperCase() + ' - ' + momentNow.format('MMMM DD, YYYY'));  
    $('#time').html(momentNow.format('hh:mm:ss A'));
  }, 100);
});
</script>
<script type='text/javascript'>
function refreshCaptcha(){
	var img = document.images['captchaimg'];
	img.src = img.src.substring(0,img.src.lastIndexOf("?"))+"?rand="+Math.random()*1000;
}
</script>
</body>
</html>